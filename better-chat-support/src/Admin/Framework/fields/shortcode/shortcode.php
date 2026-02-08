<?php if (!defined('ABSPATH')) {
  die;
} // Cannot access directly.
/**
 *
 * Field: shortcode
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (!class_exists('BetterChatSupport_Field_shortcode')) {
  class BetterChatSupport_Field_shortcode extends BetterChatSupport_Fields
  {

    public function __construct($field, $value = '', $unique = '', $where = '', $parent = '')
    {
      parent::__construct($field, $value, $unique, $where, $parent);
    }

    public function render()
    {
      echo wp_kses_post($this->field_before());
?>
      <textarea type='text' id='shortcode' class='shortcode_input' id='shortcode_after_copy' onClick='this.select();' readonly='readonly'><?php echo esc_attr($this->field['shortcode_text']) ?></textarea>
      <button id="shortcode_copy" class="button button-primary"><?php echo esc_html('Copy Shortcode', 'better-chat-support') ?></button>
      <div class='shortcode_after_copy'><i class='icofont-check-circled'></i><?php echo esc_html('Shortcode Copied to Clipboard!', 'better-chat-support') ?></div>
<?php
      echo (!empty($this->field['label'])) ? '<span class="better-chat-support--label">' . esc_attr($this->field['label']) . '</span>' : '';

      echo wp_kses_post($this->field_after());
    }
  }
}
