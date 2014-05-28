<?php

class Controller_Api_Indoor extends Controller_Api {

	public function action_points() {
	    if (Input::method() != 'GET') { $this->response($this->no_access); return; }
	    
	    // The id of the mall/standalone merchant
	    $location_id = $this->param('id', 0);
	    $location_model = Model_Location::find($location_id);
	    
	    // Only malls and standalone merchants are allowed
	    if (!$location_model || !empty($location_model->mall_id)) {
	    	return $this->_error_response(Code::ERROR_INVALID_LOCATION_ID);
	    }

	    $data = array(
	    	'merchants' => $this->get_merchants($location_id),
	    	'flags' => $this->get_flags($location_id),
	    );
	    $response = array('data' => $data, 'meta' => array('error' => null, 'status' => 1));
	    $this->response($response);
	}
	
	private function get_merchants($location_id) {
		$current_user = $this->user_login->user;

		$favorites_table = DB::table_prefix('users_favorite_locations');
		
	    $query = DB::select('locations.id',
	                'locations.name',
// 	                'locations.description',
	                'micello_infos.geometry_id',
	                array(DB::expr("NOT ISNULL($favorites_table.location_id)"), 'favorite'))
    		->from('locations')
    		->join('micello_infos', 'LEFT')
    		->on('locations.id', '=', 'micello_infos.location_id')
    		->join('users_favorite_locations', 'LEFT')
    		->on('locations.id', '=', 'users_favorite_locations.location_id')
    		->on('users_favorite_locations.user_id', '=', DB::expr($current_user->id))
    		->where('locations.type', Model_Location::TYPE_MERCHANT)
    		->where('locations.mall_id', $location_id)
	        ->where('locations.status', 1);

		$merchants = $query->distinct()->execute()->as_array();

		$merchant_ids = array('-1');
		foreach($merchants as $merchant) {
			$merchant_ids[] = $merchant['id'];
		}

		$current_date = date('Y-m-d H:i:s');
		$upcoming_date = date('Y-m-d H:i:s', strtotime(Controller_Api_Offer::UPCOMING_TIME));
		
		$offers_counts_raw = DB::select('locations_offers.location_id', array(DB::expr("COUNT(*)"), 'count'))
		    ->from('offers')
    		->join('locations_offers')
    		->on('offers.id', '=', 'locations_offers.offer_id')
    		->where('status', 1)
    		->where('locations_offers.location_id', 'in', $merchant_ids)
    		->and_where_open()
    		->or_where('date_start', 'between', array($current_date, $upcoming_date))
    		->or_where('date_start', '<=', $current_date)
    		->and_where_close()
    		->where('date_end', '>=', $current_date)
    		->group_by('locations_offers.location_id')
    		->as_object()
    		->execute();

		$offers_counts = array();
		foreach($offers_counts_raw as $offers_count_raw) {
		    $offers_counts[$offers_count_raw->location_id] = $offers_count_raw->count;
		}

		$current_date = date('Y-m-d');
		$upcoming_date = date('Y-m-d', strtotime(Controller_Api_Event::UPCOMING_TIME));

		$events_counts_raw = DB::select('locations_events.location_id', array(DB::expr("COUNT(*)"), 'count'))
    		->from('events')
    		->join('locations_events')
    		->on('events.id', '=', 'locations_events.event_id')
    		->where('status', 1)
    		->where('locations_events.location_id', 'in', $merchant_ids)
			->and_where_open()
            ->where('date_start', '<=', $current_date)
            ->where('date_end', '>=', $current_date)
			->or_where('date_start', 'between', array($current_date, $upcoming_date))
			->and_where_close()
    		->group_by('locations_events.location_id')
    		->as_object()
    		->execute();
		
		$events_counts = array();
		foreach($events_counts_raw as $events_count_raw) {
			$events_counts[$events_count_raw->location_id] = $events_count_raw->count;
		}
		
		foreach($merchants as &$merchant) {
		    $merchant['favorite'] = (bool)$merchant['favorite'];
		    $id = $merchant['id'];
		    $merchant['offers_count'] = isset($offers_counts[$id]) ? (int)$offers_counts[$id] : 0;
		    $merchant['events_count'] = isset($events_counts[$id]) ? (int)$events_counts[$id] : 0;
		}
		return $merchants;
	}
	
	private function get_flags($location_id) {
		$current_user = $this->user_login->user;

		$owner_ids = array($current_user->id);
		$following = $current_user->get_following();
		foreach ($following as $following_user) {
			$owner_ids[] = $following_user->id;
		}

		$flags = array();

		$all_flags = Model_Flag::get_flags($owner_ids, FALSE, $current_user, NULL, $location_id);

		foreach($all_flags as $found_flag) {
			$flag = new stdClass();
			$flag->id          = $found_flag->id;
			$flag->title       = $found_flag->title;
// 			$flag->description = $found_flag->description;
			$flag->type        = $found_flag->type;
			$flag->floor       = $found_flag->floor;
			$flag->latitude    = $found_flag->latitude;
			$flag->longitude   = $found_flag->longitude;
			$flag->owner       = $found_flag->owner_id;
			$flags[] = $flag;
		}

		return $flags;
	}
}
