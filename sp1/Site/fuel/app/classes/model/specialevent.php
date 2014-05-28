<?php

class Model_Specialevent extends \Orm\Model
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
        ),
        'main_location' => array(
            'key_from' => 'main_location_id',
            'model_to' => 'Model_Location',
            'key_to' => 'id',
        )
    );
    
    protected static $_many_many = array(
        'locations' => array (
            'table_through' => 'locations_specialevents'
        )
    );
    
    protected static $_has_many = array(
        'specialeventlikes',
    );
    
	protected static $_properties = array(
        'id',
        'created_by_id',
        'edited_by_id',
        'title',
        'description',
        'logo',
        'landing_screen_img',
        'main_location_id',
        'coordinator_phone',
        'coordinator_email',
        'website',
        'show_dates',
        'date_start',
        'date_end',
        'status',
        'tags',
        'social' => array(
            'data_type' => 'json',
        ),
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
    
    public function in_date_range($date = null) {
        if (is_null($date)) {
            $date = date('Y-m-d H:i:s');
        }
        
        return ($date >= $this->date_start && $date <= $this->date_end);
    }

    public function & __get($name) {
        if ($name == 'special') {
            $result = true;
            return $result;
        }
        return parent::__get($name);
    }
    
    public function is_active() {
        $locations = $this->locations;
    
        foreach ($locations as $location) {
            if ($location->timezone && Helper_Timezone::valid_timezone($location->timezone)) {
    
                $date = new DateTime('now');
                $date->setTimezone(new DateTimeZone($location->timezone));
    
                $date = $date->format('Y-m-d H:i:s');
    
                $active = $date >= $this->date_start && $date <= $this->date_end;
                if ($active) {
                    return true;
                }
            }
        }
        return false;
    }
}
