<?php

namespace Fuel\Migrations;

class Add_analytics_cid {

    public function up()
	{
        \DBUtil::add_fields('users', array(
			'analytics_cid' => array('type' => 'varchar', 'constraint' => 200, 'default' => ''),
		));
	}

	public function down()
	{
        \DBUtil::drop_fields('users', array(
			'analytics_cid'
		));
	}
}