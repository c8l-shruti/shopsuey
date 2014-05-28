<?php

namespace Fuel\Migrations;

class Add_map_version_to_micello_infos
{
	public function up()
	{
		\DBUtil::add_fields('micello_infos', array(
			'map_version' => array('constraint' => 11, 'type' => 'int', 'null' => TRUE),

		));	
	}

	public function down()
	{
		\DBUtil::drop_fields('micello_infos', array(
			'map_version'
    
		));
	}
}