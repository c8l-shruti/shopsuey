<?php

namespace Fuel\Migrations;

class Create_categories_locations
{
	public function up()
	{
		\DBUtil::create_table('categories_locations', array(
			'location_id' => array('constraint' => 11, 'type' => 'int'),
			'category_id' => array('constraint' => 11, 'type' => 'int'),

		), array('location_id', 'category_id'));
	}

	public function down()
	{
		\DBUtil::drop_table('categories_locations');
	}
}