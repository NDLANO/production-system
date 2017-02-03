<?php

namespace NDLA;


require_once __DIR__ . '/../vendor/autoload.php';
require_once 'Image.php';


class ImageAPIGateway {

	private $baseUri;
	private $guzzle;

	function __construct() {
		$this->baseUri = 'http://staging.api.ndla.no/image-api/v1/images/';
		$this->guzzle  = new \GuzzleHttp\Client( [ 'base_uri' => $this->baseUri ] );
	}


	/**
	 * @param $responseBody string JSON string
	 *
	 * @return array|string
	 */
	private function parseResponse( $responseBody, $returnAsJson = false ) {
		return ( $returnAsJson ) ? (string) $responseBody : json_decode( $responseBody, true );
	}

	/**
	 *
	 *
	 * @return array

	public function get() {
		$response = $this->guzzle->request( 'GET', $this->baseUri );

		if ( $response->getStatusCode() == 200 ) {
			// Successful request
			return $this->parseResponse($response->getBody());
		} else {
			// Unsuccessful request
			return null;
		}
	}*/

	/**
	 * Get full details of a single image.
	 * @param int $imageID
	 *
	 * @return array
	 */
	public function getDetails($imageID) {
		// Clean up params
		$imageID = (int) $imageID;

		$response = $this->guzzle->request( 'GET', $this->baseUri . $imageID );

		if ( $response->getStatusCode() == 200 ) {
			// Successful request
			return $this->parseResponse($response->getBody());
		} else {
			// Unsuccessful request
			return null;
		}
	}


	/**
	 * Find image(s) based on search query (optional).
	 *
	 * @param $query
	 * @param int $page
	 * @param int $pageSize
	 * @param bool $details
	 * @param bool $returnAsJson
	 *
	 * @return array
	 */
	public function find( $query, $page = 1, $pageSize = 10, $details = false, $returnAsJson = false ) {
		// Clean up params
		$query    = trim( $query );
		$page     = (int) $page;
		$pageSize = (int) $pageSize;

		// Handling invalid values
		if ( $page < 1 ) {
			$page = 1;
		}
		if ( $pageSize < 1 ) {
			$pageSize = 1;
		}

		// Request parameters
		$requestParams = [];
		if ( ! empty( $query ) ) {
			$requestParams['query'] = $query;
		}
		$requestParams['page']     = $page;
		$requestParams['page-size'] = $pageSize;


		$response = $this->guzzle->request( 'GET', $this->baseUri, [ 'query' => $requestParams ] );

		if ( $response->getStatusCode() == 200 ) {
			// Successful request

			$results = $this->parseResponse($response->getBody(), true);

			if($details) {
				// Refaktorering på gang...
				for($i = 0; $i > count($results['results']); $i++) {
					$imgDetails = $this->getDetails((int) $results['results'][$i]['id']);
					$results = array_merge($results['results'][$i], $imgDetails);
				}
			}

			return $results;
		} else {
			// Unsuccessful request
			return null;
		}
	}

	/**
	 * Alias of find(...$returnAsJson = true)
	 *
	 * @param $query
	 * @param int $page
	 * @param int $pageSize
	 * @param bool $details
	 *
	 * @return array
	 */
	public function findJson( $query, $page = 1, $pageSize = 10, $details = false) {
		return $this->find( $query, $page, $pageSize, $details, true );
	}
}


//$api = new ImageAPIGateway();
//echo '<pre>';
//print_r( $api->find( 'bil', 1, 1, false, false) );
//print_r( $api->getDetails(32));
//echo '</pre>';
