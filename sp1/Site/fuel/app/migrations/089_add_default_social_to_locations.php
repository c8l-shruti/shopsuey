<?php

namespace Fuel\Migrations;

class Add_default_social_to_locations
{
	public function up()
	{
		\DBUtil::add_fields('locations', array(
			'default_social' => array('type' => 'bool', 'default' => '0', 'null' => true),
		));
		\DBUtil::create_index('locations', 'default_social', 'default_social_idx');
	}

	public function down()
	{
		\DBUtil::drop_fields('locations', array(
            'default_social',
		));
		\DBUtil::drop_index('locations', 'default_social', 'default_social_idx');
	}
}