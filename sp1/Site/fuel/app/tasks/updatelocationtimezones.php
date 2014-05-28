<?php

namespace Fuel\Tasks;

class Updatelocationtimezones
{
	public static function run($args = null) {
        self::log("Processing locations");
        
        $locations = \Model_Location::find('all');
        
        foreach ($locations as $location) {
            self::log("Processing location " . $location->id);
            
            $state = strtolower($location->st);
            
            if (in_array($state, array('ha', 'hw'))) {
                $location->st = 'HI';
                $state = 'hi';
            }
            
            $location->timezone = self::get_timezone_by_state($state);
            
            $location->save();
            
        }
        
    }
    
    private static function get_timezone_by_state($state) {
        $state_timezone = array(
            'ny' => 'America/New_York', 
            'hi' => 'Pacific/Honolulu', 
            'ca' => 'America/Los_Angeles', 
            'az' => 'America/Phoenix', 
            'in' => 'America/Indiana/Indianapolis'
        );
        
        return (isset($state_timezone[$state])) ? $state_timezone[$state] : '';  
    }
    
    protected static function log($message) {
        echo "[ " . date("Y-m-d H:i:s") . "] " . $message . "\n";
        ob_flush();
    }
}
