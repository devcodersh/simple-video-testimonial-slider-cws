<?php
/**
 * Simple Video Testimonial Slider CWS - Uninstall
 *
 * @package SimpleVideoTestimonialSliderCWS
 */

// Prevent direct access
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Delete plugin options
$options_to_delete = array(
    'svts_nav_color',
    'svts_nav_hover_color',
    'svts_nav_hover_icon_color',
    'svts_play_color',
    'svts_play_icon_color',
    'svts_slides',
);

foreach ($options_to_delete as $option) {
    delete_option($option);
}

// Clear any cached data if needed
wp_cache_flush();