<?php

namespace Fuel\Migrations;

class Add_social_fields_to_events
{
	public function up()
	{
		\DBUtil::add_fields('events', array(
			'fb_event_id'  => array('constraint' => 100, 'type' => 'varchar'),
			'foursquare_venue_id' => array('constraint' => 100, 'type' => 'varchar'),
			'foursquare_event_id' => array('constraint' => 100, 'type' => 'varchar'),
		));	
	}

	public function down()
	{
		\DBUtil::drop_fields('events', array(
			'fb_event_id',
			'foursquare_venue_id',
			'foursquare_event_id',
		));
	}
}