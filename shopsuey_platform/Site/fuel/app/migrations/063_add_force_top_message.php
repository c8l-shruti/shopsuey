<?php

namespace Fuel\Migrations;

class Add_force_top_message
{
	public function up()
	{
		\DBUtil::add_fields('events', array(
            'force_top_message' => array('type' => 'bool', 'default' => 0),
		));
        \DBUtil::add_fields('offers', array(
            'force_top_message' => array('type' => 'bool', 'default' => 0),
		));
        \DBUtil::create_index('events', 'force_top_message', 'force_top_message_idx');
        \DBUtil::create_index('offers', 'force_top_message', 'force_top_message_idx');
	}

	public function down()
	{
		\DBUtil::drop_fields('events', array(
            'force_top_message'
		));
        \DBUtil::drop_fields('offers', array(
            'force_top_message'
		));
	}
}