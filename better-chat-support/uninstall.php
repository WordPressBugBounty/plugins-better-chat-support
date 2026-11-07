<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * @link       https://themeatelier.net
 * @since      1.0.0
 *
 * @package better-chat-support
 * @subpackage better-chat-support
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
	global $wpdb;
	// Delete plugin option settings.
	$options = [
		'mcs-opt',
		'mcs_settings',
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

	// Delete plugin cached data (transients).
	$transients = [
		'better_chat_support_recommended_plugins',
		'better_chat_support_recommended_plugins_v2',
		'themeatelier_plugins',
	];
	foreach ($transients as $transient) {
		delete_transient($transient);
		delete_site_transient($transient);
	}

	// Delete the analytics tables: raw events and the daily summaries.
	foreach (['mcs_analytics', 'mcs_analytics_daily', 'mcs_analytics_daily_dims'] as $better_chat_support_table) {
		$table_name = $wpdb->prefix . $better_chat_support_table;
		$wpdb->query("DROP TABLE IF EXISTS `$table_name`"); // phpcs:ignore PluginCheck.Security.DirectDB.UnescapedDBParameter, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.SchemaChange, WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Drops the plugin's own analytics tables on uninstall; names are $wpdb->prefix plus a constant.
	}
	foreach (['mcs_analytics_db_version', 'mcs_analytics_state', 'mcs_analytics_legacy_until'] as $option_name) {
		delete_option($option_name);
	}
	// Job locks and cached dashboard reports.
	$wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE 'mcs\\_analytics\\_lock\\_%' OR option_name LIKE '\\_transient\\_mcs\\_an\\_%' OR option_name LIKE '\\_transient\\_timeout\\_mcs\\_an\\_%'"); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- One-off cleanup of the plugin's own options on uninstall.
	foreach (['mcs_analytics_aggregate', 'mcs_analytics_cleanup', 'mcs_analytics_migrate'] as $better_chat_support_hook) {
		wp_unschedule_hook($better_chat_support_hook);
	}
}

// The "Clean-up Data on Deletion" toggle is saved in the Advanced settings
// option (mcs_settings) — read it from there. Only run cleanup when enabled.
$better_chat_support_settings = get_option('mcs_settings');
// Default OFF: data is only deleted when the toggle is EXPLICITLY enabled.
// A missing key, or any falsy/"false"/"0" value, must never trigger cleanup.
$better_chat_support_cleanup_raw                     = is_array($better_chat_support_settings) ? ($better_chat_support_settings['cleanup_data_deletion'] ?? false) : false;
$better_chat_support_data_delete = (true === $better_chat_support_cleanup_raw || 1 === $better_chat_support_cleanup_raw || '1' === $better_chat_support_cleanup_raw || 'true' === $better_chat_support_cleanup_raw);

if ($better_chat_support_data_delete) {
	better_chat_support_delete_plugin_data();
}
