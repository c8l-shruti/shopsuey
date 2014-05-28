<?php

namespace Fuel\Migrations;

class Alter_rewards {
    
	public function up() { 
            
            \DBUtil::add_fields('rewards', array(
                'email_sent' => array('type' => 'datetime', 'null' => true),
            ));
            
	}

	public function down() {
            
            
	}
        
}