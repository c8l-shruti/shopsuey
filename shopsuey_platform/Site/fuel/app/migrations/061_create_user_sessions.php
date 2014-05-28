<?php

namespace Fuel\Migrations;

class Create_user_sessions
{
	public function up()
	{
		\DBUtil::create_table('user_sessions', array(
            'id'            => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'user_id'       => array('constraint' => 11, 'type' => 'int'),
            'created_at'    => array('constraint' => 11, 'type' => 'int'),
		    'updated_at'    => array('constraint' => 11, 'type' => 'int'),
			'start_time'    => array('type' => 'int'),
			'end_time'      => array('type' => 'int', 'null' => true),
            'total_time'    => array('type' => 'int', 'null' => true),
		), array('id'));
        \DBUtil::create_index('user_sessions', array('user_id', 'created_at'), 'unique_index', 'unique');
	}

	public function down()
	{
		\DBUtil::drop_table('user_sessions');
	}
}