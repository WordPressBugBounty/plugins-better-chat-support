<?php

/**
 * Views class for Shortcode generator options.
 *
 * @link       https://themeatelier.net
 * @since      1.0.0
 *
 * @package better-chat-support
 * @subpackage better-chat-support/Admin/Views/BetterChatSupportHelp
 * @author     ThemeAtelier<themeatelierbd@gmail.com>
 */

namespace ThemeAtelier\BetterChatSupport\Admin\Views;

use ThemeAtelier\BetterChatSupport\Admin\Framework\Classes\BetterChatSupport;

class BetterChatSupportHelp
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
            'menu_slug'         => 'help',
            'theme'             => 'light',
            'show_search'       => false,
            'show_reset_all'    => false,
            'show_sub_menu'     => false,
            'show_bar_menu'     => false,
            'sticky_header'     => false,
            'show_reset_section'       => false,
            'save_section'       => false,
            'show_footer'       => false,
            'show_all_options'  => false,
            'menu_type'         => 'submenu',
            'class'             => 'better-chat-support-preloader',
            'nav'               => 'inline',
            'menu_position'     => 58,
        ));
        BetterChatSupport::createSection($prefix, array(
            'title'       => esc_html__('HELP', 'better-chat-support'),
            'icon'        => 'icofont-life-buoy',

            'fields'      => array(
                array(
                    'id'   => 'ta_help',
                    'type' => 'ta_help',
                ),
            )
        ));
    }
}
