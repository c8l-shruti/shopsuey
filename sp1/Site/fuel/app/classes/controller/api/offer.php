<?php

/**
 * The Api Controller.
 * This controllers the CRUD proceedures for offers
 *
 * @package  app
 * @extends  Controller_Rest
 */

class Controller_Api_Offer extends Controller_Api {

	// The relative times to consider an offer as upcoming or expiring soon
	const UPCOMING_TIME      = '+1 years';
	const EXPIRING_SOON_TIME = '+2 days';
	// The distance to consider a store to be nearby (in miles)
	const DEFAULT_NEARBY_DISTANCE = '5';

	/**
	 * Get a single offer
	 */
	public function action_get() {
            
		if (Input::method() != 'GET') { $this->response($this->no_access); return; }

		$id = $this->param('id');
		$meta = array('error' => null, 'status' => 1);

		$offer_model = Model_Offer::query()->related('locations')->related('locations.micello_info')->where('status', '>', 0)->where('id', $id)->get_one();

		if ($offer_model) {
			$offer = Helper_Api::offer_response($offer_model);

			// Check if the offer's retailer is blocked for the current user
			$blocking = NULL;
			if (count($offer->locations) > 0) {
				$current_date = date('Y-m-d H:i:s');
				$blocking = Model_Location_Blocking::query()
				->where('user_id', $this->user_login->user_id)
				->where('location_id', 'in', array_keys($offer->locations))
				->where('start_date', '<=', $current_date)
				->where('end_date', '>=', $current_date)
				->get_one();
			}
			$offer->blocked = $blocking ? TRUE : FALSE;

			// Add the info of redeems for this offer
			$sub_query = Model_Offer_Code::query()
				->select('id')
				->where('offer_id', $id);
			$redeems_query = Model_Offer_Redeem::query()
				->related('offer_code')
				->where('user_id', $this->user_login->user_id)
				->where('offer_code_id', 'in', $sub_query->get_query())
				->order_by('date', 'desc');

			$offer->times_redeemed = $redeems_query->count();
			$offer->last_redeem = Helper_Api::redeem_response($redeems_query->get_one());
            $offer->like_status = $this->user_login->user->get_like_message_status('offer', $offer->id);

			if (Input::param('include_offer_codes', 0)) {
				$offer->offer_codes = $offer_model->offer_codes;
			}

			// Set the output
			$data = array('data' => array('offer' => $offer), 'meta' => $meta);
		} else {
			$data = array('data' => null, 'meta' => Helper_Api::build_error_meta(Code::ERROR_INVALID_OFFER_ID));
		}

		$this->response($data);
	}

	/**
	 * Create a new offer
	 */
	public function action_post() {
		if (Input::method() != 'POST') { $this->response($this->no_access); return; }

		$exclude_fields = array('id', 'created_at', 'updated_at', 'created_by', 'edited_by');

		$new_offer = Model_Offer::forge(Input::post());
		$new_offer->gallery = Helper_Images_Offers::copy_images_from_params(Input::post());
		$new_offer->created_by = $new_offer->edited_by = $this->user_login->user_id;
		$new_offer->status = 1;
		$new_offer->date_start = date('Y-m-d H:i:s', $new_offer->date_start);
		$new_offer->date_end = date('Y-m-d H:i:s', $new_offer->date_end);
        $new_offer->force_top_message = 0;

		$location_ids = Input::post('location_ids', array());
		$all_locations = Input::post('all_locations');

		if ($all_locations) {
		    $location_ids = array_keys($this->user_login->user->get_assigned_companies());
		}

		if (count($location_ids) == 0) {
		    $this->_error_response(Code::ERROR_NO_LOCATION_FOR_OFFER);
		    return;
		}

		$selected_location_ids = $location_ids;
		foreach($selected_location_ids as $location_id) {
		    if (Input::post("include_merchants_$location_id")) {
		        $merchants = Model_Location::query()->where('status', '1')->where('mall_id', $location_id)->get();
		        $location_ids = array_merge($location_ids, array_keys($merchants));
		    }
		}

        if (!$this->user_login->user->is_admin()) {
            if (count(array_diff($location_ids, array_keys($this->user_login->user->get_assigned_companies()))) > 0) {
                $this->_error_response(Code::ERROR_INVALID_LOCATION_ID);
                return;
            }
		}

		foreach ($location_ids as $location_id) {
			$new_offer->locations[] = Model_Location::find($location_id);
		}

        $gallery_remove = \Fuel\Core\Input::post('gallery_remove', array());        
        if (empty($gallery_remove) && !is_null(\Fuel\Core\Input::post('deleted_image', null)) && (bool)\Fuel\Core\Input::post('deleted_image')) {
            $this->populate_images_with_default_image($new_offer);
        }
        
        if (empty($new_offer->gallery) || !empty($gallery_remove)) {
            $this->populate_images_from_location($new_offer);
        }

		// TODO: Determine what's the purpose of this field
		$new_offer->categories = '';

		if ($new_offer->redeemable) {
		    if ($new_offer->multiple_codes && empty($new_offer->default_code_type)) {
		        return $this->_error_response(Code::ERROR_DEFAULT_OFFER_CODE_TYPE_REQUIRED);
		    } elseif (! $new_offer->multiple_codes) {
		        // Create a unique offer code
		        $offer_code = new Model_Offer_Code();
		        $offer_code->auto_generated = FALSE;
		        $offer_code->status = 1;
		        $offer_code->type = Input::post('offer_code_type');
		        $offer_code->code = Input::post('offer_code');

		        if (! $offer_code->is_valid_code()) {
		            $this->_error_response(Code::ERROR_INVALID_OFFER_CODE);
		            return;
		        }

		        $new_offer->offer_codes[] = $offer_code;
		    }
		}

		// Cleanup description
		$new_offer->description = Helper_Api::strip_tags($new_offer->description);

		try {
    		if ($new_offer->save()) {
    			// TODO: Move this out of the CMS class
    			$note = '<small><em>Created by '.$this->user_login->user->email.'</em></small>';
    			$type = 'offer_edit';
    			CMS::comment($this->user_login->user_id, $new_offer->id, $type, $note, '', $new_offer);
    			$output= array('data' => array('offer' => $new_offer), 'meta' => array('error' => null, 'status' => 1));
    		} else {
    			$output = array('data' => Input::post(), 'meta' => array('error' => 'Unable to create offer', 'status' => 0));
    		}
		} catch (Orm\ValidationFailed $e) {
		    $output = array('data' => Input::post(), 'meta' => array('error' => join(' / ', $e->get_fieldset()->error()), 'status' => 0));
		}

		$this->response($output);
	} // ---> action_post()

	/**
	 * Update a offer
	 */
	public function action_put() {
		if (Input::method() != 'PUT') { $this->response($this->no_access); return; }

		$exclude_fields = array('id', 'created_at', 'updated_at', 'created_by', 'edited_by');

		$id = $this->param('id');
		if (empty($id)) {
			$this->_error_response(Code::ERROR_INVALID_OFFER_ID);
			return;
		}

		$params = Input::put();

		$offer = Model_Offer::find($id);

		$images_copied = Helper_Images_Offers::copy_images_from_params($params);

        // Check if there are images to add
		if(!empty($images_copied)) {
		    $params['gallery'] = $images_copied;
		} else if ((!isset($params['gallery']) || is_null($params['gallery'])) && empty($offer->gallery)) {
            $params['gallery'] = array();
		}

		// Check if there are images to remove
		if (isset($params['gallery_remove']) && !empty($params['gallery_remove'])) {
		    $remove = is_array($params['gallery_remove']) ? $params['gallery_remove'] : array($params['gallery_remove']);
		    Helper_Images_Offers::delete_images($remove);
		    $params['gallery'] = array();
		}
        
		$location_ids = Input::put('location_ids', array());
		$all_locations = Input::put('all_locations');

		if ($all_locations) {
			$location_ids = array_keys($this->user_login->user->get_assigned_companies());
		}

		if (count($location_ids) == 0) {
		    $this->_error_response(Code::ERROR_NO_LOCATION_FOR_OFFER);
		    return;
		}
        
		$selected_location_ids = $location_ids;
		foreach($selected_location_ids as $location_id) {
			if (Input::put("include_merchants_$location_id")) {
				$merchants = Model_Location::query()->where('status', '1')->where('mall_id', $location_id)->get();
				$location_ids = array_merge($location_ids, array_keys($merchants));
			}
		}

		if (!$this->user_login->user->is_admin()) {
		    if (count(array_diff($location_ids, array_keys($this->user_login->user->get_assigned_companies()))) > 0) {
		        $this->_error_response(Code::ERROR_INVALID_LOCATION_ID);
		        return;
		    }
		}

		$location_ids_to_add    = array_diff($location_ids, array_keys($offer->locations));
		$location_ids_to_remove = array_diff(array_keys($offer->locations), $location_ids);
		foreach ($location_ids_to_remove as $location_id) {
			unset($offer->locations[$location_id]);
		}
		foreach ($location_ids_to_add as $location_id) {
			$offer->locations[] = Model_Location::find($location_id);
		}

		foreach ($exclude_fields as $field) {
			unset($params[$field]);
		}

        if (isset($params['gallery'])) {
            $offer->gallery = $params['gallery'];
            if (empty($offer->gallery)) {
                $this->populate_images_from_location($offer);
                $params['gallery'] = $offer->gallery;
            }
        }

		$offer->from_array($params);
		$offer->edited_by = $this->user_login->user_id;

		$offer->date_start = date('Y-m-d H:i:s', $offer->date_start);
		$offer->date_end = date('Y-m-d H:i:s', $offer->date_end);

		if ($offer->redeemable) {
		    if ($offer->multiple_codes && empty($offer->default_code_type)) {
		        return $this->_error_response(Code::ERROR_DEFAULT_OFFER_CODE_TYPE_REQUIRED);
		    } elseif (! $offer->multiple_codes) {
		        // Handle unique offer code
		        $offer_code = NULL;
		        // Search the first non auto generated code (if any)
		        foreach($offer->offer_codes as $code) {
		            if (! $code->auto_generated) {
		                $offer_code = $code;
		                break;
		            }
		        }

		        if (is_null($offer_code)) {
    		        $offer_code = new Model_Offer_Code();
    		        $offer_code->auto_generated = FALSE;
    		        $offer_code->status = 1;
		            $offer->offer_codes[] = $offer_code;
		        }
		        $offer_code->type = Input::put('offer_code_type');
		        $offer_code->code = Input::put('offer_code');

		        if (! $offer_code->is_valid_code()) {
		            $this->_error_response(Code::ERROR_INVALID_OFFER_CODE);
		            return;
		        }
		    }
		}

		// Cleanup description
		$offer->description = Helper_Api::strip_tags($offer->description);

		try {
    		if ($offer->save()) {
    		    $note = '<small><em>Updated by '.$this->user_login->user->email.'</em></small>';
    		    $type = 'offer_edit';
    		    CMS::comment($this->user_login->user_id, $id, $type, $note, $offer, $offer);
    				$output= array('data' => array('offer' => $offer), 'meta' => array('error' => null, 'status' => 1));
    		} else {
    		    $output = array('data' => $params, 'meta' => array('error' => 'Unable to update offer', 'status' => 0, 'qry' => DB::last_query()));
    		}
		} catch (Orm\ValidationFailed $e) {
		    $output = array('data' => $params, 'meta' => array('error' => join(' / ', $e->get_fieldset()->error()), 'status' => 0));
		}

		$this->response($output);
	}

	/**
	 * Bookmark an offer
	 */
	public function action_bookmark() {
		$id = Input::param('id', $this->param('id'));

		switch(Input::method()) {
			case 'POST':
			case 'PUT':
				$this->bookmark_post($id);
				break;

			case 'DELETE':
				$this->bookmark_delete($id);
				break;
		}

		$this->action_bookmarks();
	}

	private function bookmark_post($id = null) {
		if ($id) { CMS::set_user_meta($this->user_login->user_id, 'bookmark_offer', $id); }
	}

	private function bookmark_delete($id = null) {
		if ($id) { CMS::delete_user_meta($this->user_login->user_id, 'bookmark_offer', $id); }
	}

	public function action_bookmarks() {
		$bookmarks = $this->_bookmarks($this->user_login->user_id);
		$output = array('meta' => array('error' => '', 'status' => 1), 'data' => array('offers' => $bookmarks));

		$this->response($output);
	}

        public static function countStuff($params, $user){
            
            $query = Model_Offer::query()->related('locations');

            // Only published offers
            $query->where('status', '1');

            // Do not count rewards
            $query->where('type', '0');

            // By default only active offers are considered
            self::_set_date_query($query, $params, time());

            if (isset($params['from_location']) && isset($params['include_merchants'])) {
                $query->and_where_open();
                $query->where('locations.id', $params['from_location']);
                $query->or_where('locations.mall_id', $params['from_location']);
                $query->and_where_close();
                $offers = $query->get();
            } elseif (isset($params['from_location'])) {
                $query->where('locations.id', $params['from_location']);
                $offers = $query->get();
            } elseif (($code = self::_set_nearby_query($user, $query, $params, false)) !== true) {
                $count = 0;
            } else {
                $offers = $query->get();
            }

            if (isset($offers)) {
                $count = 0;
                foreach ($offers as $offer) {
                    if ($user->get_like_message_status('offer', $offer->id) > -1 && $offer->is_active()) {
                        $count++;
                    }
                }
            }

            $meta = array('error' => null, 'status' => 1);
            
            return array("count" => $count, "meta" => $meta);
            
        }
        
	public function action_count() {
            if (Input::method() != 'GET') { $this->response($this->no_access); return; }

            $params = Input::get();
            $user = $this->user_login->user;

            $res = self::countStuff($params, $user);

            $data = array('data' => array('count' => $res["count"]), 'meta' => $res["meta"]);
            $this->response($data);
	}

        public static function listStuff($params, $user){
            
            $all_offers = array();
            $dfields = array('created_by', 'edited_by', 'created', 'edited');

            $favorite_location_ids = array_keys($user->favorite_locations);

            $query = Model_Offer::query()->related('locations')->where('locations.status', '=', 1);
                
            // This is so we load only non-reward offers on this query
            if (!isset($params['saved_only'])) {
                $query->where('type', Model_Offer::TYPE_DEFAULT);
            }

            if ($user->is_regular_user() || !isset($params['status_all'])) {
                // Only published offers
                $query->where('status', '1');
            } else {
                // Non-deleted offers
                $query->where('status', '>', '0');
            }
        
            $current_time = time();
            self::_set_date_query($query, $params, $current_time);

            // Check if only offers from favorite malls and locations are requested
            // https://basecamp.com/1936075/projects/1332003-development-api/todos/18556983-api-offers-get#comment_38069527
            if (isset($params['from_favorites'])) {
                $favorite_location_ids = array_keys($user->favorite_locations);
                if (count($favorite_location_ids) == 0) {
                    // No favorites, return error code to allow user to input some favorites or fix query
                    throw new Exception(Code::ERROR_NO_FAVORITES);
                } else {
                    $query->where('locations.id', 'in', $favorite_location_ids);
                }
            }

		// Check if only offers from nearby malls and locations should be returned
		// https://basecamp.com/1936075/projects/1332003-development-api/todos/18556983-api-offers-get#comment_38069527
            if (isset($params['from_nearby'])) {
                $nearby_query_result = static::_set_nearby_query($user, $query, $params);
                if ($nearby_query_result !== true) {
                    throw new Exception($nearby_query_result);
                }
            }

            // check if only offers from a specific location are being requested
            if (isset($params['from_location']) && isset($params['include_merchants'])) {
                $query->and_where_open();
                $query->where('locations.id', $params['from_location']);
                $query->or_where('locations.mall_id', $params['from_location']);
                $query->and_where_close();
            } elseif (isset($params['from_location'])) {
                $query->where('locations.id', $params['from_location']);
            } elseif (isset($params['from_user_locations'])) {
                $assigned_companies = $user->get_assigned_companies();
                if (count($assigned_companies) > 0) {
                    $query->where('locations.id', 'in', array_keys($assigned_companies));
                } else {
                    // Make sure the query returns an empty set
                    $query->where('locations.id', NULL);
                }
            }

            // Check if there is a string filter to search specific offers
            // https://basecamp.com/1936075/projects/1332003-development-api/todos/18556984-api-offers-get-key#comment_38069657
            // TODO: The brand and product fields are not defined anywhere
            if (isset($params['filter']) && !empty($params['filter'])) {
                    $query->and_where_open();
                    $query->or_where('name', 'like', "%{$params['filter']}%");
                    $query->or_where('description', 'like', "%{$params['filter']}%");
                    $filtered_location_ids = array_keys(Model_Location::query()->where('name', 'like', "%{$params['filter']}%")->get());
                    if (count($filtered_location_ids) > 0) {
                            $query->or_where('locations.id', 'in', $filtered_location_ids);
                    }
                    // TODO: Move the tag field from a json object in offers table to a separate relation
                    $query->or_where('tags', 'like', "%{$params['filter']}%");
                    $query->and_where_close();
            }

            // Check if only redeemed offers should be returned
            if (isset($params['redeemed_only'])) {
                    $redeems = Model_Offer_Redeem::query()
                            ->related('offer_code')
                            ->where('user_id', $user->id)
                            ->get();

            if (isset($params['saved_only']))
                // special case: when saved_only AND redeemed_only are specified, they should behave as an OR (2013-08-22)
                $query->and_where_open();

                if (count($redeems) > 0) {
                        $redeemed_offer_ids = array_map(function($r) { return $r->offer_code->offer_id; }, $redeems);
                        $query->where('id', 'in', $redeemed_offer_ids);
                } else {
                        // Make sure the query returns nothing
                        $query->where('id', null);
                }
            }

            $saved_offer_ids = array_keys($user->offers);
            // Check if only saved offers should be returned
            if (isset($params['saved_only'])) {

                if (count($user->offers) > 0) {
                    if (isset($params['redeemed_only']))
                    // special case: when saved_only AND redeemed_only are specified, they should behave as an OR (2013-08-22)
                            $query->or_where('id', 'in', $saved_offer_ids);
                    else
                        $query->where('id', 'in', $saved_offer_ids);
                } else {
                    // Make sure the query returns nothing
                    $query->where('id', null);
                }

                if (isset($params['redeemed_only']))
                    // special case: when saved_only AND redeemed_only are specified, they should behave as an OR (2013-08-22)
                    $query->and_where_close();
            }

            $current_date = date('Y-m-d H:i:s', $current_time);

            // Filter out offers from blocked retailers
            $blockings = Model_Location_Blocking::query()
                    ->where('user_id', $user->id)
                    ->where('start_date', '<=', $current_date)
                    ->where('end_date', '>=', $current_date)
                    ->get();

            $blocked_ids = array_keys($blockings);

            if (count($blocked_ids) > 0) {
                    $query->where('locations.id', 'not in', $blocked_ids);
            }

            $allowed_order_bys = array('date_start', 'date_end', 'name', 'description');
            if (isset($params['order_by']) && in_array($params['order_by'], $allowed_order_bys)) {
                $order_by = $params['order_by'];
            } else {
                $order_by = 'date_start';
            }

            $allowed_orders = array('asc', 'desc');
            if (isset($params['order_direction']) && in_array($params['order_direction'], $allowed_orders)) {
                $direction = $params['order_direction'];
            } else {
                $direction = 'asc';
            }

            $date_status = isset($params['date_status']) ? $params['date_status'] : 'all';

            if (!isset($params['from_cms']))  {
                // Filtering reward offers for the offers list
                $contestants = $user->contestants;
                $reward_offers = array();

                foreach ($contestants as $contestant) {
                    $rewards = $contestant->rewards;

                    foreach ($rewards as $reward) {
                        // Adding by default, for from_nearby and from_location
                        $add = true;
                        if (isset($params['from_favorites'])) {
                            // If the reward offer is on a favorite location, it should be added
                            $favorite_location_ids = array_keys($user->favorite_locations);
                            // Turning off add, it's chenging if the user has the location as fav
                            $add = false;
                            $locations = $reward->offer->locations;

                            foreach ($locations as $location) {
                                if (in_array($location->id, $favorite_location_ids)) {
                                    $add = true;
                                }
                            }
                        } else if (isset($params['redeemed_only'])) {
                            // TEH HORROR (checking if this offer has been redeemed by looking at its promo codes)
                            // We're going straight to hell for this!
                            $offer_code = array_slice($reward->offer->offer_codes, 0, 1);
                            if (empty($offer_code) || empty($offer_code[0]->offer_redeems)) {
                                $add = false;
                            }
                        }

                        if ($add) {
                            $reward_offers[] = Helper_Api::offer_response($reward->offer, true, true, $favorite_location_ids);
                        }
                    }
                }

                // Adding reward offers to the offers list
                $all_offers = $reward_offers;
            } else {
                $reward_offers = array();
                $all_offers    = array();
            }

            // Pagination query
        $page = isset($params['page'])?$params['page']:1;

        $count = $query->count() + sizeof($reward_offers);
        
        $meta = array('pagination' => static::_pagination($count, $page));
        $meta['status'] = 1;
        $meta['error'] = null;
        ksort($meta);

        $all_offers = array_slice($all_offers, $meta['pagination']['offset']['current'], $meta['pagination']['limit']);

        // first we get the ids in a native sql query in order to avoid the incredibly
        // bad performance of fuelphp's orm (slower than a golf cart with a flat tire)
        $ids = $query->get_query()->order_by("t0.$order_by", $direction)->get_only_ids("offer_id");
        // pseudo-pagination, obtaining only the ids that correspond to the current page
        $ids = array_slice($ids, max(0, $meta['pagination']['offset']['current'] - sizeof($reward_offers)), $meta['pagination']['limit'] - sizeof($reward_offers));

        // now we have the offer objects, only for the ids we requested
        if (!empty($ids)) {
            $results = Model_Offer::query()->related('locations')->related('locations.micello_info')->where('id', 'in', $ids)->order_by($order_by, $direction)->get();
        } else {
            $results = array();
        }

        // Parse the results
        $filtered = 0;
        foreach($results as $offer_model) {
            if ($date_status == 'active' && !$offer_model->is_active()) { 
                $filtered++; continue;   
            }

            $offer = Helper_Api::offer_response($offer_model, true, true, $favorite_location_ids);
			// Remove unnecessary fields (FIXME: Is this really necessary?)
			foreach($dfields as $field) { unset($offer->$field); }
            $offer->like_status = $user->get_like_message_status('offer', $offer->id);
            $offer->saved = in_array($offer->id, $saved_offer_ids);

            if ($offer->like_status > -1 || isset($params['from_cms'])) {
                // disliked offers shouldnt be included in the response
                $all_offers[] = $offer;
            }
		}

            return array("all_offers" => $all_offers, "meta" => $meta);
                
        }
	/**
	 * Get 25 offers with pagination meta that match the supplied search parameters
	 */
	public function action_list() {
            if (Input::method() != 'GET') { $this->response($this->no_access); return; }

            $params = Input::get();
            $user = $this->user_login->user;

            try {
                $resp = $this->listStuff($params, $user);
            } catch (Exception $exc) {
                return $this->_error_response($exc->getMessage());
            }
            
            // Set the output
            $data = array('data' => array('offers' => $resp["all_offers"]), 'meta' => $resp["meta"]);
            $this->response($data);
	}

	/**
	 * Save an offer por current user
	 */
	public function action_save() {
	    if (Input::method() != 'POST') { $this->response($this->no_access); return; }

	    $offers = array();

	    $user = $this->user_login->user;

	    $offer_id = Input::post('offer_id', '');
	    $offer = Model_Offer::find($offer_id);

	    if (!in_array($offer->id, array_keys($user->offers))) {
	        $user->offers[$offer->id] = $offer;
	    }

	    try {
	        if ($user->save()) {
	            $output = array(
	                'data' => array('status' => true),
	                'meta' => array('error' => '', 'status' => 1)
	            );
                Helper_Activity::log_activity($user, 'save_offer', array('offer_id' => (int)$offer_id));
                Helper_Analytics::log_event($this->user_login->user, 'offer', 'save', 'offer' . $offer->id);
	        } else {
	            $output = array('data' => $params, 'meta' => array('error' => 'Unable to save offer for user', 'status' => 0, 'qry' => DB::last_query()));
	        }
	    } catch (Orm\ValidationFailed $e) {
	        $output = array('data' => $params, 'meta' => array('error' => join(' / ', $e->get_fieldset()->error()), 'status' => 0));
	    }

	    $this->response($output);
	}

	public function action_assign_to_contest() {
	    if (Input::method() != 'POST') { $this->response($this->no_access); return; }

	    $offer_id = $this->param('id', 0);
	    $contest_id = Input::post('contest_id', 0);
	    $grand_prize = Input::post('grand_prize', 0);

        $offer = Model_Offer::find($offer_id);

	    $reward = Model_Reward::query()->where('offer_id', $offer_id)->get_one();
	    if ($contest_id != 0) {
	        if (empty($reward)) {
	            $reward = new Model_Reward();
	            $reward->offer = Model_Offer::find($offer_id);
	        }
	        $reward->grand_prize = $grand_prize;
	        $reward->contest = Model_Contest::find($contest_id);

	        try {
	            if ($reward->save()) {
                    $offer->type = 1;
                    $offer->save();

	                $output = array(
	                    'data' => array('status' => true),
	                    'meta' => array('error' => '', 'status' => 1)
	                );
	            } else {
	                $output = array('data' => $params, 'meta' => array('error' => 'Unable to associate offer with contest', 'status' => 0, 'qry' => DB::last_query()));
	            }
	        } catch (Orm\ValidationFailed $e) {
	            $output = array('data' => $params, 'meta' => array('error' => join(' / ', $e->get_fieldset()->error()), 'status' => 0));
	        }

	        $this->response($output);
	    } else if (!empty($reward)) {
	        $reward->delete();
            $offer->type = 0;
            $offer->save();
	    }

	    $this->response(array(
	        'data' => array('status' => true),
	        'meta' => array('error' => '', 'status' => 1)
	    ));
	}

	private static function _set_nearby_query($user, $query, $params) {
		if (isset($params['latitude']) && isset($params['longitude'])) {
			$latitude = $params['latitude'];
			$longitude = $params['longitude'];
		} else {
			$home_location = CMS::get_setting_for_user($user->id, 'home_location');
			if (is_null($home_location)) {
				return Code::ERROR_NO_LOCATION;
			}
			$latitude = $home_location['latitude'];
			$longitude = $home_location['longitude'];
		}
		// Get the nearby distance selected by the user or a default one
		$distance = CMS::get_setting_for_user($user->id, 'nearby_distance', self::DEFAULT_NEARBY_DISTANCE);
		// Get the nearby locations ids
		$nearby_location_ids = Model_Location::get_nearby_location_ids($latitude, $longitude, $distance);

		if (count($nearby_location_ids) == 0) {
			// No nearby malls or locations, return error code
			return Code::ERROR_NO_NEARBY_LOCATIONS;
		} else {
			$query->where('locations.id', 'in', $nearby_location_ids);
			return true;
		}
	}

	private static function _set_date_query($query, $params, $current_time) {
		$current_date = date('Y-m-d H:i:s', $current_time);
		$date_status = isset($params['date_status']) ? $params['date_status'] : 'all';

        // Check how the offer dates should be handled
		// https://basecamp.com/1936075/projects/1332003-development-api/todos/18556983-api-offers-get#comment_38069527
		if (isset($params['from_cms']) && $params['from_cms'] == '1') {
            if (isset($params['include_active'], $params['include_inactive']) && $params['include_active'] && $params['include_inactive']) {
                // called from cms, with both filters (active and inactive) --> Retreieve all offers
            } else if (isset($params['include_active']) && $params['include_active']) {
                // return only the active offers in the given timezone
                $timezone = $params['timezone'];
                if (Helper_Timezone::valid_timezone($timezone)) {
                    $now = new DateTime('now');
                    $now->setTimezone(new DateTimeZone($timezone));
                    
                    $current_date = $now->format('Y-m-d H:i:s');
                    
                    $query->where('date_start', '<=', $current_date);
                    $query->where('date_end'  , '>=', $current_date);
                }
                // Include only offers with 'active' status
                $query->where('status', '1');
                
            } else if (isset($params['include_inactive']) && $params['include_inactive']) {
                // return only the inactive offers in the given timezone
                $timezone = $params['timezone'];
                $query->and_where_open();
                if (Helper_Timezone::valid_timezone($timezone)) {
                    $now = new DateTime('now');
                    $now->setTimezone(new DateTimeZone($timezone));
                    
                    $current_date = $now->format('Y-m-d H:i:s');
                    
                    $query->and_where_open();
                    $query->or_where('date_start', '>=', $current_date);
                    $query->or_where('date_end'  , '<=', $current_date);
                    $query->and_where_close();
                }
                // Don't include offers with 'active' status
                $query->or_where('status', '!=', '1');
                $query->and_where_close();
            }
        
        } elseif ($date_status == 'active') {
			// Only active offers by date
			$query->where('date_start', '<=', date('Y-m-d H:i:s', $current_time + (12 * 3600)));
			$query->where('date_end'  , '>=', date('Y-m-d H:i:s', $current_time - (12 * 3600)));

		} elseif ($date_status == 'upcoming') {
			$upcoming_date = date('Y-m-d H:i:s', strtotime(self::UPCOMING_TIME));
			$query->where('date_start', 'between', array($current_date, $upcoming_date));
			$query->where('date_end', '>=', $current_date);

		} elseif ($date_status == 'expiring_soon') {
			$expiring_soon_date = date('Y-m-d H:i:s', strtotime(self::EXPIRING_SOON_TIME, $current_time));
			$query->where('date_start', '<=', $current_date);
			$query->where('date_end', 'between', array($current_date, $expiring_soon_date));

		} elseif ($date_status == 'all') {
			// A combination of all the above conditions
			$upcoming_date = date('Y-m-d H:i:s', strtotime(self::UPCOMING_TIME));
			$query->and_where_open();
			$query->or_where('date_start', 'between', array($current_date, $upcoming_date));
			$query->or_where('date_start', '<=', $current_date);
			$query->and_where_close();
			$query->where('date_end', '>=', $current_date);
		}
	}

	private function _bookmarks($userID) {
		$items = CMS::get_user_meta($userID, 'bookmark_offer', FALSE);

		$output = array();

		foreach($items as $id) {
			$offer = $this->action_get($id);
			if (isset($offer->data->offer)) {
				array_push($output, $offer->data->offer);
			}
		}
		return $output;
	}

    private function populate_images_from_location($offer) {
        $destination_helper = 'Helper_Images_Offers';
        foreach ($offer->locations as $location) {
            if ($location->landing_screen_img) {
                // this location has an image set, let's copy it to the offer
                $img_to_copy = $location->landing_screen_img;
                $filename = Helper_Images_Landing::copy_image($img_to_copy, $destination_helper);
            } elseif ($location->logo) {
                $img_to_copy = $location->logo;
                $filename = Helper_Images_Logos::copy_image($img_to_copy, $destination_helper);
            } else {
                continue;
            }
            $offer->gallery[] = $filename;
            break;
        }
    }
    
    private function populate_images_with_default_image($offer) {
        
        $offer->gallery = array();
        
        // Copy default image from site assets
        $default_image = 'default-logo.png';
        //Fuel\Core\Config::load('asset'); Somthing strange occurs when I uncomment this line
        
        $image_dir = 'images/'; //Fuel\Core\Config::get('img_dir');
        $asset_dir = 'assets/'; //Fuel\Core\Config::get('paths');
        
        $default_image_path = DOCROOT . $asset_dir . $image_dir . $default_image;

        $image_name = Helper_Images_Offers::copy_image_from_path($default_image_path);
        $offer->gallery[] = $image_name;
        
        return $image_name;
    }

    
    public function action_delete_photos() {
        if (Input::method() != 'POST') { $this->response($this->no_access); return; }
        $id = $this->param('id');
		if (empty($id)) {
			$this->_error_response(Code::ERROR_INVALID_OFFER_ID);
			return;
		}
		$offer = Model_Offer::find($id);
        $user  = $this->user_login->user;
        
        $location_ids = array_keys($offer->locations);
        if (!$user->is_admin()) {
		    if (count(array_diff($location_ids, array_keys($this->user_login->user->get_assigned_companies()))) > 0) {
		        $this->_error_response(Code::ERROR_INVALID_LOCATION_ID);
		        return;
		    }
		}
        
        $image_name = $this->populate_images_with_default_image($offer);
        
        if ($offer->save()) {
            $complete_url = Asset::get_file($image_name, 'img', Config::get('cms.offer_images_path'));
            return $this->response(array(
                'data' => array('status' => true, 'default_image' => $complete_url),
                'meta' => array('error' => '', 'status' => 1)
            ));
        }
        
        return $this->_error_response(Code::ERROR_SAVING_OFFER);
    }
    
    public function action_doImport(){
        
        $provider = strtolower($this->param('provider'));
        $queryFilter = Input::get('queryFilter');
        
        try {
            
            switch ($provider){
                case "sqoot":
                    $output = $this->importDealsSqoot($queryFilter);
                    break;
                case "8coupons":
                    $output = $this->importDeals8coupons($queryFilter);
                    break;
            }

        } catch (Exception $e) {
            $output = array('data' => array(), 'meta' => array('error' => $e->getMessage()), 'status' => 0);
        }

        return $this->response($output);
        
    }
    
    private function importDeals8coupons($queryFilter){
        
        $providerName = "8coupons";
        $dealsApiKey = Config::get('cms.8coupons_api_key');
        
        try {
            $zip = $this->getAnyZipCodeNow($queryFilter);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        
        $horribleFlag = true;
        
        $i = 1;
        
        $fullDeals = array();
        
        while ($horribleFlag){
            
            $dealsURL = "http://api.8coupons.com/v1/getdeals?key=".$dealsApiKey."&page=".$i."&zip=".$zip."&mileradius=20&limit=1000";

            error_log($dealsURL);

            $s = curl_init();
            curl_setopt($s, CURLOPT_URL, $dealsURL);
            //curl_setopt($s, CURLOPT_HTTPHEADER, array('Authorization: api_key '.$dealsApiKey));
            curl_setopt($s, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($s, CURLOPT_FOLLOWLOCATION, true);
            $rawResponse = curl_exec($s);
            $info = curl_getinfo($s, CURLINFO_HTTP_CODE);
            curl_close($s);

            $data = json_decode($rawResponse, true);

            if (is_null($data)) throw new Exception ("COULD NOT DECODE DATA");

            error_log("THIS COUNT: ".count($data));

            if (count($data) > 0) $fullDeals = array_merge($data, $fullDeals);
            if (count($data) < 1000) $horribleFlag = false;
            
            $i++;
            
        }

        $merchantNames = $this->getAllMerchantNames($queryFilter);
        
        $merchantsNotFound = array();
        $merchantsFound = array();
        $dealsMissed = 0;
        $dealsSaved = 0;
        $existingRecords = 0;
        
        foreach ($fullDeals as $deal){
            
            /*                
                {"dealtypeid":"1","TypeName":"Printable Coupon"},//
                {"dealtypeid":"2","TypeName":"Tip"}, //
                {"dealtypeid":"3","TypeName":"Sale"}, 
                {"dealtypeid":"4","TypeName":"Special"},
                {"dealtypeid":"5","TypeName":"Always cheap"},
                {"dealtypeid":"6","TypeName":"Coupon Code"},//
                {"dealtypeid":"7","TypeName":"Happy Hour"},
                {"dealtypeid":"8","TypeName":"Free Stuff"},
                {"userid": "18381",TypeName: "Deals-of-the-Day"}
            */
            
            if (($deal["DealTypeID"] == 1)||($deal["DealTypeID"] == null)||($deal["DealTypeID"] == 0)||($deal["DealTypeID"] == 6)||($deal["DealTypeID"] == 2)){
                //$unusable++;
                continue;
            }
            
            $existingRecord = DB::select("id")->from('offers')->where('provider', '=', $providerName)->where('internal_id', '=', $deal["ID"])->execute();
            
            if (count($existingRecord) > 0){
                error_log("OFFER ID: ".$deal["ID"]." PROVIDER: ".$providerName." ALREADY EXISTS. OMITTING.");
                $existingRecords++;
                continue;
            }
            
            if (strtolower($deal["city"]) != strtolower($queryFilter)) continue; //WRONG CITY. SKIP.
                
            $tmpMerchantName = strtolower(preg_replace("/[^A-Za-z0-9 ]/", '', $deal["name"]));
            
            $found = false;
            $foundLocationId = null;
                    
            foreach ($merchantNames as $merchantId => $merchantName){
                
                similar_text($tmpMerchantName, $merchantName, $similarPercentage); //USING THIS JUST IN CASE THERES A TYPO SOMEWHERE IN THE MERCHANTS NAME
                
                //error_log($similarPercentage);
                
                if ($similarPercentage > 90){
                    
                    error_log("PERCENTAGE: ".$similarPercentage." TMP: ".$tmpMerchantName." NAME:".$merchantName);
                    
                    $foundLocationId = $merchantId;
                    
                    $merchantsFound[$deal["storeID"]] = $deal["name"];
                    $found = true;
                    
                    break;
                }
                
            }
            
            if ($found){
                //MERCHANT FOUND. SAVE THIS DEAL!
                
		$newOffer = new Model_Offer();

                $newOffer->name = $deal["dealTitle"];
                $newOffer->description = $deal["dealinfo"];
                
                $newOffer->price_regular = round((float)$deal["dealOriginalPrice"], 2);
                $newOffer->price_offer = round((float)$deal["dealPrice"], 2);
                $newOffer->savings = round((float)$deal["dealSavings"], 2);
                
		$newOffer->created_by = $newOffer->edited_by = $this->user_login->user_id;
		$newOffer->status = 1;
                $newOffer->show_dates = 1;
                        
                $newOffer->date_start = date('Y-m-d H:i:s', strtotime($deal["postDate"]));
		$newOffer->date_end = date('Y-m-d H:i:s', strtotime($deal["expirationDate"]));
                
                $newOffer->locations[] = Model_Location::find($foundLocationId);
                
                $newOffer->tags = $this->createTagsString($deal["name"]." ".$deal["dealTitle"]);
                
                $newOffer->gallery[] = $deal["showImageStandardBig"];
                $newOffer->gallery[] = $deal["showImageStandardSmall"];
                
                $this->populate_images_from_location($newOffer);
                
                if (empty($newOffer->gallery)){
                    $this->populate_images_with_default_image($newOffer);
                }
                
		// TODO: Determine what's the purpose of this field
		$newOffer->categories = '';
                $newOffer->redeemable = 0;
                $newOffer->allowed_redeems = 0;
                
                $newOffer->multiple_codes = 0;
                $newOffer->default_code_type = "";
                $newOffer->force_top_message = 0;
                
                $newOffer->provider = $providerName; 
                $newOffer->internal_id = $deal["ID"];
                $newOffer->imported_url = $deal["URL"];
                $newOffer->raw_imported_info = $deal;
                
                try {
                    
                    if ($newOffer->save()) {
                        $dealsSaved++;
                        error_log("OFFER IMPORTED SUCCESSFULLY!!!");
                    }else{
                        error_log("UNABLE TO CREATE OFFER. ERROR 1.");
                    }
                    
                } catch (Orm\ValidationFailed $e) {
                    error_log("UNABLE TO CREATE OFFER. ERROR 2.");
		}
                
            }else{
                //MERCHANT NOT FOUND
                $merchantsNotFound[$deal["storeID"]] = $deal["name"];
                $dealsMissed++;
            }
            
            
        }
        
        //error_log(var_export($merchantsFound, true));
        ////error_log("TOTAL MERCHANTS FOUND: ".count($merchantsFound));
        ////error_log("TOTAL DEALS SAVED: ".$dealsSaved);
        //error_log(var_export($merchantsNotFound, true));
        ////error_log("TOTAL MERCHANTS MISSING: ".count($merchantsNotFound));
        ////error_log("TOTAL DEALS MISSED: ".$dealsMissed);
        
        $output = array('data' => array("dealsSaved" => $dealsSaved, "merchantsFound" => count($merchantsFound), "existingRecords" => $existingRecords), 'meta' => array('error' => null, 'status' => 1));

        return $output;
        
    }
    
    private function createTagsString($strings){
        return implode(",", explode(" ", str_replace("not", "", $strings)));
    }
    
    private function importDealsSqoot($queryFilter){
        
        $dealsApiKey = Config::get('cms.sqoot_api_key');
        $recordsByPage = 100;
        $maxTotalRecords = 2500;
        $providerName = "sqoot";
        
        $horribleFlag = true;
        
        $i = 1;
        
        $fullDeals = array();
        
        while ($horribleFlag){
            
            $dealsURL = "http://api.sqoot.com/v2/deals?radius=10&page=".$i."&per_page=".$recordsByPage."&location=".urlencode($queryFilter);
            
            error_log($dealsURL);
            
            $s = curl_init();
            curl_setopt($s, CURLOPT_URL, $dealsURL);
            curl_setopt($s, CURLOPT_HTTPHEADER, array('Authorization: api_key '.$dealsApiKey));
            curl_setopt($s, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($s, CURLOPT_FOLLOWLOCATION, true);
            $rawResponse = curl_exec($s);
            $info = curl_getinfo($s, CURLINFO_HTTP_CODE);
            curl_close($s);
            
            $data = json_decode($rawResponse, true);
        
            if (is_null($data)) throw new Exception ("COULD NOT DECODE DATA");
        
            if ($i==1){
                $totalRecords = (int) $data["query"]["total"];
                $totalPages = ceil($totalRecords/$recordsByPage);
                
                error_log("TOTAL RECORDS: ".$totalRecords." TOTAL PAGES: ".$totalPages);

                if ($totalRecords > $maxTotalRecords) throw new Exception("TOO MANY RECORDS");

            }else{
                
               if ($i == $totalPages) $horribleFlag = false;
                
            }
            
            $fullDeals = array_merge($data["deals"], $fullDeals);
                
            $i++;
            
        }
        
        $merchantNames = $this->getAllMerchantNames($queryFilter);
        
        $merchantsNotFound = array();
        $merchantsFound = array();
        $dealsMissed = 0;
        $dealsSaved = 0;
        $existingRecords = 0;
        
        foreach ($fullDeals as $deal){
            
            $existingRecord = DB::select("id")->from('offers')->where('provider', '=', $providerName)->where('internal_id', '=', $deal["deal"]["id"])->execute();
            
            if (count($existingRecord) > 0){
                error_log("OFFER ID: ".$deal["deal"]["id"]." PROVIDER: ".$providerName." ALREADY EXISTS. OMITTING.");
                $existingRecords++;
                continue;
            }
            
            if (strtolower($deal["deal"]["merchant"]["locality"]) != strtolower($queryFilter)) continue; //WRONG CITY. SKIP.
                
            $tmpMerchantName = strtolower(preg_replace("/[^A-Za-z0-9 ]/", '', $deal["deal"]["merchant"]["name"]));
            

            $found = false;
            $foundLocationId = null;
                    
            foreach ($merchantNames as $merchantId => $merchantName){
                
                similar_text($tmpMerchantName, $merchantName, $similarPercentage); //USING THIS JUST IN CASE THERES A TYPO SOMEWHERE IN THE MERCHANTS NAME
                
                //error_log($similarPercentage);
                
                if ($similarPercentage > 90){
                    
                    error_log("PERCENTAGE: ".$similarPercentage." TMP: ".$tmpMerchantName." NAME:".$merchantName);
                    
                    $foundLocationId = $merchantId;
                    
                    $merchantsFound[$deal["deal"]["merchant"]["id"]] = $deal["deal"]["merchant"];
                    $found = true;
                    
                    break;
                }
                
            }
            
            if ($found){
                //MERCHANT FOUND. SAVE THIS DEAL!
                
		$newOffer = new Model_Offer();

                $newOffer->name = $deal["deal"]["title"];
                
                if (empty($deal["deal"]["description"])){
                    $newOffer->description = $deal["deal"]['short_title'];
                }else{
                    $newOffer->description = $deal["deal"]['description'];
                }
                
                $newOffer->price_regular = round((float)$deal["deal"]["value"], 2);
                $newOffer->price_offer = round((float)$deal["deal"]["price"], 2);
                $newOffer->savings = round((float)$deal["deal"]["discount_amount"], 2);
                
		$newOffer->created_by = $newOffer->edited_by = $this->user_login->user_id;
		$newOffer->status = 1;
                $newOffer->show_dates = 1;
                
                $newOffer->date_start = date('Y-m-d H:i:s', strtotime($deal["deal"]["created_at"]));
		$newOffer->date_end = date('Y-m-d H:i:s', strtotime($deal["deal"]["expires_at"]));
                $newOffer->locations[] = Model_Location::find($foundLocationId);
                
                if (empty($deal["deal"]["category_slug"])){
                    $newOffer->tags = ""; //TODO: DO SOMETHING HERE!
                }else{
                    $newOffer->tags = $deal["deal"]["category_slug"];
                }
                
                $newOffer->gallery[] = $deal["deal"]["image_url"];
                
                $this->populate_images_from_location($newOffer);
                
                if (empty($newOffer->gallery)){
                    $this->populate_images_with_default_image($newOffer);
                }
                
		// TODO: Determine what's the purpose of this field
		$newOffer->categories = '';
                $newOffer->redeemable = 0;
                $newOffer->allowed_redeems = 0;
                $newOffer->multiple_codes = 0;
                $newOffer->default_code_type = "";
                $newOffer->force_top_message = 0;
                
                $newOffer->provider = $providerName; 
                $newOffer->internal_id = $deal["deal"]["id"];
                $newOffer->imported_url = $deal["deal"]["url"];
                $newOffer->raw_imported_info = $deal["deal"];
                
		try {
                    
                    if ($newOffer->save()) {
                        $dealsSaved++;
                        error_log("OFFER IMPORTED SUCCESSFULLY!!!");
                    }else{
                        error_log("UNABLE TO CREATE OFFER. ERROR 1.");
                    }
                    
                } catch (Orm\ValidationFailed $e) {
                    error_log("UNABLE TO CREATE OFFER. ERROR 2.");
		}
                
            }else{
                //MERCHANT NOT FOUND
                $merchantsNotFound[$deal["deal"]["merchant"]["id"]] = $deal["deal"]["merchant"];
                $dealsMissed++;
            }
            
        }
        
        //error_log(var_export($merchantsFound, true));
        ////error_log("TOTAL MERCHANTS FOUND: ".count($merchantsFound));
        ////error_log("TOTAL DEALS SAVED: ".$dealsSaved);
        //error_log(var_export($merchantsNotFound, true));
        ////error_log("TOTAL MERCHANTS MISSING: ".count($merchantsNotFound));
        ////error_log("TOTAL DEALS MISSED: ".$dealsMissed);
        
        $output = array('data' => array("dealsSaved" => $dealsSaved, "merchantsFound" => count($merchantsFound), "existingRecords" => $existingRecords), 'meta' => array('error' => null, 'status' => 1));

        return $output;
        
    }
    
    private function getAnyZipCodeNow($cityName){
        
        $result = DB::query("SELECT zip, count(*) AS entries FROM `suey_locations` WHERE city = :cityName GROUP BY zip LIMIT 1")->bind('cityName', $cityName)->execute();
        
        if (count($result) > 0){
            return $result[0]["zip"];
        }else{
            throw new Exception("COULD NOT FIND ZIP CODE");
        }
        
    }
    
    private function getAllMerchantNames($cityName){
        $rawMerchantNames = DB::select("id", "name")->from('locations')->where('city', '=', $cityName)->distinct(true)->execute();
        $merchantNames = array();
        foreach ($rawMerchantNames as $merchantNameRecord){ //KILL THIS WITH FIRE
            $merchantNames[$merchantNameRecord["id"]] = strtolower(preg_replace("/[^A-Za-z0-9 ]/", '', $merchantNameRecord["name"]));
        }
        
        return $merchantNames;
    }
}
