<?php

class Model_Apilog extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'created',
		'appid',
		'access_key',
		'resource'
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
