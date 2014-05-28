<?php

class Model_Userpreferences extends \Orm\Model
{
    protected static $_belongs_to = array('user');
    
	protected static $_properties = array(
		'id',
		'user_id',
		'deal_alerts',
		'event_alerts',
		'meeting_place_alerts',
		'rsvps',
		'event_reminders',
		'allow_friends_to_see_me',
		'allow_friends_to_see_my_location',
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
