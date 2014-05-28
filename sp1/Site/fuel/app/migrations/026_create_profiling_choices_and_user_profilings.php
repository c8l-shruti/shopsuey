<?php

namespace Fuel\Migrations;

class Create_profiling_choices_and_user_profilings
{
	public function up()
	{
		\DBUtil::create_table('profiling_choices', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'url' => array('constraint' => 255, 'type' => 'varchar'),
			'created_at' => array('constraint' => 11, 'type' => 'int'),
			'updated_at' => array('constraint' => 11, 'type' => 'int'),

		), array('id'));
		
		\DBUtil::create_table('user_profilings', array(
		    'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
		    'user_id' => array('constraint' => 11, 'type' => 'int'),
		    'profiling_choice_id' => array('constraint' => 11, 'type' => 'int'),
		    'created_at' => array('constraint' => 11, 'type' => 'int'),
		    'updated_at' => array('constraint' => 11, 'type' => 'int'),
		
		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('user_profilings');
	}
}