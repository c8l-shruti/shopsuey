<?php

namespace Fuel\Migrations;

class Create_specialeventrsvps
{
	public function up()
	{
		\DBUtil::create_table('specialeventrsvps', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'user_id' => array('constraint' => 11, 'type' => 'int'),
			'specialevent_id' => array('constraint' => 11, 'type' => 'int'),
			'created_at' => array('constraint' => 11, 'type' => 'int'),
			'updated_at' => array('constraint' => 11, 'type' => 'int'),
		), array('id'));
        
        \DBUtil::create_index('specialeventrsvps', array('user_id', 'specialevent_id'), 'rsvps_unique_index', 'unique');
        \DBUtil::create_index('specialeventrsvps', 'specialevent_id');
	}

	public function down()
	{
		\DBUtil::drop_table('specialeventrsvps');
	}
}