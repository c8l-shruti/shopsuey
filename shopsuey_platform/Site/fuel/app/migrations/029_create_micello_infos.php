<?php

namespace Fuel\Migrations;

class Create_micello_infos
{
	public function up()
	{
		\DBUtil::create_table('micello_infos', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'location_id' => array('constraint' => 11, 'type' => 'int'),
			'micello_id' => array('constraint' => 11, 'type' => 'int'),
			'type' => array('constraint' => 50, 'type' => 'varchar'),
			'map' => array('type' => 'text', 'null' => true),
			'map_expiracy' => array('type' => 'datetime', 'null' => true),
			'geometry_id' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'created_at' => array('constraint' => 11, 'type' => 'int'),
			'updated_at' => array('constraint' => 11, 'type' => 'int'),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('micello_infos');
	}
}