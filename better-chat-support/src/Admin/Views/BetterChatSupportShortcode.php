<?php

/**
 * Views class for Shortcode generator options.
 *
 * @link       https://themeatelier.net
 * @since      1.0.0
 *
 * @package better-chat-support
 * @subpackage better-chat-support/Admin/Views/BetterChatSupportShortcode
 * @author     ThemeAtelier<themeatelierbd@gmail.com>
 */

namespace ThemeAtelier\BetterChatSupport\Admin\Views;

use ThemeAtelier\BetterChatSupport\Admin\Framework\Classes\BetterChatSupport;

class BetterChatSupportShortcode
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
                    'title_help' => esc_html__('Choose between Simple or Advanced button styles for the shortcode. The Advanced style supports agent details (photo, name, designation), while the Simple style is a basic WhatsApp button.', 'better-chat-support'),
                    'options' => array(
                        '1' => BETTER_CHAT_SUPPORT_DIR_URL . '/src/Admin/Framework/assets/images/button-with-info.svg',
                        '2' => BETTER_CHAT_SUPPORT_DIR_URL . '/src/Admin/Framework/assets/images/button2.svg',
                    ),
                    'default' => '1',
                ),

                array(
                    'type'    => 'shortcode',
                    'shortcode_text' => '[mcs style="1" fbid="" timezone="Asia/Dhaka" photo="' . BETTER_CHAT_SUPPORT_DIR_URL . 'assets/image/user.webp" name="Jhon" designation="Techinical support" label="How can I help you?" online="I am online" offline="I am offline" visibility="everywhere" sizes="medium" background="yes" rounded="no" sunday="00:00-23:59" monday="23:00-23:59" tuesday="00:00-23:59" wednesday="00:00-23:59" thursday="00:00-23:59" friday="00:00-23:59" saturday="00:00-23:59"]',
                    'title' => esc_html__('Shortcode', 'better-chat-support'),
                    'desc' => esc_html__('Copy this shortcode and paste it into any page, post, or widget area. You can also edit its attributes to customize the output.', 'better-chat-support'),
                    'title_help' => '<b>' . esc_html__('Background', 'better-chat-support') . '</b> - ' . esc_html__('yes/no', 'better-chat-support') . '<br>' . '<b>' . esc_html__('Visibility', 'better-chat-support') . '</b> - ' . esc_html__('desktop/tablet/mobile', 'better-chat-support') . '<br>' . '<b>' . esc_html__('Size', 'better-chat-support') . '</b> - ' . esc_html__('large/medium/small', 'better-chat-support') . '<br>' . '<b>' . esc_html__('Rounded', 'better-chat-support') . '</b> - ' . esc_html__('yes/no', 'better-chat-support'),
                    'dependency' => array('opt-shortcode-select', 'any', '1'),
                ),

                array(
                    'type'    => 'shortcode',
                    'shortcode_text' => '[mcs style="2" fbid="ThemeAtelier" label="How can I help you?" visibility="everywhere" sizes="medium" background="yes" rounded="no"]',
                    'title' => esc_html__('Shortcode', 'better-chat-support'),
                    'desc' => esc_html__('Copy this shortcode and paste it into any page, post, or widget area. You can also edit its attributes to customize the output.', 'better-chat-support'),
                    'title_help' => '<b>' . esc_html__('Background', 'better-chat-support') . '</b> - ' . esc_html__('yes/no', 'better-chat-support') . '<br>' . '<b>' . esc_html__('Visibility', 'better-chat-support') . '</b> - ' . esc_html__('desktop/tablet/mobile', 'better-chat-support') . '<br>' . '<b>' . esc_html__('Size', 'better-chat-support') . '</b> - ' . esc_html__('large/medium/small', 'better-chat-support') . '<br>' . '<b>' . esc_html__('Rounded', 'better-chat-support') . '</b> - ' . esc_html__('yes/no', 'better-chat-support') . '<br>' . '<b>' . esc_html__('Icon BG', 'better-chat-support') . '</b> - ' . esc_html__('yes/no', 'better-chat-support'),
                    'dependency' => array('opt-shortcode-select', 'any', '2'),
                ),

            ),
        ));
    }
}
