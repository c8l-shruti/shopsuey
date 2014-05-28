<?php

namespace Fuel\Migrations;

class Create_Events
{
	public function up()
	{
		\DBUtil::drop_table('events');

		\DBUtil::create_table('events', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'title' => array('type' => 'varchar', 'constraint' => 100),
			'description' => array('type' => 'text'),
			'featured_image' => array('type' => 'varchar', 'constraint' => 250, 'null' => true),
			'coupon_image' => array('type' => 'varchar', 'constraint' => 250, 'null' => true),
			'coordinator_phone' => array('type' => 'varchar', 'constraint' => 100),
			'coordinator_email' => array('type' => 'varchar', 'constraint' => 100),
			'website' => array('type' => 'varchar', 'constraint' => 250),
			'date_start' => array('type' => 'timestamp'),
			'date_end' => array('type' => 'timestamp'),
			'status' => array('type' => 'int'),
			'code' => array('type' => 'varchar', 'constraint' => 50),
			'tags' => array('type' => 'text'),
			'mall_id' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'merchant_id' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'created_by_id' => array('constraint' => 11, 'type' => 'int'),
			'edited_by_id' => array('constraint' => 11, 'type' => 'int'),
			'created_at' => array('constraint' => 11, 'type' => 'int'),
			'updated_at' => array('constraint' => 11, 'type' => 'int'),
		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('events');

		\DBUtil::create_table('events', array(
			'id' => array('type' => 'int', 'null' => true, 'constraint' => 11, 'auto_increment' => true),
			'retailer_id' => array('type' => 'int', 'default' => '0', 'constraint' => 11),
			'status' => array('type' => 'smallint', 'default' => '1', 'constraint' => 6),
			'locations' => array('type' => 'text', 'null' => true),
			'malls' => array('type' => 'text', 'null' => true),
			'name' => array('type' => 'text', 'null' => true),
			'content' => array('type' => 'text', 'null' => true),
			'date_start' => array('type' => 'datetime', 'null' => true),
			'date_end' => array('type' => 'datetime', 'null' => true),
			'code' => array('type' => 'text', 'null' => true),
			'gallery' => array('type' => 'text', 'null' => true),
			'tags' => array('type' => 'text', 'null' => true),
			'created' => array('type' => 'timestamp'),
			'edited' => array('type' => 'datetime', 'null' => true),
			'created_by' => array('type' => 'text', 'null' => true),
			'edited_by' => array('type' => 'text', 'null' => true),
		), array('id'));

	}
}
