<?php

namespace Fuel\Migrations;

class Create_user_activities {

    public function up()
	{
		\DBUtil::create_table('user_activities', array(
			'id'            => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'user_id'       => array('constraint' => 11, 'type' => 'int'),
			'activity_type' => array('constraint' => 50, 'type' => 'varchar'),
			'details'       => array('type' => 'text'),
			'created_at'    => array('constraint' => 11, 'type' => 'int'),
			'updated_at'    => array('constraint' => 11, 'type' => 'int'),
		), array('id'));
        \DBUtil::create_index('user_activities', array('user_id', 'activity_type'), 'user_id_activity_type_idx');

        \DBUtil::add_fields('users', array(
			'last_activity' => array('type' => 'int', 'constraint' => 11, 'default' => 0),
		));
	}

	public function down()
	{
		\DBUtil::drop_table('user_activities');
        \DBUtil::drop_fields('users', array(
			'last_activity'
		));
	}
}