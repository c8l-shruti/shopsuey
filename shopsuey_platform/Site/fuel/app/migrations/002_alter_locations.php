<?php

namespace Fuel\Migrations;

class Alter_locations
{
	public function up()
	{
		\DBUtil::modify_fields('locations', array(
			'gps' => array('type' => 'text'),
		));
	}

	public function down()
	{
		\DBUtil::modify_fields('locations', array(
			'gps' => array('constraint' => 45, 'type' => 'varchar'),
		));
	}
}