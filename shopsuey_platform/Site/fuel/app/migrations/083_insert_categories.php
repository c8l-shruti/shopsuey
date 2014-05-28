<?php

namespace Fuel\Migrations;

class Insert_categories
{
    public function up()
	{
	    $categories_names = array(
	        'Apparel',
	        'Casual Dining',
	        'Fine Dining',
	        'Convenience',
	        'Nightlife',
	        'Art'
	    );
	    
	    foreach ($categories_names as $category_name) {
            $category = new \Model_Category();
            $category->name = $category_name;
            
            $category->save();
	    }
    }

    public function down()
    {
        // Nothing to do
    }
}