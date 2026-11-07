<?php

namespace ThemeAtelier\BetterChatSupport\Admin\Analytics;

if (! defined('ABSPATH')) {
    die;
}

/**
 * Analytics settings, stored in the Advanced settings option (mcs_settings).
 * That option is autoloaded, so reading it costs no query.
 */
class Settings
{
    const DEFAULT_RETENTION_DAYS = 90;
    const MIN_RETENTION_DAYS     = 7;
    const MAX_RETENTION_DAYS     = 3650;

    private static function get($key, $default = null)
    {
        $settings = get_option('mcs_settings', []);
        return is_array($settings) && array_key_exists($key, $settings) ? $settings[$key] : $default;
    }

    /**
     * Days to keep raw events. 0 keeps them forever. Daily summaries are
     * never deleted, so reports for older dates keep working.
     */
    public static function retention_days(): int
    {
        $value = self::get('analytics_retention_days', self::DEFAULT_RETENTION_DAYS);
        if ($value === '' || $value === null || ! is_numeric($value) || (int) $value < 0) {
            return self::DEFAULT_RETENTION_DAYS;
        }
        $value = (int) $value;
        if ($value === 0) {
            return 0;
        }
        return max(self::MIN_RETENTION_DAYS, min(self::MAX_RETENTION_DAYS, $value));
    }

    /** Track logged-in users who can edit posts. Off unless explicitly enabled. */
    public static function track_editors(): bool
    {
        $value = self::get('analytics_track_editors', false);
        return true === $value || 1 === $value || '1' === $value || 'true' === $value;
    }
}
