<?php

namespace Fuel\Migrations;

class Create_profiling_locations {

    public function up(){
        
        \DBUtil::create_table('profilings_locations', array(
            'location_id'           => array('constraint' => 11, 'type' => 'int'),
            'profilingchoice_id'    => array('constraint' => 11, 'type' => 'int'),
        ), array('location_id', 'profilingchoice_id'));
        
    }

    public function down(){
        
    }
}