<?php

class Model_Checkin extends \Orm\Model {

    protected static $_table_name = 'checkins';
    
    protected static $_properties = array(
        'id',
        'status',
        'user_id',
        'location_id',
        'retailer_id', //0
        'mall_id',
        'created',
        'edited',
        'created_by',
        'edited_by',
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
    
    protected static $_belongs_to = array(
        'location',
        'user',
        'created_by' => array(
            'key_from' => 'created_by_id',
            'model_to' => 'Model_User',
            'key_to' => 'id',
        ),
        'edited_by' => array(
            'key_from' => 'edited_by_id',
            'model_to' => 'Model_User',
            'key_to' => 'id',
        )
    );

}
