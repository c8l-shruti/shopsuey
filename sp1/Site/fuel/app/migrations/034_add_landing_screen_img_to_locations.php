<?php

namespace Fuel\Migrations;

class Add_landing_screen_img_to_locations
{
	public function up()
	{
		\DBUtil::add_fields('locations', array(
            'landing_screen_img' => array('type' => 'text', 'null' => true),
		));
	}

	public function down()
	{
		\DBUtil::drop_fields('locations', array(
            'landing_screen_img'
		));
	}
}