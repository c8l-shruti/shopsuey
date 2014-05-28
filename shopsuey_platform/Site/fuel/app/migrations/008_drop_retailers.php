<?php

namespace Fuel\Migrations;

class Drop_retailers
{
	public function up()
	{
		\DBUtil::drop_table('retailers');
	}

	public function down()
	{
		\DBUtil::create_table('retailers', array(
			'id' => array('type' => 'int', 'null' => true, 'constraint' => 11, 'auto_increment' => true),
			'status' => array('type' => 'smallint', 'default' => '1', 'constraint' => 6),
			'name' => array('type' => 'varchar', 'null' => true, 'constraint' => 100),
			'address' => array('type' => 'varchar', 'null' => true, 'constraint' => 255),
			'city' => array('type' => 'varchar', 'null' => true, 'constraint' => 100),
			'st' => array('type' => 'varchar', 'null' => true, 'constraint' => 2),
			'zip' => array('type' => 'varchar', 'null' => true, 'constraint' => 20),
			'description' => array('type' => 'text', 'null' => true),
			'contact' => array('type' => 'text', 'null' => true),
			'social' => array('type' => 'text', 'null' => true),
			'web' => array('type' => 'text', 'null' => true),
			'categories' => array('type' => 'text', 'null' => true),
			'tags' => array('type' => 'text', 'null' => true),
			'hours' => array('type' => 'text', 'null' => true),
			'created' => array('type' => 'timestamp', 'default' => 'CURRENT_TIMESTAMP'),
			'edited' => array('type' => 'datetime', 'null' => true),
			'created_by' => array('type' => 'text', 'null' => true),
			'edited_by' => array('type' => 'text', 'null' => true),

		), array('id'));

	}
}