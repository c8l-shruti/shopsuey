<?php

namespace Fuel\Migrations;

class Remove_location_from_events_table
{
	public function up()
	{
		\DBUtil::drop_fields('events', array('location_id'));
	}

	public function down()
	{
		\DBUtil::add_fields('events', array(
				'location_id' => array('type' => 'int', 'constraint' => 11),
		));
	}
}