<?php

class Model_Location extends \Orm\Model
{
	const TYPE_MALL = 'Mall';
	const TYPE_MERCHANT = 'Merchant';

	// TODO: Check how the different statuses for locations are handled
	const STATUS_DELETED  = 0;
	const STATUS_ACTIVE   = 1;
	const STATUS_INACTIVE = 2;
	const STATUS_DRAFT    = 3;
	const STATUS_BLOCKED  = 4;
	const STATUS_SIGNUP   = 5;

	protected static $_many_many = array(
		'offers' => array(
			'key_through_to' => 'offer_id',
			'table_through' => 'locations_offers',
		),
	    'events' => array(
			'key_through_to' => 'event_id',
			'table_through' => 'locations_events',
		),
		'favorited_users' => array(
			'table_through' => 'users_favorite_locations',
			'key_through_to' => 'user_id',
			'model_to' => 'Model_User',
		),
		'categories' => array(
			'key_through_from' => 'location_id',
	        'table_through' => 'categories_locations',
		),
            'profilings' => array(
                    'model_to' => 'Model_Profilingchoice',
                    'key_through_from' => 'location_id',
                    'key_through_to' => 'profilingchoice_id',
                    'table_through' => 'profilings_locations',
                ),
	);

	protected static $_has_many = array(
		'location_blockings' => array(
			'key_to' => 'location_id',
		),
	    'location_requests',
        'location_managers' => array(
            'key_to' => 'location_id',
        ),
	    'healthmetrics' => array(
	        'key_to' => 'location_id',
	    ),
        'location_likes' => array(
            'key_to' => 'location_id',
        ),
        'location_checkins' => array(
            'key_to' => 'location_id',
        ),
	);
	
    protected static $_has_one = array(
        'micello_info' => array(
            'model_to' => 'Model_Micello_Info',
            'key_to' => 'location_id',
        )
    );

    protected static $_belongs_to = array(
    	'user_instagram',
        'country',
    );
    
	protected static $_properties = array(
		'id',
		'type',
		'status',
        'is_customer' => array(
	        'default' => TRUE,
        ),
		'name',
		'mall_id',
		'floor',
		'address',
		'city',
		'st',
	    'country_id',
	    'zip',
		'contact',
		'email',
		'phone',
		'social' => array(
			'data_type' => 'json',
		),
		'web',
		'newsletter',
		'tags',
		'plan',
		'max_users',
		'content',
		'hours' => array(
			'data_type' => 'json',
		),
		'logo',
		'landing_screen_img',
		'latitude',
		'longitude',
        'timezone',
		'description',
		'wifi',
		'market_place_type',
		'auto_generated',
        'setup_complete',
	    'default_social',
	    'default_logo',
	    'default_landing_screen_img',
        'manually_updated',
	    'use_instagram' => array(
                'data_type' => 'int',
	        'default' => false,
            ),
	    'user_instagram_id',
		'created_at',
		'updated_at' => array('data_type' => 'int'),
		'created_by',
		'edited_by',
	);

	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'mysql_timestamp' => false,
		),
		'Orm\Observer_UpdatedAt' => array(
			'events' => array('before_save'),
			'mysql_timestamp' => false,
		),
		'Orm\\Observer_Self' => array(
			'events' => array('before_save')
		),
		'Orm\\Observer_Typing',
	);
	
	protected static $_fields_relevance = array(
        'name'        => 5.0,
        'description' => 1.5,
        'email'       => 2.0,
        'web'         => 2.0,
        'tags'        => 2.5,
        'address'     => 1.0,
        'city'        => 1.0,
        'st'          => 1.0,
        'zip'         => 1.0,
	);
	
	public static function get_nearby_location_ids($latitude, $longitude, $radius, $limit = null) {
		$origin_point = Geo::build_coordinates($latitude, $longitude);
		// Calculate the coordinates of the edges of the rectangle
		list ($upper_left_point, $lower_right_point) = Geo::get_rectangle_coordinates($origin_point, $radius);
        
        $query = DB::select('id', 'latitude', 'longitude')->from('locations');
        static::apply_type_discriminator($query);
	
		// Get locations within the rectangle
		$query
			->where('status', '1')
			->where('longitude', 'between', array($upper_left_point->longitude, $lower_right_point->longitude))
			->where('latitude', 'between', array($upper_left_point->latitude, $lower_right_point->latitude));
	
        $locations = $query->as_object()->execute();
        
		$nearby = array();
		$distances = array();

		// Refine the results of the search to get only the locations inside the radius
		foreach ($locations as $location) {
            $id = $location->id;
			$destination_point = Geo::build_coordinates($location->latitude, $location->longitude);
			if (($location_distance = Geo::calculate_distance($origin_point, $destination_point)) <= $radius) {
				$nearby[$id] = $location;
				$distances[$id] = $location_distance;
			}
		}

		// If a limit for the results is set, get only the nearest locations within that limit 
		if (!is_null($limit)) {
			asort($distances, SORT_NUMERIC);
			$distances = array_slice($distances, 0, $limit, true);
			$nearby = array_intersect_key($nearby, $distances);
		}

		return array_keys($nearby);
	}
    
    /**
     * Helper method to sort a collection by the distance of each element to the point
     * specified by $latitude,$longitude. Every element of the collection should have
     * one of the following three possibilities:
     * 
     * - element->(latitude,longitude)
     * - element->location->(latitude,longitude)
     * - element->locations[0..i]->(latitude,longitude)
     * 
     * In the last case the location with the minimum distance is going to be used
     * to sort.
     * 
     */
    public static function sort_by_proximity($latitude, $longitude, $collection, $ascending = true) {
        $point = (object) array('latitude' => $latitude, 'longitude' => $longitude);
        $distances = array();
        
        foreach ($collection as $k => $element) {
            $distance = 0;
            
            if (isset($element->latitude) && isset($element->longitude)) {
                $distance = Geo::calculate_distance($point, $element);
            } elseif (isset($element->location) && isset($element->location->latitude) && isset($element->location->longitude)) {
                $distance = Geo::calculate_distance($point, $element->location);
            } elseif (isset($element->locations) && !empty($element->locations)) {
                $this_element_distances = array();
                foreach ($element->locations as $location) {
                    $this_element_distances[] = Geo::calculate_distance($point, $location);
                }
                $distance = min($this_element_distances);
            } elseif (is_array($element) && isset($element['latitude']) && isset($element['longitude'])) {
                //perhaps it's an assoc array?
                $distance = Geo::calculate_distance($point, (object)$element);
            } else {
                $distance = 1000; // distance could not be determined!
            }
            
            $distances[$k] = $distance;
        }
        
        if ($ascending) {
            asort($distances);
        } else {
            arsort($distances);
        }
        
        $sorted = array();
        foreach ($distances as $k => $distance) {
            $sorted[] = $collection[$k];
        }
        
        return $sorted;
    } 

	public static function get_json_properties() {
		$json_fields = array();
		foreach(static::$_properties as $property => $definition) {
			if (isset($definition['data_type']) && $definition['data_type'] == 'json') {
				$json_fields[] = $property;
			}
		}
		return $json_fields;
	}
    
    public static function apply_type_discriminator($query) {
        return $query;
    }

    public function active_offers_count() {
        $result = DB::select(DB::expr('COUNT(*) as total_count'))
            ->from('offers')
            ->join('locations_offers')
            ->on('offers.id', '=', 'locations_offers.offer_id')
            ->where('locations_offers.location_id', $this->id)
            ->where('offers.status', 1)
            ->where('offers.date_start', '<', DB::expr('NOW()'))
            ->where('offers.date_end', '>', DB::expr('NOW()'))
            ->execute();
        $result_arr = $result->current();
        return $result_arr['total_count'];
    }
    
    public function active_events_count() {
    	$result = DB::select(DB::expr('COUNT(*) as total_count'))
        	->from('events')
        	->join('locations_events')
        	->on('events.id', '=', 'locations_events.event_id')
        	->where('locations_events.location_id', $this->id)
        	->where('events.status', 1)
        	->where('events.date_start', '<', DB::expr('NOW()'))
        	->where('events.date_end', '>', DB::expr('NOW()'))
        	->execute();
    	$result_arr = $result->current();
    	return $result_arr['total_count'];
    }
    
    public function empty_hours() {
        $empty = true;
        if (is_array($this->hours)) {
            $this->hours = json_decode(json_encode($this->hours));
        }
        
        foreach ($this->hours as $dayOfWeek => $timeRangeForDay) {
            if ($timeRangeForDay->open != '' || $timeRangeForDay->close != '') {
                $empty = false;
                break;
            }
        }
        
        return $empty;
    }
    
    public static function get_fields_relevance() {
        return static::$_fields_relevance;
    }
}
