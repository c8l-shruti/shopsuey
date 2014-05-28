<?php

class Model_User_Payment extends \Orm\Model
{
    const STATUS_ACTIVE = 1;
    
    protected static $_belongs_to = array('user');
    
	protected static $_properties = array(
		'id',
		'user_id',
		'customer_id',
		'subscription_id',
	    'credit_card_token',
		'status',
		'next_check_on',
		'created_at',
		'updated_at'
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
