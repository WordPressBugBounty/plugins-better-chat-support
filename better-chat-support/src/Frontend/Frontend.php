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
        wp_enqueue_style('icofont');
        wp_enqueue_style('mcs-main');
        /********************
				Js Enqueue
         ********************/
        wp_enqueue_script('moment');
        wp_enqueue_script('moment-timezone');
        wp_enqueue_script('mcs-main');
    }

    public function better_chat_support_chat_popup()
    {
        $options = get_option('mcs-opt');
        $enable_floating_chat = isset($options['enable_floating_chat']) ? $options['enable_floating_chat'] : '1';
        $fbId = isset($options['opt-fbid']) ? $options['opt-fbid'] : '';
        $optAvailablity = $options['opt-availablity'];
        $circle_animation = isset($options['circle-animation']) ? $options['circle-animation'] : '1';
        $animation = isset($options['select-animation']) ? $options['select-animation'] : '1';
        $sunday_from = $optAvailablity ? $optAvailablity['availablity-sunday']['from'] : '00:00';
        $sunday_to = $optAvailablity ? $optAvailablity['availablity-sunday']['to'] : '23:59';

        $monday_from = $optAvailablity ? $optAvailablity['availablity-monday']['from'] : '00:00';
        $monday_to = $optAvailablity ? $optAvailablity['availablity-monday']['to'] : '23:59';

        $tuesday_from = $optAvailablity ? $optAvailablity['availablity-tuesday']['from'] : '00:00';
        $tuesday_to = $optAvailablity ? $optAvailablity['availablity-tuesday']['to'] : '23:59';

        $wednesday_from = $optAvailablity ? $optAvailablity['availablity-wednesday']['from'] : '00:00';
        $wednesday_to = $optAvailablity ? $optAvailablity['availablity-wednesday']['to'] : '23:59';

        $thursday_from = $optAvailablity ? $optAvailablity['availablity-thursday']['from'] : '00:00';
        $thursday_to = $optAvailablity ? $optAvailablity['availablity-thursday']['to'] : '23:59';
        $friday_from = $optAvailablity ? $optAvailablity['availablity-friday']['from'] : '00:00';
        $friday_to = $optAvailablity ? $optAvailablity['availablity-friday']['to'] : '23:59';
        $saturday_from = $optAvailablity ? $optAvailablity['availablity-saturday']['from'] : '00:00';
        $saturday_to = $optAvailablity ? $optAvailablity['availablity-saturday']['to'] : '23:59';
        $sunday = ($sunday_from ? $sunday_from : "0:00") . "-" . ($sunday_to ? $sunday_to : "23:59");
        $monday = ($monday_from ? $monday_from : "0:00") . "-" . ($monday_to ? $monday_to : "23:59");
        $tuesday = ($tuesday_from ? $tuesday_from : "0:00") . "-" . ($tuesday_to ? $tuesday_to : "23:59");
        $wednesday = ($wednesday_from ? $wednesday_from : "0:00") . "-" . ($wednesday_to ? $wednesday_to : "23:59");
        $thursday = ($thursday_from ? $thursday_from : "0:00") . "-" . ($thursday_to ? $thursday_to : "23:59");
        $friday = ($friday_from ? $friday_from : "0:00") . "-" . ($friday_to ? $friday_to : "23:59");
        $saturday = ($saturday_from ? $saturday_from : "0:00") . "-" . ($saturday_to ? $saturday_to : "23:59");
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

        if ($enable_floating_chat && $fbId) {
?>
            <div style="--right_bottom_value_bottom: <?php echo esc_attr($right_bottom_value_bottom . $right_bottom_unit); ?>; --right_bottom_value_right: <?php echo esc_attr($right_bottom_value_right . $right_bottom_unit); ?>; --left_bottom_value_bottom: <?php echo esc_attr($left_bottom_value_bottom . $left_bottom_unit); ?>; --left_bottom_value_left: <?php echo esc_attr($left_bottom_value_left . $left_bottom_unit); ?>;" class="mSupport mSupport-<?php if (isset($options['bubble-visibility'])) { echo esc_attr($options['bubble-visibility']); }; ?>-only <?php if ($options['autoshow-popup']) : ?>mSupport-show<?php endif; if ($options['bubble-position'] === 'left') { ?>mSupport-left<?php } ?>">
                <?php echo wp_kses_post($bubbleType); ?>
                <div
                    class="mSupport__popup animation<?php echo esc_attr($animation); ?> chat-availability"
                    <?php if ($options['select-timezone']) { ?> data-timezone="<?php echo esc_attr($options['select-timezone']); ?>" <?php } ?>
                    data-availability='{ "sunday":"<?php echo esc_attr($sunday); ?>", "monday":"<?php echo esc_attr($monday); ?>", "tuesday":"<?php echo esc_attr($tuesday); ?>", "wednesday":"<?php echo esc_attr($wednesday); ?>", "thursday":"<?php echo esc_attr($thursday); ?>", "friday":"<?php echo esc_attr($friday); ?>", "saturday":"<?php echo esc_attr($saturday); ?>" }'>
                    <div class="mSupport__popup--header <?php echo esc_attr('header-' . $options['header-content-position']); ?>">
                        <?php if ($options['agent-photo']['url']) : ?>
                            <div class="image">
                                <img src="<?php echo esc_attr($options['agent-photo']['url']); ?>" />
                            </div>
                        <?php endif; ?>
                        <div class="info">
                            <div class="info__name"><?php echo esc_html($options['agent-name']); ?></div>
                            <div class="info__title"><?php echo esc_html($options['agent-subtitle']); ?></div>
                        </div>
                    </div>
                    <div class="mSupport__popup--content">
                        <div class="current-time"></div>
                        <div class="sms">

                            <div class="sms__text">
                                <?php echo esc_html($options['agent-message']); ?>
                            </div>
                        </div>
                        <div class="mSupport__send-message">
                            <i class="<?php echo esc_html($options['before-chat-icon']); ?>"></i><?php echo esc_html($options['chat-button-text']); ?>
                            <a href="https://www.m.me/<?php echo esc_attr($options['opt-fbid']); ?>" target="_blank"></a>
                        </div>
                    </div>
                </div>
            </div>
<?php
        }
    }
}
