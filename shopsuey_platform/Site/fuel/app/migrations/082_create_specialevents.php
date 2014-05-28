<?php

namespace Fuel\Migrations;

class Create_specialevents
{
	public function up()
	{
		\DBUtil::create_table('specialevents', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'created_by_id'      => array('constraint' => 11, 'type' => 'int'),
			'edited_by_id'       => array('constraint' => 11, 'type' => 'int'),
            'title'              => array('constraint' => 255, 'type' => 'varchar'),
            'description'        => array('type' => 'text', 'null' => true),
            'logo'               => array('constraint' => 255, 'type' => 'varchar'),
            'landing_screen_img' => array('constraint' => 255, 'type' => 'varchar'),
			'main_location_id'   => array('constraint' => 11, 'type' => 'int'),
            'coordinator_phone'  => array('constraint' => 255, 'type' => 'varchar'),
            'coordinator_email'  => array('constraint' => 255, 'type' => 'varchar'),
            'website'            => array('constraint' => 255, 'type' => 'varchar'),
            'show_dates'         => array('type' => 'tinyint', 'default' => 0, 'null' => true),
          	'date_start'         => array('type' => 'timestamp'),
            'date_end'           => array('type' => 'timestamp'),
            'status'             => array('type' => 'int'),
            'tags'               => array('type' => 'text'),
            'social'             => array('type' => 'text'),
            'force_top_message'  => array('type' => 'tinyint', 'default' => 0, 'null' => true),
			'created_at'         => array('constraint' => 11, 'type' => 'int'),
			'updated_at'         => array('constraint' => 11, 'type' => 'int'),

		), array('id'));

        \DBUtil::create_table('locations_specialevents', array(
			'location_id' => array('constraint' => 11, 'type' => 'int'),
			'specialevent_id' => array('constraint' => 11, 'type' => 'int'),

		), array('specialevent_id', 'location_id'));
	}

	public function down()
	{
		\DBUtil::drop_table('specialevents');
		\DBUtil::drop_table('locations_specialevents');
	}
}