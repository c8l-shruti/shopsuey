<?php

namespace Fuel\Migrations;

class Fix_rewards_and_contestants
{
	public function up()
	{
	    \DBUtil::rename_table('reward', 'rewards');
	    \DBUtil::rename_table('contestant', 'contestants');
	    \DBUtil::rename_table('contest', 'contests');
	    
        \DBUtil::add_fields('rewards', array(
            'contestant_id' => array('constraint' => 11, 'type' => 'int', 'null' => true),
            'grand_prize' => array('type' => 'bool', 'default' => 0),
		));
        \DBUtil::create_index('rewards', array('contest_id', 'offer_id'), 'rewards_unique_index', 'unique');
        
        \DBUtil::drop_fields('contestants', array(
            'reward_id',
        ));
        
        \DBUtil::add_fields('contestants', array(
            'pn_sent' => array('type' => 'bool', 'default' => 0),
        ));
        
        \DBUtil::create_index('contestants', array('contest_id', 'user_id'), 'contestants_unique_index', 'unique');
        \DBUtil::create_index('contestants', 'user_id');
	}

	public function down()
	{
	    \DBUtil::drop_fields('contestants', array(
    		'pn_sent',
	    ));

	    \DBUtil::add_fields('contestants', array(
    		'reward_id' => array('constraint' => 11, 'type' => 'int', 'null' => true),
	    ));
	    
        \DBUtil::drop_fields('rewards', array(
            'contestant_id',
            'grand_prize',
		));
        
        \DBUtil::rename_table('rewards', 'reward');
        \DBUtil::rename_table('contestants', 'contestant');
        \DBUtil::rename_table('contests', 'contest');
	}
}