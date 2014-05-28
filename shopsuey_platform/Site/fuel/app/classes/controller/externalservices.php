<?php

use Fuel\Core\Input;

/**
 * @package  app
 * @extends  Controller
 */
class Controller_Externalservices extends Controller_Cms {

    
    /**
     * Returns address information using the google geodecoding api.
     */
    public function action_address_information() {
        $latitude  = Input::get('latitude', null);
        $longitude = Input::get('longitude', null);
        
        if (!is_null($latitude) && !is_null($longitude)) {
            $timestamp = time();
            $endpoint  = "https://maps.googleapis.com/maps/api/timezone/json?location=$latitude,$longitude&timestamp=$timestamp&sensor=false";
            $response  = file_get_contents($endpoint);
            
            die($response);
        } else {
            die('Missing parameter address');
        }
    }
}
