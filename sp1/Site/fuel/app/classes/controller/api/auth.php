<?php

use Fuel\Core\Config;

/**
 * The auth Controller.
 * This controller deals out an api key to the requesting app
 *
 * @package  app
 * @extends  Controller_Rest
 */
class Controller_Api_Auth extends Controller_Api {

	public function action_delete() {
		if (Input::method() != 'DELETE') { $this->response($this->no_access); return; }

		$auth = Auth::instance('Shopsuey_Stateless');
		$auth->logout();

		$data = array(
			'data' => array('logout' => TRUE),
			'meta' => array('status' => 1, 'error' => '')
		);

		$this->response($data);
	}

	public function action_post() {
		if (Input::method() != 'POST') { $this->response($this->no_access); return; }
		
		$token = Input::post('app_id');

		$error_meta = null;

		$application = Model_Application::query()->where('token', $token)->get_one();

		if ($application) {
			$email = Input::post('email', '');
			$password = Input::post('password', '');

			// Validate user name and password
			if (!empty($email) && !empty($password)) {
				$auth = Auth::instance('Shopsuey_Stateless');
				if ($auth->login($email, $password)) {
					// TODO: Build response
					$user_login = $auth->get_user_login_object();
					$data = array(
							'data' => Helper_Api::login_response($user_login),
							'meta' => array('status' => 1, 'error' => '')
					);
				} else {
					$error_meta = Helper_Api::build_error_meta(Code::ERROR_INVALID_EMAIL_OR_PASSWORD, array('input' => Input::param()));
				}
			} else {
				$error_meta = Helper_Api::build_error_meta(Code::ERROR_EMAIL_AND_PASSWORD_REQUIRED, array('input' => Input::param()));
			}

		}	else {
			$error_meta = Helper_Api::build_error_meta(Code::ERROR_INVALID_APP_ID);
		}

		if ($error_meta) {
			$data = array('data' => NULL, 'meta' => $error_meta);
		}

		$this->response($data);
	}
	
	public function action_force_upgrade() {
	    if (Input::method() != 'GET') { $this->response($this->no_access); return; }
	    
	    $data = array(
	        'upgrade' => false,
	        'meta' => array('status' => 1, 'error' => '')
	    );
	    
	    $version = explode('.', Input::get('version', ''));
	    $current_version = explode('.', Config::get('version'));
	    
	    if(!empty($version) && count($version) == count($current_version)
	        && $version < $current_version) {
	        $data['upgrade'] = true;
	    }
	    
        // the correct way to send data, inside an array called 'data' 
        // the 'upgrade' key in $data should eventually be removed
        $data['data'] = array('upgrade' => $data['upgrade']);
	    $this->response($data);
	}
}
