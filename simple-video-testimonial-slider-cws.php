<?php
/*
Plugin Name: Simple Video Testimonial Slider CWS
Plugin URI: https://example.com/simple-video-testimonial-slider-cws
Description: Custom video testimonial slider with shortcode
Version: 1.0
*/

if (!defined('ABSPATH')) exit;
require_once plugin_dir_path(__FILE__) . 'admin/settings-page.php';
/* --------------------------
   Enqueue Assets
-------------------------- */
function vts_enqueue_assets() {
    wp_enqueue_style('swiper-css', 'https://cdnjs.cloudflare.com/ajax/libs/Swiper/11.0.5/swiper-bundle.min.css');
    wp_enqueue_script('swiper-js', 'https://cdnjs.cloudflare.com/ajax/libs/Swiper/11.0.5/swiper-bundle.min.js', [], null, true);

    wp_enqueue_style('vts-style', plugin_dir_url(__FILE__) . 'assets/css/style.css');
    wp_enqueue_script('vts-script', plugin_dir_url(__FILE__) . 'assets/js/script.js', ['swiper-js'], null, true);

    wp_localize_script('vts-script', 'vtsData', [
        'navColor' => get_option('vts_nav_color', '#89AD29'),
        'playColor' => get_option('vts_play_color', '#89AD29'),
    ]);
}
add_action('wp_enqueue_scripts', 'vts_enqueue_assets');

function vts_slider_shortcode() {

    $slides = get_option('vts_slides', []);

    ob_start();
    ?>

    <section class="testimonial-section">
        <div class="testimonial-header">
            <h2>Loved By Niche Digital Agencies Across The US!</h2>
            <div class="nav-btns">
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>

        <div class="swiper mySwiper">
            <div class="swiper-wrapper">

                <?php foreach ($slides as $slide): ?>
                    <div class="swiper-slide">
                        <div class="video-wrapper">
                            <video src="<?php echo esc_url($slide['video']); ?>" muted></video>

                            <div class="play-btn">
                                <svg
                                xmlns="http://www.w3.org/2000/svg"
                                height="1em"
                                viewBox="0 0 384 512"
                                fill="#6a47ed"
                                >
                                <path
                                    d="M361 215C375.2 223.2 384 238.1 384 254.1s-8.75 30.9-23 39L87 473c-14.4 8.1-32 7.9-46.2-.5S0 450.4 0 433.1V78.9C0 61.6 9.75 45.6 24 37.1S72.6 31.9 87 40.1L361 215z"
                                />
                                </svg>
                            </div>

                            <div class="card-content">
                                <div class="stars">★★★★★</div>
                                <h3><?php echo esc_html($slide['name']); ?></h3>
                                <p class="role"><?php echo esc_html($slide['role']); ?></p>
                                <p class="desc"><?php echo esc_html($slide['desc']); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>
    </section>

    <?php
    return ob_get_clean();
}
add_shortcode('video_testimonials', 'vts_slider_shortcode');

function vts_add_admin_menu() {
    add_menu_page(
        'Video Slider',
        'Video Slider',
        'manage_options',
        'vts-slider',
        'vts_settings_page'
    );
}
add_action('admin_menu', 'vts_add_admin_menu');