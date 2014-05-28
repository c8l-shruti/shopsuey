<?php

namespace Fuel\Migrations;

class Delete_explore_icon_from_locations
{
	public function up()
	{
	    \DBUtil::drop_fields('locations', array(
    		'explore_icon'
	    ));
	}

	public function down()
	{
	    \DBUtil::add_fields('locations', array(
    		'explore_icon' => array('type' => 'text', 'null' => true),
	    ));
	}
}