<?php

/**
 * Views class for Shortcode generator options.
 *
 * @link       https://themeatelier.net
 * @since      1.0.0
 *
 * @package better-chat-support
 * @subpackage better-chat-support/Admin/Views/Advance
 * @author     ThemeAtelier<themeatelierbd@gmail.com>
 */

namespace ThemeAtelier\BetterChatSupport\Admin\Views;

use ThemeAtelier\BetterChatSupport\Admin\Framework\Classes\BetterChatSupport;

class Advance
{
    /**
     * Create Option fields for the setting options.
     *
     * @param string $prefix Option setting key prefix.
     * @return void
     */
    public static function options($prefix)
    {
        //
        // Field: advance
        //
        BetterChatSupport::createSection($prefix, array(
            'title'       => esc_html__('Advance', 'better-chat-support'),
            'icon'        => 'icofont-code-alt',
            'fields'      => array(
                array(
                    'id'      => 'cleanup_data_deletion',
                    'type'    => 'checkbox',
                    'title' => esc_html__('Clean-up Data on Deletion', 'better-chat-support'),
                    'title_help' => '<div class="better-chat-support-info-label">' . esc_html__('Enable this option to completely remove all Messenger Chat Support plugin data when the plugin is deleted from your site.', 'better-chat-support') . '</div>' . ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'settings/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                ),
                array(
                    'id'      => 'open_in_new_tab',
                    'type'    => 'switcher',
                    'title'   => esc_html__('Open in New Tab', 'better-chat-support'),
                    'default' => true,
                    'title_help' =>
                    '<div class="better-chat-support-info-label">' . esc_html__('Enable this option to open the Messenger chat link in a new browser tab when clicked.', 'better-chat-support') . '</div>' . ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'settings/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                ),
                array(
                    'id'       => 'mcs_custom_css',
                    'type'     => 'code_editor',
                    'title' => esc_html__('Custom CSS', 'better-chat-support'),
                    'title_help' =>  '<div class="better-chat-support-info-label">' . esc_html__('Add your own custom CSS to override or extend the default styling of the chat box.', 'better-chat-support') . '</div>' . ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'settings/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                    'settings' => array(
                        'theme'  => 'mbo',
                        'mode'   => 'css',
                    ),
                ),
                array(
                    'id'       => 'mcs_custom_js',
                    'type'     => 'code_editor',
                    'title' => esc_html__('Custom JavaScript', 'better-chat-support'),
                    'title_help' => '<div class="better-chat-support-info-label">' . esc_html__('Add your own custom JavaScript to extend or customize chat box behavior.', 'better-chat-support') . '</div>' . ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'settings/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                    'settings' => array(
                        'theme'  => 'mbo',
                        'mode'   => 'js',
                    ),
                ),
            ),
        ));
    }
}
