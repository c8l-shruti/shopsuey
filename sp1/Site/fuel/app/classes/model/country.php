<?php

class Model_Country extends \Orm\Model
{
    const CODE_US = 'US';
    
    protected static $_has_many = array(
    	'locations',
    );
    
	protected static $_properties = array(
		'id',
		'code',
		'name',
		'created_at',
		'updated_at'
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
	);
	
	public static function get_default() {
	    return self::query()->where('code', self::CODE_US)->get_one();
	}
	
	public static function get_by_name($name) {
		return self::query()->where('name', $name)->get_one();
	}
}
