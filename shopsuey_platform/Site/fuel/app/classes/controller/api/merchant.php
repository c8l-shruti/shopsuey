<?php

/**
 * The Api Controller.
 * This controllers the CRUD proceedures for merchants
 *
 * @package  app
 * @extends  Controller_Rest
 */

class Controller_Api_Merchant extends Controller_Api {

	/**
	 * Get a single merchant
	 */
	public function action_get() {
		
		$id = $this->param('id', 0);
                $merchant = Model_Merchant::query()->where('id', $id)->get_one();
                
		if ($merchant) {
			$this->response($this->_build_response($merchant, true));
		} else {
			$this->_error_response(Code::ERROR_INVALID_MERCHANT_ID);
		}
	}

	/**
	 * Get 25 merchants with pagination meta
	 */
	public function action_list() {
		$page   = $this->param('page', 1);
		$string = $this->param('string', Input::param('string', ''));

        if (!$this->user_login->user->is_admin()) {
            $query = Model_Merchant::query()->related('mall')->where('status', '>', 0);
        } else {
            $query = Model_Merchant::query()->related('mall');
        }

		if (!empty($string)) {
			$search_fields = array('name','address','city','st','zip','email','web','description');
			$query->and_where_open();
			foreach ($search_fields as $field) {
				$query->or_where($field, 'like', "%$string%");
			}
			$query->and_where_close();
		}
		
		$count = $query->count();
		$meta = array(
			'pagination' => $this->_pagination($count, $page),
			'status'     => 1,
			'error'      => NULL,
		);
		
        $sort_field = Input::param('order_by', 'name');
        if (!in_array($sort_field, array('name', 'created_at', 'mall.name', 'status'))) {
            $sort_field = 'name';
        }
        
		$merchants = $query
			->order_by($sort_field, 'asc')
			->rows_limit($meta['pagination']['limit'])
			->rows_offset($meta['pagination']['offset']['current'])
			->get();
        
        foreach ($merchants as $k => $merchant) {
            $merchants[$k] = Helper_Api::model_to_real_object($merchant);
            if ($merchant->mall) {
                $merchants[$k]->mall_name = $merchant->mall->name;
                $merchants[$k]->mall_city = $merchant->mall->city;
            } else {
                $merchants[$k]->mall_name = '';
                $merchants[$k]->mall_city = '';
            }
            if (empty($merchant->latitude) || empty($merchant->longitude)) {
                $merchants[$k]->status = 4; // status = blocked
            }
        }

		$data = array('data' => array('merchants' => $merchants), 'meta' => $meta);
		$this->response($data);
	}

	/**
	 * Create a new merchant
	 */
	public function action_post() {
                $exclude = array('id', 'created_at', 'updated_at', 'created_by', 'edited_by', 'status', 'setup_complete', 'user_instagram_id', 'is_customer');
		$properties = array_keys(Model_Merchant::properties());

		$merchant = new Model_Merchant();

		foreach($properties as $property) {
			if (in_array($property, $exclude)) { continue; }
			$merchant->$property = Input::post($property);
		}

		$merchant->created_by = $merchant->edited_by = $this->user_login->user_id;
		$merchant->status = 1;
		$merchant->setup_complete = 0;
		$merchant->manually_updated = 1;
        $merchant->use_instagram = Input::post('use_instagram', 0);
        $merchant->floor = (int)$merchant->floor;
		
		$micello_info_params = Input::post('micello_info', array('micello_id' => '', 'geometry_id' => ''));

	    $merchant->micello_info = new Model_Micello_Info();
	    $merchant->micello_info->micello_id = $micello_info_params['micello_id'];
	    if (! Input::post('mall_id')) {
	        $merchant->micello_info->type = Model_Micello_Info::TYPE_COMMUNITY;
    	    // Check if the micello entity already exists
    	    if (! $this->check_available_micello_id($micello_info_params['micello_id'])) {
    	    	return $this->_error_response(Code::ERROR_MERCHANT_MICELLO_ID_ALREADY_EXISTS);
    	    }
	    } else {
	        $merchant->micello_info->type = Model_Micello_Info::TYPE_ENTITY;
	        $merchant->micello_info->geometry_id = $micello_info_params['geometry_id'];
	        // Check if the micello entity already exists
	        if (! $this->check_available_geometry_id($micello_info_params['geometry_id'])) {
	        	return $this->_error_response(Code::ERROR_MERCHANT_GEOMETRY_ID_ALREADY_EXISTS);
	        }
	    }
        
		if (Input::post('mall_id') && empty($micello_info_params['geometry_id'])) {
		    return $this->_error_response(Code::ERROR_MERCHANT_REQUIRES_MICELLO_INFO);
		}

		if (! Input::post('mall_id') && ! ((float)Input::post('latitude') && (float)Input::post('longitude'))) {
		    return $this->_error_response(Code::ERROR_COORDINATES_REQUIRED);
		}

		if (Input::post('mall_id')) {
		    $mall = Model_Mall::find(Input::post('mall_id'));
		    if (!$mall) {
		        return $this->_error_response(Code::ERROR_INVALID_MALL_ID);
		    }
		    // Force merchants inside a mall to have coordinates if not set
		    if (! (float)Input::post('latitude')) {
		        $merchant->latitude = $mall->latitude;
		    }
            if (! (float)Input::post('longitude')) {
		        $merchant->longitude = $mall->longitude;
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
                $merchant->web = 'http://' . $website;
            } else {
                return $this->_error_response(Code::ERROR_INVALID_WEBSITE_URL);
            }
	    }
        
        $facebook_page = Input::post('social.facebook', '');
		$valid = filter_var($facebook_page, FILTER_VALIDATE_URL) && (strpos($facebook_page, "facebook.com/") !== false);
	    if (!empty($facebook_page) && !$valid) {
            // let's see if adding 'http://', the website is now valid
            $valid = filter_var('http://' . $facebook_page, FILTER_VALIDATE_URL) && (strpos($facebook_page, "facebook.com/") !== false);;
            if ($valid) {
                $merchant->social->facebook = 'http://' . $facebook_page;
            } else {
                return $this->_error_response(Code::ERROR_INVALID_FACEBOOK_URL);
            }
	    }
        
        $timezone = Input::post('timezone');
        if (Helper_Timezone::valid_timezone($timezone)) {
            $merchant->timezone = $timezone;
        }
	    
	    $category_ids = Input::post('category_ids', array());
	    foreach ($category_ids as $category_id) {
	        $merchant->categories[] = Model_Category::find($category_id);
	    }

            // Cleanup description
            $merchant->description = Helper_Api::strip_tags($merchant->description);

		if ($merchant->save()) {
			// TODO: Check this
// 			$note = Input::post('description', '');
// 			if ($note) { CMS::comment($this->user_login->user_id, $merchant->id, 'merchant', $note); }
			$output = $this->_build_response($merchant);
		} else {
			$output = array('data' => Input::post(), 'meta' => array('error' => 'Unable to create merchant', 'status' => 0));
		}
		
		$this->response($output);
	}

	/**
	 * Update a merchant
	 */
	public function action_put() {
                $exclude = array('id', 'created_at', 'updated_at', 'created_by', 'edited_by', 'setup_complete', 'user_instagram_id', 'is_customer');
		$properties = array_keys(Model_Merchant::properties());
		
		$id = $this->param('id', 0);
		$merchant = Model_Merchant::find($id);
		
		if (!$merchant) {
			$this->_error_response(Code::ERROR_INVALID_MERCHANT_ID);
			return;
		}
		
		foreach($properties as $property) {
			if (in_array($property, $exclude)) { continue; }
			$merchant->$property = Input::put($property);
		}

        $merchant->use_instagram = Input::put('use_instagram', 0);
        $merchant->floor = (int)$merchant->floor;
		
		$merchant->edited_by = $this->user_login->user_id;

		if (! Input::put('mall_id') && ! ((float)Input::put('latitude') && (float)Input::put('longitude'))) {
		    return $this->_error_response(Code::ERROR_COORDINATES_REQUIRED);
		}
        
		$micello_info_params = Input::put('micello_info', array('micello_id' => '', 'geometry_id' => ''));
		
		if (Input::put('mall_id') && empty($micello_info_params['geometry_id'])) {
		    return $this->_error_response(Code::ERROR_MERCHANT_REQUIRES_MICELLO_INFO);
		}

		if (Input::put('mall_id')) {
		    $mall = Model_Mall::find(Input::put('mall_id'));
		    if (!$mall) {
		        return $this->_error_response(Code::ERROR_INVALID_MALL_ID);
		    }
		    // Force merchants inside a mall to have coordinates if not set
		    if (! (float)Input::put('latitude')) {
		        $merchant->latitude = $mall->latitude;
		    }
		    if (! (float)Input::put('longitude')) {
		        $merchant->longitude = $mall->longitude;
		    }
		}
		
		if (!$merchant->micello_info) {
			$merchant->micello_info = new Model_Micello_Info();
		}
		if (! Input::put('mall_id')) {
			$merchant->micello_info->type = Model_Micello_Info::TYPE_COMMUNITY;
			$merchant->micello_info->geometry_id = NULL;
			$previous_micello_id = empty($merchant->micello_info->micello_id) ? NULL : $merchant->micello_info->micello_id;
			// Check if the micello entity already exists
			if (! $this->check_available_micello_id($micello_info_params['micello_id'], $previous_micello_id)) {
				return $this->_error_response(Code::ERROR_MERCHANT_MICELLO_ID_ALREADY_EXISTS);
			}
		} else {
			$merchant->micello_info->type = Model_Micello_Info::TYPE_ENTITY;
			$previous_geometry_id = empty($merchant->micello_info->geometry_id) ? NULL : $merchant->micello_info->geometry_id;
			$merchant->micello_info->geometry_id = $micello_info_params['geometry_id'];
			// Check if the micello entity already exists
			if (! $this->check_available_geometry_id($micello_info_params['geometry_id'], $previous_geometry_id)) {
				return $this->_error_response(Code::ERROR_MERCHANT_GEOMETRY_ID_ALREADY_EXISTS);
			}
		}
		$merchant->micello_info->micello_id = $micello_info_params['micello_id'];

		$phone = Input::put('phone', '');
		$valid = preg_match('/^([\+][0-9]{1,3}[ \.\-])?([\(]{1}[0-9]{2,6}[\)])?([0-9 \.\-\/]{3,20})((x|ext|extension)[ ]?[0-9]{1,4})?$/', $phone);
		if (!empty($phone) && !$valid) {
		    return $this->_error_response(Code::ERROR_WRONG_PHONE_FORMAT);
		}
        
        $timezone = Input::post('timezone', null);
        if (!is_null($timezone) && Helper_Timezone::valid_timezone($timezone)) {
            $merchant->timezone = $timezone;
        }
        
        $website = Input::put('web', '');
		$valid = filter_var($website, FILTER_VALIDATE_URL);
	    if (!empty($website) && !$valid) {
            // let's see if adding 'http://', the website is now valid
            $valid = filter_var('http://' . $website, FILTER_VALIDATE_URL);
            if ($valid) {
                $merchant->web = 'http://' . $website;
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
                $merchant->social->facebook = 'http://' . $facebook_page;
            } else {
                return $this->_error_response(Code::ERROR_INVALID_FACEBOOK_URL);
            }
	    }
        
	    $category_ids = Input::put('category_ids', array());
	    $category_ids_to_add    = array_diff($category_ids, array_keys($merchant->categories));
	    $category_ids_to_remove = array_diff(array_keys($merchant->categories), $category_ids);
	    foreach ($category_ids_to_remove as $category_id) {
	        unset($merchant->categories[$category_id]);
	    }
	    foreach ($category_ids_to_add as $category_id) {
	        $merchant->categories[] = Model_Category::find($category_id);
	    }
        
            
            $profiling_ids = Input::put('profilings', array());
            $merchant->profilings = array();
            foreach($profiling_ids as $profiling_id){
                $merchant->profilings[] = Model_Profilingchoice::find($profiling_id);
            }
            
	    $merchant->manually_updated = 1;

            // Cleanup description
            $merchant->description = Helper_Api::strip_tags($merchant->description);

		if ($merchant->save()) {
			// TODO: Check this
// 			$type = 'merchant';
// 			$note = Input::post('description', '');
// 			if (!$note) { $note = '<small><em>Updated by '.$this->user_login->user->email.'</em></small>'; $type = 'merchant_edit'; }
// 			CMS::comment($data['edited_by'], $id, $type, $note, $old, $new);
			$output = $this->_build_response($merchant);
                        
                        //PROPAGATE THESE CHANGES - FAVORITE LOCATIONS FOR USERS THAT FAVORITED THE SELECTED PROFILINGS
                        
                        foreach($merchant->profilings as $profilingChoice){
                            
                            
                            $profilingChoice->favorite_locations_by_users();
                        }
                        
		} else {
			$output = array('data' => Input::put(), 'meta' => array('error' => 'Unable to update merchant', 'status' => 0));
		}

		$this->response($output);
	}
	
	public function action_logos() {
	    if (Input::method() != 'GET') { $this->response($this->no_access); return; }
	    
	    $merchant_name = substr(Input::get('merchant_name'), 0, 3);
	    $logos = DB::select('logo')->from('locations')
            	    ->where('type', 'Merchant')->where('logo', '!=','')
            	    ->where('name', 'LIKE', $merchant_name.'%')
            	    ->distinct()->execute();
	    
	    $data = array();
	    foreach ($logos as $logo) {
	        $data[] = array(
	            'logoName' => $logo['logo'],
	            'logoUrl' => Asset::get_file('small_'.@$logo['logo'], 'img', Config::get('cms.logo_images_path'))
	        );
	    }
	    
	    $this->response($data);
	}
	
	public function action_images() {
	    if (Input::method() != 'GET') { $this->response($this->no_access); return; }
	     
	    $merchant_name = substr(Input::get('merchant_name'), 0, 3);
	    $images = DB::select('landing_screen_img')->from('locations')
            	    ->where('type', 'Merchant')->where('landing_screen_img', '!=','')
            	    ->where('name', 'LIKE', $merchant_name.'%')
            	    ->distinct()->execute();

	    $data = array();
	    foreach ($images as $image) {
	        $data[] = array(
	            'imgName' => $image['landing_screen_img'],
	            'imgUrl' => Asset::get_file('small_'.@$image['landing_screen_img'], 'img', Config::get('cms.landing_images_path'))
	        );
	    }
	     
	    $this->response($data);
	}
    
    public function action_delete_photo() {
        if (Input::method() != 'POST') { $this->response($this->no_access); return; }
        
        $id = $this->param('id');
		if (empty($id)) {
			$this->_error_response(Code::ERROR_INVALID_MERCHANT_ID);
			return;
		}
        
		$mall = Model_Merchant::find($id);
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
    
	private function check_available_micello_id($micello_id, $existing_micello_id = NULL) {
	    return $this->check_available_micello_id_query('micello_id', $micello_id, Model_Micello_Info::TYPE_COMMUNITY, $existing_micello_id);
	}

	private function check_available_geometry_id($geometry_id, $existing_geometry_id = NULL) {
		return $this->check_available_micello_id_query('geometry_id', $geometry_id, Model_Micello_Info::TYPE_ENTITY, $existing_geometry_id);
	}
	
	private function check_available_micello_id_query($field, $micello_id, $type, $existing_micello_id = NULL) {

	    if (empty($micello_id)) {
	        return TRUE;
	    }

		$query = Model_Micello_Info::query()
    		->related('location')
    		->where($field, $micello_id)
    		->where('type', $type)
    		->where('location.status', '>', '0');
		 
		if (!is_null($existing_micello_id)) {
			$query->where($field, '<>', $existing_micello_id);
		}
		 
		// Check if the micello id already exists
		$micello_infos = $query->get();
		 
		return count($micello_infos) == 0;
	}
	
	private function _build_response($merchant, $offers_and_events_count = false) {
        if ($offers_and_events_count) {
            $offers_count = $this->_get_offers_for_locations($merchant, false, false, true);
            $events_count = $this->_get_events_for_locations($merchant, false, false, true);
        }

        $favorites = $this->user_login->user->get_favorite_locations_ids();
        $response = Helper_Api::location_response($merchant, $favorites, true, true);

        if ($offers_and_events_count) {
            $response->offers_count = $offers_count;
            $response->events_count = $events_count;
        }

        return array(
				'data' => array('merchant' => $response),
				'meta' => array('error' => null, 'status' => 1),
		);
	}

}
