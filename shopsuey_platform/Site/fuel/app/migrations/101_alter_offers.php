<?php

namespace Fuel\Migrations;

class Alter_offers {
    
	public function up() { 
            
            \DBUtil::add_fields('offers', array(
                'provider' => array('constraint'=> 20, 'type' => 'varchar', 'null' => true),
                'internal_id' => array('constraint'=> 20, 'type' => 'varchar', 'null' => true),
                'raw_imported_info' => array('type' => 'text'),
                'imported_url'  => array('type' => 'text', 'null' => true),
            ));
            
	}

	public function down() {
            
            
	}
        
}