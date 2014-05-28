<?php

namespace Fuel\Migrations;

class Add_nearby_index_to_location_trackings
{
	public function up()
	{
	    \DBUtil::create_index(
	            'location_trackings',
	            array('latitude', 'longitude', 'created_at', 'accuracy', 'user_id'),
	            'location_trackings_nearby_index'
        );
	}

	public function down()
	{
	    \DBUtil::drop_index('location_trackings', 'location_trackings_nearby_index');
	}
}