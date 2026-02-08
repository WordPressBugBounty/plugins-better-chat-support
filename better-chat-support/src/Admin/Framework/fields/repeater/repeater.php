<?php
/**
 *
 * Field: repeater
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
use ThemeAtelier\BetterChatSupport\Admin\Framework\Classes\BetterChatSupport;

if ( ! class_exists( 'BetterChatSupport_Field_repeater' ) ) {
  class BetterChatSupport_Field_repeater extends BetterChatSupport_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $args = wp_parse_args( $this->field, array(
        'max'          => 0,
        'min'          => 0,
        'button_title' => '<i class="icofont-plus"></i>',
      ) );

      if ( preg_match( '/'. preg_quote( '['. $this->field['id'] .']' ) .'/', $this->unique ) ) {

        echo '<div class="better-chat-support-notice better-chat-support-notice-danger">'. esc_html__( 'Error: Field ID conflict.', 'better-chat-support' ) .'</div>';

      } else {

        echo wp_kses_post($this->field_before());

        echo '<div class="better-chat-support-repeater-item better-chat-support-repeater-hidden" data-depend-id="'. esc_attr( $this->field['id'] ) .'">';
        echo '<div class="better-chat-support-repeater-content">';
        foreach ( $this->field['fields'] as $field ) {

          $field_default = ( isset( $field['default'] ) ) ? $field['default'] : '';
          $field_unique  = ( ! empty( $this->unique ) ) ? $this->unique .'['. $this->field['id'] .'][0]' : $this->field['id'] .'[0]';

          BetterChatSupport::field( $field, $field_default, '___'. $field_unique, 'field/repeater' );

        }
        echo '</div>';
        echo '<div class="better-chat-support-repeater-helper">';
        echo '<div class="better-chat-support-repeater-helper-inner">';
        echo '<i class="better-chat-support-repeater-sort icofont-drag"></i>';
        echo '<i class="better-chat-support-repeater-clone icofont-copy-invert"></i>';
        echo '<i class="better-chat-support-repeater-remove better-chat-support-confirm icofont-close" data-confirm="'. esc_html__( 'Are you sure to delete this item?', 'better-chat-support' ) .'"></i>';
        echo '</div>';
        echo '</div>';
        echo '</div>';

        echo '<div class="better-chat-support-repeater-wrapper better-chat-support-data-wrapper" data-field-id="['. esc_attr( $this->field['id'] ) .']" data-max="'. esc_attr( $args['max'] ) .'" data-min="'. esc_attr( $args['min'] ) .'">';

        if ( ! empty( $this->value ) && is_array( $this->value ) ) {

          $num = 0;

          foreach ( $this->value as $key => $value ) {

            echo '<div class="better-chat-support-repeater-item">';
            echo '<div class="better-chat-support-repeater-content">';
            foreach ( $this->field['fields'] as $field ) {

              $field_unique = ( ! empty( $this->unique ) ) ? $this->unique .'['. $this->field['id'] .']['. $num .']' : $this->field['id'] .'['. $num .']';
              $field_value  = ( isset( $field['id'] ) && isset( $this->value[$key][$field['id']] ) ) ? $this->value[$key][$field['id']] : '';

              BetterChatSupport::field( $field, $field_value, $field_unique, 'field/repeater' );

            }
            echo '</div>';
            echo '<div class="better-chat-support-repeater-helper">';
            echo '<div class="better-chat-support-repeater-helper-inner">';
            echo '<i class="better-chat-support-repeater-sort icofont-drag"></i>';
            echo '<i class="better-chat-support-repeater-clone icofont-copy-invert"></i>';
            echo '<i class="better-chat-support-repeater-remove better-chat-support-confirm icofont-close" data-confirm="'. esc_html__( 'Are you sure to delete this item?', 'better-chat-support' ) .'"></i>';
            echo '</div>';
            echo '</div>';
            echo '</div>';

            $num++;

          }

        }

        echo '</div>';

        echo '<div class="better-chat-support-repeater-alert better-chat-support-repeater-max">'. esc_html__( 'You cannot add more.', 'better-chat-support' ) .'</div>';
        echo '<div class="better-chat-support-repeater-alert better-chat-support-repeater-min">'. esc_html__( 'You cannot remove more.', 'better-chat-support' ) .'</div>';
        echo '<a href="#" class="button button-primary better-chat-support-repeater-add">'. wp_kses_post($args['button_title']) .'</a>';

        echo wp_kses_post($this->field_after());

      }

    }

    public function enqueue() {

      if ( ! wp_script_is( 'jquery-ui-sortable' ) ) {
        wp_enqueue_script( 'jquery-ui-sortable' );
      }

    }

  }
}
