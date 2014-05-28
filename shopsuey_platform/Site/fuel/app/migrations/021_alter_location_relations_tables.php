<?php

namespace Fuel\Migrations;

class Alter_location_relations_tables
{
	public function up()
	{
		\DBUtil::drop_table('merchants_offers');
		\DBUtil::rename_table('malls_offers', 'locations_offers');
		\DBUtil::modify_fields('locations_offers', array(
				'mall_id' => array('name' =>'location_id', 'type' => 'int', 'constraint' => 11),
		));
		
		\DBUtil::rename_table('merchant_blockings', 'location_blockings');
		\DBUtil::modify_fields('location_blockings', array(
				'merchant_id' => array('name' =>'location_id', 'type' => 'int', 'constraint' => 11),
		));
		
		\DBUtil::drop_table('users_favorite_merchants');
		\DBUtil::rename_table('users_favorite_malls', 'users_favorite_locations');
		\DBUtil::modify_fields('users_favorite_locations', array(
				'mall_id' => array('name' =>'location_id', 'type' => 'int', 'constraint' => 11),
		));
	}

	public function down()
	{
		\DBUtil::modify_fields('users_favorite_locations', array(
				'location_id' => array('name' =>'mall_id', 'type' => 'int', 'constraint' => 11),
		));
		\DBUtil::rename_table('users_favorite_locations', 'users_favorite_malls');
		\DBUtil::create_table('users_favorite_merchants', array(
				'user_id' => array('constraint' => 11, 'type' => 'int'),
				'merchant_id' => array('constraint' => 11, 'type' => 'int'),
		
		), array('user_id', 'merchant_id'));
		
		\DBUtil::modify_fields('location_blockings', array(
				'location_id' => array('name' =>'merchant_id', 'type' => 'int', 'constraint' => 11),
		));
		\DBUtil::rename_table('location_blockings', 'merchant_blockings');
		
		\DBUtil::modify_fields('locations_offers', array(
				'location_id' => array('name' =>'mall_id', 'type' => 'int', 'constraint' => 11),
		));
		\DBUtil::rename_table('locations_offers', 'malls_offers');
		\DBUtil::create_table('merchants_offers', array(
				'merchant_id' => array('constraint' => 11, 'type' => 'int'),
				'offer_id' => array('constraint' => 11, 'type' => 'int'),
		
		), array('merchant_id', 'offer_id'));
	}
}