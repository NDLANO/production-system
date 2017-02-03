<?php

/*
Plugin Name: NDLA: API gateway
*/

//require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/includes/ImageAPIGateway.php';


/* Add Shortcode
function ndla_img( $atts ) {

	// Attributes
	$atts = shortcode_atts(
		array(
			'query'     => '',
			'page-size' => '',
			'page'      => '',
			'details'   => ''
		),
		$atts,
		'ndlaimg'
	);

	$apiurl = 'http://staging.api.ndla.no/image-api/v1/images/?page-size=5';

	if(!empty($atts['query'])) {
		$apiurl = $apiurl.'&query='.urlencode($atts['query']);
	}

	$client = new \GuzzleHttp\Client();
	$response = $client->request('GET', $apiurl);
	$responseBody = $response->getBody();

	$result = json_decode($responseBody, true);

	$return = '';

	foreach ($result['results'] as $img) {
		$return .= '<img src="'.$img['previewUrl'].'">';
	}

	return $return;

}

add_shortcode( 'ndlaimg', 'ndla_img' );*/


add_action( 'admin_footer', 'ndla_image_search_javascript' ); // Write our JS below here

function ndla_image_search_javascript() { ?>
    <script type="text/javascript">
//        jQuery(document).ready(function ($) {
//
//            var data = {
//                'action': 'ndla_image_search',
//                'query': 'bil',
//                'pageSize': 1
//            };
//
//            jQuery.get(ajaxurl, data, function (response) {
//                alert('Got this from the server: ' + response);
//            });
//        });
    </script> <?php
}

add_action( 'wp_ajax_ndla_image_search', 'ndla_image_search_callback' );

function ndla_image_search_callback() {
	//global $wpdb; // this is how you get access to the database

	$api = new NDLA\ImageAPIGateway();

	echo $api->find( $_POST['query'], $_POST['page'], $_POST['pageSize'] );

	wp_die(); // this is required to terminate immediately and return a proper response
}