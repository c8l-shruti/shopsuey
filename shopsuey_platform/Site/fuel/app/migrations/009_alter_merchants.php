<?php

namespace Fuel\Migrations;

class Alter_merchants
{
	public function up()
	{
		\DBUtil::modify_fields('merchants', array(
				'mall_id' => array('type' => 'int', 'null' => true, 'constraint' => 11),
		));
	}

	public function down()
	{
		\DBUtil::modify_fields('merchants', array(
				'mall_id' => array('type' => 'int', 'constraint' => 11),
		));
	}
}