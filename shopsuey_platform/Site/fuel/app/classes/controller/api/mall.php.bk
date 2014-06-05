<?php

/**
 * The Api Controller.
 * This controllers the CRUD proceedures for malls
 *
 * @package  app
 * @extends  Controller_Rest
 */

class Controller_Api_Mall extends Controller_Api {

	/**
	 * Get a single mall
	 */
	public function action_get() {
		$id = $this->param('id', 0);
		$mall = Model_Mall::query()->where('id', $id)->get_one();
		$ajax = Input::get('ajax', 0);
		
		if ($ajax) {
		    $mall['hours'] = $this->create_hours_groups($mall);
		}
		
		if ($mall) {
			$this->response($this->_build_response($mall));
		} else {
			$this->_error_response(Code::ERROR_INVALID_MALL_ID);
		}
	}

	/**
	 * Get 25 malls with pagination meta
	 */
	public function action_list() {
		$page   = $this->param('page', 1);
		$string = $this->param('string', Input::param('string', ''));

		$sort_field = Input::param('order_by', 'name');
		if (!in_array($sort_field, array('name', 'created_at', 'status', 'relevance', 'simple_relevance'))) {
			$sort_field = 'name';
		}

		$select_columns = array('*');
		if (! empty($string) && $sort_field == 'relevance') {
		    $select_columns[] = DB::expr("MATCH (name) AGAINST (" . DB::escape($string) . ") AS name_relevance");
		    $select_columns[] = DB::expr("MATCH (`name`,`address`,`city`,`st`,`zip`,`email`,`web`,`description`,`tags`) AGAINST (" . DB::escape($string) . ") AS all_relevance");
		}

                if (! empty($string) && $sort_field == 'simple_relevance') {
                    $select_columns[] = $this->_get_simple_relevance_expr($string);
                }
                
		$query = DB::select_array($select_columns)
		    ->from('locations')
		    ->where('type', Model_Location::TYPE_MALL);
		
        if (!$this->user_login->user->is_admin()) {
            $query->where('status', '>', 0);
        }

        if (! empty($string)) {
    		if ($sort_field != 'relevance') {
                $this->_set_like_query($query, $string);
                /*
    			$search_fields = array('name','address','city','st','zip','email','web','description','tags');
    			$query->and_where_open();
    			foreach ($search_fields as $field) {
    				$query->or_where($field, 'like', "%$string%");
    			}
    			$query->and_where_close();
                 */
    		} else {
    		    $query->where(DB::expr("MATCH (`name`,`address`,`city`,`st`,`zip`,`email`,`web`,`description`,`tags`)"), ' AGAINST ', DB::expr("(" . DB::escape($string) . ")"));
    		}
        }

		$ids = $this->param('ids', Input::param('ids', array()));
		
		if (count($ids) > 0) {
		    $query->where('id', 'in', $ids);
		}

		if ($sort_field == 'relevance') {
		    $query->order_by(DB::expr("name_relevance + all_relevance"), 'desc');
                } elseif ($sort_field == 'simple_relevance') {
                    $query->order_by(DB::expr('`relevance`'), 'desc');
		} else {
		    $query->order_by($sort_field, 'asc');
		}

		$pagination = Input::param('pagination', '1');

		if ($pagination) {
		    $query->limit($this->limit)->offset(($page - 1) * $this->limit);
		}

		$malls = $query
		    ->as_object()
    		->execute();
		
		$malls_count = DB::count_last_query();
		$meta = array(
			'status'     => 1,
			'error'      => NULL,
		);

		// TODO: Check if the user has the appropiate permissions to bypass pagination
		if ($pagination) {
			$meta['pagination'] = $this->_pagination($malls_count, $page);
		}

        $malls_to_return = array();
        foreach ($malls as $mall) {
            $malls_to_return[$mall->id] = $mall;
            $malls_to_return[$mall->id]->merchant_count = 0; // we'll populate this info later
            if (empty($mall->latitude) || empty($mall->longitude)) {
                $malls_to_return[$mall->id]->status = 4; // status = blocked
            }
        }
        
        $mall_ids = count($malls_to_return) > 0 ? array_keys($malls_to_return) : array(-1);
        // query the merchant count for each mall and add it to the response
        $merchant_count_query = 
            DB::select('mall_id', DB::expr('COUNT(1) as count'))
                ->from(array('locations', 'l1'))
                ->where('mall_id', 'in', $mall_ids)
                ->group_by('mall_id');
        foreach ($merchant_count_query->as_object()->execute() as $count_query_item) {
            $malls_to_return[$count_query_item->mall_id]->merchant_count = $count_query_item->count;
        }

		if (Input::param('compact', '0')) {
		    $data = $this->_compact_response($malls_to_return);
		} else {
    		// Set the output
    		$data = array('data' => array('malls' => $malls_to_return), 'meta' => $meta);
		}
		$this->response($data);
	}

	/**
	 * Create a new mall
	 */
	public function action_post() {
		$exclude = array('id', 'created_at', 'updated_at', 'edited_by', 'created_by', 'status', 'setup_complete', 'user_instagram_id', 'is_customer');
		$properties = array_keys(Model_Mall::properties());
		
		$mall = new Model_Mall();

		foreach($properties as $property) {
			if (in_array($property, $exclude)) { continue; }
			$mall->$property = Input::post($property);
		}

		$mall->created_by = $mall->edited_by = $this->user_login->user_id; 
		$mall->status = 1;
		$mall->setup_complete = 0;
		$mall->manually_updated = 1;
        $mall->use_instagram = Input::post('use_instagram', 0);
		
        if (! ((float)Input::post('latitude') && (float)Input::post('longitude'))) {
		    return $this->_error_response(Code::ERROR_COORDINATES_REQUIRED);
		}

        $micello_info_params = Input::post('micello_info');
		if (!empty($micello_info_params['micello_id'])) {
		    // Check if the micello entity already exists
		    if (! $this->check_available_micello_id($micello_info_params['micello_id'])) {
		        return $this->_error_response(Code::ERROR_MICELLO_ID_ALREADY_EXISTS);
		    }
		    
			$mall->micello_info = new Model_Micello_Info();
			$mall->micello_info->micello_id = $micello_info_params['micello_id'];
			$mall->micello_info->type = Model_Micello_Info::TYPE_COMMUNITY;
			
			// Fetch merchants of the new Micello's Id and creates them on ShopSuey
			if (Input::post('sync_merchants')) {
				$dummy_community = new \stdClass();
				$dummy_community->id = $mall->micello_info->micello_id;
				\Helper_Micello::add_merchants_to_mall($dummy_community, $mall);
				
				if (Input::post('set_merchants_coords')) {
				    try {
				    	Helper_Micello::update_merchants_coordinates($mall);
				    } catch (Exception $e) {
				    	return $this->_error_response($e->getCode(), array('messages' => $e->getMessage()));
				    }
				}
			}
		}
		
		$phone = Input::post('phone', '');
		$valid = preg_match('/^([\+][0-9]{1,3}[ \.\-])?([\(]{1}[0-9]{2,6}[\)])?([0-9 \.\-\/]{3,20})((x|ext|extension)[ ]?[0-9]{1,4})?$/', $phone);
		if (!empty($phone) && !$valid) {
		    return $this->_error_response(Code::ERROR_WRONG_PHONE_FORMAT);
		}
        
        $website = Input::post('web', '');
		$valid = filter_var($website, FILTER_VALIDATE_URL);
	    if (!empty($website) && !$valid) {
            // let's see if adding 'http://', the website is now valid
            $valid = filter_var('http://' . $website, FILTER_VALIDATE_URL);
            if ($valid) {
                $mall->web = 'http://' . $website;
            } else {
                return $this->_error_response(Code::ERROR_INVALID_WEBSITE_URL);
            }
	    }
        
        $timezone = Input::post('timezone');
        if (Helper_Timezone::valid_timezone($timezone)) {
            $mall->timezone = $timezone;
        }
	    
        $facebook_page = Input::post('social.facebook', '');
		$valid = filter_var($facebook_page, FILTER_VALIDATE_URL) && (strpos($facebook_page, "facebook.com/") !== false);
	    if (!empty($facebook_page) && !$valid) {
            // let's see if adding 'http://', the website is now valid
            $valid = filter_var('http://' . $facebook_page, FILTER_VALIDATE_URL) && (strpos($facebook_page, "facebook.com/") !== false);;
            if ($valid) {
                $mall->social->facebook = 'http://' . $facebook_page;
            } else {
                return $this->_error_response(Code::ERROR_INVALID_FACEBOOK_URL);
            }
	    }
		
	    $category_ids = Input::post('category_ids', array());
	    foreach ($category_ids as $category_id) {
	        $mall->categories[] = Model_Category::find($category_id);
	    }
	    
            // Cleanup description
            $mall->description = Helper_Api::strip_tags($mall->description);

		if ($mall->save(NULL, TRUE)) {
			$output = $this->_build_response($mall);
		} else {
			$output = array('data' => Input::post(), 'meta' => array('error' => 'Unable to create mall', 'status' => 0));
		}

		$this->response($output);
	}

	private function check_available_micello_id($micello_id, $existing_micello_id = NULL) {
	    
	    $query = Model_Micello_Info::query()
    	    ->related('location')
	        ->where('micello_id', $micello_id)
    	    ->where('type', Model_Micello_Info::TYPE_COMMUNITY)
    	    ->where('location.status', '>', '0');
	    
	    if (!is_null($existing_micello_id)) {
	        $query->where('micello_id', '<>', $existing_micello_id);
	    }
	    
	    // Check if the micello id already exists
	    $micello_infos = $query->get();
	    
	    return count($micello_infos) == 0;
	}
	
	/**
	 * Update a mall
	 */
	public function action_put() {
                $exclude = array('id', 'created_at', 'updated_at', 'edited_by', 'created_by', 'setup_complete', 'user_instagram_id', 'is_customer');
		$properties = array_keys(Model_Mall::properties());

		$id = $this->param('id', 0);
		if (Input::put('set_merchants_coords')) {
		    $mall = Model_Mall::query()
//		    ->related('micello_info')
		    ->related('merchants')
		    ->related('merchants.micello_info')
		    ->where('id', $id)
		    ->get_one();
		} else {
		    $mall = Model_Mall::find($id);
		}
		
		if (!$mall) {
			$this->_error_response(Code::ERROR_INVALID_MALL_ID);
			return;
		}

		foreach($properties as $property) {
			if (in_array($property, $exclude)) { continue; }
			$mall->$property = Input::put($property);
		}

        $mall->use_instagram = Input::put('use_instagram', 0);
        
		$mall->edited_by = $this->user_login->user_id;

		if (! ((float)Input::put('latitude') && (float)Input::put('longitude'))) {
		    return $this->_error_response(Code::ERROR_COORDINATES_REQUIRED);
		}
        
		$micello_info_params = Input::put('micello_info');
        
		if (empty($micello_info_params['micello_id']) && $mall->micello_info) {
			$mall->micello_info->delete();
		} elseif (!empty($micello_info_params['micello_id']) && !$mall->micello_info) {
		    // Check if the micello entity already exists
		    if (! $this->check_available_micello_id($micello_info_params['micello_id'])) {
		    	return $this->_error_response(Code::ERROR_MICELLO_ID_ALREADY_EXISTS);
		    }
			$mall->micello_info = new Model_Micello_Info();
			$mall->micello_info->micello_id = $micello_info_params['micello_id'];
			$mall->micello_info->type = Model_Micello_Info::TYPE_COMMUNITY;
		} elseif (!empty($micello_info_params['micello_id']) && $mall->micello_info) {
		    // Check if the micello entity already exists
		    if (! $this->check_available_micello_id($micello_info_params['micello_id'], $mall->micello_info->micello_id)) {
		    	return $this->_error_response(Code::ERROR_MICELLO_ID_ALREADY_EXISTS);
		    }
		    if ($mall->micello_info->micello_id != $micello_info_params['micello_id']) {
			    $mall->micello_info->micello_id = $micello_info_params['micello_id'];
			    // Expire micello map
			    $mall->micello_info->map_expiracy = date('Y-m-d H:i:s', strtotime('-1 year'));
		    }
		}

		// Expire map info manually
		if (Input::put('clear_map_cache') && $mall->micello_info) {
		    $mall->micello_info->map_expiracy = date('Y-m-d H:i:s', strtotime('-1 year'));
		}
		
		if (Input::put('set_merchants_coords') && $mall->micello_info) {
		    try {
		        Helper_Micello::update_merchants_coordinates($mall);
		    } catch (Exception $e) {
		    	return $this->_error_response($e->getCode(), array('messages' => $e->getMessage()));
		    }
		}
		
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
                $mall->web = 'http://' . $website;
            } else {
                return $this->_error_response(Code::ERROR_INVALID_WEBSITE_URL);
            }
	    }
        
        $facebook_page = Input::put('social.facebook', '');
		$valid = filter_var($facebook_page, FILTER_VALIDATE_URL) && (strpos($facebook_page, "facebook.com/") !== false);
	    if (!empty($facebook_page) && !$valid) {
            // let's see if adding 'http://', the website is now valid
            $valid = filter_var('http://' . $facebook_page, FILTER_VALIDATE_URL) && (strpos($facebook_page, "facebook.com/") !== false);;
            if ($valid) {
                $mall->social->facebook = 'http://' . $facebook_page;
            } else {
                return $this->_error_response(Code::ERROR_INVALID_FACEBOOK_URL);
            }
	    }
        
        $timezone = Input::post('timezone', null);
        if (!is_null($timezone) && Helper_Timezone::valid_timezone($timezone)) {
            $mall->timezone = $timezone;
        }
		
	    $category_ids = Input::put('category_ids', array());
	    $category_ids_to_add    = array_diff($category_ids, array_keys($mall->categories));
	    $category_ids_to_remove = array_diff(array_keys($mall->categories), $category_ids);
	    foreach ($category_ids_to_remove as $category_id) {
	        unset($mall->categories[$category_id]);
	    }
	    foreach ($category_ids_to_add as $category_id) {
	        $mall->categories[] = Model_Category::find($category_id);
	    }
	    
            $profiling_ids = Input::put('profilings', array());
            $mall->profilings = array();
            foreach($profiling_ids as $profiling_id){
                $mall->profilings[] = Model_Profilingchoice::find($profiling_id);
            }
            
	    $mall->manually_updated = 1;
	     
            // Cleanup description
            $mall->description = Helper_Api::strip_tags($mall->description);
            
		if ($mall->save(NULL, TRUE)) {
			$output = $this->_build_response($mall);
                        
                        foreach($mall->profilings as $profilingChoice){
                            $profilingChoice->favorite_locations_by_users();
                        }
                        
		} else {
			$output = array('data' => Input::put(), 'meta' => array('error' => 'Unable to update mall', 'status' => 0));
		}

		$this->response($output);

	}
    
    public function action_delete_photo() {
        if (Input::method() != 'POST') { $this->response($this->no_access); return; }
        
        $id = $this->param('id');
		if (empty($id)) {
			$this->_error_response(Code::ERROR_INVALID_MALL_ID);
			return;
		}
        
		$mall = Model_Mall::find($id);
        $user = $this->user_login->user;
        
        if (!$user->is_admin()) {
		    if (!in_array($id, array_keys($this->user_login->user->get_assigned_companies()))) {
		        $this->_error_response(Code::ERROR_INVALID_LOCATION_ID);
		        return;
		    }
		}
        
        $image_name = $this->populate_images_with_default_image($mall, $this->param('type'));
        
        if ($mall->save()) {
            $path = ($this->param('type') == 'logo') ? 'logo_images_path' : 'landing_images_path';
            $complete_url = Asset::get_file($image_name, 'img', Config::get('cms.' . $path));
            return $this->response(array(
                'data' => array('status' => true, 'default_image' => $complete_url, 'default_image_name' => $image_name),
                'meta' => array('error' => '', 'status' => 1)
            ));
        }
        
        return $this->_error_response(Code::ERROR_SAVING_OFFER);
    }
    
    private function populate_images_with_default_image($mall, $type) {
        // Copy default image from site assets
        $default_image = 'default-logo.png';
        //Fuel\Core\Config::load('asset'); Somthing strange occurs when I uncomment this line
        
        $image_dir = 'images/'; //Fuel\Core\Config::get('img_dir');
        $asset_dir = 'assets/'; //Fuel\Core\Config::get('paths');
        
        $default_image_path = DOCROOT . $asset_dir . $image_dir . $default_image;

        if ($type == 'logo') {
            $image_name = Helper_Images_Logos::copy_image_from_path($default_image_path);
            $mall->logo = $image_name;
        } else {
            $image_name = Helper_Images_Landing::copy_image_from_path($default_image_path);
            $mall->landing_screen_img = $image_name;
        }
        
        return $image_name;
    }

	public function action_merchants_count() {
	    if (Input::method() != 'GET') { $this->response($this->no_access); return; }

	    // The id of the mall/standalone merchant
	    $id = $this->param('id', 0);
	    $mall = Model_Mall::find($id);

	    // Only malls and standalone merchants are allowed
	    if (!$mall) {
	        return $this->_error_response(Code::ERROR_INVALID_MALL_ID);
	    }

	    // Get merchants count
	    $merchants_count = Model_Merchant::query()
	    ->where('status', 1)
	    ->where('mall_id', $mall->id)
	    ->count();

	    // Set the output
	    $meta = array(
            'status' => 1,
            'error'  => null,
	    );
	    $data = array('data' => array('count' => $merchants_count), 'meta' => $meta);
	    $this->response($data);
	}

	private function _compact_response($malls) {
	    $compact_malls = array();
	    foreach($malls as $mall) {
    	    $entry = array();
    	    $entry['value'] = $entry['id'] = $mall->id;
    	    $entry['name'] = "{$mall->name} [{$mall->type}, {$mall->city}]";
    	    $entry['address'] = $mall->address;
    	    $entry['city'] = $mall->city;
    	    $entry['st'] = $mall->st;
    	    $entry['zip'] = $mall->zip;
    	    $entry['email'] = $mall->email;
    	    $entry['web'] = $mall->web;
    	    $entry['description'] = $mall->description;
    	    $compact_malls[] = $entry;
	    }
	    return $compact_malls;
	}
	
	private function _build_response($mall) {
        $offers_count = $this->_get_offers_for_locations(array_merge(array($mall), $mall->merchants), false, false, true);
        $events_count = $this->_get_events_for_locations(array_merge(array($mall), $mall->merchants), false, false, true);

        $favorites = $this->user_login->user->get_favorite_locations_ids();
        $mall = Helper_Api::location_response($mall, $favorites, true, true);
        
        // get the merchant count and add it to the response
        $merchant_count_query = 
            DB::select(DB::expr('COUNT(1) as count'))
                ->from(array('locations', 'l1'))
                ->where('mall_id', $mall->id)
                ->where('status', 1);
        
        $query_result = $merchant_count_query->execute();
        $mall->merchant_count = (int)$query_result->get('count');
        $mall->offers_count = $offers_count;
        $mall->events_count = $events_count;
        
		return array(
			'data' => array('mall' => $mall),
			'meta' => array('error' => null, 'status' => 1),
		);
	}
	
	private function create_hours_groups($mall) {
	    //$mall = json_decode(json_encode($mall)); //very primitive and disgusting cast to stdClass, sorry!
	    $groups = array(
	        array('days' => array(), 'open' => '', 'close' => ''),
	        array('days' => array(), 'open' => '', 'close' => ''),
	        array('days' => array(), 'open' => '', 'close' => ''),
	    );
	
	    if (!isset($mall->hours) || empty($mall->hours)) {
	        return $groups;
	    }
	
	    $hours = $mall->hours;
	
	    $days = array('mon', 'tue', 'wed', 'thr', 'fri', 'sat', 'sun');
	    foreach ($days as $day) {
	        $found = false;
	        foreach ($groups as $k => $group) {
	            if ($hours->$day->open && $hours->$day->close && $group['open'] == $hours->$day->open && $group['close'] == $hours->$day->close) {
	                $groups[$k]['days'][] = $day;
	                $found = true;
	                break;
	            }
	        }
	
	        if (!$found) {
	            foreach ($groups as $k => $group) {
	                if (!count($group['days']) && $hours->$day->open && $hours->$day->close) {
	                    $groups[$k]['days'][] = $day;
	                    $groups[$k]['open'] = $hours->$day->open;
	                    $groups[$k]['close'] = $hours->$day->close;
	                    break;
	                }
	            }
	        }
	    }
	    return $groups;
	
	}
	
	/**
	 * Update merchants within a mall
	 */
	public function action_update_merchants() {
		$id = $this->param('id', 0);
		
		$mall = Model_Mall::query()
		    ->related('merchants')
		    ->related('merchants.micello_info')
		    ->where('id', $id)
		    ->get_one();
	
		if (!$mall) {
			$this->_error_response(Code::ERROR_INVALID_MALL_ID);
			return;
		}

		$fields = array('name', 'entity_id', 'geometry_id', 'floor', 'phone', 'email', 'url', 'description');
		
		$merchant_keys = \Input::post('imports');
		$merchants_to_update = array();

		foreach($merchant_keys as $merchant_key) {
		    $merchant_key_parts = explode('_', $merchant_key);
		    
		    $process_merchant = \Input::post("process_$merchant_key", FALSE);
		    if (! $process_merchant) {
		        continue;
		    }

		    $merchant_id = $merchant_key_parts[1];

		    $fields_to_update = array();
		    foreach($fields as $field) {
		        $update_field = \Input::post("update_{$field}_{$merchant_key}", FALSE);
		        if ($update_field) {
		            $fields_to_update[$field] = \Input::post("{$field}_{$merchant_key}");
		        }
		    }

		    $update_type = \Input::post("type_$merchant_key");

		    if (count($fields_to_update) == 0 && $update_type != 'additional_location') {
		        continue;
		    }

		    if ($update_type == 'match') {
		        $merchant = $mall->merchants[$merchant_id];
	            $this->update_merchant($merchant, $fields_to_update);

		    } elseif ($update_type == 'additional_location') {
		        $merchant_to_delete = $mall->merchants[$merchant_id];
                $merchant_to_delete->status = \Model_Location::STATUS_DELETED;

	        } elseif ($update_type == 'new_entity') {
	            $new_merchant = \Helper_Micello::create_merchant(NULL, $mall);
                $this->update_merchant($new_merchant, $fields_to_update);
                $mall->merchants[] = $new_merchant;
		    }
		}

		try {
    		// Update merchants coordinates
		    Helper_Micello::update_merchants_coordinates($mall);
		} catch (Exception $e) {
			return $this->_error_response($e->getCode(), array('messages' => $e->getMessage()));
		}
		
		// Save the mall using a transaction to ensure that all merchants get properly saved
		if ($mall->save(NULL, TRUE)) {
		    $meta = array(
	    		'status' => 1,
	    		'error'  => null,
		    );
		    $data = array('data' => array('success' => TRUE), 'meta' => $meta);
		} else {
		    $meta = array(
	    		'status' => 0,
	    		'error'  => 'Unable to create/update merchants',
		    );
		    $data = array('data' => NULL, 'meta' => $meta);
		}

		$this->response($data);
	}
	
	private function update_merchant($merchant, $data) {
	    if (isset($data['name'])) {
	        $merchant->name = $data['name']; 
	    }
	    if (isset($data['floor'])) {
	        $merchant->floor = $data['floor']; 
	    }
	    if (isset($data['phone'])) {
	        $merchant->phone = $data['phone']; 
	    }
	    if (isset($data['email'])) {
	        $merchant->email = $data['email']; 
	    }
	    if (isset($data['url'])) {
	        $merchant->web = $data['url']; 
	    }
	    if (isset($data['description'])) {
	        $merchant->description = $data['description']; 
	    }
	    if (isset($data['entity_id'])) {
	        $merchant->micello_info->micello_id = $data['entity_id']; 
	    }
	    if (isset($data['geometry_id'])) {
	        $merchant->micello_info->geometry_id = $data['geometry_id']; 
	    }
	}
}
