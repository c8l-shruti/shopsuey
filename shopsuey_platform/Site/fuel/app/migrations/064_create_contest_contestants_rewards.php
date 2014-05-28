<?php

namespace Fuel\Migrations;

class Create_contest_contestants_rewards
{
	public function up()
	{
		\DBUtil::create_table('contest', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'name' => array('constraint' => 255, 'type' => 'varchar'),
		    'start_date' => array('type' => 'timestamp'),
		    'end_date' => array('type' => 'timestamp'),
		    'created_at' => array('constraint' => 11, 'type' => 'int'),
		    'updated_at' => array('constraint' => 11, 'type' => 'int'),
		    
		), array('id'));
		
		\DBUtil::create_table('contestant', array(
		    'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
		    'user_id' => array('constraint' => 11, 'type' => 'int'),
		    'contest_id' => array('constraint' => 11, 'type' => 'int'),
		    'reward_id' => array('constraint' => 11, 'type' => 'int', 'null' => true),
		    'created_at' => array('constraint' => 11, 'type' => 'int'),
		    'updated_at' => array('constraint' => 11, 'type' => 'int'),
		    
		), array('id'));
		
		\DBUtil::create_table('reward', array(
		    'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
		    'contest_id' => array('constraint' => 11, 'type' => 'int'),
		    'offer_id' => array('constraint' => 11, 'type' => 'int'),
		    'sent' => array('type' => 'timestamp', 'null' => true),
		    'created_at' => array('constraint' => 11, 'type' => 'int'),
		    'updated_at' => array('constraint' => 11, 'type' => 'int'),
		    
		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('reward');
		\DBUtil::drop_table('contestant');
		\DBUtil::drop_table('contest');
	}
}