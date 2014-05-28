<?php

namespace Fuel\Migrations;

class Delete_location_id_from_users
{
	public function up()
	{
	    \DBUtil::drop_fields('users', array(
            'location_id'
	    ));
	}

	public function down()
	{
	    \DBUtil::add_fields('users', array(
            'location_id' => array('type' => 'int', 'null' => true),
	    ));
	}
}