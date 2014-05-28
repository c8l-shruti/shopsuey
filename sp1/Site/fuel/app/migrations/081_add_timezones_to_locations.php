<?php

namespace Fuel\Migrations;

class Add_timezones_to_locations
{
	public function up()
	{
		\DBUtil::add_fields('locations', array(
			'timezone' => array('type' => 'varchar', 'constraint' => 100),
		));	
	}

	public function down()
	{
		\DBUtil::drop_fields('locations', array(
			'timezone'
		));
	}
}