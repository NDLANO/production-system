<?php
/*
Plugin Name: NDLA: Images
*/


function ndla_thickbox_form() {
add_thickbox(); ?>
<div id="my-content-id" style="display:none;">
    <div class="ndla-images">
        <form id="ndla-image-form">
            <p><input class="search-term" id="q" type="text" value=""></p>
            <input id="submit-search" class="button" type="submit" value="<?= _e('Search', 'pixabay_images'); ?>">
        </form>
        <div id="ndla-results" class="image-results"></div>
    </div>
</div>
    <?php
}

add_filter('admin_footer', 'ndla_thickbox_form');


function ndla_images_enqueue_js()
{
    wp_enqueue_script('my_custom_script', plugin_dir_url(__FILE__) . 'assets/js/ndla_images.js');
}

add_action('admin_enqueue_scripts', 'ndla_images_enqueue_js');

function ndla_images_enqueue_css() {
    wp_enqueue_style('ndla_images_styles', plugin_dir_url(__FILE__) . 'assets/css/ndla_images.css');
}

add_action('admin_enqueue_scripts', 'ndla_images_enqueue_css');

function ndla_get_media_dialog_button($id = "", $classes = [], $text = "NDLA Bilder") {
    $class = "thickbox button ";
    if (is_array($classes)) {
        $class .= join(" ", $classes);
    }

    return '<a name="NDLA Image" id="' . $id . '" href="#TB_inline?width=780&height=650&inlineId=my-content-id" class="' . $class . '">' . $text . '</a>';
}

function ndla_media_buttons_context() {

    return ndla_get_media_dialog_button();
}

add_filter('media_buttons_context', 'ndla_media_buttons_context');

function ndla_images_select_image_ajax() {

}

add_action('wp_ajax_ndla_images_select_image', 'ndla_images_select_image_ajax');