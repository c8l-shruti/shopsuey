<?php

/**
 * Wrapper class for the Foursquare's API
 * @author lucas
 *
 */
namespace Foursquare;

class FoursquareException extends \FuelException {}

class Api {

	private static $_client_id;
	private static $_client_secret;
	
	const ERROR_FOURSQUARE_API = 1;
	const ERROR_REQUEST_DECODING = 2;
	const ERROR_INCORRECT_REQUEST = 3;
	
	final private function __construct() {}

	private static function _init()
	{
		\Config::load('foursquare', true);
		static::$_client_id = \Config::get('foursquare.client_id');
		static::$_client_secret = \Config::get('foursquare.client_secret');
	}

	public static function get_venues_by_term($term, $location) {
	    static::_init();
	    $service = "venues/search";
	    $params = array('query' => $term);
	    if (!empty($location)) {
	        $params['near'] = $location;
	    } else {
	        $params['intent'] = 'global';
	    }
	    return self::_make_request($service, $params);
	}

	public static function search_exact_venue($latitude, $longitude, $term) {
	    static::_init();
	    $service = "venues/search";
	    $params = array('query' => $term, 'll' => "$latitude,$longitude", 'intent' => 'match');
	    return self::_make_request($service, $params);
	}
	
	public static function get_venue($venueId) {
	    static::_init();
	    $service = "venues/$venueId";
	    $params = array();
	    return self::_make_request($service, $params);
	}

	public static function get_venue_hours($venueId) {
	    static::_init();
	    $service = "venues/$venueId/hours";
	    $params = array();
	    return self::_make_request($service, $params);
	}
	
	private static function _make_request($endpoint, $params) {
		$url = \Config::get('foursquare.base_url') . "$endpoint?client_id=" . self::$_client_id . "&client_secret=" . self::$_client_secret;
		$url .= "&v=" . date('Ymd');

// 		if (!isset($params['near']) && !isset($params['intent'])) {
// 		    $url .= "&intent=global";
// 		}

		foreach($params as $key => $param) {
		    $param = urlencode($param);
		    $url .= "&$key=$param";
		}

		\Log::debug('Foursquare request: ' . $url);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
		curl_setopt($ch, CURLOPT_TIMEOUT, 120);
		
		$headers = array('Cache-Control: no-cache');
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			
		if (($raw_response = curl_exec($ch)) === false) {
			throw new FoursquareException("Error while querying Foursquare's API (" . curl_error($ch) . " - " . curl_errno($ch) . ")", static::ERROR_FOURSQUARE_API);
		}
		curl_close($ch);

		if (($response = json_decode($raw_response)) === NULL) {
			throw new FoursquareException("Error decoding response from Foursquare's API", static::ERROR_REQUEST_DECODING);
		}
		
		if ($response->meta->code != '200') {
		    throw new FoursquareException("Error on the request to Foursquare's API", static::ERROR_INCORRECT_REQUEST);
		}

		return $response->response;
	}	
}
