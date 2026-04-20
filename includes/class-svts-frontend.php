<?php
/**
 * Frontend functionality
 *
 * @package SimpleVideoTestimonialSliderCWS
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class SVTS_Frontend {

    /**
     * Render the testimonial slider
     */
    public function render_slider($atts) {
        $slider_heading = get_option('svts_slider_heading', __('Loved By Niche Digital Agencies Across The US!', 'simple-video-testimonial-slider-cws'));

        $atts = shortcode_atts(array(
            'title' => $slider_heading,
            'class' => '',
        ), $atts);

        $slides = get_option('svts_slides', array());

        if (empty($slides)) {
            return '<p>' . __('No testimonials found. Please add some testimonials in the admin settings.', 'simple-video-testimonial-slider-cws') . '</p>';
        }

        ob_start();
        ?>
        <section class="svts-testimonial-section <?php echo esc_attr($atts['class']); ?>">
            <div class="svts-testimonial-header">
                <h2><?php echo esc_html($atts['title']); ?></h2>
                <div class="svts-nav-btns">
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                </div>
            </div>

            <div class="swiper svts-swiper">
                <div class="swiper-wrapper">
                    <?php foreach ($slides as $slide): ?>
                        <div class="swiper-slide">
                            <div class="svts-video-wrapper">
                                <video src="<?php echo esc_url($slide['video']); ?>" preload="metadata"></video>

                                <div class="svts-play-btn" aria-label="<?php esc_attr_e('Play video', 'simple-video-testimonial-slider-cws'); ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 384 512" fill="var(--svts-play-icon-color)">
                                        <path d="M361 215C375.2 223.2 384 238.1 384 254.1s-8.75 30.9-23 39L87 473c-14.4 8.1-32 7.9-46.2-.5S0 450.4 0 433.1V78.9C0 61.6 9.75 45.6 24 37.1S72.6 31.9 87 40.1L361 215z"/>
                                    </svg>
                                </div>

                                <div class="svts-card-content">
                                    <div class="svts-stars" aria-label="<?php esc_attr_e('Star rating: ', 'simple-video-testimonial-slider-cws'); ?><?php echo intval($slide['stars']); ?> out of 5">
                                        <?php
                                            $stars = intval(!empty($slide['stars']) ? $slide['stars'] : 5);
                                            for ($i = 0; $i < $stars; $i++) {
                                                echo '★';
                                            }
                                        ?>
                                    </div>
                                    <h3><?php echo esc_html($slide['name']); ?></h3>
                                    <p class="svts-role"><?php echo esc_html($slide['role']); ?></p>
                                    <p class="svts-desc"><?php echo esc_html($slide['desc']); ?></p>
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
}