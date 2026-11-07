<?php

namespace ThemeAtelier\BetterChatSupport\Admin\Analytics;

if (! defined('ABSPATH')) {
    die;
}

/**
 * Dashboard reports. Reads only the daily summary tables (never the raw
 * events) and caches each result for 10 minutes, or until the summaries
 * change, whichever comes first. When the range includes today and new
 * events have arrived, today's summary is rebuilt first (at most every 3 s).
 *
 * Response shapes match 2.6.x, so the old endpoints keep working.
 */
// phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, PluginCheck.Security.DirectDB.UnescapedDBParameter -- The plugin's own summary tables; results are cached in a transient.
class Reports
{
    const CACHE_TTL = 10 * MINUTE_IN_SECONDS;
    const PERIODS   = ['this_week', 'last_week', 'this_month', 'last_month', 'this_year', 'last_year', 'custom'];
    const TYPE_KEYS = ['visitors', 'views', 'conversions'];

    /** All four dashboard sections in one response. */
    public static function dashboard(array $params): array
    {
        $q     = self::normalize($params);
        $today = Aggregator::today();
        // Ranges that include today get today's summary rebuilt first when new
        // events have arrived (at most every 3 s); older days change in cron runs.
        $state   = ($q['from'] <= $today && $today <= $q['to']) ? Aggregator::refresh_today(3) : Schema::state();
        $updated = max((int) ($state['last_run'] ?? 0), (int) ($state['today_at'] ?? 0));
        $key     = 'mcs_an_' . md5(wp_json_encode([$q, $updated, Schema::DB_VERSION]));

        $cached = get_transient($key);
        if (is_array($cached)) {
            return $cached;
        }

        $where = self::where($q);
        $data  = [
            'summary' => self::summary($where),
            'chart'   => self::chart($q, $where),
            'devices' => self::devices($where),
            'tables'  => self::tables($q),
            'meta'    => [
                'from'         => $q['from'],
                'to'           => $q['to'],
                'last_updated' => $updated ?: null,
                // Old data is still being rolled up after an upgrade.
                'preparing'    => ($state['raw_indexes'] ?? '') !== 'done' || ($state['backfill'] ?? 'pending') !== 'done',
            ],
        ];
        set_transient($key, $data, self::CACHE_TTL);
        return $data;
    }

    private static function normalize(array $params): array
    {
        $period = in_array($params['period'] ?? '', self::PERIODS, true) ? $params['period'] : 'this_week';
        $widget = $params['widget_id'] ?? 'all';
        $widget = ($widget !== 'all' && is_numeric($widget)) ? absint($widget) : 'all';

        [$from, $to] = self::resolve_period($period, (string) ($params['date_from'] ?? ''), (string) ($params['date_to'] ?? ''));
        return ['period' => $period, 'from' => $from, 'to' => $to, 'widget' => $widget];
    }

    private static function where(array $q): string
    {
        global $wpdb;
        $where = $wpdb->prepare('day BETWEEN %s AND %s', $q['from'], $q['to']);
        if ($q['widget'] !== 'all') {
            $where .= $wpdb->prepare(' AND widget_id = %d', $q['widget']);
        }
        return $where;
    }

    private static function summary(string $where): array
    {
        global $wpdb;
        $daily = Schema::daily_table();
        $row   = $wpdb->get_row(
            "SELECT SUM(visitors) AS visitors, SUM(views) AS views, SUM(conversions) AS conversions,
                SUM(converted_sessions) AS converted
            FROM `$daily` WHERE $where"
        );
        $visitors  = (int) ($row->visitors ?? 0);
        $converted = (int) ($row->converted ?? 0);
        return [
            'visitors'        => $visitors,
            'views'           => (int) ($row->views ?? 0),
            'conversions'     => (int) ($row->conversions ?? 0),
            // Share of visits that clicked through to Messenger.
            'conversion_rate' => $visitors > 0 ? min(100, round(($converted / $visitors) * 100, 1)) : 0,
        ];
    }

    private static function chart(array $q, string $where): array
    {
        global $wpdb;
        $daily    = Schema::daily_table();
        $by_month = in_array($q['period'], ['this_year', 'last_year'], true);
        $bucket   = $by_month ? 'LEFT(day, 7)' : 'day';

        $rows = $wpdb->get_results(
            "SELECT $bucket AS k, SUM(visitors) AS visitors, SUM(views) AS views, SUM(conversions) AS conversions
            FROM `$daily` WHERE $where GROUP BY k"
        );

        $labels   = self::label_range($q['from'], $q['to'], $by_month);
        $index    = array_flip($labels);
        $datasets = array_fill_keys(self::TYPE_KEYS, array_fill(0, count($labels), 0));
        foreach ($rows ?: [] as $row) {
            if (! isset($index[$row->k])) {
                continue;
            }
            foreach (self::TYPE_KEYS as $type) {
                $datasets[$type][$index[$row->k]] = (int) $row->$type;
            }
        }
        return ['labels' => $labels, 'datasets' => $datasets];
    }

    private static function devices(string $where): array
    {
        global $wpdb;
        $daily  = Schema::daily_table();
        $rows   = $wpdb->get_results(
            "SELECT device_type, SUM(visitors) AS visitors, SUM(views) AS views, SUM(conversions) AS conversions
            FROM `$daily` WHERE $where GROUP BY device_type"
        );
        $empty  = array_fill_keys(self::TYPE_KEYS, 0);
        $result = ['desktop' => $empty, 'mobile' => $empty, 'other' => $empty];
        foreach ($rows ?: [] as $row) {
            $dev = isset($result[$row->device_type]) ? $row->device_type : 'other';
            foreach (self::TYPE_KEYS as $type) {
                $result[$dev][$type] += (int) $row->$type;
            }
        }
        return $result;
    }

    private static function tables(array $q): array
    {
        global $wpdb;
        $dims = Schema::dims_table();
        $out  = [];
        foreach (['countries' => 'country', 'pages' => 'page', 'browsers' => 'browser'] as $key => $dim) {
            $where = $wpdb->prepare('dim = %s AND day BETWEEN %s AND %s', $dim, $q['from'], $q['to']);
            if ($q['widget'] !== 'all') {
                $where .= $wpdb->prepare(' AND widget_id = %d', $q['widget']);
            }
            $rows = $wpdb->get_results(
                "SELECT dim_value AS label, SUM(views) AS views, SUM(conversions) AS conversions
                FROM `$dims` WHERE $where
                GROUP BY dim_value ORDER BY views DESC, conversions DESC, label ASC LIMIT 10"
            );
            $out[$key] = array_map(function ($row) {
                return [
                    'label'       => $row->label,
                    'views'       => (int) $row->views,
                    'conversions' => (int) $row->conversions,
                ];
            }, $rows ?: []);
        }
        return $out;
    }

    private static function valid_date(string $date): bool
    {
        if (! preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $date, $m)) {
            return false;
        }
        return checkdate((int) $m[2], (int) $m[3], (int) $m[1]);
    }

    /** Local-date range for a period. Same rules as 2.6.x. */
    private static function resolve_period(string $period, string $date_from, string $date_to): array
    {
        $now = current_time('timestamp');

        switch ($period) {
            case 'last_week':
                $dow         = (int) gmdate('N', $now);
                $last_monday = $now - ($dow - 1) * DAY_IN_SECONDS - 7 * DAY_IN_SECONDS;
                return [gmdate('Y-m-d', $last_monday), gmdate('Y-m-d', $last_monday + 6 * DAY_IN_SECONDS)];
            case 'this_month':
                return [gmdate('Y-m-01', $now), gmdate('Y-m-t', $now)];
            case 'last_month':
                $last = gmmktime(0, 0, 0, (int) gmdate('m', $now), 1, (int) gmdate('Y', $now)) - DAY_IN_SECONDS;
                return [gmdate('Y-m-01', $last), gmdate('Y-m-t', $last)];
            case 'this_year':
                return [gmdate('Y-01-01', $now), gmdate('Y-12-31', $now)];
            case 'last_year':
                $year = (int) gmdate('Y', $now) - 1;
                return ["{$year}-01-01", "{$year}-12-31"];
            case 'custom':
                $from = self::valid_date($date_from) ? $date_from : gmdate('Y-m-d', $now - 6 * DAY_IN_SECONDS);
                $to   = self::valid_date($date_to) ? $date_to : gmdate('Y-m-d', $now);
                if ($from > $to) {
                    [$from, $to] = [$to, $from];
                }
                // Keep the chart to at most ~10 years of daily labels.
                $min = gmdate('Y-m-d', strtotime($to . ' UTC') - 3660 * DAY_IN_SECONDS);
                return [max($from, $min), $to];
            default: // this_week
                $dow = (int) gmdate('N', $now);
                return [gmdate('Y-m-d', $now - ($dow - 1) * DAY_IN_SECONDS), gmdate('Y-m-d', $now + (7 - $dow) * DAY_IN_SECONDS)];
        }
    }

    private static function label_range(string $from, string $to, bool $by_month): array
    {
        $labels = [];
        $start  = strtotime($from . ' UTC');
        $end    = strtotime($to . ' UTC');
        if ($by_month) {
            while ($start <= $end) {
                $labels[] = gmdate('Y-m', $start);
                $start    = gmmktime(0, 0, 0, (int) gmdate('m', $start) + 1, 1, (int) gmdate('Y', $start));
            }
        } else {
            while ($start <= $end) {
                $labels[] = gmdate('Y-m-d', $start);
                $start   += DAY_IN_SECONDS;
            }
        }
        return $labels;
    }
}
