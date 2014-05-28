<?php

namespace Fuel\Migrations;

class Create_merchants_offers
{
	public function up()
	{
		\DBUtil::create_table('merchants_offers', array(
			'merchant_id' => array('constraint' => 11, 'type' => 'int'),
			'offer_id' => array('constraint' => 11, 'type' => 'int'),

		), array('merchant_id', 'offer_id'));
	}

	public function down()
	{
		\DBUtil::drop_table('merchants_offers');
	}
}