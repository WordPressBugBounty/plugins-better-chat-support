<?php

/**
 * Handles the Whatsapp Chat Support functionality.
 *
 * @package    Whatsapp chat support
 * @version    1.0
 * @author     ThemeAtelier
 * @website    https://themeatelier.net/
 */

namespace ThemeAtelier\BetterChatSupport\Frontend\Shortcode;

use ThemeAtelier\BetterChatSupport\Frontend\CustomButtonsTemplates;

/**
 * Class CustomShortcode
 *
 * This class handles the custom shortcode functionality for WhatsApp chat buttons.
 *
 * @since 1.0.0
 */
class CustomShortcode
{


	/**
	 * Handles the custom buttons shortcode rendering.
	 *
	 * This function is responsible for rendering custom WhatsApp buttons via shortcodes.
	 *
	 * @since 1.0.0
	 *
	 * @return string The HTML output for the custom WhatsApp buttons.
	 */
	public function mcs_custom_buttons_shortcode($atts)
	{
		// Function implementation goes here.
		$atts = shortcode_atts(array(
			'style' => '1',
			'photo' => BETTER_CHAT_SUPPORT_DIR_URL . 'assets/image/user.webp',
			'name' => esc_html__('Robert', 'better-chat-support'),
			'designation' => esc_html__('Sales Support', 'better-chat-support'),
			'label' => esc_html__('How can I help you?', 'better-chat-support'),
			'online' => esc_html__('I\'m available', 'better-chat-support'),
			'offline'  => esc_html__('I\'m offline', 'better-chat-support'),
			'fbid' => esc_html__('ThemeAtelier', 'better-chat-support'),
			'timezone' => '',
			'visibility' => '',
			'sizes' => '',
			'background' => '',
			'rounded' => '',
			'icon_bg' => '',
			'icon' => 'yes',
			'sunday' => esc_html__('00:00-23:59', 'better-chat-support'),
			'monday' => esc_html__('00:00-23:59', 'better-chat-support'),
			'tuesday' => esc_html__('00:00-23:59', 'better-chat-support'),
			'wednesday' => esc_html__('00:00-23:59', 'better-chat-support'),
			'thursday' => esc_html__('00:00-23:59', 'better-chat-support'),
			'friday' => esc_html__('00:00-23:59', 'better-chat-support'),
			'saturday' => esc_html__('00:00-23:59', 'better-chat-support'),
		), $atts);

		ob_start();

		$button_obj = new CustomButtonsTemplates($atts);

		if (! empty($atts['style'])) {

			// Style Switch
			switch ($atts['style']) {
				case '1':
					$button_obj->mcs_button_s1();
					break;
				case '2':
					$button_obj->mcs_button_s2();
					break;
				default:
					$button_obj->mcs_button_s1();
					break;
			}
		}

		return ob_get_clean();
	}
}
