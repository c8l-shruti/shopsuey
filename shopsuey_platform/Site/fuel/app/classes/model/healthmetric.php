<?php

class Model_Healthmetric extends \Orm\Model
{
    const HISTORIC_MAX_DAYS = 90;
    
    protected static $_table_name = 'health_metrics';
    
    protected static $_belongs_to = array('location');

	protected static $_properties = array(
			'id',
	        'location_id',
			'favorites_count',
		    'offers_count',
		    'events_count',
		    'sign_ups_count',
		    'check_ins_count',
            'check_ins_via_ss_count',
		    'likes_count',
            'likes_via_ss_count',
		    'follows_count',
		    'redemptions_count',
		    'rsvps_count',
	        'created_at',
			'updated_at'
	);

	protected static $_observers = array(
			'Orm\Observer_CreatedAt' => array(
					'events' => array('before_insert'),
					'mysql_timestamp' => false,
			),
			'Orm\Observer_UpdatedAt' => array(
					'events' => array('before_save'),
					'mysql_timestamp' => false,
			)
	);
}
