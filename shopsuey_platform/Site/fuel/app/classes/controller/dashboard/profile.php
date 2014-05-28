<?php

/**
 * The User Controller.
 * This controllers the CRUD proceedures for the CMS users section
 *
 * @package  app
 * @extends  Controller_Cms
 */

class Controller_Dashboard_profile extends Controller_Cms {
	private $test = true;

	public function action_edit() {
        $id = $this->user_login->user->id;
        if (!$id || !($user = CMS::user($id))) {
            return $this->error_404();
        }
		
		if (Input::post()) {
            return $this->user_update($user);
		} else {
			$content_data = $this->_build_content_data($this->user_login->user, $user);
			$content = View::forge('cms/user/edit', $content_data);
		}

		return $this->form_edit($content, $user);
	}

    public function action_billing() {
        // Include .js
		$apnd = array('files/base.js', 'files/users.js');
		$excl = array('autotab', 'cleditor', 'dualist', 'elfinder', 'fullcalendar', 'flot', 'maskedinput', 'tagsinput', 'upload');
		$scripts = CMS::scripts($apnd, NULL, $excl);

		\Package::load('braintree');

		$user = $this->user_login->user;
        $error = NULL;
        $subscription = NULL;
        $customer = NULL;
        
		if ($user->payment) {
    		try {
    			$subscription = \Braintree\Api::get_subscription($user->payment->subscription_id);
    			$customer = \Braintree\Api::get_customer($user->payment->customer_id);
    		} catch (\Braintree\Exception $e) {
    			$code = $e->getCode();
    			if ($code == \Braintree\Api::ERROR_SUBSCRIPTION_GET) {
    				$msg = 'Error while retrieving the subscription information';
    			} elseif ($code == \Braintree\Api::ERROR_CUSTOMER_GET) {
    				$msg = 'Error while retrieving the customer information';
    			} else {
    				$msg = 'An error occurred during retrieval of billing info';
    			}
				$error = array('type' => 'fail', 'message' => $msg);
    		}
		} else {
		    $error = array('type' => 'warning', 'message' => 'The user does not have an active subscription');
		}
		
		if (! is_null($error)) {
		    $this->msg = $error;
		}

        $content_data = array(
            'customer' => is_null($customer) ? NULL : $customer->info,
            'credit_card' => !is_null($customer) && count($customer->info->creditCards) > 0 ? $customer->info->creditCards[0] : NULL,
            'subscription' => is_null($subscription) ? NULL : $subscription->info,
            'user' => $user,
            'transaction_statuses' => \Braintree\Api::get_transaction_statuses(),
            'transaction_types' => \Braintree\Api::get_transaction_types(),
        );
        $content = View::forge('cms/user/billing', $content_data);
        
        $header_data = array(
            'style' => array('styles.css', 'autoSuggest.css'),
            'scripts' => $scripts,
            'ie' => 'ie.css'
        );

        $wrapper_data = array(
            'page' => array(
                'name' => 'Billing Information',
                'icon' => 'icon-cart'),
            'crumbs' => array(
				array('title'=>'Dashboard', 'link' => Uri::create('dashboard')),
				array('title'=>'Billing Information', 'link' => '#'),
			),
            'message' => $this->msg,
            'me' => $this->user_login->user,
            'content' => $content,
            'company' => $this->get_current_company(),
        );

        // Compile view
        $header = View::forge('base/header', $header_data);
        $cont = View::forge('cms/wrapper', $wrapper_data);
        $footer = View::forge('base/footer');
        $page = $header . $cont . $footer;

        return Response::forge($page);
    }

    public function action_updatecc() {
        $form = Input::post();

        $user = $this->user_login->user;
        if (! $user->payment || empty($user->payment->customer_id)) {
            return Response::redirect("dashboard/profile/billing");
        }
        
        if (!$form) {
            return $this->_updatecc_form();
        } else {
            if (!CMS::verify_nonce('updatecc', $form['nonce'])) { // verify nonce
            	return $this->_updatecc_error('Request denied');
            } elseif (empty($form['address'])) {
            	return $this->_updatecc_error('Enter your billing address');
            } elseif (empty($form['city'])) {
            	return $this->_updatecc_error('Enter your billing city');
            } elseif (empty($form['state'])) {
            	return $this->_updatecc_error('Enter your billing state');
            } elseif (empty($form['zip'])) {
            	return $this->_updatecc_error('Enter your billing zip code');
            }

            \Package::load('braintree');

            $credit_card = array(
        		'number'          => $form['number'],
        		'cvv'             => $form['cvv'],
        		'expiration_date' => $form['expiration'],
            );
            $billing_address = array(
        		'address'  => $form['address'],
        		'city'     => $form['city'],
        		'state'    => $form['state'],
        		'zip_code' => $form['zip'],
            );
            
            try {
            	$customer = \Braintree\Api::update_customer($user->payment->customer_id, $user->payment->credit_card_token, $credit_card, $billing_address);
            } catch (\Braintree\Exception $e) {
            	$code = $e->getCode();
            	if ($code == \Braintree\Api::ERROR_INCOMPLETE_CREDIT_CARD_INFO) {
            		return $this->_updatecc_error('Please fill all your credit card info');
            	} elseif ($code == \Braintree\Api::ERROR_INCOMPLETE_BILLING_ADDRESS) {
            		return $this->_updatecc_error('Please fill all your billing address info');
            	} elseif ($code == \Braintree\Api::ERROR_TRANSACTION_VALIDATION) {
            		return $this->_updatecc_error('An error occurred during credit card validation: ' . $this->build_braintree_error_msg($e));
            	} else {
            		return $this->_updatecc_error('An error occurred during credit card processing: ' . $this->build_braintree_error_msg($e));
            	}
            }

            $user->payment->credit_card_token = $customer->credit_card_token;

            if ($user->save()) {
                $msg = array('type' => 'success', 'message' => 'Successfully updated credit card info', 'autohide' => true);
                Session::set('message', $msg);
                return Response::redirect("dashboard/profile/billing");
            } else {
        		return $this->_updatecc_error('An error occurred while updating credit info');
            }
        }
    }

	private function form_edit($content, $user = null) {
		// Include .js
		$apnd = array('files/base.js', 'files/users.js');
		$excl = array('autotab', 'cleditor', 'dualist', 'elfinder', 'fullcalendar', 'flot', 'maskedinput', 'tagsinput', 'upload');
		$scripts = CMS::scripts($apnd, NULL, $excl);

		// Set header params
		$header_data = array(
			'style' => array('styles.css', 'autoSuggest.css'),
			'scripts' => $scripts,
			'ie' => 'ie.css'
		);

		$wrapper_data = array(
			'page' => array(
				'name' => 'Profile Editor',
				'subnav' => View::forge('cms/user/menu'),
				'icon' => 'icon-contact'),

			'crumbs' => array(
				array('title'=>'Dashboard', 'link' => Uri::create('dashboard')),
				array('title'=>'Edit Profile', 'link' => '#'),
			),

			'me' => $this->user_login->user,
			'message' => $this->msg,
			'content' => $content
		);
		// Compile view
		$header = View::forge('base/header', $header_data);
		$cont = View::forge('cms/wrapper', $wrapper_data);
		$footer = View::forge('base/footer');
		$temp = $header . $cont . $footer;

		return Response::forge($temp);
	}

	private function user_update($user) {
		$post = Input::post();

		$data = array_merge($post, $post['meta']);
        $data['group'] = $this->user_login->user->group;
		unset($data['meta']);
		
		$url = Uri::create("api/user/{$user->id}");
        
		$this->api->setData($data);
		$this->api->setMethod('PUT');
		$this->api->setURL($url);

		$output = $this->api->execute();

		if ($output->meta->status == 1) {
			$user = $output->data->user;
			$msg = array('type' => 'success', 'message' => 'Successfully updated user '.$user->email, 'autohide' => true);
			Session::set('message', $msg);
			return Response::redirect("dashboard/profile/edit");
		} else {
			$err = $output->meta->error;
			if (!$err) { $err = 'Error: 900 - Unable to process request'; }
			$this->msg = array('type' => 'fail', 'message' => $err);
		
			// Set content params
			$post['id'] = $user->id;
			$content_data = $this->_build_content_data($this->user_login->user, $post);

			$content = View::forge('cms/user/edit', $content_data);
            
			return $this->form_edit($content, $user);
		}
	}

	private function _build_content_data($current_user, $user) {
		if (is_array($user)) {
			// TODO: Improve this
			$user = json_decode(json_encode($user));
		}

		// Set content params
		return array(
                'profile_mode' => true,
				'action' => 'edit',
				'groups' => Model_User::get_groups(Model_User::GROUP_MERCHANT, $current_user->group),
				'me' => $current_user,
				'user' => $user,
		        'login_hash' => $this->user_login->login_hash,
		);
	}
	
	private function _updatecc_form() {
	    \Config::load('braintree', true);

	    // Include .js
	    $apnd = array('files/base.js', 'files/users.js');
	    $excl = array('autotab', 'cleditor', 'dualist', 'elfinder', 'fullcalendar', 'flot', 'maskedinput', 'tagsinput', 'upload');
	    $scripts = CMS::scripts($apnd, NULL, $excl);
	    
	    $user = $this->user_login->user;

	    \Package::load('braintree');

	    $customer = NULL;
    	try {
    		$customer = \Braintree\Api::get_customer($user->payment->customer_id);
    	} catch (\Braintree\Exception $e) {
    		$code = $e->getCode();
    		if ($code == \Braintree\Api::ERROR_CUSTOMER_GET) {
    			$msg = 'Error while retrieving the customer information';
    		} else {
    			$msg = 'An error occurred during retrieval of billing info';
    		}
    		$this->msg = array('type' => 'fail', 'message' => $msg);
    	}

	    $content_data = array(
    		'client_side_encryption_key' => \Config::get('braintree.client_side_encryption_key'),
    		'client_side_library_url' => \Config::get('braintree.client_side_library_url'),
	        'credit_card' => !is_null($customer) && count($customer->info->creditCards) > 0 ? $customer->info->creditCards[0] : NULL,
	    );
	    $content = View::forge('cms/user/updatecc', $content_data);
	    
	    $header_data = array(
    		'style' => array('styles.css', 'autoSuggest.css'),
    		'scripts' => $scripts,
    		'ie' => 'ie.css'
	    );
	    
	    $wrapper_data = array(
    		'page' => array(
				'name' => 'Update Credit Card',
				'icon' => 'icon-card'),
    
    		'crumbs' => array(
				array('title'=>'Dashboard', 'link' => Uri::create('dashboard')),
				array('title'=>'Billing Information', 'link' => Uri::create('dashboard/profile/billing')),
				array('title'=>'Update Credit Card', 'link' => '#')
    		),
    
            'message' => $this->msg,
            'me' => $this->user_login->user,
    		'content' => $content,
    		'company' => $this->get_current_company(),
	    );
	    
	    // Compile view
	    $header = View::forge('base/header', $header_data);
	    $cont = View::forge('cms/wrapper', $wrapper_data);
	    $footer = View::forge('base/footer');
	    $page = $header . $cont . $footer;
	    
	    return Response::forge($page);
	}
	
	private function _updatecc_error($msg) {
		$this->msg = array('type' => 'fail', 'message' => $msg);
		return $this->_updatecc_form();
	}
	
	private function build_braintree_error_msg(\Braintree\Exception $e) {
		$errors_object = $e->getErrorsObject();
		$msg = '';
		if (! is_null($errors_object) && isset($errors_object->messages)) {
			$msg = implode(' ', $errors_object->messages);
		}
		return $msg;
	}
}
