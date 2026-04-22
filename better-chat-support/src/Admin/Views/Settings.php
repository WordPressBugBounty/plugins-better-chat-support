<?php

/**
 * Views class for Shortcode generator options.
 *
 * @link       https://themeatelier.net
 * @since      1.0.0
 *
 * @package better-chat-support
 * @subpackage better-chat-support/Admin/Views/Settings
 * @author     ThemeAtelier<themeatelierbd@gmail.com>
 */

namespace ThemeAtelier\BetterChatSupport\Admin\Views;

use ThemeAtelier\BetterChatSupport\Admin\Framework\Classes\BetterChatSupport;

class Settings
{
    /**
     * Create Option fields for the setting options.
     *
     * @param string $prefix Option setting key prefix.
     * @return void
     */
    public static function options($prefix)
    {
        BetterChatSupport::createOptions($prefix, array(
            'menu_title'        => esc_html__('Messenger Chat Support', 'better-chat-support'),
            'menu_icon'         => 'dashicons-format-chat',
            'framework_title'   => esc_html__('Messenger Chat Support', 'better-chat-support'),
            'menu_slug'         => 'settings',
            'theme'             => 'light',
            'show_search'           => false,
            'show_reset_all'        => false,
            'show_sub_menu'         => false,
            'show_bar_menu'         => false,
            'sticky_header'         => false,
            'show_footer'           => false,
            'show_all_options'      => false,
            'menu_type'         => 'submenu',
            'class'             => 'better-chat-support-preloader better_chat_support_shortcode mcs_settings',
            'menu_position'     => 58,
        ));

        //
        // Field: shortcodes
        //
        
        Advance::options($prefix);
    }
}
