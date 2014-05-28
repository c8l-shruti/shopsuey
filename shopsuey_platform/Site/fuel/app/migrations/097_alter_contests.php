<?php

namespace Fuel\Migrations;

class Alter_contests {
    
	public function up() {
            
            \DBUtil::add_fields('contests', array(
                'how_favorite_location_id' => array('constraint' => 11, 'type' => 'int', 'null' => true),
                'how_checkin_location_id' => array('constraint' => 11, 'type' => 'int', 'null' => true),
                'how_signup' => array('type' => 'boolean', 'null' => true),
            ));
            
	}

	public function down() {
            
            
	}
        
}