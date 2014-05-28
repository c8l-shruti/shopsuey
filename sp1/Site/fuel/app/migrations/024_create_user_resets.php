<?php

namespace Fuel\Migrations;

class Create_user_resets
{
	public function up()
	{
		\DBUtil::create_table('user_resets', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'user_id' => array('constraint' => 11, 'type' => 'int'),
			'hash' => array('constraint' => 100, 'type' => 'varchar'),
			'used' => array('type' => 'bool'),
			'expiracy' => array('type' => 'datetime'),
			'created_at' => array('constraint' => 11, 'type' => 'int'),
			'updated_at' => array('constraint' => 11, 'type' => 'int'),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('user_resets');
	}
}