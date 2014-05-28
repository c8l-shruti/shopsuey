<?php

namespace Fuel\Migrations;

class Create_offer_codes
{
	public function up()
	{
		\DBUtil::create_table('offer_codes', array(
			'id' 							=> array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'status'					=> array('constraint' => 6, 'type' => 'int', 'default' => 1),
			'type' 						=> array('constraint' => 50, 'type' => 'varchar'),
			'code' 						=> array('type' => 'text'),
			'offer_id' 				=> array('constraint' => 11, 'type' => 'int'),
			'auto_generated'	=> array('constraint' => 11, 'type' => 'int'),
			'created_at' 			=> array('constraint' => 11, 'type' => 'int'),
			'updated_at' 			=> array('constraint' => 11, 'type' => 'int'),
		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('offer_codes');
	}
}