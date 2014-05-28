<?php

namespace Fuel\Migrations;

class Rename_table_retailer_blockings_to_merchant_blockings
{
	public function up()
	{
		\DBUtil::rename_table('retailer_blockings', 'merchant_blockings');
		\DBUtil::modify_fields('merchant_blockings', array(
				'retailer_id' => array('name' => 'merchant_id', 'type' => 'int', 'constraint' => 11),
		));
	}

	public function down()
	{
		\DBUtil::rename_table('merchant_blockings', 'retailer_blockings');
		\DBUtil::modify_fields('retailer_blockings', array(
				'merchant_id' => array('name' => 'retailer_id', 'type' => 'int', 'constraint' => 11),
		));
	}
}