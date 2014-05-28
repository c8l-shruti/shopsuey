<?php

class Model_Mall extends Model_Location
{
	protected static $_table_name = 'locations';

	protected static $_many_many = array(
		'offers' => array(
			'key_through_to' => 'offer_id',
		    'key_through_from' => 'location_id',
			'table_through' => 'locations_offers',
		),
	    'events' => array(
	        'key_through_to' => 'event_id',
	        'key_through_from' => 'location_id',
			'table_through' => 'locations_events',
	    ),
		'favorited_users' => array(
			'table_through' => 'users_favorite_locations',
			'key_through_to' => 'user_id',
		    'key_through_from' => 'location_id',
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
		'merchants',
		'events' => array(
			'key_to' => 'location_id',
		),
        'flags' => array(
            'key_to'   => 'mall_id',
            'model_to' => 'Model_Flag',
			'key_from' => 'id',
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
// 		'categories',
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
	        'default' => FALSE,
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
	
	public function _event_before_save() {
		// Force type of the entry
		$this->type = Model_Location::TYPE_MALL;
        if (is_null($this->plan)) {
            $this->plan = 0;
        }
        if (is_null($this->max_users)) {
            $this->max_users = -1;
        }
        if (is_null($this->content)) {
            $this->content = '';
        }
	}
	
	public static function query($options = array()) {
		return parent::query($options)->where('type', Model_Location::TYPE_MALL);
	}
    
    public static function apply_type_discriminator($query) {
        return $query->where('type', Model_Location::TYPE_MALL);;
    }
}
