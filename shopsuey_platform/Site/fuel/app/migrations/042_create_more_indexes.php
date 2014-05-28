<?php

namespace Fuel\Migrations;

class Create_more_indexes {

    public function up()
	{
        \DBUtil::create_index('locations', array('status', 'name'), 'status_name_idx');
        \DBUtil::create_index('sessions', 'updated', 'updated_idx');
        \DBUtil::create_index('notices', array('status', 'date_start'), 'status_date_start_idx');
        \DBUtil::create_index('events', array('status', 'date_start'), 'status_date_start_idx');
        \DBUtil::create_index('offers', array('status', 'date_start'), 'status_date_start_idx');
        
	}

	public function down()
	{
		\DBUtil::drop_index('locations', 'status_name_idx');
        \DBUtil::drop_index('sessions', 'updated_idx');
        \DBUtil::drop_index('notices', 'status_date_start_idx');
        \DBUtil::drop_index('events', 'status_date_start_idx');
        \DBUtil::drop_index('offers', 'status_date_start_idx');
	}
    
}
