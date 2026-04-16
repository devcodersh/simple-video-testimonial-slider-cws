<?php

function vts_settings_page() {

    if (isset($_POST['vts_save'])) {

        update_option('vts_nav_color', sanitize_text_field($_POST['nav_color']));
        update_option('vts_play_color', sanitize_text_field($_POST['play_color']));

        $slides = [];
        if (!empty($_POST['video'])) {
            foreach ($_POST['video'] as $key => $video) {
                $slides[] = [
                    'video' => esc_url_raw($video),
                    'name' => sanitize_text_field($_POST['name'][$key]),
                    'role' => sanitize_text_field($_POST['role'][$key]),
                    'desc' => sanitize_textarea_field($_POST['desc'][$key]),
                ];
            }
        }

        update_option('vts_slides', $slides);
    }

    $slides = get_option('vts_slides', []);
    ?>

    <div class="wrap">
        <h1>Video Slider Settings</h1>

        <form method="post">

            <h2>Colors</h2>
            <input type="color" name="nav_color" value="<?php echo get_option('vts_nav_color', '#89AD29'); ?>">
            <input type="color" name="play_color" value="<?php echo get_option('vts_play_color', '#89AD29'); ?>">

            <h2>Slides</h2>

            <div id="slides-container">
                <?php foreach ($slides as $i => $slide): ?>
                    <div class="slide-item">
                        <input type="text" name="video[]" value="<?php echo $slide['video']; ?>" placeholder="Video URL">
                        <input type="text" name="name[]" value="<?php echo $slide['name']; ?>" placeholder="Name">
                        <input type="text" name="role[]" value="<?php echo $slide['role']; ?>" placeholder="Role">
                        <textarea name="desc[]"><?php echo $slide['desc']; ?></textarea>
                        <hr>
                    </div>
                <?php endforeach; ?>
            </div>

            <button type="button" onclick="addSlide()">Add Slide</button>

            <br><br>
            <input type="submit" name="vts_save" value="Save Settings" class="button button-primary">
        </form>
    </div>

    <script>
    function addSlide() {
        let html = `
        <div class="slide-item">
            <input type="text" name="video[]" placeholder="Video URL">
            <input type="text" name="name[]" placeholder="Name">
            <input type="text" name="role[]" placeholder="Role">
            <textarea name="desc[]" placeholder="Description"></textarea>
            <hr>
        </div>`;
        document.getElementById('slides-container').insertAdjacentHTML('beforeend', html);
    }
    </script>

    <?php
}