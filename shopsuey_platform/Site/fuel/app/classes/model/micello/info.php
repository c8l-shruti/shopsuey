<?php

class Model_Micello_Info extends \Orm\Model
{
	const TYPE_COMMUNITY = 'community';
	const TYPE_ENTITY = 'entity';

	protected static $_belongs_to = array('location');
	
	protected static $_properties = array(
		'id',
		'location_id',
		'micello_id',
		'type',
		'map' => array(
			'data_type' => 'json',
		),
		'map_expiracy',
        'map_version',
		'geometry_id',
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
}
