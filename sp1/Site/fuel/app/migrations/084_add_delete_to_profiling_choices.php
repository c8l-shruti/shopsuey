<?php

namespace Fuel\Migrations;

class Add_delete_to_profiling_choices
{
	public function up()
	{
		\DBUtil::add_fields('profiling_choices', array(
			'deleted'    => array('type' => 'tinyint', 'default' => 0, 'null' => true),
			'deleted_by' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'deleted_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
		));	
	}

	public function down()
	{
		\DBUtil::drop_fields('profiling_choices', array(
			'deleted',
            'deleted_by',
            'deleted_at'
		));
	}
}