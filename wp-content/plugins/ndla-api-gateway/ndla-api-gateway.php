<?php

/*
Plugin Name: NDLA: API gateway
*/

require_once __DIR__ . '/includes/ImageAPIGateway.php';


/*
 * Global functions for use in theme/other plugins
 */
if ( ! function_exists( 'ndla_image_search' ) ) {
	function ndla_image_search( $query, $page = 1, $pageSize = 10 ) {
		$api = new NDLA\ImageAPIGateway();

		return $api->find( $query, $page, $pageSize, false );
	}
}

if ( ! function_exists( 'ndla_image_details' ) ) {
	function ndla_image_details( $imageID ) {
		$api = new NDLA\ImageAPIGateway();

		return $api->getDetails( $imageID, false );
	}
}


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