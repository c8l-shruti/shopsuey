<?php

namespace Fuel\Migrations;

class Alter_offers_2
{
	public function up()
	{
		\DBUtil::drop_fields('offers', array('retailer_id', 'malls', 'locations'));
		\DBUtil::modify_fields('offers', array(
				'created' => array('name' =>'created_at', 'type' => 'int', 'constraint' => 11),
				'edited' => array('name' =>'updated_at', 'type' => 'int', 'constraint' => 11),
		));
	}

	public function down()
	{
		\DBUtil::modify_fields('offers', array(
				'created_at' => array('name' =>'created', 'type' => 'int', 'constraint' => 11),
				'updated_at' => array('name' =>'edited', 'type' => 'int', 'constraint' => 11),
		));
		\DBUtil::add_fields('offers', array(
				'retailer_id' => array('type' => 'int', 'constraint' => 11),
				'malls'		    => array('type' => 'text'),
				'locations'   => array('type' => 'text'),
		));
	}
}