<?php

namespace Fuel\Migrations;

class Alter_offers_imported_info {
    
	public function up() { 
            
            \DBUtil::modify_fields('offers', array(
                'raw_imported_info' => array('type' => 'text', 'null' => true),
            ));
            
	}

	public function down() {
            
            
	}
        
}