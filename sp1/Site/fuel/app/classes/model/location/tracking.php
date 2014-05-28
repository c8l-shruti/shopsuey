<?php

class Model_Location_Tracking extends \Orm\Model {
    
    protected static $_belongs_to = array(
        'user',
    );
    
    protected static $_properties = array(
        'id',
        'user_id',
		'created_at',
        'latitude',
        'longitude',
        'accuracy',
	);
    
    protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'mysql_timestamp' => false,
		),
	);
    
    public static function get_last_location_tracking($user) {
        $last_tracking = Model_Location_Tracking::query()
                ->where('user_id', $user->id)
                ->limit(1)
                ->order_by('created_at', 'DESC')
                ->get_one();
        
        return $last_tracking;
    }
        
    public static function get_nearby_users($upper_left_point, $lower_right_point, $accuracy, $from_time, $to_time) {
    	$location_trackings_table = DB::table_prefix('location_trackings');
    	$user_metafields_1_table = DB::table_prefix('um1');
    	$user_metafields_2_table = DB::table_prefix('um2');

	    $long_range = array($lower_right_point->longitude, $upper_left_point->longitude);
	    sort($long_range);
		$lat_range = array($lower_right_point->latitude, $upper_left_point->latitude);
		sort($lat_range);

    	$location_trackings_result = DB::select(
    	        'location_trackings.user_id', 'latitude', 'longitude', 'location_trackings.created_at',
//     	        DB::expr("MAX($location_trackings_table.created_at) AS created_at"),
    	        DB::expr("IFNULL($user_metafields_1_table.value, 'other') AS gender"),
    	        DB::expr("$user_metafields_2_table.value AS zipcode")
    	    )
    	    ->from('location_trackings')
    	    ->join(array('user_metafields', 'um1'), 'LEFT')
    	    ->on('location_trackings.user_id', '=', 'um1.user_id')
    	    ->on('um1.key', '=', DB::expr('"gender"'))
    	    ->join(array('user_metafields', 'um2'), 'LEFT')
    	    ->on('location_trackings.user_id', '=', 'um2.user_id')
    	    ->on('um2.key', '=', DB::expr('"zipcode"'))
    	    ->where('longitude', 'between', $long_range)
        	->where('latitude', 'between', $lat_range)
        	->where('location_trackings.created_at', 'between', array($from_time, $to_time))
        	->and_where_open()
        	->or_where('accuracy', '<=', $accuracy)
        	->or_where('accuracy', NULL)
        	->and_where_close()
//         	->group_by('location_trackings.user_id')
        	->execute();
    	
    	$location_trackings = array();
    	foreach($location_trackings_result->as_array() as $location_tracking) {
    	    $user_id = $location_tracking['user_id'];
    	    if (!isset($location_trackings[$user_id]) || $location_tracking['created_at'] > $location_trackings[$user_id]['created_at']) {
    	        $location_trackings[$user_id] = $location_tracking;
    	    }
    	}

    	return array_values($location_trackings);
    }
}

