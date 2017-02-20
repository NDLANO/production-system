<?php
/*
Plugin Name: NDLA: Images
Author: Mathias Lidal
Text Domain: ndla-images
*/

function ndla_images_load_plugin_textdomain() {
    load_plugin_textdomain( 'ndla-images', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}


add_action( 'plugins_loaded', 'ndla_images_load_plugin_textdomain' );

function ndla_thickbox_form() {
    ?>
    <div id="ndla-images-content" class="ndla-images hidden">
        <form id="ndla-image-form" class="search-form">
            <p><input class="search-term" id="q" type="text" value=""></p>
            <input id="submit-search" class="button" type="submit" value="<?php _e('Search', 'ndla-images'); ?>">
        </form>
        <div class="results-container">
            <div id="ndla-results-container" class="image-results"></div>
            <div class="ndla-media-sidebar">
            </div>
        </div>
        <div class="ndla-image-footer">
            <button type="button" class="button media-button button-primary button-large ndla-image-button-insert" disabled="disabled">Sett inn på side</button>
        </div>
    </div>
    <?php
}

add_filter('admin_footer', 'ndla_thickbox_form');


function ndla_images_enqueue_js()
{
    wp_enqueue_script( 'jquery-ui-dialog' ); // jquery and jquery-ui should be dependencies, didn't check though...
    wp_enqueue_style( 'wp-jquery-ui-dialog' );
    wp_enqueue_script('ndla-images-script', plugin_dir_url(__FILE__) . 'assets/js/ndla_images.js');
}

add_action('admin_enqueue_scripts', 'ndla_images_enqueue_js');

function ndla_images_enqueue_css() {
    wp_enqueue_style('ndla_images_styles', plugin_dir_url(__FILE__) . 'assets/css/ndla_images.css');
}

add_action('admin_enqueue_scripts', 'ndla_images_enqueue_css');

function ndla_get_media_dialog_button($id, $text, $classes = []) {
    $class = "button ";
    if (is_array($classes)) {
        $class .= join(" ", $classes);
    }

    $tag = '<a name="NDLA Image" id="ndla-images-open" href="#" class="' . $class . '">' . $text . '</a>';
    echo $tag;
}

    function ndla_media_buttons_context($context) {

        ndla_get_media_dialog_button($context, __('NDLA Images', 'ndla-images'));
}

add_filter('media_buttons', 'ndla_media_buttons_context', 15);

