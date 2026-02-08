<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Field: icon
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'BetterChatSupport_Field_icon' ) ) {
  class BetterChatSupport_Field_icon extends BetterChatSupport_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $args = wp_parse_args( $this->field, array(
        'button_title' => esc_html__( 'Add Icon', 'better-chat-support' ),
        'remove_title' => esc_html__( 'Remove Icon', 'better-chat-support' ),
      ) );

      echo wp_kses_post($this->field_before());

      $nonce  = wp_create_nonce( 'BetterChatSupport_icon_nonce' );
      $hidden = ( empty( $this->value ) ) ? ' hidden' : '';

      echo '<div class="better-chat-support-icon-select">';
      echo '<span class="better-chat-support-icon-preview'. esc_attr( $hidden ) .'"><i class="'. esc_attr( $this->value ) .'"></i></span>';
      echo '<a href="#" class="button button-primary better-chat-support-icon-add" data-nonce="'. esc_attr( $nonce ) .'">'. esc_html($args['button_title']) .'</a>';
      echo '<a href="#" class="button better-chat-support-warning-primary better-chat-support-icon-remove'. esc_attr( $hidden ) .'">'. wp_kses_post($args['remove_title']) .'</a>';
      echo '<input type="hidden" name="'. esc_attr( $this->field_name() ) .'" value="'. esc_attr( $this->value ) .'" class="better-chat-support-icon-value"'. wp_kses_post($this->field_attributes()) .' />';
      echo '</div>';

      echo wp_kses_post($this->field_after());

    }

    public function enqueue() {
      add_action( 'admin_footer', array( 'BetterChatSupport_Field_icon', 'add_footer_modal_icon' ) );
      add_action( 'customize_controls_print_footer_scripts', array( 'BetterChatSupport_Field_icon', 'add_footer_modal_icon' ) );
    }

    public static function add_footer_modal_icon() {
    ?>
      <div id="better-chat-support-modal-icon" class="better-chat-support-modal better-chat-support-modal-icon hidden">
        <div class="better-chat-support-modal-table">
          <div class="better-chat-support-modal-table-cell">
            <div class="better-chat-support-modal-overlay"></div>
            <div class="better-chat-support-modal-inner">
              <div class="better-chat-support-modal-title">
                <?php esc_html_e( 'Add Icon', 'better-chat-support' ); ?>
                <div class="better-chat-support-modal-close better-chat-support-icon-close"></div>
              </div>
              <div class="better-chat-support-modal-header">
                <input type="text" placeholder="<?php esc_html_e( 'Search...', 'better-chat-support' ); ?>" class="better-chat-support-icon-search" />
              </div>
              <div class="better-chat-support-modal-content">
                <div class="better-chat-support-modal-loading"><div class="better-chat-support-loading"></div></div>
                <div class="better-chat-support-modal-load"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php
    }

  }
}
