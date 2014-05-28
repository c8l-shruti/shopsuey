<?php

namespace Fuel\Migrations;

class Add_location_id_to_events
{
	public function up()
	{
		\DBUtil::drop_fields('events', array(
			'mall_id',
			'merchant_id',
		));
		\DBUtil::add_fields('events', array(
			'location_id' => array('constraint' => 11, 'type' => 'int'),

		));	
	}

	public function down()
	{
		\DBUtil::drop_fields('events', array(
			'location_id'
		));
		\DBUtil::add_fields('events', array(
				'mall_id' => array('constraint' => 11, 'type' => 'int', 'null' => true),
				'merchant_id' => array('constraint' => 11, 'type' => 'int', 'null' => true),
		));
		
	}
}