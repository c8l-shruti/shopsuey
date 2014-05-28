<?php

namespace Fuel\Migrations;

class Fix_null_descriptions
{
	public function up()
	{
	    // Set null descriptions as empty strings, cannot be undone
	    \DB::update('locations')
    	    ->value('description', '')
    	    ->where('description', NULL)
    	    ->execute();
	}

	public function down()
	{

	}
}