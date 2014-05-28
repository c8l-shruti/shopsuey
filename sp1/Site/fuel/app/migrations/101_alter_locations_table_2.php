<?php

namespace Fuel\Migrations;

class Alter_locations_table_2
{
	public function up()
	{
	    \DBUtil::modify_fields('locations', array(
	    	'is_customer' => array('type' => 'bool', 'default' => '1'),
	    ));
	    // Set all locations as customers, this thing cannot be reverted
	    \DB::update('locations')
    	    ->value('is_customer', '1')
    	    ->execute();
	}

	public function down()
	{
	    \DBUtil::modify_fields('locations', array(
	    	'is_customer' => array('type' => 'bool'),
	    ));
	}
}