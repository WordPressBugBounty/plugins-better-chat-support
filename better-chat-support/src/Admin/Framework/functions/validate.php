<?php if (! defined('ABSPATH')) {
  die;
} // Cannot access directly.
/**
 *
 * Email validate
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (! function_exists('BetterChatSupport_validate_email')) {
  function BetterChatSupport_validate_email($value)
  {

    if (! filter_var($value, FILTER_VALIDATE_EMAIL)) {
      return esc_html__('Please enter a valid email address.', 'better-chat-support');
    }
  }
}

/**
 *
 * Numeric validate
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (! function_exists('BetterChatSupport_validate_numeric')) {
  function BetterChatSupport_validate_numeric($value)
  {

    if (! is_numeric($value)) {
      return esc_html__('Please enter a valid number.', 'better-chat-support');
    }
  }
}

/**
 *
 * Required validate
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (! function_exists('BetterChatSupport_validate_required')) {
  function BetterChatSupport_validate_required($value)
  {

    if (empty($value)) {
      return esc_html__('This field is required.', 'better-chat-support');
    }
  }
}

/**
 *
 * URL validate
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (! function_exists('BetterChatSupport_validate_url')) {
  function BetterChatSupport_validate_url($value)
  {

    if (! filter_var($value, FILTER_VALIDATE_URL)) {
      return esc_html__('Please enter a valid URL.', 'better-chat-support');
    }
  }
}

/**
 *
 * Email validate for Customizer
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (! function_exists('BetterChatSupport_customize_validate_email')) {
  function BetterChatSupport_customize_validate_email($validity, $value, $wp_customize)
  {

    if (! sanitize_email($value)) {
      $validity->add('required', esc_html__('Please enter a valid email address.', 'better-chat-support'));
    }

    return $validity;
  }
}

/**
 *
 * Numeric validate for Customizer
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (! function_exists('BetterChatSupport_customize_validate_numeric')) {
  function BetterChatSupport_customize_validate_numeric($validity, $value, $wp_customize)
  {

    if (! is_numeric($value)) {
      $validity->add('required', esc_html__('Please enter a valid number.', 'better-chat-support'));
    }

    return $validity;
  }
}

/**
 *
 * Required validate for Customizer
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (! function_exists('BetterChatSupport_customize_validate_required')) {
  function BetterChatSupport_customize_validate_required($validity, $value, $wp_customize)
  {

    if (empty($value)) {
      $validity->add('required', esc_html__('This field is required.', 'better-chat-support'));
    }

    return $validity;
  }
}

/**
 *
 * URL validate for Customizer
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (! function_exists('BetterChatSupport_customize_validate_url')) {
  function BetterChatSupport_customize_validate_url($validity, $value, $wp_customize)
  {

    if (! filter_var($value, FILTER_VALIDATE_URL)) {
      $validity->add('required', esc_html__('Please enter a valid URL.', 'better-chat-support'));
    }

    return $validity;
  }
}
