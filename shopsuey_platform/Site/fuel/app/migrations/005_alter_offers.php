<?php

namespace Fuel\Migrations;

class Alter_offers
{
	public function up()
	{
		\DBUtil::drop_fields('offers', 'code');
		\DBUtil::add_fields('offers', array(
			'allowed_redeems' 	=> array('type' => 'int', 'constraint' => 11, 'default' => 1),
			'multiple_codes'		=> array('type' => 'int', 'constraint' => 1),
			'default_code_type' => array('constraint' => 50, 'type' => 'varchar'),
		));
	}

	public function down()
	{
		\DBUtil::drop_fields('offers', array(
			'default_code_type',
			'multiple_codes',
			'allowed_redeems',
		));
		\DBUtil::add_fields('offers', array(
			'code' => array('type' => 'text'),
		));
	}
}
