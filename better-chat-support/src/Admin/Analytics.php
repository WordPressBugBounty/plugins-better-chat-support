<?php

namespace ThemeAtelier\BetterChatSupport\Admin;

use ThemeAtelier\BetterChatSupport\Admin\Analytics\Aggregator;
use ThemeAtelier\BetterChatSupport\Admin\Analytics\Cli;
use ThemeAtelier\BetterChatSupport\Admin\Analytics\Reports;
use ThemeAtelier\BetterChatSupport\Admin\Analytics\Schema;
use ThemeAtelier\BetterChatSupport\Admin\Analytics\Tracker;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

if (! defined('ABSPATH')) {
    die;
}

/**
 * Analytics bootstrap: hooks, cron and REST routes.
 *
 * Request path (cheap):   Tracker — validate, then one INSERT.
 * Background (WP-Cron):   Aggregator — daily summaries, backfill, retention.
 * Admin dashboard:        Reports — summary tables only, cached.
 *
 * Nothing here queries the database while loading; this class is built on
 * every request.
 */
class Analytics
{
    const REST_NAMESPACE = 'better-chat-support/v1';

    function __construct()
    {
        add_filter('cron_schedules', [Aggregator::class, 'cron_schedules']);
        add_action('init', [Aggregator::class, 'ensure_scheduled']);
        add_action('admin_init', [Schema::class, 'maybe_upgrade']);
        add_action(Aggregator::HOOK_AGGREGATE, [Aggregator::class, 'cron_aggregate']);
        add_action(Aggregator::HOOK_CLEANUP, [Aggregator::class, 'cron_cleanup']);
        add_action(Schema::HOOK_MIGRATE, [Schema::class, 'run_raw_migration']);

        add_action('rest_api_init', [$this, 'register_rest_routes']);
        add_action('wp_footer', [Tracker::class, 'print_script']);

        if (defined('WP_CLI') && WP_CLI) {
            \WP_CLI::add_command('bcs-analytics', Cli::class);
        }
    }

    /** Activation hook. */
    public static function activate()
    {
        Schema::maybe_upgrade();
    }

    /** Deactivation hook. The other edition (Free/Pro) reschedules on its next request. */
    public static function deactivate()
    {
        Aggregator::unschedule();
    }

    public function register_rest_routes()
    {
        register_rest_route(self::REST_NAMESPACE, '/analytics/track', [
            'methods'             => WP_REST_Server::CREATABLE,
            'callback'            => [Tracker::class, 'handle'],
            'permission_callback' => [Tracker::class, 'permission'],
        ]);

        // One request for the whole dashboard.
        register_rest_route(self::REST_NAMESPACE, '/analytics/dashboard', [
            'methods'             => WP_REST_Server::READABLE,
            'callback'            => function (WP_REST_Request $request) {
                return new WP_REST_Response(Reports::dashboard($request->get_params()));
            },
            'permission_callback' => [$this, 'admin_permission'],
        ]);

        // 2.6.x endpoints, kept for compatibility. Same data, same cache.
        $sections = [
            '/analytics'         => 'summary',
            '/analytics/chart'   => 'chart',
            '/analytics/devices' => 'devices',
            '/analytics/tables'  => 'tables',
        ];
        foreach ($sections as $route => $section) {
            register_rest_route(self::REST_NAMESPACE, $route, [
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => function (WP_REST_Request $request) use ($section) {
                    return new WP_REST_Response(Reports::dashboard($request->get_params())[$section]);
                },
                'permission_callback' => [$this, 'admin_permission'],
            ]);
        }
    }

    public function admin_permission()
    {
        return current_user_can('manage_options');
    }
}
