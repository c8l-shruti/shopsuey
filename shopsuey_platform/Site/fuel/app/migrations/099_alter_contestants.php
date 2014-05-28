<?php

namespace Fuel\Migrations;

class Alter_contestants {
    
	public function up() {
            
            \DBUtil::add_fields('contestants', array(
                'email_sent' => array('type' => 'datetime', 'null' => true),
            ));
            
	}

	public function down() {
            
            
	}
        
}