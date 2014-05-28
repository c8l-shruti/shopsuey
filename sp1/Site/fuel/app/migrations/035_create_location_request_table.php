<?php

namespace Fuel\Migrations;

class Create_location_request_table
{
	public function up()
	{
		\DBUtil::create_table('location_requests', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'user_id' => array('constraint' => 11, 'type' => 'int'),
			'location_id' => array('constraint' => 11, 'type' => 'int'),
			'created_at' => array('constraint' => 11, 'type' => 'int'),
			'updated_at' => array('constraint' => 11, 'type' => 'int'),
		), array('id'));
        \DBUtil::create_index('location_requests', array('user_id', 'location_id'), 'location_requests_unique_index', 'unique');
        \DBUtil::create_index('location_requests', 'user_id');
        \DBUtil::create_index('location_requests', 'location_id');
	}

	public function down()
	{
		\DBUtil::drop_table('location_requests');
	}
}