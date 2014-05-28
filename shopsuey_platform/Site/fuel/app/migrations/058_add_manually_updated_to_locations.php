<?php

namespace Fuel\Migrations;

class Add_manually_updated_to_locations
{
	public function up()
	{
		\DBUtil::add_fields('locations', array(
			'manually_updated' => array('type' => 'bool', 'default' => 0),

		));	
	}

	public function down()
	{
		\DBUtil::drop_fields('locations', array(
			'manually_updated'
    
		));
	}
}