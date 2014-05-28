<?php

namespace Fuel\Migrations;

class Add_redeemable_to_offers
{
	public function up()
	{
		\DBUtil::add_fields('offers', array(
			'redeemable' => array('type' => 'bool'),

		));	
	}

	public function down()
	{
		\DBUtil::drop_fields('offers', array(
			'redeemable'
    
		));
	}
}