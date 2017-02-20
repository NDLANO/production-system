<?php
/*
Plugin Name: NDLA: Production content types
*/
function ndla_register_post_types() {
    $args = array(
        'public' => true,
        'label' => 'Emnebeskrivelser',
        'supports' => 'title'
    );

    register_post_type('emnebeskrivelse', $args);
}

add_action('init', 'ndla_register_post_types');


function ndla_publish_emnebeskrivelse( $ID ) {
    $metabilde = get_field('metabilde', $ID);
    $metabeskrivelse = get_field('metabeskrivelse', $ID);


}

add_action('publish_emnebeskrivelse', 'ndla_publish_emnebeskrivelse');
