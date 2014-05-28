<?php

/**
 * The Log In Controller.
 * This controller manages log in access
 * 
 * @package  app
 * @extends  Controller
 */
class Controller_Login extends Controller_Cms {
	
	/**
	 * Output default log in form
	 */
	public function action_invalid() {
		$apnd = array('files/login.js');
		$incl = Config::get('cms.min_scripts');
		$scripts = CMS::scripts($apnd, $incl);
		
		$header_data = array(
		    'style' => array('reset.css', 'newLogin.css'),
			'scripts' => $scripts,
			'ie' => 'ie.css'
		);
		
		$welcome_data = array(
			'user' => Session::get('user'),
			'notice' => $this->msg
		);
		
		$header = View::forge('base/header', $header_data);
		$cont = View::forge('welcome/index', $welcome_data);
		$footer = View::forge('base/footer');
		$temp = $header . $cont . $footer;
		
		return Response::forge($temp);
	}
	 
	/**
	 * Log In
	 */
	public function action_index() {

		$auth = Auth::instance('Shopsuey_Session');

		$form = Input::post();
		
		if ($form) {
			if (!$form['u'] && !$this->msg) { // valid email
				$this->msg = array('message' => 'Email address required', 'type' => 'error', 'form' => $form);
			}
			elseif (!$form['p'] && !$this->msg) { // valid password
				$this->msg = array('message' => 'Password required', 'type' => 'error', 'form' => $form);	
			}
			
			elseif (!CMS::verify_nonce('login', $form['nonce']) || Input::method() != 'POST' && !$this->msg) { // validate nonce
				$this->msg = array('message' => 'Request Denied', 'type' => 'error', 'form' => $form);
			}
			
			if ($this->msg) { return $this->action_invalid(); }

			$auth->set_app_id(Config::get('cms.appid'));

			if (! $auth->login($form['u'], $form['p'])) {
				$this->msg = array('message' => 'Invalid email or password', 'type' => 'error', 'form' => $form);
				return $this->action_invalid();
			} else {
				$user_login = $auth->get_user_login_object();

				if (!$user_login->user->is_new_user() && $user_login->user->is_regular_user()) {
				    $user = $user_login->user;
					// Display appropriate informative message
					Session::set('message', array('message' => 'In order to access this area you must first setup your business', 'type' => 'error', 'field' => null));
					// Redirect to signup page
					return Response::redirect('setup/profile/signup');
				}

				$remember = isset($form['remember']) ? TRUE : FALSE;

				$user_login->user->set_meta_field_value('remember', $remember);
				$user_login->user->save();

				if (! $user_login->user->is_admin()) {
    				// MixPanel: Track event
    				\Package::load('mixpanel');
    				\Mixpanel\Api::track_event(Event_Mixpanel::EVENT_LOGIN, $user_login->user->id);
				}

				$reset = $user_login->user->get_meta_field_value('reset');
				if ($reset && $reset->value) { return Response::redirect('login/update/'); }
				else { return Response::redirect($this->landing); }
			}
		} elseif ($auth->user_logged_in()) { 
			$user_login = $auth->get_user_login_object();
			$remember = $user_login->user->get_meta_field_value('remember');
			if ($remember) { return Response::redirect($this->landing); }
			else { return $this->action_invalid(); }
		} else {
			return $this->action_invalid();
		}
		
	}
	
	/**
	 * Forgot 
	 */
	public function action_forgot() {
		$form = Input::post();
		
		if (!$form) { // show form only
			$this->msg = array('message' => 'Enter your email address');
		}
		else { // process reset
			$nonce = $form['nonce'];
			if (CMS::verify_nonce('forgot', $nonce)) { 
				if (!$form['u']) { $this->msg = array('message' => 'Email address required', 'type' => 'fail'); }
				else { 
					$url = Uri::create('api/user/forgot');
					$this->api->setData(array('email' => $form['u']));
					$this->api->setURL($url);
					$this->api->setMethod('POST');
					
					$output = $this->api->execute();
					error_log(print_r($output, true));
					if ($output && $output->meta->status == 1) { 
						Session::destroy();
						$this->msg = array('type' => 'success', 'message' => 'You will receive an email at '.$form['u'].' with directions on how to reset your password.'); 
					}
					else { 
						$this->msg = array('type' => 'fail', 'message' => 'Error: 900 - Unable to process your request'); 
					}
				}
			}
			else { $this->msg = array('message' => 'Request Denied', 'type' => 'fail'); }
		}
		
		$apnd = array('files/login.js');
		$incl = Config::get('cms.min_scripts');
		$scripts = CMS::scripts($apnd, $incl);
		
		$header_data = array(
			'style' => 'styles.css',
			'scripts' => $scripts,
			'ie' => 'ie.css'
		);
		
		$welcome_data = array(
			'user' => Session::get('user'),
			'notice' => $this->msg
		);
		
		$header = View::forge('base/header', $header_data);
		$cont = View::forge('welcome/forgot', $welcome_data);
		$footer = View::forge('base/footer');
		$temp = $header . $cont . $footer;
		
		return Response::forge($temp);
	}
	
	/**
	 * Reset password
	 */
	public function action_reset() {
	    $controller = 'welcome/reset';
	    if (Input::method() == 'POST') {        
	        $new_password = Input::post('new_password', '');
	        $new_password_confirm = Input::post('new_password_confirm', '');
	        $hash = Input::post('hash', '');
	        
	        if ($new_password != '' && $new_password_confirm != '') {
	            if ($new_password == $new_password_confirm) {
	                $hash = Input::post('hash');
	                $user_reset = Model_User_Reset::query()->where('hash', $hash)->where('used', FALSE)->where('expiracy', '>', date('Y-m-d H:i:s'))->get_one();
	                
	                $auth = Auth::instance('Shopsuey_Stateless');
	                $user = $user_reset->user;
	                $user->password = $auth->hash_password($new_password);
	                $user_reset->used = 1;
	                 
	                if ($user_reset->save()) {
	                    $email_data = array('password' => $new_password, 'username' => $user->email, 'email' => $user->email);
	                    $data = (array) CMS::email($user->email, null, 'ShopSuey :: Password Reset', $email_data, 'email/reset');
	                    unset($data['data']['password']);
	                    
	                    $this->msg = array('type' => 'success', 'message' => 'Password Reset! You will receive an email with your new password.');
	                    
                        //GROUP_USER o GROUP_ANONYMOUS
	                    if (in_array($user->group, array(Model_User::GROUP_ANONYMOUS, Model_User::GROUP_USER))) {
                            $controller = 'welcome/successfulresetwithoutlogin';
                        } else {
                            $controller = 'welcome/index';
                        }
	                }
	            } else {
	                $this->msg = array('message' => 'Password confirmation is different than password.', 'type' => 'fail');
	            }
	        } else {
	            $this->msg = array('message' => 'You should enter password and confirm fields.', 'type' => 'fail');
	        }
	        
	    } else if (Input::method() == 'GET') {
	        $hash = Input::get('hash');
	        
	        $user_reset = Model_User_Reset::query()->where('hash', $hash)->where('used', FALSE)->where('expiracy', '>', date('Y-m-d H:i:s'))->get_one();
	        
	        if (!$user_reset) { // show form only
	            $this->msg = array('message' => 'Request Denied', 'type' => 'fail');

	            $controller = 'welcome/index';
	        }
	    }
	    
	    $apnd = array('files/login.js');
	    $incl = Config::get('cms.min_scripts');
	    $scripts = CMS::scripts($apnd, $incl);
	    
	    $header_data = array(
	        'style' => 'styles.css',
	        'scripts' => $scripts,
	        'ie' => 'ie.css'
	    );
	    
	    $welcome_data = array(
	        'hash' => $hash,
	        'user' => Session::get('user'),
	        'notice' => $this->msg
	    );

		$header = View::forge('base/header', $header_data);
		$cont = View::forge($controller, $welcome_data);
		$footer = View::forge('base/footer');
		$temp = $header . $cont . $footer;
		
		return Response::forge($temp);
	}
	
	/**
	 * Update password 
	 */
	public function action_update() {
		$user = (array) CMS::current_user();
		if (!$user) { 
			Session::set('message', array('type' => 'warning', 'message' => 'You must be logged in to change your password.'));
			return Response::redirect('login/'); 
		}
		
		$form = Input::post();
		
		if ($form) {
			$nonce = $form['nonce'];
			if (!CMS::verify_nonce('update', $nonce)) { // verify nonce
				Session::set('message', array('message' => 'Request Denied', 'type' => 'fail'));
				return Response::redirect('login/update/');	
			}
			elseif (!$form['old_password']) { // validate old password
				Session::set('message', array('message' => 'Error: Enter old password', 'type' => 'fail'));
				return Response::redirect('login/update/');
			}
			elseif (!$form['password']) { // validate new password
				Session::set('message', array('message' => 'Error: Enter new password', 'type' => 'fail'));
				return Response::redirect('login/update/');
			}
			elseif ($form['password'] != $form['confirm']) { // validate matching passwords
				Session::set('message', array('message' => 'Error: Passwords do not match', 'type' => 'fail'));
				return Response::redirect('login/update/');
			}
			
			// Validation Passed -> process request
			$url = Uri::create('api/user/'.$user['username']);

			$this->api->setData(array('password' => $form['password'], 'old_password' => $form['old_password']));
			$this->api->setMethod('PUT');
			$this->api->setURL($url);
			
			$output = $this->api->execute();
			
			if ($output) { 
				if ($output->meta->status != 1) { $this->msg = array('type' => 'fail', 'message' => $output->meta->error); }
				else {
					CMS::delete_user_meta($user['id'], 'reset');
					Session::set('message', array('type' => 'success', 'message' => 'Your password has been changed.'));
					return Response::redirect($this->landing);
				}
			}
			else { 
				$this->msg = array('type' => 'fail', 'message' => 'Error: 900 - Unable to process your request', 'form' => $form);
			}
			
		}
			
		$apnd = array('files/login.js');
		$incl = Config::get('cms.min_scripts');
		$scripts = CMS::scripts($apnd, $incl);
		
		$header_data = array(
			'style' => 'styles.css',
			'scripts' => $scripts,
			'ie' => 'ie.css'
		);
		
		$welcome_data = array(
			'user' => Session::get('user'),
			'notice' => $this->msg
		);
		
		$header = View::forge('base/header', $header_data);
		$cont = View::forge('welcome/setpassword', $welcome_data);
		$footer = View::forge('base/footer');
		$temp = $header . $cont . $footer;
		
		return Response::forge($temp);		
	}
	
	/**
	 * Output and process create account form
	 */
	public function action_create() {
	    $user = $this->user_login->user;
	    if (! ($user->is_guest() || $user->is_regular_user())) {
	        Session::set('message', array('type' => 'warning', 'message' => 'You can\'t create an account while logged in.'));
	        return Response::redirect('login');
	    }
	    
	    $form = Input::post();
	
	    if (!$form) {
	        $session_form = Session::get('form');
	        if (! $session_form || ($user->is_regular_user() && preg_match('/\/login(\/)?$/', Input::referrer()))) {
	            Session::delete('form', $form);
    	        // To prefill form
    	        $session_form = array(
    	        	'name_of_business' => $user->get_meta_field_value('name_of_business'),
    	        	'real_name'        => $user->get_meta_field_value('real_name'),
    	        	'role'             => $user->get_meta_field_value('role'),
    	        	'email'            => $user->email,
    	        );
	        }
	        
	        $apnd = array('files/login.js');
	        $incl = Config::get('cms.min_scripts');
	        $scripts = CMS::scripts($apnd, $incl);
	        
    	    $header_data = array(
    	        'style' => array('reset.css', 'newLogin.css'),
    	        'scripts' => $scripts,
    	        'ie' => 'ie.css'
    	    );
    	
    	    $welcome_data = array(
    	        'notice' => $this->msg,
    	        'form' => $session_form,
    	        'existing_user' => ! $user->is_guest(),
    	    );
    	
    	    $header = View::forge('base/header', $header_data);
    	    $cont = View::forge('welcome/create', $welcome_data);
    	    $footer = View::forge('base/footer');
    	    $temp = $header . $cont . $footer;
    	
    	    return Response::forge($temp);
	    } else {
	        Session::set('form', $form);
			if (!CMS::verify_nonce('create', $form['nonce'])) { // verify nonce
				Session::set('message', array('message' => 'Request Denied', 'type' => 'fail', 'field' => null));
				return Response::redirect('login/create/');	
			} elseif ($user->is_guest() && !$form['password']) { // validate new password
				Session::set('message', array('message' => 'Enter password', 'type' => 'fail', 'field' => 'password'));
				return Response::redirect('login/create/');
			} elseif (!$form['real_name']) { // validate new password
			    Session::set('message', array('message' => 'Enter your name', 'type' => 'fail', 'field' => 'real_name'));
			    return Response::redirect('login/create/');
			} elseif (!$form['name_of_business']) { // validate new password
		        Session::set('message', array('message' => 'Enter name of business', 'type' => 'fail', 'field' => 'name_of_business'));
		        return Response::redirect('login/create/');
			} elseif (!$form['email']) { // validate new password
	            Session::set('message', array('message' => 'Enter your email', 'type' => 'fail', 'field' => 'email'));
	            return Response::redirect('login/create/');
            } elseif (!$form['role']) { // validate new password
                Session::set('message', array('message' => 'Enter your position', 'type' => 'fail', 'field' => 'role'));
                return Response::redirect('login/create/');
			} elseif ($user->is_guest() && $form['password'] != $form['confirmPassword']) { // validate matching passwords
				Session::set('message', array('message' => 'Passwords do not match', 'type' => 'fail', 'field' => 'confirmPassword'));
				return Response::redirect('login/create/');
			} elseif (!isset($form['terms'])) {
				Session::set('message', array('message' => 'You should accept the Terms of Service', 'type' => 'fail', 'field' => 'terms'));
				return Response::redirect('login/create/');
			}
			
			$form['status'] = Model_User::STATUS_STEP1;
			
			// Validation Passed -> process request
			if ($user->is_guest()) {
			    $url = Uri::create("api/user");
			    $this->api->setMethod('POST');
			} else {
			    $url = Uri::create("api/user/{$user->id}");
			    $this->api->setMethod('PUT');
			}

			$this->api->setData($form);
			$this->api->setURL($url);
			
			$output = $this->api->execute();
			
			if ($output) { 
				if ($output->meta->status != 1) { 
				    Session::set('message', array('type' => 'fail', 'message' => $output->meta->error, 'field' => null));
				    return Response::redirect('login/create/');
				} else {
				    $auth = Auth::instance('Shopsuey_Session');
				    $auth->set_app_id(Config::get('cms.appid'));
				    
				    if ($user->is_guest() && !$auth->login($form['email'], $form['password'])) {
				        $this->msg = array('message' => 'Invalid email or password', 'type' => 'error', 'form' => $form);
				        return $this->action_invalid();
				    }
					
                    // MixPanel: Register user profile and track event
                    \Package::load('mixpanel');

                    $user = $output->data->user;

                    $profile_info = array(
                        'email'    => $user->email,
                        'username' => $user->meta->real_name,
                        'company'  => $user->meta->name_of_business,
                        'role'     => $user->meta->role,
                    );

                    \Mixpanel\Api::set_profile($user->id, $profile_info);

                    \Mixpanel\Api::track_event(Event_Mixpanel::EVENT_CREATE_ACCOUNT, $user->id);

                    Session::delete('form', $form);
                    
				    Response::redirect('setup/profile/businesses');
				}
			}
			else { 
				$this->msg = array('type' => 'fail', 'message' => 'Error: 900 - Unable to process your request', 'form' => $form);
				Response::redirect('login/create');
			}
	    }
	}

}