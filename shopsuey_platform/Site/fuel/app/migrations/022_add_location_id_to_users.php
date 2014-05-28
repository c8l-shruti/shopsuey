<?php

namespace Fuel\Migrations;

class Add_location_id_to_users
{
	public function up()
	{
		\DBUtil::add_fields('users', array(
			'location_id' => array('constraint' => 11, 'type' => 'int', 'null' => true),

		));	
	}

	public function down()
	{
		\DBUtil::drop_fields('users', array(
			'location_id'
    
		));
	}
}