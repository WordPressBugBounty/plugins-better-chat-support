<?php

namespace ThemeAtelier\BetterChatSupport\Admin\Analytics;

use WP_Error;
use WP_REST_Request;
use WP_REST_Response;

if (! defined('ABSPATH')) {
    die;
}

/**
 * Event collection.
 *
 * The browser batches a page's events into one navigator.sendBeacon()
 * request, sent about 5 seconds after the first event or when the page is
 * hidden, so a page view normally costs one request. A Messenger click is
 * sent at once, since the visitor is usually leaving. The endpoint validates everything in
 * PHP first, then runs exactly one query: a single multi-row INSERT.
 */
// phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, PluginCheck.Security.DirectDB.UnescapedDBParameter -- Insert into the plugin's own table; values go through prepare().
class Tracker
{
    const TYPES          = ['visitor', 'view', 'conversion'];
    const DEVICES        = ['desktop', 'mobile', 'other'];
    const MAX_EVENTS     = 20;
    const MAX_CONVERSION = 5;
    const MAX_BODY       = 8192;

    const BOT_PATTERN = '/bot\b|bot\/|crawl|spider|slurp|mediapartners|facebookexternalhit|bingpreview|headless|phantomjs|puppeteer|playwright|selenium|lighthouse|pagespeed|gtmetrix|pingdom|uptimerobot|statuscake|site24x7|curl\/|wget|python-|go-http-client|java\/|okhttp|axios|node-fetch|libwww|httpclient|scrapy|ahrefs|semrush|mj12|petalbot|bytespider|gptbot|claudebot|ccbot/i';

    /** @var array|null Request body parsed in the permission check. */
    private static $parsed = null;

    /* ── Token ───────────────────────────────────────────────────────── */

    /**
     * A site-wide token printed into the page. It is not per user (the visitor
     * is anonymous and the page may come from a page cache), so it only stops
     * clients that never loaded a page. A token is valid for 2–3 weeks, longer
     * than any normal page-cache lifetime.
     */
    public static function token(int $weeks_ago = 0): string
    {
        $week = (int) floor(time() / WEEK_IN_SECONDS) - $weeks_ago;
        return substr(hash_hmac('sha256', 'mcs-analytics|' . $week . '|' . home_url('/'), wp_salt('nonce')), 0, 20);
    }

    private static function token_ok($token): bool
    {
        if (! is_string($token) || $token === '') {
            return false;
        }
        for ($i = 0; $i <= 2; $i++) {
            if (hash_equals(self::token($i), $token)) {
                return true;
            }
        }
        return false;
    }

    /* ── Permission check (runs before the callback, no DB access) ───── */

    public static function permission(WP_REST_Request $request)
    {
        if (! self::same_origin()) {
            return new WP_Error('mcs_forbidden', 'Cross-origin tracking is not allowed.', ['status' => 403]);
        }

        $body = (string) $request->get_body();
        if ($body === '' || strlen($body) > self::MAX_BODY) {
            return new WP_Error('mcs_bad_request', 'Invalid payload.', ['status' => 400]);
        }
        $data = json_decode($body, true);
        if (! is_array($data)) {
            return new WP_Error('mcs_bad_request', 'Invalid payload.', ['status' => 400]);
        }

        if (isset($data['e'])) {
            if (! self::token_ok($data['t'] ?? '')) {
                return new WP_Error('mcs_forbidden', 'Invalid token.', ['status' => 403]);
            }
        } elseif (! self::legacy_allowed()) {
            return new WP_Error('mcs_forbidden', 'Invalid token.', ['status' => 403]);
        }

        self::$parsed = $data;
        return true;
    }

    /** The 2.6.x script sent one event per request, without a token. */
    private static function legacy_allowed(): bool
    {
        $until = get_option(Schema::OPTION_LEGACY_UNTIL); // Autoloaded.
        return $until === false || time() < (int) $until;
    }

    private static function normalize_host($host): string
    {
        $host = strtolower((string) $host);
        return strpos($host, 'www.') === 0 ? substr($host, 4) : $host;
    }

    /** Browsers send Origin on every POST, sendBeacon included. */
    private static function same_origin(): bool
    {
        $source = isset($_SERVER['HTTP_ORIGIN']) ? sanitize_text_field(wp_unslash($_SERVER['HTTP_ORIGIN'])) : '';
        if ($source === '' || $source === 'null') {
            // Privacy modes and sandboxed frames send "Origin: null".
            $source = isset($_SERVER['HTTP_REFERER']) ? esc_url_raw(wp_unslash($_SERVER['HTTP_REFERER'])) : '';
        }
        $host = self::normalize_host(wp_parse_url($source, PHP_URL_HOST));
        if ($host === '') {
            return false;
        }
        $allowed = [
            self::normalize_host(wp_parse_url(home_url(), PHP_URL_HOST)),
            self::normalize_host(wp_parse_url(site_url(), PHP_URL_HOST)),
        ];
        $allowed = (array) apply_filters('better_chat_support_analytics_allowed_hosts', $allowed);
        return in_array($host, array_map([__CLASS__, 'normalize_host'], $allowed), true);
    }

    /* ── Callback ────────────────────────────────────────────────────── */

    public static function handle(WP_REST_Request $request)
    {
        $data   = self::$parsed ?? json_decode((string) $request->get_body(), true);
        $legacy = is_array($data) && ! isset($data['e']);
        $done   = $legacy
            ? new WP_REST_Response(['success' => true], 200)
            : new WP_REST_Response(null, 204);

        if (! is_array($data)) {
            return new WP_REST_Response(['success' => false], 400);
        }

        $ua = isset($_SERVER['HTTP_USER_AGENT']) ? sanitize_text_field(wp_unslash($_SERVER['HTTP_USER_AGENT'])) : '';
        if ((bool) apply_filters('better_chat_support_analytics_is_bot', $ua === '' || preg_match(self::BOT_PATTERN, $ua), $ua)) {
            return $done; // Silently dropped.
        }

        if (! RateLimiter::allow()) {
            return new WP_REST_Response(['success' => false], 429);
        }

        $rows = $legacy ? self::rows_from_legacy($data, $ua) : self::rows_from_batch($data, $ua);
        if ($rows === null) {
            return new WP_REST_Response(['success' => false], 400);
        }
        if ($rows) {
            self::insert($rows);
        }
        return $done;
    }

    private static function rows_from_batch(array $data, string $ua): ?array
    {
        if (! is_array($data['e']) || ! $data['e']) {
            return null;
        }
        $session = self::clean_session($data['s'] ?? '');
        $device  = self::clean_device($data['d'] ?? '');
        $page    = self::clean_path($data['p'] ?? '');
        $country = self::detect_country();
        $browser = self::detect_browser($ua);

        $rows        = [];
        $seen        = [];
        $conversions = 0;
        foreach (array_slice($data['e'], 0, self::MAX_EVENTS) as $event) {
            if (! is_array($event) || ! isset($event[0]) || ! in_array($event[0], self::TYPES, true)) {
                continue;
            }
            $type   = $event[0];
            $widget = isset($event[1]) ? absint($event[1]) : 0;
            $path   = isset($event[2]) ? self::clean_path($event[2]) : $page;

            if ($type === 'conversion') {
                if (++$conversions > self::MAX_CONVERSION) {
                    continue;
                }
            } else {
                // One visitor per batch, one view per widget and page.
                $key = $type === 'visitor' ? 'visitor' : "view|$widget|$path";
                if (isset($seen[$key])) {
                    continue;
                }
                $seen[$key] = true;
            }
            $rows[] = [$widget, $type, $device, $country, $browser, $path, $session];
        }
        return $rows;
    }

    private static function rows_from_legacy(array $data, string $ua): ?array
    {
        $type = $data['event_type'] ?? '';
        if (! in_array($type, self::TYPES, true)) {
            return null;
        }
        return [[
            absint($data['widget_id'] ?? 0),
            $type,
            self::clean_device($data['device_type'] ?? ''),
            self::detect_country(),
            self::detect_browser($ua),
            self::clean_path($data['page_url'] ?? ''),
            self::clean_session($data['session_id'] ?? ''),
        ]];
    }

    private static function insert(array $rows): void
    {
        global $wpdb;
        $table  = Schema::raw_table();
        $values = [];
        foreach ($rows as $row) {
            array_push($values, ...$row);
        }
        $placeholders = implode(',', array_fill(0, count($rows), '(%d,%s,%s,%s,%s,%s,%s)'));
        // $wpdb->query(), not $wpdb->insert(): insert() first runs
        // SHOW FULL COLUMNS to check charsets, a second query per request.
        $wpdb->query($wpdb->prepare(
            "INSERT INTO `$table` (widget_id, event_type, device_type, country, browser, page_url, session_id) VALUES $placeholders",
            $values
        ));
    }

    private static function clean_session($value): string
    {
        return is_string($value) ? substr(preg_replace('/[^A-Za-z0-9_-]/', '', $value), 0, 64) : '';
    }

    private static function clean_device($value): string
    {
        return in_array($value, self::DEVICES, true) ? $value : 'desktop';
    }

    private static function clean_path($value): string
    {
        if (! is_string($value) || $value === '' || $value[0] !== '/') {
            return '';
        }
        return substr(esc_url_raw(substr($value, 0, 500)), 0, 500);
    }

    private static function detect_country(): string
    {
        foreach (['HTTP_CF_IPCOUNTRY', 'HTTP_X_COUNTRY_CODE', 'HTTP_GEOIP_COUNTRY_CODE'] as $h) {
            $val = isset($_SERVER[$h]) ? sanitize_text_field(wp_unslash($_SERVER[$h])) : '';
            if ($val !== '' && preg_match('/^[A-Za-z]{2,3}$/', $val)) {
                return strtoupper($val);
            }
        }
        return '';
    }

    private static function detect_browser(string $ua): string
    {
        if (stripos($ua, 'Edg') !== false)            return 'Edge';
        if (stripos($ua, 'OPR') !== false)            return 'Opera';
        if (stripos($ua, 'YaBrowser') !== false)      return 'Yandex';
        if (stripos($ua, 'SamsungBrowser') !== false) return 'Samsung';
        if (stripos($ua, 'Chrome') !== false)         return 'Chrome';
        if (stripos($ua, 'Firefox') !== false)        return 'Firefox';
        if (stripos($ua, 'Safari') !== false)         return 'Safari';
        if (stripos($ua, 'MSIE') !== false || stripos($ua, 'Trident') !== false) return 'IE';
        return 'Other';
    }

    /* ── Front-end script ────────────────────────────────────────────── */

    public static function should_track_current_user(): bool
    {
        if (is_user_logged_in() && current_user_can('edit_posts') && ! Settings::track_editors()) {
            return false;
        }
        return ! is_customize_preview();
    }

    public static function print_script(): void
    {
        if (! self::should_track_current_user()) {
            return;
        }
        $config = wp_json_encode([
            'u' => esc_url_raw(rest_url('better-chat-support/v1/analytics/track')),
            't' => self::token(),
        ]);
        ?>
<script id="mcs-analytics">(function(){
var cfg=<?php echo $config; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_json_encode() of an esc_url_raw() URL and a hex token. ?>,nav=navigator,ua=nav.userAgent||"";
/* Bots and automated browsers are not visitors. */
if(nav.webdriver||/bot\b|bot\/|crawl|spider|slurp|headless|lighthouse|pingdom|phantom|gtmetrix|pagespeed/i.test(ua))return;
if(!nav.sendBeacon&&!window.fetch)return;
var device=/tablet|ipad|playbook|silk/i.test(ua)?"other":(/mobile|android|iphone|ipod|blackberry|iemobile|opera mini/i.test(ua)?"mobile":"desktop");
/* sessionStorage throws in some privacy modes; tracking carries on without it. */
function store(k,v){try{if(v===undefined)return sessionStorage.getItem(k);if(v===null)sessionStorage.removeItem(k);else sessionStorage.setItem(k,v);}catch(e){}return null;}
var sid=store("mcs_sid");if(!sid){sid=Math.random().toString(36).slice(2)+Date.now().toString(36);store("mcs_sid",sid);}
var path=location.pathname,q=[],timer=null;
/* Batching: a page's events go out together about 5 s after the first one (or earlier, if the page is hidden or left), normally one request per page view. A Messenger click is sent at once: the visitor is usually about to leave. Anything queued later is sent when the page is hidden. */
function add(type,w){if(q.length>=40)return;q.push([type,+w||0,path]);if(type==="conversion")flush();else if(!timer)timer=setTimeout(flush,5000);}
if(!store("mcs_visited")){store("mcs_visited","1");add("visitor",0);}
/* Conversion = a click that actually goes to Messenger: an m.me/messenger.com link, or a chat control that wraps one. The floating bubble only opens/closes the popup. */
var MSG='a[href*="m.me/"],a[href*="messenger.com/"]';
document.addEventListener("click",function(e){var t=e.target;if(!t||!t.closest)return;var hit=t.closest(MSG);if(!hit){var b=t.closest(".better_chat_support_link,.mSupport__send-message,.better_chat_support_multi_user,.mSupport_button,.chat-link");if(b&&b.querySelector(MSG))hit=b;}if(hit)add("conversion",0);},{passive:true});
/* View = the floating bubble scrolling into sight, once per page. */
function setupViews(){var els=document.querySelectorAll(".mSupport_bubble .mSupport_button");if(!els.length||!("IntersectionObserver"in window))return;var o=new IntersectionObserver(function(en){en.forEach(function(x){if(x.isIntersecting){add("view",x.target.getAttribute("data-widget-id"));o.unobserve(x.target);}});},{threshold:0.5});els.forEach(function(el){o.observe(el);});}
if(document.readyState==="loading")document.addEventListener("DOMContentLoaded",setupViews);else setupViews();
/* sendBeacon survives the page closing or navigating to Messenger; fetch keepalive is the fallback. */
function flush(){if(timer){clearTimeout(timer);timer=null;}if(!q.length)return;
var body=JSON.stringify({t:cfg.t,s:sid,d:device,p:path,e:q.slice(0,20)});q=[];
try{if(nav.sendBeacon&&nav.sendBeacon(cfg.u,new Blob([body],{type:"text/plain"})))return;}catch(e){}
try{fetch(cfg.u,{method:"POST",keepalive:true,credentials:"same-origin",headers:{"Content-Type":"text/plain"},body:body}).catch(function(){});}catch(e){}}
document.addEventListener("visibilitychange",function(){if(document.visibilityState==="hidden")flush();});
window.addEventListener("pagehide",flush);
/* Back/forward cache: a restored page is a new view of it. */
window.addEventListener("pageshow",function(e){if(e.persisted&&document.querySelector(".mSupport_bubble .mSupport_button"))add("view",0);});
})();</script>
<?php
    }
}
