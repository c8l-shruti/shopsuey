<?php

class Model_Hours extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'status',
		'parent_id',
		'type',
		'label',
		'time_open',
		'time_close',
		'order',
		'created',
		'edited',
		'created_by',
		'edited_by'
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
