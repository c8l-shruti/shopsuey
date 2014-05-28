<?php

namespace Fuel\Migrations;

class Create_userpreferences
{
	public function up()
	{
		\DBUtil::create_table('userpreferences', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'user_id' => array('constraint' => 11, 'type' => 'int'),
            'deal_alerts' => array('type' => 'boolean'),
            'event_alerts' => array('type' => 'boolean'),
            'meeting_place_alerts' => array('type' => 'boolean'),
            'rsvps' => array('type' => 'boolean'),
            'event_reminders' => array('type' => 'boolean'),
            'allow_friends_to_see_me' => array('type' => 'boolean'),
            'allow_friends_to_see_my_location' => array('type' => 'boolean'),
            'created_at' => array('constraint' => 11, 'type' => 'int'),
			'updated_at' => array('constraint' => 11, 'type' => 'int'),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('userpreferences');
	}
}