<?php

namespace Fuel\Migrations;

class Add_gallery_to_events
{
	public function up()
	{
		\DBUtil::add_fields('events', array(
			'gallery' => array('type' => 'text', 'null' => true),

		));	
	}

	public function down()
	{
		\DBUtil::drop_fields('events', array(
			'gallery'
    
		));
	}
}