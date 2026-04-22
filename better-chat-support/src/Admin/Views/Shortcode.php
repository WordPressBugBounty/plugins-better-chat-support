<?php

/**
 * Views class for Shortcode generator options.
 *
 * @link       https://themeatelier.net
 * @since      1.0.0
 *
 * @package better-chat-support
 * @subpackage better-chat-support/Admin/Views/Shortcode
 * @author     ThemeAtelier<themeatelierbd@gmail.com>
 */

namespace ThemeAtelier\BetterChatSupport\Admin\Views;

use ThemeAtelier\BetterChatSupport\Admin\Framework\Classes\BetterChatSupport;

class Shortcode
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
            'menu_slug'         => 'shortcodes',
            'theme'             => 'light',
            'show_search'       => false,
            'show_reset_all'    => false,
            'show_sub_menu'     => false,
            'show_bar_menu'     => false,
            'show_footer'       => false,
            'show_reset_section'       => false,
            'save_section'       => false,
            'show_all_options'  => false,
            'menu_type'         => 'submenu',
            'class'             => 'better-chat-support-preloader',
            'nav'               => 'inline',
            'menu_position'     => 58,
        ));

        //
        // Field: shortcodes
        //
        BetterChatSupport::createSection($prefix, array(
            'title'       => esc_html__('Shortcodes', 'better-chat-support'),
            'icon'        => 'icofont-code-alt',
            'fields'      => array(
                array(
                    'id'      => 'opt-shortcode-select',
                    'type'    => 'image_select',
                    'title'   => esc_html__('Select Button Style', 'better-chat-support'),
                    'title_help' =>
                    '<div class="better-chat-support-info-label">' .
                        esc_html__('Choose between Simple or Advanced button styles for the shortcode. The Advanced style supports agent details (photo, name, designation), while the Simple style is a basic Messenger button.', 'better-chat-support') .
                        '</div>' .
                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#shortcode') . '">' . esc_html__('Live Demo', 'better-chat-support') . '</a>' .
                        ' <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'button-integration/shortcodes/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                    'options' => array(
                        '1' => BETTER_CHAT_SUPPORT_DIR_URL . '/src/Admin/Framework/assets/images/button-with-info.svg',
                        '2' => BETTER_CHAT_SUPPORT_DIR_URL . '/src/Admin/Framework/assets/images/button2.svg',
                    ),
                    'default' => '1',
                ),

                array(
                    'id'    => 'advance_button_shortcode',
                    'type'  => 'shortcode',
                    'title' => esc_html__('Shortcode', 'better-chat-support'),
                    'desc'  => '<b>' . esc_html__('Available Attributes', 'better-chat-support') . '</b> = style="1", fbid="Facebook user name", message="Your desired message" top_label="Button top label" main_label="Button main label" visibility="desktop/tablet/mobile", size="1", background="#0084ff", hover_background="#0066ff", text_color="#ffffff", hover_text_color="#ffffff", padding="5px 15px 5px 6px", border_radius="50px", border="0px", border_style="solid", border_color="#0084ff", border_hover_color="#0066ff"',
                    'dependency' => array('opt-shortcode-select', 'any', '1'),
                    'shortcode_text'    => '[better_chat_support style="1" fbid="themeAtelier" message="Hi! I have a question about your service." timezone="Asia/Dhaka" top_label="John / Technical support" main_label="How can I help you?" online="I am online" offline="I am offline" sunday="00:00-23:59" monday="00:00-23:59" tuesday="00:00-23:59" wednesday="00:00-23:59" thursday="00:00-23:59" friday="00:00-23:59" saturday="00:00-23:59"]',
                ),
                array(
                    'id'    => 'simple_button_shortcode',
                    'type'  => 'shortcode',
                    'title' => esc_html__('Shortcode', 'better-chat-support'),
                    'desc'  => '<b>' . esc_html__('Available Attributes', 'better-chat-support') . '</b> = style="2", fbid="Facebook user name", message="Your desired message" label="Button label" visibility="desktop/tablet/mobile", size="1", background="#0084ff", hover_background="#0066ff", text_color="#ffffff", hover_text_color="#ffffff", padding="5px 15px 5px 6px", border_radius="50px", icon="yes/no" icon_bg="yes/no" icon_background="#ffffff", hover_icon_background="#ffffff", icon_color="#0084ff", hover_icon_color="#0066ff", border="0px", border_style="solid", border_color="#0084ff", border_hover_color="#0066ff"',
                    'dependency' => array('opt-shortcode-select', 'any', '2'),
                    'shortcode_text'    => '[better_chat_support style="2" fbid="themeAtelier" message="Hi! I have a question about your service." label="How can I help you?" sunday="00:00-23:59" monday="00:00-23:59" tuesday="00:00-23:59" wednesday="00:00-23:59" thursday="00:00-23:59" friday="00:00-23:59" saturday="00:00-23:59"]',
                ),
            ),
        ));
    }
}
