<?php

class Model_User_Reset extends \Orm\Model
{
	protected static $_belongs_to = array('user');
	
	protected static $_properties = array(
		'id',
		'user_id',
		'hash',
		'used',
		'expiracy',
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

	public function generate_hash($salt) {
		return hash_hmac('sha256', uniqid() . time() . $this->user_id, $salt, true);
	}
}
