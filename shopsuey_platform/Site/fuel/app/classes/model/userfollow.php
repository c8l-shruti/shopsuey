<?php

class Model_Userfollow extends \Orm\Model {

    protected static $_table_name = 'user_follows';
    
    protected static $_belongs_to = array(
        'follower_user' => array(
            'key_from' => 'follower_id',
            'model_to' => 'Model_User',
            'key_to'   => 'id',
        ),
        'followee_user' => array(
            'key_from' => 'followee_id',
            'model_to' => 'Model_User',
            'key_to'   => 'id',
        )
    );
    
    protected static $_properties = array(
		'id',
        'follower_id',
        'followee_id',
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

