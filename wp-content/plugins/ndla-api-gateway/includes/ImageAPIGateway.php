<?php

namespace NDLA;


require_once __DIR__ . '/../vendor/autoload.php';


class ImageAPIGateway {

	private $baseUri;
	private $guzzle;

	function __construct() {
	    $optApiUrl = get_option('ndla_api_url');
	    if(empty($optApiUrl)) {
	        $optApiUrl = 'http://staging.api.ndla.no/';
        }
		$this->baseUri = $optApiUrl.'image-api/v1/images/';
		$this->guzzle  = new \GuzzleHttp\Client( [ 'base_uri' => $this->baseUri ] );
	}


	/**
	 * @param string $responseBody JSON string
	 * @param bool $returnAsJson
	 *
	 * @return array|string
	 */
	private function parseResponse( $responseBody, $returnAsJson = false ) {
		return ( $returnAsJson ) ? (string) $responseBody : json_decode( $responseBody, true );
	}


	/**
	 * Get full details of a single image.
	 * @param int $imageID
	 * @param bool $returnAsJson
	 *
	 * @return array
	 */
	public function getDetails($imageID, $returnAsJson = false) {
		// Clean up params
		$imageID = (int) $imageID;

		$response = $this->guzzle->request( 'GET', $this->baseUri . $imageID );

		if ( $response->getStatusCode() == 200 || $imageID > 1 ) {
			// Successful request
			return $this->parseResponse($response->getBody(), $returnAsJson);
		} else {
			// Unsuccessful request
			return null;
		}
	}


	/**
	 * Find image(s) based on search query (optional).
	 *
	 * @param string $query
	 * @param int $page
	 * @param int $pageSize
	 * @param bool $returnAsJson
	 *
	 * @return array
	 */
	public function find( $query, $page = 1, $pageSize = 10, $returnAsJson = false ) {
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
			return $this->parseResponse($response->getBody(), $returnAsJson);
		} else {
			// Unsuccessful request
			return null;
		}
	}
}
