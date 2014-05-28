<?php

class Model_Flagvote extends \Orm\Model {
    
    protected static $_belongs_to = array(
        'flag',
        'user',
    );
    
    protected static $_properties = array(
		'id',
        'flag_id',
        'user_id',
        'status',
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

