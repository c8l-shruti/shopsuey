<?php

namespace Fuel\Migrations;

class Create_user_location_tracking
{
	public function up()
	{
		\DBUtil::create_table('location_trackings', array(
            'id'         => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'user_id'    => array('constraint' => 11, 'type' => 'int'),
            'created_at' => array('constraint' => 11, 'type' => 'int'),
			'latitude'   => array('type' => 'double'),
			'longitude'  => array('type' => 'double'),
            'accuracy'   => array('type' => 'double', 'null' => true),
		), array('id'));
        \DBUtil::create_index('location_trackings', array('user_id', 'created_at'), 'unique_index', 'unique');
	}

	public function down()
	{
		\DBUtil::drop_table('location_trackings');
	}
}