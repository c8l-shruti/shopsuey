<?php

namespace Fuel\Migrations;

class Add_setup_complete_to_locations
{
	public function up()
	{
		\DBUtil::add_fields('locations', array(
			'setup_complete' => array('type' => 'bool', 'default' => 0),

		));	
	}

	public function down()
	{
		\DBUtil::drop_fields('locations', array(
			'setup_complete'
    
		));
	}
}