<?php

namespace Fuel\Migrations;

class Add_instagram_to_locations
{
	public function up()
	{
		\DBUtil::add_fields('locations', array(
			'use_instagram' => array('type' => 'bool', 'default' => '0'),
			'user_instagram_id' => array('constraint' => 11, 'type' => 'int', 'null' => TRUE),

		));	
	}

	public function down()
	{
		\DBUtil::drop_fields('locations', array(
			'use_instagram'
,			'user_instagram_id'
    
		));
	}
}