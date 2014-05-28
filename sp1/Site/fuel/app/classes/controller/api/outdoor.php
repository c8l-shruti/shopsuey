<?php

use Fuel\Core\DB;

class Controller_Api_Outdoor extends Controller_Api {

    // Numbers of suggestions of each type to return
    const DEFAULT_SUGGESTIONS_COUNT = 25;
    
	public function action_suggestions() {
		if (Input::method() != 'GET') { $this->response($this->no_access); return; }

		$latitude = Input::param('latitude');
		$longitude = Input::param('longitude');
		$radius = Input::param('radius');
		
		$order_by = Input::param('order_by', 'distance');

		if ($order_by == 'distance' && (is_null($latitude) || is_null($longitude))) {
			return $this->_error_response(Code::ERROR_MISSING_POSITION);
		}

		$limit = Input::param('limit', self::DEFAULT_SUGGESTIONS_COUNT);
		$keyword = Input::param('keyword');
		
		$data = array(
		    'merchants' => $this->search_locations('merchant', $latitude, $longitude, $radius, $order_by, $limit, $keyword),
		    'malls' => $this->search_locations('mall', $latitude, $longitude, $radius, $order_by, $limit, $keyword),
		    'flags' => $this->search_flags($latitude, $longitude, $radius, $order_by, $limit, $keyword),
	    );
		$response = array('data' => $data, 'meta' => array('error' => null, 'status' => 1));
		$this->response($response);
	}

	public function action_around() {
		if (Input::method() != 'GET') { $this->response($this->no_access); return; }

		$latitude = Input::param('latitude');
		$longitude = Input::param('longitude');
		$radius = Input::param('radius');
		
		if (is_null($latitude) || is_null($longitude) || empty($radius)) {
			return $this->_error_response(Code::ERROR_MISSING_POSITION);
		}

		$data = array(
		    'merchants' => $this->search_locations('merchant', $latitude, $longitude, $radius),
		    'malls' => $this->search_locations('mall', $latitude, $longitude, $radius),
		    'flags' => $this->search_flags($latitude, $longitude, $radius),
	    );
		$response = array('data' => $data, 'meta' => array('error' => null, 'status' => 1));
		$this->response($response);
	}
	
	private function search_locations($type, $latitude, $longitude, $radius, $order_by = NULL, $limit = NULL, $keyword = NULL) {
	    if ($type == 'merchant') {
	        $model = 'Merchant';
	    } elseif ($type == 'mall') {
	        $model = 'Mall';
	    }
	    $query = DB::select('locations.id', 'locations.name', 'locations.latitude', 'locations.longitude', 'locations.is_customer', 'micello_infos.micello_id')
	        ->from('locations')
	        ->join('micello_infos', 'LEFT')
	        ->on('locations.id', '=', 'micello_infos.location_id')
	        ->where('locations.type', $model)
	        ->where('locations.status', '1');

	    // Only standalone merchants
	    if ($type == 'merchant') {
	        $query->and_where_open();
	        $query->or_where('locations.mall_id', NULL);
	        $query->or_where('locations.mall_id', 0);
	        $query->and_where_close();
	    }
	     
		$query
    		->where('locations.longitude', '!=', NULL)
    		->where('locations.latitude', '!=', NULL);
		
		if ($radius) {
		    $origin_point = Geo::build_coordinates($latitude, $longitude);

		    // Calculate the coordinates of the edges of the rectangle
    		list ($upper_left_point, $lower_right_point) = Geo::get_rectangle_coordinates($origin_point, $radius);

    		// Get locations within the rectangle
    		$query
        		->where('locations.longitude', 'between', array($upper_left_point->longitude, $lower_right_point->longitude))
        		->where('locations.latitude', 'between', array($upper_left_point->latitude, $lower_right_point->latitude));
		}
		    
	    if (! is_null($keyword)) {
    		$search_fields = array('name', 'description', 'tags');
    		$query->and_where_open();
    		foreach ($search_fields as $field) {
    			$query->or_where("locations.$field", 'like', "%$keyword%");
    		}
    		$query->and_where_close();
	    }
	    
	    if ($order_by == 'distance') {
	    	$query->order_by(DB::expr('`distance`'), 'asc');
	    }
	    
	    /*
	    if ($type == 'merchant') {
	        $query->join(array('locations', 'mall'), 'LEFT')
	            ->on('locations.mall_id', '=', 'mall.id')
	            ->select(array('mall.id', 'mall_id'), array('mall.name', 'mall_name'));
	    }
	    */

        if ($order_by == 'distance') {
            $esc_latitude  = DB::escape($latitude);
            $esc_longitude = DB::escape($longitude);
            $l = DB::table_prefix('locations');
            $query
	    	    ->select(DB::expr(Geo::EARTH_RADIUS . " * 2 * ASIN(SQRT(POWER(SIN(({$esc_latitude} - $l.latitude) * PI()/180 / 2), 2) + COS({$esc_latitude} * PI()/180) * COS($l.latitude * PI()/180) * POWER(SIN(({$esc_longitude} - $l.longitude) * PI()/180 / 2), 2) )) AS `distance`"));
	    }
	    
	    if (! is_null($limit)) {
	        $query->limit($limit);
	    }
	    $locations = $query->distinct()->execute()->as_array();

	    foreach($locations as &$location) {
	        unset($location['distance']);
	        $location['is_customer'] = (bool)$location['is_customer'];
	    }

        return $locations;
	}
	
	private function search_flags($latitude, $longitude, $radius, $order_by = NULL, $limit = NULL, $keyword = NULL) {
	    $current_user = $this->user_login->user;
	    
	    $owner_ids = array($current_user->id);
	    $following = $current_user->get_following();
	    foreach ($following as $following_user) {
	    	$owner_ids[] = $following_user->id;
	    }
	    
	    $origin = Geo::build_coordinates($latitude, $longitude);
	    $flags = array();
	    
	    $all_flags = Model_Flag::get_nearby_flags($latitude, $longitude, $radius, $owner_ids, FALSE, $current_user, $keyword, -1);

	    foreach($all_flags as $found_flag) {
	        $flag = new stdClass();
	        $flag->id = $found_flag->id;
	        $flag->title = $found_flag->title;
// 	        $flag->description = $found_flag->description;
	        $flag->type = $found_flag->type;
	        $flag->latitude = $found_flag->latitude;
	        $flag->longitude = $found_flag->longitude;
	        $flag->owner = $found_flag->owner_id;
	        if ($order_by == 'distance') {
    	        $flag_point = Geo::build_coordinates($flag->latitude, $flag->longitude);
    	        $flag->distance = (string)Geo::calculate_distance($origin, $flag_point);
	        }
	        $flags[] = $flag;
	    }

        if ($order_by == 'distance') {
    	    usort($flags, function($a, $b) {
    	        if ($a->distance < $b->distance) {
    	            return -1;
    	        } elseif ($a->distance > $b->distance) {
    	            return 1;
    	        } else {
    	            return 0;
    	        }
    	    });
        }
	    
        foreach($flags as &$flag) {
        	unset($flag->distance);
        }
        
        if (! is_null($limit)) {
            $flags = array_slice($flags, 0, $limit);
        }
        
        return $flags;
	}
}
