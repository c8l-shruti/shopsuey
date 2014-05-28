<?php

namespace Fuel\Migrations;

class Create_eventrsvps
{
	public function up()
	{
		\DBUtil::create_table('eventrsvps', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'user_id' => array('constraint' => 11, 'type' => 'int'),
			'event_id' => array('constraint' => 11, 'type' => 'int'),
			'created_at' => array('constraint' => 11, 'type' => 'int'),
			'updated_at' => array('constraint' => 11, 'type' => 'int'),
		), array('id'));
        
        \DBUtil::create_index('eventrsvps', array('user_id', 'event_id'), 'rsvps_unique_index', 'unique');
        \DBUtil::create_index('eventrsvps', 'event_id');
	}

	public function down()
	{
		\DBUtil::drop_table('eventrsvps');
	}
}