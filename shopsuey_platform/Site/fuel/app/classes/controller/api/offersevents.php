<?php

/**
 * The Api Controller.
 * This controllers the CRUD proceedures for offers
 *
 * @package  app
 * @extends  Controller_Rest
 */
class Controller_Api_Offersevents extends Controller_Api {

    public function action_search() {

        $respOffers = Controller_Api_Offer::listStuff(Input::get(), $this->user_login->user);
        $respEvents = Controller_Api_Event::listStuff(Input::get(), $this->user_login->user);
                
        $data = array('data' => array('offers' => $respOffers["all_offers"], 'events' => $respEvents["events_array"]));
        
        $this->response($data);
    }

    public function action_count() {
        
        $offersCount = Controller_Api_Offer::countStuff(Input::get(), $this->user_login->user);
        $eventsCount = Controller_Api_Event::countStuff(Input::get(), $this->user_login->user);
                
        $data = array('data' => array('offers' => $offersCount["count"], 'events' => $eventsCount["count"]));
        
        $this->response($data);
        
    }
    
}
