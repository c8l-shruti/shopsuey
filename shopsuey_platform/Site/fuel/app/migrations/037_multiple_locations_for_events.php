<?php

namespace Fuel\Migrations;

class Multiple_locations_for_events
{
	public function up()
	{
		\DBUtil::create_table('locations_events', array(
			'location_id' => array('constraint' => 11, 'type' => 'int'),
			'event_id' => array('constraint' => 11, 'type' => 'int'),

		), array('event_id', 'location_id'));
	}

	public function down()
	{
		\DBUtil::drop_table('locations_events');
	}
}