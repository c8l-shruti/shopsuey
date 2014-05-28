<?php

namespace Fuel\Migrations;

class Alter_categories_profiling_choices 
{
	public function up()
	{
		\DBUtil::modify_fields('categories_profiling_choices', array(
				'location_id' => array('name' =>'category_id', 'constraint' => 11, 'type' => 'int'),
				'profiling_choice_id' => array('name' =>'profilingchoice_id', 'constraint' => 11, 'type' => 'int'),
		));
	}

	public function down()
	{
		\DBUtil::modify_fields('categories_profiling_choices', array(
				'category_id' => array('name' =>'location_id', 'constraint' => 11, 'type' => 'int'),
                'profilingchoice_id' => array('name' =>'profiling_choice_id', 'constraint' => 11, 'type' => 'int'),
		));
	}
}

