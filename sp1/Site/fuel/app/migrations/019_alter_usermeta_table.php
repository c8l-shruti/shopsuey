<?php

namespace Fuel\Migrations;

class Alter_usermeta_table
{
	public function up()
	{
		\DBUtil::rename_table('usermeta', 'user_metafields');
		\DBUtil::modify_fields('user_metafields', array(
				'meta_key' => array('name' =>'key', 'type' => 'varchar', 'constraint' => 50),
				'meta_value' => array('name' =>'value', 'type' => 'text'),
		));
		\DBUtil::add_fields('user_metafields', array(
				'created_at' => array('type' => 'int', 'constraint' => 11),
				'updated_at' => array('type' => 'int', 'constraint' => 11),
		));
	}

	public function down()
	{
		\DBUtil::drop_fields('user_metafields', array('created_at', 'updated_at'));
		\DBUtil::modify_fields('user_metafields', array(
				'key' => array('name' =>'meta_key', 'type' => 'varchar', 'constraint' => 50),
				'value' => array('name' =>'meta_value', 'type' => 'text'),
		));
		\DBUtil::rename_table('user_metafields', 'usermeta');
	}
}