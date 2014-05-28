<?php

namespace Fuel\Migrations;

class Create_user_instagrams
{
	public function up()
	{
		\DBUtil::create_table('user_instagrams', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'user_id' => array('constraint' => 11, 'type' => 'int'),
			'access_token' => array('constraint' => 100, 'type' => 'varchar'),
			'username' => array('constraint' => 100, 'type' => 'varchar'),
			'instagram_user_id' => array('constraint' => 50, 'type' => 'varchar'),
			'created_at' => array('constraint' => 11, 'type' => 'int'),
			'updated_at' => array('constraint' => 11, 'type' => 'int'),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('user_instagrams');
	}
}