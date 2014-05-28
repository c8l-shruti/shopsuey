<?php

namespace Fuel\Migrations;

class Add_enabled_to_contests
{
	public function up()
	{
		\DBUtil::add_fields('contests', array(
			'enabled' => array('type' => 'bool', 'default' => '0'),

		));	
	}

	public function down()
	{
		\DBUtil::drop_fields('contests', array(
			'enabled'
    
		));
	}
}