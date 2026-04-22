<?php
// don't call the file directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Better Chat Support Pro Agent Message.
 *
 * @package    better-chat-support
 * @subpackage better-chat-support/src/Frontend
 */
if (!empty($agent_photo_url)) {
?>
    <div class="image">
        <img src="<?php echo esc_attr($agent_photo_url); ?>" />
    </div>
<?php }