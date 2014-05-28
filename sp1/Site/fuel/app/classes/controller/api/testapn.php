<?php

class Controller_Api_Testapn extends Controller_Api {
	
	public function action_send() {
        if (Input::method() != 'POST') { $this->response($this->no_access); return; }
        
        $current_user = $this->user_login->user;
        $token = $current_user->apn_token;
        
        if (!$token) {
            $this->_error_response(Code::ERROR_INVALID_APN_TOKEN);
            return;
        }
        
        $text = "Test APN! The current time is " . date("H:i:s");
        
        try {
            $type = Input::post('type', 'unspecified');
            $result = Helper_Apn::send_notification($current_user, $text, array('type' => $type));
            $this->response(array(
            'data' => array(
                'result' => $result
            )));	
        } catch (Exception $e) {
            $this->response(array(
                'data' => array(
                    'result' => false,
                    'reason' => $e->getMessage()
                )
            ));	
        }
			
	}
}