<?php

use Fuel\Core\Config;

/**
 * The session Controller.
 *
 * @package  app
 * @extends  Controller_Rest
 */
class Controller_Api_Session extends Controller_Api {
    
	public function action_start() {
		if (Input::method() != 'POST') { $this->response($this->no_access); return; }

		$current_user = $this->user_login->user;
		$new_session = new Model_User_Session();
		
		$new_session->user_id = $current_user->id;
		$new_session->start_time = time();
		
		if ($new_session->save()) { // Success
		    $data = array(
		        'data' => array('session_id' => $new_session->id),
		        'meta' => array('error' => '', 'status' => 1)
		    );
		
		    $this->response($data);
		} else {
		    $this->_error_response(Code::ERROR_SAVING_SESSION_START_TIME);
		}
	}
	
	public function action_end() {
	    if (Input::method() != 'POST') { $this->response($this->no_access); return; }
	    
	    $session_id = Input::post('session_id', '');
	    $current_user = $this->user_login->user;
	    
	    $current_session = null;
	    foreach ($current_user->sessions as $session) {
	        if ($session->id == $session_id) {
	            $current_session = $session;
	        }
	    }
	    
	    if ($current_session == null) {
            return $this->_error_response(Code::ERROR_SESSION_NOT_STARTED);
        }
        
        if ($current_session->end_time != null) {
            return $this->_error_response(Code::ERROR_SESSION_ALREADY_ENDED);
        }
        
	    $current_session->end_time = time();
	    $current_session->total_time = $current_session->end_time - $current_session->start_time;
	    
	    if ($current_session->save()) { // Success
	        $data = array(
	            'data' => array('session_id' => $current_session->id),
	            'meta' => array('error' => '', 'status' => 1)
	        );
	    
	        $this->response($data);
	    } else {
	        $this->_error_response(Code::ERROR_SAVING_SESSION_END_TIME);
	    }
	}
	
	public function action_get_total_time() {
	    if (Input::method() != 'GET') { $this->response($this->no_access); return; }
	    
	    $current_user = $this->user_login->user;
	    $sessions = $current_user->sessions;
	    
	    $total_time = 0;
	    
        foreach ($sessions as $session) {
            $total_time += $session->total_time;
        }
	    
	    $data = array(
	        'data' => array('total_time' => $total_time),
	        'meta' => array('error' => '', 'status' => 1)
	    );
	     
	    $this->response($data);
	}
}
