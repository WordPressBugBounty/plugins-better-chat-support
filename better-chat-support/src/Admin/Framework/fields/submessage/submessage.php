<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Field: submessage
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'BetterChatSupport_Field_submessage' ) ) {
  class BetterChatSupport_Field_submessage extends BetterChatSupport_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $style = ( ! empty( $this->field['style'] ) ) ? $this->field['style'] : 'normal';

      echo '<div class="better-chat-support-submessage better-chat-support-submessage-'. esc_attr( $style ) .'">'. wp_kses_post($this->field['content']) .'</div>';

    }

  }
}
