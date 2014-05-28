<?php

namespace Fuel\Migrations;

class Create_micello_index {

    public function up()
	{
        \DBUtil::create_index('micello_infos', 'location_id', 'location_id_idx');
        
	}

	public function down()
	{
		\DBUtil::drop_index('micello_infos', 'location_id_idx');
	}
    
}
