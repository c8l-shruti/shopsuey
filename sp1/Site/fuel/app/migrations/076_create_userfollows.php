<?php

namespace Fuel\Migrations;

class Create_userfollows
{
	public function up()
	{
        \DBUtil::create_table(
            'user_follows', 
            array(
                'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
                'follower_id' => array('constraint' => 11, 'type' => 'int'),
                'followee_id' => array('constraint' => 11, 'type' => 'int'),
                'created_at' => array('constraint' => 11, 'type' => 'int'),
                'updated_at' => array('constraint' => 11, 'type' => 'int')

		), array('id', 'follower_id', 'followee_id'));
	}

	public function down()
	{
		\DBUtil::drop_table('user_follows');
	}
}
