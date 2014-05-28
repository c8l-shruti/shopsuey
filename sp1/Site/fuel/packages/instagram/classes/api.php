<?php

/**
 * Class to allow interaction with Instagram's API
*
*/
namespace Instagram;

class Exception extends \FuelException {}

class Api {

    const METHOD_GET  = 'GET';
    const METHOD_POST = 'POST';
    
    const ERROR_API = 1;
    const ERROR_REQUEST_DECODING = 2;
    const ERROR_INCORRECT_REQUEST = 3;
    const ERROR_ACCESS_TOKEN = 4;
    const ERROR_AUTH = 5;
    
	private static $_client_id;
	private static $_client_secret;
	private static $_instagram_api_url;
	private static $_callback_url;

	final private function __construct() {}

	private static function _init()
	{
		\Config::load('instagram', true);

		static::$_client_id         = \Config::get('instagram.client_id', '');
		static::$_client_secret     = \Config::get('instagram.client_secret', '');
		static::$_instagram_api_url = \Config::get('instagram.instagram_api_url', '');
		static::$_callback_url      = \Uri::create(\Config::get('instagram.callback_url', ''));
	}

	public static function get_auth_url($redirect_url) {
	    static::_init();
	    
	    $params = array(
            'client_id'     => static::$_client_id,
            'redirect_uri'  => static::$_callback_url,
            'response_type' => 'code',
            'state'         => $redirect_url,
        );

	    return static::$_instagram_api_url . 'oauth/authorize/?' . static::build_url_query($params);
	}
	
	public static function get_logout_url() {
	    return "https://instagram.com/accounts/logout/";
	}
	
	public static function get_access_token($code) {
	    static::_init();
	    
	    $params = array(
            'client_id'     => static::$_client_id,
            'client_secret' => static::$_client_secret,
            'grant_type'    => 'authorization_code',
            'redirect_uri'  => static::$_callback_url,
            'code'          => $code,
        );
	    
	    $oauth_token = static::make_request('oauth/access_token', static::METHOD_POST, $params);

	    if (! isset($oauth_token->access_token) || empty($oauth_token->access_token)) {
	        throw new Exception("Error while fetching access token from Instagram's API", static::ERROR_ACCESS_TOKEN);
	    }
	    
	    $response = new \stdClass();
	    $response->access_token = $oauth_token->access_token;
	    $response->user_id      = $oauth_token->user->id;
	    $response->username     = $oauth_token->user->username;
	    
	    return $response;
	}
	
	public static function get_user_feed($access_token) {
	    static::_init();
	     
	    $params = array(
	    	'access_token' => $access_token,
	    );
	     
	    $response = static::make_request('v1/users/self/feed', static::METHOD_GET, $params);
	    
	    if ($response->meta->code != '200') {
	    	throw new Exception("Invalid token when accessing to Instagram's API", static::ERROR_AUTH);
	    }
	    	  
	    return $response->data;
	}

	public static function get_user_recent_media($access_token, $user_id) {
		static::_init();
	
		$params = array(
			'access_token' => $access_token,
		);
	
		$response = static::make_request("v1/users/$user_id/media/recent", static::METHOD_GET, $params);
		 
		if ($response->meta->code != '200') {
			throw new Exception("Invalid token when accessing to Instagram's API", static::ERROR_AUTH);
		}
	
		return $response->data;
	}
	
	private static function make_request($endpoint, $method, $params) {
	    $url = static::$_instagram_api_url . $endpoint;
	    
	    if ($method == static::METHOD_GET) {
	        $url .= '/?' . static::build_url_query($params);
	    }
	    
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
	    curl_setopt($ch, CURLOPT_TIMEOUT, 120);

	    if ($method == static::METHOD_POST) {
	        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
	    }
	    
	    $headers = array('Cache-Control: no-cache');
	    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	    if (($raw_response = curl_exec($ch)) === false) {
	    	throw new Exception("Error while querying Instagram's API (" . curl_error($ch) . " - " . curl_errno($ch) . ")", static::ERROR_API);
	    }
	    curl_close($ch);
	    
	    if (($response = json_decode($raw_response)) === NULL) {
	    	throw new Exception("Error decoding response from Instagram's API", static::ERROR_REQUEST_DECODING);
	    }
	    
	    return $response;
	}
	
	private static function build_url_query($params) {
	    $encoded_params = array();
	    foreach ($params as $key => $value) {
	        $encoded_params[] = "$key=" . urlencode($value);
	    }
	    return implode('&', $encoded_params);
	}
}
