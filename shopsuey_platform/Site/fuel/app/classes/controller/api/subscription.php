<?php


class Controller_Api_Subscription extends Controller_Api {

	public function action_add() {
        if (Input::method() != 'POST') { $this->response($this->no_access); return; }
        
		$id = Input::post('location_id', '');
        $location = Model_Location::find($id);
        
        if (!$id || !$location) {
            return $this->_error_response(Code::ERROR_INVALID_SUBSCRIPTION_LOCATION_ID);
        }
        
        if ($this->user_login->user->has_subscribed($id)) {
            return $this->_error_response(Code::ERROR_ALREADY_SUBSCRIBED);
        }
        
        $subscription = new Model_Subscription();
        $subscription->user_id = $this->user_login->user->id;
        $subscription->location_id = $id;
        $subscription->save();

		$meta = array('error' => null, 'status' => 1);
        $data = array('data' => array(), 'meta' => $meta);
        $this->response($data);
	} // ---> action_post()

}
