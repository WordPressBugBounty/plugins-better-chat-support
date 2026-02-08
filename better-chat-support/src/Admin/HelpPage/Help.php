<?php

namespace ThemeAtelier\BetterChatSupport\Admin\HelpPage;

if (! defined('ABSPATH')) {
    exit;
}  // if direct access.

/**
 * The help class for the Testimonial Free
 */
class Help
{

    /**
     * Single instance of the class
     *
     * @var null
     */
    protected static $_instance = null;

    /**
     * Plugins Path variable.
     *
     * @var array
     */
    protected static $plugins = array(
        'domain-for-sale'           => 'domain-for-sale.php',
        'ask-faq'                   => 'ask-faq.php',
        'attentive-security'        => 'attentive-security.php',
        'better-chat-support'       => 'messenger-chat-support.php',
        'bizreview'                 => 'bizreview.php',
        'booklet-booking-system'    => 'booklet.php',
        'skype-chat'                => 'skype-chat.php',
        'chat-help'                 => 'chat-whatsapp.php',
        'chat-telegram'             => 'telegram-chat.php',
        'chat-viber'                => 'chat-viber-lite.php',
        'click-to-dial'             => 'click-to-dial.php',
        'click-to-mail'             => 'click-to-mail.php',
        'darkify'                   => 'darkify.php',
        'eventful'                  => 'eventful.php',
        'eventful-for-elementor'    => 'eventful-for-elementor.php',
        'postify'                   => 'postify.php',
        'idonate'                   => 'idonate.php',
        'greet-bubble'                   => 'greet-bubble.php',
    );


    /**
     * Welcome pages
     *
     * @var array
     */
    public $pages = array(
        'better-chat-support',
    );

    /**
     * Not show this plugin list.
     *
     * @var array
     */
    protected static $not_show_plugin_list = array(
        'bizreview',
        'chat-viber',
        'chat-telegram',
        'click-to-dial',
        'chat-skype',
        'click-to-mail',
        'ask-faq',
        'attentive-security',
        'booklet-booking-system',
        'postify',
        'better-chat-support',
        'idonate',
    );

    /**
     * Help page construct function.
     */
    public function __construct()
    {
        $page   = isset($_GET['page']) ? sanitize_text_field(wp_unslash($_GET['page'])) : '';
        if ('better-chat-support' !== $page) {
            return;
        }
    }

    /**
     * Main Help page Instance
     *
     * @static
     * @return self Main instance
     */
    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }



    /**
     * Sprtf_plugins_info_api_help_page function.
     *
     * @return void
     */
    public function themeatelier_plugins_info_api_help_page()
    {
        $plugins_arr = get_transient('themeatelier_plugins');
        if (false === $plugins_arr) {
            $args    = (object) array(
                'author'   => 'themeatelier',
                'per_page' => '120',
                'page'     => '1',
                'fields'   => array(
                    'slug',
                    'name',
                    'version',
                    'downloaded',
                    'active_installs',
                    'last_updated',
                    'rating',
                    'num_ratings',
                    'short_description',
                    'author',
                ),
            );
            $request = array(
                'action'  => 'query_plugins',
                'timeout' => 30,
                'request' => serialize($args),
            );
            // https://codex.wordpress.org/WordPress.org_API.
            $url      = 'http://api.wordpress.org/plugins/info/1.0/';
            $response = wp_remote_post($url, array('body' => $request));
            if (! is_wp_error($response)) {

                $plugins_arr = array();
                $plugins     = unserialize($response['body']);

                if (isset($plugins->plugins) && (count($plugins->plugins) > 0)) {
                    foreach ($plugins->plugins as $pl) {
                        if (! in_array($pl->slug, self::$not_show_plugin_list, true)) {
                            $plugins_arr[] = array(
                                'slug'              => $pl->slug,
                                'name'              => $pl->name,
                                'version'           => $pl->version,
                                'downloaded'        => $pl->downloaded,
                                'active_installs'   => $pl->active_installs,
                                'last_updated'      => strtotime($pl->last_updated),
                                'rating'            => $pl->rating,
                                'num_ratings'       => $pl->num_ratings,
                                'short_description' => $pl->short_description,
                            );
                        }
                    }
                }

                set_transient('themeatelier_plugins', $plugins_arr, 24 * HOUR_IN_SECONDS);
            }
        }

        if (is_array($plugins_arr) && (count($plugins_arr) > 0)) {
            array_multisort(array_column($plugins_arr, 'active_installs'), SORT_DESC, $plugins_arr);


            foreach ($plugins_arr as $plugin) {
                $plugin_slug = $plugin['slug'];
                $image_type  = 'png';
                if (isset(self::$plugins[$plugin_slug])) {
                    $plugin_file = self::$plugins[$plugin_slug];
                } else {
                    $plugin_file = $plugin_slug . '.php';
                }

                switch ($plugin_slug) {
                    case 'postify':
                        $image_type = 'jpg';
                        break;
                    case 'darkify':
                        $image_type = 'gif?rev=3301202';
                        break;
                }

                $details_link = network_admin_url('plugin-install.php?tab=plugin-information&amp;plugin=' . $plugin['slug'] . '&amp;TB_iframe=true&amp;width=600&amp;height=550');
?>
                <div class="plugin-card <?php echo esc_attr($plugin_slug); ?>" id="<?php echo esc_attr($plugin_slug); ?>">
                    <div class="plugin-card-top">
                        <div class="name column-name">
                            <h3>
                                <a class="thickbox" title="<?php echo esc_attr($plugin['name']); ?>"
                                    href="<?php echo esc_url($details_link); ?>">
                                    <?php echo esc_html($plugin['name']); ?>
                                    <img src="<?php echo esc_url('https://ps.w.org/' . $plugin_slug . '/assets/icon-256x256.' . $image_type); ?>"
                                        class="plugin-icon" />
                                </a>
                            </h3>
                        </div>
                        <div class="action-links">
                            <ul class="plugin-action-buttons">
                                <li>
                                    <?php
                                    if ($this->is_plugin_installed($plugin_slug, $plugin_file)) {
                                        if ($this->is_plugin_active($plugin_slug, $plugin_file)) {
                                    ?>
                                            <button type="button" class="button button-disabled" disabled="disabled">Active</button>
                                        <?php
                                        } else {
                                        ?>
                                            <a href="<?php echo esc_url($this->activate_plugin_link($plugin_slug, $plugin_file)); ?>"
                                                class="button button-primary activate-now">
                                                <?php esc_html_e('Activate', 'better-chat-support'); ?>
                                            </a>
                                        <?php
                                        }
                                    } else {
                                        ?>
                                        <a href="<?php echo esc_url($this->install_plugin_link($plugin_slug)); ?>"
                                            class="button install-now">
                                            <?php esc_html_e('Install Now', 'better-chat-support'); ?>
                                        </a>
                                    <?php } ?>
                                </li>
                                <li>
                                    <a href="<?php echo esc_url($details_link); ?>" class="thickbox open-plugin-details-modal"
                                        aria-label="<?php echo esc_html('More information about ' . $plugin['name']); ?>"
                                        title="<?php echo esc_attr($plugin['name']); ?>">
                                        <?php esc_html_e('More Details', 'better-chat-support'); ?>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="desc column-description">
                            <p><?php echo esc_html(isset($plugin['short_description']) ? $plugin['short_description'] : ''); ?></p>
                            <p class="authors"> <cite>By <a href="https://themeatelier.com/">Themeatelier</a></cite></p>
                        </div>
                    </div>
                    <?php
                    echo '<div class="plugin-card-bottom">';

                    if (isset($plugin['rating'], $plugin['num_ratings'])) {
                    ?>
                        <div class="vers column-rating">
                            <?php
                            wp_star_rating(
                                array(
                                    'rating' => $plugin['rating'],
                                    'type'   => 'percent',
                                    'number' => $plugin['num_ratings'],
                                )
                            );
                            ?>
                            <span class="num-ratings">(<?php echo esc_html(number_format_i18n($plugin['num_ratings'])); ?>)</span>
                        </div>
                    <?php
                    }
                    if (isset($plugin['version'])) {
                    ?>
                        <div class="column-updated">
                            <strong><?php esc_html_e('Version:', 'better-chat-support'); ?></strong>
                            <span><?php echo esc_html($plugin['version']); ?></span>
                        </div>
                    <?php
                    }

                    if (isset($plugin['active_installs'])) {
                    ?>
                        <div class="column-downloaded">
                            <?php echo esc_html(number_format_i18n($plugin['active_installs'])) . esc_html__('+ Active Installations', 'better-chat-support'); ?>
                        </div>
                    <?php
                    }

                    if (isset($plugin['last_updated'])) {
                    ?>
                        <div class="column-compatibility">
                            <strong><?php esc_html_e('Last Updated:', 'better-chat-support'); ?></strong>
                            <span><?php echo esc_html(human_time_diff($plugin['last_updated'])) . ' ' . esc_html__('ago', 'better-chat-support'); ?></span>
                        </div>
                    <?php
                    }

                    echo '</div>';
                    ?>
                </div>
        <?php
            }
        }
    }

    /**
     * Check plugins installed function.
     *
     * @param string $plugin_slug Plugin slug.
     * @param string $plugin_file Plugin file.
     * @return boolean
     */
    public function is_plugin_installed($plugin_slug, $plugin_file)
    {
        return file_exists(WP_PLUGIN_DIR . '/' . $plugin_slug . '/' . $plugin_file);
    }

    /**
     * Check active plugin function
     *
     * @param string $plugin_slug Plugin slug.
     * @param string $plugin_file Plugin file.
     * @return boolean
     */
    public function is_plugin_active($plugin_slug, $plugin_file)
    {
        return is_plugin_active($plugin_slug . '/' . $plugin_file);
    }

    /**
     * Install plugin link.
     *
     * @param string $plugin_slug Plugin slug.
     * @return string
     */
    public function install_plugin_link($plugin_slug)
    {
        return wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=' . $plugin_slug), 'install-plugin_' . $plugin_slug);
    }

    /**
     * Active Plugin Link function
     *
     * @param string $plugin_slug Plugin slug.
     * @param string $plugin_file Plugin file.
     * @return string
     */
    public function activate_plugin_link($plugin_slug, $plugin_file)
    {
        return wp_nonce_url(admin_url('admin.php?page=help&action=activate&plugin=' . $plugin_slug . '/' . $plugin_file . '#recommended'), 'activate-plugin_' . $plugin_slug . '/' . $plugin_file);
    }



    /**
     * The Chat Help Help Callback.
     *
     * @return void
     */
    public function help_page_callback()
    {
        add_thickbox();

        $action   = isset($_GET['action']) ? sanitize_text_field(wp_unslash($_GET['action'])) : '';
        $plugin   = isset($_GET['plugin']) ? sanitize_text_field(wp_unslash($_GET['plugin'])) : '';
        $_wpnonce = isset($_GET['_wpnonce']) ? sanitize_text_field(wp_unslash($_GET['_wpnonce'])) : '';

        if (isset($action, $plugin) && ('activate' === $action) && wp_verify_nonce($_wpnonce, 'activate-plugin_' . $plugin)) {
            activate_plugin($plugin, '', false, true);
        }

        if (isset($action, $plugin) && ('deactivate' === $action) && wp_verify_nonce($_wpnonce, 'deactivate-plugin_' . $plugin)) {
            deactivate_plugins($plugin, '', false, true);
        }

        ?>
        <div class="better-chat-support">
            <!-- Header section start -->
            <section class="themeatelier__help header themeatelier-container">
                <div class="themeatelier-container">
                    <div class="header_nav">
                        <div class="header_nav_left">

                            <div class="header_nav_menu">
                                <ul>
                                    <li>
                                        <a href="<?php echo esc_url(home_url('') . '/wp-admin/admin.php?page=help#get-start'); ?>" data-id="get-start-tab" class="active">
                                            <i class="icofont-play-alt-2"></i>
                                            <?php echo esc_html__('Get Started', 'better-chat-support') ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo esc_url(home_url('') . '/wp-admin/admin.php?page=help#recommended'); ?>" data-id="recommended-tab">
                                            <i class="icofont-thumbs-up"></i>
                                            <?php echo esc_html__('Recommended', 'better-chat-support') ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo esc_url(home_url('') . '/wp-admin/admin.php?page=help#lite-to-pro'); ?>" data-id="lite-to-pro-tab">
                                            <i class="icofont-badge"></i>
                                            <?php echo esc_html__('Lite Vs Pro', 'better-chat-support') ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo esc_url(home_url('') . '/wp-admin/admin.php?page=help#pro-plugins'); ?>" data-id="pro-plugins-tab">
                                            <i class="icofont-info-circle"></i>
                                            <?php echo esc_html__('Pro Plugins', 'better-chat-support') ?>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="header_nav_right">
                            <div class="header_nav_right_menu">
                                <a target="_blank" href="<?php echo esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#pricing') ?>"><?php echo esc_html__('🚀 Upgrading To Pro!', 'better-chat-support') ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!--header section end -->

            <!-- Start Page -->
            <section class="start_page tab-content active" id="get-start-tab">
                <div class="themeatelier-container">
                    <div class="start_page_wrapper">
                        <div class="start_page_nav">
                            <div class="nav_left">
                                <h2 class="section_title"><?php echo esc_html('Welcome to Better Chat Support for Messenger!', 'better-chat-support') ?><span class="version__badge"><?php echo esc_html(BETTER_CHAT_SUPPORT_VERSION) ?></span></h2>
                                <span class="section_subtitle">
                                    <?php echo esc_html__('Thank you for installing Better Chat Support for Messenger! This playlist will help you get started with the plugin. Enjoy!', 'better-chat-support') ?>
                                </span>
                            </div>
                            <div class="nev_right">
                                <i class="icofont-youtube-play"></i>
                                <a target="_blank" href="https://www.youtube.com/@themeatelier">Themeatelier</a>
                            </div>
                        </div>
                        <div class="section_video">
                            <div class="video">
                                <iframe width="724" height="405" src="https://www.youtube.com/embed/uskCC8jo4mo"
                                    title="Better Chat Support - Overview" frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                    referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                            </div>
                            <div class="section_video_play_list">
                                <div class="play_list_item active" data-video_id="3LbuUw7SdNQ">
                                    <div class="play_list_item_title">
                                        <h3>Overview</h3>
                                    </div>
                                    <div class="play_list_item_content">
                                        <div class="title">Better Chat Support Plugin - Overview</div>
                                        <span>5:11</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <ul class="section_buttons">
                            <li>
                                <a class="chat_btn_primary"
                                    href="<?php echo esc_url(home_url('') . '/wp-admin/admin.php?page=mcs'); ?>"><?php echo esc_html__('Plugin Settings', 'better-chat-support') ?></a>
                            </li>
                            <li>
                                <a target="_blank" class="chat_btn_secondary"
                                    href="<?php echo esc_url(BETTER_CHAT_SUPPORT_DEMO_URL) ?>"><?php echo esc_html__('Live Demo', 'better-chat-support') ?></a>
                            </li>
                            <li>
                                <a target="_blank" class="chat_btn_secondary arrow-btn"
                                    href="<?php echo esc_url(BETTER_CHAT_SUPPORT_DEMO_URL) ?>"><?php echo esc_html__('Upgrade To Pro', 'better-chat-support') ?>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="section_quick_help">
                        <div class="quick_help_wrapper">
                            <a target="_blank" href="<?php echo BETTER_CHAT_SUPPORT_DOCS_URL; ?>overview" class="quick_help_item">
                                <div class="quick_help_item_icon"><i class="icofont-file-alt"></i></div>
                                <div class="quick_help_item_content">
                                    <h4 class="quick_help_item_title">
                                        <?php echo esc_html__('Documentation', 'better-chat-support') ?>
                                    </h4>
                                    <div class="content"><?php echo esc_html__('Explore Messenger Chat Support plugin capabilities in our enriched documentation.', 'better-chat-support') ?></div>
                                </div>
                            </a>
                            <a target="_blank" href="https://wordpress.org/support/plugin/better-chat-support/" class="quick_help_item">
                                <div class="quick_help_item_icon"><i class="icofont-support"></i></div>
                                <div class="quick_help_item_content">
                                    <h4 class="quick_help_item_title">
                                        <?php echo esc_html__('Technical Support', 'better-chat-support') ?>
                                    </h4>
                                    <div class="content"><?php echo esc_html__('For personalized assistance, reach out to our skilled support team for prompt help.', 'better-chat-support') ?></div>
                                </div>
                            </a>
                            <a target="_blank" href="https://www.themeatelier.net/contact/" class="quick_help_item">
                                <div class="quick_help_item_icon"><i class="icofont-users"></i></div>
                                <div class="quick_help_item_content">
                                    <h4 class="quick_help_item_title">
                                        <?php echo esc_html__('Hire Us!', 'better-chat-support') ?>
                                    </h4>
                                    <div class="content"><?php echo esc_html__('We are available for freelance work for any WordPress, React, NextJS projects. Click to contact us.', 'better-chat-support') ?></div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Recommended Page -->
            <section id="recommended-tab" class="recommended_page tab-content">
                <div class="themeatelier-container">
                    <h2 class="help_page_title">Enhance your Website with our Free Robust Plugins</h2>
                    <div class="themeatelier-wp-list-table plugin-install-php">
                        <div class="recommended_plugins" id="the-list">
                            <?php
                            $this->themeatelier_plugins_info_api_help_page();
                            ?>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Lite To Pro Page -->
            <section class="themeatelier__help lite_vs_pro_page tab-content" id="lite-to-pro-tab">
                <div class="themeatelier-container">
                    <h2 class="help_page_title">Lite Vs Pro Comparison</h2>

                    <div class="themeatelier-features">
                        <ul>
                            <li class="themeatelier-header">
                                <span class="themeatelier-title"><?php echo esc_html__('FEATURES', 'better-chat-support'); ?></span>
                                <span class="themeatelier-free"><?php echo esc_html__('Lite', 'better-chat-support'); ?></span>
                                <span class="themeatelier-pro">🚀<?php echo esc_html__('PRO', 'better-chat-support'); ?></span>
                            </li>

                            <li class="themeatelier-body">
                                <span class="themeatelier-title"><?php echo esc_html__('All Free Version Features', 'better-chat-support'); ?></span>
                                <span class="themeatelier-free themeatelier-check-icon"></span>
                                <span class="themeatelier-pro themeatelier-check-icon"></span>
                            </li>
                            <li class="themeatelier-body">
                                <span class="themeatelier-title">
                                    <?php echo esc_html__('Multi-agents layout.', 'better-chat-support'); ?>
                                    <i class="themeatelier-hot"><?php echo esc_html__('Hot', 'better-chat-support'); ?></i>
                                </span>
                                <span class="themeatelier-free"><span class="themeatelier-free themeatelier-close-icon"></span></span>
                                <span class="themeatelier-pro"><span class="themeatelier-free themeatelier-check-icon"></span></span>
                            </li>
                            <li class="themeatelier-body">
                                <span class="themeatelier-title"><?php echo esc_html__('Time based availability for agents', 'better-chat-support'); ?></span>
                                <span class="themeatelier-free themeatelier-check-icon"></span>
                                <span class="themeatelier-pro themeatelier-check-icon"></span>
                            </li>
                            <li class="themeatelier-body">
                                <span class="themeatelier-title"><?php echo esc_html__('Bubble animation', 'better-chat-support'); ?></span>
                                <span class="themeatelier-free"><b>2</b></span>
                                <span class="themeatelier-pro"><b>14</b></span>
                            </li>
                            <li class="themeatelier-body">
                                <span class="themeatelier-title"><?php echo esc_html__('Dark and Night Layout Mode', 'better-chat-support'); ?></span>
                                <span class="themeatelier-free themeatelier-close-icon"></span>
                                <span class="themeatelier-pro themeatelier-check-icon"></span>
                            </li>
                            <li class="themeatelier-body">
                                <span class="themeatelier-title"><?php echo esc_html__('Transition Effect for Circle Icon', 'better-chat-support'); ?></span>
                                <span class="themeatelier-free"><b>2</b></span>
                                <span class="themeatelier-pro"><b>4</b></span>
                            </li>
                            <li class="themeatelier-body">
                                <span class="themeatelier-title"><?php echo esc_html__('Shortcode Buttons', 'better-chat-support'); ?></span>
                                <span class="themeatelier-free themeatelier-check-icon"></span>
                                <span class="themeatelier-pro themeatelier-check-icon"></span>
                            </li>
                            <li class="themeatelier-body">
                                <span class="themeatelier-title"><?php echo esc_html__('WooCommerce Buttons', 'better-chat-support'); ?></span>
                                <span class="themeatelier-free themeatelier-check-icon"></span>
                                <span class="themeatelier-pro themeatelier-check-icon"></span>
                            </li>
                            <li class="themeatelier-body">
                                <span class="themeatelier-title"><?php echo esc_html__('Gutenberg Button Blocks', 'better-chat-support'); ?></span>
                                <span class="themeatelier-free themeatelier-check-icon"></span>
                                <span class="themeatelier-pro themeatelier-check-icon"></span>
                            </li>
                            <li class="themeatelier-body">
                                <span class="themeatelier-title"><?php echo esc_html__('Export/Import Option', 'better-chat-support'); ?></span>
                                <span class="themeatelier-free themeatelier-check-icon"></span>
                                <span class="themeatelier-pro themeatelier-check-icon"></span>
                            </li>
                        </ul>
                    </div>

                    <div class="themeatelier-upgrade-to-pro">
                        <h2 class="themeatelier-section-title"><?php echo esc_html__('Upgrade To PRO & Enjoy Advanced Features!', 'better-chat-support'); ?></h2>
                        <span class="themeatelier-section-subtitle">
                            <?php
                            // translators: %s: Number of people using Eventful (e.g., 1300+).
                            echo sprintf(esc_html__('Already, %s people are using Better Chat Support on their websites to turn visitors into customers with powerful video greetings — why won’t you?', 'better-chat-support'), '<b>1000+</b>'); ?>
                        </span>
                        <div class="themeatelier-upgrade-to-pro-btn">
                            <div class="themeatelier-action-btn">
                                <a target="_blank" href="<?php echo esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#pricing') ?>" class="chat_btn_primary">
                                    <?php echo esc_html__('Upgrade to Pro Now!', 'better-chat-support'); ?>
                                </a>
                                <span class="themeatelier-small-paragraph">
                                    <?php
                                    // translators: %s: Refund Policy link.
                                    echo sprintf(esc_html__('14-Day No-Questions-Asked %s', 'better-chat-support'), '<a target="_blank" href="https://themeatelier.net/refund-policy/">' . esc_html__('Refund Policy', 'better-chat-support') . '</a>'); ?>
                                </span>
                            </div>
                            <a target="_blank" class="chat_btn_secondary" href="<?php echo esc_url(BETTER_CHAT_SUPPORT_DEMO_URL . '#features') ?>"><?php echo esc_html__('See All Features', 'better-chat-support'); ?></a>
                            <a target="_blank" class="chat_btn_secondary" href="<?php echo esc_url(BETTER_CHAT_SUPPORT_DEMO_URL) ?>"><?php echo esc_html__('Pro Live Demo', 'better-chat-support'); ?></a>
                        </div>
                    </div>


                    <div class="chat_testimonial">
                        <div class="chat_testimonial_title_section">
                            <span class="chat_testimonial-subtitle"><?php echo esc_html__('NO NEED TO TAKE OUR WORD FOR IT', 'better-chat-support'); ?></span>
                            <h2 class="themeatelier-section-title"><?php echo esc_html__('Our Users Love Better Chat Support!', 'better-chat-support'); ?></h2>
                        </div>
                        <div class="chat_testimonial_wrap">

                            <div class="chat_testimonial_area">
                                <div class="chat_testimonial_content">
                                    <p><?php echo esc_html__('This is exactly what I was looking for, I just need the chat bubble, the customer clicks on it and jumps to the landing page. It would be great if the plugin allows to adjust the bubble button position. It is too close to the bottom margin, and overlaps the BACK TO HOME button.', 'better-chat-support'); ?></p>
                                </div>
                                <div class="chat_testimonial-info">
                                    <div class="themeatelier-img">
                                        <img src="<?php echo esc_url(BETTER_CHAT_SUPPORT_DIR_URL . 'src/Admin/HelpPage/assets/images/TomQv.jpeg'); ?>"
                                            alt="<?php echo esc_attr__('TomQv', 'better-chat-support'); ?>">
                                    </div>
                                    <div class="themeatelier-info">
                                        <h3><?php echo esc_html__('TomQv', 'better-chat-support'); ?></h3>
                                        <div class="themeatelier-star">
                                            <i>★★★★★</i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="chat_testimonial_area">
                                <div class="chat_testimonial_content">
                                    <p><?php echo esc_html__('Thank you for that easy to use, easy to setup plugin. Works great and allows me to have direct contact with my Messenger clients.', 'better-chat-support'); ?></p>
                                </div>
                                <div class="chat_testimonial-info">
                                    <div class="themeatelier-img">
                                        <img src="<?php echo esc_url(BETTER_CHAT_SUPPORT_DIR_URL . 'src/Admin/HelpPage/assets/images/Pascal.jpeg'); ?>"
                                            alt="<?php echo esc_attr__('Pascal', 'better-chat-support'); ?>">
                                    </div>
                                    <div class="themeatelier-info">
                                        <h3><?php echo esc_html__('Pascal', 'better-chat-support'); ?></h3>
                                        <div class="themeatelier-star">
                                            <i>★★★★★</i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="chat_testimonial_area">
                                <div class="chat_testimonial_content">
                                    <p><?php echo esc_html__('I appreciate your work. It was easy to set it up.', 'better-chat-support'); ?></p>
                                </div>
                                <div class="chat_testimonial-info">
                                    <div class="themeatelier-img">
                                        <img src="<?php echo esc_url(BETTER_CHAT_SUPPORT_DIR_URL . 'src/Admin/HelpPage/assets/images/user_image.jpg'); ?>"
                                            alt="<?php echo esc_attr__('st025', 'better-chat-support'); ?>">
                                    </div>
                                    <div class="themeatelier-info">
                                        <h3><?php echo esc_html__('st025', 'better-chat-support'); ?></h3>
                                        <div class="themeatelier-star">
                                            <i>★★★★★</i>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>

                    </div>
                </div>
            </section>

            <!-- About Page -->
            <section id="pro-plugins-tab" class="themeatelier__help about-page tab-content">
                <div class="themeatelier-container">
                    <div class="themeatelier-our-plugin-list">
                        <h2 class="help_page_title"><?php echo esc_html__('Upgrade your Website with our High-quality Plugins!', 'better-chat-support') ?></h2>
                        <div class="themeatelier-our-plugin-list-wrap">
                            <a target="_blank" class="themeatelier-our-plugin-list-box" href="<?php echo esc_url(BETTER_CHAT_SUPPORT_DEMO_URL) ?>">
                                <div class="box_btn">
                                    <?php echo esc_html__('View Details ', 'better-chat-support') ?>
                                    <i class="icofont-long-arrow-right"></i>
                                </div>
                                <img src="<?php echo esc_url(BETTER_CHAT_SUPPORT_DIR_URL . 'src/Admin/HelpPage/assets/images/greet-logo.png') ?>" alt="logo">
                                <h4><?php echo esc_html__('Greet Bubble - Video Greetings, Video Welcome for WordPress', 'better-chat-support'); ?></h4>
                                <p><?php echo esc_html__('Placing a video on websites can significantly increase sales of services or products. Greet is a professional video bubble plugin for displaying welcome videos in a fun and engaging way.', 'better-chat-support'); ?></p>
                            </a>
                            <a target="_blank" class="themeatelier-our-plugin-list-box" href="https://wpchathelp.com/">
                                <div class="box_btn">
                                    <?php echo esc_html__('View Details ', 'better-chat-support') ?>
                                    <i class="icofont-long-arrow-right"></i>
                                </div>
                                <img src="<?php echo esc_url(BETTER_CHAT_SUPPORT_DIR_URL . 'src/Admin/HelpPage/assets/images/chat-help.png') ?>" alt="logo">
                                <h4><?php echo esc_html__('WhatsApp Chat Help - Chat Support Plugin for WordPress', 'better-chat-support'); ?></h4>
                                <p><?php echo esc_html__('WhatsApp Chat Help allows website owners to easily add a WhatsApp chat support button to their WordPress site.', 'better-chat-support'); ?></p>
                            </a>

                            <a target="_blank" class="themeatelier-our-plugin-list-box" href="https://wpeventful.com/">
                                <div class="box_btn">
                                    <?php echo esc_html__('View Details ', 'better-chat-support') ?>
                                    <i class="icofont-long-arrow-right"></i>
                                </div>
                                <img src="<?php echo esc_url(BETTER_CHAT_SUPPORT_DIR_URL . 'src/Admin/HelpPage/assets/images/eventful.png') ?>" alt="Eventful">
                                <h4>
                                    <?php echo esc_html__('Eventful - Event Showcase Addon for The Events Calendar', 'better-chat-support') ?>
                                </h4>
                                <p><?php echo esc_html__('With "Eventful," you can effortlessly create intelligent layouts for "The Events Calendar" plugin, effectively addressing and resolving compatibility issues that may arise.', 'better-chat-support') ?></p>
                            </a>
                            <a target="_blank" class="themeatelier-our-plugin-list-box" href="https://darkifywp.com/">
                                <div class="box_btn">
                                    <?php echo esc_html__('View Details ', 'better-chat-support') ?>
                                    <i class="icofont-long-arrow-right"></i>
                                </div>
                                <img src="<?php echo esc_url(BETTER_CHAT_SUPPORT_DIR_URL . 'src/Admin/HelpPage/assets/images/darkify.gif') ?>" alt="logo">
                                <h4><?php echo esc_html__('Darkify - WordPress Dark Mode Plugin', 'better-chat-support') ?></h4>
                                <p><?php echo esc_html__('Darkify – is an extremely advanced dark mode plugin for any WordPress website. The plugin has the option to enable a dark mode switcher for the front end and also WordPress admin.', 'better-chat-support') ?></p>
                            </a>
                            <a target="_blank" class="themeatelier-our-plugin-list-box" href="https://wpdomainforsale.com/">
                                <div class="box_btn">
                                    <?php echo esc_html__('View Details ', 'better-chat-support') ?>
                                    <i class="icofont-long-arrow-right"></i>
                                </div>
                                <img src="<?php echo esc_url(BETTER_CHAT_SUPPORT_DIR_URL . 'src/Admin/HelpPage/assets/images/thumbnail-2.png') ?>" alt="logo">
                                <h4><?php echo esc_html__('Domain For Sale Plugin for WordPress', 'better-chat-support') ?></h4>
                                <p><?php echo esc_html__('The ultimate WordPress plugin for domain sales, appraisals, auctions, and marketplace management.', 'better-chat-support') ?></p>
                            </a>
                            <a target="_blank" class="themeatelier-our-plugin-list-box" href="https://themeatelier.net/downloads/eventful-for-elementor/">
                                <div class="box_btn">
                                    <?php echo esc_html__('View Details ', 'better-chat-support') ?>
                                    <i class="icofont-long-arrow-right"></i>
                                </div>
                                <img src="<?php echo esc_url(BETTER_CHAT_SUPPORT_DIR_URL . 'src/Admin/HelpPage/assets/images/eventful-for-elementor.png') ?>" alt="logo">
                                <h4><?php echo esc_html__('Eventful for Elementor - Events Showcase for The Events Calendar', 'better-chat-support') ?></h4>
                                <p><?php echo esc_html__('Easily display events from The Events Calendar plugin with Elementor widgets, offering seamless customization and powerful layout options.', 'better-chat-support') ?></p>
                            </a>



                        </div>
                    </div>
                </div>
            </section>

            <!-- Footer Section -->
            <section class="themeatelier_footer">
                <div class="themeatelier_footer_top">
                    <p>
                        <span><?php echo esc_html__('Made With', 'better-chat-support') ?> <i class="icofont-heart-alt"></i></span>
                        <?php echo esc_html__('By the Team', 'better-chat-support') ?> <a target="_blank" href="https://themeatelier.net/"><?php echo esc_html__('ThemeAtelier', 'better-chat-support') ?></a>
                    </p>
                    <p><?php echo esc_html__('Get connected with', 'better-chat-support') ?></p>
                    <ul>
                        <li>
                            <a target="_blank" href="https://www.facebook.com/ThemeAtelier/"><i
                                    class="icofont-facebook"></i></a>
                        </li>
                        <li>
                            <a target="_blank" href="https://x.com/intent/follow?screen_name=themeatelier"><i
                                    class="icofont-x"></i></a>
                        </li>
                        <li>
                            <a target="_blank" href="https://profiles.wordpress.org/themeatelier/#content-plugins"><i
                                    class="icofont-brand-wordpress"></i></a>
                        </li>
                        <li>
                            <a target="_blank" href="https://www.youtube.com/@themeatelier"><i
                                    class="icofont-youtube-play"></i></a>
                        </li>
                    </ul>
                </div>
            </section>
        </div>
<?php
    }
}
