<?php

namespace Fuel\Migrations;

class Create_flag_votes
{
	public function up()
	{
        \DBUtil::create_table('flagvotes', array(
			'id'         => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'user_id'    => array('constraint' => 11, 'type' => 'int'),
			'flag_id'    => array('constraint' => 11, 'type' => 'int'),
            'status'     => array('constraint' => 11, 'type' => 'int'),
			'created_at' => array('constraint' => 11, 'type' => 'int'),
			'updated_at' => array('constraint' => 11, 'type' => 'int'),
		), array('id'));
        \DBUtil::create_index('flagvotes', array('user_id', 'flag_id'), 'votes_unique_index', 'unique');
        \DBUtil::create_index('flagvotes', 'flag_id');
	}

	public function down()
	{
		\DBUtil::drop_table('flagvotes');
	}
}
