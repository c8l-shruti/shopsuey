<?php

class Model_Application extends \Orm\Model
{
	protected static $_has_many = array(
		'logins' => array(
			'model_to' => 'Model_User_Login',
		),
	);
	
	protected static $_properties = array(
		'id',
		'name',
		'slug',
		'contact',
		'domains',
		'tags',
		'secret',
		'token',
		'description',
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
	);
}
