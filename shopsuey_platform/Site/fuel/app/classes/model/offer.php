<?php

use Fuel\Core\Config;

class Model_Offer extends \Orm\Model
{
    const TYPE_DEFAULT = 0;
    const TYPE_REWARD = 1;
    
	protected static $_has_many = array(
	    'offer_codes',
	    'offerlikes',
	);

	protected static $_many_many = array(
	    'locations',
	    'users' => array(
	        'key_through_to' => 'user_id',
	        'table_through' => 'users_offers',
	    ),
	);

	protected static $_properties = array(
            'id',
            'status',
            'name',
            'description',
            'price_regular',
            'price_offer',
            'savings',
            'show_dates',
            'date_start',
            'date_end',
            'gallery' => array(
                'data_type' => 'json',
            ),
            'categories',
            'tags',
            'redeemable',
            'allowed_redeems',
            'multiple_codes',
            'default_code_type',
            'force_top_message',
            'type' => array('default' => self::TYPE_DEFAULT),
            'created_at',
            'updated_at' => array('data_type' => 'int'),
            'created_by',
            'edited_by',

            'provider',
            'internal_id' => array('data_type' => 'int'),
            'raw_imported_info' => array('data_type' => 'json'),
            'imported_url',
            
	);

	protected static $_has_one = array(
	    'reward',
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
    
    public static function filter_active_offer_ids(array $offer_ids = array()) {
        if (empty($offer_ids)) {
            return array();
        }
        
        $offers_data = DB::select('offers.id', 'offers.date_start', 'offers.date_end', 'locations.timezone')
                        ->from('offers')
                        ->join('locations_offers')->on('offers.id', '=', 'locations_offers.offer_id')
                        ->join('locations')->on('locations.id', '=', 'locations_offers.location_id')
                        ->where('offers.status', '=', '1')
                        ->where('offers.id', 'in', $offer_ids)
                        ->as_object()->execute();
        
        
        $active_offers = array();
        foreach ($offers_data as $offer_data) {
            if (!Helper_Timezone::valid_timezone($offer_data->timezone)) {
                $offer_data->timezone = Config::get('timezone.default_timezone');
            }

            $date = new DateTime('now');
            $date->setTimezone(new DateTimeZone($offer_data->timezone));
            $date = $date->format('Y-m-d H:i:s');

            $active = $date >= $offer_data->date_start && $date <= $offer_data->date_end;
            if ($active && !in_array($offer_data->id, $active_offers)) {
                $active_offers[] = $offer_data->id;
                continue;
            }
        }

        return $active_offers;
    }
}
