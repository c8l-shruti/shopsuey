<?php

namespace Fuel\Migrations;

class Create_users_offers
{
	public function up()
	{
		\DBUtil::create_table('users_offers', array(
			'user_id' => array('constraint' => 11, 'type' => 'int'),
			'offer_id' => array('constraint' => 11, 'type' => 'int'),

		), array('user_id', 'offer_id'));
	}

	public function down()
	{
		\DBUtil::drop_table('users_offers');
	}
}