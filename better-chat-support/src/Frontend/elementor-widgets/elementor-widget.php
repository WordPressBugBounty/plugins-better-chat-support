<?php
if (! defined('ABSPATH')) exit;

class Mcs_El_Widgets
{

    /**
     *
     * Holds the version of the plugin.
     *
     * @since 1.7.0
     * @since 1.7.1 Moved from property with that name to a constant.
     *
     * @var string The plugin version.
     */
    const  VERSION = '1.0';
    /**
     * Minimum Elementor Version
     *
     * Holds the minimum Elementor version required to run the plugin.
     *
     * @since 1.7.0
     * @since 1.7.1 Moved from property with that name to a constant.
     *
     * @var string Minimum Elementor version required to run the plugin.
     */
    const  MINIMUM_ELEMENTOR_VERSION = '1.7.0';
    /**
     * Minimum PHP Version
     *
     * Holds the minimum PHP version required to run the plugin.
     *
     * @since 1.7.0
     * @since 1.7.1 Moved from property with that name to a constant.
     *
     * @var string Minimum PHP version required to run the plugin.
     */
    const  MINIMUM_PHP_VERSION = '5.4';
    /**
     * Instance
     *
     * Holds a single instance of the `Press_Elements` class.
     *
     * @since 1.7.0
     *
     * @access private
     * @static
     *
     * @var Press_Elements A single instance of the class.
     */
    private static  $_instance = null;

    public function __construct()
    {
        $this->init_hooks();
        add_action(
            'elementor/widgets/register',
            [$this, 'register_widgets']
        );

        add_action(
            'elementor/elements/categories_registered',
            [$this, 'register_category']
        );
    }

    /**
     * Init Hooks
     *
     * Hook into actions and filters.
     *
     * @since 1.7.0
     *
     * @access private
     */
    private function init_hooks()
    {
        add_action('init', [$this, 'init']);
    }

    /**
     * Init Laamya Elementor Widget
     *
     * Load the plugin after Elementor (and other plugins) are loaded.
     *
     * @since 1.0.0
     * @since 1.7.0 The logic moved from a standalone function to this class method.
     *
     * @access public
     */
    public function init()
    {

        if (!did_action('elementor/loaded')) {
            //add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
            return;
        }

        // Check for required Elementor version

        if (!version_compare(ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=')) {
            add_action('admin_notices', [$this, 'admin_notice_minimum_elementor_version']);
            return;
        }

        // Check for required PHP version

        if (version_compare(PHP_VERSION, self::MINIMUM_PHP_VERSION, '<')) {
            add_action('admin_notices', [$this, 'admin_notice_minimum_php_version']);
            return;
        }

        // Add new Elementor Categories
        add_action('elementor/elements/categories_registered', [$this, 'add_elementor_category']);


        // Register New Widgets
        add_action('elementor/widgets/widgets_registered', [$this, 'on_widgets_registered']);

        // Laamya Companion enqueue style and scripts
        add_action('wp_enqueue_scripts', [$this, 'enqueue_element_widgets_scripts']);
    }

    public function register_category()
    {
        \Elementor\Plugin::instance()->elements_manager->add_category(
            'mcs-elements',
            [
                'title' => __('Messenger Chat Support', 'better-chat-support'),
            ]
        );
    }

    public function register_widgets($widgets_manager)
    {
        require_once __DIR__ . '/widgets/buttons.php';
        $widgets_manager->register(
            new \Mcselementor\Widgets\Mcs_Buttons()
        );
    }
}
