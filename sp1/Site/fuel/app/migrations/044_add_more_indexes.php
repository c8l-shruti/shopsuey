<?php

namespace Fuel\Migrations;

class Add_more_indexes {

    public function up()
	{
        \DBUtil::create_index('offer_codes', 'offer_id', 'offer_id_idx');
        \DBUtil::create_index('users', 'group', 'group_idx');
        \DBUtil::create_index('locations', array('type', 'status'), 'type_status_idx');
        
	}

	public function down()
	{
		\DBUtil::drop_index('offer_codes', 'offer_id_idx');
		\DBUtil::drop_index('locations', 'type_status_idx');
		\DBUtil::drop_index('users', 'group_idx');
	}
    
}
