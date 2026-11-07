<?php

namespace ThemeAtelier\BetterChatSupport\Admin\Analytics;

if (! defined('ABSPATH')) {
    die;
}

/**
 * Manage Better Chat Support analytics.
 *
 * ## EXAMPLES
 *
 *     wp bcs-analytics status
 *     wp bcs-analytics migrate
 *     wp bcs-analytics aggregate --all
 *     wp bcs-analytics cleanup
 */
// phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, PluginCheck.Security.DirectDB.UnescapedDBParameter -- Read-only counts on the plugin's own tables.
class Cli
{
    /**
     * Show schema version, job state and row counts.
     */
    public function status()
    {
        global $wpdb;
        \WP_CLI::log('DB version:   ' . var_export(get_option(Schema::OPTION_VERSION), true) . ' (current ' . Schema::DB_VERSION . ')');
        \WP_CLI::log('State:        ' . wp_json_encode(Schema::state()));
        \WP_CLI::log('Retention:    ' . (Settings::retention_days() ?: 'forever') . ' days');
        foreach ([Schema::raw_table(), Schema::daily_table(), Schema::dims_table()] as $table) {
            $exists = $wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $wpdb->esc_like($table))) === $table;
            \WP_CLI::log(str_pad($table . ':', 30) . ($exists ? number_format((int) $wpdb->get_var("SELECT COUNT(*) FROM `$table`")) . ' rows' : 'missing'));
        }
        foreach ([Aggregator::HOOK_AGGREGATE, Aggregator::HOOK_CLEANUP, Schema::HOOK_MIGRATE] as $hook) {
            $next = wp_next_scheduled($hook);
            \WP_CLI::log(str_pad($hook . ':', 30) . ($next ? 'next run ' . wp_date('Y-m-d H:i:s', $next) : 'not scheduled'));
        }
    }

    /**
     * Run the database upgrade, including the raw-table index change.
     */
    public function migrate()
    {
        Schema::maybe_upgrade();
        Schema::run_raw_migration();
        \WP_CLI::success('State: ' . wp_json_encode(Schema::state()));
    }

    /**
     * Roll raw events up into the daily summaries.
     *
     * [--all]
     * : Rebuild every day that still has raw events, not just recent days.
     */
    public function aggregate($args, $assoc)
    {
        Schema::maybe_upgrade();
        if (! empty($assoc['all'])) {
            $state                 = Schema::state();
            $state['done_through'] = Aggregator::shift_day(Aggregator::today(), -2);
            $state['backfill']     = 'pending';
            Schema::save_state($state);
        }
        $start  = microtime(true);
        $cursor = null;
        do {
            $state = Aggregator::aggregate(60);
            if (($state['backfill_cursor'] ?? null) === $cursor) {
                break; // No progress: another run holds the lock.
            }
            $cursor = $state['backfill_cursor'] ?? null;
        } while (($state['backfill'] ?? 'done') === 'running');
        \WP_CLI::success(sprintf('Aggregated in %.2fs. State: %s', microtime(true) - $start, wp_json_encode($state)));
    }

    /**
     * Delete raw events older than the retention setting.
     */
    public function cleanup()
    {
        $total = 0;
        do {
            $n      = Aggregator::cleanup(60);
            $total += $n;
        } while ($n > 0);
        \WP_CLI::success(number_format($total) . ' raw events deleted.');
    }
}
