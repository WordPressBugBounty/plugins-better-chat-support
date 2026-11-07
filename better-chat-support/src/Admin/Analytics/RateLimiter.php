<?php

namespace ThemeAtelier\BetterChatSupport\Admin\Analytics;

if (! defined('ABSPATH')) {
    die;
}

/**
 * Fixed-window rate limit for the tracking endpoint that never touches the
 * database. Counters live in the persistent object cache when there is one,
 * else APCu, else a tiny counter file per client in the system temp dir.
 * If none is writable the request is allowed (fail open: analytics must
 * never break the site).
 *
 * A real visitor sends at most one request per page view, so the default
 * of 60 requests per client per minute only stops floods.
 */
// phpcs:disable WordPress.WP.AlternativeFunctions -- Hot-path counter with flock(); WP_Filesystem has no locking and may need credentials.
class RateLimiter
{
    const GROUP  = 'mcs_analytics_rl';
    const WINDOW = 60;

    public static function allow(): bool
    {
        $per_client = (int) apply_filters('better_chat_support_analytics_rate_limit', 60);
        $global     = (int) apply_filters('better_chat_support_analytics_global_rate_limit', 6000);
        $bucket     = (int) floor(time() / self::WINDOW);

        if ($per_client > 0 && ! self::hit('c' . self::client_key(), $bucket, $per_client)) {
            return false;
        }
        // Site-wide ceiling: protects the database even if the per-client key
        // is evaded (rotating IPs or spoofed proxy headers).
        if ($global > 0 && ! self::hit('global', $bucket, $global)) {
            return false;
        }
        return true;
    }

    /**
     * Key for the visitor. REMOTE_ADDR alone would put every visitor of a
     * site behind Cloudflare or a load balancer into one bucket, so the
     * forwarded client IP is added. Hashed: no IP is stored anywhere.
     */
    private static function client_key(): string
    {
        // Every proxy header is part of the key: whichever one the site's
        // proxy sets tells its visitors apart.
        $parts = [];
        foreach (['REMOTE_ADDR', 'HTTP_CF_CONNECTING_IP', 'HTTP_X_REAL_IP', 'HTTP_X_FORWARDED_FOR'] as $header) {
            $parts[] = isset($_SERVER[$header]) ? trim(explode(',', sanitize_text_field(wp_unslash($_SERVER[$header])))[0]) : '';
        }
        $key = apply_filters('better_chat_support_analytics_client_key', implode('|', $parts));
        return substr(md5($key . wp_salt('nonce')), 0, 20);
    }

    private static function hit(string $key, int $bucket, int $limit): bool
    {
        $name = $key . ':' . $bucket;

        if (wp_using_ext_object_cache()) {
            wp_cache_add($name, 0, self::GROUP, self::WINDOW * 2);
            $n = wp_cache_incr($name, 1, self::GROUP);
            return $n === false || $n <= $limit;
        }

        if (function_exists('apcu_enabled') && apcu_enabled()) {
            $success = false;
            $n       = apcu_inc(self::GROUP . ':' . md5(home_url()) . ':' . $name, 1, $success, self::WINDOW * 2);
            return $n === false || $n <= $limit;
        }

        return self::file_hit($key, $bucket, $limit);
    }

    private static function dir(): string
    {
        return trailingslashit(get_temp_dir()) . 'bcs-rl-' . substr(md5(home_url() . wp_salt('nonce')), 0, 12);
    }

    private static function file_hit(string $key, int $bucket, int $limit): bool
    {
        $dir = self::dir();
        if (! is_dir($dir) && ! @mkdir($dir, 0700)) { // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged -- Unwritable temp dir: fail open.
            return true;
        }
        $fh = @fopen($dir . '/' . md5($key) . '.cnt', 'c+'); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged -- Fail open.
        if (! $fh) {
            return true;
        }
        $count = 1;
        if (flock($fh, LOCK_EX)) {
            $data = explode(':', (string) stream_get_contents($fh));
            if (count($data) === 2 && (int) $data[0] === $bucket) {
                $count = (int) $data[1] + 1;
            }
            ftruncate($fh, 0);
            rewind($fh);
            fwrite($fh, $bucket . ':' . $count);
            fflush($fh);
            flock($fh, LOCK_UN);
        }
        fclose($fh);
        return $count <= $limit;
    }

    /** Hourly cron: remove counter files from past windows. */
    public static function cleanup_files(): void
    {
        $dir = self::dir();
        if (! is_dir($dir)) {
            return;
        }
        $cutoff = time() - 10 * MINUTE_IN_SECONDS;
        $n      = 0;
        foreach ((array) glob($dir . '/*.cnt') as $file) {
            if (@filemtime($file) < $cutoff) { // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged -- File may vanish between glob() and stat.
                wp_delete_file($file);
            }
            if (++$n >= 20000) {
                break;
            }
        }
    }
}
