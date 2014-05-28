<?php

namespace Fuel\Migrations;

class Alter_flags_add_location_id
{
	public function up()
	{
        \DBUtil::add_fields('flags', array(
            'location_id'   => array('constraint' => 11, 'type' => 'int', 'null' => true),
            'location_type' => array('constraint' => 20, 'type' => 'varchar', 'null' => true),
		));
		\DBUtil::drop_fields('flags', array('mall_id'));
	}

	public function down()
	{
        \DBUtil::drop_fields('flags', array('location_id', 'location_type'));
		\DBUtil::add_fields('flags', array(
			'mall_id' => array('constraint' => 11, 'type' => 'int')
		));
	}
}