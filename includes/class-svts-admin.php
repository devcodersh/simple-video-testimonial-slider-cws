<?php
/**
 * Admin functionality
 *
 * @package SimpleVideoTestimonialSliderCWS
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class SVTS_Admin {

    /**
     * Constructor
     */
    public function __construct() {
        add_action('admin_post_svts_save_settings', array($this, 'save_settings'));
    }

    /**
     * Render admin page
     */
    public function render_page() {
        // Check user capabilities
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'simple-video-testimonial-slider-cws'));
        }

        $slides = get_option('svts_slides', array());
        $slider_heading = get_option('svts_slider_heading', __('Loved By Niche Digital Agencies Across The US!', 'simple-video-testimonial-slider-cws'));
        $nav_color = get_option('svts_nav_color', '#89AD29');
        $nav_hover_color = get_option('svts_nav_hover_color', '#6a47ed');
        $nav_hover_icon_color = get_option('svts_nav_hover_icon_color', '#6a47ed');
        $play_color = get_option('svts_play_color', '#89AD29');
        $play_icon_color = get_option('svts_play_icon_color', '#6a47ed');

        ?>
        <div class="wrap">
            <h1><?php _e('Video Testimonial Slider Settings', 'simple-video-testimonial-slider-cws'); ?></h1>

            <?php settings_errors(); ?>

            <div class="svts-admin-container">
                <form method="post" action="<?php echo admin_url('admin-post.php'); ?>" enctype="multipart/form-data">
                    <?php wp_nonce_field('svts_save_settings', 'svts_nonce'); ?>
                    <input type="hidden" name="action" value="svts_save_settings">

                    <div class="svts-top-button">
                        <?php submit_button(__('Save Settings', 'simple-video-testimonial-slider-cws'), 'primary', 'submit'); ?>
                    </div>

                    <div class="svts-section">
                        <h2><?php _e('Slider Heading', 'simple-video-testimonial-slider-cws'); ?></h2>
                        <table class="form-table">
                            <tr>
                                <th scope="row">
                                    <label for="slider_heading"><?php _e('Slider Heading', 'simple-video-testimonial-slider-cws'); ?></label>
                                </th>
                                <td>
                                    <input type="text" id="slider_heading" name="slider_heading" value="<?php echo esc_attr($slider_heading); ?>" class="regular-text" />
                                    <p class="description"><?php _e('Add a custom heading for the testimonial slider.', 'simple-video-testimonial-slider-cws'); ?></p>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="svts-section">
                        <h2><?php _e('Color Settings', 'simple-video-testimonial-slider-cws'); ?></h2>
                        <table class="form-table">
                            <tr>
                                <th scope="row">
                                    <label for="nav_color"><?php _e('Navigation Buttons Color', 'simple-video-testimonial-slider-cws'); ?></label>
                                </th>
                                <td>
                                    <input type="color" id="nav_color" name="nav_color" value="<?php echo esc_attr($nav_color); ?>" />
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="nav_hover_color"><?php _e('Navigation Buttons Hover Background', 'simple-video-testimonial-slider-cws'); ?></label>
                                </th>
                                <td>
                                    <input type="color" id="nav_hover_color" name="nav_hover_color" value="<?php echo esc_attr($nav_hover_color); ?>" />
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="nav_hover_icon_color"><?php _e('Navigation Buttons Hover Icon', 'simple-video-testimonial-slider-cws'); ?></label>
                                </th>
                                <td>
                                    <input type="color" id="nav_hover_icon_color" name="nav_hover_icon_color" value="<?php echo esc_attr($nav_hover_icon_color); ?>" />
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="play_color"><?php _e('Play Button Background', 'simple-video-testimonial-slider-cws'); ?></label>
                                </th>
                                <td>
                                    <input type="color" id="play_color" name="play_color" value="<?php echo esc_attr($play_color); ?>" />
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="play_icon_color"><?php _e('Play Button Icon', 'simple-video-testimonial-slider-cws'); ?></label>
                                </th>
                                <td>
                                    <input type="color" id="play_icon_color" name="play_icon_color" value="<?php echo esc_attr($play_icon_color); ?>" />
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="svts-section">
                        <h2><?php _e('Testimonials', 'simple-video-testimonial-slider-cws'); ?></h2>
                        <div id="svts-slides-container">
                            <?php if (!empty($slides)): ?>
                                <?php foreach ($slides as $index => $slide): ?>
                                    <div class="svts-slide-item" data-index="<?php echo $index; ?>">
                                        <h3><?php printf(__('Testimonial %d', 'simple-video-testimonial-slider-cws'), $index + 1); ?></h3>
                                        <table class="form-table">
                                            <tr>
                                                <th scope="row">
                                                    <label><?php _e('Video URL', 'simple-video-testimonial-slider-cws'); ?></label>
                                                </th>
                                                <td>
                                                    <input type="url" name="slides[<?php echo $index; ?>][video]" value="<?php echo esc_url($slide['video']); ?>" placeholder="https://example.com/video.mp4" required />
                                                    <p class="description"><?php _e('Enter the URL of your video file (MP4, WebM, OGV supported)', 'simple-video-testimonial-slider-cws'); ?></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">
                                                    <label><?php _e('Name', 'simple-video-testimonial-slider-cws'); ?></label>
                                                </th>
                                                <td>
                                                    <input type="text" name="slides[<?php echo $index; ?>][name]" value="<?php echo esc_attr($slide['name']); ?>" required />
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">
                                                    <label><?php _e('Role/Position', 'simple-video-testimonial-slider-cws'); ?></label>
                                                </th>
                                                <td>
                                                    <input type="text" name="slides[<?php echo $index; ?>][role]" value="<?php echo esc_attr($slide['role']); ?>" required />
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">
                                                    <label><?php _e('Star Rating', 'simple-video-testimonial-slider-cws'); ?></label>
                                                </th>
                                                <td>
                                                    <select name="slides[<?php echo $index; ?>][stars]" required>
                                                        <option value="1" <?php selected($slide['stars'], '1'); ?>>★ (1 Star)</option>
                                                        <option value="2" <?php selected($slide['stars'], '2'); ?>>★★ (2 Stars)</option>
                                                        <option value="3" <?php selected($slide['stars'], '3'); ?>>★★★ (3 Stars)</option>
                                                        <option value="4" <?php selected($slide['stars'], '4'); ?>>★★★★ (4 Stars)</option>
                                                        <option value="5" <?php selected($slide['stars'], '5'); ?>>★★★★★ (5 Stars)</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">
                                                    <label><?php _e('Description', 'simple-video-testimonial-slider-cws'); ?></label>
                                                </th>
                                                <td>
                                                    <textarea name="slides[<?php echo $index; ?>][desc]" rows="3" required><?php echo esc_textarea($slide['desc']); ?></textarea>
                                                </td>
                                            </tr>
                                        </table>
                                        <button type="button" class="button svts-remove-slide"><?php _e('Remove Testimonial', 'simple-video-testimonial-slider-cws'); ?></button>
                                        <hr>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                        <button type="button" id="svts-add-slide" class="button button-secondary"><?php _e('Add New Testimonial', 'simple-video-testimonial-slider-cws'); ?></button>
                    </div>

                    <?php submit_button(__('Save Settings', 'simple-video-testimonial-slider-cws'), 'primary', 'submit'); ?>
                    
                </form>

                <div class="svts-preview-section">
                    <h2><?php _e('Preview', 'simple-video-testimonial-slider-cws'); ?></h2>
                    <div class="svts-preview-notice">
                        <p><?php _e('Use the shortcode [video_testimonials] to display the slider on your site.', 'simple-video-testimonial-slider-cws'); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <script type="text/html" id="svts-slide-template">
            <div class="svts-slide-item" data-index="{{index}}">
                <h3><?php _e('Testimonial', 'simple-video-testimonial-slider-cws'); ?> {{number}}</h3>
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label><?php _e('Video URL', 'simple-video-testimonial-slider-cws'); ?></label>
                        </th>
                        <td>
                            <input type="url" name="slides[{{index}}][video]" placeholder="https://example.com/video.mp4" required />
                            <p class="description"><?php _e('Enter the URL of your video file (MP4, WebM, OGV supported)', 'simple-video-testimonial-slider-cws'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label><?php _e('Name', 'simple-video-testimonial-slider-cws'); ?></label>
                        </th>
                        <td>
                            <input type="text" name="slides[{{index}}][name]" required />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label><?php _e('Role/Position', 'simple-video-testimonial-slider-cws'); ?></label>
                        </th>
                        <td>
                            <input type="text" name="slides[{{index}}][role]" required />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label><?php _e('Star Rating', 'simple-video-testimonial-slider-cws'); ?></label>
                        </th>
                        <td>
                            <select name="slides[{{index}}][stars]" required>
                                <option value="1">★ (1 Star)</option>
                                <option value="2">★★ (2 Stars)</option>
                                <option value="3">★★★ (3 Stars)</option>
                                <option value="4">★★★★ (4 Stars)</option>
                                <option value="5" selected>★★★★★ (5 Stars)</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label><?php _e('Description', 'simple-video-testimonial-slider-cws'); ?></label>
                        </th>
                        <td>
                            <textarea name="slides[{{index}}][desc]" rows="3" required></textarea>
                        </td>
                    </tr>
                </table>
                <button type="button" class="button svts-remove-slide"><?php _e('Remove Testimonial', 'simple-video-testimonial-slider-cws'); ?></button>
                <hr>
            </div>
        </script>
        <?php
    }

    /**
     * Save settings
     */
    public function save_settings() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['svts_nonce'], 'svts_save_settings')) {
            wp_die(__('Security check failed', 'simple-video-testimonial-slider-cws'));
        }

        // Check user capabilities
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions', 'simple-video-testimonial-slider-cws'));
        }

        // Save slider heading
        if (isset($_POST['slider_heading'])) {
            update_option('svts_slider_heading', sanitize_text_field($_POST['slider_heading']));
        }

        // Save color settings
        $color_fields = array('nav_color', 'nav_hover_color', 'nav_hover_icon_color', 'play_color', 'play_icon_color');
        foreach ($color_fields as $field) {
            if (isset($_POST[$field])) {
                $color = sanitize_hex_color($_POST[$field]);
                if ($color) {
                    update_option('svts_' . $field, $color);
                }
            }
        }

        // Save slides
        $slides = array();
        if (isset($_POST['slides']) && is_array($_POST['slides'])) {
            foreach ($_POST['slides'] as $slide) {
                if (!empty($slide['video']) && !empty($slide['name'])) {
                    $slides[] = array(
                        'video' => esc_url_raw($slide['video']),
                        'name' => sanitize_text_field($slide['name']),
                        'role' => sanitize_text_field($slide['role']),
                        'desc' => sanitize_textarea_field($slide['desc']),
                        'stars' => intval($slide['stars']) > 0 ? intval($slide['stars']) : 5,
                    );
                }
            }
        }

        update_option('svts_slides', $slides);

        // Redirect back with success message
        wp_redirect(add_query_arg(
            array(
                'page' => 'svts-slider',
                'message' => 'saved'
            ),
            admin_url('admin.php')
        ));
        exit;
    }
}