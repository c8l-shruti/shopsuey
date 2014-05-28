<?php

namespace Fuel\Migrations;

class Alter_users_favorite_locations {
    
	public function up() {
            
            \DBUtil::add_fields('users_favorite_locations', array(
                'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
                'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
            ));
            
	}

	public function down() {
            
            
	}
        
}