<?php

class Model_Flag extends \Orm\Model
{
    const TYPE_ALOHA      = 'Aloha';
    const TYPE_POI        = 'POI';
    const TYPE_COMMUNITY  = 'Community';
    const TYPE_ATTRACTION = 'Attraction';
     
    protected static $_table_name = 'flags';

    protected static $_many_many = array(
        'invited_users' => array(
			'table_through'  => 'flag_invited_users',
			'key_through_to' => 'user_id',
			'model_to'       => 'Model_User',
        ),
    );
    
    protected static $_belongs_to = array(
        'location' => array(
            'key_from' => 'location_id',
            'model_to' => 'Model_Location',
            'key_to'   => 'id',
        ),
        'owner' => array(
            'key_from' => 'owner_id',
            'model_to' => 'Model_User',
            'key_to'   => 'id',
        )
    );
    
    protected static $_properties = array(
		'id',
		'type',
        'owner_id',
		'title',
        'private',
		'description',
        'latitude',
		'longitude',
        'location_id',
        'location_type',
        'floor',
        'image_uri',
        'created_at',
		'updated_at' => array('data_type' => 'int')
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
    
    public function is_visible_for($user) {
        /*
         * On https://riotmill.atlassian.net/browse/SSP-487 (edit an existing flag) the definition of "private flag" is:
         * whether the flag should be visible to all users (who are following you) or just the ones you will invite 
         * However, as in the first time there will be a few flags, we will not consider this constraint.
         */
        return (!$this->private /*&& ($this->owner->is_followed_by($user) || $this->owner->same_as($user))*/) || 
               ( $this->private && ($this->is_invited($user) || $this->owner->same_as($user)));
    }
    
    public function is_invited($user) {
        if (!$this->private) {
            return true;
        }
        
        foreach ($this->invited_users as $invited_user) {
            if ($invited_user->same_as($user)) {
                return true;
            }
        }
        return false;
    }
    
    public function same_as($flag) {
        return $flag->id == $this->id;
    }
    
    public static function get_valid_types() {
        return array(self::TYPE_ALOHA, self::TYPE_ATTRACTION, self::TYPE_COMMUNITY, self::TYPE_POI);
    }
    
    public static function get_flags(array $owner_ids, $private, $current_user, $keyword = null, $location_id = null, $floor = null, $sortByName = false, $sortByCreationDate = false) {
        
        $flags_query  = Model_Flag::query()->related("invited_users");
        $return_flags = array();
        
        if (!empty($owner_ids)) {
            $flags_query->where('owner_id', 'in', $owner_ids);
        }
        
        if(!is_null($keyword)) {
            $flags_query->and_where_open()
                                    ->or_where('title', 'LIKE', "%$keyword%")
                                    ->or_where('description', 'LIKE', "%$keyword%")
                               ->and_where_close();
        }
        
        if ($location_id == -1) {
            $flags_query->where('location_id', 'is', null);
        } elseif (!is_null($location_id)) {
            $flags_query->where('location_id', $location_id);
            if (!is_null($floor)) {
                $flags_query->where('floor', $floor);
            }
        }
        
        if ($sortByName){            
            $flags_query->order_by("title", "ASC");
        }
        
        if ($sortByCreationDate){            
            $flags_query->order_by("created_at", "DESC");
        }
        
        $flags = $flags_query->get();
        
        foreach ($flags as $flag) {
            if ($private) {
                if ($flag->private && $flag->is_invited($current_user)) {
                    $return_flags[] = $flag;
                }
            } else{
                if ($flag->is_invited($current_user)) {
                    $return_flags[] = $flag;
                }
            }
        }
        
        
        $to_return = array();
        foreach ($return_flags as $flag) {
            $to_return[$flag->id] = $flag;
        }
        
        return $to_return;
    }
    
    public static function get_nearby_flags($latitude, $longitude, $radius, array $owner_ids, $private, $current_user, $keyword = null, $location_id = null, $floor = null, $sortByName = false, $sortByCreationDate = false) {
        $return_flags = array();

        $nearby_flags_query = Model_Flag::query()->related('invited_users');

        if ($radius) {
            $origin_point = Geo::build_coordinates($latitude, $longitude);

            // Calculate the coordinates of the edges of the rectangle
            list ($upper_left_point, $lower_right_point) = Geo::get_rectangle_coordinates($origin_point, $radius);
            
            $nearby_flags_query->where('longitude', 'between', array($upper_left_point->longitude, $lower_right_point->longitude))
                ->where('latitude' , 'between', array($upper_left_point->latitude, $lower_right_point->latitude));
        }

        if (!empty($owner_ids)) {
            $nearby_flags_query->where('owner_id', 'in', $owner_ids);
        }
        
        if(!is_null($keyword)) {
            $nearby_flags_query->and_where_open()
                                    ->or_where('title', 'LIKE', "%$keyword%")
                                    ->or_where('description', 'LIKE', "%$keyword%")
                               ->and_where_close();
        }

        if ($location_id == -1) {
            $nearby_flags_query->where('location_id', 'is', null);
        } elseif (!is_null($location_id) && !is_null($floor)) {
            $nearby_flags_query->where('location_id', $location_id);
            $nearby_flags_query->where('floor'      , $floor);
        }
        
        if ($sortByName){            
            $nearby_flags_query->order_by("title", "ASC");
        }
        
        if ($sortByCreationDate){            
            $nearby_flags_query->order_by("created_at", "DESC");
        }
        
        $flags = $nearby_flags_query->get();
        
        foreach ($flags as $flag) {
            if ($private) {
                if ($flag->private && $flag->is_invited($current_user)) {
                    $return_flags[] = $flag;
                }
            } else{
                if ($flag->is_invited($current_user)) {
                    $return_flags[] = $flag;
                }
            }
        }
        
        
        $to_return = array();
        foreach ($return_flags as $flag) {
            $to_return[$flag->id] = $flag;
        }
        
        return $to_return;
    }
}