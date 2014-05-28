<?php

class Model_Event extends \Orm\Model
{
    protected static $_belongs_to = array(
        'created_by' => array(
            'key_from' => 'created_by_id',
            'model_to' => 'Model_User',
            'key_to' => 'id',
        ),
        'edited_by' => array(
            'key_from' => 'edited_by_id',
            'model_to' => 'Model_User',
            'key_to' => 'id',
        )
    );
    
    protected static $_many_many = array(
        'locations' => array (
            'table_through' => 'locations_events'
        )
    );
    
    protected static $_has_many = array(
        'eventlikes',
    );
    
	protected static $_properties = array(
		'id',
        'created_by_id',
        'edited_by_id',
		'title',
		'description',
        'featured_image',
        'gallery' => array(
            'data_type' => 'json',
        ),
        'coupon_image',
        'coordinator_phone',
        'coordinator_email',
        'website',
        'show_dates',
		'date_start',
		'date_end',
        'status',
		'code',
		'tags',
		'fb_event_id',
		'foursquare_venue_id',
		'foursquare_event_id',
        'force_top_message',
		'created_at',
		'updated_at' => array('data_type' => 'int'),
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
        'Orm\\Observer_Typing',
	);
    
    public function get_location_ids() {
        $ids = array();
        foreach ($this->locations as $loc) {
            $ids[] = $loc->id;
        }
        return $ids;
    }
    
    public function is_active() {
        $locations = $this->locations;
        
        foreach ($locations as $location) {
            if (!Helper_Timezone::valid_timezone($location->timezone)) {
                $location->timezone = Config::get('timezone.default_timezone');
            }
            
            $date = new DateTime('now');
            $date->setTimezone(new DateTimeZone($location->timezone));

            $date = $date->format('Y-m-d H:i:s');

            $active = $date >= $this->date_start && $date <= $this->date_end;
            if ($active) {
                return true;
            }
        }
        return false;
    }
    
    public function in_date_range($date = null) {
        if (is_null($date)) {
            $date = date('Y-m-d H:i:s');
        }
        
        return ($date >= $this->date_start && $date <= $this->date_end);
    }

    public function & __get($name) {
        if ($name == 'special') {
            $result = false;
            return $result;
        }
        return parent::__get($name);
    }
    
    public static function filter_active_event_ids(array $events_ids = array()) {
        if (empty($events_ids)) {
            return array();
        }
        
        $events_data = DB::select('events.id', 'events.date_start', 'events.date_end', 'locations.timezone')
                        ->from('events')
                        ->join('locations_events')->on('events.id', '=', 'locations_events.event_id')
                        ->join('locations')->on('locations.id', '=', 'locations_events.location_id')
                        ->where('events.status', '=', '1')
                        ->where('events.id', 'in', $events_ids)
                        ->as_object()->execute();
        
        
        $active_events = array();
        foreach ($events_data as $event_data) {
            if (!Helper_Timezone::valid_timezone($event_data->timezone)) {
                $event_data->timezone = Config::get('timezone.default_timezone');
            }

            $date = new DateTime('now');
            $date->setTimezone(new DateTimeZone($event_data->timezone));
            $date = $date->format('Y-m-d H:i:s');

            $active = $date >= $event_data->date_start && $date <= $event_data->date_end;
            if ($active && !in_array($event_data->id, $active_events)) {
                $active_events[] = $event_data->id;
                continue;
            }
        }

        return $active_events;
    }
}
