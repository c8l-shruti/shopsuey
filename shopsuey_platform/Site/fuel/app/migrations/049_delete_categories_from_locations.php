<?php

namespace Fuel\Migrations;

class Delete_categories_from_locations
{
	public function up()
	{
	    \DBUtil::drop_fields('locations', array('categories'));
	}

	public function down()
	{
	    \DBUtil::add_fields('locations', array(
	            'categories' => array('type' => 'text'),
	    ));
	}
}