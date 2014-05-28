<?php

namespace Fuel\Migrations;

class Add_default_logo_and_default_landing_img_to_locations
{
	public function up()
	{
		\DBUtil::add_fields('locations', array(
			'default_logo' => array('type' => 'bool', 'default' => '0', 'null' => true),
			'default_landing_screen_img' => array('type' => 'bool', 'default' => '0', 'null' => true),
		));
		\DBUtil::create_index('locations', 'name', 'name_idx');
	}

	public function down()
	{
		\DBUtil::drop_fields('locations', array(
            'default_logo',
	        'default_landing_screen_img'
		));
		\DBUtil::drop_index('locations', 'name', 'name_idx');
	}
}