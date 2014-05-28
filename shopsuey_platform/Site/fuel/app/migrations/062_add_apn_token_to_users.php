<?php

namespace Fuel\Migrations;

class Add_apn_token_to_users
{
	public function up()
	{
		\DBUtil::add_fields('users', array(
            'apn_token' => array('constraint' => 255, 'type' => 'varchar', 'null' => true),
		));
	}

	public function down()
	{
		\DBUtil::drop_fields('users', array(
            'apn_token'
		));
	}
}