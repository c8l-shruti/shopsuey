<?php

class Model_Category extends \Orm\Model
{
    protected static $_many_many = array('locations');
    
	protected static $_properties = array(
		'id',
		'name',
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
