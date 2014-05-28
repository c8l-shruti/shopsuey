<?php

namespace Fuel\Migrations;

class Create_flags
{
	public function up()
	{
		\DBUtil::create_table('flags', array(
			'id'          => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'type'        => array('constraint' => 50, 'type' => 'varchar'),
			'title'       => array('constraint' => 255, 'type' => 'varchar'),
			'image_uri'   => array('constraint' => 255, 'type' => 'varchar', 'null' => true),
			'private'     => array('type' => 'tinyint', 'default' => 0, 'null' => true),
            'description' => array('type' => 'text', 'null' => true),
			'latitude'    => array('type' => 'float', 'null' => true),
            'longitude'   => array('type' => 'float', 'null' => true),
            'mall_id'     => array('constraint' => 11, 'type' => 'int', 'null' => true),
            'owner_id'    => array('constraint' => 11, 'type' => 'int'),
            'floor'       => array('constraint' => 11, 'type' => 'int', 'null' => true),
            'created_at'  => array('constraint' => 11, 'type' => 'int'),
			'updated_at'  => array('constraint' => 11, 'type' => 'int'),

		), array('id'));
        
        \DBUtil::create_table(
            'flag_invited_users', 
            array(
                'flag_id' => array('constraint' => 11, 'type' => 'int'),
                'user_id' => array('constraint' => 11, 'type' => 'int'),
		), array('flag_id', 'user_id'));
	}

	public function down()
	{
		\DBUtil::drop_table('flags');
		\DBUtil::drop_table('flag_invited_users');
	}
}
