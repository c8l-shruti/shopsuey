<?php

namespace Fuel\Migrations;

class Create_twitterrequests
{
	public function up()
	{
		\DBUtil::create_table('twitterrequests', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'user_id' => array('constraint' => 11, 'type' => 'int'),
			'location_id' => array('constraint' => 11, 'type' => 'int'),
			'created_at' => array('constraint' => 11, 'type' => 'int'),
			'updated_at' => array('constraint' => 11, 'type' => 'int'),
		), array('id'));
        \DBUtil::create_index('twitterrequests', array('user_id', 'location_id'), 'twitterrequests_unique_index', 'unique');
        \DBUtil::create_index('twitterrequests', 'location_id');
	}

	public function down()
	{
		\DBUtil::drop_table('twitterrequests');
	}
}