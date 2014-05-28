<?php

namespace Fuel\Migrations;

class Alter_malls_and_merchants_tables
{
	public function up()
	{
		\DBUtil::drop_fields('malls', array('gps'));
        \DBUtil::add_fields('malls', array(
			'latitude'  => array('type' => 'float', 'null' => true),
			'longitude' => array('type' => 'float', 'null' => true),
		));
		\DBUtil::add_fields('merchants', array(
			'latitude'  => array('type' => 'float', 'null' => true),
			'longitude' => array('type' => 'float', 'null' => true),
		));
	}

	public function down()
	{
		\DBUtil::drop_fields('merchants', array('latitude', 'longitude'));
		\DBUtil::drop_fields('malls', array('latitude', 'longitude'));
		\DBUtil::add_fields('malls', array('gps' => array('type' => 'text')));
	}
}