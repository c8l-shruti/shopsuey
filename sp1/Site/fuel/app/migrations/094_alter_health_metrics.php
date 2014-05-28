<?php

namespace Fuel\Migrations;

class Alter_health_metrics {

    public function up()
	{
        \DBUtil::add_fields('health_metrics', array(
            'likes_via_ss_count'     => array('constraint' => 11, 'type' => 'int', 'null' => true),
            'check_ins_via_ss_count' => array('constraint' => 11, 'type' => 'int', 'null' => true),
		));
	}

	public function down()
	{
        \DBUtil::drop_fields('health_metrics', array('likes_via_ss_count', 'check_ins_via_ss_count'));
	}
}