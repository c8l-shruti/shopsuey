<?php

namespace Fuel\Migrations;

class Create_location_like_checkin {
    
    public function up()
	{
		\DBUtil::create_table('location_likes', array(
			'id'          => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'user_id'     => array('constraint' => 11, 'type' => 'int'),
			'location_id' => array('constraint' => 11, 'type' => 'int'),
			'created_at'  => array('constraint' => 11, 'type' => 'int'),
			'updated_at'  => array('constraint' => 11, 'type' => 'int'),
		), array('id'));
        \DBUtil::create_index('location_likes', array('user_id', 'location_id'), 'location_user_unique_index', 'UNIQUE');
        
        
        \DBUtil::create_table('location_checkins', array(
			'id'          => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'user_id'     => array('constraint' => 11, 'type' => 'int'),
			'location_id' => array('constraint' => 11, 'type' => 'int'),
			'created_at'  => array('constraint' => 11, 'type' => 'int'),
			'updated_at'  => array('constraint' => 11, 'type' => 'int'),
		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('location_likes');
		\DBUtil::drop_table('location_checkins');
	}
}