<?php

/**
 * The proximity messaging Api Controller.
 *
 * @package  app
 * @extends  Controller_Api
 */

class Controller_Api_Proximity extends Controller_Api {

    // The relative times to consider an event as upcoming
    const UPCOMING_TIME = '+7 days';
    // The distance to consider a location to be nearby, in miles
    const DEFAULT_NEARBY_DISTANCE = 5;
    // Maximum number of messages to return
    const DEFAULT_MAX_MESSAGES = 50;
    // How old should be a message interaction in order to be ignored so the
    // message is displayed again
    const SHOW_AGAIN_TIME = '-7 days';
    // How long should rewards be displayed in this list after they were received
    const MAX_TIME_FOR_REWARDS = '-5 days';
    
	/**
	 * Get all current events and offers
	 */
	public function action_get() {
        if (Input::method() != 'GET') { $this->response($this->no_access); return; }
        
        $params = Input::get();
        
        // events
        $events = array();
        $query_events = Model_Event::query();
        $query_events->where('status', '1'); // published events only!
        $special_events_query = Model_Specialevent::query()->where('status', '1');
        
        if ($this->from_nearby($query_events, $params, $special_events_query)) {
            $this->from_today($query_events, $params);
            $this->from_today($special_events_query, $params);
            $events = array_merge($query_events->get(), $special_events_query->get());
        }
        
        // offers
        $offers = array();
        $query_offers = Model_Offer::query();
        $query_offers->where('status', '1');
        if ($this->from_nearby($query_offers, $params)) {
            $this->from_today($query_offers, $params);
            $offers = $query_offers->get();
        }
        
        $max_messages = self::DEFAULT_MAX_MESSAGES;
        if (isset($params['max_messages'])) {
            $max_messages = (int)$params['max_messages'];
        }
        
        $messages_array = array();
        foreach ($events as $event) {
            if ($event->is_active()) {
                $messages_array[] = array('type' => 'event', 'details' => $event);
            }
        }
        
        foreach ($offers as $offer) {
            if ($offer->is_active()) {
                $messages_array[] = array('type' => 'offer', 'details' => $offer);
            }
        }
        
        $location = $this->get_latitude_and_longitude($params);
        $messages_array = $this->sort_by_recommendations($messages_array, $location['latitude'], $location['longitude']);
        
        $this->add_forced_messages($messages_array, $params);
        $this->add_contests_rewards($messages_array, $params);
        $messages_array = array_slice($messages_array, 0, $max_messages);
        
        foreach ($messages_array as $k => $msg) {
            $to_return = $msg['type'] == 'offer' ? Helper_Api::offer_response($msg['details']) : Helper_Api::event_response($msg['details']);
            $messages_array[$k]['details'] = $to_return;
        }
        
        // store location tracking
        $lt = new Model_Location_Tracking();
        $lt->user_id = $this->user_login->user_id;
        $lt->latitude = $params['latitude'];
        $lt->longitude = $params['longitude'];
        if (isset($params['accuracy'])) {
            $lt->accuracy = $params['accuracy'];
        }
        try {
            $lt->save();
        } catch (Exception $e) {
            // doesn't really matter if we lose some location tracking data
            error_log("Location tracking failed: " . $e->getMessage());
        }
        
        $meta = array('status' => 1, 'error' => null);
		$data = array('data' => array('messages' => $messages_array), 'meta' => $meta);
		$this->response($data);
        
	} // ---> action_get()
    
    private function add_forced_messages(&$messages, $params) {
        $forced_events_query = Model_Event::query()->where('force_top_message', '1')->where('status', '1');
        $this->from_today($forced_events_query, $params);
        $forced_events = $forced_events_query->get();
        
        $forced_offers_query = Model_Offer::query()->where('force_top_message', '1')->where('status', '1');
        $this->from_today($forced_offers_query, $params);
        $forced_offers = $forced_offers_query->get();
        
        foreach ($forced_events as $event) {
            array_unshift($messages, array('type' => 'event', 'details' => $event));
        }
        
        foreach ($forced_offers as $offer) {
            array_unshift($messages, array('type' => 'offer', 'details' => $offer));
        }
    }
    
    private function add_contests_rewards(&$messages, $params) {
        $u = $this->user_login->user;
        $rewards_query = Model_Reward::query()->related('contestant')->related('offer')
                ->where('contestant.user_id', $this->user_login->user->id)
                ->where('offer.status', '1');
        $rewards = $rewards_query->get();
        
        foreach ($rewards as $reward) {
            $offer_code = array_slice($reward->offer->offer_codes, 0, 1);
            $unclaimed = !$offer_code || empty($offer_code[0]->offer_redeems);
            if ($unclaimed) {
                // did it this way (empty code blocks in the ifs) in order to improve readability
                if ($u->get_like_message_status('offer', $reward->offer->id) == -1) {
                    // user has disliked this, don't display it
                } elseif (($like = $u->get_like_message_object('offer', $reward->offer->id)) && $like->updated_at > strtotime(self::SHOW_AGAIN_TIME)) {
                    // user has liked this recently (that means he already saw it, so there's no point in showing it again)
                } elseif (strtotime(self::MAX_TIME_FOR_REWARDS) < $reward->created_at) {
                    array_unshift($messages, array('type' => 'offer', 'details' => $reward->offer));
                }
            }
        }
    }
    
    private function sort_by_recommendations($messages_array, $lat, $long) {
        $u = $this->user_login->user;
        // remove all disliked events and offers
        foreach ($messages_array as $k => $msg) {
            
            if ($u->get_like_message_status($msg['type'], $msg['details']->id) == -1) {
                // user has disliked this, don't display it
                unset($messages_array[$k]);
            } elseif (($like = $u->get_like_message_object($msg['type'], $msg['details']->id)) && $like->updated_at > strtotime(self::SHOW_AGAIN_TIME)) {
                // user has liked this recently (that means he already saw it, so there's no point in showing it again)
                unset($messages_array[$k]);
            } else {
                $messages_array[$k]['like_status'] = $u->get_like_message_status($msg['type'], $msg['details']->id);
            }
        }
        
        $messages_array = Helper_recommendation::sort_by_recommendation_weight($messages_array, $u, $lat, $long);
        
        // in order to restore numeric index
        return array_merge($messages_array);
    }
    
    private function from_nearby($query, $params, $special_events_query = null) {
        $query->related('locations');
        
        $nearby_location_ids = $this->get_nearby_location_ids($params);
        if (!$nearby_location_ids) {
            return false;
        }
        
        $query->where('locations.id', 'in', $nearby_location_ids);
        
        if (!empty($special_events_query)) {
            $special_events_query->related('locations');
            $special_events_query->where('main_location_id', 'in', $nearby_location_ids);
        }
        return true;
	}
    
    private function get_nearby_location_ids($params) {
        $location = $this->get_latitude_and_longitude($params);
        $latitude = $location['latitude'];
        $longitude = $location['longitude'];

        // Get the nearby distance selected by the user or a default one
		$distance = self::DEFAULT_NEARBY_DISTANCE;
        if (isset($params['radius']) && $params['radius'] > 0) {
            $distance = (float)($params['radius']);
        }
        
		// Get the nearby locations ids
		$nearby_location_ids = Model_Location::get_nearby_location_ids($latitude, $longitude, $distance);
        
		if (count($nearby_location_ids) == 0) {
            return array();
		} else {
			return $nearby_location_ids;
		}
    }
    
    private function get_latitude_and_longitude($params) {
        $latitude = $longitude = 0;
        if (isset($params['latitude']) && isset($params['longitude'])) {
			$latitude = $params['latitude'];
			$longitude = $params['longitude'];
		} else {
			$home_location = CMS::get_setting_for_user($this->user_login->user_id, 'home_location');
			if (is_null($home_location)) {
				return false;
			}
			$latitude = $home_location['latitude'];
			$longitude = $home_location['longitude'];
		}
        
        return array('latitude' => $latitude, 'longitude' => $longitude);
    }
    
    private function from_today($query, $params) {
        $current_time = time();
        
        $start_date = date('Y-m-d H:i:s', $current_time + (12 * 3600));
        $end_date   = date('Y-m-d H:i:s', $current_time - (12 * 3600));
        
        $query->where('date_start', '<=', $start_date);
        $query->where('date_end'  , '>=', $end_date);
        return true;
    }
    
    public function action_set_like_status() {
        if (Input::method() != 'POST') { $this->response($this->no_access); return; }
        
        $params = Input::post();
        
        try {
            $entity = $this->get_entity($params);
            if (!$entity) {
                return $this->_error_response(Code::ERROR_INVALID_ENTITY_ID);
            }
        } catch (Exception $e) {
            return $this->_error_response(Code::ERROR_INVALID_ENTITY_TYPE);
        }
        
        $status = (int)$params['status'];
        
        if (!in_array($params['status'], array('-1', '0', '1'))) {
            return $this->_error_response(Code::ERROR_INVALID_LIKE_STATUS);
        }
        
        $user = $this->user_login->user;
        if ($params['type'] == 'offer') {
            // offer likes
            $already_liked_query = Model_Offerlike::query()
                    ->where('user_id', $user->id)
                    ->where('offer_id', $entity->id);
            $already_liked = $already_liked_query->count();

            if (!$already_liked) {
                $like = new Model_Offerlike();
                $like->user_id = $user->id;
                $like->offer_id = $entity->id;
                $like->status = $status;
                $like->save();
            } else {
                $already_liked = $already_liked_query->get_one();
                $already_liked->status = $status;
                $already_liked->save();
            }
        } elseif ($params['type'] == 'event') {
            // event likes
            $already_liked_query = Model_Eventlike::query()
                    ->where('user_id', $user->id)
                    ->where('event_id', $entity->id);
            $already_liked = $already_liked_query->count();

            if (!$already_liked) {
                $like = new Model_Eventlike();
                $like->user_id = $user->id;
                $like->event_id = $entity->id;
                $like->status = $status;
                $like->save();
            } else {
                $already_liked = $already_liked_query->get_one();
                $already_liked->status = $status;
                $already_liked->save();
            }
        } elseif ($params['type'] == 'specialevent') {
            // special event likes
            $already_liked_query = Model_Specialeventlike::query()
                    ->where('user_id', $user->id)
                    ->where('specialevent_id', $entity->id);
            $already_liked = $already_liked_query->count();

            if (!$already_liked) {
                $like = new Model_Specialeventlike();
                $like->user_id = $user->id;
                $like->specialevent_id = $entity->id;
                $like->status = $status;
                $like->save();
            } else {
                $already_liked = $already_liked_query->get_one();
                $already_liked->status = $status;
                $already_liked->save();
            }
        } else {
            return $this->_error_response(Code::ERROR_INVALID_ENTITY_TYPE);
        }

        $event_label = $params['type'] . $entity->id;
        if ($status == 1) {
            Helper_Activity::log_activity($this->user_login->user, 'like_' . $params['type'], array($params['type'] . '_id' => (int)$entity->id));
            $event_label = $params['type'] . $entity->id;
            Helper_Analytics::log_event($this->user_login->user, $params['type'], 'like', $event_label);
        } elseif ($status == -1) {
            Helper_Analytics::log_event($this->user_login->user, $params['type'], 'dislike', $event_label);
        }
        
        $meta = array('error' => null, 'status' => 1);
        $data = array('data' => array(), 'meta' => $meta);
        $this->response($data);
    }
    
    public function action_get_likes() {
        if (Input::method() != 'GET') { $this->response($this->no_access); return; }
        $params = Input::get();
        $user = $this->user_login->user;

        // offer likes
        $offer_likes_query = Model_Offerlike::query()
            ->where('user_id', $user->id);
        
        if (isset($params['like_status']) && in_array($params['like_status'], array('1', '-1'))) {
            $offer_likes_query->where('status', $params['like_status']);
        }
        
        $offer_likes = $offer_likes_query->get();
        $offers = array();
        foreach ($offer_likes as $offer_like) {
            $offer = Model_Offer::query()
                ->where('id', $offer_like->offer_id);
            $like = array(
                'status' => $offer_like->status,
                'offer' => Helper_Api::offer_response($offer->get_one())
            );
            $offers[] = $like;
        }

        // event likes
        $event_likes_query = Model_Eventlike::query()
            ->where('user_id', $user->id);
        
        if (isset($params['like_status']) && in_array($params['like_status'], array('1', '-1'))) {
            $event_likes_query->where('status', $params['like_status']);
        }
        
        $events_likes = $event_likes_query->get();
        $events = array();
        foreach ($events_likes as $event_like) {
            $event = Model_Event::query()
                ->where('id', $event_like->event_id);
            $like = array(
                'status' => $event_like->status,
                'event' => Helper_Api::event_response($event->get_one())
            );
            $events[] = $like;
        }

        // special event likes
        $specialevent_likes_query = Model_Specialeventlike::query()
            ->where('user_id', $user->id);

        if (isset($params['like_status']) && in_array($params['like_status'], array('1', '-1'))) {
            $specialevent_likes_query->where('status', $params['like_status']);
        }

        $specialevents_likes = $specialevent_likes_query->get();
        $specialevents = array();
        foreach ($specialevents_likes as $specialevent_like) {
            $specialevent = Model_Specialevent::query()
                ->where('id', $specialevent_like->specialevent_id);
            $like = array(
                'status' => $specialevent_like->status,
                'event' => Helper_Api::event_response($specialevent->get_one())
            );
            $specialevents[] = $like;
        }
    
        $meta = array('error' => null, 'status' => 1);
        $data = array('data' => array(
            'offers' => $offers,
            'events' => array_merge($events, $specialevents),
        ), 'meta' => $meta);
        
        $this->response($data);
    }
    
    private function get_entity($params) {
        $id = $params['entity_id'];
        if ($params['type'] == 'offer') {
            return Model_Offer::find($id);
        } elseif ($params['type'] == 'event') {
            return Model_Event::find($id);
        } elseif ($params['type'] == 'specialevent') {
            return Model_Specialevent::find($id);
        }
        throw new Exception('Invalid entity type');
    }
}
