<?php

namespace Fuel\Migrations;

class Add_show_dates
{
	public function up()
	{
		\DBUtil::add_fields('events', array(
            'show_dates' => array('type' => 'bool', 'default' => '0'),
		));
        \DBUtil::add_fields('offers', array(
            'show_dates' => array('type' => 'bool', 'default' => '0'),
		));
	}

	public function down()
	{
		\DBUtil::drop_fields('offers', array(
            'show_dates'
		));
        \DBUtil::drop_fields('events', array(
            'show_dates'
		));
	}
    
}
