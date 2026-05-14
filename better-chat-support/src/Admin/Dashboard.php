<?php

namespace ThemeAtelier\BetterChatSupport\Admin;

if (! defined('ABSPATH')) {
    die;
}

class Dashboard
{
    public function __construct()
    {
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts'], 100);
        add_action('admin_head', [$this, 'suppress_notices_and_fix_layout']);
    }

    public function suppress_notices_and_fix_layout(): void
    {
        $screen = get_current_screen();
        if (!$screen || $screen->id !== 'toplevel_page_mcs') {
            return;
        }

        remove_all_actions('admin_notices');
        remove_all_actions('all_admin_notices');
        remove_all_actions('user_admin_notices');
        remove_all_actions('network_admin_notices');
    }

    public function enqueue_scripts(string $hook): void
    {
        if ($hook !== 'toplevel_page_mcs') {
            return;
        }

        wp_dequeue_style('common');
        wp_deregister_style('common-css');

        $options        = get_option('mcs-opt', []);
        $opt_number     = isset($options['opt-number']) ? $options['opt-number'] : '';
        $has_global_chat = !empty($opt_number);

        add_action('admin_print_scripts', function () use ($has_global_chat) {
            $data = [
                'restUrl'       => esc_url_raw(rest_url('better-chat-support/v1')),
                'nonce'         => wp_create_nonce('wp_rest'),
                'version'       => BETTER_CHAT_SUPPORT_VERSION,
                'widgets'       => [],
                'hasGlobalChat' => $has_global_chat,
            ];
            echo wp_print_inline_script_tag(
                'window.mcsDashboard = ' . wp_json_encode($data) . ';' .
                'window.mcsChat = { restUrl: ' . wp_json_encode(esc_url_raw(rest_url('better-chat-support/v1'))) . ', nonce: ' . wp_json_encode(wp_create_nonce('wp_rest')) . ' };'
            );
        });

        wp_enqueue_script(
            'better-chat-support-admin-dashboard',
            BETTER_CHAT_SUPPORT_DIR_URL . 'src/Admin/assets/js/better-chat-support-admin.js',
            [],
            BETTER_CHAT_SUPPORT_VERSION,
            true
        );
    }
}
