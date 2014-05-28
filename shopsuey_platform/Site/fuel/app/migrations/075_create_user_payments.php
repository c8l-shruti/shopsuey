<?php

namespace Fuel\Migrations;

class Create_user_payments
{
	public function up()
	{
		\DBUtil::create_table('user_payments', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'user_id' => array('constraint' => 11, 'type' => 'int'),
			'customer_id' => array('constraint' => 100, 'type' => 'varchar'),
			'subscription_id' => array('constraint' => 100, 'type' => 'varchar'),
		    'credit_card_token' => array('constraint' => 100, 'type' => 'varchar'),
			'status' => array('constraint' => 11, 'type' => 'int'),
			'next_check_on' => array('type' => 'datetime'),
			'created_at' => array('constraint' => 11, 'type' => 'int'),
			'updated_at' => array('constraint' => 11, 'type' => 'int'),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('user_payments');
	}
}