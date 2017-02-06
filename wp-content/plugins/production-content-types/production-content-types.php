<?php
/*
Plugin Name: NDLA: Production content types
*/
function register_post_types() {
    $args = array(
        'public' => true,
        'label' => 'Emnebeskrivelser',
    );

    register_post_type('emnebeskrivelse', $args);
}

add_action('init', 'register_post_types');