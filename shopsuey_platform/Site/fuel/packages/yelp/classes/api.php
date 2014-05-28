<?php

/**
 * Wrapper class for the Foursquare's API
 * @author lucas
 *
 */
namespace Yelp;

class YelpException extends \FuelException {}

class Api {

	private static $_consumer_key;
	private static $_consumer_secret;
	private static $_token;
	private static $_token_secret;
	
	const ERROR_YELP_API = 1;
	const ERROR_REQUEST_DECODING = 2;
	
	final private function __construct() {}

	private static function _init()
	{
		\Config::load('yelp', true);
		static::$_consumer_key = \Config::get('yelp.consumer_key');
		static::$_consumer_secret = \Config::get('yelp.consumer_secret');
		static::$_token = \Config::get('yelp.token');
		static::$_token_secret = \Config::get('yelp.token_secret');
	}

	public static function search_businesses_by_term($term, $location) {
	    static::_init();
	    $service = "search";
	    $params = array('term' => $term);
	    if (!empty($location)) {
	        $params['location'] = $location;
	    }
	    return self::_make_request($service, $params);
	}

	public static function search_businesses_by_term_and_location($term, $latitude, $longitude) {
	    static::_init();
	    $service = "search";
	    $params = array(
            'term' => $term,
            'll' => "$latitude,$longitude",
            'radius_filter' => 500,
        );
	    return self::_make_request($service, $params);
	}
	
	private static function _make_request($endpoint, $params) {
		$url = \Config::get('yelp.base_url') . "$endpoint?";

		$params_array = array();
		foreach($params as $key => $param) {
		    $param = urlencode($param);
		    $params_array[] = "$key=$param"; 
		}

		$url .= implode('&', $params_array);
		
		// Sign the request
		$signed_url = self::_get_signed_url($url);
		
		\Log::debug('Yelp request: ' . $signed_url);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $signed_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
		curl_setopt($ch, CURLOPT_TIMEOUT, 120);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		
		if (($raw_response = curl_exec($ch)) === false) {
			throw new YelpException("Error while querying Yelp's API (" . curl_error($ch) . " - " . curl_errno($ch) . ")", static::ERROR_YELP_API);
		}
		curl_close($ch);

		if (($response = json_decode($raw_response)) === NULL) {
			throw new YelpException("Error decoding response from Yelp's API", static::ERROR_REQUEST_DECODING);
		}
		
		return $response;
	}
	
	private static function _get_signed_url($url) {
	    // Token object built using the OAuth library
	    $token = new \OAuthToken(self::$_token, self::$_token_secret);
	    
	    // Consumer object built using the OAuth library
	    $consumer = new \OAuthConsumer(self::$_consumer_key, self::$_consumer_secret);
	    
	    // Yelp uses HMAC SHA1 encoding
	    $signature_method = new \OAuthSignatureMethod_HMAC_SHA1();
	    
	    // Build OAuth Request using the OAuth PHP library. Uses the consumer and token object created above.
	    $oauthrequest = \OAuthRequest::from_consumer_and_token($consumer, $token, 'GET', $url);
	    
	    // Sign the request
	    $oauthrequest->sign_request($signature_method, $consumer, $token);
	    
	    // Get the signed URL
	    return $oauthrequest->to_url();
	}
}
