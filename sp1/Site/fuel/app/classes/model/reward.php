<?php

class Model_Reward extends \Orm\Model {

    protected static $_belongs_to = array(
        'contest',
        'offer',
        'contestant'
    );
    
    protected static $_properties = array(
        'id',
        'sent',
        'email_sent',
        'contestant_id',
        'offer_id',
        'contest_id',
        'grand_prize' => array(
            'default' => 0
        ),
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

    public function isDelivered(){
        return ($this->contestant)?true:false;
    }
}
