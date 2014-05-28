<?php

namespace Fuel\Migrations;

class Add_apn_bundle_and_env_to_users
{
	public function up()
	{
		\DBUtil::add_fields('users', array(
            'apn_bundle' => array('constraint' => 255, 'type' => 'varchar', 'null' => true),
            'apn_env' => array('constraint' => 255, 'type' => 'varchar', 'null' => true),
		));
	}

	public function down()
	{
		\DBUtil::drop_fields('users', array(
            'apn_bundle',
            'apn_env',
		));
	}
}