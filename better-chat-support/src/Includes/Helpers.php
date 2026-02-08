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
}
