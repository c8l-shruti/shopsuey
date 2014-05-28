<?php

namespace Fuel\Migrations;

class Create_subscriptions
{
	public function up()
	{
		\DBUtil::create_table('subscriptions', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'user_id' => array('constraint' => 11, 'type' => 'int'),
			'location_id' => array('constraint' => 11, 'type' => 'int'),
			'created_at' => array('constraint' => 11, 'type' => 'int'),
			'updated_at' => array('constraint' => 11, 'type' => 'int'),
		), array('id'));
        \DBUtil::create_index('subscriptions', array('user_id', 'location_id'), 'subscriptions_unique_index', 'unique');
        \DBUtil::create_index('subscriptions', 'location_id');
	}

	public function down()
	{
		\DBUtil::drop_table('subscriptions');
	}
}