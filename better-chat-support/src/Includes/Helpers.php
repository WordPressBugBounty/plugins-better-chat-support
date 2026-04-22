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
 * @package better-chat-support
 * @subpackage better-chat-support/Helpers
 * @author     ThemeAtelier<themeatelierbd@gmail.com>
 */

namespace ThemeAtelier\BetterChatSupport\Includes;

/**
 * The Helpers class to manage all public facing stuffs.
 *
 * @since 1.0.0
 */
class Helpers
{
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
	}

	/**
	 * Register the All scripts for the public-facing side of the site.
	 *
	 * @since    2.0
	 */
	public function register_all_scripts()
	{
		wp_register_style('icofont', BETTER_CHAT_SUPPORT_DIR_URL . 'src/Frontend/assets/css/icofont' . $this->min . '.css', array(), '1.0', false);
		wp_register_style('mcs-main', BETTER_CHAT_SUPPORT_DIR_URL . 'src/Frontend/assets/css/mSupport-main' . $this->min . '.css', array(), '1.0', false);
		/********************
				Js Enqueue
		 ********************/
		wp_register_script('moment', array('jquery'), '1.0', true);
		wp_register_script('moment-timezone', BETTER_CHAT_SUPPORT_DIR_URL . 'src/Frontend/assets/js/moment-timezone-with-data' . $this->min . '.js', array('jquery'), '1.0', true);
		wp_register_script('mcs-main', BETTER_CHAT_SUPPORT_DIR_URL . 'src/Frontend/assets/js/mSupport-main' . $this->min . '.js', array('jquery'), '1.0', true);
	}

	public static function user_availability($optAvailablity)
	{
		$days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];

		$availability = [];
		foreach ($days as $day) {
			$from = !empty($optAvailablity["availablity-$day"]['from']) ? $optAvailablity["availablity-$day"]['from'] : '00:00';
			$to = !empty($optAvailablity["availablity-$day"]['to']) ? $optAvailablity["availablity-$day"]['to'] : '23:59';

			$availability[$day] = "$from-$to";
		}

		return json_encode($availability);
	}


	/**
	 * Custom Template locator .
	 *
	 * @param  mixed $template_name template name .
	 * @param  mixed $template_path template path .
	 * @param  mixed $default_path default path .
	 * @return string
	 */
	public static function better_chat_support_locate_template($template_name, $template_path = '', $default_path = '')
	{
		if (! $template_path) {
			$template_path = 'better-chat-support/templates';
		}
		if (! $default_path) {
			$default_path = BETTER_CHAT_SUPPORT_DIR_PATH . 'src/Frontend/Templates/';
		}
		$template = locate_template(trailingslashit($template_path) . $template_name);
		// Get default template.
		if (! $template) {
			$template = $default_path . $template_name;
		}
		// Return what we found.
		return $template;
	}

	public static function should_display_element($options)
	{
		$visibilities = !empty($options['visibility']) ? $options['visibility'] : [];
		$visibility_by_theme_page = !empty($options['visibility_by_theme_page']) ? $options['visibility_by_theme_page'] : [];
		$theme_page_target = isset($visibility_by_theme_page['theme_page_target']) ? $visibility_by_theme_page['theme_page_target'] : '';
		$theme_page_all = isset($visibility_by_theme_page['theme_page_all']) ? $visibility_by_theme_page['theme_page_all'] : '';
		$theme_page = !empty($visibility_by_theme_page['theme_page']) ? $visibility_by_theme_page['theme_page'] : [];
		$theme_page = array_combine($theme_page, $theme_page);
		$visibility_by_page = !empty($options['visibility_by_page']) ? $options['visibility_by_page'] : [];
		$page_target 		= isset($visibility_by_page['page_target']) ? $visibility_by_page['page_target'] : '';
		$page_all 			= isset($visibility_by_page['page_all']) ? $visibility_by_page['page_all'] : '';
		$page 				= !empty($visibility_by_page['page']) ? $visibility_by_page['page'] : [];
		$current_page_id = get_queried_object_id();

		if (!empty($visibilities)) {
			foreach ($visibilities as $key => $visibility) {

				switch ($visibility) {
					case 'theme_page':
						if (in_array('theme_page', $visibilities)) {
							$page_conditions = array(
								'post_page'        => is_home(),
								'search_page'      => is_search(),
								'404_page'         => is_404(),
							);

							foreach ($page_conditions as $page_key => $is_page) {
								if ($is_page) {
									if ($theme_page_target == 'include') {
										if ($theme_page_all || empty($theme_page)) {
											return true;
										} else {
											if (in_array($page_key, $theme_page)) {
												return true;
											} else {
												return false;
											}
										}
									} else {
										if ($theme_page_all || empty($theme_page)) {
											return false;
										} else {
											if (in_array($page_key, $theme_page)) {
												return false;
											} else {
												return true;
											}
										}
									}
								}
							}
						}
						break;
					case 'page':
						if (in_array('page', $visibilities)) {
							if (is_page()) {
								if ($page_target == 'include') {
									if ($page_all || empty($page)) {
										return true;
									} else {
										if (in_array($current_page_id, $page)) {
											return true;
										} else {
											return false;
										}
									}
								} else {
									if ($page_all || empty($page)) {
										return false;
									} else {
										if (in_array($current_page_id, $page)) {
											return false;
										} else {
											return true;
										}
									}
								}
							}
						}
						break;
				}
			}
		} else {
			return true;
		}
	}
}
