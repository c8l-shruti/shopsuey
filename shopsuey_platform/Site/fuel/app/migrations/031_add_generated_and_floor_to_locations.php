<?php

namespace Fuel\Migrations;

class Add_generated_and_floor_to_locations
{
	public function up()
	{
		\DBUtil::add_fields('locations', array(
			'auto_generated' => array('type' => 'bool', 'default' => '0', 'null' => true),
			'is_customer' => array('type' => 'bool'),
	        'floor' => array('constraint' => 11, 'type' => 'int', 'null' => true),
		));	
	}

	public function down()
	{
		\DBUtil::drop_fields('locations', array(
            'auto_generated',
	        'is_customer',
            'floor',
		));
	}
}