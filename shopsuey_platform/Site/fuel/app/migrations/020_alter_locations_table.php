<?php

namespace Fuel\Migrations;

class Alter_locations_table
{
	public function up()
	{
		\DBUtil::add_fields('locations', array(
				'latitude' => array('type' => 'float', 'null' => true),
				'longitude' => array('type' => 'float', 'null' => true),
				'description' => array('type' => 'text', 'null' => true),
				'wifi' => array('type' => 'text', 'null' => true),
				'market_place_type' => array('type' => 'varchar', 'constraint' => 50, 'null' => true),
				'type' => array('type' => 'varchar', 'constraint' => 50),
		));
		\DBUtil::drop_fields('locations', array('retailer_id', 'gps'));
		\DBUtil::modify_fields('locations', array(
				'created' => array('name' =>'created_at', 'type' => 'int', 'constraint' => 11),
				'edited' => array('name' =>'updated_at', 'type' => 'int', 'constraint' => 11),
				'mall_id' => array('null' => true, 'type' => 'int', 'constraint' => 11),
		));
	}

	public function down()
	{
		\DBUtil::modify_fields('locations', array(
				'created_at' => array('name' =>'created', 'type' => 'int', 'constraint' => 11),
				'updated_at' => array('name' =>'edited', 'type' => 'int', 'constraint' => 11),
				'mall_id' => array('type' => 'int', 'constraint' => 11),
		));
		\DBUtil::drop_fields('locations', array('latitude', 'longitude', 'description', 'wifi', 'market_place_type', 'type'));
		\DBUtil::add_fields('locations', array(
				'retailer_id' => array('type' => 'int', 'constraint' => 11),
				'gps' => array('type' => 'text'),
		));
	}
}