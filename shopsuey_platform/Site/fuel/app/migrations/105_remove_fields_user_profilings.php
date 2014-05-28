<?php

namespace Fuel\Migrations;

class Remove_fields_user_profilings {

	public function up() {

            \DBUtil::drop_fields('user_profilings', array(
                'created_at',
                'updated_at'
            ));

	}

	public function down() {

	}

}