<?php

namespace Fuel\Migrations;

class Add_fbuid_column_to_users 
{
	public function up()
	{
		\DBUtil::add_fields('users', array(
            'fbuid' => array('constraint' => 255, 'type' => 'varchar', 'null' => true),
		));
	}

	public function down()
	{
		\DBUtil::drop_fields('users', array(
            'fbuid'
		));
	}
    
}
