<?php

namespace Fuel\Migrations;

class Add_explore_icon_to_locations
{
	public function up()
	{
		\DBUtil::add_fields('locations', array(
			'explore_icon' => array('type' => 'text', 'null' => true),

		));	
	}

	public function down()
	{
		\DBUtil::drop_fields('locations', array(
			'explore_icon'
    
		));
	}
}