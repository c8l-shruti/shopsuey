<?php

namespace Fuel\Migrations;

class Alter_micello_infos
{
	public function up()
	{
		\DBUtil::modify_fields('micello_infos', array(
            'map' => array('type' => 'mediumtext', 'null' => true),
		));
	}

	public function down()
	{
		\DBUtil::modify_fields('micello_infos', array(
            'map' => array('type' => 'text', 'null' => true),
		));
	}
}