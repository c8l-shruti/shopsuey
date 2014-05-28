<?php

/**
 * The Api Controller.
 * This controllers the CRUD proceedures for interactions with social channels
 *
 * @package  app
 * @extends  Controller_Rest
 */

class Controller_Api_Socialinteraction extends Controller_Api {

    /**
     * Adds a new like to the given location and the current user
     */
    public function action_location_like() {
        if (Input::method() != 'POST') { $this->response($this->no_access); return; }
        
        $current_user = $this->user_login->user;
        
        $location_id  = $this->param('id', null);
        $location     = Model_Location::find($location_id);
                
        if (!$location) {
            return $this->_error_response(Code::ERROR_INVALID_LOCATION);
        }
        
        $location_like = Model_Location_Like::query()
                            ->where('location_id', $location_id)
                            ->where('user_id', $current_user->id)
                            ->get_one();
        
        if (!$location_like) {
            $location_like = new Model_Location_Like();
            $location_like->user     = $current_user;
            $location_like->location = $location;
            
            if ($location_like->save()) {
            
                $data = array(
                    'data' => array('success' => true),
                    'meta' => array('error' => '', 'status' => 1)
                );
                return $this->response($data);
            } else {
                return $this->_error_response(Code::ERROR_SAVING_SOCIAL_INTERACTION);
            }
            
        } else {
            return $this->_error_response(Code::ERROR_LOCATION_ALREADY_HAS_LIKE_FOR_THIS_USER);
        }
    }
    
    /**
     * Deletes an existent like for the given location and the current user
     */
    public function action_location_dislike() {
        if (Input::method() != 'DELETE') { $this->response($this->no_access); return; }
        
        $current_user = $this->user_login->user;
        
        $location_id  = $this->param('id', null);
        $location     = Model_Location::find($location_id);
        
        if (!$location) {
            return $this->_error_response(Code::ERROR_INVALID_LOCATION);
        }
        
        $location_like = Model_Location_Like::query()
                            ->where('location_id', $location_id)
                            ->where('user_id', $current_user->id)
                            ->get_one();
        
        if ($location_like) {
            if (!$location_like->delete()) {
                return $this->_error_response(Code::ERROR_SAVING_SOCIAL_INTERACTION);
            }
        }
        
        $data = array(
            'data' => array('success' => true),
            'meta' => array('error' => '', 'status' => 1)
        );
        return $this->response($data);
    }
    
    /**
     * Adds a new checkin of the current user to the given location 
     */
    public function action_location_checkin() {
        if (Input::method() != 'POST') { $this->response($this->no_access); return; }
        
        $current_user = $this->user_login->user;
        
        $location_id  = $this->param('id', null);
        $location     = Model_Location::find($location_id);
        
        if (!$location) {
            return $this->_error_response(Code::ERROR_INVALID_LOCATION);
        }
        
        $location_checkin = new Model_Location_Checkin();
        $location_checkin->user = $current_user;
        $location_checkin->location = $location;
        
        if ($location_checkin->save()) {
            
            $data = array(
                'data' => array('success' => true),
                'meta' => array('error' => '', 'status' => 1)
            );
            return $this->response($data);
        } else {
            return $this->_error_response(Code::ERROR_SAVING_SOCIAL_INTERACTION);
        }
    }
    
}