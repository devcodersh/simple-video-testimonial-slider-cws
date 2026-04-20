<?php
/**
 * Main plugin class
 *
 * @package SimpleVideoTestimonialSliderCWS
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class SVTS_Plugin {

    /**
     * Single instance of the plugin
     */
    private static $instance = null;

    /**
     * Get single instance of the plugin
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->init_hooks();
        $this->includes();
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        add_action('init', array($this, 'load_textdomain'));
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_assets'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        add_shortcode('video_testimonials', array($this, 'render_slider_shortcode'));
    }

    /**
     * Admin instance.
     *
     * @var SVTS_Admin
     */
    private $admin;

    /**
     * Include required files
     */
    private function includes() {
        require_once SVTS_PLUGIN_DIR . 'includes/class-svts-admin.php';
        require_once SVTS_PLUGIN_DIR . 'includes/class-svts-frontend.php';

        $this->admin = new SVTS_Admin();
    }

    /**
     * Load plugin textdomain
     */
    public function load_textdomain() {
        load_plugin_textdomain(
            'simple-video-testimonial-slider-cws',
            false,
            dirname(SVTS_PLUGIN_BASENAME) . '/languages/'
        );
    }

    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_menu_page(
            __('Video Testimonial Slider', 'simple-video-testimonial-slider-cws'),
            __('Video Slider', 'simple-video-testimonial-slider-cws'),
            'manage_options',
            'svts-slider',
            array($this, 'admin_page'),
            'dashicons-video-alt3',
            30
        );
    }

    /**
     * Admin page callback
     */
    public function admin_page() {
        $admin = new SVTS_Admin();
        $admin->render_page();
    }

    /**
     * Enqueue frontend assets
     */
    public function enqueue_frontend_assets() {
        // Enqueue bundled Swiper instead of CDN
        wp_enqueue_style(
            'svts-swiper',
            SVTS_PLUGIN_URL . 'assets/css/swiper-bundle.min.css',
            array(),
            '11.0.5'
        );

        wp_enqueue_script(
            'svts-swiper',
            SVTS_PLUGIN_URL . 'assets/js/swiper-bundle.min.js',
            array(),
            '11.0.5',
            true
        );

        wp_enqueue_style(
            'svts-style',
            SVTS_PLUGIN_URL . 'assets/css/style.css',
            array('svts-swiper'),
            SVTS_VERSION
        );

        // Add inline CSS to prevent color flash on page load
        $inline_css = sprintf(
            ':root { --svts-nav-color: %s; --svts-nav-hover-color: %s; --svts-nav-hover-icon-color: %s; --svts-play-color: %s; --svts-play-icon-color: %s; }',
            get_option('svts_nav_color', '#89AD29'),
            get_option('svts_nav_hover_color', '#6a47ed'),
            get_option('svts_nav_hover_icon_color', '#6a47ed'),
            get_option('svts_play_color', '#89AD29'),
            get_option('svts_play_icon_color', '#6a47ed')
        );
        wp_add_inline_style('svts-style', $inline_css);

        wp_enqueue_script(
            'svts-script',
            SVTS_PLUGIN_URL . 'assets/js/script.js',
            array('svts-swiper'),
            SVTS_VERSION,
            true
        );

        // Localize script with settings
        wp_localize_script('svts-script', 'svtsData', array(
            'navColor' => get_option('svts_nav_color', '#89AD29'),
            'navHoverColor' => get_option('svts_nav_hover_color', '#6a47ed'),
            'navHoverIconColor' => get_option('svts_nav_hover_icon_color', '#6a47ed'),
            'playColor' => get_option('svts_play_color', '#89AD29'),
            'playIconColor' => get_option('svts_play_icon_color', '#6a47ed'),
        ));
    }

    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets($hook) {
        if ('toplevel_page_svts-slider' !== $hook) {
            return;
        }

        wp_enqueue_style(
            'svts-admin',
            SVTS_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            SVTS_VERSION
        );

        wp_enqueue_script(
            'svts-admin',
            SVTS_PLUGIN_URL . 'assets/js/admin.js',
            array('jquery'),
            SVTS_VERSION,
            true
        );

        wp_localize_script('svts-admin', 'svtsAdmin', array(
            'nonce' => wp_create_nonce('svts_admin_nonce'),
            'confirmDelete' => __('Are you sure you want to delete this testimonial?', 'simple-video-testimonial-slider-cws'),
        ));
    }

    /**
     * Render slider shortcode
     */
    public function render_slider_shortcode($atts) {
        $frontend = new SVTS_Frontend();
        return $frontend->render_slider($atts);
    }

    /**
     * Plugin activation
     */
    public static function activate() {
        // Set default options
        add_option('svts_nav_color', '#89AD29');
        add_option('svts_nav_hover_color', '#6a47ed');
        add_option('svts_nav_hover_icon_color', '#6a47ed');
        add_option('svts_play_color', '#89AD29');
        add_option('svts_play_icon_color', '#6a47ed');
        add_option('svts_slider_heading', __('Loved By Niche Digital Agencies Across The US!', 'simple-video-testimonial-slider-cws'));
        add_option('svts_slides', array());

        // Flush rewrite rules
        flush_rewrite_rules();
    }

    /**
     * Plugin deactivation
     */
    public static function deactivate() {
        // Flush rewrite rules
        flush_rewrite_rules();
    }
}