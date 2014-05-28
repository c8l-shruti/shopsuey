<?php

namespace Fuel\Migrations;

class Create_promo_codes {

    public function up()
	{
		\DBUtil::create_table('promocodes', array(
			'id'          => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'code'        => array('constraint' => 150, 'type' => 'varchar'),
			'type'        => array('constraint' => 11, 'type' => 'int'),
            'description' => array('type' => 'text'),
		    'date_start'  => array('constraint' => 11, 'type' => 'int'),
		    'date_end'    => array('constraint' => 11, 'type' => 'int'),
		    'created_at'  => array('constraint' => 11, 'type' => 'int'),
			'updated_at'  => array('constraint' => 11, 'type' => 'int'),

		), array('id'));
        
        \DBUtil::create_index('promocodes', 'code', 'promo_code_index');
		
		\DBUtil::add_fields('users', array(
			'promo_code_id'   => array('constraint' => 11, 'type' => 'int', 'null' => true),
		));
	}

	public function down()
	{
        \DBUtil::drop_fields('users', array('promo_code_id'));
	    \DBUtil::drop_table('promocodes');
	}
}