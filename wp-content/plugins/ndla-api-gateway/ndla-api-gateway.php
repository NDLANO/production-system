<?php

/*
Plugin Name: NDLA: API gateway
*/

require_once __DIR__ . '/includes/ImageAPIGateway.php';






/*
 * AJAX endpoints
 */

add_action( 'wp_ajax_ndla_image_search', 'ndla_image_search_callback' );

function ndla_image_search_callback() {
	$api      = new NDLA\ImageAPIGateway();
	$response = $api->find( $_GET['query'], $_GET['page'], $_GET['pageSize'], true );

	if ( $response != null ) {
		status_header( 200 );
		echo $response;
	} else {
		status_header( 500 );
	}

	wp_die(); // this is required to terminate immediately and return a proper response
}


add_action( 'wp_ajax_ndla_image_details', 'ndla_image_details_callback' );

function ndla_image_details_callback() {
	$api      = new NDLA\ImageAPIGateway();
	$response = $api->getDetails( $_GET['imageid'], true );

	if ( $response != null ) {
		status_header( 200 );
		echo $response;
	} else {
		status_header( 500 );
	}

	wp_die(); // this is required to terminate immediately and return a proper response
}