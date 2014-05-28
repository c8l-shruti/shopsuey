<?php

namespace Fuel\Migrations;

class Alter_profiling_choices {

    public function up()
	{
        \DBUtil::add_fields('profiling_choices', array(
            'order' => array('constraint' => 11, 'type' => 'int', 'null' => true),
		));
	}

	public function down()
	{
        \DBUtil::drop_fields('profiling_choices', array('order'));
	}
    
}
