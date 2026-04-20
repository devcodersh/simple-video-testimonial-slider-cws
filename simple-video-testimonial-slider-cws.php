<?php
/**
 * Plugin Name: Simple Video Testimonial Slider CWS
 * Plugin URI: https://wordpress.org/plugins/simple-video-testimonial-slider-cws/
 * Description: A responsive video testimonial slider plugin with customizable colors and easy shortcode integration.
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://yourwebsite.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: simple-video-testimonial-slider-cws
 * Requires at least: 5.0
 * Tested up to: 6.4
 * Requires PHP: 7.4
 * Network: false
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('SVTS_VERSION', '1.0.0');
define('SVTS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SVTS_PLUGIN_URL', plugin_dir_url(__FILE__));
define('SVTS_PLUGIN_BASENAME', plugin_basename(__FILE__));

// Include required files
require_once SVTS_PLUGIN_DIR . 'includes/class-svts-plugin.php';

// Initialize the plugin
function svts_init() {
    SVTS_Plugin::get_instance();
}
add_action('plugins_loaded', 'svts_init');

// Activation hook
register_activation_hook(__FILE__, array('SVTS_Plugin', 'activate'));

// Deactivation hook
register_deactivation_hook(__FILE__, array('SVTS_Plugin', 'deactivate'));