<?php

/**
 * The User Preferences API Controller.
 *
 * @package  app
 * @extends  Controller_Api
 */

class Controller_Api_Preferences extends Controller_Api {

    public function action_get() {
		if (Input::method() != 'GET') { 
            $this->response($this->no_access); 
            return; 
        }

        $user_data = $this->user;
        $id = $user_data['id'];
        $user = Model_User::find($id);
        
        $data = array();
        if ($user->group == 2 && is_null($user->preferences)) {
            // anonymous user, we have to return default values (all false)
            foreach ($this->get_preferences_keys() as $key) {
                $data[$key] = false;
            }
        } else if (is_null($user->preferences)) {
            // non anonymous user has not set preferences, then we have to return
            // some default values (all true)
            foreach ($this->get_preferences_keys() as $key) {
                $data[$key] = true;
            }
        } else {
            $preferences = $user->preferences;
            $data = $this->preferences_to_response_array($preferences);
        }
        
		$this->response($data);
	} // action_get -> get a user's preferences
    
    public function action_post() {
        if (Input::method() != 'POST') { 
            $this->response($this->no_access); 
            return; 
        }
        
        $user_data = $this->user;
        $id = $user_data['id'];
        $user = Model_User::find($id);
        
        if (is_null($user->preferences)) {
            $user->preferences = new Model_Userpreferences();
        }
        
        foreach ($this->get_preferences_keys() as $key) {
            if (is_null(Input::param($key))) {
                $this->_error_response(Code::ERROR_MISSING_SETTING, array('missing_param' => $key));
                return;
            }
            $user->preferences->$key = (bool)Input::param($key);
        }
        
        $user->save();
        $output = array('data' => $this->preferences_to_response_array($user->preferences), 'meta' => array('status' => 1));
        $this->response($output);
    } // action_post -> set a user's preferences
    
    protected function preferences_to_response_array($preferences) {
        $keys = $this->get_preferences_keys();
        
        $return = array();
        foreach ($keys as $key) {
            $return[$key] = (bool)$preferences->$key;
        }
        return $return;
    }
    
    protected function get_preferences_keys() {
        return array(
            'deal_alerts', 'event_alerts', 'meeting_place_alerts',
            'rsvps', 'event_reminders', 'allow_friends_to_see_me',
            'allow_friends_to_see_my_location'
        );
    }
}