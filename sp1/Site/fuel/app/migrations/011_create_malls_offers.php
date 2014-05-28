<?php

namespace Fuel\Migrations;

class Create_malls_offers
{
	public function up()
	{
		\DBUtil::create_table('malls_offers', array(
			'mall_id' => array('constraint' => 11, 'type' => 'int'),
			'offer_id' => array('constraint' => 11, 'type' => 'int'),

		), array('mall_id', 'offer_id'));
	}

	public function down()
	{
		\DBUtil::drop_table('malls_offers');
	}
}