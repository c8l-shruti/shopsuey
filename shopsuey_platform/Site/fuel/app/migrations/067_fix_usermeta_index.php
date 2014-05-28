<?php

namespace Fuel\Migrations;

class Fix_usermeta_index {

    public function up()
	{
//         \DBUtil::drop_index('user_metafields', 'META');
        \DBUtil::create_index('user_metafields', array('user_id', 'key'), 'user_id_key_idx');
        
	}

	public function down()
	{
        \DBUtil::drop_index('user_metafields', 'user_id_key_idx');
	}
    
}
