<?php

class Helper_Micello {

    private static $_days = array(1 => 'mon', 2 => 'tue', 3 => 'wed', 4 => 'thr', 5 => 'fri', 6 => 'sat', 7 => 'sun');

    public static function create_mall($community) {
        $mall = new \Model_Mall();
        $mall->status = 1;
//         $mall->is_customer = 1;
        $mall->contact = '';
        $mall->email = '';
        $mall->phone = '';
        $mall->social = self::new_social_object();
        $mall->web = '';
        $mall->newsletter = '';
        $mall->tags = '';
        $mall->plan = '0';
        $mall->max_users = '0';
        $mall->content = '';
        $mall->hours = self::new_hours_array();
        $mall->logo = '';
        $mall->description = '';
        $mall->wifi = '';
        $mall->market_place_type = '';
        $mall->auto_generated = 1;
        $mall->setup_complete = 0;
        $mall->manually_updated = 0;
        $mall->timezone = '';
        $mall->use_instagram = 0;
        $mall->created_by = $mall->edited_by = '0';
         
        $micello_info = new \Model_Micello_Info();
        $mall->micello_info = $micello_info;
        
        self::update_location_from_community($mall, $community);
        
        return $mall;
    }
    
    public static function create_standalone_merchant($community) {
        $merchant = new \Model_Merchant();
        $merchant->status = 1;
//         $merchant->is_customer = 1;
        $merchant->mall_id = NULL;
        $merchant->floor = NULL;
        $merchant->contact = '';
        $merchant->email = '';
        $merchant->phone = '';
        $merchant->social = self::new_social_object();
        $merchant->web = '';
        $merchant->newsletter = '';
        $merchant->tags = '';
        $merchant->content = '';
        $merchant->hours = self::new_hours_array();
        $merchant->logo = '';
        $merchant->max_users = '0';
        $merchant->plan = '0';
        $merchant->description = '';
        $merchant->auto_generated = 1;
        $merchant->setup_complete = 0;
        $merchant->manually_updated = 0;
        $merchant->timezone = '';
        $merchant->use_instagram = 0;
        $merchant->created_by = $merchant->edited_by = '0';

        $micello_info = new \Model_Micello_Info();
        $merchant->micello_info = $micello_info;

        self::update_location_from_community($merchant, $community);

        return $merchant;
    }
    
    public static function update_location_from_community(&$location, $community) {
        if (empty($location->name) || !$location->manually_updated) { $location->name = $community->name; }
        if (empty($location->address) || !$location->manually_updated) { $location->address = $community->street1; }
        if (empty($location->city) || !$location->manually_updated) { $location->city = $community->city; }
        if (empty($location->st) || !$location->manually_updated) { $location->st = \Helper_Api::get_state_code($community->state); }
        if (empty($location->country_id) || !$location->manually_updated) { $location->country = \Model_Country::get_by_name($community->country); }
        if (empty($location->zip) || !$location->manually_updated) { $location->zip = $community->zipcode; }
        if (empty($location->latitude) || !$location->manually_updated) { $location->latitude = $community->lat; }
        if (empty($location->longitude) || !$location->manually_updated) { $location->longitude = $community->lon; }
        
        $location->micello_info->micello_id = $community->id;
        $location->micello_info->type = \Model_Micello_Info::TYPE_COMMUNITY;
    }
    
    public static function create_merchant($entity, $mall) {
        $merchant = new \Model_Merchant();
        $merchant->mall_id = $mall->id;
        $merchant->status = 1;
//         $merchant->is_customer = 0;
        $merchant->address = $mall->address;
        $merchant->city = $mall->city;
        $merchant->st = $mall->st;
        if (! empty($mall->country)) {
            $merchant->country = $mall->country;
        } else {
            $merchant->country = Model_Country::get_default();
        }
        $merchant->zip = $mall->zip;
        $merchant->latitude = $mall->latitude;
        $merchant->longitude = $mall->longitude;
        $merchant->contact = '';
        $merchant->email = '';
        $merchant->phone = '';
        $merchant->social = self::new_social_object();
        $merchant->web = '';
        $merchant->newsletter = '';
        $merchant->tags = '';
        $merchant->content = '';
        $merchant->description = '';
        if (!self::empty_hours_object($mall->hours)) {
        	$merchant->hours = $mall->hours;
        } else {
            $merchant->hours = self::new_hours_array();
        }
        $merchant->logo = '';
        $merchant->max_users = '0';
        $merchant->plan = '0';
        $merchant->auto_generated = 1;
        $merchant->setup_complete = 0;
        $merchant->manually_updated = 0;
        $merchant->timezone = '';
        $merchant->use_instagram = 0;
        $merchant->created_by = $merchant->edited_by = '0';
        
        $micello_info = new \Model_Micello_Info();
        $micello_info->type = Model_Micello_Info::TYPE_ENTITY;
        $merchant->micello_info = $micello_info;

        if (!is_null($entity)) {
            self::update_merchant_from_entity($merchant, $entity);
        }
        
        return $merchant;
    }

    public static function update_merchant_from_entity(&$merchant, $entity) {
        if (empty($merchant->name) || !$merchant->manually_updated) { $merchant->name = $entity->nm; }
        if (empty($merchant->floor) || !$merchant->manually_updated) { $merchant->floor = $entity->lnm; }

        $merchant->micello_info->type = \Model_Micello_Info::TYPE_ENTITY;
        $merchant->micello_info->micello_id = $entity->eid;
        $merchant->micello_info->geometry_id = $entity->gid;
        
        // Fetch info from micello
        $micello_entity_info = self::get_micello_entity_info($entity->eid);
        if (!is_null($micello_entity_info)) {
        	self::fill_micello_info_into_merchant($micello_entity_info, $merchant);
        }
    }
    
    public static function add_merchants_to_mall($data, $mall) {
        // Create merchants for mall if needed and copy info from mall for each one
        \Package::load('micello');
        try {
            $result = \Micello\Api::get_entities($data->id);
        } catch (\Micello\MicelloException $e) {
            return FALSE;
        }

        $entities = $result->results;

        foreach($entities as $entity) {
            $mall->merchants[] = self::create_merchant($entity, $mall);
        }
    }
    
    public static function new_social_object() {
        $social = new \stdClass();
        $social->twitter = '';
        $social->facebook = '';
        $social->foursquare = '';
        $social->pintrest = '';
        return $social;
    }
    
    public static function new_hours_array() {
        $hours = array();
        foreach (self::$_days as $key => $name) {
            $hours[$name] = array('open' => '', 'close' => '');
        }
        return $hours;
    }
    
    public static function valid_social_object($social) {
        return !is_null($social)
            && is_object($social)
            && isset($social->twitter)
            && isset($social->facebook)
            && isset($social->foursquare)
            && isset($social->pintrest);
    }
    
    public static function valid_hours_object($hours) {
        $hours = (array)$hours;
        foreach (self::$_days as $key => $name) {
            if (isset($hours[$name])) {
                $day = (array)$hours[$name];
                if (!isset($day['open']) || !isset($day['close'])) {
                    return FALSE;
                }
            } else {
                return FALSE;
            }
        }
        return TRUE;
    }
    
    public static function empty_hours_object($hours) {
        if (empty($hours)) {
            return TRUE;
        }
        $hours = (array)$hours;
        foreach (self::$_days as $key => $name) {
            $day = (array)$hours[$name];
            if (!empty($day['open']) || !empty($day['close'])) {
                return FALSE;
            }
        }
        return TRUE;
    }

    public static function get_foursquare_info($location, $type) {
        static $categories = array(
            'mall' => array('Mall'),
            'standalone_merchant' => array(
                'Hardware Store', 'Furniture / Home Store', 'Electronics Store',
                'Department Store', 'Grocery Store', 'Bar', 'Miscellaneous Shop',
            ),
    	);

    	\Package::load('foursquare');

        try {
    	    $venues = \Foursquare\Api::search_exact_venue($location->latitude, $location->longitude, $location->name);
        } catch (\Foursquare\FoursquareException $e) {
            return NULL;
        }
    
        if (!isset($venues->venues)) {
            return NULL;
        }
    
        if ($type == 'standalone_merchant' && preg_match('/.*-.*/', $location->name)) {
            list($location_name, $location_geo) = explode('-', $location->name);
        } else {
            $location_name = $location->name;
        }

        $allowed_categories = $categories[$type];
        // Distance should be 500 meters as most
        $min_distance = 500;
        $min_similarity_percent = 80;
        $venue_match = NULL;
        foreach($venues->venues as $venue) {
            $valid_category = FALSE;
            foreach ($venue->categories as $category) {
                if (in_array($category->name, $allowed_categories)) {
                    $valid_category = TRUE;
                    break;
                }
            }
            // similar_text function returns different values when the parameters are passed in different order
            similar_text($location_name, $venue->name, $similarity_percent1);
            similar_text($venue->name, $location_name, $similarity_percent2);
            $similarity_percent = max($similarity_percent1, $similarity_percent2);
            if ($valid_category && isset($venue->location->distance) && $venue->location->distance < $min_distance && $similarity_percent >= $min_similarity_percent) {
                $min_distance = $venue->location->distance;
                $venue_match = $venue;
            }
        }
 
        if (!is_null($venue_match)) {
            try {
                $venue_full_info = \Foursquare\Api::get_venue($venue_match->id);
            } catch (\Foursquare\FoursquareException $e) {
                return $venue_match;
            }

            try {
                $venue_full_info->hours = self::process_foursquare_hours(\Foursquare\Api::get_venue_hours($venue_match->id));
            } catch (\Foursquare\FoursquareException $e) {
                $venue_full_info->hours = NULL;
            }

            return $venue_full_info;
        } else {
            return NULL;
        }
    }
    
    public static function process_foursquare_hours($foursquare_hours) {
        $hours = self::new_hours_array();
        
        if (!isset($foursquare_hours->hours) || !isset($foursquare_hours->hours->timeframes)) {
            return $hours;
        }
        
        foreach($foursquare_hours->hours->timeframes as $time_frame) {
            $open_times = array_shift($time_frame->open);
            $times = array(
                'open' => date('h:iA', strtotime($open_times->start)),
                'close' => date('h:iA', strtotime($open_times->end)),
            );
            foreach($time_frame->days as $key => $number) {
                $hours[self::$_days[$number]]['open'] = $times['open'];
                $hours[self::$_days[$number]]['close'] = $times['close'];
            }
        }
        
        return $hours;
    }
    
    public static function get_yelp_info($location, $type) {
        \Package::load('yelp');

        try {
            $result = \Yelp\Api::search_businesses_by_term_and_location($location->name, $location->latitude, $location->longitude);
        } catch (\Yelp\YelpException $e) {
            return NULL;
        }

        if (!isset($result->businesses)) {
            return NULL;
        }

        if ($type == 'standalone_merchant' && preg_match('/.*-.*/', $location->name)) {
            list($location_name, $location_geo) = explode('-', $location->name);
        } else {
            $location_name = $location->name;
        }
    	 
        // Distance should be 500 meters as most
        $min_distance = 500;
        $min_similarity_percent = 80;
        $business_match = NULL;
        foreach($result->businesses as $business) {
            // similar_text function returns different values when the parameters are passed in different order
            similar_text($location_name, $business->name, $similarity_percent1);
            similar_text($business->name, $location_name, $similarity_percent2);
            $similarity_percent = max($similarity_percent1, $similarity_percent2);
            if (isset($business->distance) && $business->distance < $min_distance && $similarity_percent >= $min_similarity_percent) {
                $min_distance = $business->distance;
                $business_match = $business;
            }
        }

        return $business_match;
    }
    
    public static function fill_foursquare_info_into_location($venue_full_info, &$location) {
        $foursquare_fields_filled = array();

        if (isset($venue_full_info->venue->contact->formattedPhone) && (empty($location->phone) || !$location->manually_updated)) {
            $location->phone = $venue_full_info->venue->contact->formattedPhone;
            $foursquare_fields_filled[] = 'phone';
        }
        if (isset($venue_full_info->venue->contact->twitter) && (empty($location->social->twitter) || !$location->manually_updated)) {
            $location->social->twitter = "https://twitter.com/" . $venue_full_info->venue->contact->twitter;
            $foursquare_fields_filled[] = 'social[twitter]';
        }
        if (isset($venue_full_info->venue->url) && (empty($location->web) || !$location->manually_updated)) {
            $location->web = $venue_full_info->venue->url;
            $foursquare_fields_filled[] = 'web';
        }
        if (isset($venue_full_info->venue->description) && (empty($location->description) || !$location->manually_updated)) {
            $location->description = $venue_full_info->venue->description;
            $foursquare_fields_filled[] = 'description';
        }
        // 	    if (isset($venue_full_info->venue->tags) && empty($location->tags)) {
        // 	        $location->tags = $venue_full_info->venue->tags;
        // 	    }
        if ((self::empty_hours_object($location->hours) || !$location->manually_updated) && !self::empty_hours_object($venue_full_info->hours)) {
            $location->hours = $venue_full_info->hours;
            $foursquare_fields_filled[] = 'hours';
        }

        return $foursquare_fields_filled;
    }
    
    public static function fill_yelp_info_into_location($business_info, &$location) {
        $yelp_fields_filled = array();
        
        if (isset($business_info->display_phone) && (empty($location->phone) || !$location->manually_updated)) {
            $location->phone = $business_info->display_phone;
            $yelp_fields_filled[] = 'phone';
        }
        
        return $yelp_fields_filled;         
    }
    
    public static function add_social_network_info(&$location, $type) {
        // Try to fetch data from foursquare. Fill additional info if found
        $venue_full_info = self::get_foursquare_info($location, $type);
        
        if (!is_null($venue_full_info)) {
        	self::fill_foursquare_info_into_location($venue_full_info, $location);
        } else {
        	// If no data from foursquare is found, try yelp
        	$business_info = self::get_yelp_info($location, $type);
        	if (!is_null($business_info)) {
        		self::fill_yelp_info_into_location($business_info, $location);
        	}
        }
    }

    public static function get_micello_entity_info($entity_id) {
        \Package::load('micello');
        try {
            $entity_info = \Micello\Api::get_entity_info($entity_id);
        } catch (\Micello\MicelloException $e) {
            return NULL;
        }
        return $entity_info;
    }
    
    public static function fill_micello_info_into_merchant($entity_info, &$merchant) {
        $micello_fields_filled = array();
        
        if (isset($entity_info->results)) {
            foreach ($entity_info->results as $info_field) {
                $field = NULL;
                switch($info_field->name) {
                    case 'phone':
                        $field = 'phone';
                        break;
                    case 'email':
                        $field = 'email';
                        break;
                    case 'url':
                        $field = 'web';
                        break;
                    case 'description':
                        $field = 'description';
                        break;
                    default:
                }
                if (!is_null($field) && (empty($merchant->$field) || !$merchant->manually_updated)) {
                    $merchant->$field = $info_field->value;
                    $micello_fields_filled[] = $field;
                }
            }
        }
        
        return $micello_fields_filled;
    }
    
    public static function update_map(&$micello_info) {
        if (time() > strtotime($micello_info->map_expiracy) || $micello_info->map == null) {
            // Database cache is expired, fetch the map info again
            Package::load('micello');
            try {
                $map_info = Micello\Api::get_community_map($micello_info->micello_id);
            } catch (Micello\MicelloException $e) {
                $message = $e->getMessage() . ' [' . $e->getCode() . ']';
                throw new Exception($message, Code::ERROR_MICELLO_REQUEST);
            }
            
            $micello_info->map_expiracy = date('Y-m-d H:i:s', strtotime(\Config::get('cms.micello_map_validity'), time()));
            
            if ($map_info->v > $micello_info->map_version || $micello_info->map == null) {
                $micello_info->map_version = $map_info->v;
                $micello_info->map = $map_info;
                return TRUE;
            }
        }
        return FALSE;
    }

    /**
     * Given a mall, it updates the coordinates of the merchants to their real values by
     * using the map from micello
     * @param Model_Mall $mall
     */
    public static function update_merchants_coordinates(Model_Mall &$mall) {
        /*
        // The mall should be retrieved by using something similar to the following
        // to avoid doing lots of queries inside this function
        $mall = Model_Mall::query()
            ->related('micello_info')
            ->related('merchants')
            ->related('merchants.micello_info')
            ->where('id', $mall->id)
            ->get_one();
        */
        
        if (! $mall->micello_info) {
            throw new Exception("No Micello data configured for mall", Code::ERROR_NO_MICELLO_INFO);
        }

        // Make sure we have the latest map version
        self::update_map($mall->micello_info);
        
        // Fetch merchants for given mall. Index them by geometry_id
        $merchants_gid = array();
        foreach($mall->merchants as $merchant) {
            // Where are only interested on merchants that have micello info
            if ($merchant->micello_info && !empty($merchant->micello_info->geometry_id)) {
                $merchants_gid[$merchant->micello_info->geometry_id] = $merchant;
            }
        }

        $map_info = $mall->micello_info->map->d[0];
        
        for($i = 0; $i < count($map_info->l); $i++) {
            $level = $map_info->l[$i];
            for($j = 0; $j < count($level->g); $j++) {
                $geo = $level->g[$j];
                if (isset($merchants_gid[$geo->id]) && isset($geo->l)) {
                    $coordinates = self::calculate_affine_coordinates($map_info->t, $geo->l);
                    $merchants_gid[$geo->id]->latitude  = $coordinates->latitude;
                    $merchants_gid[$geo->id]->longitude = $coordinates->longitude;
                }
            }
        }
    }
    
    public static function calculate_map_bounds($micello_info) {
        $map_info = $micello_info->map->d[0];

        // Upper left corner of the map
        $upper_left_info = array(0.0, 0.0);
        $upper_left_point = self::calculate_affine_coordinates($map_info->t, $upper_left_info);

        // Lower right corner of the map
        $lower_right_info = array($map_info->w, $map_info->h);
        $lower_right_point = self::calculate_affine_coordinates($map_info->t, $lower_right_info);
        
        return array($upper_left_point, $lower_right_point);
    }
    
    private static function calculate_affine_coordinates($affine_info, $entity_info) {
        $longitude = $entity_info[0] * $affine_info[0] + $entity_info[1] * $affine_info[2] + $affine_info[4];
        $latitude  = $entity_info[0] * $affine_info[1] + $entity_info[1] * $affine_info[3] + $affine_info[5];
        return Geo::build_coordinates($latitude, $longitude);
    }
}
