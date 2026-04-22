<?php
// don't call the file directly.

use ThemeAtelier\BetterChatSupport\Includes\Helpers;

if (! defined('ABSPATH')) {
    exit;
}

/**
 * Better Chat Support Pro Agent Message.
 *
 * @package    better-chat-support
 * @subpackage better-chat-support/src/Frontend
 */


?>
<div class="mSupport__popup--header 
    <?php echo $header_content_position === 'center' ? 'header-center' : ''; ?>">
    <?php include Helpers::better_chat_support_locate_template('items/thumbnail.php'); ?>
    <div class="info">
        <?php if ($agent_name) : ?>
            <div class="info__name"><?php echo esc_html($agent_name); ?></div>
        <?php endif;
        if ($agent_subtitle || $offline_agent_subtitle) : ?>
            <div class="info__title" data-online="<?php echo esc_attr($agent_subtitle); ?>"
                data-offline="<?php echo esc_attr($offline_agent_subtitle); ?>"><?php echo esc_html($agent_subtitle); ?></div>
        <?php endif; ?>
    </div>
</div>