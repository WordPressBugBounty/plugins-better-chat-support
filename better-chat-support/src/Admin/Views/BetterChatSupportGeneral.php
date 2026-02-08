<?php

/**
 * Views class for Shortcode generator options.
 *
 * @link       https://themeatelier.net
 * @since      1.0.0
 *
 * @package better-chat-support
 * @subpackage better-chat-support/Admin/Views/BetterChatSupportGeneral
 * @author     ThemeAtelier<themeatelierbd@gmail.com>
 */

namespace ThemeAtelier\BetterChatSupport\Admin\Views;

use ThemeAtelier\BetterChatSupport\Admin\Framework\Classes\BetterChatSupport;

class BetterChatSupportGeneral
{
    /**
     * Create Option fields for the setting options.
     *
     * @param string $prefix Option setting key prefix.
     * @return void
     */
    public static function options($prefix, $timezones)
    {
        //
        // Create a section
        //
        BetterChatSupport::createSection($prefix, array(
            'title'  => esc_html__('General', 'better-chat-support'),
            'icon'   => 'icofont-facebook-messenger',
            'fields' => array(

                // changing chat type
                array(
                    'type' => 'switcher',
                    'id'   => 'enable_floating_chat',
                    'title' => esc_html__('Enable Floating Chat', 'better-chat-support'),
                    'desc' => esc_html__('Turn ON to show floating chat bubble on your website.', 'better-chat-support'),
                    'default' => true,
                ),
                array(
                    'id'         => 'opt-chat-type',
                    'type'       => 'button_set',
                    'title'      => esc_html__('Bubble Type', 'better-chat-support'),
                    'desc' => esc_html__('Choose how the chat bubble handles users', 'better-chat-support'),
                    'default' => 'single',
                    'dependency' => array('enable_floating_chat', '==', 'true'),
                    'options'    => array(
                        'single'  => array(
                            'text'  => esc_html__('Single User', 'better-chat-support'),
                        ),
                        'multi' => array(
                            'text' => esc_html__('Multiple Users', 'better-chat-support'),
                            'pro_only' => true,
                        ),
                    ),
                ),

                // adding contact number
                array(
                    'id'    => 'opt-fbid',
                    'type'  => 'text',
                    'title' => esc_html__('Facebook ID', 'better-chat-support'),
                    'title_help'  => esc_html__('Add your profile or page ID to receive messages.', 'better-chat-support'),
                    'desc'  => esc_html__('If your link is facebook.com/themeatelier, just use themeatelier in this field.', 'better-chat-support'),
                    'dependency' => array('enable_floating_chat|opt-chat-type', '==|any', 'true|single'),
                    'placeholder'   => 'E.g. themeatelier',
                ),

                // changeing timezone
                array(
                    'id'    => 'select-timezone',
                    'type'  => 'select',
                    'title' => esc_html__('Timezone', 'better-chat-support'),
                    'desc' => esc_html__('Select your local timezone. Availability schedules will be applied based on this timezone.)', 'better-chat-support'),
                    'chosen'      => true,
                    'placeholder' => esc_html__('Select Timezone', 'better-chat-support'),
                    'dependency' => array('enable_floating_chat|opt-chat-type', '==|any', 'true|single'),
                    'options' => $timezones,
                ),

                // Add availablity
                array(
                    'id'    => 'opt-availablity',
                    'type'  => 'tabbed',
                    'title' => esc_html__('Availability', 'better-chat-support'),
                    'title_help' => esc_html__('Set your daily availability using 24-hour format (e.g., 09:00 to 18:00). To mark a full day as offline, set both From and To values to 00:00.', 'better-chat-support'),
                    'dependency' => array('enable_floating_chat|opt-chat-type', '==|any', 'true|single'),
                    // sunday
                    'tabs'  => array(
                        array(
                            'title'  => esc_html__('Sunday', 'better-chat-support'),
                            'fields' => array(
                                array(
                                    'id'       => 'availablity-sunday',
                                    'type'     => 'datetime',
                                    'from_to'  => true,
                                    'settings' => array(
                                        'noCalendar' => true,
                                        'enableTime' => true,
                                        'dateFormat' => 'H:i',
                                        'time_24hr'  => true,
                                    ),
                                ),
                            ),
                        ),
                        // monday
                        array(
                            'title'  => esc_html__('Monday', 'better-chat-support'),
                            'fields' => array(
                                array(
                                    'id'       => 'availablity-monday',
                                    'type'     => 'datetime',
                                    'from_to'  => true,
                                    'settings' => array(
                                        'noCalendar' => true,
                                        'enableTime' => true,
                                        'dateFormat' => 'H:i',
                                        'time_24hr'  => true,
                                    ),
                                ),
                            ),
                        ),
                        // tuesday
                        array(
                            'title'  => esc_html__('Tuesday', 'better-chat-support'),
                            'fields' => array(
                                array(
                                    'id'       => 'availablity-tuesday',
                                    'type'     => 'datetime',
                                    'from_to'  => true,
                                    'settings' => array(
                                        'noCalendar' => true,
                                        'enableTime' => true,
                                        'dateFormat' => 'H:i',
                                        'time_24hr'  => true,
                                    ),
                                ),
                            ),
                        ),
                        // wednesday
                        array(
                            'title'  => esc_html__('Wednesday', 'better-chat-support'),
                            'fields' => array(
                                array(
                                    'id'       => 'availablity-wednesday',
                                    'type'     => 'datetime',
                                    'from_to'  => true,
                                    'settings' => array(
                                        'noCalendar' => true,
                                        'enableTime' => true,
                                        'dateFormat' => 'H:i',
                                        'time_24hr'  => true,
                                    ),
                                ),
                            ),
                        ),

                        // thursday
                        array(
                            'title'  => esc_html__('Thursday', 'better-chat-support'),
                            'fields' => array(
                                array(
                                    'id'       => 'availablity-thursday',
                                    'type'     => 'datetime',
                                    'from_to'  => true,
                                    'settings' => array(
                                        'noCalendar' => true,
                                        'enableTime' => true,
                                        'dateFormat' => 'H:i',
                                        'time_24hr'  => true,
                                    ),
                                ),
                            ),
                        ),

                        // friday
                        array(
                            'title'  => esc_html__('Friday', 'better-chat-support'),
                            'fields' => array(
                                array(
                                    'id'       => 'availablity-friday',
                                    'type'     => 'datetime',
                                    'from_to'  => true,
                                    'settings' => array(
                                        'noCalendar' => true,
                                        'enableTime' => true,
                                        'dateFormat' => 'H:i',
                                        'time_24hr'  => true,
                                    ),
                                ),
                            ),
                        ),

                        // thursday
                        array(
                            'title'  => esc_html__('Saturday', 'better-chat-support'),
                            'fields' => array(
                                array(
                                    'id'       => 'availablity-saturday',
                                    'type'     => 'datetime',
                                    'from_to'  => true,
                                    'settings' => array(
                                        'noCalendar' => true,
                                        'enableTime' => true,
                                        'dateFormat' => 'H:i',
                                        'time_24hr'  => true,
                                    ),
                                ),
                            ),
                        ),

                    ),
                ),

                // adding agent photo
                array(
                    'id'    => 'agent-photo',
                    'type'    => 'media',
                    'title'   => esc_html__('Agent Photo', 'better-chat-support'),
                    'title_help' => esc_html__('Upload an agent photo to display inside the chat bubble.', 'better-chat-support'),
                    'library' => 'image',
                    'preview' => true,
                    'dependency' => array('enable_floating_chat|opt-chat-type', '==|any', 'true|single'),
                    'default' => [
                        'url' => BETTER_CHAT_SUPPORT_DIR_URL . 'assets/image/user.webp',
                    ],
                ),

                // agent name
                array(
                    'id'    => 'agent-name',
                    'type'    => 'text',
                    'title'   => esc_html__('Agent Name', 'better-chat-support'),
                    'title_help' => esc_html__('Enter the agent name to display inside the chat bubble.', 'better-chat-support'),
                    'default' => esc_html__('John Doe', 'better-chat-support'),
                    'dependency' => array('enable_floating_chat|opt-chat-type', '==|any', 'true|single'),
                ),

                // agent subtitle
                array(
                    'id'    => 'agent-subtitle',
                    'type'    => 'text',
                    'title'   => esc_html__('Subtitle', 'better-chat-support'),
                    'title_help' => esc_html__('Enter a subtitle to display below the agent’s name in the chat bubble.', 'better-chat-support'),
                    'default' => esc_html__('Typically replies within a day', 'better-chat-support'),
                    'dependency' => array('enable_floating_chat|opt-chat-type', '==|any', 'true|single'),
                ),

                // agent subtitle
                array(
                    'id'    => 'agent-message',
                    'type'    => 'textarea',
                    'title'   => esc_html__('Message From Agent', 'better-chat-support'),
                    'title_help' => esc_html__('Add a custom message to display inside the agent’s message box.', 'better-chat-support'),
                    'default' => esc_html__('Hello, Welcome to the site. Please click below button for chatting me through messenger.', 'better-chat-support'),
                    'dependency' => array('enable_floating_chat|opt-chat-type', '==|any', 'true|single'),
                ),

                // before chat icon
                array(
                    'id'    => 'before-chat-icon',
                    'type'  => 'icon',
                    'title' => esc_html__('Icon for Send Message Button', 'better-chat-support'),
                    'default' => 'icofont-facebook-messenger',
                    'title_help' => esc_html__('Select an icon to display before the send message button text.', 'better-chat-support'),
                    'dependency' => array('enable_floating_chat|opt-chat-type', '==|any', 'true|single'),
                ),

                // agent subtitle
                array(
                    'id'    => 'chat-button-text',
                    'type'    => 'text',
                    'title'   => esc_html__('Send Message Button Label', 'better-chat-support'),
                    'title_help' => esc_html__('Enter the text to display on the send message button.', 'better-chat-support'),
                    'default' => esc_html__('Send a message', 'better-chat-support'),
                    'dependency' => array('enable_floating_chat|opt-chat-type', '==|any', 'true|single'),
                ),
                // Autometically show popup
                array(
                    'id'    => 'autoshow-popup',
                    'type'    => 'switcher',
                    'title'   => esc_html__('Auto Open Popup', 'better-chat-support'),
                    'title_help' => esc_html__('Enable this option to automatically open the chat popup when the page loads. Useful for drawing visitor attention without requiring a click.', 'better-chat-support'),
                    'default' => false,
                    'dependency' => array('enable_floating_chat', '==', 'true'),
                ),

                // Bubble position
                array(
                    'id'      => 'bubble-position',
                    'type'    => 'button_set',
                    'title'   => esc_html__('Bubble Position', 'better-chat-support'),
                    'title_help' => esc_html__('Select the screen position where the floating chat button will appear.', 'better-chat-support'),
                    'default' => 'right',
                    'options'    => array(
                        'right'  => esc_html__('Bottom Right', 'better-chat-support'),
                        'left' => esc_html__('Bottom Left', 'better-chat-support'),
                    ),
                    'dependency' => array('enable_floating_chat', '==', 'true'),
                ),
                array(
                    'id'    => 'right_bottom',
                    'type'  => 'spacing',
                    'title' => esc_html__('Margin From Bottom Right', 'better-chat-support'),
                    'title_help' => esc_html__('Set the margin (spacing) of the floating chat button from the bottom and right edges of the screen. Adjust to reposition the button as needed.', 'better-chat-support'),
                    'top'   => false,
                    'left'  => false,
                    'default'  => array(
                        'right'    => '30',
                        'bottom'  => '30',
                        'unit'   => 'px',
                    ),
                    'dependency' => array('enable_floating_chat|bubble-position', '==|==', 'true|right'),
                ),
                array(
                    'id'    => 'left_bottom',
                    'type'  => 'spacing',
                    'title' => esc_html__('Margin from Bottom Left', 'better-chat-support'),
                    'title_help' => esc_html__('Set the margin (spacing) of the floating chat button from the bottom and left edges of the screen. Adjust to reposition the button as needed.', 'better-chat-support'),
                    'top'   => false,
                    'right'  => false,
                    'default'  => array(
                        'left'    => '30',
                        'bottom'  => '30',
                        'unit'   => 'px',
                    ),
                    'dependency' => array('enable_floating_chat|bubble-position', '==|==', 'true|left'),
                ),
                // bubble visibility
                array(
                    'id'      => 'bubble-visibility',
                    'type'    => 'button_set',
                    'title'   => esc_html__('Device Visibility', 'better-chat-support'),
                    'title_help' => '<b>' . esc_html__('Everywhere', 'better-chat-support') . '</b> - ' . esc_html__('Visible on all devices.', 'better-chat-support') . '<br>' . '<b>' . esc_html__('Desktop Only', 'better-chat-support') . '</b> - ' . esc_html__('Visible on devices wider than 991px.', 'better-chat-support') . '<br>' . '<b>' . esc_html__('Tablet Only', 'better-chat-support') . '</b> - ' . esc_html__(' Visible on devices between 576px and 991px.', 'better-chat-support') . '<br>' . '<b>' . esc_html__('Mobile Only', 'better-chat-support') . '</b> - ' . esc_html__('Visible on devices smaller than 576px.', 'better-chat-support'),
                    'default' => 'everywhere',
                    'options'    => array(
                        'everywhere'  => esc_html__('Everywhere', 'better-chat-support'),
                        'desktop' => esc_html__('Desktop Only', 'better-chat-support'),
                        'tablet' => esc_html__('Tablet Only', 'better-chat-support'),
                        'mobile' => esc_html__('Mobile Only', 'better-chat-support'),
                    ),
                    'dependency' => array('enable_floating_chat', '==', 'true'),
                ),

                // Header content position
                array(
                    'id'      => 'bubble-style',
                    'type'    => 'button_set',
                    'title'   => esc_html__('Select Bubble Layout Mode', 'better-chat-support'),
                    'title_help' => esc_html__('Choose a color mode for the chat bubble. This controls the overall appearance and theme style of the bubble.', 'better-chat-support'),
                    'default' => 'default',
                    'options'    => array(
                        'default'  => array(
                            'text'  => esc_html__('Light mode', 'better-chat-support'),
                        ),
                        'dark' => array(
                            'text'  => esc_html__('Dark mode', 'better-chat-support'),
                            'pro_only' => true,
                        ),
                        'night' => array(
                            'text'  => esc_html__('Night mode', 'better-chat-support'),
                            'pro_only' => true,
                        ),
                    ),
                    'dependency' => array('enable_floating_chat', '==', 'true'),
                ),

                // Header content position
                array(
                    'id'      => 'header-content-position',
                    'type'    => 'button_set',
                    'title'   => esc_html__('Bubble Header Content Position', 'better-chat-support'),
                    'title_help' => esc_html__('Choose the alignment for the header content.', 'better-chat-support'),
                    'default' => 'left',
                    'options'    => array(
                        'left'  => esc_html__('Left', 'better-chat-support'),
                        'center' => esc_html__('Center', 'better-chat-support'),
                    ),
                    'dependency' => array('enable_floating_chat|opt-chat-type', '==|any', 'true|single'),
                ),
                // changing bubble animations
                array(
                    'id'    => 'select-animation',
                    'type'  => 'select',
                    'title' => esc_html__('Select Animation For Bubble', 'better-chat-support'),
                    'title_help' => esc_html__('Choose an animation style for how the chat bubble appears on the screen. You can pick a specific effect or select "Random" to apply a different animation each time.', 'better-chat-support'),
                    'options' => array(
                        '1'     => esc_html__('Fade Right', 'better-chat-support'),
                        '2'     => esc_html__('Fade Down', 'better-chat-support'),
                        '4'     => esc_html__('Fade In Scale (Pro)', 'better-chat-support'),
                        '5'     => esc_html__('Rotation (Pro)', 'better-chat-support'),
                        '6'     => esc_html__('Slide Fall (Pro)', 'better-chat-support'),
                        '7'     => esc_html__('Slide Down (Pro)', 'better-chat-support'),
                        '3'     => esc_html__('Ease Down (Pro)', 'better-chat-support'),
                        '8'     => esc_html__('Rotate Left (Pro)', 'better-chat-support'),
                        '9'     => esc_html__('Flip Horizontal (Pro)', 'better-chat-support'),
                        '10'    => esc_html__('Flip Vertical (Pro)', 'better-chat-support'),
                        '11'    => esc_html__('Flip Up (Pro)', 'better-chat-support'),
                        '12'    => esc_html__('Super Scaled (Pro)', 'better-chat-support'),
                        '13'    => esc_html__('Slide Up (Pro)', 'better-chat-support'),
                        'random' => esc_html__('Random (Pro)', 'better-chat-support'),
                    ),
                    'default'     => '1',
                    'dependency' => array('enable_floating_chat', '==', 'true'),
                ),
                array(
                    'id'      => 'opt-button-style',
                    'type'    => 'image_select',
                    'title'   => esc_html__('Flating Button Style', 'better-chat-support'),
                    'title_help' => esc_html__('Choose a style for the floating chat button from the available design options.', 'better-chat-support'),
                    'options' => array(
                        '1' => BETTER_CHAT_SUPPORT_DIR_URL . '/src/Admin/Framework/assets/images/button-1.svg',
                        '2' => BETTER_CHAT_SUPPORT_DIR_URL . '/src/Admin/Framework/assets/images/button-2.svg',
                        '3' => BETTER_CHAT_SUPPORT_DIR_URL . '/src/Admin/Framework/assets/images/button-3.svg',
                        '4' => BETTER_CHAT_SUPPORT_DIR_URL . '/src/Admin/Framework/assets/images/button-4.svg',
                        '5' => BETTER_CHAT_SUPPORT_DIR_URL . '/src/Admin/Framework/assets/images/button-5.svg',
                        '6' => BETTER_CHAT_SUPPORT_DIR_URL . '/src/Admin/Framework/assets/images/button-6.svg',
                        '7' => BETTER_CHAT_SUPPORT_DIR_URL . '/src/Admin/Framework/assets/images/button-7.svg',
                        '8' => BETTER_CHAT_SUPPORT_DIR_URL . '/src/Admin/Framework/assets/images/button-8.svg',
                        '9' => BETTER_CHAT_SUPPORT_DIR_URL . '/src/Admin/Framework/assets/images/button-9.svg',
                    ),
                    'default' => '1',
                    'dependency' => array('enable_floating_chat', '==', 'true'),
                ),

                // Circle button icon
                array(
                    'id'    => 'circle-button-icon',
                    'type'  => 'icon',
                    'title' => esc_html__('Icon For Circle Button', 'better-chat-support'),
                    'title_help' => esc_html__('Select an icon to display inside the circular chat button.', 'better-chat-support'),
                    'default' => 'icofont-facebook-messenger',
                    'dependency' => array('enable_floating_chat', '==', 'true'),
                ),

                // Circle button icon close
                array(
                    'id'    => 'circle-button-close',
                    'type'  => 'icon',
                    'title' => esc_html__('Icon For Circle Button Close', 'better-chat-support'),
                    'title_help' => esc_html__('Select the icon to display when the circular chat button is in the close state.', 'better-chat-support'),
                    'default' => 'icofont-close',
                    'dependency' => array('enable_floating_chat', '==', 'true'),
                ),

                // changeing circle animations
                array(
                    'id'    => 'circle-animation',
                    'type'  => 'select',
                    'title' => esc_html__('Transition Effect for Circle Icon', 'better-chat-support'),
                    'title_help' => esc_html__('Select the animation effect used when toggling the circular chat button between open and close states.', 'better-chat-support'),
                    'options' => array(
                        '1'     => esc_html__('Slide Down', 'better-chat-support'),
                        '3'     => esc_html__('Fade', 'better-chat-support'),
                        '2'     => esc_html__('Rotate (Pro)', 'better-chat-support'),
                        '4'     => esc_html__('Slide Up (Pro)', 'better-chat-support'),
                    ),
                    'default'     => '1',
                    'dependency' => array('enable_floating_chat', '==', 'true'),
                ),

                // Bubble text
                array(
                    'id'    => 'bubble-text',
                    'type'    => 'text',
                    'title'   => esc_html__('Bubble Text', 'better-chat-support'),
                    'subtitle' => esc_html__('Change text to show in bubble', 'better-chat-support'),
                    'default' => esc_html__('How may I help you?', 'better-chat-support'),
                    'dependency' => array('enable_floating_chat|opt-button-style', '==|any', 'true|2,3,4,5,6,7'),
                ),
                array(
                    'id'      => 'cleanup_data_deletion',
                    'type'    => 'checkbox',
                    'title' => esc_html__('Clean-up Data on Deletion', 'better-chat-support'),
                    'title_help' => esc_html__('Enable this option to completely remove all Messenger Chat Support plugin data when the plugin is deleted from your site.', 'better-chat-support'),
                    'dependency' => array('enable_floating_chat', '==', 'true'),
                ),
                array(
                    'title'       => esc_html__('Backup', 'better-chat-support'),
                    'title_help' => esc_html__('Export or import plugin settings for backup or migration purposes.', 'better-chat-support'),
                    'type' => 'backup',
                    'dependency' => array('enable_floating_chat', '==', 'true'),
                ),
            )
        ));
    }
}
