<?php

use Fuel\Core\Request;

/**
 * The Api Controller.
 * This controller is the base api controller and is to be extended by all api functions
 *
 * @package  app
 * @extends  Controller_Rest
 */

class Controller_Api extends Controller {

// 	// access key
// 	protected $access_key = null;

// 	// appid
// 	protected $appid = null;

	// no access error
	protected $no_access = array('data' => null, 'meta' => array('error' => 'You do not have access to this method', 'status' => 0, 'error_code' => Code::ERROR_ACCESS_DENIED));

	// limit
	protected static $limit = 20;

	// default format
	protected $format = 'json';

	// contains a list of method properties such as limit, log and level
	protected $methods = array();

	// status code to return in case a not defined action is called
	protected $no_method_status = 405;

	// status code to return in case the called action doesn't return data
	protected $no_data_status = 204;

	// user arra
// 	protected $user = null;

// 	protected $userObj = null;

	// rest format
	protected $rest_format = null;

	protected $access_allowed = false;

	protected $user_login = null;

	// List all supported methods
	protected $_supported_formats = array(
		'xml' => 'application/xml',
		'rawxml' => 'application/xml',
		'json' => 'application/json',
		'jsonp'=> 'text/javascript',
		'serialized' => 'application/vnd.php.serialized',
		'php' => 'text/plain',
		'html' => 'text/html',
		'csv' => 'application/csv'
	);

// 	protected $_test = false;

	public function before() {
		$auth = Auth::instance('Shopsuey_Stateless');
		// Build the name of the resource to check if the user is allowed to use it
		$controller = strtolower(Request::active()->controller);
		$resource_name = implode('/', array_slice(explode('_', $controller), 1));

		// Check if there's an explicit action
		$action = Request::active()->action;
		if ($action == 'index') {
			switch(Input::method()) {
				case 'POST':
					$action = 'create';
					break;
				case 'GET':
					$action = 'read';
					break;
				case 'PUT':
					$action = 'update';
					break;
				case 'DELETE':
					$action = 'delete';
					break;
			}
		}

        if (function_exists('newrelic_name_transaction')) {
            newrelic_name_transaction("$resource_name/$action");
        }

		error_log("$resource_name => $action");

		$this->access_allowed = $auth->has_access("{$resource_name}.{$action}");

                // The auth login driver always return an object, even if the user is guest
		$this->user_login = $auth->get_user_login_object();

		parent::before();
	}
	
	public function router($method, $params) {
		// This router does essentially the same as the original, but it adds routing
		// of actions according to their HTTP method and also checks for allowed access
		if ($this->access_allowed) {
			error_log("API Access granted!!");

			if ($method == 'index') {
				switch(Input::method()) {
					case 'POST':
						$action = 'post';
						break;
					case 'GET':
						$action = 'get';
						break;
					case 'PUT':
						$action = 'put';
						break;
					case 'DELETE':
						$action = 'delete';
						break;
					default:
						$action = $method;
				}
			} else {
				$action = $method;
			}
			$action_name = 'action_' . $action;

			$rc = new ReflectionClass($this);
			if ($rc->hasMethod($action_name)) {
				$rc->getMethod($action_name)->invokeArgs($this, $params);
			} else {
				$this->_error_response(Code::ERROR_INVALID_PROCEDURE);
			}
			return;
		}
        
        if (!$this->user_login->user->id) {
            $this->_error_response(Code::ERROR_INVALID_LOGIN_HASH);
        } else {
            $this->_error_response(Code::ERROR_INVALID_PROCEDURE);
        }
	}

	public function after($response) {
		// Log the request
		$data = array(
			'appid' => $this->user_login->application_id ?: 'guest user',
			'access_key' => $this->user_login->login_hash ?: 'guest user',
			'created' => date('Y-m-d H:i:s'),
			'resource' => URI::current());

		DB::insert('apilog')->set($data)->execute();

		if (!$response instanceof \Response) {
			$response = $this->response;
		}
		return parent::after($response);
	} // ---> after()


	/**
	 * This method returns the named parameter requested, or all of them
	 * if no parameter is given.
	 *
	 * @param   string  $param    The name of the parameter
	 * @param   mixed   $default  Default value
	 * @return  mixed
	 */
// 	public function param($param, $default = null) {
// 		return $this->request->param($param, $default);
// 	} // ---> param()

	/**
	 * This method returns all of the named parameters.
	 *
	 * @return  array
	 */
// 	public function params() {
// 		return $this->request->params();
// 	} // ---> params()


	protected function is_format($fmt) {
		if (isset($this->_supported_formats[$fmt])) { return true; }
		else { return false; }
	} // ---> is_format()


	protected function response($data = array(), $http_code = 200, $override_format_class = false) {
		// set the format
		$this->format = Input::param('format', $this->param('format', $this->format));

		// set status
		if ((is_array($data) and empty($data)) or ($data == '')) {
			$this->response->status = $this->no_data_status;
			return;
		}
		$this->response->status = $http_code;

		// check format -> default to json
		if (!$this->is_format($this->format)) { $this->format = 'json'; }

		// If the format method exists, call and return the output in that format
		if (method_exists('Format', 'to_'.$this->format)) {

			// Set the correct format header
			$this->response->set_header('Cache-Control', 'no-cache, must-revalidate');
			$this->response->set_header('Expires', 'Mon, 26 Jul 1997 05:00:00 GMT');
			$this->response->set_header('Content-Type', $this->_supported_formats[$this->format]);
            if ($override_format_class && $this->format == 'json') {
                $this->response->body(json_encode($data));
            } else {
                $this->response->body(\Format::forge($data)->{'to_'.$this->format}());
            }
		}

		// Format not supported, output directly
		else {
			$this->response->body((string) $data);
		}
	} // ---> response()


	protected function _detect_lang() {
		if (!$lang = \Input::server('HTTP_ACCEPT_LANGUAGE')) {
			return null;
		}

		// They might have sent a few, make it an array
		if (strpos($lang, ',') !== false) {
			$langs = explode(',', $lang);

			$return_langs = array();

			foreach ($langs as $lang) {
				// Remove weight and strip space
				list($lang) = explode(';', $lang);
				$return_langs[] = trim($lang);
			}

			return $return_langs;
		}

		// Nope, just return the string
		return $lang;
	} // ---> _detect_lang()

	// Get a location's hours
	protected function _hours($parent_id, $type) {
		$hours = array();
		$results = DB::select('label', 'time_close', 'time_open')
		->from('hours')
		->where('parent_id', '=', $parent_id)
		->and_where('type', '=', $type)->and_where('status', '=', 1)
		->order_by('order', 'asc')
		->execute();

		return $results;
	} // ---> hours()

	// Get a query's pagination
	protected static function _pagination($count = 0, $page = 1, $limit = null){
            
            $limit = static::$limit;

            $pages = ceil($count/$limit);
            $page = ($page > $page) ? $pages : $page;
            $page = ($page < 1) ? 1 : $page;

            $next = $prev = $onext = $oprev = NULL;

            $offset = ($page * $limit) - $limit;
            if ($page < $pages) { $next = $page + 1; $onext = $offset + $limit; }
            if ($page > 1) { $prev = $page - 1; $oprev = $offset - $limit; }

            $page_data = array('current'=> $page, 'count' => $pages, 'next' => $next, 'prev'=> $prev);
            $offset_data = array('current'=> $offset, 'next' => $onext, 'prev' => $oprev);

            ksort($page_data);
            ksort($offset_data);

            $output = array(
                    'page'=>$page_data,
                    'offset' => $offset_data,
                    'limit' => $limit,
                    'records' => $count);

            ksort($output);

            return $output;
            
	} // ---> pagination()

	protected function _locations($ids) {

		$loc_table = 'suey_locations';
		$mall_table = 'suey_malls';

		$ids = implode(', ', $ids);
		$sql =  "SELECT loc.id AS location_id, loc.name AS location_name, ";
		$sql .= "mall.id AS mall_id, mall.name AS mall_name ";
		$sql .= "FROM $loc_table AS loc ";
		$sql .= "JOIN $mall_table AS mall ";
		$sql .= "ON mall.id = loc.mall_id ";
		$sql .= "WHERE loc.id IN ($ids)";

		$qry = DB::query($sql)->execute();

		return $qry;
	}

	protected function _malls($ids) {
		$mall_table = 'suey_malls';

		$ids = implode(', ', $ids);

		$sql = "SELECT id, name FROM $mall_table WHERE id IN ($ids)";
		$qry = DB::query($sql)->execute();

		return $qry;
	}

	protected function _error_response($code, $extra_fields = array()) {
		$data = array(
			'data' => null,
			'meta' => Helper_Api::build_error_meta($code, $extra_fields),
		);
		$this->response($data);
	}

    protected function _get_offers_for_locations($loc, $include_locations = false, $ignore_like_status = FALSE, $only_count = FALSE) {
        if (is_array($loc)) {
            $locations_ids = array_map(function($l) {
                return $l->id;
            }, $loc);
        } else {
            $locations_ids = array($loc->id);
        }

        if (empty($locations_ids)) {
            return array();
        }

		$current_date = date('Y-m-d H:i:s');
		$upcoming_date = date('Y-m-d H:i:s', strtotime(Controller_Api_Offer::UPCOMING_TIME));

		$offer_models = Model_Offer::query()
			->related('locations')
			->where('status', 1)
			->where('locations.id', 'in', $locations_ids)
		    ->and_where_open()
			->or_where('date_start', 'between', array($current_date, $upcoming_date))
			->or_where('date_start', '<=', $current_date)
			->and_where_close()
			->where('date_end', '>=', $current_date)
			->get();

        $count = 0;
		$offers = array();
		foreach($offer_models as $offer_model) {
            if ($ignore_like_status || $this->user_login->user->get_like_message_status('offer', $offer_model->id) != -1) {
                if ($only_count) {
                    $count++;
                } else {
                    array_push($offers, Helper_Api::offer_response($offer_model, $include_locations, false));
                }
            }
		}
		return $only_count ? $count : $offers;
	}

	protected function _get_events_for_locations($loc, $include_locations = false, $ignore_like_status = FALSE, $only_count = FALSE) {
        if (is_array($loc)) {
            $locations_ids = array_map(function($l) {
                return $l->id;
            }, $loc);
        } else {
            $locations_ids = array($loc->id);
        }

        if (empty($locations_ids)) {
            return array();
        }

		$current_date = date('Y-m-d');
		$upcoming_date = date('Y-m-d', strtotime(Controller_Api_Event::UPCOMING_TIME));

		$event_models = Model_Event::query()
            ->related('locations')
			->where('status', 1)
			->where('locations.id', 'in', $locations_ids)
			->and_where_open()
            ->where('date_start', '<=', $current_date)
            ->where('date_end', '>=', $current_date)
			->or_where('date_start', 'between', array($current_date, $upcoming_date))
			->and_where_close()
			->get();

        $count = 0;
		$events = array();
		foreach($event_models as $event_model) {
            if ($ignore_like_status || $this->user_login->user->get_like_message_status('event', $event_model->id) != -1) {
                if ($only_count) {
                    $count++;
                } else {
                    array_push($events, Helper_Api::event_response($event_model, $include_locations, false));
                }
            }
		}
        
        if ($only_count) {
            $special_events_count = Model_Specialevent::query()
                    ->related('locations')
                    ->where('status', 1)
                    ->and_where_open()
                    ->where('locations.id', 'in', $locations_ids)
                    ->or_where('main_location_id', 'in', $locations_ids)
                    ->and_where_close()
                    ->and_where_open()
                    ->where('date_start', '<=', $current_date)
                    ->where('date_end', '>=', $current_date)
                    ->or_where('date_start', 'between', array($current_date, $upcoming_date))
                    ->and_where_close()
                    ->count();
            
            $count += $special_events_count;
        }
        
		return $only_count ? $count : $events;
	}
        
        protected function _get_simple_relevance_expr($string, $table_prefix = NULL, $required_fields = NULL, $as_name = NULL) {
	    $fields_relevance = Model_Location::get_fields_relevance();
	    $table_prefix = is_null($table_prefix) ? '' : "`{$table_prefix}`.";
	    $string_parts = preg_split('/\s+/', $string);
	    // Fuel adds single quotes to each end of the string :(
	    $escaped_strings = array_map(function($s) { return trim(DB::escape($s), "'"); }, $string_parts);
	    $like_expressions = array();
	    foreach($fields_relevance as $field => $relevance) {
	        // Check if field is among the required fields
	        if (!is_null($required_fields) && !in_array($field, $required_fields)) {
	            continue;
	        }
	        foreach($escaped_strings as $escaped_string) {
	            $like_expressions[] = "({$table_prefix}`$field` LIKE \"%$escaped_string%\") * $relevance";
	        }
	    }
	    if (is_null($as_name)) {
	        $as_name = 'relevance';
	    }
	    $expr = implode(' + ', $like_expressions) . " AS `$as_name`";
	    return DB::expr($expr);
	}

	protected function _set_like_query($query, $string, $table_prefix = NULL) {
	    $table_prefix = is_null($table_prefix) ? '' : "{$table_prefix}.";
	    $search_fields = array('name','address','city','st','zip','email','web','description','tags');
	    $string_parts = preg_split('/\s+/', $string);
	    $query->and_where_open();
	    foreach ($search_fields as $field) {
	        foreach($string_parts as $string_part) {
	    	    $query->or_where($table_prefix . $field, 'like', "%$string_part%");
	        }
	    }
	    $query->and_where_close();
	}
}
