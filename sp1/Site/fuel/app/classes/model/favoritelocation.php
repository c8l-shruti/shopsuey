<?php

class Model_Favoritelocation extends \Orm\Model {
    
    protected static $_table_name = 'users_favorite_locations';
    
    protected static $_primary_key = array('location_id', 'user_id');
    
    protected static $_belongs_to = array(
        'location',
        'user',
    );
    
    protected static $_properties = array(
        'location_id',
        'user_id',
        'created_at' => array('data_type' => 'int'),
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

