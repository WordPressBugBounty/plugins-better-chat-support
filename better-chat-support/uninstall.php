<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * @link       https://themeatelier.net
 * @since      1.0.0
 *
 * @package chat-help-pro
 * @subpackage chat-help-pro/src/Admin/Views/Advance
 * @author     ThemeAtelier<themeatelierbd@gmail.com>
 */

// If uninstall not called from WordPress, then exit.
if (! defined('WP_UNINSTALL_PLUGIN')) {
	exit;
}

/**
 * Delete plugin data function.
 *
 * @return void
 */
function better_chat_support_delete_plugin_data()
{
	// Delete plugin option settings.
	$options = [
		'mcs-opt',
		'better_chat_support_version',
		'better_chat_support_db_version',
		'better_chat_support_first_version',
		'better_chat_support_activation_date',
		'themeatelier_offer_banner_dismissed_new_year_2026',
	];

	foreach ($options as $option_name) {
		delete_option($option_name);       // Delete regular option.
		delete_site_option($option_name); // Delete multisite option.
	}
}

// Load WPTP file.
require plugin_dir_path(__FILE__) . '/messenger-chat-support.php';
$better_chat_support_plugin_settings = get_option('mcs-opt');
$better_chat_support_data_delete     = isset($better_chat_support_plugin_settings['cleanup_data_deletion']) ? $better_chat_support_plugin_settings['cleanup_data_deletion'] : false;

if ($better_chat_support_data_delete) {
	better_chat_support_delete_plugin_data();
}
