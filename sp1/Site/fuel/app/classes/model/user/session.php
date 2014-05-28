<?php

class Model_User_Session extends \Orm\Model
{
	protected static $_belongs_to = array('user', 'application');
    
	protected static $_properties = array(
        'id',
		'user_id',
		'start_time',
		'end_time',
        'total_time',
		'created_at',
		'updated_at',
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
