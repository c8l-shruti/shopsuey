<?php

namespace Fuel\Migrations;

class Create_retailer_blockings
{
	public function up()
	{
		\DBUtil::create_table('retailer_blockings', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'type' => array('constraint' => 20, 'type' => 'varchar'),
			'retailer_id' => array('constraint' => 11, 'type' => 'int'),
			'user_id' => array('constraint' => 11, 'type' => 'int'),
			'start_date' => array('type' => 'datetime'),
			'end_date' => array('type' => 'datetime'),
			'created_at' => array('constraint' => 11, 'type' => 'int'),
			'updated_at' => array('constraint' => 11, 'type' => 'int'),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('retailer_blockings');
	}
}