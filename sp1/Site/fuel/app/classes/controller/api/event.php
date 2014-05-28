<?php

/**
 * The Api Controller.
 * This controllers the CRUD proceedures for events
 *
 * @package  app
 * @extends  Controller_Rest
 */

class Controller_Api_Event extends Controller_Api {

    // The relative times to consider an event as upcoming
    const UPCOMING_TIME = '+1 years';
    // The distance to consider a location to be nearby, in miles
    const DEFAULT_NEARBY_DISTANCE = 5;
    
	/**
	 * Get a single event
	 */
	public function action_get() {
        if (Input::method() != 'GET') { $this->response($this->no_access); return; }
        
		$id = $this->param('id');
        $event = Model_Event::find($id);
        
        if (!$event) {
            return $this->_error_response(Code::ERROR_INVALID_EVENT_ID);
        }
        
        // has the user rsvpd to this event?
        $rsvpd = false;
        $rsvps = $this->user_login->user->eventrsvps;
        foreach ($rsvps as $rsvp) {
            if ($event->id == $rsvp->event_id) {
                $rsvpd = true;
                break;
            }
        }
        
        // Build the urls for the images
        $event = Helper_Api::event_response($event);
        $event->rsvpd = $rsvpd;
        $event->attending_friends = array(); // TO DO
        $event->like_status = $this->user_login->user->get_like_message_status('event', $event->id);

		$meta = array('error' => null, 'status' => 1);
        $data = array('data' => array('event' => $event), 'meta' => $meta);
        $this->response($data);
	} // ---> action_get()

    public function action_rsvp() {
        if (Input::method() != 'POST') { $this->response($this->no_access); return; }
        
        $event_id = $this->param('id');
        $user = $this->user_login->user;
        
        $params = Input::post();
        
        $status = true;
        if (isset($params['rsvp_status'])) {
            $status = (bool)($params['rsvp_status']);
        } 
        
        $event = Model_Event::find($event_id);
        
        if (!$event) {
            return $this->_error_response(Code::ERROR_INVALID_EVENT_ID);
        }

        $rsvpd_query = Model_Eventrsvp::query()
                ->where('user_id', $user->id)
                ->where('event_id', $event->id);
        $already_rsvpd = $rsvpd_query->count();

        if ($status && !$already_rsvpd) {
            $rsvp = new Model_Eventrsvp();
            $rsvp->user_id = $user->id;
            $rsvp->event_id = $event->id;
            $rsvp->save();

            Helper_Analytics::log_event($this->user_login->user, 'event', 'rsvp', 'event' . $event->id);
        } elseif (!$status && $already_rsvpd) {
            $rsvp = $rsvpd_query->get_one();
            $rsvp->delete();
        }
        
        $meta = array('error' => null, 'status' => 1);
        $data = array('data' => array('event' => $event), 'meta' => $meta);
        $this->response($data);
    }

    public function action_specialrsvp() {
        if (Input::method() != 'POST') { $this->response($this->no_access); return; }

        $event_id = $this->param('id');
        $user = $this->user_login->user;

        $params = Input::post();

        $status = true;
        if (isset($params['rsvp_status'])) {
            $status = (bool)($params['rsvp_status']);
        }

        $event = Model_Specialevent::find($event_id);

        if (!$event) {
            return $this->_error_response(Code::ERROR_INVALID_EVENT_ID);
        }

        $rsvpd_query = Model_Specialeventrsvp::query()
                ->where('user_id', $user->id)
                ->where('specialevent_id', $event->id);
        $already_rsvpd = $rsvpd_query->count();

        if ($status && !$already_rsvpd) {
            $rsvp = new Model_Specialeventrsvp();
            $rsvp->user_id = $user->id;
            $rsvp->specialevent_id = $event->id;
            $rsvp->save();
        } elseif (!$status && $already_rsvpd) {
            $rsvp = $rsvpd_query->get_one();
            $rsvp->delete();
        }

        $meta = array('error' => null, 'status' => 1);
        $data = array('data' => array('event' => $event), 'meta' => $meta);
        $this->response($data);
    }
    
    /**
    * Get events with pagination meta
    */
    public function action_count() {
        if (Input::method() != 'GET') { $this->response($this->no_access); return; }

        $params = Input::get();
        $user = $this->user_login->user;

        $res = self::countStuff($params, $user);

        $data = array('data' => array('count' => $res["count"]), 'meta' => $res["meta"]);
        $this->response($data);
    }
    
    public static function countStuff($params, $user){
    
        $query = Model_Event::query();
        $query->where('status', '1'); // published events only!
        
        $special_events_query = Model_Specialevent::query()->related('locations')->related('locations.micello_info')->where('status', '>', 0);
        
        self::events_today_and_upcoming($query, $special_events_query, $params);
        
        if (isset($params['from_location']) && isset($params['include_merchants'])) {
            $result = self::events_from_location($query, $special_events_query, $params, true);
        } elseif (isset($params['from_location'])) {
            $result = self::events_from_location($query, $special_events_query, $params);
        } else {
            $result = self::events_from_nearby($user, $query, $special_events_query, $params);
        }
        
        if ($result !== true) {
            $count = 0;
        } else {
            $count = 0;
            $events = $query->get();
            $special_events = $special_events_query->get();
            $events = array_merge($events, $special_events);
            
            foreach ($events as $event) {
                if ($user->get_like_message_status('event', $event->id) > -1) {
                    $count++;
                }
            }
        }

        $meta = array('error' => null, 'status' => 1);
        
        return array("count" => $count, "meta" => $meta);
        
    }
    
        public static function listStuff($params, $user){
            
            $query = Model_Event::query()->related('locations')->where('locations.status', '=', 1);
                
            $query->where('status', '1'); // published events only!
            $special_events_query = Model_Specialevent::query()->related('locations')->related('locations.micello_info')->where('t0.status', '>', 0)->where('locations.status', '=', 1);

            $result = true;
            if (isset($params['from_favorites'])) {
                $result = self::events_from_favorites($user, $query, $special_events_query, $params);
            } elseif (isset($params['from_nearby'])) {
                $result = self::events_from_nearby($user, $query, $special_events_query, $params);
            } elseif (isset($params['from_location']) && isset($params['include_merchants'])) {
                $result = self::events_from_location($query, $special_events_query, $params, true);
            } elseif (isset($params['from_location'])) {
                $result = self::events_from_location($query, $special_events_query, $params);
            }

            if ($result !== true) {
                throw new Exception($result);
            }

            if (isset($params['today'])) {
                $result = self::events_today($query, $special_events_query, $params);
            } elseif (isset($params['upcoming'])) {
                $result = self::events_upcoming($query, $special_events_query, $params);
            } elseif (isset($params['rsvp'])) {
                $result = self::events_rsvp($user, $query, $special_events_query, $params);
            } else {
                $result = self::events_today_and_upcoming($query, $special_events_query, $params);
            }

            if (isset($params['keyword'])) {
                $result = self::events_keyword($query, $special_events_query, $params);
            }

            if ($result !== true) {
                throw new Exception($result);
            }

            $page = isset($params['page'])?$params['page']:1;
            
            $count = $query->count() + $special_events_query->count();
            $meta = array('pagination' => static::_pagination($count, $page));
            $meta['status'] = 1;
            $meta['error'] = null;

            $limit = $meta['pagination']['limit'];
            $offset = $meta['pagination']['offset']['current'];

            $events = $query->get();

            $location = self::get_user_location($user, $params);
            if (is_object($location)) {
                $events = Model_Location::sort_by_proximity($location->latitude, $location->longitude, $events);
            }

            $special_events = $special_events_query->order_by('date_start', 'desc')->get();
            $all_events = array_merge($special_events, $events);

            $events_array = array();
            foreach ($all_events as $event) {

                if (isset($params['today']) && !$event->is_active()) { continue; }

                // has the user rsvpd to this event?
                $rsvpd = false;

                if ($event->special) {
                    $rsvps = $user->specialeventrsvps;
                } else {
                    $rsvps = $user->eventrsvps;
                }

                foreach ($rsvps as $rsvp) {
                    if ((!$event->special && $event->id == $rsvp->event_id) || ($event->special && $event->id == $rsvp->specialevent_id)) {
                        $rsvpd = true;
                        break;
                    }
                }

                $type = $event->special ? 'specialevent' : 'event';
                $event_to_return = Helper_Api::event_response($event);
                $event_to_return->rsvpd = $rsvpd;
                $event_to_return->like_status = $user->get_like_message_status($type, $event->id);

                if ($event_to_return->like_status > -1) {
                    // disliked events shouldnt be included in the response
                    $events_array[] = $event_to_return;
                }
            }

            $events_array = array_slice($events_array, $offset, $limit);

            return array("events_array" => $events_array, "meta" => $meta);
            
        }
        
	/**
	 * Get events with pagination meta
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
            
            $data = array('data' => array('events' => $resp["events_array"]), 'meta' => $resp["meta"]);
            $this->response($data);
	} // ---> action_list()
    
    private static function events_from_favorites($user, $query, $special_events_query, $params) {
        
        $favorite_location_ids = array_keys($user->favorite_locations);
        if (!count($favorite_location_ids)) {
            return Code::ERROR_NO_FAVORITES_FOR_EVENT;
        }
        $query->related('locations')->where('locations.id', 'in', $favorite_location_ids);
        $special_events_query->related('locations')->where('main_location_id', 'in', $favorite_location_ids);
        return true;
    }
    
    private static function get_user_location($user, $params) {
        $latitude = $longitude = 0;
        if (isset($params['latitude']) && isset($params['longitude'])) {
            $latitude = $params['latitude'];
            $longitude = $params['longitude'];
        } else {
            $home_location = CMS::get_setting_for_user($user->id, 'home_location');
            if (is_null($home_location)) {
                return Code::ERROR_NO_LOCATION_FOR_EVENT;
            }
            $latitude = $home_location['latitude'];
            $longitude = $home_location['longitude'];
        }
        return (object)array('latitude' => $latitude, 'longitude' => $longitude);
    }
    
    private static function events_from_nearby($user, $query, $special_events_query, $params) {
        $latitude = $longitude = 0;
        if (isset($params['latitude']) && isset($params['longitude'])) {
            $latitude = $params['latitude'];
            $longitude = $params['longitude'];
        } else {
            $home_location = CMS::get_setting_for_user($user->id, 'home_location');
            if (is_null($home_location)) {
                return Code::ERROR_NO_LOCATION_FOR_EVENT;
            }
            $latitude = $home_location['latitude'];
            $longitude = $home_location['longitude'];
        }
        // Get the nearby distance selected by the user or a default one
        $distance = CMS::get_setting_for_user($user->id, 'nearby_distance', self::DEFAULT_NEARBY_DISTANCE);
        // Get the nearby locations
        $nearby_location_ids = Model_Location::get_nearby_location_ids($latitude, $longitude, $distance);

        if (count($nearby_location_ids) == 0) {
            // No nearby locations, return error code
            return Code::ERROR_NO_NEARBY_EVENTS;
        }
            
        $query->related('locations')->where('locations.id', 'in', $nearby_location_ids);
        $special_events_query->related('locations')->where('main_location_id', 'in', $nearby_location_ids);
        return true;
    }
    
    private static function events_from_location($query, $special_events_query, $params, $include_merchants = false) {
        $location_id = $params['from_location'];
        $query->related('locations');
        $query->and_where_open();
        $query->where('locations.id', $location_id);
        $special_events_query->related('locations');
        $special_events_query->and_where_open();
        $special_events_query->where('main_location_id', $location_id);
        if ($include_merchants) {
            $query->or_where('locations.mall_id', $location_id);
            $special_events_query->or_where('locations.mall_id', $location_id);
        }
        $query->and_where_close();
        $special_events_query->and_where_close();
        return true;
    }
    
    private static function events_today($query, $special_events_query, $params) {
        $current_time = time();
        
        $start_date = date('Y-m-d H:i:s', $current_time + (12 * 3600));
        $end_date   = date('Y-m-d H:i:s', $current_time - (12 * 3600));
        
        $query->where('date_start', '<=', $start_date);
        $query->where('date_end'  , '>=', $end_date);
        $special_events_query->where('date_start', '<=', $start_date);
        $special_events_query->where('date_end'  , '>=', $end_date);
        return true;
    }
    
    private static function events_upcoming($query, $special_events_query, $params) {
        $current_date = date('Y-m-d H:i:s');
        $tomorrow_date = date('Y-m-d', strtotime(self::UPCOMING_TIME));
        $query->where('date_start', 'between', array($current_date, $tomorrow_date));
        $special_events_query->where('date_start', 'between', array($current_date, $tomorrow_date));
        return true;
    }
    
    private static function events_today_and_upcoming($query, $special_events_query, $params) {
        $current_date  = date('Y-m-d H:i:s');
        $tomorrow_date = date('Y-m-d', strtotime(self::UPCOMING_TIME));
        
        $current_time = time();
        $start_date   = date('Y-m-d H:i:s', $current_time + (12 * 3600));
        $end_date     = date('Y-m-d H:i:s', $current_time - (12 * 3600));
        
        $query->and_where_open();
        $query->where('date_start', '<=', $start_date);
        $query->where('date_end'  , '>=', $end_date);
        $query->or_where('date_start', 'between', array($current_date, $tomorrow_date));
        $query->and_where_close();
        
        $special_events_query->and_where_open();
        $special_events_query->where('date_start', '<=', $start_date);
        $special_events_query->where('date_end'  , '>=', $end_date);
        $special_events_query->or_where('date_start', 'between', array($current_date, $tomorrow_date));
        $special_events_query->and_where_close();
        return true;
    }
    
    private function events_rsvp($user, $query, $special_events_query, $params) {
        $rsvps = $user->eventrsvps;
        $specialrsvps = $user->specialeventrsvps;
        $event_ids = array();
        $specialevent_ids = array(); 
        
        foreach ($rsvps as $rsvp) {
            $event_ids[] = $rsvp->event_id;
        }

        foreach ($specialrsvps as $rsvp) {
            $specialevent_ids[] = $rsvp->specialevent_id;
        }
        
        if ((count($event_ids) + count($specialevent_ids)) == 0) {
            return Code::ERROR_NO_RSVPS;
        }

        $event_ids[] = $specialevent_ids[] = -1; // in order to prevent "in ()"
        $query->where('id', 'in', $event_ids);
        $special_events_query->where('id', 'in', $specialevent_ids);
        return true;
    }
    
    private function events_keyword($query, $special_events_query, $params) {
        $keyword = $params['keyword'];
        $query->and_where_open();
        $query->where('tags', 'like', "%$keyword%");
        $query->or_where('title', 'like', "%$keyword%");
        $query->or_where('description', 'like', "%$keyword%");
        $query->and_where_close();
        
        $special_events_query->and_where_open();
        $special_events_query->where('tags', 'like', "%$keyword%");
        $special_events_query->or_where('title', 'like', "%$keyword%");
        $special_events_query->or_where('description', 'like', "%$keyword%");
        $special_events_query->and_where_close();
        
        return true;
    }

    /**
	 * Get 25 events with pagination meta that match the supplied search parameters
	 */
	public function action_search() {
		$events = array();

		$params = Input::get();
		$search = Input::get('name');

		if (isset($params['order_by'])) {
			$order_by = $params['order_by'];
		} else {
			$order_by = 'date_start';
		}
		
		if (isset($params['order_direction'])) {
			$order_direction = $params['order_direction'];
		} else {
			$order_direction = 'desc';
		}
        
        $events_query = Model_Event::query()->related('locations')->where('status', '>', 0);
        $specialevents_query = Model_Specialevent::query()->where('status', '>', 0);
        
        if (isset($params['from_cms']) && $params['from_cms']) {
            $timezone = isset($params['timezone']) ? $params['timezone'] : $default_timezone;
            $now = new DateTime('now');
            $now->setTimezone(new DateTimeZone($timezone));

            $current_date = $now->format('Y-m-d H:i:s');

            if (isset($params['include_active'], $params['include_inactive']) && $params['include_active'] && $params['include_inactive']) {
                // called from cms, with both filters (active and inactive) --> Retreieve all offers
            } else if (isset($params['include_active']) && $params['include_active']) {
                // return only the active offers in the given timezone
                $events_query->where('date_start', '<=', $current_date);
                $events_query->where('date_end'  , '>=', $current_date);
                // Include only events with 'active' status
                $events_query->where('status'    , '1');
                $specialevents_query->where('date_start', '<=', $current_date);
                $specialevents_query->where('date_end'  , '>=', $current_date);
                
            } else if (isset($params['include_inactive']) && $params['include_inactive']) {
                $events_query->and_where_open();
                $events_query->or_where('date_start', '>=', $current_date);
                $events_query->or_where('date_end'  , '<=', $current_date);
                
                // Don't include events with 'active' status
                $events_query->or_where('status'    , '!=', '1');
                $events_query->and_where_close();
                
                $specialevents_query->and_where_open();
                $specialevents_query->or_where('date_start', '>=', $current_date);
                $specialevents_query->or_where('date_end'  , '<=', $current_date);
                
                // Don't include events with 'active' status
                $specialevents_query->or_where('status'    , '!=', '1');
                $specialevents_query->and_where_close();
            }
        }
		
		if (!empty($search)) {
            $events_query->where('title', 'like', "%$search%");
            $specialevents_query->where('title', 'like', "%$search%");
        }
        
        if (isset($params['location_id'])) {
            $events_query->and_where_open();
            $events_query->where('locations.id', $params['location_id']);
            if (isset($params['include_merchants']) && $params['include_merchants']) {
                $events_query->or_where('locations.mall_id', $params['location_id']);
            }
            $events_query->and_where_close();
            $specialevents_query->where('main_location_id', $params['location_id']);
        } elseif(isset($params['from_user_locations'])) {
            $assigned_companies = $this->user_login->user->get_assigned_companies();
            if (count($assigned_companies) > 0) {
                $events_query->where('locations.id', 'in', array_keys($assigned_companies));
                $specialevents_query->where('main_location_id', 'in', array_keys($assigned_companies));
            } else {
                // Make sure the query returns an empty set
                $events_query->where('id', NULL);
                $specialevents_query->where('id', NULL);
            }
        }
        $cresults = $events_query->count() + $specialevents_query->count();
		$meta = array('pagination' => $this->_pagination($cresults, Input::param('page', 1)));
		$meta['status'] = 1;
		$meta['error'] = null;
		ksort($meta);

		$specialevents_query->order_by($order_by, $order_direction);
		$events_query->order_by($order_by, $order_direction);

		$specialevent_ids = $specialevents_query->get_query()->get_only_ids('id');
        $specialevent_ids = array_slice($specialevent_ids, $meta['pagination']['offset']['current'], $meta['pagination']['limit']);
        $event_ids = $events_query->get_query()->get_only_ids('event_id');
        $event_ids = array_slice($event_ids, max(0, $meta['pagination']['offset']['current'] - $specialevents_query->count()), $meta['pagination']['limit'] - count($specialevent_ids));

        $events_result = empty($event_ids) ? array() : Model_Event::query()
            ->related('locations')
            ->where('id', 'in', $event_ids)
            ->order_by($order_by, $order_direction)
            ->get();

        $special_events_result = empty($specialevent_ids) ? array() : Model_Specialevent::query()
            ->related('locations')
            ->where('id', 'in', $specialevent_ids)
            ->order_by($order_by, $order_direction)
            ->get();
        
        $all_events_result = array_merge($special_events_result, $events_result);

		// Parse the results
		foreach($all_events_result as $item) {
            if (isset($params['today']) && !$item->is_active()) { 
                continue;   
            }
            
			$events[] = Helper_Api::event_response($item, true);
		}
        
		// Set the output
		$data = array('data' => array('events'=> $events), 'meta' => $meta);
		$this->response($data);
	}
    
	private function _search($params, $order_by, $order) {
		$table = 'suey_events';

		$likes = array('name', 'description', 'tags');
		$equal = array('retailer_id', 'code');
		$gtrng = array('date_start');
		$ltrng = array('date_end');

		$whr = array();
		$sql = "SELECT * FROM $table WHERE status = 1 AND ";
		foreach($params as $field => $param) {
			$operand = null;

			$operand = (in_array($field, $likes) && $operand == null) ? 'LIKE' : $operand;
			$operand = (in_array($field, $equal) && $operand == null) ? '=' : $operand;
			$operand = (in_array($field, $gtrng) && $operand == null) ? '>=' : $operand;
			$operand = (in_array($field, $ltrng) && $operand == null) ? '<=' : $operand;
			$operand = ($operand == null) ? 'LIKE' : $operand;

			$value = $params[$field];

			if ($operand == 'LIKE') { $value = "%".$value."%"; }

			$str = "$field $operand '$value'";
			array_push($whr, $str);
		}

		$sql .= implode(' AND ', $whr);
		$cresults = DB::query($sql)->execute();
		$meta = array('pagination' => $this->_pagination(count($cresults), Input::param('page', 1)));
		$meta['status'] = 1;
		$meta['error'] = null;
		ksort($meta);

		$sql .= " ORDER BY $order_by $order";
		$sql .= " LIMIT ".$meta['pagination']['offset']['current'].", ".$meta['pagination']['limit'];

		$results = DB::query($sql)->execute();

		$output = array('meta' => $meta, 'results' => $results, 'cresults' => $cresults);
		ksort($output);
		$output = (object) $output;

		return $output;
	}
    
    private function populate_images_with_default_image($event) {
        $event->gallery = array();
        
        // Copy default image from site assets
        $default_image = 'default-logo.png';
        //Fuel\Core\Config::load('asset'); Somthing strange occurs when I uncomment this line
        
        $image_dir = 'images/'; //Fuel\Core\Config::get('img_dir');
        $asset_dir = 'assets/'; //Fuel\Core\Config::get('paths');
        
        $default_image_path = DOCROOT . $asset_dir . $image_dir . $default_image;

        $image_name = Helper_Images_Events::copy_image_from_path($default_image_path);
        $event->gallery[] = $image_name;
        
        return $image_name;
    }

    
    public function action_delete_photos() {
        if (Input::method() != 'POST') { $this->response($this->no_access); return; }
        $id = $this->param('id');
		if (empty($id)) {
			$this->_error_response(Code::ERROR_INVALID_EVENT_ID);
			return;
		}
		$event = Model_Event::find($id);
        $user  = $this->user_login->user;
        
        $location_ids = array_keys($event->locations);
        if (!$user->is_admin()) {
		    if (count(array_diff($location_ids, array_keys($this->user_login->user->get_assigned_companies()))) > 0) {
		        $this->_error_response(Code::ERROR_INVALID_LOCATION_ID);
		        return;
		    }
		}
        
        $image_name = $this->populate_images_with_default_image($event);
        
        if ($event->save()) {
            $complete_url = Asset::get_file($image_name, 'img', Config::get('cms.event_images_path'));
            return $this->response(array(
                'data' => array('status' => true, 'default_image' => $complete_url),
                'meta' => array('error' => '', 'status' => 1)
            ));
        }
        
        return $this->_error_response(Code::ERROR_SAVING_EVENT);
    }
}
