<?php

/**
 * Views class for Shortcode generator options.
 *
 * @link       https://themeatelier.net
 * @since      1.0.0
 *
 * @package better-chat-support
 * @subpackage better-chat-support/Admin/Views/General
 * @author     ThemeAtelier<themeatelierbd@gmail.com>
 */

namespace ThemeAtelier\BetterChatSupport\Admin\Views;

use ThemeAtelier\BetterChatSupport\Admin\Framework\Classes\BetterChatSupport;

class General
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
            'class' => 'floating_chat',
            'fields' => array(
                array(
                    'id'         => 'chat_layout',
                    'type'       => 'layout_preset',
                    'title'      => esc_html__('Floating Chat Layout(s)', 'better-chat-support'),
                    'title_help' =>
                    '<div class="better-chat-support-info-label">' .
                        esc_html__('Choose how your Messenger chat appears.', 'better-chat-support') .
                        '</div>' .
                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#shortcode') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                    'class'   => 'better-chat-support-layout-preset',
                    'options'    => array(
                        'off' => array(
                            'image'           => esc_url(BETTER_CHAT_SUPPORT_DIR_URL . 'src/Admin/Framework/assets/images/off.svg'),
                            'text'            => esc_html__('Disable Chat', 'better-chat-support'),
                            'option_demo_url' => '',
                        ),
                        'agent' => array(
                            'image'           => esc_url(BETTER_CHAT_SUPPORT_DIR_URL . 'src/Admin/Framework/assets/images/single_agent.svg'),
                            'text'            => esc_html__('Single Agent', 'better-chat-support'),
                            'option_demo_url' => esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#example_1'),
                        ),
                        'button' => array(
                            'image'           => esc_url(BETTER_CHAT_SUPPORT_DIR_URL . 'src/Admin/Framework/assets/images/simple_button.svg'),
                            'text'            => esc_html__('Chat Button', 'better-chat-support'),
                            'option_demo_url' => esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#example_9'),
                        ),
                        'multi_pro' => array(
                            'image'           => esc_url(BETTER_CHAT_SUPPORT_DIR_URL . 'src/Admin/Framework/assets/images/multi_agent.svg'),
                            'text'            => esc_html__('Multi-Agent List', 'better-chat-support'),
                            'option_demo_url' => esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#example_3'),
                            'pro_only'      => true,
                        ),
                        'multi_grid_pro' => array(
                            'image'           => esc_url(BETTER_CHAT_SUPPORT_DIR_URL . 'src/Admin/Framework/assets/images/multi_grid.svg'),
                            'text'            => esc_html__('Multi-Agent Grid', 'better-chat-support'),
                            'option_demo_url' => esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#example_4'),
                            'pro_only'      => true,
                        ),
                    ),
                    'default' => 'agent',
                ),
                array(
                    'type' => 'subheading',
                    'style'   => 'success',
                    'content' => esc_html__('By "Disable Chat" option selected, the floating chat feature will be disabled. However, you can still use other functionalities, including shortcodes, and button blocks provided by the plugin.', 'better-chat-support'),
                    'dependency' => array('chat_layout', '==', 'off'),
                ),
                array(
                    'type' => 'section_tab',
                    'dependency' => array('chat_layout', '!=', 'off', 'any'),
                    'class' => 'mSupport-mt-0',
                    'tabs' => array(
                        array(
                            'title' => esc_html__('General', 'better-chat-support'),
                            'icon'  => 'icofont-gears',
                            'fields' => array(
                                array(
                                    'id'    => 'opt-fbid',
                                    'type'  => 'text',
                                    'title' => esc_html__('Facebook ID', 'better-chat-support'),
                                    'title_help'  => '<div class="better-chat-support-info-label">' . esc_html__('Add your profile or page ID to receive messages.', 'better-chat-support') . '</div>' . ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/single-agent/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                    'desc'  => esc_html__('If your link is facebook.com/themeatelier, just use themeatelier in this field.', 'better-chat-support'),
                                    'dependency' => array('chat_layout', '!=', 'off', 'any'),
                                    'placeholder'   => 'E.g. themeatelier',
                                ),
                                array(
                                    'id' => 'show_current_time',
                                    'type' => 'switcher',
                                    'title' => esc_html__('Current Time', 'better-chat-support'),
                                    'title_help' => '<div class="better-chat-support-img-tag"><img src="' . esc_url(BETTER_CHAT_SUPPORT_DIR_URL . 'src/Admin/Framework/assets/images/preview/current_time.jpg') . '" alt=""></div> <div class="better-chat-support-info-label">' . esc_html__('Enable to display the current time before the agent’s message.', 'better-chat-support') . '</div>' .
                                        '<a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#shortcode') . '">' . esc_html__('Live Demo', 'better-chat-support') . '</a>' .
                                        '<a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/single-agent/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                    'default' => true,
                                    'dependency' => array('chat_layout', 'any', 'agent', 'any'),
                                    'text_on'    => esc_html__('Enabled', 'better-chat-support'),
                                    'text_off'   => esc_html__('Disabled', 'better-chat-support'),
                                    'text_width' => 100
                                ),
                                array(
                                    'id'    => 'agent-message',
                                    'type'    => 'textarea',
                                    'title'   => esc_html__('Message From Agent', 'better-chat-support'),
                                    'title_help' => '<div class="better-chat-support-img-tag"><img src="' . esc_url(BETTER_CHAT_SUPPORT_DIR_URL . 'src/Admin/Framework/assets/images/preview/agent_message.jpg') . '" alt=""></div> <div class="better-chat-support-info-label">' . esc_html__('Add a custom message to display inside the agent’s message box.', 'better-chat-support') . '</div> <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#shortcode') . '">' . esc_html__('Live Demo', 'better-chat-support') . '</a> <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/single-agent/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                    'default' => esc_html__('Hello, Welcome to the site. Please click below button for chatting me through messenger.', 'better-chat-support'),
                                    'dependency' => array('chat_layout', 'any', 'agent', 'any'),
                                ),
                                array(
                                    'id' => 'availablity_heading',
                                    'type' => 'heading',
                                    'content' => esc_html__('Availability', 'better-chat-support'),
                                    'dependency' => array(
                                        array('chat_layout', 'not-any', 'multi,multi_grid', 'any'),
                                    ),
                                ),
                                // changeing timezone
                                array(
                                    'id'    => 'select-timezone',
                                    'type'  => 'select',
                                    'title' => esc_html__('Timezone', 'better-chat-support'),
                                    'title_help' =>  '<div class="better-chat-support-info-label">' . esc_html__('Select your local timezone. Availability schedules will be applied based on this timezone.', 'better-chat-support') . '</div>' . ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/single-agent/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                    'chosen'      => true,
                                    'placeholder' => esc_html__('Select timezone', 'better-chat-support'),
                                    'dependency' => array('chat_layout', 'any', 'agent,button', 'any'),
                                    'options' => $timezones,
                                ),
                                // Add availablity
                                array(
                                    'id'    => 'opt-availablity',
                                    'type'  => 'tabbed',
                                    'title' => esc_html__('Availability', 'better-chat-support'),
                                    'title_help' =>  '<div class="better-chat-support-info-label">' . esc_html__('Set your daily availability using 24-hour format (e.g., 09:00 to 18:00). To mark a full day as offline, set both From and To values to 00:00.', 'better-chat-support') . '</div>' . ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/single-agent/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                    'dependency' => array('chat_layout', 'any', 'agent,button', 'any'),
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
                            ),
                        ),
                        array(
                            'title' => esc_html__('Header & Footer', 'better-chat-support'),
                            'icon' => 'icofont-layout',
                            'fields' => array(
                                array(
                                    'id'    => 'box_header_title',
                                    'type'  => 'heading',
                                    'content' => esc_html__('Box Header', 'better-chat-support'),
                                    'dependency' => array('chat_layout', 'any', 'form,agent,multi,multi_grid', 'any'),
                                ),
                                // Agent photo type
                                array(
                                    'id' => 'agent_photo_type',
                                    'type' => 'button_set',
                                    'title' => esc_html__('Agent Photo Type', 'better-chat-support'),
                                    'title_help' => '<div class="better-chat-support-info-label">' .
                                        esc_html__('Choose how the agent photo is displayed:', 'better-chat-support') . '</div>' . ' <b>Default:</b> ' . esc_html__('Use the plugin’s built-in photo.', 'better-chat-support') . '<br>' . ' <b>Custom:</b> ' . esc_html__('Upload your own image.', 'better-chat-support') . '<br>' . ' <b>None:</b> ' . esc_html__('No photo will be shown.', 'better-chat-support') . '<br>' . ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/single-agent/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                    'options' => array(
                                        'default'   =>  esc_html__('Default', 'better-chat-support'),
                                        'custom' => esc_html__('Custom', 'better-chat-support'),
                                        'none' => esc_html__('None', 'better-chat-support'),
                                    ),
                                    'default'   => 'default',
                                    'dependency' => array('chat_layout', 'any', 'agent', 'any'),
                                ),


                                // adding agent photo
                                array(
                                    'id' => 'agent-photo',
                                    'type' => 'media',
                                    'title' => esc_html__('Agent Photo', 'better-chat-support'),
                                    'title_help' => '<div class="better-chat-support-img-tag"><img src="' . esc_url(BETTER_CHAT_SUPPORT_DIR_URL . 'src/Admin/Framework/assets/images/preview/user_image.jpg') . '" alt=""></div>' . '<div class="better-chat-support-info-label">' . esc_html__('Upload an agent photo to display inside the chat bubble.', 'better-chat-support') . '</div>' . ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#shortcode') . '">' . esc_html__('Live Demo', 'better-chat-support') . '</a>' . ' <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/single-agent/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                    'placeholder' => esc_html__('Upload or select an image from the media library.', 'better-chat-support'),

                                    'library' => 'image',
                                    'preview' => true,
                                    'dependency' => array('chat_layout|agent_photo_type', 'any|==', 'agent|custom', 'any'),
                                ),

                                // agent name
                                array(
                                    'id' => 'agent-name',
                                    'type' => 'text',
                                    'title' => esc_html__('Agent Name', 'better-chat-support'),
                                    'title_help' => '<div class="better-chat-support-img-tag"><img src="' . esc_url(BETTER_CHAT_SUPPORT_DIR_URL . 'src/Admin/Framework/assets/images/preview/agent_name.jpg') . '" alt=""></div>' . '<div class="better-chat-support-info-label">' . esc_html__('Enter the agent’s name to display inside the chat bubble.', 'better-chat-support') . '</div>' . ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#shortcode') . '">' . esc_html__('Live Demo', 'better-chat-support') . '</a>' . ' <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/single-agent/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                    'default' => esc_html__('John Doe', 'better-chat-support'),
                                    'dependency' => array('chat_layout', 'any', 'agent', 'any'),
                                ),

                                // agent subtitle
                                array(
                                    'id' => 'agent-subtitle',
                                    'type' => 'text',
                                    'title' => esc_html__('Subtitle', 'better-chat-support'),
                                    'title_help' => '<div class="better-chat-support-img-tag"><img src="' . esc_url(BETTER_CHAT_SUPPORT_DIR_URL . 'src/Admin/Framework/assets/images/preview/agent_subtitle.jpg') . '" alt=""></div>' .  '<div class="better-chat-support-info-label">' . esc_html__('Enter a subtitle to display below the agent’s name in the chat bubble.', 'better-chat-support') . '</div>' . ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#shortcode') . '">' . esc_html__('Live Demo', 'better-chat-support') . '</a>' . ' <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/single-agent/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                    'default' => esc_html__('Typically replies within a day.', 'better-chat-support'),
                                    'dependency' => array('chat_layout', 'any', 'agent', 'any'),
                                ),
                                array(
                                    'id' => 'offline_agent_subtitle',
                                    'type' => 'text',
                                    'title' => esc_html__('Offline Subtitle', 'better-chat-support'),
                                    'title_help' => '<div class="better-chat-support-img-tag"><img src="' . esc_url(BETTER_CHAT_SUPPORT_DIR_URL . 'src/Admin/Framework/assets/images/preview/agent_subtitle.jpg') . '" alt=""></div>' .  '<div class="better-chat-support-info-label">' . esc_html__('Enter a subtitle to display when the agent is offline in the chat bubble.', 'better-chat-support') . '</div>' . ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#shortcode') . '">' . esc_html__('Live Demo', 'better-chat-support') . '</a>' . ' <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/single-agent/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                    'default' => esc_html__('Currently offline, will reply soon.', 'better-chat-support'),
                                    'dependency' => array('chat_layout', 'any', 'agent', 'any'),
                                ),

                                // Bubble title
                                array(
                                    'id' => 'bubble-title',
                                    'type' => 'text',
                                    'title' => esc_html__('Bubble Title', 'better-chat-support'),
                                    'title_help' => '<div class="better-chat-support-img-tag"><img src="' . esc_url(BETTER_CHAT_SUPPORT_DIR_URL . 'src/Admin/Framework/assets/images/preview/bubble_title.jpg') . '" alt=""></div>' . '<div class="better-chat-support-info-label">' . esc_html__('Enter the main title text to display at the top of the chat bubble.', 'better-chat-support') . '</div>' . ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#shortcode') . '">' . esc_html__('Live Demo', 'better-chat-support') . '</a>' . ' <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/single-agent/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                    'default' => esc_html__('Need Help? Chat with us', 'better-chat-support'),

                                    'dependency' => array('chat_layout', 'any', 'multi,multi_grid', 'any'),
                                ),

                                // Bubble subtitle
                                array(
                                    'id' => 'bubble-subtitle',
                                    'type' => 'text',
                                    'title' => esc_html__('Bubble Subtitle', 'better-chat-support'),
                                    'title_help' =>
                                    '<div class="better-chat-support-img-tag"><img src="' . esc_url(BETTER_CHAT_SUPPORT_DIR_URL . 'src/Admin/Framework/assets/images/preview/bubble_subtitle.jpg') . '" alt=""></div>' .
                                        '<div class="better-chat-support-info-label">' .
                                        esc_html__('Enter a subtitle to display below the main title inside the chat bubble.', 'better-chat-support') .
                                        '</div>' .
                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#shortcode') . '">' . esc_html__('Live Demo', 'better-chat-support') . '</a>' .
                                        ' <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/single-agent/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                    'default' => esc_html__('Click one of our representatives below', 'better-chat-support'),
                                    'dependency' => array('chat_layout', 'any', 'multi,multi_grid', 'any'),
                                ),
                                // Header content position
                                array(
                                    'id' => 'header-content-position',
                                    'type' => 'button_set',
                                    'title' => esc_html__('Bubble Header Content Position', 'better-chat-support'),
                                    'title_help' => '<div class="better-chat-support-img-tag"><img src="' . esc_url(BETTER_CHAT_SUPPORT_DIR_URL . 'src/Admin/Framework/assets/images/preview/header_left_center.jpg') . '" alt=""></div>' . '<div class="better-chat-support-info-label">' . esc_html__('Choose the alignment for the header content.', 'better-chat-support') . '</div>' . ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#shortcode') . '">' . esc_html__('Live Demo', 'better-chat-support') . '</a>' . ' <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/single-agent/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                    'default' => 'left',
                                    'options' => array(
                                        'left' => esc_html__('Left', 'better-chat-support'),
                                        'center' => esc_html__('Center', 'better-chat-support'),
                                    ),
                                    'dependency' => array('chat_layout', 'any', 'agent', 'any'),
                                ),

                                array(
                                    'id'    => 'box_footer_title',
                                    'type'  => 'heading',
                                    'content' => esc_html__('Box Footer', 'better-chat-support'),
                                    'dependency' => array('chat_layout', 'any', 'agent,multi,multi_grid', 'any'),
                                ),

                                // before chat icon
                                array(
                                    'id' => 'before-chat-icon',
                                    'type' => 'button_set',
                                    'title' => esc_html__('Icon for Send Message Button', 'better-chat-support'),
                                    'title_help' => '<div class="better-chat-support-img-tag"><img src="' . esc_url(BETTER_CHAT_SUPPORT_DIR_URL . 'src/Admin/Framework/assets/images/preview/send_message_icon.jpg') . '" alt=""></div>' . '<div class="better-chat-support-info-label">' . esc_html__('Select an icon to display before the send message button text.', 'better-chat-support') . '</div>' . ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#shortcode') . '">' . esc_html__('Live Demo', 'better-chat-support') . '</a>' . ' <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/single-agent/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                    'options' => array(
                                        'icofont-facebook-messenger'    => array(
                                            'option_name' => '<i class="icofont-facebook-messenger"></i>',
                                        ),
                                        'icofont-live-support'    => array(
                                            'option_name' => '<i class="icofont-live-support"></i>',
                                        ),
                                        'icofont-ui-messaging'    => array(
                                            'option_name' => '<i class="icofont-ui-messaging"></i>',
                                        ),
                                        'icofont-telegram'    => array(
                                            'option_name' => '<i class="icofont-telegram"></i>',
                                        ),
                                        'icofont-paper-plane'    => array(
                                            'option_name' => '<i class="icofont-paper-plane"></i>',
                                        ),
                                        'no_icon'    => array(
                                            'option_name' => esc_html__('No Icon', 'better-chat-support'),
                                        ),
                                        'native'    => array(
                                            'option_name' => esc_html__('Native', 'better-chat-support'),
                                            'pro_only'    => true,
                                        ),
                                        'custom'    => array(
                                            'option_name' => esc_html__('Custom', 'better-chat-support'),
                                            'pro_only'    => true,
                                        ),
                                    ),
                                    'default' => 'icofont-facebook-messenger',
                                    'dependency' => array('chat_layout', 'any', 'agent', 'any'),
                                ),

                                array(
                                    'id' => 'before-chat-icon-native',
                                    'type' => 'icon',
                                    'title' => esc_html__('Send Message Native Icon', 'better-chat-support'),
                                    'title_help' =>
                                    '<div class="better-chat-support-info-label">' .
                                        esc_html__('Choose a native icon from the built-in library of 2000+ icons to display before the send message button text.', 'better-chat-support') .
                                        '</div>' .
                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#shortcode') . '">' . esc_html__('Live Demo', 'better-chat-support') . '</a>' .
                                        ' <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL) . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',

                                    'default' => 'icofont-facebook-messenger',
                                    'dependency' => array('chat_layout|before-chat-icon', 'any|', 'agent|native', 'any'),
                                ),
                                array(
                                    'id' => 'before-chat-icon-custom',
                                    'type' => 'media',
                                    'title' => esc_html__('Send Message Custom Icon', 'better-chat-support'),
                                    'title_help' =>
                                    '<div class="better-chat-support-info-label">' .
                                        esc_html__('Upload or select a custom icon from your media library to display before the send message button text.', 'better-chat-support') .
                                        '</div>' .
                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#shortcode') . '">' . esc_html__('Live Demo', 'better-chat-support') . '</a>' .
                                        ' <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/single-agent/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',

                                    'dependency' => array('chat_layout|before-chat-icon', 'any|==', 'agent|custom', 'any'),
                                ),

                                // agent message button text
                                array(
                                    'id' => 'chat-button-text',
                                    'type' => 'text',
                                    'title' => esc_html__('Send Message Button Label', 'better-chat-support'),
                                    'title_help' =>
                                    '<div class="better-chat-support-img-tag"><img src="' . esc_url(BETTER_CHAT_SUPPORT_DIR_URL . 'src/Admin/Framework/assets/images/preview/send_message_text.jpg') . '" alt=""></div>' .
                                        '<div class="better-chat-support-info-label">' .
                                        esc_html__('Enter the text to display on the send message button.', 'better-chat-support') .
                                        '</div>' .
                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#shortcode') . '">' . esc_html__('Live Demo', 'better-chat-support') . '</a>' .
                                        ' <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/single-agent/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                    'default' => esc_html__('Send Message', 'better-chat-support'),

                                    'dependency' => array('chat_layout', 'any', 'agent', 'any'),
                                ),

                                array(
                                    'id' => 'footer_content',
                                    'type' => 'switcher',
                                    'class' => 'switcher_pro_only',
                                    'title' => esc_html__('Footer Content', 'better-chat-support'),
                                    'title_help' =>
                                    '<div class="better-chat-support-info-label">' .
                                        esc_html__('Enable or disable the footer text below the chat box. You can also replace the default text with your own custom message.', 'better-chat-support') .
                                        '</div>' .
                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/single-agent/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                    'text_on' => esc_html__('Enable', 'better-chat-support'),
                                    'text_off' => esc_html__('Disable', 'better-chat-support'),
                                    'text_width' => 100,
                                    'dependency' => array('chat_layout', 'any', 'form,agent,agent_input,multi,multi_agent_form', 'any'),
                                ),
                            )
                        ),
                        array(
                            'title' => esc_html__('Button', 'better-chat-support'),
                            'icon' => 'icofont-scroll-double-right',
                            'fields' => array(
                                array(
                                    'id' => 'opt-button-style',
                                    'type' => 'layout_preset',
                                    'title' => esc_html__('Floating Button Style', 'better-chat-support'),
                                    'title_help' =>
                                    '<div class="better-chat-support-info-label">' .
                                        esc_html__('Choose a style for the floating chat button from the available design options.', 'better-chat-support') .
                                        '</div>' .
                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#shortcode') . '">' . esc_html__('Live Demo', 'better-chat-support') . '</a>' .
                                        ' <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/button/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                    'options' => array(
                                        '1' => array(
                                            'image'           => esc_url(BETTER_CHAT_SUPPORT_DIR_URL . 'src/Admin/Framework/assets/images/button-1.svg'),
                                            'text'            => esc_html__('Icon Button', 'better-chat-support'),
                                            'option_demo_url' => esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#example_7'),
                                        ),
                                        '2' => array(
                                            'image'           => esc_url(BETTER_CHAT_SUPPORT_DIR_URL . 'src/Admin/Framework/assets/images/button-2.svg'),
                                            'text'            => esc_html__('Simple Button', 'better-chat-support'),
                                            'option_demo_url' => esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#example_8'),
                                        ),
                                        '11' => array(
                                            'image'           => esc_url(BETTER_CHAT_SUPPORT_DIR_URL . 'src/Admin/Framework/assets/images/advance-filled.svg'),
                                            'text'            => esc_html__('Advance Button', 'better-chat-support'),
                                            'option_demo_url' => esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#example_9'),
                                            'pro_only'    => true,
                                        ),
                                    ),
                                    'default' => '1',
                                ),

                                // Agent photo type
                                array(
                                    'id' => 'agent_avatar_type',
                                    'type' => 'button_set',
                                    'title' => esc_html__('Agent Avatar Type', 'better-chat-support'),
                                    'title_help' => '<div class="better-chat-support-info-label">' .
                                        esc_html__('Choose how the agent photo is displayed:', 'better-chat-support') . '</div>' . ' <b>Default:</b> ' . esc_html__('Use the plugin’s built-in photo.', 'better-chat-support') . '<br>' . ' <b>Custom:</b> ' . esc_html__('Upload your own image.', 'better-chat-support') . '<br>' . ' <b>None:</b> ' . esc_html__('No photo will be shown.', 'better-chat-support') . '<br>' . ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/button/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                    'options' => array(
                                        'default'   =>  esc_html__('Default', 'better-chat-support'),
                                        'custom' => esc_html__('Custom', 'better-chat-support'),
                                        'none' => esc_html__('None', 'better-chat-support'),
                                    ),
                                    'default'   => 'default',
                                    'dependency' => array('chat_layout|opt-button-style', 'any|==', 'agent,button|10', 'any'),
                                ),

                                // adding agent photo
                                array(
                                    'id' => 'agent_avatar',
                                    'type' => 'media',
                                    'title' => esc_html__('Agent Avatar', 'better-chat-support'),
                                    'title_help' => '<div class="better-chat-support-img-tag"><img src="' . esc_url(BETTER_CHAT_SUPPORT_DIR_URL . 'src/Admin/Framework/assets/images/preview/user_image.jpg') . '" alt=""></div>' . '<div class="better-chat-support-info-label">' . esc_html__('Upload an agent photo to display inside the chat bubble.', 'better-chat-support') . '</div>' . ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#shortcode') . '">' . esc_html__('Live Demo', 'better-chat-support') . '</a>' . ' <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/button/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                    'placeholder' => esc_html__('Upload or select an image from the media library.', 'better-chat-support'),

                                    'library' => 'image',
                                    'preview' => true,
                                    'dependency' => array('chat_layout|opt-button-style|agent_avatar_type', 'any|==|==', 'agent,button|10|custom', 'any'),
                                ),
                                // Agent photo type
                                array(
                                    'id' => 'chat_button_image_type',
                                    'type' => 'button_set',
                                    'title' => esc_html__('Chat Button Image Type', 'better-chat-support'),
                                    'title_help' => '<div class="better-chat-support-info-label">' .
                                        esc_html__('Choose how the button image is displayed:', 'better-chat-support') . '</div>' . ' <b>Default:</b> ' . esc_html__('Use the plugin’s built-in photo.', 'better-chat-support') . '<br>' . ' <b>Custom:</b> ' . esc_html__('Upload your own image.', 'better-chat-support') . '<br>' . ' <b>None:</b> ' . esc_html__('No photo will be shown.', 'better-chat-support') . '<br>' . ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/button/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                    'options' => array(
                                        'default'   =>  esc_html__('Default', 'better-chat-support'),
                                        'custom' => esc_html__('Custom', 'better-chat-support'),
                                        'none' => esc_html__('None', 'better-chat-support'),
                                    ),
                                    'default'   => 'default',
                                    'dependency' => array('chat_layout|opt-button-style', 'any|==', 'multi,multi_grid|10', 'any'),
                                ),

                                // adding agent photo
                                array(
                                    'id' => 'chat_button_image',
                                    'type' => 'media',
                                    'title' => esc_html__('Chat Button Image', 'better-chat-support'),
                                    'title_help' => '<div class="better-chat-support-img-tag"><img src="' . esc_url(BETTER_CHAT_SUPPORT_DIR_URL . 'src/Admin/Framework/assets/images/preview/user_image.jpg') . '" alt=""></div>' . '<div class="better-chat-support-info-label">' . esc_html__('Upload an agent photo to display inside the chat bubble.', 'better-chat-support') . '</div>' . ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#shortcode') . '">' . esc_html__('Live Demo', 'better-chat-support') . '</a>' . ' <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/button/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                    'placeholder' => esc_html__('Upload or select an image from the media library.', 'better-chat-support'),
                                    'library' => 'image',
                                    'preview' => true,
                                    'dependency' => array('chat_layout|opt-button-style|chat_button_image_type', 'any|==|==', 'multi,multi_grid|10|custom', 'any'),
                                ),

                                // Button text
                                array(
                                    'id' => 'button_top_label',
                                    'type' => 'text',
                                    'title' => esc_html__('Button Top Label', 'better-chat-support'),
                                    'title_help' =>
                                    '<div class="better-chat-support-img-tag"><img src="' . esc_url(BETTER_CHAT_SUPPORT_DIR_URL . 'src/Admin/Framework/assets/images/preview/button_text.jpg') . '" alt=""></div>' .
                                        '<div class="better-chat-support-info-label">' .
                                        esc_html__('Enter the text to display inside the floating chat button top label.', 'better-chat-support') .
                                        '</div>' .
                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#shortcode') . '">' . esc_html__('Live Demo', 'better-chat-support') . '</a>' .
                                        ' <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/button/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                    'default' => esc_html__('John Doe / Technical support', 'better-chat-support'),

                                    'dependency' => array('chat_layout|opt-button-style', 'any|==', 'agent,button|10', 'any'),
                                ),
                                array(
                                    'id' => 'bubble-text',
                                    'type' => 'text',
                                    'title' => esc_html__('Button Main Label', 'better-chat-support'),
                                    'title_help' =>
                                    '<div class="better-chat-support-img-tag"><img src="' . esc_url(BETTER_CHAT_SUPPORT_DIR_URL . 'src/Admin/Framework/assets/images/preview/button_text.jpg') . '" alt=""></div>' .
                                        '<div class="better-chat-support-info-label">' .
                                        esc_html__('Enter the text to display inside the floating chat button main label.', 'better-chat-support') .
                                        '</div>' .
                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#shortcode') . '">' . esc_html__('Live Demo', 'better-chat-support') . '</a>' .
                                        ' <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/button/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                    'default' => esc_html__('How may I help you?', 'better-chat-support'),

                                    'dependency' => array('chat_layout|opt-button-style', 'any|!=', 'agent,button|1', 'any'),
                                ),
                                array(
                                    'id' => 'chat_button_top_label',
                                    'type' => 'text',
                                    'title' => esc_html__('Button Top Label', 'better-chat-support'),
                                    'title_help' =>
                                    '<div class="better-chat-support-img-tag"><img src="' . esc_url(BETTER_CHAT_SUPPORT_DIR_URL . 'src/Admin/Framework/assets/images/preview/button_top_text.jpg') . '" alt=""></div>' .
                                        '<div class="better-chat-support-info-label">' .
                                        esc_html__('Enter the text to display inside the floating chat button top label.', 'better-chat-support') .
                                        '</div>' .
                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#shortcode') . '">' . esc_html__('Live Demo', 'better-chat-support') . '</a>' .
                                        ' <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/button/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                    'default' => esc_html__('Support Team', 'better-chat-support'),

                                    'dependency' => array('chat_layout|opt-button-style', 'any|==', 'multi,multi_grid|10', 'any'),
                                ),
                                array(
                                    'id' => 'chat_button_text',
                                    'type' => 'text',
                                    'title' => esc_html__('Button Main Label', 'better-chat-support'),
                                    'title_help' => '<div class="better-chat-support-info-label">' .
                                        esc_html__('Enter the text to display inside the floating chat button main label.', 'better-chat-support') .
                                        '</div>' .
                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#shortcode') . '">' . esc_html__('Live Demo', 'better-chat-support') . '</a>' .
                                        ' <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/button/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                    'default' => esc_html__('Start a conversation', 'better-chat-support'),

                                    'dependency' => array('chat_layout|opt-button-style', 'any|!=', 'multi,multi_grid|1', 'any'),
                                ),
                                // Online Text
                                array(
                                    'id' => 'online_text',
                                    'type' => 'text',
                                    'title' => esc_html__('Online Text', 'better-chat-support'),
                                    'title_help' =>
                                    '<div class="better-chat-support-img-tag"><img src="' . esc_url(BETTER_CHAT_SUPPORT_DIR_URL . 'src/Admin/Framework/assets/images/preview/i_am_on.jpg') . '" alt=""></div>' .
                                        '<div class="better-chat-support-info-label">' .
                                        esc_html__('Set the text to display when the agent is online and available for chat.', 'better-chat-support') .
                                        '</div>' .
                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#shortcode') . '">' . esc_html__('Live Demo', 'better-chat-support') . '</a>' .
                                        ' <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/button/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                    'default' => esc_html__('I Am Online', 'better-chat-support'),

                                    'dependency' => array('chat_layout|opt-button-style', 'not-any|==', 'multi,multi_grid|10', 'any'),
                                ),
                                // Ofline Text
                                array(
                                    'id' => 'offline_text',
                                    'type' => 'text',
                                    'title' => esc_html__('Offline Text', 'better-chat-support'),
                                    'title_help' => '<div class="better-chat-support-info-label">' .
                                        esc_html__('Set the text to display when the agent is offline or unavailable for chat.', 'better-chat-support') .
                                        '</div>' .
                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#shortcode') . '">' . esc_html__('Live Demo', 'better-chat-support') . '</a>' .
                                        ' <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/button/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                    'default' => esc_html__('I Am Offline', 'better-chat-support'),

                                    'dependency' => array('chat_layout|opt-button-style', 'not-any|==', 'multi,multi_grid|10', 'any'),
                                ),

                                // Circle button icon
                                array(
                                    'id' => 'circle-button-icon-1',
                                    'type' => 'button_set',
                                    'title' => esc_html__('Icon for Circle Button', 'better-chat-support'),
                                    'title_help' =>
                                    '<div class="better-chat-support-img-tag"><img src="' . esc_url(BETTER_CHAT_SUPPORT_DIR_URL . 'src/Admin/Framework/assets/images/preview/circle_icon.jpg') . '" alt=""></div>' .
                                        '<div class="better-chat-support-info-label">' .
                                        esc_html__('Select an icon to display inside the circular chat button.', 'better-chat-support') .
                                        '</div>' .
                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#shortcode') . '">' . esc_html__('Live Demo', 'better-chat-support') . '</a>' .
                                        ' <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/button/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',

                                    'options' => array(
                                        'icofont-facebook-messenger'    => array(
                                            'option_name' => '<i class="icofont-facebook-messenger"></i>',
                                        ),
                                        'icofont-live-support'    => array(
                                            'option_name' => '<i class="icofont-live-support"></i>',
                                        ),
                                        'icofont-ui-messaging'    => array(
                                            'option_name' => '<i class="icofont-ui-messaging"></i>',
                                        ),
                                        'icofont-telegram'    => array(
                                            'option_name' => '<i class="icofont-telegram"></i>',
                                        ),
                                        'icofont-paper-plane'    => array(
                                            'option_name' => '<i class="icofont-paper-plane"></i>',
                                        ),
                                        'native'    => array(
                                            'option_name' => esc_html__('Native', 'better-chat-support'),
                                        ),
                                        'custom'    => array(
                                            'option_name' => esc_html__('Custom', 'better-chat-support'),
                                        ),
                                    ),
                                    'default' => 'icofont-facebook-messenger',
                                    'dependency' => array('opt-button-style', '==', '1', 'any'),
                                ),

                                // Circle button icon
                                array(
                                    'id' => 'circle-button-icon-native',
                                    'type' => 'icon',
                                    'title' => esc_html__('Circle Button Native Icon', 'better-chat-support'),
                                    'title_help' =>
                                    '<div class="better-chat-support-info-label">' .
                                        esc_html__('Choose a native icon from the built-in library of 2000+ icons to display inside the circular chat button.', 'better-chat-support') .
                                        '</div>' .
                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/button/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',

                                    'default' => 'icofont-facebook-messenger',
                                    'dependency' => array('opt-button-style|circle-button-icon-1', '==|==', '1|native', 'any'),
                                ),
                                array(
                                    'id' => 'circle-button-icon-custom',
                                    'type' => 'media',
                                    'title' => esc_html__('Circle Button Custom Icon', 'better-chat-support'),
                                    'title_help' =>
                                    '<div class="better-chat-support-info-label">' .
                                        esc_html__('Upload or select a custom icon from your media library to display inside the circular chat button.', 'better-chat-support') .
                                        '</div>' .
                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/button/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',

                                    'dependency' => array('opt-button-style|circle-button-icon-1', '==|==', '1|custom', 'any'),
                                ),

                                // Circle button icon close
                                array(
                                    'id' => 'circle-button-close-1',
                                    'type' => 'button_set',
                                    'title' => esc_html__('Icon for Circle Button Close', 'better-chat-support'),
                                    'title_help' =>
                                    '<div class="better-chat-support-info-label">' .
                                        esc_html__('Select the icon to display when the circular chat button is in the close state.', 'better-chat-support') .
                                        '</div>' .
                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#shortcode') . '">' . esc_html__('Live Demo', 'better-chat-support') . '</a>' .
                                        ' <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/button/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',

                                    'options' => array(
                                        'icofont-close'    => array(
                                            'option_name' => '<i class="icofont-close"></i>',
                                        ),
                                        'icofont-close-line'    => array(
                                            'option_name' => '<i class="icofont-close-line"></i>',
                                        ),
                                        'icofont-close-circled'    => array(
                                            'option_name' => '<i class="icofont-close-circled"></i>',
                                        ),
                                        'icofont-ui-close'    => array(
                                            'option_name' => '<i class="icofont-ui-close"></i>',
                                        ),
                                        'icofont-close-squared-alt'    => array(
                                            'option_name' => '<i class="icofont-close-squared-alt"></i>',
                                        ),
                                        'native'    => array(
                                            'option_name' => esc_html__('Native', 'better-chat-support'),
                                        ),
                                        'custom'    => array(
                                            'option_name' => esc_html__('Custom', 'better-chat-support'),
                                        ),
                                    ),
                                    'default' => 'icofont-close',
                                    'dependency' => array('opt-button-style', '==', '1', 'any'),
                                ),

                                array(
                                    'id' => 'circle-button-close-native',
                                    'type' => 'icon',
                                    'title' => esc_html__('Circle Button Native Close Icon', 'better-chat-support'),
                                    'title_help' =>
                                    '<div class="better-chat-support-info-label">' .
                                        esc_html__('Choose a native icon from the built-in library to display when the circular chat button is in the close state.', 'better-chat-support') .
                                        '</div>' .
                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/button/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',

                                    'default' => 'icofont-close',
                                    'dependency' => array('opt-button-style|circle-button-close-1', '==|==', '1|native', 'any'),
                                ),
                                array(
                                    'id' => 'circle-button-close-custom',
                                    'type' => 'media',
                                    'title' => esc_html__('Circle Button Custom Close Icon', 'better-chat-support'),
                                    'title_help' =>
                                    '<div class="better-chat-support-info-label">' .
                                        esc_html__('Upload or select a custom icon from your media library to display when the circular chat button is in the close state.', 'better-chat-support') .
                                        '</div>' .
                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/button/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',

                                    'dependency' => array('opt-button-style|circle-button-close-1', '==|==', '1|custom', 'any'),
                                ),

                                // Circle button icon
                                array(
                                    'id' => 'circle-button-icon',
                                    'type' => 'button_set',
                                    'title' => esc_html__('Icon for Button', 'better-chat-support'),
                                    'title_help' =>
                                    '<div class="better-chat-support-img-tag"><img src="' . esc_url(BETTER_CHAT_SUPPORT_DIR_URL . 'src/Admin/Framework/assets/images/preview/button_icon.jpg') . '" alt=""></div>' .
                                        '<div class="better-chat-support-info-label">' .
                                        esc_html__('Select an icon to display inside the floating chat button.', 'better-chat-support') .
                                        '</div>' .
                                        ' <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/button/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',

                                    'options' => array(
                                        'icofont-facebook-messenger'    => array(
                                            'option_name' => '<i class="icofont-facebook-messenger"></i>',
                                        ),
                                        'icofont-live-support'    => array(
                                            'option_name' => '<i class="icofont-live-support"></i>',
                                        ),
                                        'icofont-ui-messaging'    => array(
                                            'option_name' => '<i class="icofont-ui-messaging"></i>',
                                        ),
                                        'icofont-telegram'    => array(
                                            'option_name' => '<i class="icofont-telegram"></i>',
                                        ),
                                        'icofont-paper-plane'    => array(
                                            'option_name' => '<i class="icofont-paper-plane"></i>',
                                        ),
                                        'no_icon'    => array(
                                            'option_name' => esc_html__('No Icon', 'better-chat-support'),
                                        ),
                                        'native'    => array(
                                            'option_name' => esc_html__('Native', 'better-chat-support'),
                                            'pro_only' => true
                                        ),
                                        'custom'    => array(
                                            'option_name' => esc_html__('Custom', 'better-chat-support'),
                                            'pro_only' => true
                                        ),
                                    ),
                                    'default' => 'icofont-facebook-messenger',
                                    'dependency' => array('opt-button-style', '==', '2', 'any'),
                                ),
                                // Circle button icon close
                                array(
                                    'id' => 'circle-button-close',
                                    'type' => 'button_set',
                                    'title' => esc_html__('Icon for Button Close', 'better-chat-support'),
                                    'title_help' =>
                                    '<div class="better-chat-support-img-tag"><img src="' . esc_url(BETTER_CHAT_SUPPORT_DIR_URL . 'src/Admin/Framework/assets/images/preview/button_icon_close.jpg') . '" alt=""></div>' .
                                        '<div class="better-chat-support-info-label">' .
                                        esc_html__('Select the icon to display when the floating chat button is in the close state.', 'better-chat-support') .
                                        '</div>' .
                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#shortcode') . '">' . esc_html__('Live Demo', 'better-chat-support') . '</a>' .
                                        ' <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/button/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',

                                    'options' => array(
                                        'icofont-close'    => array(
                                            'option_name' => '<i class="icofont-close"></i>',
                                        ),
                                        'icofont-close-line'    => array(
                                            'option_name' => '<i class="icofont-close-line"></i>',
                                        ),
                                        'icofont-close-circled'    => array(
                                            'option_name' => '<i class="icofont-close-circled"></i>',
                                        ),
                                        'icofont-ui-close'    => array(
                                            'option_name' => '<i class="icofont-ui-close"></i>',
                                        ),
                                        'icofont-close-squared-alt'    => array(
                                            'option_name' => '<i class="icofont-close-squared-alt"></i>',
                                        ),
                                        'native'    => array(
                                            'option_name' => esc_html__('Native', 'better-chat-support'),
                                            'pro_only' => true
                                        ),
                                        'custom'    => array(
                                            'option_name' => esc_html__('Custom', 'better-chat-support'),
                                            'pro_only' => true
                                        ),
                                    ),
                                    'default' => 'icofont-close',
                                    'dependency' => array('circle-button-icon|opt-button-style', '!=|==', 'no_icon|2', 'any'),
                                ),
                                array(
                                    'id' => 'button-close-native',
                                    'type' => 'icon',
                                    'title' => esc_html__('Button Native Close Icon', 'better-chat-support'),
                                    'title_help' =>
                                    '<div class="better-chat-support-info-label">' .
                                        esc_html__('Choose a native icon from the built-in library to display when the floating chat button is in the close state.', 'better-chat-support') .
                                        '</div>' .
                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#shortcode') . '">' . esc_html__('Live Demo', 'better-chat-support') . '</a>' .
                                        ' <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/button/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',

                                    'default' => 'icofont-close',
                                    'dependency' => array('circle-button-icon|opt-button-style|circle-button-close', '!=|==|==', 'no_icon|2|native', 'any'),
                                ),
                                array(
                                    'id' => 'button-close-custom',
                                    'type' => 'media',
                                    'title' => esc_html__('Button Custom Close Icon', 'better-chat-support'),
                                    'title_help' =>
                                    '<div class="better-chat-support-info-label">' .
                                        esc_html__('Upload or select a custom icon from your media library to display when the floating chat button is in the close state.', 'better-chat-support') .
                                        '</div>' .
                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#shortcode') . '">' . esc_html__('Live Demo', 'better-chat-support') . '</a>' .
                                        ' <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/button/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',

                                    'dependency' => array('circle-button-icon|opt-button-style|circle-button-close', '!=|==|==', 'no_icon|2|custom', 'any'),
                                ),
                                // changeing circle animations
                                array(
                                    'id' => 'circle-animation',
                                    'type' => 'select',
                                    'title' => esc_html__('Transition Effect for Circle Icon', 'better-chat-support'),
                                    'title_help' =>
                                    '<div class="better-chat-support-info-label">' .
                                        esc_html__('Select the animation effect used when toggling the circular chat button between open and close states.', 'better-chat-support') .
                                        '</div>' .
                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#shortcode') . '">' . esc_html__('Live Demo', 'better-chat-support') . '</a>' .
                                        ' <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/button/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                    'options' => array(
                                        '1' => esc_html__('Slide Down', 'better-chat-support'),
                                        '3' => esc_html__('Fade', 'better-chat-support'),
                                        '2' => esc_html__('Rotate', 'better-chat-support'),
                                        '4' => esc_html__('Slide Up', 'better-chat-support'),
                                    ),

                                    'default' => '1',
                                    'dependency' => array('chat_layout|opt-button-style', 'any|!=', 'agent,multi,multi_grid|10', 'any'),
                                ),

                                array(
                                    'id' => 'button_size',
                                    'type' => 'button_set',
                                    'title' => esc_html__('Button Size', 'better-chat-support'),
                                    'title_help' => '<div class="better-chat-support-info-label">' . esc_html__('Select the size of the button.', 'better-chat-support') . '</div>',
                                    'options' => array(
                                        '0.7' => array(
                                            'option_name'   => esc_html__('XS', 'better-chat-support'),
                                        ),
                                        '0.8' => array(
                                            'option_name'   => esc_html__('S', 'better-chat-support'),
                                        ),
                                        '1' => array(
                                            'option_name'   => esc_html__('M', 'better-chat-support'),
                                        ),
                                        '1.2' => array(
                                            'option_name'   => esc_html__('L', 'better-chat-support'),
                                        ),
                                        '1.4' => array(
                                            'option_name'   => esc_html__('XL', 'better-chat-support'),
                                        ),
                                        '1.6' => array(
                                            'option_name'   => esc_html__('XXL', 'better-chat-support'),
                                        ),
                                        'custom' => array(
                                            'option_name'   => esc_html__('Custom', 'better-chat-support'),
                                            'pro_only' => true
                                        ),
                                    ),
                                    'default' => '1',
                                ),
                                array(
                                    'id'        => 'bubble_icon_color',
                                    'type'      => 'color_group',
                                    'title' => esc_html__('Icon Color', 'better-chat-support'),
                                    'title_help' => '<div class="better-chat-support-img-tag"><img src="' . esc_url(BETTER_CHAT_SUPPORT_DIR_URL . 'src/Admin/Framework/assets/images/preview/icon_background.jpg') . '" alt=""></div>' . '<div class="better-chat-support-info-label">' .
                                        esc_html__('You can define normal and hover colors for the button icon.', 'better-chat-support') .
                                        '</div>',
                                    'options' => array(
                                        'normal_color'   => esc_html__('Normal Color', 'better-chat-support'),
                                        'hover_color' => esc_html__('Hover Color', 'better-chat-support'),
                                    ),
                                    'dependency' => array('circle-button-icon|opt-button-style', '!=|!=', 'no_icon|10', 'any'),
                                ),

                                array(
                                    'id' => 'bubble_icon_bg',
                                    'type' => 'switcher',
                                    'title' => esc_html__('Icon Background', 'better-chat-support'),
                                    'text_on' => esc_html__('Enable', 'better-chat-support'),
                                    'text_off' => esc_html__('Disable', 'better-chat-support'),
                                    'title_help' => '<div class="better-chat-support-img-tag"><img src="' . esc_url(BETTER_CHAT_SUPPORT_DIR_URL . 'src/Admin/Framework/assets/images/preview/icon_background.jpg') . '" alt=""></div>' . '<div class="better-chat-support-info-label">' .
                                        esc_html__('Enable/Disable Button Inner Icon Background', 'better-chat-support') .
                                        '</div>' .
                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#shortcode') . '">' . esc_html__('Live Demo', 'better-chat-support') . '</a>' .
                                        ' <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/button/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',

                                    'default' => true,
                                    'text_width' => 90,
                                    'dependency' => array('circle-button-icon|opt-button-style', '!=|==', 'no_icon|2', 'any'),
                                ),


                                array(
                                    'id'        => 'bubble_icon_bg_color',
                                    'type'      => 'color_group',
                                    'title' => esc_html__('Icon Background Color', 'better-chat-support'),
                                    'title_help' => '
                                            <div class="better-chat-support-info-label">' .
                                        esc_html__('You can define normal and hover background colors for the button icon.', 'better-chat-support') .
                                        '</div>',
                                    'options' => array(
                                        'normal_color'   => esc_html__('Normal Color', 'better-chat-support'),
                                        'hover_color' => esc_html__('Hover Color', 'better-chat-support'),
                                    ),
                                    'default'   => array(
                                        'normal_color' => '#ffffff',
                                        'hover_color' => '#ffffff',
                                    ),
                                    'dependency' => array('circle-button-icon|opt-button-style|bubble_icon_bg', '!=|==|==', 'no_icon|2|true', 'any'),
                                ),



                                array(
                                    'id'        => 'bubble_button_bg',
                                    'type'      => 'color_group',
                                    'title' => esc_html__('Button Background', 'better-chat-support'),
                                    'title_help' => '<div class="better-chat-support-img-tag"><img src="' . esc_url(BETTER_CHAT_SUPPORT_DIR_URL . 'src/Admin/Framework/assets/images/preview/button_text.jpg') . '" alt=""></div>' . '<div class="better-chat-support-info-label">' .
                                        esc_html__('Set your button background color.', 'better-chat-support') .
                                        '</div>' .
                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#shortcode') . '">' . esc_html__('Live Demo', 'better-chat-support') . '</a>' .
                                        ' <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/button/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                    'options' => array(
                                        'normal_color'   => esc_html__('Normal Color', 'better-chat-support'),
                                        'hover_color' => esc_html__('Hover Color', 'better-chat-support'),
                                    ),
                                ),
                                array(
                                    'id'        => 'bubble_button_text',
                                    'type'      => 'color_group',
                                    'title' => esc_html__('Button Label Color', 'better-chat-support'),
                                    'title_help' => '<div class="better-chat-support-img-tag"><img src="' . esc_url(BETTER_CHAT_SUPPORT_DIR_URL . 'src/Admin/Framework/assets/images/preview/button_text.jpg') . '" alt=""></div>' . '<div class="better-chat-support-info-label">' .
                                        esc_html__('Set your button label color.', 'better-chat-support') .
                                        '</div>' .
                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#shortcode') . '">' . esc_html__('Live Demo', 'better-chat-support') . '</a>' .
                                        ' <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/button/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                    'options' => array(
                                        'normal_color'   => esc_html__('Normal Color', 'better-chat-support'),
                                        'hover_color' => esc_html__('Hover Color', 'better-chat-support'),
                                    ),
                                    'dependency' => array('opt-button-style', '!=', '1', 'any'),
                                ),

                                array(
                                    'id' => 'bubble_button_border',
                                    'type' => 'border',
                                    'title' => esc_html__('Button Border', 'better-chat-support'),
                                    'title_help' => '<div class="better-chat-support-img-tag"><img src="' . esc_url(BETTER_CHAT_SUPPORT_DIR_URL . 'src/Admin/Framework/assets/images/preview/icon_background.jpg') . '" alt=""></div>' . '<div class="better-chat-support-info-label">' .
                                        esc_html__('Set border for the floating chat button.', 'better-chat-support') .
                                        '</div>' .
                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#shortcode') . '">' . esc_html__('Live Demo', 'better-chat-support') . '</a>' .
                                        ' <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/button/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                    'all'      => true,
                                    'hover_color'      => true,
                                    'border_radius'      => true,
                                    'default'  => array(
                                        'all'   => '0',
                                        'style' => 'solid',
                                        'color' => '',
                                        'hover_color' => '',
                                        'border_radius' => '50',
                                    ),
                                ),
                                array(
                                    'id' => 'bubble_icon_border',
                                    'type' => 'border',
                                    'title' => esc_html__('Icon Border', 'better-chat-support'),
                                    'title_help' => '<div class="better-chat-support-img-tag"><img src="' . esc_url(BETTER_CHAT_SUPPORT_DIR_URL . 'src/Admin/Framework/assets/images/preview/button_border.jpg') . '" alt=""></div>' . '<div class="better-chat-support-info-label">' .
                                        esc_html__('Set border for the floating chat button.', 'better-chat-support') .
                                        '</div>' .
                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#shortcode') . '">' . esc_html__('Live Demo', 'better-chat-support') . '</a>' .
                                        ' <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/button/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                    'all'      => true,
                                    'hover_color'      => true,
                                    'border_radius'      => true,
                                    'default'  => array(
                                        'all'   => '0',
                                        'style' => 'solid',
                                        'color' => '#ffffff',
                                        'hover_color' => '#ffffff',
                                        'border_radius' => '50',
                                    ),
                                    'dependency' => array('opt-button-style', '!=', '1', 'any'),
                                ),
                                // Button padding
                                array(
                                    'id' => 'bubble-button-padding',
                                    'type' => 'spacing',
                                    'title' => esc_html__('Button Padding', 'better-chat-support'),
                                    'title_help' => '<div class="better-chat-support-info-label">' .
                                        esc_html__('Adjust the inner spacing (padding) of the floating chat button.', 'better-chat-support') .
                                        '</div>' .
                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#shortcode') . '">' . esc_html__('Live Demo', 'better-chat-support') . '</a>' .
                                        ' <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/button/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                    'default' => array(
                                        'top' => '5',
                                        'right' => '15',
                                        'bottom' => '5',
                                        'left' => '6',
                                        'unit' => 'px',
                                    ),
                                    'dependency' => array('opt-button-style', '!=', '1', 'any'),
                                ),
                                array(
                                    'id' => 'bubble_button_tooltip',
                                    'type' => 'button_set',
                                    'title' => esc_html__('Button Tooltip', 'better-chat-support'),
                                    'title_help' =>
                                    '<div class="better-chat-support-img-tag"><img src="' . esc_url(BETTER_CHAT_SUPPORT_DIR_URL . 'src/Admin/Framework/assets/images/preview/button_tooltip.jpg') . '" alt=""></div>' .
                                        '<div class="better-chat-support-info-label">' .
                                        esc_html__('Enable and customize the tooltip text that appears when hovering over the floating chat button.', 'better-chat-support') .
                                        '</div>' .
                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#shortcode') . '">' . esc_html__('Live Demo', 'better-chat-support') . '</a>' .
                                        ' <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/button/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',

                                    'options' => array(
                                        'on_hover' => esc_html__('On Hover', 'better-chat-support'),
                                        'show' => esc_html__('Show', 'better-chat-support'),
                                        'hide' => esc_html__('Hide', 'better-chat-support'),
                                    ),
                                    'default' => 'on_hover',
                                ),
                                array(
                                    'id' => 'bubble_button_tooltip_text',
                                    'type' => 'text',
                                    'title' => esc_html__('Button Tooltip Text', 'better-chat-support'),
                                    'title_help' =>
                                    '<div class="better-chat-support-img-tag"><img src="' . esc_url(BETTER_CHAT_SUPPORT_DIR_URL . 'src/Admin/Framework/assets/images/preview/button_tooltip.jpg') . '" alt=""></div>' .
                                        '<div class="better-chat-support-info-label">' .
                                        esc_html__('Enter the text that will appear inside the tooltip when hovering over the floating chat button.', 'better-chat-support') .
                                        '</div>' .
                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#shortcode') . '">' . esc_html__('Live Demo', 'better-chat-support') . '</a>' .
                                        ' <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/button/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                    'default' => esc_html__('Need Help? Chat with us', 'better-chat-support'),

                                    'dependency' => array('bubble_button_tooltip', '!=', 'hide', 'any'),
                                ),
                                array(
                                    'id' => 'bubble_button_tooltip_background',
                                    'type' => 'color',
                                    'title' => esc_html__('Button Tooltip Background', 'better-chat-support'),
                                    'title_help' =>
                                    '<div class="better-chat-support-info-label">' .
                                        esc_html__('Choose the background color for the tooltip that appears when hovering over the floating chat button.', 'better-chat-support') .
                                        '</div>' .
                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#shortcode') . '">' . esc_html__('Live Demo', 'better-chat-support') . '</a>' .
                                        ' <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/button/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',

                                    'default' => '#f5f7f9',
                                    'dependency' => array('bubble_button_tooltip', '!=', 'hide', 'any'),
                                ),
                                array(
                                    'id' => 'bubble_button_tooltip_width',
                                    'type' => 'slider',
                                    'title' => esc_html__('Button Tooltip Width', 'better-chat-support'),
                                    'title_help' =>
                                    '<div class="better-chat-support-img-tag"><img src="' . esc_url(BETTER_CHAT_SUPPORT_DIR_URL . 'src/Admin/Framework/assets/images/preview/button_tooltip_width.jpg') . '" alt=""></div>' .
                                        '<div class="better-chat-support-info-label">' .
                                        esc_html__('Set the maximum width of the tooltip that appears when hovering over the floating chat button.', 'better-chat-support') .
                                        '</div>' .
                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#shortcode') . '">' . esc_html__('Live Demo', 'better-chat-support') . '</a>' .
                                        ' <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/button/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',

                                    'min' => 20,
                                    'max' => 500,
                                    'step' => 5,
                                    'unit' => 'px',
                                    'default' => 180,
                                    'dependency' => array('bubble_button_tooltip', '!=', 'hide', 'any'),
                                ),
                                array(
                                    'id'    => 'heading',
                                    'type'  => 'heading',
                                    'content' => esc_html__('Positioning', 'better-chat-support'),
                                ),
                                array(
                                    'id'      => 'bubble-position',
                                    'type'    => 'button_set',
                                    'title' => esc_html__('Bubble Position', 'better-chat-support'),
                                    'title_help' =>
                                    '<div class="better-chat-support-info-label">' .
                                        esc_html__('Select the screen position where the floating chat button will appear.', 'better-chat-support') .
                                        '</div>' .
                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#shortcode') . '">' . esc_html__('Live Demo', 'better-chat-support') . '</a>' .
                                        ' <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/button/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                    'options' => array(
                                        'bottom_right' => esc_html__('Bottom Right', 'better-chat-support'),
                                        'bottom_left'  => esc_html__('Bottom Left', 'better-chat-support'),
                                        'middle_right' => esc_html__('Middle Right', 'better-chat-support'),
                                        'middle_left'  => esc_html__('Middle Left', 'better-chat-support'),
                                    ),

                                    'default' => 'bottom_right',
                                ),

                                array(
                                    'id'    => 'right_bottom',
                                    'type'  => 'spacing',
                                    'title' => esc_html__('Margin from Bottom Right', 'better-chat-support'),
                                    'title_help' =>
                                    '<div class="better-chat-support-info-label">' .
                                        esc_html__('Set the margin (spacing) of the floating chat button from the bottom and right edges of the screen. Adjust to reposition the button as needed.', 'better-chat-support') .
                                        '</div>' .
                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#shortcode') . '">' . esc_html__('Live Demo', 'better-chat-support') . '</a>' .
                                        ' <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/button/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                    'top'   => false,
                                    'left'  => false,
                                    'default'  => array(
                                        'right'    => '30',
                                        'bottom'  => '30',
                                        'unit'   => 'px',
                                    ),
                                    'dependency' => array('bubble-position', '==', 'bottom_right', 'any'),
                                ),

                                array(
                                    'id'    => 'left_bottom',
                                    'type'  => 'spacing',
                                    'title' => esc_html__('Margin from Bottom Left', 'better-chat-support'),
                                    'title_help' =>
                                    '<div class="better-chat-support-info-label">' .
                                        esc_html__('Set the margin (spacing) of the floating chat button from the bottom and left edges of the screen. Adjust to reposition the button as needed.', 'better-chat-support') .
                                        '</div>' .
                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#shortcode') . '">' . esc_html__('Live Demo', 'better-chat-support') . '</a>' .
                                        ' <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/button/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                    'top'   => false,
                                    'right'  => false,
                                    'default'  => array(
                                        'left'    => '30',
                                        'bottom'  => '30',
                                        'unit'   => 'px',
                                    ),
                                    'dependency' => array('bubble-position', '==', 'bottom_left', 'any'),
                                ),

                                array(
                                    'id'    => 'right_middle',
                                    'type'  => 'spacing',
                                    'title' => esc_html__('Margin from Middle Right', 'better-chat-support'),
                                    'title_help' =>
                                    '<div class="better-chat-support-info-label">' .
                                        esc_html__('Set the margin (spacing) of the floating chat button from the right edge of the screen when positioned in the middle. Adjust to fine-tune its placement.', 'better-chat-support') .
                                        '</div>' .
                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#shortcode') . '">' . esc_html__('Live Demo', 'better-chat-support') . '</a>' .
                                        ' <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/button/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                    'top'   => false,
                                    'left'  => false,
                                    'bottom'  => false,
                                    'default'  => array(
                                        'right'    => '20',
                                        'unit'   => 'px',
                                    ),
                                    'dependency' => array('bubble-position', '==', 'middle_right', 'any'),
                                ),

                                array(
                                    'id'    => 'left_middle',
                                    'type'  => 'spacing',
                                    'title' => esc_html__('Margin from Middle Left', 'better-chat-support'),
                                    'title_help' =>
                                    '<div class="better-chat-support-info-label">' .
                                        esc_html__('Set the margin (spacing) of the floating chat button from the left edge of the screen when positioned in the middle. Adjust to fine-tune its placement.', 'better-chat-support') .
                                        '</div>' .
                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#shortcode') . '">' . esc_html__('Live Demo', 'better-chat-support') . '</a>' .
                                        ' <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/button/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                    'top'   => false,
                                    'right' => false,
                                    'bottom' => false,
                                    'default'  => array(
                                        'left' => '20',
                                        'unit' => 'px',
                                    ),
                                    'dependency' => array('bubble-position', '==', 'middle_left', 'any'),
                                ),

                                array(
                                    'id'    => 'enable-positioning-tablet',
                                    'type'  => 'switcher',
                                    'title' => esc_html__('Different Positioning for Tablet Devices', 'better-chat-support'),
                                    'title_help' =>
                                    '<div class="better-chat-support-info-label">' .
                                        esc_html__('Enable this option to set a custom bubble position specifically for tablet devices. Useful for optimizing layout and user experience on medium screens.', 'better-chat-support') .
                                        '</div>' .
                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#shortcode') . '">' . esc_html__('Live Demo', 'better-chat-support') . '</a>' .
                                        ' <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/button/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',

                                    'text_on' => esc_html__('Yes', 'better-chat-support'),
                                    'text_off'  => esc_html__('No', 'better-chat-support'),
                                    'dependency' => array('bubble-visibility', '==', 'everywhere', 'any'),
                                ),

                                // Bubble position
                                array(
                                    'id'      => 'bubble-position-tablet',
                                    'type'    => 'button_set',
                                    'title' => esc_html__('Bubble Position', 'better-chat-support'),
                                    'title_help' =>
                                    '<div class="better-chat-support-info-label">' .
                                        esc_html__('Select the screen position where the chat bubble will appear.', 'better-chat-support') .
                                        '</div>' .
                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#shortcode') . '">' . esc_html__('Live Demo', 'better-chat-support') . '</a>' .
                                        ' <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/button/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                    'options' => array(
                                        'bottom_right' => esc_html__('Bottom Right', 'better-chat-support'),
                                        'bottom_left'  => esc_html__('Bottom Left', 'better-chat-support'),
                                        'middle_right' => esc_html__('Middle Right', 'better-chat-support'),
                                        'middle_left'  => esc_html__('Middle Left', 'better-chat-support'),
                                    ),
                                    'default' => 'bottom_right',
                                    'dependency' => array('enable-positioning-tablet|bubble-visibility', '==|==', 'true|everywhere', 'any'),
                                ),

                                array(
                                    'id'    => 'right_bottom_tablet',
                                    'type'  => 'spacing',
                                    'title' => esc_html__('Margin from Bottom Right', 'better-chat-support'),
                                    'title_help' =>
                                    '<div class="better-chat-support-info-label">' .
                                        esc_html__('Set the margin (spacing) of the chat bubble from the bottom and right edges of the screen. Adjust to fine-tune its placement.', 'better-chat-support') .
                                        '</div>' .
                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#shortcode') . '">' . esc_html__('Live Demo', 'better-chat-support') . '</a>' .
                                        ' <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/button/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                    'top'   => false,
                                    'left'  => false,
                                    'default'  => array(
                                        'right'    => '30',
                                        'bottom'  => '30',
                                        'unit'   => 'px',
                                    ),
                                    'dependency' => array('bubble-position-tablet|enable-positioning-tablet|bubble-visibility', '==|==|==', 'bottom_right|true|everywhere', 'any'),
                                ),

                                array(
                                    'id'    => 'left_bottom_tablet',
                                    'type'  => 'spacing',
                                    'title' => esc_html__('Margin from Bottom Left', 'better-chat-support'),
                                    'title_help' =>
                                    '<div class="better-chat-support-info-label">' .
                                        esc_html__('Set the margin (spacing) of the chat bubble from the bottom and left edges of the screen. Adjust to fine-tune its placement.', 'better-chat-support') .
                                        '</div>' .
                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#shortcode') . '">' . esc_html__('Live Demo', 'better-chat-support') . '</a>' .
                                        ' <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/button/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                    'top'   => false,
                                    'right'  => false,
                                    'default'  => array(
                                        'left'    => '30',
                                        'bottom'  => '30',
                                        'unit'   => 'px',
                                    ),
                                    'dependency' => array('bubble-position-tablet|enable-positioning-tablet|bubble-visibility', '==|==|==', 'bottom_left|true|everywhere', 'any'),
                                ),

                                array(
                                    'id'    => 'right_middle_tablet',
                                    'type'  => 'spacing',
                                    'title' => esc_html__('Margin from Middle Right', 'better-chat-support'),
                                    'title_help' =>
                                    '<div class="better-chat-support-info-label">' .
                                        esc_html__('Set the margin (spacing) of the chat bubble from the right edge of the screen when positioned in the middle. Adjust to fine-tune its placement.', 'better-chat-support') .
                                        '</div>' .
                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#shortcode') . '">' . esc_html__('Live Demo', 'better-chat-support') . '</a>' .
                                        ' <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/button/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                    'top'   => false,
                                    'left'  => false,
                                    'bottom'  => false,
                                    'default'  => array(
                                        'right'    => '20',
                                        'unit'   => 'px',
                                    ),
                                    'dependency' => array('bubble-position-tablet|enable-positioning-tablet|bubble-visibility', '==|==|==', 'middle_right|true|everywhere', 'any'),
                                ),

                                array(
                                    'id'    => 'left_middle_tablet',
                                    'type'  => 'spacing',
                                    'title' => esc_html__('Margin from Middle Left', 'better-chat-support'),
                                    'title_help' =>
                                    '<div class="better-chat-support-info-label">' .
                                        esc_html__('Set the margin (spacing) of the chat bubble from the left edge of the screen when positioned in the middle. Adjust to fine-tune its placement.', 'better-chat-support') .
                                        '</div>' .
                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#shortcode') . '">' . esc_html__('Live Demo', 'better-chat-support') . '</a>' .
                                        ' <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/button/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                    'top'   => false,
                                    'right' => false,
                                    'bottom' => false,
                                    'default'  => array(
                                        'left' => '20',
                                        'unit' => 'px',
                                    ),
                                    'dependency' => array('bubble-position-tablet|enable-positioning-tablet|bubble-visibility', '==|==|==', 'middle_left|true|everywhere', 'any'),
                                ),
                                array(
                                    'id'    => 'enable-positioning-mobile',
                                    'type'  => 'switcher',
                                    'title' => esc_html__('Different Positioning for Mobile Devices', 'better-chat-support'),
                                    'title_help' =>
                                    '<div class="better-chat-support-info-label">' .
                                        esc_html__('Enable this option to set a custom chat bubble position specifically for mobile devices. Useful for optimizing layout and usability on smaller screens.', 'better-chat-support') .
                                        '</div>' .
                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#shortcode') . '">' . esc_html__('Live Demo', 'better-chat-support') . '</a>' .
                                        ' <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/button/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                    'text_on' => esc_html__('Yes', 'better-chat-support'),
                                    'text_off'  => esc_html__('No', 'better-chat-support'),
                                    'dependency'    => array('bubble-visibility', '==', 'everywhere', 'any')
                                ),

                                // Bubble position
                                array(
                                    'id'      => 'bubble-position-mobile',
                                    'type'    => 'button_set',
                                    'title' => esc_html__('Bubble Position', 'better-chat-support'),
                                    'title_help' =>
                                    '<div class="better-chat-support-info-label">' .
                                        esc_html__('Select the screen position where the chat bubble will appear.', 'better-chat-support') .
                                        '</div>' .
                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#shortcode') . '">' . esc_html__('Live Demo', 'better-chat-support') . '</a>' .
                                        ' <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/button/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                    'options' => array(
                                        'bottom_right' => esc_html__('Bottom Right', 'better-chat-support'),
                                        'bottom_left'  => esc_html__('Bottom Left', 'better-chat-support'),
                                        'middle_right' => esc_html__('Middle Right', 'better-chat-support'),
                                        'middle_left'  => esc_html__('Middle Left', 'better-chat-support'),
                                    ),

                                    'default' => 'bottom_right',
                                    'dependency' => array('enable-positioning-mobile|bubble-visibility', '==|==', 'true|everywhere', 'any'),
                                ),
                                array(
                                    'id'    => 'right_bottom_mobile',
                                    'type'  => 'spacing',
                                    'title' => esc_html__('Margin from Bottom Right', 'better-chat-support'),
                                    'title_help' =>
                                    '<div class="better-chat-support-info-label">' .
                                        esc_html__('Set the margin (spacing) of the chat bubble from the bottom and right edges of the screen. Adjust to reposition the bubble as needed.', 'better-chat-support') .
                                        '</div>' .
                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#shortcode') . '">' . esc_html__('Live Demo', 'better-chat-support') . '</a>' .
                                        ' <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/button/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                    'top'   => false,
                                    'left'  => false,
                                    'default'  => array(
                                        'right'    => '30',
                                        'bottom'  => '30',
                                        'unit'   => 'px',
                                    ),
                                    'dependency' => array('bubble-position-mobile|enable-positioning-mobile|bubble-visibility', '==|==|==', 'bottom_right|true|everywhere', 'any'),
                                ),

                                array(
                                    'id'    => 'left_bottom_mobile',
                                    'type'  => 'spacing',
                                    'title' => esc_html__('Margin from Bottom Left', 'better-chat-support'),
                                    'title_help' =>
                                    '<div class="better-chat-support-info-label">' .
                                        esc_html__('Set the margin (spacing) of the chat bubble from the bottom and left edges of the screen. Adjust to reposition the bubble as needed.', 'better-chat-support') .
                                        '</div>' .
                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#shortcode') . '">' . esc_html__('Live Demo', 'better-chat-support') . '</a>' .
                                        ' <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/button/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                    'top'   => false,
                                    'right'  => false,
                                    'default'  => array(
                                        'left'    => '30',
                                        'bottom'  => '30',
                                        'unit'   => 'px',
                                    ),
                                    'dependency' => array('bubble-position-mobile|enable-positioning-mobile|bubble-visibility', '==|==|==', 'bottom_left|true|everywhere', 'any'),
                                ),

                                array(
                                    'id'    => 'right_middle_mobile',
                                    'type'  => 'spacing',
                                    'title' => esc_html__('Margin from Middle Right', 'better-chat-support'),
                                    'title_help' =>
                                    '<div class="better-chat-support-info-label">' .
                                        esc_html__('Set the margin (spacing) of the chat bubble from the right edge of the screen when positioned in the middle. Adjust to fine-tune its placement.', 'better-chat-support') .
                                        '</div>' .
                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#shortcode') . '">' . esc_html__('Live Demo', 'better-chat-support') . '</a>' .
                                        ' <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/button/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',

                                    'top'   => false,
                                    'left'  => false,
                                    'bottom'  => false,
                                    'default'  => array(
                                        'right'    => '20',
                                        'unit'   => 'px',
                                    ),
                                    'dependency' => array('bubble-position-mobile|enable-positioning-mobile|bubble-visibility', '==|==|==', 'middle_right|true|everywhere', 'any'),
                                ),

                                array(
                                    'id'    => 'left_middle_mobile',
                                    'type'  => 'spacing',
                                    'title' => esc_html__('Margin from Middle Left', 'better-chat-support'),
                                    'title_help' =>
                                    '<div class="better-chat-support-info-label">' .
                                        esc_html__('Set the margin (spacing) of the chat bubble from the left edge of the screen when positioned in the middle. Adjust to fine-tune its placement.', 'better-chat-support') .
                                        '</div>' .
                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#shortcode') . '">' . esc_html__('Live Demo', 'better-chat-support') . '</a>' .
                                        ' <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/button/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                    'top'   => false,
                                    'right' => false,
                                    'bottom' => false,
                                    'default'  => array(
                                        'left' => '20',
                                        'unit' => 'px',
                                    ),
                                    'dependency' => array('bubble-position-mobile|enable-positioning-mobile|bubble-visibility', '==|==|==', 'middle_left|true|everywhere', 'any'),
                                ),
                            )
                        ),

                        array(
                            'title' => esc_html__('Style', 'better-chat-support'),
                            'icon' => 'icofont-paint',
                            'fields' => array(
                                // Autometically show popup
                                array(
                                    'id'        => 'autoshow-popup',
                                    'type'      => 'switcher',
                                    'title' => esc_html__('Auto Open Popup', 'better-chat-support'),
                                    'title_help' =>
                                    '<div class="better-chat-support-info-label">' .
                                        esc_html__('Enable this option to automatically open the chat popup when the page loads. Useful for drawing visitor attention without requiring a click.', 'better-chat-support') .
                                        '</div>' .
                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/style-settings/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                    'text_on' => esc_html__('Yes', 'better-chat-support'),
                                    'text_off'  => esc_html__('No', 'better-chat-support'),
                                    'default'   => false,
                                    'dependency' => array('chat_layout', 'any', 'agent,multi,multi_grid', 'any'),
                                ),

                                // Auto open popup timeout
                                array(
                                    'id' => 'auto_open_popup_timeout',
                                    'type' => 'slider',
                                    'title' => esc_html__('Auto Open Popup Timeout', 'better-chat-support'),
                                    'title_help' =>
                                    '<div class="better-chat-support-info-label">' .
                                        esc_html__('Set the delay (in seconds) before the chat popup automatically opens after the page loads.', 'better-chat-support') .
                                        '</div>' .
                                        ' <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/style-settings/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                    'min' => 0,
                                    'max' => 30000,
                                    'step' => 100,
                                    'default' => 0,
                                    'dependency' => array('autoshow-popup|chat_layout', '==|any', 'true|agent,multi,multi_grid', 'any'),
                                    'unit'       => __('ms', 'better-chat-support'),
                                ),

                                // changeing bubble animations
                                array(
                                    'id'    => 'select-animation',
                                    'type'  => 'select',
                                    'title' => esc_html__('Select Animation for Bubble', 'better-chat-support'),
                                    'title_help' =>
                                    '<div class="better-chat-support-info-label">' .
                                        esc_html__('Choose an animation style for how the chat bubble appears on the screen. You can pick a specific effect or select "Random" to apply a different animation each time.', 'better-chat-support') .
                                        '</div>' .
                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#shortcode') . '">' . esc_html__('Live Demo', 'better-chat-support') . '</a>' .
                                        ' <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/style-settings/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                    'options' => array(
                                        '1'      => esc_html__('Fade Right', 'better-chat-support'),
                                        '2'      => esc_html__('Fade Down', 'better-chat-support'),
                                        '3'      => esc_html__('Ease Down', 'better-chat-support'),
                                        '4'      => esc_html__('Fade In Scale', 'better-chat-support'),
                                        '5'      => esc_html__('Rotation', 'better-chat-support'),
                                        '6'      => esc_html__('Slide Fall', 'better-chat-support'),
                                        '7'      => esc_html__('Slide Down', 'better-chat-support'),
                                        '8'      => esc_html__('Rotate Left', 'better-chat-support'),
                                        '9'      => esc_html__('Flip Horizontal', 'better-chat-support'),
                                        '10'     => esc_html__('Flip Vertical', 'better-chat-support'),
                                        '11'     => esc_html__('Flip Up', 'better-chat-support'),
                                        '12'     => esc_html__('Super Scaled', 'better-chat-support'),
                                        '13'     => esc_html__('Slide Up', 'better-chat-support'),
                                        'random' => esc_html__('Random', 'better-chat-support'),
                                    ),
                                    'default'     => 'random',
                                    'dependency' => array('chat_layout', 'any', 'agent,multi,multi_grid', 'any'),
                                ),

                                // Header content position
                                array(
                                    'id'      => 'bubble-style',
                                    'type'    => 'button_set',
                                    'title' => esc_html__('Select Bubble Layout Mode', 'better-chat-support'),
                                    'title_help' =>
                                    '<div class="better-chat-support-info-label">' .
                                        esc_html__('Choose a color mode for the chat bubble. This controls the overall appearance and theme style of the bubble.', 'better-chat-support') .
                                        '</div>' .
                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#shortcode') . '">' . esc_html__('Live Demo', 'better-chat-support') . '</a>' .
                                        ' <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/style-settings/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                    'options' => array(
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
                                    'default' => 'default',
                                    'dependency' => array('chat_layout', 'any', 'agent,multi,multi_grid', 'any'),
                                ),
                                array(
                                    'id'      => 'alternative_mSupportBubble',
                                    'type'    => 'text',
                                    'title' => esc_html__('Custom Bubble Trigger', 'better-chat-support'),
                                    'title_help' =>
                                    '<div class="better-chat-support-info-label">' .
                                        esc_html__('Add custom CSS selectors (e.g., .classname, #idname) that can also act as triggers to open the chat bubble. Useful when you want other elements on your site to open the chat besides the default button.', 'better-chat-support') .
                                        '</div>' .
                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/style-settings/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                    'placeholder' => esc_html__('.classname, #idname', 'better-chat-support'),
                                    'dependency' => array('chat_layout', 'any', 'agent,multi,multi_grid', 'any'),

                                ),
                                array(
                                    'id'        => 'color_settings',
                                    'type'      => 'color_group',
                                    'title' => esc_html__('Color Settings', 'better-chat-support'),
                                    'title_help' =>
                                    '<div class="better-chat-support-img-tag">' .
                                        '<img src="' . esc_url(BETTER_CHAT_SUPPORT_DIR_URL . 'src/Admin/Framework/assets/images/preview/button_border.jpg') . '" alt="' . esc_html__('Preview Image', 'better-chat-support') . '">' .
                                        '</div> 
                                            <div class="better-chat-support-info-label">' .
                                        esc_html__('Set your brand colors for the chat bubble. Adjust the Primary and Secondary colors to match your site style.', 'better-chat-support') .
                                        '</div>' .
                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#shortcode') . '">' . esc_html__('Live Demo', 'better-chat-support') . '</a>' .
                                        ' <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL) . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                    'options' => array(
                                        'primary'   => esc_html__('Primary', 'better-chat-support'),
                                        'secondary' => esc_html__('Secondary', 'better-chat-support'),
                                    ),

                                    'default'   => array(
                                        'primary' => '#0084ff',
                                        'secondary' => '#0066ff',
                                    ),
                                ),
                                array(
                                    'id'        => 'send_button_color',
                                    'type'      => 'color_group',
                                    'title' => esc_html__('Send Button', 'better-chat-support'),
                                    'class'      => 'color_group_pro_only',
                                    'desc' => __('Unlock full customization of the Send Button colors with the ', 'better-chat-support') .
                                        '<a style="font-weight:bold;text-decoration:underline;font-style:italic;" href="' . esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#pricing') . '" target="_blank" class="better-chat-support-upgrade-link">' . esc_html__('Pro version.', 'better-chat-support') . '</a>',
                                    'title_help' =>
                                    '<div class="better-chat-support-info-label">' .
                                        esc_html__('Choose the colors for the send button.', 'better-chat-support') .
                                        '</div>' .
                                        ' <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/style-settings/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                    'options' => array(
                                        'color'   => esc_html__('Color', 'better-chat-support'),
                                        'hover_color' => esc_html__('Hover Color', 'better-chat-support'),
                                        'background'   => esc_html__('Background', 'better-chat-support'),
                                        'hover_background' => esc_html__('Hover Background', 'better-chat-support'),
                                    ),
                                ),

                                array(
                                    'id'       => 'better_chat_support_typography',
                                    'type'     => 'typography',
                                    'title' => esc_html__('Typography', 'better-chat-support'),
                                    'title_help' =>
                                    '<div class="better-chat-support-info-label">' .
                                        esc_html__('Select the font family and weight for your chat bubble text. Adjust these options to keep the widget consistent with your site’s typography.', 'better-chat-support') .
                                        '</div>' .
                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/style-settings/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                    'font_size' => false,
                                    'line_height' => false,
                                    'font_style' => false,
                                    'letter_spacing' => false,
                                    'text_align' => false,
                                    'text_transform' => false,
                                    'text_color' => false,
                                    'color' => false,
                                    'subset' => false,
                                    'output'  => '.mSupport,.mSupport-multi,.mSupport-multi input, .advance_button, .mSupport__popup__content input, .mSupport__popup__content textarea',
                                ),
                            )
                        ),

                        array(
                            'title'  => esc_html__('Others', 'better-chat-support'),
                            'icon'   => 'icofont-settings',
                            'fields' => array(
                                // Visiblity
                                array(
                                    'id'    => 'heading',
                                    'type'  => 'heading',
                                    'content' => esc_html__('Visiblity', 'better-chat-support'),
                                ),
                                // device visibility
                                array(
                                    'id'      => 'bubble-visibility',
                                    'type'    => 'button_set',
                                    'title' => esc_html__('Device Visibility', 'better-chat-support'),
                                    'title_help' =>
                                    '<div class="better-chat-support-info-label">' .
                                        '<b>' . esc_html__('Everywhere:', 'better-chat-support') . '</b> ' . esc_html__('Visible on all devices.', 'better-chat-support') . '<br />' .
                                        '<b>' . esc_html__('Desktop Only:', 'better-chat-support') . '</b> ' . esc_html__('Visible on devices wider than 991px.', 'better-chat-support') . '<br />' .
                                        '<b>' . esc_html__('Tablet Only:', 'better-chat-support') . '</b> ' . esc_html__('Visible on devices between 576px and 991px.', 'better-chat-support') . '<br />' .
                                        '<b>' . esc_html__('Mobile Only:', 'better-chat-support') . '</b> ' . esc_html__('Visible on devices smaller than 576px.', 'better-chat-support') .
                                        '</div>' .
                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/others/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                    'options' => array(
                                        'everywhere' => esc_html__('Everywhere', 'better-chat-support'),
                                        'desktop'    => esc_html__('Desktop Only', 'better-chat-support'),
                                        'tablet'     => esc_html__('Tablet Only', 'better-chat-support'),
                                        'mobile'     => esc_html__('Mobile Only', 'better-chat-support'),
                                    ),

                                    'default' => 'everywhere',
                                ),
                                // pages visibility
                                array(
                                    'id'       => 'visibility',
                                    'type'     => 'checkbox',
                                    'class'    => 'better_chat_support_column_2 visibility',
                                    'title' => esc_html__('Visibility By', 'better-chat-support'),
                                    'title_help' =>
                                    '<div class="better-chat-support-info-label">' .
                                        esc_html__('Select where the chat bubble should be visible. You can enable it by specific content types such as pages, posts, products, categories, or tags.', 'better-chat-support') .
                                        '</div>' .
                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/others/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                    'options' => array(
                                        'theme_page'       => esc_html__('Theme Pages', 'better-chat-support'),
                                        'page'             => esc_html__('Pages', 'better-chat-support'),
                                        'posts'            => esc_html__('Posts (Pro)', 'better-chat-support'),
                                        'product'          => esc_html__('Products (Pro)', 'better-chat-support'),
                                        'category'         => esc_html__('Post Categories (Pro)', 'better-chat-support'),
                                        'tags'             => esc_html__('Post Tags (Pro)', 'better-chat-support'),
                                        'product_category' => esc_html__('Product Categories (Pro)', 'better-chat-support'),
                                        'product_tags'     => esc_html__('Product Tags (Pro)', 'better-chat-support'),
                                    ),

                                ),

                                array(
                                    'id'            => 'visibility_by_theme_page',
                                    'type'          => 'accordion',
                                    'class'         => 'padding-t-0',
                                    'dependency'    => array('visibility', 'any', 'theme_page', 'any'),
                                    'accordions'    => array(
                                        array(
                                            'title'     => esc_html__('Theme Pages', 'better-chat-support'),
                                            'fields'    => array(
                                                array(
                                                    'id'    => 'theme_page_target',
                                                    'type'  => 'select',
                                                    'title' => esc_html__('Target', 'better-chat-support'),
                                                    'title_help' =>
                                                    '<div class="better-chat-support-info-label">' .
                                                        esc_html__('Choose whether to show (Include) or hide (Exclude) the chat bubble on the selected items.', 'better-chat-support') .
                                                        '</div>' .
                                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/others/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                                    'options' => array(
                                                        'include' => esc_html__('Include', 'better-chat-support'),
                                                        'exclude' => esc_html__('Exclude', 'better-chat-support'),
                                                    ),
                                                ),
                                                array(
                                                    'id'    => 'theme_page_all',
                                                    'type'  => 'checkbox',
                                                    'title' => esc_html__('All Theme Pages', 'better-chat-support'),
                                                    'title_help' =>
                                                    '<div class="better-chat-support-info-label">' .
                                                        esc_html__('Enable this option to apply the chat bubble visibility rule to all theme pages at once, instead of selecting them individually.', 'better-chat-support') .
                                                        '</div>' .
                                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/others/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                                ),
                                                // Include specific
                                                array(
                                                    'id'      => 'theme_page',
                                                    'type'  => 'select',
                                                    'title' => esc_html__('Theme Pages', 'better-chat-support'),
                                                    'title_help' =>
                                                    '<div class="better-chat-support-info-label">' .
                                                        esc_html__('Select a specific theme page where the chat bubble visibility rule should apply. Options include Blog, 404, or Search pages.', 'better-chat-support') .
                                                        '</div>' .
                                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/others/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                                    'options' => array(
                                                        'post_page'   => esc_html__('Blog Page', 'better-chat-support'),
                                                        '404_page'    => esc_html__('404 Page', 'better-chat-support'),
                                                        'search_page' => esc_html__('Search Page', 'better-chat-support'),
                                                    ),

                                                    'chosen'      => true,
                                                    'multiple'     => true,
                                                    'sortable'    => true,
                                                    'dependency'    => array('theme_page_all', '!=', 'true', 'any'),
                                                ),
                                            )
                                        ),
                                    )
                                ),
                                array(
                                    'id'            => 'visibility_by_page',
                                    'type'          => 'accordion',
                                    'class'         => 'padding-t-0',
                                    'dependency'    => array('visibility', 'any', 'page', 'any'),
                                    'accordions'    => array(
                                        array(
                                            'title'     => esc_html__('Pages', 'better-chat-support'),
                                            'fields'    => array(
                                                array(
                                                    'id'    => 'page_target',
                                                    'type'  => 'select',
                                                    'title' => esc_html__('Target', 'better-chat-support'),
                                                    'title_help' =>
                                                    '<div class="better-chat-support-info-label">' .
                                                        esc_html__('Choose whether to show (Include) or hide (Exclude) the chat bubble on the selected pages.', 'better-chat-support') .
                                                        '</div>' .
                                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/others/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                                    'options' => array(
                                                        'include' => esc_html__('Include', 'better-chat-support'),
                                                        'exclude' => esc_html__('Exclude', 'better-chat-support'),
                                                    ),

                                                ),
                                                array(
                                                    'id'    => 'page_all',
                                                    'type'  => 'checkbox',
                                                    'title' => esc_html__('All Pages', 'better-chat-support'),
                                                    'title_help' =>
                                                    '<div class="better-chat-support-info-label">' .
                                                        esc_html__('Enable this option to apply the chat bubble visibility rule to all pages across your site, instead of selecting individual pages.', 'better-chat-support') .
                                                        '</div>' .
                                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/others/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                                ),
                                                // Include specific
                                                array(
                                                    'id'    => 'page',
                                                    'type'  => 'select',
                                                    'title' => esc_html__('Pages', 'better-chat-support'),
                                                    'title_help' =>
                                                    '<div class="better-chat-support-info-label">' .
                                                        esc_html__('Select one or more specific pages where the chat bubble visibility rule should apply.', 'better-chat-support') .
                                                        '</div>' .
                                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL . 'floating-chat/others/?ref=1') . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                                    'options' => 'pages',

                                                    'query_args'  => array(
                                                        'posts_per_page' => -1,
                                                    ),
                                                    'chosen'      => true,
                                                    'multiple'     => true,
                                                    'sortable'    => false,
                                                    'empty_message'    => esc_html__('You don\'t have any pages available.', 'better-chat-support'),
                                                    'dependency'    => array('page_all', '!=', 'true', 'any'),
                                                ),
                                            )
                                        ),
                                    )
                                ),
                            )
                        ),
                        array(
                            'title' => esc_html__('Backup', 'better-chat-support'),
                            'icon'  => 'icofont-shield',
                            'fields' => array(
                                array(
                                    'title'    => esc_html__('Backup', 'better-chat-support'),
                                    'title_help' =>
                                    '<div class="better-chat-support-info-label">' .
                                        esc_html__('Export or import plugin settings for backup or migration purposes.', 'better-chat-support') .
                                        '</div>' .
                                        ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(BETTER_CHAT_SUPPORT_DOCS_URL) . '">' . esc_html__('Open Docs', 'better-chat-support') . '</a>',
                                    'type' => 'backup',
                                ),
                            )
                        ),
                    ),
                ),
            )
        ));
    }
}
