<?php

namespace Fuel\Migrations;

class Add_index_to_offer_redeems
{
	public function up()
	{
	    \DBUtil::create_index(
    		'offer_redeems',
    		'offer_code_id',
    		'offer_redeems_offer_code_id_index'
	    );
	}

	public function down()
	{
	    \DBUtil::drop_index('offer_redeems', 'offer_redeems_offer_code_id_index');
	}
}