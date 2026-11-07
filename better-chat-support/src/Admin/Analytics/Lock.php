<?php

namespace ThemeAtelier\BetterChatSupport\Admin\Analytics;

if (! defined('ABSPATH')) {
    die;
}

/**
 * Simple option-based lock so two cron runs never aggregate, clean up or
 * migrate at the same time. Only used by background jobs, never on the
 * tracking path.
 */
class Lock
{
    private static function key($name)
    {
        return 'mcs_analytics_lock_' . $name;
    }

    public static function acquire(string $name, int $ttl): bool
    {
        $key = self::key($name);
        if (add_option($key, time(), '', false)) {
            return true;
        }
        // Take over a lock left behind by a run that died.
        $since = (int) get_option($key, 0);
        if ($since < time() - $ttl) {
            update_option($key, time(), false);
            return true;
        }
        return false;
    }

    public static function release(string $name): void
    {
        delete_option(self::key($name));
    }
}
