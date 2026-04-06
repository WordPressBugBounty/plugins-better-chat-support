<?php

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @link       https://themeatelier.net
 * @since      1.0.0
 *
 * @package better-chat-support-por
 * @subpackage better-chat-support-por/Frontend
 * @author     ThemeAtelier<themeatelierbd@gmail.com>
 */

namespace ThemeAtelier\BetterChatSupport\Frontend;

use ThemeAtelier\BetterChatSupport\Includes\Helpers;

/**
 * The Frontend class to manage all public facing stuffs.
 *
 * @since 1.0.0
 */
class Frontend
{
    /**
     * The slug of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_slug   The slug of this plugin.
     */
    private $plugin_slug;

    /**
     * The min of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $min   The slug of this plugin.
     */
    private $min;
    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $plugin_name       The name of the plugin.
     * @param      string $version    The version of this plugin.
     */
    public function __construct()
    {
        $this->min   = defined('WP_DEBUG') && WP_DEBUG ? '' : '.min';
        add_action('wp_footer', [$this, 'better_chat_support_chat_popup']);
    }

    public function enqueue_scripts()
    {
        $mcs_settings = get_option('mcs_settings');
        $mcs_custom_css = isset($mcs_settings['mcs_custom_css']) ? $mcs_settings['mcs_custom_css'] : '';
        $mcs_custom_js = isset($mcs_settings['mcs_custom_js']) ? $mcs_settings['mcs_custom_js'] : '';
        wp_enqueue_style('icofont');
        wp_enqueue_style('mcs-main');

        $custom_css = '';
        if ($mcs_custom_css) {
            $custom_css .= $mcs_custom_css;
        }
        wp_add_inline_style('mcs-main', $custom_css);
        /********************
				Js Enqueue
         ********************/
        wp_enqueue_script('moment');
        wp_enqueue_script('moment-timezone');
        wp_enqueue_script('mcs-main');

        if (!empty($mcs_custom_js)) {
            wp_add_inline_script('mcs-main', $mcs_custom_js);
        }
    }

    public function better_chat_support_chat_popup()
    {
        $options = get_option('mcs-opt');
        $enable_floating_chat = isset($options['opt-chat-type']) ? $options['opt-chat-type'] : '';
        $fbId = isset($options['opt-fbid']) ? $options['opt-fbid'] : '';
        $optAvailability = isset($options['opt-availablity']) ? $options['opt-availablity'] : '';
        $user_availability = Helpers::user_availability($optAvailability);
        $random = wp_rand(1, 13);
        $select_animation = isset($options['select-animation']) ? $options['select-animation'] : 'random';
        if ('random' === $select_animation) {
            $select_animation = $random;
        }

        $bubble_visibility = isset($options['bubble-visibility']) ? $options['bubble-visibility'] : 'everywhere';
        $auto_show_popup = isset($options['autoshow-popup']) ? $options['autoshow-popup'] : '';
        if ($auto_show_popup) {
            $auto_show_popup = 'mSupport-show';
        }
        $bubble_style = isset($options['bubble-style']) ? $options['bubble-style'] : '';
        if ($bubble_style === 'dark') {
            $bubble_style = 'dark-mode ';
        } elseif ($bubble_style === 'night') {
            $bubble_style = 'night-mode ';
        }
        $bubble_position = isset($options['bubble-position']) ? $options['bubble-position'] : 'mSupport-right';
        $select_timezone = isset($options['select-timezone']) ? $options['select-timezone'] : '';
        $header_content_position = isset($options['header-content-position']) ? $options['header-content-position'] : 'left';
        $agent_photo = isset($options['agent-photo']) ? $options['agent-photo'] : '';
        $agent_photo_url = isset($agent_photo['url']) ? $agent_photo['url'] : '';
        $agent_name = isset($options['agent-name']) ? $options['agent-name'] : '';
        $agent_subtitle = isset($options['agent-subtitle']) ? $options['agent-subtitle'] : '';
        $agent_message = isset($options['agent-message']) ? $options['agent-message'] : '';
        $before_chat_icon = isset($options['before-chat-icon']) ? $options['before-chat-icon'] : 'icofont-facebook-messenger';
        $chat_button_text = isset($options['chat-button-text']) ? $options['chat-button-text'] : 'Send a message';
        $facebook_id = isset($options['opt-fbid']) ? $options['opt-fbid'] : '';
        $circle_animation = isset($options['circle-animation']) ? $options['circle-animation'] : '';

        $bubbleType = null;
        $buttonLabel = $options['bubble-text'];
        if ($options['opt-button-style'] === '1') {
            $bubbleType = '<div class="mSupport-bubble circle-bubble circle-animation-' . esc_attr($circle_animation) . ' ">
      <span class="open-icon"><i class="' . esc_html($options['circle-button-icon']) . '"></i></span>
      <span class="close-icon"><i class="' . esc_html($options['circle-button-close']) . '"></i></span>
    </div>';
        } elseif ($options['opt-button-style'] === '2') {
            $bubbleType = '<div class="mSupport-bubble bubble mSupport-btn-bg">
      <div class="bubble__icon bubble-animation' . esc_attr($circle_animation) . '">
      <span class="bubble__icon--open"><i class="' . esc_html($options['circle-button-icon']) . '"></i></span>
      <span class="bubble__icon--close"><i class="' . esc_html($options['circle-button-close']) . '"></i></span>
      </div>' . esc_attr($buttonLabel) . '</div>';
        } elseif ($options['opt-button-style'] === '3') {
            $bubbleType = '<div class="mSupport-bubble bubble">
      <div class="bubble__icon bubble-animation' . esc_attr($circle_animation) . '">
      <span class="bubble__icon--open"><i class="' . esc_html($options['circle-button-icon']) . '"></i></span>
      <span class="bubble__icon--close"><i class="' . esc_html($options['circle-button-close']) . '"></i></span>
      </div>' . esc_attr($buttonLabel) . '</div>';
        } elseif ($options['opt-button-style'] === '4') {
            $bubbleType = '<div class="mSupport-bubble bubble mSupport-btn-rounded mSupport-btn-bg">
    <div class="bubble__icon bubble-animation' . esc_attr($circle_animation) . '">
    <span class="bubble__icon--open"><i class="' . esc_html($options['circle-button-icon']) . '"></i></span>
    <span class="bubble__icon--close"><i class="' . esc_html($options['circle-button-close']) . '"></i></span>
    </div>' . esc_attr($buttonLabel) . '</div>';
        } elseif ($options['opt-button-style'] === '5') {
            $bubbleType = '<div class="mSupport-bubble bubble mSupport-btn-rounded">
  <div class="bubble__icon bubble-animation' . esc_attr($circle_animation) . '">
  <span class="bubble__icon--open"><i class="' . esc_html($options['circle-button-icon']) . '"></i></span>
  <span class="bubble__icon--close"><i class="' . esc_html($options['circle-button-close']) . '"></i></span>
  </div>' . esc_attr($buttonLabel) . '</div>';
        } elseif ($options['opt-button-style'] === '6') {
            $bubbleType = '<div class="mSupport-bubble bubble mSupport-btn-bg bubble-transparent">
  <div class="bubble__icon bubble-animation' . esc_attr($circle_animation) . '">
  <span class="bubble__icon--open"><i class="' . esc_html($options['circle-button-icon']) . '"></i></span>
  <span class="bubble__icon--close"><i class="' . esc_html($options['circle-button-close']) . '"></i></span>
  </div>' . esc_attr($buttonLabel) . '</div>';
        } elseif ($options['opt-button-style'] === '7') {
            $bubbleType = '<div class="mSupport-bubble bubble  bubble-transparent">
  <div class="bubble__icon bubble-animation' . esc_attr($circle_animation) . '">
  <span class="bubble__icon--open"><i class="' . esc_html($options['circle-button-icon']) . '"></i></span>
  <span class="bubble__icon--close"><i class="' . esc_html($options['circle-button-close']) . '"></i></span>
  </div>' . esc_attr($buttonLabel) . '</div>';
        } elseif ($options['opt-button-style'] === '8') {
            $bubbleType = '<div class="mSupport-bubble bubble mSupport-btn-bg mSupport-btn-rounded bubble-transparent">
  <div class="bubble__icon bubble-animation' . esc_attr($circle_animation) . '">
  <span class="bubble__icon--open"><i class="' . esc_html($options['circle-button-icon']) . '"></i></span>
  <span class="bubble__icon--close"><i class="' . esc_html($options['circle-button-close']) . '"></i></span>
  </div>' . esc_attr($buttonLabel) . '</div>';
        } elseif ($options['opt-button-style'] === '9') {
            $bubbleType = '<div class="mSupport-bubble bubble mSupport-btn-rounded bubble-transparent">
  <div class="bubble__icon bubble-animation' . esc_attr($circle_animation) . '">
  <span class="bubble__icon--open"><i class="' . esc_html($options['circle-button-icon']) . '"></i></span>
  <span class="bubble__icon--close"><i class="' . esc_html($options['circle-button-close']) . '"></i></span>
  </div>' . esc_attr($buttonLabel) . '</div>';
        }

        // Right
        $right_bottom              = isset($options['right_bottom']) ? $options['right_bottom'] : array();
        $right_bottom_value_bottom = isset($right_bottom['bottom']) ? $right_bottom['bottom'] : '30';
        $right_bottom_value_right  = isset($right_bottom['right']) ? $right_bottom['right'] : '30';
        $right_bottom_unit         = isset($right_bottom['unit']) ? $right_bottom['unit'] : 'px';

        // Left
        $left_bottom              = isset($options['left_bottom']) ? $options['left_bottom'] : array();
        $left_bottom_value_bottom = isset($left_bottom['bottom']) ? $left_bottom['bottom'] : '30';
        $left_bottom_value_left   = isset($left_bottom['left']) ? $left_bottom['left'] : '30';
        $left_bottom_unit         = isset($left_bottom['unit']) ? $left_bottom['unit'] : 'px';

        $right_middle              = isset($options['right_middle']) ? $options['right_middle'] : array();
        $right_middle_value_right = isset($right_middle['right']) ? $right_middle['right'] : '30';
        $right_middle_unit         = isset($right_middle['unit']) ? $right_middle['unit'] : 'px';

        $left_middle              = isset($options['left_middle']) ? $options['left_middle'] : array();
        $left_middle_value_left = isset($left_middle['left']) ? $left_middle['left'] : '30';
        $left_middle_unit         = isset($left_middle['unit']) ? $left_middle['unit'] : 'px';

        $positions = '
        --right_bottom_value_bottom: ' . esc_attr($right_bottom_value_bottom . $right_bottom_unit) . '; --right_bottom_value_right: ' . esc_attr($right_bottom_value_right . $right_bottom_unit) . '; --left_bottom_value_bottom: ' . esc_attr($left_bottom_value_bottom . $left_bottom_unit) . '; --left_bottom_value_left: ' . esc_attr($left_bottom_value_left . $left_bottom_unit) . '; --right_middle_value_right: ' . esc_attr($right_middle_value_right . $right_middle_unit) . '; --left_middle_value_left: ' . esc_attr($left_middle_value_left . $left_middle_unit) . ';
        ';

        $should_display_element = Helpers::should_display_element($options);

        if ($should_display_element) {

            if ('off' !== $enable_floating_chat && $fbId) {
?>
                <div
                    style="<?php echo wp_kses_post($positions); ?>" class="mSupport_bubble mSupport mSupport-<?php echo esc_attr($bubble_visibility); ?>-only <?php echo esc_attr($auto_show_popup . ' ' . $bubble_style . ' ' . $bubble_position); ?>">
                    <?php echo wp_kses_post($bubbleType); ?>
                    <div
                        class="mSupport__popup animation<?php echo esc_attr($select_animation) ?> chat-availability"
                        <?php if ($select_timezone) { ?> data-timezone="<?php echo esc_attr($select_timezone); ?>" <?php } ?>
                        data-availability="<?php echo esc_attr($user_availability) ?>">
                        <div class="mSupport__popup--header <?php echo esc_attr('header-' . $header_content_position); ?>">
                            <?php if ($agent_photo_url) :
                                echo '<div class="image">';
                                echo '<img src="' . esc_attr($agent_photo_url) . '" alt="' . esc_html($agent_name) . '" />';
                                echo '</div>';
                            endif;
                            if ($agent_name || $agent_subtitle) {
                                echo '<div class="info">';
                                if ($agent_name) {
                                    echo '<div class="info__name">' . esc_html($agent_name) . '</div>';
                                }
                                if ($agent_subtitle) {
                                    echo '<div class="info__title">' . esc_html($agent_subtitle) . '</div>';
                                }
                                echo '</div>';
                            }
                            ?>
                        </div>
                        <div class="mSupport__popup--content">
                            <div class="current-time"></div>
                            <?php if ($agent_message) : ?>
                                <div class="sms">
                                    <div class="sms__text">
                                        <?php echo esc_html($agent_message); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="mSupport__send-message">
                                <i class="<?php echo esc_attr($before_chat_icon); ?>"></i><?php echo esc_html($chat_button_text); ?>
                                <a href="https://www.m.me/<?php echo esc_attr($facebook_id); ?>" target="_blank"></a>
                            </div>
                        </div>
                    </div>
                </div>
<?php
            }
        }
    }
}
