<?php

namespace Fuel\Migrations;

class Add_fields_to_health_metrics
{
	public function up()
	{
		\DBUtil::add_fields('health_metrics', array(
	        'redemptions_count' => array('constraint' => 11, 'type' => 'int'),
	        'rsvps_count' => array('constraint' => 11, 'type' => 'int'),
		));	
	}

	public function down()
	{
		\DBUtil::drop_fields('health_metrics', array(
	        'redemptions_count',
	        'rsvps_count',
		));
	}
}