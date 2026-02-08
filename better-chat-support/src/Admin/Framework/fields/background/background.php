<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Field: background
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
use ThemeAtelier\BetterChatSupport\Admin\Framework\Classes\BetterChatSupport;

if ( ! class_exists( 'BetterChatSupport_Field_background' ) ) {
  class BetterChatSupport_Field_background extends BetterChatSupport_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $args                             = wp_parse_args( $this->field, array(
        'background_color'              => true,
        'background_image'              => true,
        'background_position'           => true,
        'background_repeat'             => true,
        'background_attachment'         => true,
        'background_size'               => true,
        'background_origin'             => false,
        'background_clip'               => false,
        'background_blend_mode'         => false,
        'background_gradient'           => false,
        'background_gradient_color'     => true,
        'background_gradient_direction' => true,
        'background_image_preview'      => true,
        'background_auto_attributes'    => false,
        'compact'                       => false,
        'background_image_library'      => 'image',
        'background_image_placeholder'  => esc_html__( 'Not selected', 'better-chat-support' ),
      ) );

      if ( $args['compact'] ) {
        $args['background_color']           = false;
        $args['background_auto_attributes'] = true;
      }

      $default_value                    = array(
        'background-color'              => '',
        'background-image'              => '',
        'background-position'           => '',
        'background-repeat'             => '',
        'background-attachment'         => '',
        'background-size'               => '',
        'background-origin'             => '',
        'background-clip'               => '',
        'background-blend-mode'         => '',
        'background-gradient-color'     => '',
        'background-gradient-direction' => '',
      );

      $default_value = ( ! empty( $this->field['default'] ) ) ? wp_parse_args( $this->field['default'], $default_value ) : $default_value;

      $this->value = wp_parse_args( $this->value, $default_value );

      echo wp_kses_post($this->field_before());

      echo '<div class="better-chat-support--background-colors">';

      //
      // Background Color
      if ( ! empty( $args['background_color'] ) ) {

        echo '<div class="better-chat-support--color">';

        echo ( ! empty( $args['background_gradient'] ) ) ? '<div class="better-chat-support--title">'. esc_html__( 'From', 'better-chat-support' ) .'</div>' : '';

        BetterChatSupport::field( array(
          'id'      => 'background-color',
          'type'    => 'color',
          'default' => $default_value['background-color'],
        ), $this->value['background-color'], $this->field_name(), 'field/background' );

        echo '</div>';

      }

      //
      // Background Gradient Color
      if ( ! empty( $args['background_gradient_color'] ) && ! empty( $args['background_gradient'] ) ) {

        echo '<div class="better-chat-support--color">';

        echo ( ! empty( $args['background_gradient'] ) ) ? '<div class="better-chat-support--title">'. esc_html__( 'To', 'better-chat-support' ) .'</div>' : '';

        BetterChatSupport::field( array(
          'id'      => 'background-gradient-color',
          'type'    => 'color',
          'default' => $default_value['background-gradient-color'],
        ), $this->value['background-gradient-color'], $this->field_name(), 'field/background' );

        echo '</div>';

      }

      //
      // Background Gradient Direction
      if ( ! empty( $args['background_gradient_direction'] ) && ! empty( $args['background_gradient'] ) ) {

        echo '<div class="better-chat-support--color">';

        echo ( ! empty( $args['background_gradient'] ) ) ? '<div class="better-chat-support---title">'. esc_html__( 'Direction', 'better-chat-support' ) .'</div>' : '';

        BetterChatSupport::field( array(
          'id'          => 'background-gradient-direction',
          'type'        => 'select',
          'options'     => array(
            ''          => esc_html__( 'Gradient Direction', 'better-chat-support' ),
            'to bottom' => esc_html__( '&#8659; top to bottom', 'better-chat-support' ),
            'to right'  => esc_html__( '&#8658; left to right', 'better-chat-support' ),
            '135deg'    => esc_html__( '&#8664; corner top to right', 'better-chat-support' ),
            '-135deg'   => esc_html__( '&#8665; corner top to left', 'better-chat-support' ),
          ),
        ), $this->value['background-gradient-direction'], $this->field_name(), 'field/background' );

        echo '</div>';

      }

      echo '</div>';

      //
      // Background Image
      if ( ! empty( $args['background_image'] ) ) {

        echo '<div class="better-chat-support--background-image">';

        BetterChatSupport::field( array(
          'id'          => 'background-image',
          'type'        => 'media',
          'class'       => 'better-chat-support-assign-field-background',
          'library'     => $args['background_image_library'],
          'preview'     => $args['background_image_preview'],
          'placeholder' => $args['background_image_placeholder'],
          'attributes'  => array( 'data-depend-id' => $this->field['id'] ),
        ), $this->value['background-image'], $this->field_name(), 'field/background' );

        echo '</div>';

      }

      $auto_class   = ( ! empty( $args['background_auto_attributes'] ) ) ? ' better-chat-support--auto-attributes' : '';
      $hidden_class = ( ! empty( $args['background_auto_attributes'] ) && empty( $this->value['background-image']['url'] ) ) ? ' better-chat-support--attributes-hidden' : '';

      echo '<div class="better-chat-support--background-attributes'. esc_attr( $auto_class . $hidden_class ) .'">';

      //
      // Background Position
      if ( ! empty( $args['background_position'] ) ) {

        BetterChatSupport::field( array(
          'id'              => 'background-position',
          'type'            => 'select',
          'options'         => array(
            ''              => esc_html__( 'Background Position', 'better-chat-support' ),
            'left top'      => esc_html__( 'Left Top', 'better-chat-support' ),
            'left center'   => esc_html__( 'Left Center', 'better-chat-support' ),
            'left bottom'   => esc_html__( 'Left Bottom', 'better-chat-support' ),
            'center top'    => esc_html__( 'Center Top', 'better-chat-support' ),
            'center center' => esc_html__( 'Center Center', 'better-chat-support' ),
            'center bottom' => esc_html__( 'Center Bottom', 'better-chat-support' ),
            'right top'     => esc_html__( 'Right Top', 'better-chat-support' ),
            'right center'  => esc_html__( 'Right Center', 'better-chat-support' ),
            'right bottom'  => esc_html__( 'Right Bottom', 'better-chat-support' ),
          ),
        ), $this->value['background-position'], $this->field_name(), 'field/background' );

      }

      //
      // Background Repeat
      if ( ! empty( $args['background_repeat'] ) ) {

        BetterChatSupport::field( array(
          'id'          => 'background-repeat',
          'type'        => 'select',
          'options'     => array(
            ''          => esc_html__( 'Background Repeat', 'better-chat-support' ),
            'repeat'    => esc_html__( 'Repeat', 'better-chat-support' ),
            'no-repeat' => esc_html__( 'No Repeat', 'better-chat-support' ),
            'repeat-x'  => esc_html__( 'Repeat Horizontally', 'better-chat-support' ),
            'repeat-y'  => esc_html__( 'Repeat Vertically', 'better-chat-support' ),
          ),
        ), $this->value['background-repeat'], $this->field_name(), 'field/background' );

      }

      //
      // Background Attachment
      if ( ! empty( $args['background_attachment'] ) ) {

        BetterChatSupport::field( array(
          'id'       => 'background-attachment',
          'type'     => 'select',
          'options'  => array(
            ''       => esc_html__( 'Background Attachment', 'better-chat-support' ),
            'scroll' => esc_html__( 'Scroll', 'better-chat-support' ),
            'fixed'  => esc_html__( 'Fixed', 'better-chat-support' ),
          ),
        ), $this->value['background-attachment'], $this->field_name(), 'field/background' );

      }

      //
      // Background Size
      if ( ! empty( $args['background_size'] ) ) {

        BetterChatSupport::field( array(
          'id'        => 'background-size',
          'type'      => 'select',
          'options'   => array(
            ''        => esc_html__( 'Background Size', 'better-chat-support' ),
            'cover'   => esc_html__( 'Cover', 'better-chat-support' ),
            'contain' => esc_html__( 'Contain', 'better-chat-support' ),
            'auto'    => esc_html__( 'Auto', 'better-chat-support' ),
          ),
        ), $this->value['background-size'], $this->field_name(), 'field/background' );

      }

      //
      // Background Origin
      if ( ! empty( $args['background_origin'] ) ) {

        BetterChatSupport::field( array(
          'id'            => 'background-origin',
          'type'          => 'select',
          'options'       => array(
            ''            => esc_html__( 'Background Origin', 'better-chat-support' ),
            'padding-box' => esc_html__( 'Padding Box', 'better-chat-support' ),
            'border-box'  => esc_html__( 'Border Box', 'better-chat-support' ),
            'content-box' => esc_html__( 'Content Box', 'better-chat-support' ),
          ),
        ), $this->value['background-origin'], $this->field_name(), 'field/background' );

      }

      //
      // Background Clip
      if ( ! empty( $args['background_clip'] ) ) {

        BetterChatSupport::field( array(
          'id'            => 'background-clip',
          'type'          => 'select',
          'options'       => array(
            ''            => esc_html__( 'Background Clip', 'better-chat-support' ),
            'border-box'  => esc_html__( 'Border Box', 'better-chat-support' ),
            'padding-box' => esc_html__( 'Padding Box', 'better-chat-support' ),
            'content-box' => esc_html__( 'Content Box', 'better-chat-support' ),
          ),
        ), $this->value['background-clip'], $this->field_name(), 'field/background' );

      }

      //
      // Background Blend Mode
      if ( ! empty( $args['background_blend_mode'] ) ) {

        BetterChatSupport::field( array(
          'id'            => 'background-blend-mode',
          'type'          => 'select',
          'options'       => array(
            ''            => esc_html__( 'Background Blend Mode', 'better-chat-support' ),
            'normal'      => esc_html__( 'Normal', 'better-chat-support' ),
            'multiply'    => esc_html__( 'Multiply', 'better-chat-support' ),
            'screen'      => esc_html__( 'Screen', 'better-chat-support' ),
            'overlay'     => esc_html__( 'Overlay', 'better-chat-support' ),
            'darken'      => esc_html__( 'Darken', 'better-chat-support' ),
            'lighten'     => esc_html__( 'Lighten', 'better-chat-support' ),
            'color-dodge' => esc_html__( 'Color Dodge', 'better-chat-support' ),
            'saturation'  => esc_html__( 'Saturation', 'better-chat-support' ),
            'color'       => esc_html__( 'Color', 'better-chat-support' ),
            'luminosity'  => esc_html__( 'Luminosity', 'better-chat-support' ),
          ),
        ), $this->value['background-blend-mode'], $this->field_name(), 'field/background' );

      }

      echo '</div>';

      echo wp_kses_post($this->field_after());

    }

    public function output() {

      $output    = '';
      $bg_image  = array();
      $important = ( ! empty( $this->field['output_important'] ) ) ? '!important' : '';
      $element   = ( is_array( $this->field['output'] ) ) ? join( ',', $this->field['output'] ) : $this->field['output'];

      // Background image and gradient
      $background_color        = ( ! empty( $this->value['background-color']              ) ) ? $this->value['background-color']              : '';
      $background_gd_color     = ( ! empty( $this->value['background-gradient-color']     ) ) ? $this->value['background-gradient-color']     : '';
      $background_gd_direction = ( ! empty( $this->value['background-gradient-direction'] ) ) ? $this->value['background-gradient-direction'] : '';
      $background_image        = ( ! empty( $this->value['background-image']['url']       ) ) ? $this->value['background-image']['url']       : '';


      if ( $background_color && $background_gd_color ) {
        $gd_direction   = ( $background_gd_direction ) ? $background_gd_direction .',' : '';
        $bg_image[] = 'linear-gradient('. $gd_direction . $background_color .','. $background_gd_color .')';
        unset( $this->value['background-color'] );
      }

      if ( $background_image ) {
        $bg_image[] = 'url('. $background_image .')';
      }

      if ( ! empty( $bg_image ) ) {
        $output .= 'background-image:'. implode( ',', $bg_image ) . $important .';';
      }

      // Common background properties
      $properties = array( 'color', 'position', 'repeat', 'attachment', 'size', 'origin', 'clip', 'blend-mode' );

      foreach ( $properties as $property ) {
        $property = 'background-'. $property;
        if ( ! empty( $this->value[$property] ) ) {
          $output .= $property .':'. $this->value[$property] . $important .';';
        }
      }

      if ( $output ) {
        $output = $element .'{'. $output .'}';
      }

      $this->parent->output_css .= $output;

      return $output;

    }

  }
}
