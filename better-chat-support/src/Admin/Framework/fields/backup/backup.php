<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Field: backup
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'BetterChatSupport_Field_backup' ) ) {
  class BetterChatSupport_Field_backup extends BetterChatSupport_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $unique = $this->unique;
      $nonce  = wp_create_nonce( 'BetterChatSupport_backup_nonce' );
      $export = add_query_arg( array( 'action' => 'better-chat-support-export', 'unique' => $unique, 'nonce' => $nonce ), admin_url( 'admin-ajax.php' ) );

      echo wp_kses_post($this->field_before());

      echo '<textarea name="BetterChatSupport_import_data" class="better-chat-support-import-data"></textarea>';
      echo '<button type="submit" class="button button-primary better-chat-support-confirm better-chat-support-import" data-unique="'. esc_attr( $unique ) .'" data-nonce="'. esc_attr( $nonce ) .'">'. esc_html__( 'Import', 'better-chat-support' ) .'</button>';
      echo '<hr />';
      echo '<textarea readonly="readonly" class="better-chat-support-export-data">'. esc_attr( wp_json_encode( get_option( $unique ) ) ) .'</textarea>';
      echo '<a href="'. esc_url( $export ) .'" class="button button-primary better-chat-support-export" target="_blank">'. esc_html__( 'Export & Download', 'better-chat-support' ) .'</a>';
      echo '<hr />';
      echo '<button type="submit" name="BetterChatSupport_transient[reset]" value="reset" class="button better-chat-support-warning-primary better-chat-support-confirm better-chat-support-reset" data-unique="'. esc_attr( $unique ) .'" data-nonce="'. esc_attr( $nonce ) .'">'. esc_html__( 'Reset', 'better-chat-support' ) .'</button>';

      echo wp_kses_post($this->field_after());

    }

  }
}
