<?php

class Model_Merchant extends Model_Location
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
	    'mall',
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
		'edited_by'
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
		$this->type = Model_Location::TYPE_MERCHANT;
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
                return parent::query($options)->where('type', Model_Location::TYPE_MERCHANT);
	}
    
    public static function apply_type_discriminator($query) {
        return $query->where('type', Model_Location::TYPE_MERCHANT);;
    }
    
    public function inherit_data_from_mall(Model_Mall $mall) {
        $this->timezone   = $mall->timezone;
        $this->address    = $mall->address;
        $this->city       = $mall->city;
        $this->st         = $mall->st;
        $this->country_id = $mall->country_id;
        $this->zip        = $mall->zip;
        $this->country    = $mall->country;
        $this->latitude   = $mall->latitude;
        $this->longitude  = $mall->longitude;
        $this->mall       = $mall;
        $this->mall_id    = $mall->id;
        $this->hours      = $mall->hours;
    }
}
