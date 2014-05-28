<?php

namespace Fuel\Migrations;

class Create_redeems
{
	public function up()
	{
		\DBUtil::create_table('offer_redeems', array(
			'id' 						=> array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'date' 					=> array('type' => 'datetime'),
			'offer_code_id'	=> array('constraint' => 11, 'type' => 'int'),
			'user_id' 			=> array('constraint' => 11, 'type' => 'int'),
			'created_at'		=> array('constraint' => 11, 'type' => 'int'),
			'updated_at'		=> array('constraint' => 11, 'type' => 'int'),
		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('offer_redeems');
	}
}
