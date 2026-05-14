<?php

namespace ThemeAtelier\BetterChatSupport\Admin;

use WP_REST_Server;
use WP_REST_Request;

if (! defined('ABSPATH')) {
    die;
}

class Leads
{
    function __construct()
    {
        $options          = get_option('mcs-opt');
        $mcs_leads        = isset($options['mcs_leads']) ? $options['mcs_leads'] : true;
        if ($mcs_leads) {
            add_action('better_chat_support_recommended_page_menu', [$this, 'register_leads_submenu']);
            add_action('admin_head', [$this, 'localize_script']);
            add_action('rest_api_init', [$this, 'register_rest_routes']);

            $this->create_leads_table();
        }
    }

    public function register_leads_submenu()
    {
        add_submenu_page(
            'mcs',
            esc_html__('Leads', 'better-chat-support'),
            esc_html__('Leads', 'better-chat-support'),
            'manage_options',
            'mcs#/leads',
            '__return_true'
        );
    }

    public function localize_script()
    {
        ?>
        <script>
            window.mcsString = <?php echo json_encode(self::get_strings()); ?>;
        </script>
        <?php
    }

    public function create_leads_table()
    {
        global $wpdb;
        $table_name      = $wpdb->prefix . 'mcs_leads';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        field LONGTEXT NOT NULL,
        meta LONGTEXT NOT NULL,
        agent_name VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY  (id)
    ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    public function register_rest_routes()
    {
        register_rest_route(
            'better-chat-support/v1',
            '/leads',
            [
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => [$this, 'get_leads'],
                'permission_callback' => [$this, 'can_manage_leads'],
            ]
        );

        register_rest_route(
            'better-chat-support/v1',
            '/leads/(?P<id>\d+)',
            [
                'methods'             => WP_REST_Server::DELETABLE,
                'callback'            => [$this, 'delete_lead'],
                'permission_callback' => [$this, 'can_manage_leads'],
                'args'                => [
                    'id' => [
                        'validate_callback' => fn($param) => is_numeric($param),
                    ],
                ],
            ]
        );
    }

    public function can_manage_leads(WP_REST_Request $request): bool
    {
        return is_user_logged_in() && current_user_can(apply_filters('mcs_leads_capability', 'manage_options'));
    }

    public function get_leads(WP_REST_Request $request)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'mcs_leads';
        $results    = $wpdb->get_results("SELECT * FROM {$table_name}", ARRAY_A);

        foreach ($results as &$row) {
            if (!empty($row['field'])) {
                $unserialized = @unserialize($row['field']);
                if ($unserialized !== false) {
                    $row['field'] = $unserialized;
                }
            }
            if (!empty($row['meta'])) {
                $unserialized = @unserialize($row['meta']);
                if ($unserialized !== false) {
                    $row['meta'] = $unserialized;
                }
            }
        }
        return new \WP_REST_Response($results, 200);
    }

    public function delete_lead(WP_REST_Request $request)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'mcs_leads';
        $id         = intval($request['id']);

        $deleted = $wpdb->delete($table_name, ['id' => $id], ['%d']);

        if ($deleted === false) {
            return new \WP_Error('db_error', 'Failed to delete lead', ['status' => 500]);
        }

        if ($deleted === 0) {
            return new \WP_Error('not_found', 'Lead not found', ['status' => 404]);
        }

        return new \WP_REST_Response(['deleted' => true, 'id' => $id], 200);
    }

    public static function get_strings()
    {
        return [
            'leads'                                       => esc_html__('Leads', 'better-chat-support'),
            'all_email'                                   => esc_html__('All Email', 'better-chat-support'),
            'pending_verification'                        => esc_html__('Pending Verification', 'better-chat-support'),
            'verified'                                    => esc_html__('Verified', 'better-chat-support'),
            'resend'                                      => esc_html__('Resend', 'better-chat-support'),
            'you_can_resend_again_within'                 => esc_html__('You can resend again within', 'better-chat-support'),
            'all_domain'                                  => esc_html__('All Domain', 'better-chat-support'),
            'all_extension'                               => esc_html__('All Extension', 'better-chat-support'),
            'search'                                      => esc_html__('Search', 'better-chat-support'),
            'loading'                                     => esc_html__('Loading...', 'better-chat-support'),
            'delete_lead'                                 => esc_html__('Delete Lead', 'better-chat-support'),
            'are_you_sure_you_want_to_delete_this_lead'   => esc_html__('Are you sure you want to delete this lead?', 'better-chat-support'),
            'cancel'                                      => esc_html__('Cancel', 'better-chat-support'),
            'yes_delete'                                  => esc_html__('Yes, Delete', 'better-chat-support'),
            'leads_deleted_successful'                    => esc_html__('Lead(s) deleted successfully!', 'better-chat-support'),
            'delete_failed'                               => esc_html__('Delete failed!', 'better-chat-support'),
            'failed_to_delete_lead'                       => esc_html__('Failed to delete lead', 'better-chat-support'),
            'export'                                      => esc_html__('Export', 'better-chat-support'),
            'action'                                      => esc_html__('Action', 'better-chat-support'),
            'date'                                        => esc_html__('Date', 'better-chat-support'),
            'showing'                                     => esc_html__('Showing', 'better-chat-support'),
            'per_page'                                    => esc_html__('Per page', 'better-chat-support'),
            'page'                                        => esc_html__('Page', 'better-chat-support'),
            'others_information'                          => esc_html__('Others Information', 'better-chat-support'),
            'form_submitted_data'                         => esc_html__('Form Submitted Data', 'better-chat-support'),
            'show_more'                                   => esc_html__('Show More', 'better-chat-support'),
            'show_less'                                   => esc_html__('Show Less', 'better-chat-support'),
            'all'                                         => esc_html__('All', 'better-chat-support'),
            'of'                                          => esc_html__('of', 'better-chat-support'),
            'copied'                                      => esc_html__('Copied!', 'better-chat-support'),
            'no_leads_to_export'                          => esc_html__('No leads to export', 'better-chat-support'),
            'no_leads_found'                              => esc_html__('No leads found.', 'better-chat-support'),
            'all_agents'                                  => esc_html__('All Agents', 'better-chat-support'),
        ];
    }
}
