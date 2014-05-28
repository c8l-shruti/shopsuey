<?php

namespace Fuel\Migrations;

class Create_categories_profiling_choices
{
	public function up()
	{
		\DBUtil::create_table('categories_profiling_choices', array(
			'location_id' => array('constraint' => 11, 'type' => 'int'),
			'profiling_choice_id' => array('constraint' => 11, 'type' => 'int'),

		), array('location_id', 'profiling_choice_id'));
	}

	public function down()
	{
		\DBUtil::drop_table('categories_profiling_choices');
	}
}