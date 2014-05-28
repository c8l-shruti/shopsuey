<?php

use Fuel\Core\Config;

class Helper_Timezone {
    
    private static $timezones;
    
    /**
     * Return true if the given timezone name exists on the config array.
     * 
     * @param string $timezone_name
     * @return boolean
     */
    public static function valid_timezone($timezone_name) {
        self::_load_timezones();
        
        foreach (self::$timezones as $timezone) {
            if ($timezone['tz'] == $timezone_name) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Return a list of the available timezone names
     * 
     * @return array
     */
    public static function get_timezone_list($only_active_countries = false) {
        self::_load_timezones();
        
        if ($only_active_countries) {
            $active_countries = Config::get('timezone.active_countries');
            
            $active_timezones = array();
            foreach (self::$timezones as $timezone) {
                if (in_array($timezone['iso'], $active_countries)) {
                    $active_timezones[] = $timezone['tz'];
                }
            }
            
            return $active_timezones;
        }
        
        return array_keys(self::$timezones);
    }
    
    /**
     * Return the details of the given timezone. If the timezone is not valid,
     * it will throw an exception.
     * 
     * @param  string $timezone_name
     * @return array
     * @throws Exception
     */
    public static function get_timezone_details($timezone_name) {
        if (self::valid_timezone($timezone_name)) {
            return self::$timezones[$timezone_name];
        }
        
        throw new Exception('Invalid timezone name');
    }
    
    private static function _load_timezones() {
        if (is_null(self::$timezones)) {
            $timezones = Config::get('timezone.list');
            
            self::$timezones = array();
            foreach ($timezones as $timezone) {
                self::$timezones[$timezone['tz']] = $timezone; 
            }
        }
    }
}
