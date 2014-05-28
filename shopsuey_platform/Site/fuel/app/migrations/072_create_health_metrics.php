<?php

namespace Fuel\Migrations;

class Create_health_metrics
{
	public function up()
	{
		\DBUtil::create_table('health_metrics', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'location_id' => array('constraint' => 11, 'type' => 'int'),
		    'favorites_count' => array('constraint' => 11, 'type' => 'int'),
		    'offers_count' => array('constraint' => 11, 'type' => 'int'),
		    'events_count' => array('constraint' => 11, 'type' => 'int'),
		    'sign_ups_count' => array('constraint' => 11, 'type' => 'int'),
		    'check_ins_count' => array('constraint' => 11, 'type' => 'int'),
		    'likes_count' => array('constraint' => 11, 'type' => 'int'),
		    'follows_count' => array('constraint' => 11, 'type' => 'int'),
		    'created_at' => array('constraint' => 11, 'type' => 'int'),
		    'updated_at' => array('constraint' => 11, 'type' => 'int'),
		    
		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('health_metrics');
	}
}