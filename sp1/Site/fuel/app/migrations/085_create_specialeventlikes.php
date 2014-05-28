<?php

namespace Fuel\Migrations;

class Create_specialeventlikes
{
	public function up()
	{
		\DBUtil::create_table('specialeventlikes', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'user_id' => array('constraint' => 11, 'type' => 'int'),
			'specialevent_id' => array('constraint' => 11, 'type' => 'int'),
            'status' => array('constraint' => 11, 'type' => 'int'),
			'created_at' => array('constraint' => 11, 'type' => 'int'),
			'updated_at' => array('constraint' => 11, 'type' => 'int'),
		), array('id'));
        \DBUtil::create_index('specialeventlikes', array('user_id', 'specialevent_id'), 'likes_unique_index', 'unique');
        \DBUtil::create_index('specialeventlikes', 'specialevent_id');
	}

	public function down()
	{
		\DBUtil::drop_table('specialeventlikes');
	}
}