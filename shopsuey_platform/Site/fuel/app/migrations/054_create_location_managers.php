<?php

namespace Fuel\Migrations;

class Create_location_managers
{
	public function up()
	{
		\DBUtil::create_table('location_managers', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'location_id' => array('constraint' => 11, 'type' => 'int'),
			'user_id' => array('constraint' => 11, 'type' => 'int'),
			'include_merchants' => array('type' => 'bool'),
			'created_at' => array('constraint' => 11, 'type' => 'int'),
			'updated_at' => array('constraint' => 11, 'type' => 'int'),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('location_managers');
	}
}