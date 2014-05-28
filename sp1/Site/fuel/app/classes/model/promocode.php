<?php

use Fuel\Core\Config;

class Model_Promocode extends \Orm\Model
{
    const FREE_ACCOUNT   = 1;
    const PRICE_DISCOUNT = 2;
    
    protected static $_has_many = array(
	    'users' => array(
            'key_from' => 'id',
            'model_to' => 'Model_User',
            'key_to' => 'promo_code_id'
        ),
	);
    
    protected static $_properties = array(
		'id',
        'code',
        'type',
        'description',
		'date_start',
		'date_end',
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
    
    public function is_active() {
        $current_time = time();
        
        return ($this->date_start <= $current_time && $this->date_end >= $current_time);
    }
    
    public function get_promo_code_type_name() {
        $names = array(
            self::FREE_ACCOUNT   => 'free',
            self::PRICE_DISCOUNT => 'discount'
        );
        
        return $names[$this->type];
    }
    
}

