<?php

namespace ThemeAtelier\BetterChatSupport\Admin\Analytics;

if (! defined('ABSPATH')) {
    die;
}

/**
 * Analytics tables and migrations. Shared by the Free and Pro plugins: both
 * use the same tables, options and version number.
 *
 * Version history:
 *  1 (≤ 2.6.1) wp_mcs_analytics with five indexes, reports read the raw table.
 *  2 (2.7.0)   Raw table keeps one index (created_at). Reports read the daily
 *              summary tables, filled by WP-Cron.
 */
// phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.SchemaChange, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, PluginCheck.Security.DirectDB.UnescapedDBParameter -- The plugin's own tables; names are $wpdb->prefix plus a constant.
class Schema
{
    const DB_VERSION = '2';

    /** Autoloaded, so checking it on admin requests costs no query. */
    const OPTION_VERSION = 'mcs_analytics_db_version';

    /** Background job state (cursors, last run). Not autoloaded. */
    const OPTION_STATE = 'mcs_analytics_state';

    /**
     * Until this timestamp, single-event requests from the 2.6.x tracking
     * script are accepted without a token, so pages still in a page cache keep
     * reporting after the upgrade. Autoloaded: read on the tracking path.
     */
    const OPTION_LEGACY_UNTIL = 'mcs_analytics_legacy_until';
    const LEGACY_GRACE        = 14 * DAY_IN_SECONDS;

    const HOOK_MIGRATE = 'mcs_analytics_migrate';

    /** Indexes from v1 that no report uses but every INSERT maintains. */
    const OLD_INDEXES = ['idx_widget_event_date', 'idx_device', 'idx_country', 'idx_browser'];

    public static function raw_table(): string
    {
        global $wpdb;
        return $wpdb->prefix . 'mcs_analytics';
    }

    public static function daily_table(): string
    {
        global $wpdb;
        return $wpdb->prefix . 'mcs_analytics_daily';
    }

    public static function dims_table(): string
    {
        global $wpdb;
        return $wpdb->prefix . 'mcs_analytics_daily_dims';
    }

    public static function state(): array
    {
        $state = get_option(self::OPTION_STATE, []);
        return is_array($state) ? $state : [];
    }

    public static function save_state(array $state): void
    {
        update_option(self::OPTION_STATE, $state, false);
    }

    public static function is_current(): bool
    {
        return get_option(self::OPTION_VERSION) === self::DB_VERSION;
    }

    /** Run on admin_init, activation, and at the start of every analytics cron job. */
    public static function maybe_upgrade(): void
    {
        if (self::is_current()) {
            return;
        }
        if (! Lock::acquire('install', 120)) {
            return;
        }
        try {
            self::install();
        } finally {
            Lock::release('install');
        }
    }

    private static function install(): void
    {
        global $wpdb;
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        $raw    = self::raw_table();
        $exists = $wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $wpdb->esc_like($raw))) === $raw;
        $state  = self::state();

        if (! $exists) {
            // Fresh install: create the raw table with its final indexes.
            dbDelta(self::raw_table_sql());
            $state['raw_indexes'] = 'done';
        } else {
            // Existing install: changing indexes on a large table can take a
            // while, so it runs in a background cron job, not on this request.
            $state['raw_indexes'] = self::raw_indexes_ok() ? 'done' : 'pending';
        }

        dbDelta(self::daily_table_sql());
        dbDelta(self::dims_table_sql());

        if (! isset($state['done_through'])) {
            // Forward aggregation picks up yesterday and today; the backfill
            // walks back from the day before yesterday to the oldest raw event.
            $state['done_through'] = Aggregator::shift_day(Aggregator::today(), -2);
            $state['backfill']     = 'pending';
        }
        self::save_state($state);

        if (get_option(self::OPTION_LEGACY_UNTIL) === false) {
            add_option(self::OPTION_LEGACY_UNTIL, $exists ? time() + self::LEGACY_GRACE : 0, '', true);
        }

        // Autoload the version so checking it never costs a query. Older
        // versions stored it with autoload off and read it on every request.
        update_option(self::OPTION_VERSION, self::DB_VERSION, true);

        if ($state['raw_indexes'] === 'pending' && ! wp_next_scheduled(self::HOOK_MIGRATE)) {
            wp_schedule_single_event(time(), self::HOOK_MIGRATE);
        }
        Aggregator::ensure_scheduled();
    }

    /** Cron: move the raw table to the v2 indexes. */
    public static function run_raw_migration(): void
    {
        self::maybe_upgrade();
        $state = self::state();
        if (($state['raw_indexes'] ?? '') !== 'pending') {
            return;
        }
        if (! Lock::acquire('migrate', 30 * MINUTE_IN_SECONDS)) {
            return;
        }

        global $wpdb;
        if (function_exists('set_time_limit')) {
            set_time_limit(0); // phpcs:ignore Squiz.PHP.DiscouragedFunctions.Discouraged -- One-off index change on a possibly large table, in a cron request.
        }

        $raw   = self::raw_table();
        $keys  = self::index_names();
        $parts = [];
        if (! in_array('created_at', $keys, true)) {
            $parts[] = 'ADD KEY created_at (created_at)';
        }
        foreach (self::OLD_INDEXES as $old) {
            if (in_array($old, $keys, true)) {
                $parts[] = 'DROP KEY ' . $old;
            }
        }

        $ok = true;
        if ($parts) {
            $alter = "ALTER TABLE `$raw` " . implode(', ', $parts);
            // Online where the server supports it, so inserts keep working.
            $suppress = $wpdb->suppress_errors(true);
            $ok       = $wpdb->query($alter . ', ALGORITHM=INPLACE, LOCK=NONE') !== false;
            $wpdb->suppress_errors($suppress);
            if (! $ok) {
                $ok = $wpdb->query($alter) !== false;
            }
        }

        $state                = self::state();
        $state['raw_indexes'] = $ok && self::raw_indexes_ok() ? 'done' : 'pending';
        if ($state['raw_indexes'] === 'pending') {
            // Kept for `wp bcs-analytics status`; the next aggregate run retries.
            $state['migrate_error'] = substr((string) $wpdb->last_error, 0, 500);
        } else {
            unset($state['migrate_error']);
        }
        self::save_state($state);
        Lock::release('migrate');

        if ($state['raw_indexes'] === 'done') {
            // Start aggregating right away instead of waiting for the next slot.
            wp_schedule_single_event(time(), Aggregator::HOOK_AGGREGATE, ['continue']);
        }
    }

    private static function index_names(): array
    {
        global $wpdb;
        $raw  = self::raw_table();
        $rows = $wpdb->get_results("SHOW INDEX FROM `$raw`");
        return array_values(array_unique(array_map(function ($r) {
            return $r->Key_name;
        }, $rows ?: [])));
    }

    private static function raw_indexes_ok(): bool
    {
        $keys = self::index_names();
        return in_array('created_at', $keys, true) && ! array_intersect(self::OLD_INDEXES, $keys);
    }

    private static function raw_table_sql(): string
    {
        global $wpdb;
        $table   = self::raw_table();
        $charset = $wpdb->get_charset_collate();
        return "CREATE TABLE $table (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            widget_id BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
            event_type VARCHAR(20) NOT NULL,
            device_type VARCHAR(10) NOT NULL DEFAULT 'desktop',
            country VARCHAR(3) NOT NULL DEFAULT '',
            browser VARCHAR(30) NOT NULL DEFAULT '',
            page_url VARCHAR(500) NOT NULL DEFAULT '',
            session_id VARCHAR(64) DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY created_at (created_at)
        ) $charset;";
    }

    private static function daily_table_sql(): string
    {
        global $wpdb;
        $table   = self::daily_table();
        $charset = $wpdb->get_charset_collate();
        return "CREATE TABLE $table (
            day DATE NOT NULL,
            widget_id BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
            device_type VARCHAR(10) NOT NULL DEFAULT 'desktop',
            visitors INT(10) UNSIGNED NOT NULL DEFAULT 0,
            views INT(10) UNSIGNED NOT NULL DEFAULT 0,
            conversions INT(10) UNSIGNED NOT NULL DEFAULT 0,
            converted_sessions INT(10) UNSIGNED NOT NULL DEFAULT 0,
            PRIMARY KEY  (day,widget_id,device_type)
        ) $charset;";
    }

    private static function dims_table_sql(): string
    {
        global $wpdb;
        $table   = self::dims_table();
        $charset = $wpdb->get_charset_collate();
        return "CREATE TABLE $table (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            day DATE NOT NULL,
            dim VARCHAR(10) NOT NULL,
            widget_id BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
            dim_value VARCHAR(500) NOT NULL DEFAULT '',
            views INT(10) UNSIGNED NOT NULL DEFAULT 0,
            conversions INT(10) UNSIGNED NOT NULL DEFAULT 0,
            PRIMARY KEY  (id),
            KEY dim_day (dim,day)
        ) $charset;";
    }
}
