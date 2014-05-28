<?php


class Controller_Api_Socialnetworkrequest extends Controller_Api {

	public function action_twitter() {
        if (Input::method() != 'POST') { $this->response($this->no_access); return; }
        
		$id = Input::post('location_id', '');
        $location = Model_Location::find($id);
        
        if (!$id || !$location) {
            return $this->_error_response(Code::ERROR_INVALID_SN_REQUEST_LOCATION_ID);
        }
        
        if ($this->user_login->user->has_requested_twitter($id)) {
            return $this->_error_response(Code::ERROR_ALREADY_REQUESTED_SN);
        }
        
        $request = new Model_Twitterrequest();
        $request->user_id = $this->user_login->user->id;
        $request->location_id = $id;
        $request->save();

		$meta = array('error' => null, 'status' => 1);
        $data = array('data' => array(), 'meta' => $meta);
        $this->response($data);
	} // ---> action_twitter()

}
