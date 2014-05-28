<?php

namespace Fuel\Migrations;

class Create_indexes {

    public function up()
	{
        \DBUtil::create_index('locations_offers', 'offer_id', 'offer_id_idx');
        \DBUtil::create_index('locations_events', 'location_id', 'location_id_idx');
        \DBUtil::create_index('locations', 'mall_id', 'mall_id_idx');
        
	}

	public function down()
	{
		\DBUtil::drop_index('locations_offers', 'offer_id_idx');
		\DBUtil::drop_index('locations_events', 'location_id_idx');
		\DBUtil::drop_index('locations', 'mall_id_idx');
	}
    
}
