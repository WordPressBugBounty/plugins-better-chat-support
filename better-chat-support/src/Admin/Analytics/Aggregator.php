<?php

namespace ThemeAtelier\BetterChatSupport\Admin\Analytics;

if (! defined('ABSPATH')) {
    die;
}

/**
 * Background jobs: roll raw events up into daily summaries, backfill history
 * after an upgrade, and delete raw events past the retention window.
 *
 * Days are the site's local days (Settings → General → Timezone). Each day is
 * rebuilt from scratch, so a run can be repeated safely.
 */
// phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, PluginCheck.Security.DirectDB.UnescapedDBParameter -- The plugin's own tables; names are $wpdb->prefix plus a constant, values go through prepare().
class Aggregator
{
    const HOOK_AGGREGATE = 'mcs_analytics_aggregate';
    const HOOK_CLEANUP   = 'mcs_analytics_cleanup';
    const INTERVAL       = 'mcs_analytics_15min';

    /** Seconds one cron run may spend before handing over to the next. */
    const TIME_BUDGET = 20;

    const DELETE_BATCH       = 2000;
    const DELETE_MAX_BATCHES = 50;

    /** Per day, keep the top N pages; the long tail never reaches a top-10 list. */
    const PAGE_ROWS_PER_DAY = 500;

    public static function cron_schedules($schedules)
    {
        $schedules[self::INTERVAL] = [
            'interval' => 15 * MINUTE_IN_SECONDS,
            'display'  => 'Every 15 minutes (Better Chat Support analytics)',
        ];
        return $schedules;
    }

    /** Cheap: reads the autoloaded cron option, writes only when an event is missing. */
    public static function ensure_scheduled(): void
    {
        if (! Schema::is_current()) {
            // Not migrated yet: one aggregate run will do the upgrade.
            if (! wp_next_scheduled(self::HOOK_AGGREGATE)) {
                wp_schedule_event(time(), self::INTERVAL, self::HOOK_AGGREGATE);
            }
            return;
        }
        if (! wp_next_scheduled(self::HOOK_AGGREGATE)) {
            wp_schedule_event(time() + MINUTE_IN_SECONDS, self::INTERVAL, self::HOOK_AGGREGATE);
        }
        if (! wp_next_scheduled(self::HOOK_CLEANUP)) {
            wp_schedule_event(time() + 5 * MINUTE_IN_SECONDS, 'hourly', self::HOOK_CLEANUP);
        }
    }

    public static function unschedule(): void
    {
        foreach ([self::HOOK_AGGREGATE, self::HOOK_CLEANUP, Schema::HOOK_MIGRATE] as $hook) {
            wp_unschedule_hook($hook);
        }
    }

    /* ── Dates ───────────────────────────────────────────────────────── */

    public static function today(): string
    {
        return wp_date('Y-m-d');
    }

    public static function shift_day(string $day, int $days): string
    {
        return gmdate('Y-m-d', strtotime($day . ' 12:00:00 UTC') + $days * DAY_IN_SECONDS);
    }

    /** Unix timestamps of local midnight at the start and end of $day. */
    public static function day_bounds(string $day): array
    {
        $start = new \DateTimeImmutable($day . ' 00:00:00', wp_timezone());
        return [$start->getTimestamp(), $start->modify('+1 day')->getTimestamp()];
    }

    /* ── Aggregation ─────────────────────────────────────────────────── */

    /** Cron callback. The optional argument only tells continuation runs apart. */
    public static function cron_aggregate($unused = ''): void
    {
        self::aggregate(self::TIME_BUDGET);
    }

    /**
     * Rebuild today, any finished days not yet rolled up, and (after an
     * upgrade) older history, until the time budget runs out.
     *
     * @return array State after the run.
     */
    public static function aggregate(float $budget): array
    {
        Schema::maybe_upgrade();
        $state = Schema::state();
        if (($state['raw_indexes'] ?? '') === 'pending') {
            // Normally done by its own one-off event; retried here so a lost
            // or failed event can never leave the upgrade unfinished.
            Schema::run_raw_migration();
            $state = Schema::state();
        }
        if (($state['raw_indexes'] ?? '') !== 'done') {
            return $state;
        }
        if (! Lock::acquire('aggregate', 10 * MINUTE_IN_SECONDS)) {
            return $state;
        }

        $deadline = microtime(true) + $budget;
        $today    = self::today();

        // Forward: from the first day not finalised up to today.
        $day = isset($state['done_through']) ? self::shift_day($state['done_through'], 1) : $today;
        while ($day <= $today) {
            self::aggregate_day($day);
            if ($day < $today) {
                $state['done_through'] = $day;
            }
            $day = self::shift_day($day, 1);
            if (microtime(true) > $deadline) {
                break;
            }
        }

        // Backward: history that existed before the upgrade.
        if (($state['backfill'] ?? 'done') === 'pending') {
            $state = self::init_backfill($state);
        }
        while (($state['backfill'] ?? 'done') === 'running' && microtime(true) < $deadline) {
            $day = $state['backfill_cursor'];
            if ($day < $state['backfill_min']) {
                $state['backfill'] = 'done';
                unset($state['backfill_cursor'], $state['backfill_min']);
                break;
            }
            self::aggregate_day($day);
            $state['backfill_cursor'] = self::shift_day($day, -1);
        }

        $state['last_run'] = time();
        Schema::save_state($state);
        Lock::release('aggregate');

        $behind = ($state['backfill'] ?? 'done') !== 'done' || ($state['done_through'] ?? $today) < self::shift_day($today, -1);
        if ($behind && ! wp_next_scheduled(self::HOOK_AGGREGATE, ['continue'])) {
            wp_schedule_single_event(time() + MINUTE_IN_SECONDS, self::HOOK_AGGREGATE, ['continue']);
        }
        return $state;
    }

    /**
     * Rebuild today's summary when the dashboard is opened and events have
     * arrived since the last rebuild, so new visits show up at once instead of
     * at the next 15-minute cron run. The "anything new?" check is a primary
     * key lookup; the rebuild covers one day of raw events through the
     * created_at index and runs at most once every $min_gap seconds.
     *
     * @return array State after the refresh.
     */
    public static function refresh_today(int $min_gap): array
    {
        global $wpdb;
        $state = Schema::state();
        if (($state['raw_indexes'] ?? '') !== 'done') {
            return $state;
        }
        $raw    = Schema::raw_table();
        $latest = (int) $wpdb->get_var("SELECT MAX(id) FROM `$raw`");
        if ($latest <= (int) ($state['today_seen_id'] ?? 0) || time() - (int) ($state['today_at'] ?? 0) < $min_gap) {
            return $state;
        }
        if (! Lock::acquire('aggregate', 10 * MINUTE_IN_SECONDS)) {
            return $state;
        }
        self::aggregate_day(self::today());
        $state                  = Schema::state();
        $state['today_at']      = time();
        $state['today_seen_id'] = $latest;
        Schema::save_state($state);
        Lock::release('aggregate');
        return $state;
    }

    private static function init_backfill(array $state): array
    {
        global $wpdb;
        $raw = Schema::raw_table();
        // Uses the created_at index, so this is instant even on large tables.
        $oldest = $wpdb->get_var("SELECT UNIX_TIMESTAMP(MIN(created_at)) FROM `$raw`");
        $cursor = $state['done_through'] ?? self::shift_day(self::today(), -2);
        if (! $oldest) {
            $state['backfill'] = 'done';
            return $state;
        }
        $state['backfill']        = 'running';
        $state['backfill_min']    = wp_date('Y-m-d', (int) $oldest);
        $state['backfill_cursor'] = $cursor;
        return $state;
    }

    /** Replace the summaries for one local day with fresh totals from the raw table. */
    public static function aggregate_day(string $day): void
    {
        global $wpdb;
        [$from, $to] = self::day_bounds($day);
        $raw   = Schema::raw_table();
        $daily = Schema::daily_table();
        $dims  = Schema::dims_table();
        $range = $wpdb->prepare('created_at >= FROM_UNIXTIME(%d) AND created_at < FROM_UNIXTIME(%d)', $from, $to);

        $wpdb->query('START TRANSACTION');

        $dev = "CASE WHEN device_type IN ('desktop', 'mobile') THEN device_type ELSE 'other' END";

        $wpdb->query($wpdb->prepare("DELETE FROM `$daily` WHERE day = %s", $day));
        $wpdb->query($wpdb->prepare(
            "INSERT INTO `$daily` (day, widget_id, device_type, visitors, views, conversions)
            SELECT %s, widget_id, $dev AS dev,
                SUM(event_type = 'visitor'), SUM(event_type = 'view'), SUM(event_type = 'conversion')
            FROM `$raw` WHERE $range
            GROUP BY widget_id, dev",
            $day
        ));
        // Sessions that converted, each counted once (under the first widget
        // it converted on), so totals across widgets add up to distinct sessions.
        $wpdb->query($wpdb->prepare(
            "UPDATE `$daily` d JOIN (
                SELECT w, dev, COUNT(*) AS c FROM (
                    SELECT MIN(widget_id) AS w, MIN($dev) AS dev
                    FROM `$raw` WHERE $range AND event_type = 'conversion' AND session_id <> ''
                    GROUP BY session_id
                ) s GROUP BY w, dev
            ) x ON d.widget_id = x.w AND d.device_type = x.dev
            SET d.converted_sessions = x.c
            WHERE d.day = %s",
            $day
        ));

        $wpdb->query($wpdb->prepare("DELETE FROM `$dims` WHERE dim IN ('country', 'browser', 'page') AND day = %s", $day));
        foreach (['country' => 'country', 'browser' => 'browser', 'page' => 'page_url'] as $dim => $column) {
            $limit = $dim === 'page' ? ' ORDER BY views DESC LIMIT ' . (int) self::PAGE_ROWS_PER_DAY : '';
            $wpdb->query($wpdb->prepare(
                "INSERT INTO `$dims` (day, dim, widget_id, dim_value, views, conversions)
                SELECT %s, %s, widget_id, $column,
                    SUM(event_type = 'view') AS views, SUM(event_type = 'conversion') AS conversions
                FROM `$raw` WHERE $range AND $column <> ''
                GROUP BY widget_id, $column
                HAVING views > 0 OR conversions > 0$limit",
                $day,
                $dim
            ));
        }

        $wpdb->query('COMMIT');
    }

    /* ── Retention ───────────────────────────────────────────────────── */

    public static function cron_cleanup($unused = ''): void
    {
        self::cleanup(10);
    }

    /**
     * Delete raw events older than the retention window, in small batches.
     * Never touches a day that has not been rolled up yet.
     *
     * @return int Rows deleted.
     */
    public static function cleanup(float $budget): int
    {
        Schema::maybe_upgrade();
        RateLimiter::cleanup_files();

        $days  = Settings::retention_days();
        $state = Schema::state();
        if ($days === 0 || ($state['raw_indexes'] ?? '') !== 'done' || ($state['backfill'] ?? '') !== 'done' || empty($state['done_through'])) {
            return 0;
        }
        if (! Lock::acquire('cleanup', 10 * MINUTE_IN_SECONDS)) {
            return 0;
        }

        global $wpdb;
        $raw        = Schema::raw_table();
        $cutoff_day = min(self::shift_day(self::today(), -$days), self::shift_day($state['done_through'], 1));
        [$cutoff]   = self::day_bounds($cutoff_day);
        $deadline   = microtime(true) + $budget;
        $deleted    = 0;
        $more       = false;

        for ($i = 0; $i < self::DELETE_MAX_BATCHES; $i++) {
            $n        = (int) $wpdb->query($wpdb->prepare(
                "DELETE FROM `$raw` WHERE created_at < FROM_UNIXTIME(%d) ORDER BY created_at LIMIT %d",
                $cutoff,
                self::DELETE_BATCH
            ));
            $deleted += $n;
            $more     = $n === self::DELETE_BATCH;
            if (! $more || microtime(true) > $deadline) {
                break;
            }
        }

        Lock::release('cleanup');
        if ($more && ! wp_next_scheduled(self::HOOK_CLEANUP, ['continue'])) {
            wp_schedule_single_event(time() + MINUTE_IN_SECONDS, self::HOOK_CLEANUP, ['continue']);
        }
        return $deleted;
    }
}
