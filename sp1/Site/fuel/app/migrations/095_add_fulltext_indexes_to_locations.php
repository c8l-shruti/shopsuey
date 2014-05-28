<?php

namespace Fuel\Migrations;

class Add_fulltext_indexes_to_locations
{
	public function up()
	{
	    $sql = 'ALTER TABLE `' . \DB::table_prefix('locations') . '` ENGINE = MyISAM';
	    \DB::query($sql, \DB::UPDATE)->execute();
	    \DBUtil::create_index(
	            'locations',
	            array('name','address','city','st','zip','email','web','description','tags'),
	            'locations_all_fulltext_index',
	            'FULLTEXT'
        );
	    \DBUtil::create_index(
	            'locations',
	            array('name'),
	            'locations_name_fulltext_index',
	            'FULLTEXT'
        );
	}

	public function down()
	{
	    \DBUtil::drop_index('locations', 'locations_name_fulltext_index');
	    \DBUtil::drop_index('locations', 'locations_all_fulltext_index');
	    $sql = 'ALTER TABLE `' . \DB::table_prefix('locations') . '` ENGINE = InnoDB';
	    \DB::query($sql, \DB::UPDATE)->execute();
	}
}