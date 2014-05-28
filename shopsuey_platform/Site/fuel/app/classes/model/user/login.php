<?php

class Model_User_Login extends \Orm\Model
{
	protected static $_belongs_to = array('user', 'application');
    
	protected static $_properties = array(
		'id',
		'login_hash',
		'ip',
		'user_id',
		'application_id',
		'expiracy',
		'created_at',
		'updated_at' => array('data_type' => 'int'),
	);

	protected static $_observers = array(
		'Orm\Observer_UpdatedAt' => array(
			'events' => array('before_save'),
			'mysql_timestamp' => false,
		),
	);
}
