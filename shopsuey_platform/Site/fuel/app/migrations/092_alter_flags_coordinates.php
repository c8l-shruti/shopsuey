<?php

namespace Fuel\Migrations;

class Alter_flags_coordinates {
    
    public function up()
	{
        \DBUtil::modify_fields('flags', array(
            'latitude' => array('type' => 'decimal', 'constraint' => '13,10', 'null' => true),
            'longitude' => array('type' => 'decimal', 'constraint' => '13,10', 'null' => true),
		));
	}

	public function down()
	{
        \DBUtil::modify_fields('flags', array(
            'latitude' => array('type' => 'float', 'null' => true),
            'longitude' => array('type' => 'float', 'null' => true),
		));
	}
}
