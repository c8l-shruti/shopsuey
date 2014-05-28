<?php

namespace Fuel\Migrations;

class Create_like_tables
{
	public function up()
	{
		\DBUtil::create_table('eventlikes', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'user_id' => array('constraint' => 11, 'type' => 'int'),
			'event_id' => array('constraint' => 11, 'type' => 'int'),
            'status' => array('constraint' => 11, 'type' => 'int'),
			'created_at' => array('constraint' => 11, 'type' => 'int'),
			'updated_at' => array('constraint' => 11, 'type' => 'int'),
		), array('id'));
        \DBUtil::create_index('eventlikes', array('user_id', 'event_id'), 'likes_unique_index', 'unique');
        \DBUtil::create_index('eventlikes', 'event_id');
        
        \DBUtil::create_table('offerlikes', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'user_id' => array('constraint' => 11, 'type' => 'int'),
			'offer_id' => array('constraint' => 11, 'type' => 'int'),
            'status' => array('constraint' => 11, 'type' => 'int'),
			'created_at' => array('constraint' => 11, 'type' => 'int'),
			'updated_at' => array('constraint' => 11, 'type' => 'int'),
		), array('id'));
        \DBUtil::create_index('offerlikes', array('user_id', 'offer_id'), 'likes_unique_index', 'unique');
        \DBUtil::create_index('offerlikes', 'offer_id');
	}

	public function down()
	{
		\DBUtil::drop_table('likes_events');
		\DBUtil::drop_table('likes_offers');
	}
}