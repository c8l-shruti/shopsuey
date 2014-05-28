<?php

/**
 * The Api Controller.
 * This controllers the CRUD proceedures for locations
 *
 * @package  app
 * @extends  Controller_Rest
 */

class Controller_Api_Location extends Controller_Api {

	// Numbers of nearest locations to return when the radius is not set
	const NEAREST_LOCATIONS_LIMIT = 10;
	
	// Default radius for searchs (in miles)
	const DEFAULT_RADIUS = 5;

	const MICELLO_TYPE_MALL     = 'Shopping Mall';
	const MICELLO_TYPE_MERCHANT = 'Retail';
	
	/**
	 * Get a single location
	 */
	public function action_get() {
	    $id = $this->param('id', 0);
	    $location = Model_Location::query()->where('status', 1)->where('id', $id)->get_one();
	
	    if ($location) {

                $include_micello_info = (bool)Input::get('include_micello_info', false);
        
                $locationResponse = Helper_Api::location_response($location, array(), $include_micello_info, true);
                $locationResponse->use_instagram = (int) $locationResponse->use_instagram;
                
	        $this->response(array(
                    'data' => array('location' => $locationResponse),
                    'meta' => array('error' => null, 'status' => 1),
	        ));
	    } else {
	        $this->_error_response(Code::ERROR_INVALID_LOCATION_ID);
	    }
	}
	
	/**
	 * Get 25 locations with pagination meta
	 */
	public function action_list() {
		$page   = $this->param('page', 1);
		$string = $this->param('string', Input::param('string', ''));

		$order_by = Input::param('order_by', 'name');
		if (!in_array($order_by, array('name', 'relevance', 'simple_relevance')) || empty($string)) {
			$order_by = 'name';
		}
		
		$l1 = DB::table_prefix('l1');
		
		$select_columns = array('l1.*', array('l2.name', 'mall_name'), array('l2.city', 'mall_city'));
		if (! empty($string) && $order_by == 'relevance') {
			$select_columns[] = DB::expr("MATCH ($l1.name) AGAINST (" . DB::escape($string) . ") AS name_relevance");
			$select_columns[] = DB::expr("MATCH ($l1.name, $l1.address, $l1.city, $l1.st, $l1.zip, $l1.email, $l1.web, $l1.description, $l1.tags) AGAINST (" . DB::escape($string) . ") AS all_relevance");
		}

		if (! empty($string) && $order_by == 'simple_relevance') {
		    $select_columns[] = $this->_get_simple_relevance_expr($string, $l1);
		}
		
		$query = DB::select_array($select_columns)->from(array('locations', 'l1'))->where('l1.status', '>', 0);
        $query->join(array('locations', 'l2'), 'LEFT')->on('l1.mall_id', '=', 'l2.id');;
        
        if (Input::param('active_only', '0')) {
            $query->where('l1.status', '1');
        }

		if (! empty($string)) {
		    if ($order_by == 'relevance') {
		        $query->where(DB::expr("MATCH ($l1.name, $l1.address, $l1.city, $l1.st, $l1.zip, $l1.email, $l1.web, $l1.description, $l1.tags)"), ' AGAINST ', DB::expr("(" . DB::escape($string) . ")"));
		    } else {
		        $this->_set_like_query($query, $string, 'l1');
		        /*
                $search_fields = array('l1.name','l1.address','l1.city','l1.st','l1.zip','l1.email','l1.web','l1.description','l1.tags');
                $query->and_where_open();
                foreach ($search_fields as $field) {
                    $query->or_where($field, 'like', "%$string%");
                }
                $query->and_where_close();
                */
		    }
		}
		
		$ids = $this->param('ids', Input::param('ids', array()));
		
		if (count($ids) > 0) {
		    $query->where('l1.id', 'in', $ids);
		}

		$similar_to = Input::param('similar_to');
		if (!empty($similar_to)) {
		    $location = Model_Location::find($similar_to);
		    if ($location) {
		        $query->where('l1.name', $location->name);
		        $query->where('l1.type', $location->type);
		        $query->where('l1.country_id', $location->country_id);
		    }
		}

		if (Input::param('compact', '0') && !$this->user_login->user->is_admin()) {
    		$assigned_companies = $this->user_login->user->get_assigned_companies(TRUE, TRUE, TRUE);
    		if (count($assigned_companies) > 0) {
    		    $query->where('l1.id', 'in', array_keys($assigned_companies));
    		} else {
    		    // Make sure the query returns an empty set
    		    $query->where('l1.id', NULL);
    		}
		}
		
		$pagination = Input::param('pagination', '1');
		
		$meta = array(
				'status'     => 1,
				'error'      => NULL,
		);

		if ($pagination || $this->user_login->user->is_regular_user()) {
			$query->limit($this->limit)->offset(($page - 1) * $this->limit);
		}
		
		if ($order_by == 'relevance') {
		    $query->order_by(DB::expr("name_relevance + all_relevance"), 'desc');
		} elseif ($order_by == 'simple_relevance') {
		    $query->order_by(DB::expr('`relevance`'), 'desc')->order_by('l1.name', 'asc');
		} else {
		    $query->order_by($order_by, 'asc');
		}
		$locations = $query
		    ->as_assoc()
		    ->execute();
		
		if (Input::param('compact', '0')) {
		    $data = $this->_compact_response($locations);
		} else {
		    $count = DB::count_last_query();
		    $meta['pagination'] = $this->_pagination($count, $page);
		    // Set the output
		    $data = array('data' => array('locations' => $locations), 'meta' => $meta);
		}
		$this->response($data);
	}

	/**
	 * Service to get the locations to display on an outdoor map
	 */
	public function action_search() {
		if (Input::method() != 'GET') { $this->response($this->no_access); return; }
		
		$params = Input::get();
		$current_user = $this->user_login->user;
		$favorite_locations_ids = array_keys($current_user->favorite_locations);

		// Check if only malls are to be returned
		if (isset($params['malls_only'])) {
			$model = 'Model_Mall';
		} elseif (isset($params['merchants_only'])) {
			$model = 'Model_Merchant';
		} else {
			$model = 'Model_Location';
		}
		$query = $model::query();

		// Only active locations
		$query->where('status', 1);

        if (!isset($params['all_merchants']) || !$params['all_merchants']) {
            // Only malls or standalone merchants
            $query->and_where_open();
            $query->or_where('mall_id', NULL);
            $query->or_where('mall_id', 0);
            $query->and_where_close();
        }
		
		$filter_set = FALSE;

		$order_by = Input::param('order_by', '');
		
		if ($order_by == 'distance' && ! (isset($params['latitude']) && isset($params['longitude']))) {
	        return $this->_error_response(Code::ERROR_MISSING_POSITION);
		}
		
		if (isset($params['latitude']) && isset($params['longitude'])) {
			if (isset($params['radius'])) {
				$radius = $params['radius'];
			} else {
				$radius = self::DEFAULT_RADIUS;
			}

			if ($radius > 0) {
    			$origin_point = Geo::build_coordinates($params['latitude'], $params['longitude']);
    			// Calculate the coordinates of the edges of the rectangle
    			list ($upper_left_point, $lower_right_point) = Geo::get_rectangle_coordinates($origin_point, $radius);
    			
    			// Get locations within the rectangle
    			$query
        			->where('longitude', 'between', array($upper_left_point->longitude, $lower_right_point->longitude))
        			->where('latitude', 'between', array($upper_left_point->latitude, $lower_right_point->latitude));
			}

			$filter_set = TRUE;
		}
		
		if (isset($params['keyword']) && !empty($params['keyword'])) {
		    if ($order_by == 'relevance') {
    		    $query->where(DB::expr("MATCH (name, address, city, st, zip, email, web, description, tags)"), ' AGAINST ', DB::expr("(" . DB::escape($params['keyword']) . ")"));
		    } else {	    
		        $this->_set_like_query($query, $params['keyword']);
		        /*
		        $search_fields = array('name', 'address', 'city', 'st', 'zip', 'email', 'web', 'description', 'tags');
    		    $query->and_where_open();
    		    foreach ($search_fields as $field) {
    		        $query->or_where($field, 'like', "%{$params['keyword']}%");
    		    }
    		    $query->and_where_close();
    		    */
		    }

			$filter_set = TRUE;
		} elseif ($order_by == 'relevance' || $order_by == 'simple_relevance') {
		    $order_by = 'name';
		}
		
		if (isset($params['favorites']) && !empty($params['favorites']) && $params['favorites'] == 1) {
            if (empty($favorite_locations_ids)) {
                return $this->_error_response(Code::ERROR_NO_FAVORITE_LOCATIONS);
            }
		    $query->where('id', 'in', $favorite_locations_ids);
		
		    $filter_set = TRUE;
		}
		
		if (! $filter_set) {
			return $this->_error_response(Code::ERROR_NO_FILTERS);
		}		
		
		// Set the output
		$count = $query->count();
		$meta['status'] = 1;
		$meta['error'] = null;

		$locations_query = $query->get_query()->reset_select_columns()->select('id')
		    ->select('latitude')->select('longitude');

		if ($order_by == 'relevance') {
			$locations_query->order_by(DB::expr("name_relevance + all_relevance"), 'desc');
		} elseif ($order_by == 'simple_relevance') {
			$locations_query->order_by(DB::expr("`relevance`"), 'desc')->order_by('name', 'asc');
		} elseif ($order_by == 'name') {
			$locations_query->order_by('name', 'asc');
		} elseif ($order_by == 'distance') {
			$locations_query->order_by(DB::expr('distance'), 'asc');
		}
		
		if (isset($params['pagination']) && $params['pagination']) {
    		$meta['pagination'] = $this->_pagination($count, Input::param('page', 1));
		    $limit = $meta['pagination']['limit'];
            $offset = $meta['pagination']['offset']['current'];
            $locations_query->offset($offset)->limit($limit);
		}
        
        if ($order_by == 'relevance') {
            $locations_query
                ->select(DB::expr("MATCH (name) AGAINST (" . DB::escape($params['keyword']) . ") AS name_relevance"))
                ->select(DB::expr("MATCH (name, address, city, st, zip, email, web, description, tags) AGAINST (" . DB::escape($params['keyword']) . ") AS all_relevance"));
        } elseif ($order_by == 'distance') {
            $locations_query
                ->select(DB::expr("3956 * 2 * ASIN(SQRT(POWER(SIN(({$params['latitude']} - latitude) * PI()/180 / 2), 2) + COS({$params['latitude']} * PI()/180) * COS(latitude * PI()/180) * POWER(SIN(({$params['longitude']} - longitude) * PI()/180 / 2), 2) )) as distance"));
        } elseif ($order_by == 'simple_relevance') {
            $locations_query
                ->select($this->_get_simple_relevance_expr($params['keyword']));
        }
        
        // workaround to override orm hydration (takes a long long time!)
        $locations = $locations_query->distinct()->execute()->as_array();

        $ids = array(-1); // in order to prevent query from failing if there are no ids
        foreach ($locations as $loc) {
            $ids[] = $loc['id'];
        }

        $new_query = $model::query();
        $new_query->where('id', 'in', $ids);
        $unsorted_locations_to_output = $new_query->get();
        $locations_to_output = array();
        // Order according to the previous query response
        foreach($ids as $id) {
            if (isset($unsorted_locations_to_output[$id])) {
                $locations_to_output[] = $unsorted_locations_to_output[$id];
            }
        }
        $locations_to_output = Helper_Api::locations_response($locations_to_output, $favorite_locations_ids, false);
        $locations_to_output = $this->add_micello_info($locations_to_output);
        
        if (isset($params['include_parent_mall']) && $params['include_parent_mall']) {
            foreach ($locations_to_output as $loc) {
                if ($loc->mall_id) {
                    $loc->parent_mall = new stdClass();
                    $mall = Model_Mall::find($loc->mall_id);
                    $loc->parent_mall->id = $mall->id;
                    $loc->parent_mall->name = $mall->name;
                }
            }
        }
        
		$data = array('data' => array('locations' => $locations_to_output), 'meta' => $meta);
		$this->response($data);
	}
    
    private function add_micello_info($locations) {
        if (empty($locations)) {
            return $locations;
        }
        
        $locations_to_return = array();
        
        foreach ($locations as $location) {
            $location->micello_info = null;
            $locations_to_return[$location->id] = $location;
        }

        $ids = array_keys($locations_to_return);
        $micello_infos = Model_Micello_Info::query()
                ->select('micello_id', 'location_id', 'type', 'geometry_id')
                ->where('location_id', 'in', $ids)
                ->get();
        
        foreach ($micello_infos as $info) {
            $locations_to_return[$info->location_id]->micello_info = $info;
        }
        
        return array_merge($locations_to_return); // in order to restore numeric indexes
    }

	public function action_map() {
		if (Input::method() != 'GET') { $this->response($this->no_access); return; }
	
		$id = $this->param('id', 0);
		$location = Model_Location::find($id);
	
		if (!$location) {
			return $this->_error_response(Code::ERROR_INVALID_LOCATION_ID);
		}
	
		$micello_info = $location->micello_info;
	
		if (!$micello_info) {
			return $this->_error_response(Code::ERROR_NO_MICELLO_INFO);
		}
	
		// This shouldn't happen. This action should only be called using a mall
		// or standalone merchant
		if ($micello_info->type != Model_Micello_Info::TYPE_COMMUNITY) {
			return $this->_error_response(Code::ERROR_INCORRECT_MICELLO_TYPE);
		}
	
		try {
		    $this->update_micello_map($micello_info);
		} catch (Exception $e) {
		    return $this->_error_response($e->getCode(), array('messages' => $e->getMessage()));
		}
		
		// Set the output
		$meta = array(
			'status' => 1,
			'error'  => null,
		);
        
        if (!$micello_info->map) {
			return $this->_error_response(Code::ERROR_MICELLO_REQUEST);
        }
        
		$data = array('data' => array('map' => $micello_info->map), 'meta' => $meta);
		$this->response($data, 200, true);
	}

	public function action_maps_validity() {
	    if (Input::method() != 'POST') { $this->response($this->no_access); return; }
	     
	    $communities_raw = Input::post('communities', NULL);
	    
	    $communities = json_decode($communities_raw);
	    
	    if ($communities === NULL || !is_array($communities)) {
	        return $this->_error_response(Code::ERROR_INVALID_COMMUNITIES);
	    }
	    
	    $outdated_communities = array();
	    foreach($communities as $community) {
	        if (isset($community->cid) && isset($community->v)) {
	            $micello_info = Model_Micello_Info::query()
	                ->related('location')
	                ->where('micello_id', $community->cid)
	                ->where('type', Model_Micello_Info::TYPE_COMMUNITY)
	                ->where('location.status', 1)
	                ->get_one();
	            
	            if ($micello_info) {
    	            try {
    	            	$this->update_micello_map($micello_info);
    	            } catch (Exception $e) {
    	            	return $this->_error_response($e->getCode(), array('messages' => $e->getMessage()));
    	            }
    	             
    	            if ($micello_info->map_version > $community->v) {
    	                $outdated_communities[] = $community->cid;
    	            }
	            }
	        }
	    }
	    
	    $meta = array(
    		'status' => 1,
    		'error'  => null,
	    );
	    
	    $data = array('data' => array('outdated_communities' => $outdated_communities), 'meta' => $meta);
	    $this->response($data);
	}

    private function update_micello_map(&$micello_info) {
        $map_updated = Helper_Micello::update_map($micello_info);
        if (!$micello_info->save()) {
            throw new Exception("Error saving micello map info", Code::ERROR_SAVE_MICELLO_INFO);
        }
        if ($map_updated) {
            $this->send_map_update_email($micello_info);
        }
    }
	
    private function send_map_update_email($micello_info) {
        $email_data = array('mall' => $micello_info->location);
        $notification_email = Config::get('cms.map_update_notification_email');
        $location_name = Helper_Api::get_location_friendly_name($micello_info->location);
        $result = CMS::email($notification_email, null, "$location_name has been updated and needs review", $email_data, 'email/map_updated');
        if ($result['meta']['status'] == 0) {
        	\Log::warning("An error occurred while sending map update notification email to admin: {$result['meta']['error']}");
        }
    }
    
	public function action_points() {
		if (Input::method() != 'GET') { $this->response($this->no_access); return; }
		
		// The id of the mall/standalone merchant
		$id = $this->param('id', 0);
		$location_model = Model_Location::find($id);
		
		// Only malls and standalone merchants are allowed
		if (!$location_model || !empty($location_model->mall_id)) {
			return $this->_error_response(Code::ERROR_INVALID_LOCATION_ID);
		}

		$favorite_locations_ids = array_keys($this->user_login->user->favorite_locations);

		$ignore_like_status = Input::get('ignore_like_status', FALSE);
		
		$location = Helper_Api::location_response($location_model, $favorite_locations_ids);
		$location->offers = $this->_get_offers_for_locations($location, FALSE, $ignore_like_status);
		$location->events = $this->_get_events_for_locations($location, FALSE, $ignore_like_status);

		// Get merchants that are inside the location
		$merchants_query = Model_Merchant::query()
    		->related('micello_info')
    		->where('status', 1)
    		->where('mall_id', $location->id);

		$floor = Input::get('floor', NULL);

		if (!is_null($floor)) {
		    $merchants_query->where('floor', $floor);
		}
		
		$merchants = $merchants_query->get();
        
		$location->merchants = array();
		foreach($merchants as $merchant_model) {
			$merchant = Helper_Api::location_response($merchant_model, $favorite_locations_ids);
            $merchant->offers = array();
            $merchant->events = array();
			$location->merchants[$merchant->id] = $merchant;
		}
        
        $offers = $this->_get_offers_for_locations($merchants, true, $ignore_like_status);
        foreach ($offers as $offer) {
            foreach ($offer->locations as $offer_location) {
                if (isset($location->merchants[$offer_location->id])) {
                    $location->merchants[$offer_location->id]->offers[] = $offer;
                }
            }
            unset($offer->locations); // client side doesn't need this info
        }
        
        $events = $this->_get_events_for_locations($merchants, true, $ignore_like_status);
        foreach ($events as $event) {
            foreach ($event->locations as $event_location) {
                if (isset($location->merchants[$event_location->id])) {
                    $location->merchants[$event_location->id]->events[] = $event;
                }
            }
            unset($event->locations); // client side doesn't need this info
        }
        
        $location->merchants = array_merge($location->merchants); // to restore indexes
		
		// Set the output
		$meta = array(
			'status' => 1,
			'error'  => null,
		);
		$data = array('data' => array('location' => $location), 'meta' => $meta);
		$this->response($data);
	}
	
	public function action_merchants_at_mall() {
	    if (Input::method() != 'GET') { $this->response($this->no_access); return; }
	
	    // The id of the mall/standalone merchant
	    $id = $this->param('id', 0);
	    $location_model = Model_Location::find($id);
	
	    // Only malls and standalone merchants are allowed
	    if (!$location_model || !empty($location_model->mall_id)) {
	        return $this->_error_response(Code::ERROR_INVALID_LOCATION_ID);
	    }
	
	    $location = Helper_Api::location_response($location_model);
	
	    // Get merchants that are inside the location
	    $merchants_query = Model_Merchant::query()
                    	    ->related('micello_info')
                    	    ->where('status', 1)
                    	    ->where('mall_id', $location->id);
	    
	    $keyword = Input::get('keyword', '');
	    if (!empty($keyword)) {
	        $this->_set_like_query($merchants_query, $keyword);
            /*
	        $search_fields = array('name', 'address', 'city', 'st', 'zip', 'email', 'web', 'description');
	        $merchants_query->and_where_open();
	        foreach ($search_fields as $field) {
	            $merchants_query->or_where($field, 'like', "%{$keyword}%");
	        }
	        $merchants_query->and_where_close();
	        */
	        $filter_set = TRUE;
	    }
	    
	    $order_by = Input::get('order_by', '');
	    if (!empty($order_by) && $order_by == 'name') {
	        $merchants_query->order_by('name');
	    }
	
	    $merchants = $merchants_query->get();
	
	    $location->merchants = array();
	    foreach($merchants as $merchant_model) {
	        $merchant = Helper_Api::location_response($merchant_model);
	        array_push($location->merchants, $merchant);
	    }
	
	    // Set the output
	    $meta = array(
	        'status' => 1,
	        'error'  => null,
	    );
	    $data = array('data' => array('location' => $location), 'meta' => $meta);
	    $this->response($data);
	}

	public function action_merchants() {
	    if (Input::method() != 'GET') { $this->response($this->no_access); return; }

	    // The id of the mall/standalone merchant
	    $id = $this->param('id', 0);
	    $location_model = Model_Location::find($id);

	    // Only malls and standalone merchants are allowed
	    if (!$location_model || !empty($location_model->mall_id)) {
	        return $this->_error_response(Code::ERROR_INVALID_LOCATION_ID);
	    }

	    $order_by = Input::get('order_by', 'name');
	    $keyword = Input::get('keyword', '');

	    $columns_array = array('id');
	    if (! empty($keyword) && $order_by == 'relevance') {
	    	$columns_array[] = DB::expr("MATCH (name) AGAINST (" . DB::escape($keyword) . ") AS name_relevance");
	    	$columns_array[] = DB::expr("MATCH (name, address, city, st, zip, email, web, description, tags) AGAINST (" . DB::escape($keyword) . ") AS all_relevance");
	    }

	    if (! empty($keyword) && $order_by == 'simple_relevance') {
	    	$columns_array[] = $this->_get_simple_relevance_expr($keyword);
	    }

	    // Get merchants that are inside the location
	    $raw_query = DB::select_array($columns_array)
	        ->from('locations')
	        ->where('type', Model_Location::TYPE_MERCHANT)
    	    ->where('status', 1)
    	    ->where('mall_id', $location_model->id);

	    if (!empty($keyword)) {
	        if ($order_by == 'name' || $order_by == 'simple_relevance') {
	            $this->_set_like_query($raw_query, $keyword);
	            /*
    	        $search_fields = array('name', 'address', 'city', 'st', 'zip', 'email', 'web', 'description', 'tags');
    	        $raw_query->and_where_open();
    	        foreach ($search_fields as $field) {
    	            $raw_query->or_where($field, 'like', "%{$keyword}%");
    	        }
    	        $raw_query->and_where_close();
    	        */
	        } elseif ($order_by == 'relevance') {
	            $raw_query->where(DB::expr("MATCH (name, address, city, st, zip, email, web, description, tags)"), ' AGAINST ', DB::expr("(" . DB::escape($keyword) . ")"));
	        }
	    } elseif ($order_by == 'relevance') {
	        $order_by = 'name';
	    }

	    if ($order_by == 'relevance') {
	    	$raw_query->order_by(DB::expr("name_relevance + all_relevance"), 'desc');
	    } elseif ($order_by == 'simple_relevance') {
	    	$raw_query->order_by(DB::expr("`relevance`"), 'desc')->order_by('name', 'asc');
	    } elseif ($order_by == 'name') {
	    	$raw_query->order_by('name', 'asc');
	    }
	    
	    $page = Input::param('page', 1);

    	$raw_query->limit($this->limit)->offset(($page - 1) * $this->limit);
	    
	    $merchant_ids = $raw_query->execute();

	    $merchant_ids = array_map(function($m) { return $m['id']; }, $merchant_ids->as_array());
	    if (count($merchant_ids) == 0) {
	        $merchant_ids = array(-1);
	    }

	    $merchants_count = DB::count_last_query();

        $meta = array('pagination' => $this->_pagination($merchants_count, $page));
		$meta['status'] = 1;
		$meta['error'] = null;

        $merchants = Model_Merchant::query()
            ->where('id', 'in', $merchant_ids)
            ->get();

        $merchants_response = array();

        // Sort entries according to the previous query response
	    foreach ($merchant_ids as $merchant_id) {
	        if (isset($merchants[$merchant_id])) {
	            $merchant_model = $merchants[$merchant_id];
                $merchant_response = Helper_Api::location_response($merchant_model, $this->user_login->user->favorite_locations, false);
                $merchant_response->offers_count = $this->_get_offers_for_locations($merchant_model, false, false, true);
                $merchant_response->events_count = $this->_get_events_for_locations($merchant_model, false, false, true);
                $merchants_response[] = $merchant_response;
	        }
	    }

	    $data = array('data' => array('merchants' => $merchants_response), 'meta' => $meta);
	    $this->response($data);
	}
	
	public function action_micello_community() {
		if (Input::method() != 'GET') { $this->response($this->no_access); return; }

		$keyword = Input::get('term');
		
		if (! $keyword) {
			return $this->response(array());
		}

		$search_by = Input::get('search_by', 'keyword');
		Package::load('micello');
		try {
		    if ($search_by == 'keyword') {
			    $result = Micello\Api::get_communities_by_keyword($keyword);
		    } else {
		        $community = Micello\Api::get_community_by_id($keyword);
		        $result = new \stdClass();
		        $result->results = array($community);
		    }
		} catch (Micello\MicelloException $e) {
			return $this->_error_response(Code::ERROR_MICELLO_REQUEST, array('messages' => $e->getMessage() . ' [' . $e->getCode() . ']'));
		}

		$locations = $result->results;
		
		$types = explode('|', Input::get('type', ''));

		if (count($types) > 0) {
			$locations = array_filter($locations, function($l) use($types) { return in_array($l->ct, $types); });
		}
		
		$response = array();
		foreach($locations as $location) {
			$entry = new stdClass();
			$entry->value = $location->name;
			$entry->data  = $location;
			array_push($response, $entry);
		}
		
		$this->response($response);
	}

	public function action_micello_entity() {
	    if (Input::method() != 'GET') { $this->response($this->no_access); return; }
	
	    $location_id = Input::get('location_id');
	    $location = Model_Location::find($location_id);
	    
	    if (!$location || !$location->micello_info || $location->micello_info->type != Model_Micello_Info::TYPE_COMMUNITY) {
	        return $this->response(array());
	    }

	    Package::load('micello');
	    try {
	        $result = Micello\Api::get_entities($location->micello_info->micello_id);
	    } catch (Micello\MicelloException $e) {
	        return $this->_error_response(Code::ERROR_MICELLO_REQUEST, array('messages' => $e->getMessage() . ' [' . $e->getCode()) . ']');
	    }
	
	    $entities = $result->results;
	
	    $include_entity_info = \Input::get('include_entity_info', FALSE);
	    
	    $response = array();
	    foreach($entities as $entity) {
	        $entry = new stdClass();
	        $entry->value = $entity->nm;
	        $entry->data  = $entity;
	        if ($include_entity_info) {
	            try {
	                $entity_info = Micello\Api::get_entity_info($entity->eid);
	                $entry->info = isset($entity_info->results) ? $entity_info->results : array();
        	    } catch (Micello\MicelloException $e) {
    	            $entry->info = array();
	            }
            }
	        array_push($response, $entry);
	    }

	    usort($response, function($a, $b){ return strcmp($a->value, $b->value); });
	    
	    $this->response($response);
	}
	
 	public function action_block() {
		if (Input::method() != 'POST') { $this->response($this->no_access); return; }
		
		$location_id = $this->param('id');
		$location = Model_Location::find($location_id);
		
		if (! $location) {
		return $this->_error_response(Code::ERROR_INVALID_LOCATION_ID);
		}
		
		// Determine if the location is already blocked
		$blocking = Model_Location_Blocking::query()
			->where('user_id', $this->user_login->user_id)
			->where('location_id', $location_id)
			->get_one();
		
		if (!$blocking) {
			// Create a new blocking
			$blocking = new Model_Location_Blocking();
		}
		
		$time_lapse = Input::param('time_lapse', Model_Location_Blocking::BLOCKED_PERMANENTLY);
		switch($time_lapse) {
			case Model_Location_Blocking::BLOCKED_PERMANENTLY:
				$relative_time = Model_Location_Blocking::BLOCKED_PERMANENTLY_TIME;
				break;
			case Model_Location_Blocking::BLOCKED_TODAY:
				$relative_time = Model_Location_Blocking::BLOCKED_TODAY_TIME;
				break;
			case Model_Location_Blocking::BLOCKED_THIS_WEEK:
				$relative_time = Model_Location_Blocking::BLOCKED_THIS_WEEK_TIME;
				break;
			default:
				return $this->_error_response(Code::ERROR_INVALID_BLOCKING_TIME_LAPSE);
		}
		
		$time = time();
		$blocking->type = $time_lapse;
		$blocking->user_id = $this->user_login->user_id;
		$blocking->location_id = $location_id;
		$blocking->start_date = date('Y-m-d H:i:s');
		$blocking->end_date = date('Y-m-d H:i:s', strtotime($relative_time, $time));
		
		if (!$blocking->save()) {
			return $this->_error_response(Code::ERROR_WHILE_BLOCKING);
		}
		
		$output = array('meta' => array('error' => '', 'status' => 1), 'data' => array('blocking' => $blocking));
		$this->response($output);
	}
	
	public function action_unblock() {
		if (Input::method() != 'DELETE') { $this->response($this->no_access); return; }
		
		$location_id = $this->param('id');
		$location = Model_Location::find($location_id);
		
		if (! $location) {
			return $this->_error_response(Code::ERROR_INVALID_LOCATION_ID);
		}
		
		// Determine if the location is already blocked
		$blocking = Model_Location_Blocking::query()
			->where('user_id', $this->user_login->user_id)
			->where('location_id', $location_id)
			->get_one();
		
		if (!$blocking) {
			return $this->_error_response(Code::ERROR_LOCATION_NOT_BLOCKED);
		}
		
		if (!$blocking->delete()) {
			return $this->_error_response(Code::ERROR_WHILE_UNBLOCKING);
		}
		
		$output = array('meta' => array('error' => '', 'status' => 1), 'data' => array());
		$this->response($output);
	}
	
	public function action_blocked() {
		if (Input::method() != 'GET') { $this->response($this->no_access); return; }
		
		// Search for blocked merchants
		$blockings = Model_Location_Blocking::query()
		->where('user_id', $this->user_login->user_id)
		->get();
		
		$output = array('meta' => array('error' => '', 'status' => 1), 'data' => array('blockings' => $blockings));
		$this->response($output);
	}
	
	/**
	 * Favorite/unfavorite location
	 */
	public function action_favorite() {	
	    if (Input::method() != 'POST') { $this->response($this->no_access); return; }
	    
	    $location_id = $this->param('id');
		$location = Model_Location::find($location_id);
		
		if (!$location) {
			return $this->_error_response(Code::ERROR_INVALID_LOCATION_ID);
		}
		
		$current_user = $this->user_login->user;
		
		// fav = 1, unfav = 0
		$status = Input::post('status', -1);
		$is_favorite = array_key_exists($location->id, $current_user->favorite_locations);
	    
		if ($status == $is_favorite || $status == -1) {
		    return $this->_error_response(Code::ERROR_UPDATING_FAVORITE_LOCATIONS);
		} else if (!$status) {
	        unset($current_user->favorite_locations[$location->id]);
            Helper_Activity::log_activity($current_user, 'unfavorite_location', array('location_id' => (int)$location->id));
	    } else if ($status) {
	        $current_user->favorite_locations[$location->id] = $location;
            Helper_Activity::log_activity($current_user, 'favorite_location', array('location_id' => (int)$location->id));
	    }
	    
	    if ($current_user->save()) {
	        $data = array(
	            'data' => array('status' => true),
	            'meta' => array('error' => '', 'status' => 1)
	        );
	        $this->response($data);
	    } else {
	        $this->_error_response(Code::ERROR_UPDATING_FAVORITE_LOCATIONS);
	    }
	}
	
	/**
	 * Request shopsuey for location
	 */
	public function action_request() {
	    if (Input::method() != 'POST') { $this->response($this->no_access); return; }
	    
	    $location_id = $this->param('id');
	    $location = Model_Location::find($location_id);
	
	    if (!$location) {
	        return $this->_error_response(Code::ERROR_INVALID_LOCATION_ID);
	    }
	
	    $current_user = $this->user_login->user;
	    $location_request = new Model_Location_Request();
	    $location_request->location = $location;
	    
	    $location_request_query = Model_Location_Request::query()
                            	    ->where('location_id', $location_id)
                            	    ->where('user_id', $current_user->id);
	     
	    if ($location_request_query->get_one() == null) {
    	    $current_user->location_requests[] = $location_request;
    	    
    	    if (!$current_user->save()) {
    	        return $this->_error_response(Code::ERROR_SAVING_LOCATION_REQUEST);
    	    }
	    } else {
	        return $this->_error_response(Code::ERROR_EXISTENT_LOCATION_REQUEST);
	    }
	    
	    $data = array(
	        'data' => array('status' => true),
	        'meta' => array('error' => '', 'status' => 1)
	    );
	    $this->response($data);
	}
	
	/**
	 * Checks if the current user has requested shopsuey for location
	 */
	public function action_requested() {
	    if (Input::method() != 'GET') { $this->response($this->no_access); return; }
	     
	    $location_id = $this->param('id');
	    $location = Model_Location::find($location_id);
	
	    if (!$location) {
	        return $this->_error_response(Code::ERROR_INVALID_LOCATION_ID);
	    }
	
	    $current_user = $this->user_login->user;
	    $location_request_query = Model_Location_Request::query()
                            	    ->where('location_id', $location_id)
                            	    ->where('user_id', $current_user->id);
	    
	    $requested = $location_request_query->get_one() != null;
	
	    $data = array(
	        'data' => array('requested' => $requested),
	        'meta' => array('error' => '', 'status' => 1)
	    );
	    $this->response($data);
	}
	
	/**
	 * Update a location. Intended to be used by non-admin CMS users
	 */
	public function action_put() {
	    $allowed_fields = array(
            'name', 'address', 'city', 'st', 'country_id', 'zip', 'contact', 
	        'email', 'phone', 'web', 'hours', 'timezone', 'logo', 'landing_screen_img', 
	        'social', 'use_instagram', 'user_instagram_id', 'default_social', 
        );
        $properties = array_keys(Model_Location::properties());
	
	    $location_id = $this->param('id', 0);
	    $location = Model_Location::find($location_id);
	
	    if (!$location) {
	        $this->_error_response(Code::ERROR_INVALID_LOCATION_ID);
	        return;
	    }

        if (!in_array($location_id, array_keys($this->user_login->user->get_assigned_companies()))) {
            $this->_error_response(Code::ERROR_INVALID_LOCATION_ID);
            return;
        }
        
        if ($location->type == Model_Location::TYPE_MERCHANT && is_null($location->mall_id)) {
            // It's a stand alone merchant, so I'll allow them to update their coordinates
            $allowed_fields[] = 'latitude';
            $allowed_fields[] = 'longitude';
        }

        $category_ids = Input::put('category_ids', array());
        $category_ids_to_add    = array_diff($category_ids, array_keys($location->categories));
        $category_ids_to_remove = array_diff(array_keys($location->categories), $category_ids);
        foreach ($category_ids_to_remove as $category_id) {
            unset($location->categories[$category_id]);
        }
        foreach ($category_ids_to_add as $category_id) {
            $location->categories[] = Model_Category::find($category_id);
        }
        
        // Only allowed properties can be set
	    foreach($properties as $property) {
	        if (! in_array($property, $allowed_fields)) { continue; }
	        
	        $value = Input::put($property, NULL);
	        if (is_null($value)) { continue; }
	        
	        $location->$property = Input::put($property);
	    }
	
	    $location->edited_by = $this->user_login->user_id;

	    $phone = Input::put('phone', '');
	    $valid = preg_match('/^([\+][0-9]{1,3}[ \.\-])?([\(]{1}[0-9]{2,6}[\)])?([0-9 \.\-\/]{3,20})((x|ext|extension)[ ]?[0-9]{1,4})?$/', $phone);
	    if (!empty($phone) && !$valid) {
	        return $this->_error_response(Code::ERROR_WRONG_PHONE_FORMAT);
	    }
	
	    $website = Input::put('web', '');
	    $valid = filter_var($website, FILTER_VALIDATE_URL);
	    if (!empty($website) && !$valid) {
            // let's see if adding 'http://', the website is now valid
            $valid = filter_var('http://' . $website, FILTER_VALIDATE_URL);
            if ($valid) {
                $location->web = 'http://' . $website;
            } else {
                return $this->_error_response(Code::ERROR_INVALID_WEBSITE_URL);
            }
	    }

	    $phone = Input::put('email', '');
	    $valid = filter_var($phone, FILTER_VALIDATE_EMAIL);
	    if (!empty($phone) && !$valid) {
	        return $this->_error_response(Code::ERROR_INVALID_LOCATION_EMAIL);
	    }

	    // FIXME: This should not happen until the second step is completed
	    $location->setup_complete = TRUE;
	    $location->manually_updated = TRUE;
	    
	    if ($location->save()) {
	        $output = array('data' => array(), 'meta' => array('error' => null, 'status' => 1));
	    } else {
	        $output = array('data' => Input::put(), 'meta' => array('error' => 'Unable to save location', 'status' => 0));
	    }

	    $this->response($output);
	}

	/*
	 * Update the url of an image
	 */
	public function action_update_images() {
	    $location_id = $this->param('id', 0);
	    $location = Model_Location::find($location_id);
	    
	    if (!$location) {
	        $this->_error_response(Code::ERROR_INVALID_LOCATION_ID);
	        return;
	    }
	    
        if (!in_array($location_id, array_keys($this->user_login->user->get_assigned_companies()))) {
	        $this->_error_response(Code::ERROR_INVALID_LOCATION_ID);
	        return;
	    }

	    if ($logo_url = Input::put('logo', NULL)) {
	        $location->logo = $logo_url;
	    }

	    if ($landing_image_url = Input::put('landing', NULL)) {
	        $location->landing_screen_img = $landing_image_url;
	    }

	    if ($location->save()) {
	        $output = array('data' => array(), 'meta' => array('error' => null, 'status' => 1));
	    } else {
	        $output = array('data' => Input::put(), 'meta' => array('error' => 'Unable to save location images', 'status' => 0));
	    }
	    
	    $this->response($output);
	}
	
	public function action_foursquare_venues() {
	    if (Input::method() != 'GET') { $this->response($this->no_access); return; }

	    $keyword = Input::get('term');
	
	    if (!$keyword) {
	        return $this->response(array());
	    }

	    $location = trim(Input::get('location'));
	     
	    Package::load('foursquare');
	    try {
	        $result = Foursquare\Api::get_venues_by_term($keyword, $location);
	    } catch (Foursquare\FoursquareException $e) {
	        return $this->_error_response(Code::ERROR_FOURSQUARE_REQUEST, array('messages' => $e->getMessage() . ' [' . $e->getCode() . ']'));
	    }

	    $venues = $result->venues;

	    $response = array();
	    foreach($venues as $venue) {
	        $entry = new stdClass();
	        $entry->value = $venue->name;
	        if (isset($venue->location) && isset($venue->location->city)) {
	            $entry->value .= " - {$venue->location->city}";
	        }
	        $entry->data  = $venue;
	        array_push($response, $entry);
	    }

	    // FIXME: The $this->response() method returns nothing on an empty array
	    $this->response->set_header('Cache-Control', 'no-cache, must-revalidate');
	    $this->response->set_header('Expires', 'Mon, 26 Jul 1997 05:00:00 GMT');
	    $this->response->set_header('Content-Type', 'application/json');
	    $this->response->body(\Format::forge($response)->to_json());
	}

	public function action_foursquare_hours() {
	    if (Input::method() != 'GET') { $this->response($this->no_access); return; }

	    $venue_id = Input::get('venue_id');

	    if (!$venue_id) {
	        return $this->response(array());
	    }

	    Package::load('foursquare');
	    try {
	        $result = Foursquare\Api::get_venue_hours($venue_id);
	    } catch (Foursquare\FoursquareException $e) {
	        return $this->_error_response(Code::ERROR_FOURSQUARE_REQUEST, array('messages' => $e->getMessage() . ' [' . $e->getCode() . ']'));
	    }

	    $this->response($this->_process_foursquare_hours($result));
	}
	
	private function _process_foursquare_hours($foursquare_hours) {
	    $days = array(1 => 'mon', 2 => 'tue', 3 => 'wed', 4 => 'thr', 5 => 'fri', 6 => 'sat', 7 => 'sun');

	    $groups = array(
            array('days' => array(), 'open' => '', 'close' => ''),
            array('days' => array(), 'open' => '', 'close' => ''),
            array('days' => array(), 'open' => '', 'close' => ''),
	    );

	    if (!isset($foursquare_hours->hours) || !isset($foursquare_hours->hours->timeframes)) {
	        return $groups;
	    }
	
	    for($i = 0; $i < count($foursquare_hours->hours->timeframes) && $i < count($groups); $i++) {
	        $time_frame = $foursquare_hours->hours->timeframes[$i];
	        $open_times = array_shift($time_frame->open);
	        $groups[$i]['open'] = date('h:iA', strtotime($open_times->start));
	        $groups[$i]['close'] = date('h:iA', strtotime($open_times->end));
	        foreach($time_frame->days as $key => $number) {
	            $groups[$i]['days'][] = $days[$number];
	        }
	    }
	
	    return $groups;
	}
	
	public function action_yelp_businesses() {
	    if (Input::method() != 'GET') { $this->response($this->no_access); return; }
	
	    $keyword = Input::get('term');
	
	    if (!$keyword) {
	        return $this->response(array());
	    }
	
	    $location = trim(Input::get('location'));
	
	    Package::load('yelp');
	    try {
	        $result = Yelp\Api::search_businesses_by_term($keyword, $location);
	    } catch (Yelp\YelpException $e) {
	        return $this->_error_response(Code::ERROR_YELP_REQUEST, array('messages' => $e->getMessage() . ' [' . $e->getCode() . ']'));
	    }
	
	    $businesses = isset($result->businesses) ? $result->businesses : array();
	
	    $response = array();
	    foreach($businesses as $business) {
	        $entry = new stdClass();
	        $entry->value = $business->name;
	        if (isset($business->location) && isset($business->location->city)) {
	            $entry->value .= " - {$business->location->city}";
	        }
	        $entry->data  = $business;
	        array_push($response, $entry);
	    }
	
	    // FIXME: The $this->response() method returns nothing on an empty array
	    $this->response->set_header('Cache-Control', 'no-cache, must-revalidate');
	    $this->response->set_header('Expires', 'Mon, 26 Jul 1997 05:00:00 GMT');
	    $this->response->set_header('Content-Type', 'application/json');
	    $this->response->body(\Format::forge($response)->to_json());
	}
	
	private function _compact_response($locations) {
	    $compact_locations = array();
	    foreach($locations as $location) {
	        $entry = array();
	        $entry['value'] = $entry['id'] = $location['id'];
	        if ($location['type'] == Model_Location::TYPE_MERCHANT && !empty($location['mall_id'])) {
                $city = $location['city'] ? $location['city'] : $location['mall_city'];
	            $entry['name'] = "{$location['name']} [{$location['type']}, {$location['mall_name']}, $city]";
	        } else {
	            $entry['name'] = "{$location['name']} [{$location['type']}, {$location['city']}]";
	        }
	        $entry['address'] = $location['address'];
	        $entry['city'] = $location['city'];
	        $entry['st'] = $location['st'];
	        $entry['zip'] = $location['zip'];
	        $entry['email'] = $location['email'];
	        $entry['web'] = $location['web'];
	        $entry['description'] = $location['description'];
	        $entry['type'] = $location['type'];
	        $compact_locations[] = $entry;
	    }
	    return $compact_locations;
	}
	
    /**
     * Service to determine if the user's current position is supported, i.e.,
     * there are locations nearby his current position
     */
    public function action_position_supported() {
        if (Input::method() != 'GET') { $this->response($this->no_access); return; }

        $params = Input::get();
        
        if (! (isset($params['latitude']) && isset($params['longitude']))) {
            return $this->_error_response(Code::ERROR_MISSING_POSITION);
        }

        if (isset($params['radius'])) {
            $radius = $params['radius'];
        } else {
            $radius = self::DEFAULT_RADIUS;
        }

        $nearby_locations_ids = Model_Location::get_nearby_location_ids($params['latitude'], $params['longitude'], $radius, null);
        $position_supported = count($nearby_locations_ids) > 0;

        $data = array(
            'data' => array('supported' => $position_supported),
            'meta' => array('error' => '', 'status' => 1)
        );
        $this->response($data);
    }
    
    public function action_active_shoppers() {
        if (Input::method() != 'GET') { $this->response($this->no_access); return; }
        
        $id = $this->param('id', 0);
        $location = Model_Location::find($id);
    
    	if (!$location) {
    		return $this->_error_response(Code::ERROR_INVALID_LOCATION_ID);
    	}
    
    	$micello_info = $location->micello_info;
    	
    	if (!$micello_info) {
    		return $this->_error_response(Code::ERROR_NO_MICELLO_INFO);
    	}

    	// This shouldn't happen. This action should only be called using a mall
    	// or standalone merchant
    	if ($micello_info->type != Model_Micello_Info::TYPE_COMMUNITY) {
    		return $this->_error_response(Code::ERROR_INCORRECT_MICELLO_TYPE);
    	}

    	try {
    		$this->update_micello_map($micello_info);
    	} catch (Exception $e) {
    		return $this->_error_response($e->getCode(), array('messages' => $e->getMessage()));
    	}

    	$start_time = Input::get('start_time', time());
    	$end_time = Input::get('end_time', time());
    	
    	$accuracy = Input::get('accuracy', \Config::get('cms.nearby_users_accuracy'));

    	// Calculate the coordinates of the edges of the rectangle
    	list ($upper_left_point, $lower_right_point) = Helper_Micello::calculate_map_bounds($micello_info);

        $location_trackings = Model_Location_Tracking::get_nearby_users(
            $upper_left_point,
            $lower_right_point,
	        $accuracy,
            $start_time,
            $end_time
        );
    
    	$data = array(
			'data' => array('active_shoppers' => $location_trackings),
			'meta' => array('error' => '', 'status' => 1)
    	);
    	$this->response($data);
    }

    public function action_get_current_health_metrics() {
        if (Input::method() != 'GET') { $this->response($this->no_access); return; }
    
        $ids = Input::get('location_ids', '');
        $ids = explode(',', $ids);
    
        $health_metrics = DB::select()
            ->from('health_metrics')
            ->where('location_id', 'in', $ids)
            ->order_by('created_at', 'asc')
            ->execute();
        
        $response = array();
        foreach ($health_metrics as $health_metric) {
            $response[$health_metric['location_id']] = $health_metric;
        }
        
        $data = array(
            'data' => array('health_metrics' => $response),
            'meta' => array('error' => '', 'status' => 1)
        );
        
        $this->response($data);
    }
    
    public function action_get_historic_health_metrics() {
        if (Input::method() != 'GET') { $this->response($this->no_access); return; }
    
        $id = $this->param('id', 0);
        
        $min_historic_date = time() - 86400 * Model_Healthmetric::HISTORIC_MAX_DAYS;
    
        $health_metrics = Model_Healthmetric::query()
            ->where('location_id', $id)
            ->where('created_at', '>=', $min_historic_date)
            ->get();
    
        $response = array();
        foreach ($health_metrics as $health_metric) {
            $response[] = Helper_Api::model_to_real_object($health_metric);
        }
    
        $data = array(
            'data' => array('health_metrics' => $response),
            'meta' => array('error' => '', 'status' => 1)
        );
    
        $this->response($data);
    }
    
    public function action_businesses_search() {
        $name = Input::get('name', '');
        $zip_or_city = Input::get('zip_or_city', '');
        $type = Input::get('type', '');
        
        $l = DB::table_prefix('locations');
        $m = DB::table_prefix('mall');
        
        $query = DB::select('locations.id', 'locations.name', 'locations.zip', 'locations.city', 'locations.type',
                    array('mall.name', 'mall_name'), array('mall.zip', 'mall_zip'),
                    array('mall.city', 'mall_city'), array('mall.id', 'mall_id'),
                    $this->_get_simple_relevance_expr($name, $l, array('name'), 'name_relevance'),
                    $this->_get_simple_relevance_expr($zip_or_city, $l, array('city', 'zip'), 'location_address_relevance'),
                    $this->_get_simple_relevance_expr($zip_or_city, $m, array('city', 'zip'), 'mall_address_relevance')
            )
            ->from('locations')
            ->join(array('locations', 'mall'), 'LEFT')
            ->on('locations.mall_id', '=', 'mall.id')
            ->where('locations.status', 1)
            ->and_where_open()
            ->or_where('mall.status', 1)
            ->or_where('mall.status', NULL)
            ->and_where_close();

        $name_parts = preg_split('/\s+/', $name);
        $query->and_where_open();
    	foreach($name_parts as $name_part) {
    		$query->or_where('locations.name', 'like', "%{$name_part}%");
    	}
        $query->and_where_close();

        $zip_or_city_parts = preg_split('/\s+/', $zip_or_city);
        $query->and_where_open();
        foreach($zip_or_city_parts as $zip_or_city_part) {
            $query
                ->or_where('locations.zip', 'like', "%{$zip_or_city_part}%")
                ->or_where('locations.city', 'like', "%{$zip_or_city_part}%")
                ->or_where('mall.zip', 'like', "%{$zip_or_city_part}%")
                ->or_where('mall.city', 'like', "%{$zip_or_city_part}%");
        }
        $query->and_where_close();
        
        // Check the location type to return
        if ($type == 'marketplace') {
            $query->where('locations.type', Model_Location::TYPE_MALL);
        } elseif ($type == 'merchant') {
            $query->where('locations.type', Model_Location::TYPE_MERCHANT);
        }
        
        // Hard limit on the number of results
        $query->limit(20);
        
        $result = $query
            ->order_by(DB::expr('`name_relevance` + `location_address_relevance` + `mall_address_relevance`'), 'desc')
            ->order_by('locations.name', 'asc')
            ->execute();
        
        $response = array(
            'locations' => array()
        );
        
        foreach($result->as_array() as $location) {
            if ($location['type'] == Model_Location::TYPE_MERCHANT && ! empty($location['mall_id'])) {
                $location_details = "{$location['mall_name']}, {$location['mall_city']}, {$location['mall_zip']}";
            } else {
                $location_details = "{$location['city']}, {$location['zip']}";
            }
            $response['locations'][] = array(
                'label' => "{$location['name']} [{$location_details}]",
                'id' => $location['id'],
                'mall_id' => $location['mall_id'],
                'zip' => empty($location['mall_zip']) ? $location['zip'] : $location['mall_zip'],
                'city' => empty($location['mall_city']) ? $location['city'] : $location['mall_city'],
                'name' => $location['name'],
                'location' => $location_details,
            );
        }
        
        // FIXME: The $this->response() method returns nothing on an empty array
        $this->response->set_header('Cache-Control', 'no-cache, must-revalidate');
        $this->response->set_header('Expires', 'Mon, 26 Jul 1997 05:00:00 GMT');
        $this->response->set_header('Content-Type', 'application/json');
        $this->response->body(\Format::forge($response)->to_json());
    }
    
    public function action_instagram_feed() {
    	if (Input::method() != 'GET') { $this->response($this->no_access); return; }
    
    	$id = $this->param('id', 0);
    	$location = Model_Location::find($id);
    
    	if (!$location) {
    		return $this->_error_response(Code::ERROR_INVALID_LOCATION_ID);
    	}

    	if (! $location->user_instagram) {
    	    return $this->_error_response(Code::ERROR_NO_INSTAGRAM_CONFIGURED);
    	}

        \Package::load('instagram');

		try {
			$feed = \Instagram\Api::get_user_recent_media($location->user_instagram->access_token, $location->user_instagram->instagram_user_id);
		} catch (\Instagram\Exception $e) {
    	    return $this->_error_response(Code::ERROR_INSTAGRAM_FEED);
		}

    	$data = array(
    		'data' => array('feed' => $feed, 'username' => $location->user_instagram->username),
    		'meta' => array('error' => '', 'status' => 1)
    	);
    	$this->response($data);
    }
}
