<?php

namespace Fuel\Migrations;

class Add_offer_type
{
	public function up()
	{
        \DBUtil::add_fields('offers', array(
            'type' => array('type' => 'tinyint', 'default' => 0),
		));
	}

	public function down()
	{
        \DBUtil::drop_fields('offers', array(
            'type'
		));
	}
}