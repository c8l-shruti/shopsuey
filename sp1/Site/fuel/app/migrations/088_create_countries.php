<?php

namespace Fuel\Migrations;

class Create_countries
{
	public function up()
	{
		\DBUtil::create_table('countries', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'code' => array('constraint' => 5, 'type' => 'varchar'),
			'name' => array('constraint' => 100, 'type' => 'varchar'),
			'position' => array('constraint' => 11, 'type' => 'int'),
		    'created_at' => array('constraint' => 11, 'type' => 'int'),
			'updated_at' => array('constraint' => 11, 'type' => 'int'),

		), array('id'));
		
		\DBUtil::add_fields('locations', array(
			'country_id'   => array('constraint' => 11, 'type' => 'int'),
		));
	}

	public function down()
	{
        \DBUtil::drop_fields('locations', array('country_id'));

	    \DBUtil::drop_table('countries');
	}
}