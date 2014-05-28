<?php

/**
 * Wrapper class for the Micello's API
 * @author lucas
 *
 */
namespace Micello;

class MicelloException extends \FuelException {}

class Api {

	private static $_api_key;

	const ERROR_MICELLO_API = 1;
	const ERROR_REQUEST_DECODING = 2;
	
	const ENTITY_INFO_FIELD_PHONE = 'phone';
	const ENTITY_INFO_FIELD_EMAIL = 'email';
	const ENTITY_INFO_FIELD_URL = 'url';
	const ENTITY_INFO_FIELD_DESC = 'description';
	
	final private function __construct() {}

	private static function _init()
	{
		\Config::load('micello', true);
		static::$_api_key = \Config::get('micello.api_key');
	}

	public static function get_nearby_communities($latitude, $longitude, $radius) {
		static::_init();
		$url = "search/community/lat/$latitude/lon/$longitude/radius/$radius";
		return self::_make_request($url);
	}
	
	public static function get_communities_by_keyword($keyword) {
		static::_init();
		$keyword = rawurlencode($keyword);
		$url = "search/community/keyword/$keyword";
		return self::_make_request($url);
	}
	
	public static function get_entities($community_id, $filter_merchants = TRUE) {
		static::_init();
		$url = "list/community/$community_id/entities";
		$response = self::_make_request($url);
		if ($filter_merchants) {
		    $filtered_results = array();
		    foreach ($response->results as $result) {
		        if (isset($result->eid) && isset($result->nm)) {
		            $filtered_results[] = $result;
		        }
		        // This is a temporary patch to support booths
		        // from convention centers
		        if (isset($result->a) && preg_match('/^booth/i', $result->a)) {
		            $result->nm = $result->a;
		            $result->eid = '';
		            $filtered_results[] = $result;
		        }
		    }
		    $response->results = $filtered_results;
		}
		return $response;
	}

	public static function get_entity_info($entity_id) {
	    static::_init();
	    $url = "list/entity/properties";
	    return self::_make_request($url, array('entity_id' => $entity_id));
	}
	
	public static function get_community_map($community_id) {
		static::_init();
		$url = "map/community/$community_id";
		return self::_make_request($url);
	}

	public static function get_community_by_id($community_id) {
		static::_init();
		$url = "details/community";
		return self::_make_request($url, array('id' => $community_id));
	}
	
	private static function _make_request($url, $params = NULL) {
		$url = \Config::get('micello.base_url') . "/$url?api_key=" . self::$_api_key;
		
		if (!is_null($params)) {
    		foreach($params as $key => $param) {
    		    $param = urlencode($param);
    		    $url .= "&$key=$param";
    		}
		}

		\Log::debug('Micello request: ' . $url);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
		curl_setopt($ch, CURLOPT_TIMEOUT, 120);
		
		$headers = array('Cache-Control: no-cache');
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			
		if (($raw_response = curl_exec($ch)) === false) {
			throw new MicelloException("Error while querying Micello's API (" . curl_error($ch) . " - " . curl_errno($ch) . ")", static::ERROR_MICELLO_API);
		}
		curl_close($ch);

		if (($response = json_decode($raw_response)) === NULL) {
			throw new MicelloException("Error decoding response from Micello's API", static::ERROR_REQUEST_DECODING);
		}
		return $response;
	}	
}
