<?php

class Model_Profilingchoice extends \Orm\Model
{
	protected static $_table_name = 'profiling_choices';

	protected static $_many_many = array(
		'users' => array(
			'key_through_to' => 'user_id',
                        'key_through_from' => 'profiling_choice_id',
			'table_through' => 'user_profilings',
                        
		),
		'categories' => array(
                    'table_through' => 'categories_profiling_choices',
                ),
                'locations' => array(
                    'table_through' => 'profilings_locations',
                    'key_through_from' => 'profilingchoice_id',
                ),
	);
	
	protected static $_properties = array(
		'id',
            'name',
		'url',
            'order',
            'deleted',
            'deleted_by',
            'deleted_at',
	    'created_at',
	    'updated_at' => array('data_type' => 'int'),
	);

	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'mysql_timestamp' => false,
		),
		'Orm\Observer_UpdatedAt' => array(
			'events' => array('before_save'),
			'mysql_timestamp' => false,
		),
		'Orm\\Observer_Self' => array(
			'events' => array('before_save')
		),
		'Orm\\Observer_Typing',
	);
    
    public function logic_delete($current_user) {
        $this->deleted    = true;
        $this->deleted_at = time();
        $this->deleted_by = $current_user->id;
        $this->order      = null;
    }
    
    public function favorite_locations_by_single_user($user){
        
        
        foreach ($this->locations as $location){

            if ($location->status != Model_Location::STATUS_ACTIVE) return false;

            $user->favorite_location($location);

        }
        
        return true;
    }
    
    public function favorite_locations_by_users(){

        
        
        foreach ($this->users as $user){
            
            $this->favorite_locations_by_single_user($user);
            
        }
        
        return true;
    }
        
}
