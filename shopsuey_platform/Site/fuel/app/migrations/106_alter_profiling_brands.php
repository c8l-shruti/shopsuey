<?php

namespace Fuel\Migrations;

class Alter_profiling_brands {

    public function up(){
        \DBUtil::add_fields('profiling_choices', array(
            'name' => array('type' => 'varchar', 'constraint' => 200, 'default' => ''),
        ));
    }
        
    public function down() {

    }

}