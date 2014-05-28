<?php

namespace Fuel\Migrations;

class Create_users_favorite_malls
{
	public function up()
	{
		\DBUtil::create_table('users_favorite_malls', array(
			'user_id' => array('constraint' => 11, 'type' => 'int'),
			'mall_id' => array('constraint' => 11, 'type' => 'int'),

		), array('user_id', 'mall_id'));
	}

	public function down()
	{
		\DBUtil::drop_table('users_favorite_malls');
	}
}