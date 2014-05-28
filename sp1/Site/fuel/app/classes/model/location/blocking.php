<?php

class Model_Location_Blocking extends \Orm\Model
{
	const BLOCKED_TODAY				= 'today';
	const BLOCKED_THIS_WEEK 	= 'this_week';
	const BLOCKED_PERMANENTLY = 'permanently';

	const BLOCKED_TODAY_TIME 			 = 'tomorrow';
	const BLOCKED_THIS_WEEK_TIME 	 = 'Monday next week';
	const BLOCKED_PERMANENTLY_TIME = '+50 years';
	
	protected static $_belongs_to = array('location', 'user');
	
	protected static $_properties = array(
		'id',
		'type',
		'location_id',
		'user_id',
		'start_date',
		'end_date',
		'created_at',
		'updated_at' => array('data_type' => 'int'),
	);

	protected static $_observers = array(
        'Orm\\Observer_Typing' => array(
            'events' => array('before_save', 'after_save', 'after_load')
        ),
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
