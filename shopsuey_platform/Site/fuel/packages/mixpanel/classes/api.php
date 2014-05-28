<?php

/**
 * Wrapper class for the Mixpanel PHP Library
 *
 */
namespace Mixpanel;

class Api {
    
    private static $_reserved_properties = array('email', 'first_name', 'last_name', 'username');

    private static $_project_token;
    private static $_debug;
    private static $_instance;

    final private function __construct() {}
    
    private static function _init()
    {
        if (is_null(static::$_instance)) {
        	\Config::load('mixpanel', true);
        
        	static::$_project_token = \Config::get('mixpanel.project_token', '');
        	static::$_debug = \Config::get('mixpanel.debug', FALSE);

        	static::$_instance = \Mixpanel::getInstance(static::$_project_token, array(
    	        'debug' => static::$_debug,
	        ));
        }
    }
    
    /**
     * Set/Update profile for a user with the given id. Takes an array of properties
     * 
     * @param string|int $user_id
     * @param array $reserved_properties
     */
    public static function set_profile($user_id, $profile_properties = array()) {
        static::_init();
        
        $properties = array();
        
        foreach ($profile_properties as $key => $value) {
            if (in_array($key, static::$_reserved_properties)) {
                $key = '$' . $key;
            }
            $properties[$key] = $value;
        }
        
        static::$_instance->people->set($user_id, $properties);
    }
    
    /**
     * Tracks an event. Takes optional user_id to identify users and an array of additional properties
     * for the event 
     * 
     * @param string $event
     * @param string|int $user_id
     * @param array $additional_properties
     */
    public static function track_event($event, $user_id = NULL, $additional_properties = array()) {
        static::_init();
        
        if (!is_null($user_id)) {
            static::$_instance->identify($user_id);
        }
        
        static::$_instance->track($event, $additional_properties);
    }
}
