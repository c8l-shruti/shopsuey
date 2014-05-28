<?php

class Model_User_Instagram extends \Orm\Model
{
    protected static $_belongs_to = array('user');

    protected static $_has_many = array(
    	'locations',
    );
    
	protected static $_properties = array(
		'id',
		'user_id',
		'access_token',
		'username',
		'instagram_user_id',
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
}
