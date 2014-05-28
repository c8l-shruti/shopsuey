<?php

namespace Fuel\Migrations;

class Create_users_favorite_merchants
{
	public function up()
	{
		\DBUtil::create_table('users_favorite_merchants', array(
			'user_id' => array('constraint' => 11, 'type' => 'int'),
			'merchant_id' => array('constraint' => 11, 'type' => 'int'),

		), array('user_id', 'merchant_id'));
	}

	public function down()
	{
		\DBUtil::drop_table('users_favorite_merchants');
	}
}