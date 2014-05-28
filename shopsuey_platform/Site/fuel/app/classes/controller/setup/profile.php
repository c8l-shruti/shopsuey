<?php

/**
* The Setup Profile Controller.
* This controller manages the setup profile flow
*
* @package  app
* @extends  Controller_Cms
*/
class Controller_Setup_Profile extends Controller_Cms {

    /**
     * Setup Business Profile step #2
     */
    public function action_content() {
        $user = $this->user_login->user;
        
        if (!$user) {
        	Session::set('message', array('type' => 'warning', 'message' => 'You have to be authenticated in order to do this.'));
        	return Response::redirect('login');
        }
        
        if ($user->status != Model_User::STATUS_STEP2) {
        	Session::set('message', array('type' => 'warning', 'message' => 'The user cannot access this step'));
        	return Response::redirect('welcome/index');
        }
        
        $location_ids = explode(",", Input::get('ids', ''));
        $location_ids = array_filter($location_ids, function($element) { return preg_match('/^[0-9]+$/', $element); } );
        
        if (empty($location_ids)) {
            $user_locations = $this->user_login->user->get_assigned_companies(false);
            $location_ids = array_map(function($loc) { return $loc->id; }, $user_locations);
        }
        
        $locations = array();
        $location_with_images = null;
        
        foreach ($location_ids as $location_id) {
            $location = Model_Location::find($location_id);
            if (!$location || !$this->has_permissions_for($location)) {
                return $this->error_404();
            }
            $locations[] = $location;
            if (!$location_with_images && $location->logo && $location->landing_screen_img) {
                $location_with_images = $location;
            }
        }
        
        if (!$location_with_images) {
            $location_with_images = $locations[0];
            // let's try to find images from a marketplace instead, as a last fallback
            foreach ($locations as $location) {
                if (isset($location->mall_id) && $location->mall_id) {
                    $mall = Model_Mall::find($location->mall_id);
                    if ($mall->logo && $mall->landing_screen_img) {
                        $location_with_images->logo = $mall->logo;
                        $location_with_images->landing_screen_img = $mall->landing_screen_img;
                    }
                }
            }
        }
        
        if (empty($locations)) {
            return $this->error_404();
        }

        \Package::load('instagram');
        
        $instagram_latest_post = NULL;
        if ($user->instagram) {
        	try {
        		$feed = \Instagram\Api::get_user_recent_media($user->instagram->access_token, $user->instagram->instagram_user_id);
        		$instagram_latest_post = array_shift($feed);
        	} catch (\Instagram\Exception $e) {
        	}
        }

    	$form = Input::post();
    
    	if (!$form) {
    		// display form
    		$incl = Config::get('cms.scripts');
    		$scripts = CMS::scripts(array(), $incl);
    		 
    		$header_data = array(
				'style' => array('reset.css', 'newLogin.css', 'jquery.Jcrop.min.css', 'jquery-ui-newLogin/jquery-ui.css'),
				'scripts' => $scripts,
				'ie' => 'ie.css'
    		);

    		$welcome_data = array(
				'notice' => $this->msg,
				'location' => $location_with_images ? $location_with_images : $locations[0],
                'business_name' => $user->get_meta_field_value('name_of_business'),
		        'instagram_set' => !is_null($instagram_latest_post),
    		    'instagram_latest_post' => $instagram_latest_post,
		        'instagram_auth_url' => \Instagram\Api::get_auth_url(\Uri::create(\Uri::current())), 
    		);
    		 
    		$header = View::forge('base/header', $header_data);
    		$cont = View::forge('cms/setup/profile/content', $welcome_data);
    		$footer = View::forge('base/footer');
    		$temp = $header . $cont . $footer;
    		 
    		return Response::forge($temp);
    	} else {
    		// process form
    		$data = Input::post();
            $this->process_images($data, $location_with_images);
            $data['replace_logo_in_all_stores'] = isset($data['replace_logo_in_all_stores']) ? $data['replace_logo_in_all_stores'] : false;
            $data['replace_landing_in_all_stores'] = isset($data['replace_landing_in_all_stores']) ? $data['replace_landing_in_all_stores'] : false;
            $data['landing_instagram'] = isset($data['landing_instagram']) ? $data['landing_instagram'] : false;
            
            foreach ($locations as $location) {
                
                if (!$location->logo || $data['replace_logo_in_all_stores']) {
                    $location->logo = $data['logo'];
                }
                
                if (!$location->landing_screen_img || $data['replace_landing_in_all_stores']) {
                    if ($data['landing_instagram'] && !is_null($instagram_latest_post)) {
                        $location->use_instagram = TRUE;
                        $location->user_instagram = $user->instagram;
                    } else {
                        $location->landing_screen_img = $data['landing_screen_img'];
                    }
                }
                
                if (empty($location->categories) && count($data['categories']) <= 3) {
                    foreach ($data['categories'] as $category_id) {
                        if ($category_id) {
                            $category = Model_Category::find($category_id);
                            if ($category) {
                                $location->categories[] = $category;
                            }
                        }
                    }
                }
                
                $location->save();
            }
            
            // Change the user status
            $user->status = Model_User::STATUS_STEP3;
            
            // Save the user and redirect to following step
            $user->save();
            
            // MixPanel: Track event
            \Package::load('mixpanel');
            \Mixpanel\Api::track_event(Event_Mixpanel::EVENT_SETUP_PROFILE_CONTENT, $user->id);
            
        	return Response::redirect("setup/profile/payment");
    	}
    }
    
    /**
     * Setup Business Profile step #1
     */
    public function action_businesses() {
        $user = $this->user_login->user;
        
        if (!$user) {
        	Session::set('message', array('type' => 'warning', 'message' => 'You have to be authenticated in order to do this.'));
        	return Response::redirect('login');
        }

        if ($user->status != Model_User::STATUS_STEP1) {
        	Session::set('message', array('type' => 'warning', 'message' => 'The user cannot access this step'));
        	return Response::redirect('welcome/index');
        }
        
        $form = Input::post();
        
        if (!$form) {
        	// display form
        	$incl = Config::get('cms.scripts');
        	$scripts = CMS::scripts(array(), $incl);
        	 
        	$header_data = array(
    			'style' => array('reset.css', 'newLogin.css', 'ui_custom.css'),
    			'scripts' => $scripts,
    			'ie' => 'ie.css'
        	);
        	 
        	$businesses_data = array(
    			'notice' => $this->msg,
    			'user' => $user,
    	        'business_name' => $user->get_meta_field_value('name_of_business'),
    	        'businesses_search_url' => Uri::create('api/location/businesses_search'),
    	        'login_hash' => $this->user_login->login_hash,
        	);
        	 
        	$header = View::forge('base/header', $header_data);
        	$cont = View::forge('cms/setup/profile/businesses', $businesses_data);
        	$footer = View::forge('base/footer');
        	$temp = $header . $cont . $footer;
        	 
        	return Response::forge($temp);
        } else {
        	// process form
        	$stores_number = (int)Input::post('stores_number', 0);
        	if ($stores_number <= 0) {
        	    return $this->businesses_error_redirection('Invalid number of stores', 'stores_number');
        	}

        	$multiple_params = array();
        	$multiple_fields = array('name', 'zip_or_city', 'location', 'location_id', 'mall_id');
        	foreach($multiple_fields as $multiple_field) {
        	    $params = Input::post($multiple_field, array());
        	    if (count($params) != $stores_number) {
        	        return $this->businesses_error_redirection('One of the expected parameters is missing');
        	    }
        	    $multiple_params[$multiple_field] = $params;
        	}

        	if (Input::post('business_type', '') == 'marketplace') {
        	    $model = 'Model_Mall';
        	} else {
        	    $model = 'Model_Merchant';
        	}
        	
        	// TODO: There's no field for instagram
        	$social_info = Helper_Api::new_social_object();
        	$social_info->twitter = Input::post('twitter', '');
        	$social_info->facebook = Input::post('facebook', '');
        	
        	for($i = 0; $i < $stores_number; $i++) {
        	    $location_id = $multiple_params['location_id'][$i];
        	    if (!empty($location_id)) {
                    $location = $model::find($location_id);
                    if (! $location) {
                        return $this->businesses_error_redirection('Could not find the existing location', "store_$i");
                    }
        	    } else {
        	        $name = $multiple_params['name'][$i];
        	        $zip_or_city = $multiple_params['zip_or_city'][$i];
        	        if (empty($name) || empty($zip_or_city)) {
        	            return $this->businesses_error_redirection('The new location does not have the name or the zip code', "store_$i");
        	        }
        	        
        	        $location_info = $multiple_params['location'][$i];
        	        if ($model == 'Model_Mall' && empty($location_info)) {
        	            return $this->businesses_error_redirection('The city for the new marketplace is missing', "store_$i");
        	        }

    	            $location = new $model();
    	            $location->name = $name;
    	            if (is_numeric($zip_or_city)) {
    	                $location->zip = $zip_or_city;
    	                $location->city = '';
    	            } else {
    	                $location->city = $zip_or_city;
    	                $location->zip = '';
    	            }
    	            
    	            // Special status for pending locations of a signup flow. Must be changed
    	            // to 'active' when the user completes the payment step
    	            $location->status = $model::STATUS_SIGNUP;

//     	            $location->is_customer = 1;
    	            $location->address = '';
    	            $location->st = '';
    	            $location->country = \Model_Country::get_default();
    	            $location->contact = '';
    	            $location->email = '';
    	            $location->phone = '';
    	            $location->web = '';
    	            $location->newsletter = '';
    	            $location->tags = '';
    	            $location->content = '';
    	            $location->description = '';
    	            $location->hours = Helper_Api::new_hours_array();
    	            $location->social = Helper_Api::new_social_object();
    	            $location->logo = '';
                    if ($model == 'Model_Merchant') { 
                        $location->floor = '';
                    }
    	            $location->max_users = '0';
    	            $location->plan = '0';
    	            $location->use_instagram = FALSE;
    	            $location->timezone = '';
    	             
    	            $location->auto_generated = 0;
    	            $location->setup_complete = 0;
    	            $location->manually_updated = 0;
    	            $location->created_by = $location->edited_by = $user->id;

    	            if ($location instanceof Model_Merchant) {
    	                $mall_id = $multiple_params['mall_id'][$i];
    	                if (!empty($mall_id)) {
    	                    // Regular merchant
        	                $mall = Model_Mall::find($mall_id);
        	                if (! $mall) {
        	                	return $this->businesses_error_redirection('The marketplace for the new merchant could not be found', "store_$i");
        	                }
        	                $location->mall = $mall;
    	                } else {
    	                    // Standalone merchant
    	                    $location->mall = NULL;
    	                }
    	            } else {
    	                $location->city = $location_info;
    	            }
        	    }
        	    
        	    $error_msg = $this->check_social_info($social_info);
        	    
        	    if (!empty($error_msg)) {
        	        return $this->businesses_error_redirection($error_msg);
        	    }
        	    
        	    // Only set social info for new locations
        	    if (Helper_Api::empty_social_object($location->social)) {
        	        $location->social = $social_info;
        	    }
                
        	    $location_manager = new Model_Location_Manager();
        	    $location_manager->location = $location;
        	    // A marketplace user can manage all merchants within that mall
        	    $location_manager->include_merchants = $location instanceof Model_Mall;
        	    $user->location_managers[] = $location_manager;
        	}

        	// Assign the correct group to the user
        	if ($model == 'Model_Mall') {
        	    $user->group = Model_User::GROUP_MANAGER;
        	} else {
        	    $user->group = Model_User::GROUP_MERCHANT;
        	}

        	// Change the user status
        	$user->status = Model_User::STATUS_STEP2;

            // Save the user and redirect to following step. Uses transactions to ensure
            // that all relationships get properly saved
        	if ($user->save(NULL, TRUE)) {
        	    // MixPanel: Track event
        	    \Package::load('mixpanel');
        	    \Mixpanel\Api::track_event(Event_Mixpanel::EVENT_SETUP_PROFILE_BUSINESS, $user->id);

            	return Response::redirect("setup/profile/content");
        	} else {
        	    return $this->businesses_error_redirection('An error occurred while saving businesses info');
        	}
        }
    }
    
    private function check_social_info($social) {
        $facebook_pattern = '/^http(s)?:\/\/(www.)?facebook.com\/(page\/)?\w[\w\.]*(\/\w[\w\.]*)?$/';
        $twitter_pattern = '/^@\w+$/';
        if (!empty($social->facebook) &&
            preg_match($facebook_pattern, $social->facebook) == 0) {
            return 'Incorrect facebook page format.';
        } else if (!empty($social->twitter) &&
            preg_match($twitter_pattern, $social->twitter) == 0) {
            return 'Incorrect twitter user format.';
        }
    
        return '';
    }
    
    /**
     * Setup Setup Business Profile step #3
     */
    /*
    public function action_payment() {
    	$user = $this->user_login->user;
    
    	if (!$user) {
    		Session::set('message', array('type' => 'warning', 'message' => 'You have to be authenticated in order to do this.'));
    		return Response::redirect('login');
    	}
    
    	if ($user->status != Model_User::STATUS_STEP3) {
    		Session::set('message', array('type' => 'warning', 'message' => 'The user cannot access this step'));
    		return Response::redirect('welcome/index');
    	}

    	\Config::load('braintree', true);

    	$form = Input::post();
    
    	if (!$form) {
    		// display form
    		$incl = Config::get('cms.scripts');
    		$scripts = CMS::scripts(array(), $incl);
    
    		$header_data = array(
				'style' => array('reset.css', 'newLogin.css'),
				'scripts' => $scripts,
				'ie' => 'ie.css'
    		);
    
    		\Config::load('braintree', true);
    		
    		$payment_data = array(
				'notice' => $this->msg,
		        'client_side_encryption_key' => \Config::get('braintree.client_side_encryption_key'),
		        'client_side_library_url' => \Config::get('braintree.client_side_library_url'),
    		    'locations' => $user->get_assigned_companies(),
                'business_name' => $user->get_meta_field_value('name_of_business'),
                'login_hash' => $this->user_login->login_hash,
    		    'fees_info' => Config::get('cms.payments.fees_info'),
    		);
    
    		$header = View::forge('base/header', $header_data);
    		$cont = View::forge('cms/setup/profile/payment', $payment_data);
    		$footer = View::forge('base/footer');
    		$temp = $header . $cont . $footer;
    
    		return Response::forge($temp);
    	} else {
    	    if (! $form['privacy']) {
    	    	return $this->payment_error_redirection('You must accept the privacy and refund policies', 'privacy');
    	    }

            //* If the user have a free promo code, bypass the Braintree subscription 
            $promo_code = null;
            if (isset($form['promo'])) {
                $promo_code = Model_Promocode::query()->where('code', '=', $form['promo'])->get_one();
            }
            
            if (!is_null($promo_code) && $promo_code->type == Model_Promocode::FREE_ACCOUNT) {
                // Bypass subscription
                //$promo_code->users[] = $user;
                $user->promocode = $promo_code;
                
            } else {
                // process form
                // Use this to check if the form comes encrypted :P
                //*if ($form['cc_number'] == '123') { // validate new password
                // Session::set('message', array('message' => 'Error: Not encripted!', 'type' => 'fail'));
                //return Response::redirect('setup/profile/payment/');
                //}
                if (!CMS::verify_nonce('payment', $form['nonce'])) { // verify nonce
                    return $this->payment_error_redirection('Request denied');
                } elseif (empty($form['address'])) {
                    return $this->payment_error_redirection('Enter your billing address', 'address');
                } elseif (empty($form['city'])) {
                    return $this->payment_error_redirection('Enter your billing city', 'city');
                } elseif (empty($form['state'])) {
                    return $this->payment_error_redirection('Enter your billing state', 'state');
                } elseif (empty($form['zip'])) {
                    return $this->payment_error_redirection('Enter your billing zip code', 'zip');
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
                    $customer = \Braintree\Api::create_customer($credit_card, $billing_address);
                } catch (\Braintree\Exception $e) {
                    $code = $e->getCode();
                    if ($code == \Braintree\Api::ERROR_INCOMPLETE_CREDIT_CARD_INFO) {
                        return $this->payment_error_redirection('Please fill all your credit card info', 'number');
                    } elseif ($code == \Braintree\Api::ERROR_INCOMPLETE_BILLING_ADDRESS) {
                        return $this->payment_error_redirection('Please fill all your billing address info', 'address');
                    } elseif ($code == \Braintree\Api::ERROR_TRANSACTION_VALIDATION) {
                        return $this->payment_error_redirection('An error occurred during credit card validation: ' . $this->build_braintree_error_msg($e));
                    } else {
                        return $this->payment_error_redirection('An error occurred during credit card processing: ' . $this->build_braintree_error_msg($e));
                    }
                }

                $markeplaces_count = 0;
                $merchants_count = 0;
                foreach($user->location_managers as $location_manager) {
                    $location_type = $location_manager->location->type;
                    if ($location_type == Model_Location::TYPE_MALL) {
                        $markeplaces_count++;
                    } elseif ($location_type == Model_Location::TYPE_MERCHANT) {
                        $merchants_count++;
                    }
                }
                
                $add_ons = array( 'add' => array() );
                if ($markeplaces_count > 0) {
                    $add_ons['add'][] = array(
                        'id'       => Config::get('cms.payments.mall_monthly_fee_id'),
                        'quantity' => $markeplaces_count,
                    );
                }
                if ($merchants_count > 0) {
                    $add_ons['add'][] = array(
                        'id'       => Config::get('cms.payments.merchant_monthly_fee_id'),
                        'quantity' => $merchants_count,
                    );
                }
                
                $discounts = null;
                if (!is_null($promo_code) && $promo_code->type == Model_Promocode::PRICE_DISCOUNT) {
                    //$promo_code->users[] = $user;
                    $user->promocode = $promo_code;
                    $discounts = array(
                        'add' => array(
                            array( 'id' => $promo_code->code )
                        ),
                    );
                }

                try {
                    $subscription = \Braintree\Api::subscribe_customer(
                        $customer->credit_card_token,
                        Config::get('cms.payments.plan_id'),
                        NULL,
                        NULL,
                        $add_ons,
                        $discounts
                    );
                } catch (\Braintree\Exception $e) {
                    $code = $e->getCode();
                    if ($code == \Braintree\Api::ERROR_TRANSACTION_VALIDATION) {
                        return $this->payment_error_redirection('An error occurred during subscription validation: ' . $this->build_braintree_error_msg($e));
                    } else {
                        return $this->payment_error_redirection('An error occurred during subscription processing: ' . $this->build_braintree_error_msg($e));
                    }
                }

                $payment_info = new Model_User_Payment();
                $payment_info->customer_id = $customer->customer_id;
                $payment_info->credit_card_token = $customer->credit_card_token;
                $payment_info->subscription_id = $subscription->subscription_id;
                // This status indicates that the user is allowed to access the platform
                $payment_info->status = Model_User_Payment::STATUS_ACTIVE;
                // When to check if the user actually paid for the service after the trial
                $trial_days = Config::get('cms.payments.fees_info.trial_days');
                $payment_info->next_check_on = date('Y-m-d H:i:s', strtotime("+$trial_days days"));
                $user->payment = $payment_info;
            }
            
            // Mark user as active to grant access to CMS
            $user->status = Model_User::STATUS_ACTIVE;

            // Mark new locations as active
            foreach ($user->location_managers as $location_manager) {
                if ($location_manager->location->status == Model_Location::STATUS_SIGNUP) {
                    $location_manager->location->status = Model_Location::STATUS_ACTIVE;
                }
            }

            // Save the user and redirect to following step. Uses transactions to ensure
            // that all relationships get properly saved
            if ($user->save(NULL, TRUE)) {
                // MixPanel: Track event
                \Package::load('mixpanel');
                \Mixpanel\Api::track_event(Event_Mixpanel::EVENT_SETUP_PROFILE_PAYMENT, $user->id);

                Session::set('first_time', true); // in order to display welcome message
                return Response::redirect("welcome/index");
            } else {
                return $this->payment_error_redirection('An error occurred while saving payment info');
            }
    	}
    }
    */
    private function businesses_error_redirection($msg, $field = null) {
        Session::set('message', array('type' => 'fail', 'message' => $msg, 'field' => $field));
        return Response::redirect('setup/profile/businesses');
    }

    private function payment_error_redirection($msg, $field = null) {
    	Session::set('message', array('type' => 'fail', 'message' => $msg, 'field' => $field));
    	return Response::redirect('setup/profile/payment');
    }
    
    private function build_braintree_error_msg(\Braintree\Exception $e) {
        $errors_object = $e->getErrorsObject();
        $msg = '';
        if (! is_null($errors_object) && isset($errors_object->messages)) {
            $msg = implode(' ', $errors_object->messages);
        }
        return $msg;
    }
    
    private function process_images(&$data, $location) {
    	$data['logo'] = $location->logo;
    	$data['landing_screen_img'] = $location->landing_screen_img;
    
    	$files = $this->process_files();
    	foreach ($files as $file) {
    		$file['content'] = base64_encode(file_get_contents($file['file']));
    
    		if ($file['field'] == 'logo') {
    			$data['logo'] = Helper_Images_Logos::copy_one_image_from_params($file, $data);
    		} elseif ($file['field'] == 'landing') {
    			$data['landing_screen_img'] = Helper_Images_Landing::copy_one_image_from_params($file, $data);
    		}
    	}
    }

    private function has_permissions_for($location) {
        $user = $this->user_login->user;
        
        if (!$user) {
            return false;
        }
        
        if ($user->is_admin()) {
            return true;
        }
        
        $companies = $user->get_assigned_companies();
        foreach ($companies as $company) {
            if ($location->id == $company->id) {
                return true;
            }
        }
        
        return false;
    }
    
    public function action_signup() {
        // display form
        $scripts = Config::get('cms.signup_scripts');
        $header_data = array(
            'style' => array('reset.css', 'newLogin.css', 'jquery.Jcrop.min.css', 'jquery-ui-newLogin/jquery-ui.css'),
            'scripts' => $scripts,
            'ie' => 'ie.css'
        );

        $user = $this->user_login->user;

        if (! ($user->is_guest() || $user->is_new_user() || $user->is_regular_user())) {
        	Session::set('message', array('type' => 'warning', 'message' => 'You can\'t access the signup flow.'));
        	return Response::redirect('/');
        }

        $businesses = $this->get_businesses($user);
        $business_name = $user->get_meta_field_value('name_of_business');
        
        \Config::load('braintree', true);
        
        $signup_data = array(
            'user_nonce' => CMS::create_nonce('user_create'),
            'payment_nonce' => CMS::create_nonce('payment'),
            'user' => $user,
            'business_name' => $business_name ? : htmlentities("<Business>"),
	        'businesses_search_url' => Uri::create('api/location/businesses_search'),
	        'login_hash' => $this->user_login->login_hash,
            'businesses' => $businesses,
            'social' => $this->get_businesses_social($businesses),
            'content' => $this->get_businesses_content($user, $businesses),
            'instagram' => $this->get_instagram_info($user),
            'client_side_encryption_key' => \Config::get('braintree.client_side_encryption_key'),
            'client_side_library_url' => \Config::get('braintree.client_side_library_url'),
            'fees_info' => Config::get('cms.payments.fees_info'),
        );
        
        $header = View::forge('base/header', $header_data);
        $cont = View::forge('cms/setup/profile/signup', $signup_data);
        $footer = View::forge('base/footer');
        $temp = $header . $cont . $footer;
        
        return Response::forge($temp);
    }
    
    private function get_businesses($user) {
        $businesses = array();
        
        if ($user->status >= Model_User::STATUS_STEP2) {
            // User has already completed step 2. Get assigned businesses
            $businesses = $user->get_assigned_companies(false);
        }
        
        return $businesses;
    }

    private function get_businesses_social($locations) {
        $social = Helper_Api::new_social_object();

        foreach ($locations as $location) {
            if (! Helper_Api::empty_social_object($location->social)) {
                $social = $location->social;
                break;
            }
        }

        return $social;
    }
    
    private function get_businesses_content($user, $locations) {
        $location_with_images = null;

        if ($user->status >= Model_User::STATUS_STEP2) {
        	// User has already completed step 2. Get content for businesses
            foreach ($locations as $location) {
            	if (!$location_with_images && $location->logo && $location->landing_screen_img) {
            		$location_with_images = $location;
            	}
            }
            
            if (!$location_with_images) {
            	$location_with_images = array_shift($locations);
            	// let's try to find images from a marketplace instead, as a last fallback
            	foreach ($locations as $location) {
            		if (isset($location->mall_id) && $location->mall_id) {
            			$mall = Model_Mall::find($location->mall_id);
            			if ($mall->logo && $mall->landing_screen_img) {
            				$location_with_images->logo = $mall->logo;
            				$location_with_images->landing_screen_img = $mall->landing_screen_img;
            			}
            		}
            	}
            }
        }

        return (object)array(
            'name' => $location_with_images ? $location_with_images->name : '',
            'logo' => $location_with_images ? $location_with_images->logo : '',
            'landing_screen_img' => $location_with_images ? $location_with_images->landing_screen_img : '',
        );
    }

    private function get_instagram_info($user) {
        \Package::load('instagram');
        
        $instagram_latest_post = NULL;
        if ($user->instagram) {
        	try {
        		$feed = \Instagram\Api::get_user_recent_media($user->instagram->access_token, $user->instagram->instagram_user_id);
        		$instagram_latest_post = array_shift($feed);
        	} catch (\Instagram\Exception $e) {
        	}
        }
        
        return (object)array(
        	'is_set' => !is_null($instagram_latest_post),
        	'latest_post' => $instagram_latest_post,
        	'auth_url' => \Instagram\Api::get_auth_url(Uri::create("setup/profile/signup")),
        );
    }
    
    public function action_ajax_create() {
        $user = $this->user_login->user;

        $form = Input::post();
        $errors = array();
        
        if (!CMS::verify_nonce('user_create', $form['nonce'])) { // verify nonce
        	$errors[] = array('message' => 'Request Denied', 'field' => null);
        } elseif ($user->is_guest() && !$form['password']) { // validate new password
        	$errors[] = array('message' => 'Enter password', 'field' => 'password');
        } elseif (!$form['real_name']) { // Validate real name
        	$errors[] = array('message' => 'Enter your name', 'field' => 'real_name');
        } elseif (!$form['name_of_business']) { // validate business name
        	$errors[] = array('message' => 'Enter name of business', 'field' => 'name_of_business');
        } elseif (!$form['email']) { // validate email
        	$errors[] = array('message' => 'Enter your email', 'field' => 'email');
        } elseif (!$form['role']) { // validate role
        	$errors[] = array('message' => 'Enter your position', 'field' => 'role');
        } elseif ($user->is_guest() && $form['password'] != $form['confirmPassword']) { // validate matching passwords
        	$errors[] = array('message' => 'Passwords do not match', 'field' => 'confirmPassword');
        } elseif ($user->is_guest() && !isset($form['terms'])) {
        	$errors[] = array('message' => 'You should accept the Terms of Service', 'field' => 'terms');
        }

        if (count($errors) > 0) {
            return $this->ajax_error($errors, 'user_create');
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
                    $errors[] = array('message' => $output->meta->error, 'field' => null);
                    return $this->ajax_error($errors, 'user_create');
        	} else {
                    $auth = Auth::instance('Shopsuey_Session');
                    $auth->set_app_id(Config::get('cms.appid'));

                    $userInfo = $output->data->user;

                    $updatedUser = Model_User::find($userInfo->id);

                    if ($user->is_guest() && !$auth->force_login($updatedUser)) {
                    $errors[] = array('message' => 'Unexpected error while starting user session', 'field' => null);
                    return $this->ajax_error($errors, 'user_create');
                    }

                    // This only needs to be done if the user is new
                    if ($user->is_guest()) {
                        // MixPanel: Register user profile and track event
                        
                        \Package::load('mixpanel');

                        $profile_info = array(
                            'company'  => $updatedUser->get_meta_field_value('name_of_business'),
                            'username' => $updatedUser->get_meta_field_value('real_name'),
                            'role'     => $updatedUser->get_meta_field_value('role'),
                            'email'    => $updatedUser->email,
                        );

                        \Mixpanel\Api::set_profile($updatedUser->id, $profile_info);
                        \Mixpanel\Api::track_event(Event_Mixpanel::EVENT_CREATE_ACCOUNT, $updatedUser->id);
                    }

                    $user_login = $auth->get_user_login_object();
                    return $this->ajax_response(array(
                        'login_hash' => $user_login->login_hash,
                        //'nonce'      => CMS::create_nonce('user_create')
                    ));
        	}
        } else {
            
            $errors[] = array('message' => 'Error: 900 - Unable to process your request', 'field' => null);
            return $this->ajax_error($errors, 'user_create');
            
        }
    }
    
    public function action_ajax_businesses() {
        $user = $this->user_login->user;
        $errors = array();
        
        if (!$user) {
            $errors[] = array('message' => 'You have to be authenticated in order to do this.', 'field' => NULL);
            return $this->ajax_error($errors);
        }
        
        if (! $user->is_new_user()) {
            $errors[] = array('message' => 'The user cannot access this step', 'field' => NULL);
            return $this->ajax_error($errors);
        }
        
        $form = Input::post();
        
    	// process form
    	$stores_number = (int)Input::post('stores_number', 0);
    	if ($stores_number <= 0) {
            $errors[] = array('message' => 'Invalid number of stores', 'field' => NULL);
            return $this->ajax_error($errors);
    	}
    
    	$multiple_params = array();
    	$multiple_fields = array('name', 'zip_or_city', 'location', 'location_id', 'mall_id');
    	foreach($multiple_fields as $multiple_field) {
    		$params = Input::post($multiple_field, array());
    		if (count($params) != $stores_number) {
                $errors[] = array('message' => 'One of the expected parameters is missing', 'field' => NULL);
                return $this->ajax_error($errors);
    		}
    		$multiple_params[$multiple_field] = $params;
    	}
    
    	if (Input::post('business_type', '') == 'marketplace') {
    		$model = 'Model_Mall';
    	} else {
    		$model = 'Model_Merchant';
    	}
    	 
    	// TODO: There's no field for instagram
    	$social_info = Helper_Api::new_social_object();
    	$social_info->twitter = Input::post('twitter', '');
    	$social_info->facebook = Input::post('facebook', '');

    	$location_managers_to_delete = array();
    	// Remove currently managed locations for user
    	foreach ($user->location_managers as $location_manager) {
    		unset($user->location_managers[$location_manager->id]);
    		$location_managers_to_delete[] = $location_manager;
    	}

    	$locations = array();

    	for($i = 0; $i < $stores_number; $i++) {
    		$location_id = $multiple_params['location_id'][$i];
    		if (!empty($location_id)) {
    			$location = $model::find($location_id);
    			if (! $location) {
                    $errors[] = array('message' => 'Could not find the existing location', 'field' => NULL);
                    return $this->ajax_error($errors);
    			}
    		} else {
    			$name = $multiple_params['name'][$i];
    			$zip_or_city = $multiple_params['zip_or_city'][$i];
    			if (empty($name) || empty($zip_or_city)) {
                    $errors[] = array('message' => 'The new location does not have the name or the zip code', 'field' => NULL);
                    return $this->ajax_error($errors);
    			}
    				
    			$location_info = $multiple_params['location'][$i];
    			if ($model == 'Model_Mall' && empty($location_info)) {
                    $errors[] = array('message' => 'The city for the new marketplace is missing', 'field' => NULL);
                    return $this->ajax_error($errors);
    			}
    
    			$location = new $model();
    			$location->name = $name;
    			if (is_numeric($zip_or_city)) {
    				$location->zip = $zip_or_city;
    				$location->city = '';
    			} else {
    				$location->city = $zip_or_city;
    				$location->zip = '';
    			}
    			 
    			// Special status for pending locations of a signup flow. Must be changed
    			// to 'active' when the user completes the payment step
    			$location->status = $model::STATUS_SIGNUP;
    
    			//     	            $location->is_customer = 1;
    			$location->address = '';
    			$location->st = '';
    			$location->country = \Model_Country::get_default();
    			$location->contact = '';
    			$location->email = '';
    			$location->phone = '';
    			$location->web = '';
    			$location->newsletter = '';
    			$location->tags = '';
    			$location->content = '';
    			$location->description = '';
    			$location->hours = Helper_Api::new_hours_array();
    			$location->social = Helper_Api::new_social_object();
    			$location->logo = '';
    			if ($model == 'Model_Merchant') {
    				$location->floor = '';
    			}
    			$location->max_users = '0';
    			$location->plan = '0';
    			$location->use_instagram = FALSE;
    			$location->timezone = '';
    
    			$location->auto_generated = 0;
    			$location->setup_complete = 0;
    			$location->manually_updated = 0;
    			$location->created_by = $location->edited_by = $user->id;
    
    			if ($location instanceof Model_Merchant) {
    				$mall_id = $multiple_params['mall_id'][$i];
    				if (!empty($mall_id)) {
    					// Regular merchant
    					$mall = Model_Mall::find($mall_id);
    					if (! $mall) {
                            $errors[] = array('message' => 'The marketplace for the new merchant could not be found', 'field' => NULL);
                            return $this->ajax_error($errors);
    					}
    					$location->mall = $mall;
    				} else {
    					// Standalone merchant
    					$location->mall = NULL;
    				}
    			} else {
    				$location->city = $location_info;
    			}
    		}
    		 
    		$error_msg = $this->check_social_info($social_info);
    		 
    		if (!empty($error_msg)) {
                $errors[] = array('message' => $error_msg, 'field' => NULL);
                return $this->ajax_error($errors);
    		}
    		 
    		// Only set social info for new locations
    		if (Helper_Api::empty_social_object($location->social)) {
    			$location->social = $social_info;
    		}

    		$location_manager = new Model_Location_Manager();
    		$location_manager->location = $location;
    		// A marketplace user can manage all merchants within that mall
    		$location_manager->include_merchants = $location instanceof Model_Mall;
    		$user->location_managers[] = $location_manager;
    		
    		$locations[] = $location;
    	}
    
    	// Assign the correct group to the user
    	if ($model == 'Model_Mall') {
    		$user->group = Model_User::GROUP_MANAGER;
    	} else {
    		$user->group = Model_User::GROUP_MERCHANT;
    	}

    	$step_changed = FALSE;
    	if ($user->status < Model_User::STATUS_STEP2) {
        	// Change the user status
        	$user->status = Model_User::STATUS_STEP2;
        	$step_changed = TRUE;
    	}
    
    	// Save the user and redirect to following step. Uses transactions to ensure
    	// that all relationships get properly saved
    	if ($user->save(NULL, TRUE)) {
    	    foreach ($location_managers_to_delete as $location_manager) {
    	    	$location_manager->delete();
    	    }

    	    if ($step_changed) {
        		// MixPanel: Track event
        		\Package::load('mixpanel');
        		\Mixpanel\Api::track_event(Event_Mixpanel::EVENT_SETUP_PROFILE_BUSINESS, $user->id);
    	    }

        	// Generate a response that will be used to populate content page
    	    return $this->ajax_response(array(
                'content' => $this->get_businesses_content($user, $locations),
            ));

    	} else {
    	    $errors[] = array('message' => 'An error occurred while saving businesses info', 'field' => NULL);
    	    return $this->ajax_error($errors);
    	}
    }
    
    public function action_ajax_content() {
        $user = $this->user_login->user;
        $errors = array();
        
        if (!$user) {
        	$errors[] = array('message' => 'You have to be authenticated in order to do this.', 'field' => NULL);
        	return $this->ajax_error($errors);
        }
        
        if (! $user->is_new_user()) {
        	$errors[] = array('message' => 'The user cannot access this step', 'field' => NULL);
        	return $this->ajax_error($errors);
        }
        
        $data = Input::post();
        
        $locations = $this->get_businesses($user);
        $content = $this->get_businesses_content($user, $locations);
        $instagram = $this->get_instagram_info($user);
        
        $this->process_images($data, $content);
        
        $data['replace_logo_in_all_stores'] = isset($data['replace_logo_in_all_stores']) ? $data['replace_logo_in_all_stores'] : false;
        $data['replace_landing_in_all_stores'] = isset($data['replace_landing_in_all_stores']) ? $data['replace_landing_in_all_stores'] : false;
        $data['landing_instagram'] = isset($data['landing_instagram']) ? $data['landing_instagram'] : false;
        
        foreach ($locations as $location) {
        
        	if (!$location->logo || $data['replace_logo_in_all_stores']) {
        		$location->logo = $data['logo'];
        	}
        
        	if (!$location->landing_screen_img || $data['replace_landing_in_all_stores']) {
        		if ($data['landing_instagram'] && !is_null($instagram->latest_post)) {
        			$location->use_instagram = TRUE;
        			$location->user_instagram = $user->instagram;
        		} else {
        			$location->landing_screen_img = $data['landing_screen_img'];
        		}
        	}
        
        	if (empty($location->categories) && isset($data['categories']) && count($data['categories']) <= 3) {
        		foreach ($data['categories'] as $category_id) {
        			if ($category_id) {
        				$category = Model_Category::find($category_id);
        				if ($category) {
        					$location->categories[] = $category;
        				}
        			}
        		}
        	}
        
        	$location->save();
        }
        
        if ($user->status < Model_User::STATUS_STEP2) {
            
            // Change the user status
            $user->status = Model_User::STATUS_STEP3;

            // Save the user
            $user->save();
            
            // MixPanel: Track event
            \Package::load('mixpanel');
            \Mixpanel\Api::track_event(Event_Mixpanel::EVENT_SETUP_PROFILE_CONTENT, $user->id);
            
        }else{
            
            //SIGNUP PROCESS FINISHED. SAVE AND GTFO.
            
            // Mark user as active to grant access to CMS
            $user->status = Model_User::STATUS_ACTIVE;

            $signup_location_ids = array();
            // Mark new locations as active
            foreach ($user->location_managers as $location_manager) {
                    if ($location_manager->location->status == Model_Location::STATUS_SIGNUP) {
                            $location_manager->location->status = Model_Location::STATUS_ACTIVE;
                            $signup_location_ids[] = $location_manager->location->id;
                    }
            }

            // Save the user and redirect to following step. Uses transactions to ensure
            // that all relationships get properly saved
            if ($user->save(NULL, TRUE)) {
                
                // MixPanel: Track event
                \Package::load('mixpanel');
                \Mixpanel\Api::track_event(Event_Mixpanel::EVENT_SETUP_PROFILE_PAYMENT, $user->id);

                $email_data = array('user' => $user, 'signup_location_ids' => $signup_location_ids);
                $notification_email = Config::get('cms.signup_notification_email');
                $result = CMS::email($notification_email, null, 'A user completed signup', $email_data, 'email/signup_complete');
                if ($result['meta']['status'] == 0) {
                    \Log::warning("An error occurred while sending notification email to admin: {$result['meta']['error']}");
                }

                Session::set('first_time', true); // in order to display welcome message

                return $this->ajax_response(array(
                        'status' => 'ok',
                ));

            } else {
                $errors[] = array('message' => 'An error occurred while saving info', 'field' => NULL);
                return $this->ajax_error($errors);
            }
            
            
        }
        
        return $this->ajax_response(array(
        	'content' => $this->get_businesses_content($user, $locations),
        ));
    }
    
    /*
    public function action_ajax_payment() {
    	$user = $this->user_login->user;
    	$errors = array();
    	
    	if (!$user) {
    		$errors[] = array('message' => 'You have to be authenticated in order to do this.', 'field' => NULL);
    		return $this->ajax_error($errors, 'payment');
    	}
    	
    	if (! $user->is_new_user() || $user->status < Model_User::STATUS_STEP2) {
    		$errors[] = array('message' => 'The user cannot access this step', 'field' => NULL);
    		return $this->ajax_error($errors, 'payment');
    	}

    	\Config::load('braintree', true);
    
    	$form = Input::post();
    
		if (! $form['privacy']) {
    		$errors[] = array('message' => 'You must accept the privacy and refund policies', 'field' => 'privacy');
    		return $this->ajax_error($errors, 'payment');
		}
    
		// If the user have a free promo code, bypass the Braintree subscription 
		$promo_code = null;
		if (isset($form['promo'])) {
			$promo_code = Model_Promocode::query()->where('code', '=', $form['promo'])->get_one();
		}
    
		if (!is_null($promo_code) && $promo_code->type == Model_Promocode::FREE_ACCOUNT) {
			// Bypass subscription
			//$promo_code->users[] = $user;
			$user->promocode = $promo_code;

		} else {
			// process form
			// Use this to check if the form comes encrypted :P
			//if ($form['cc_number'] == '123') { // validate new password
			// Session::set('message', array('message' => 'Error: Not encripted!', 'type' => 'fail'));
			//return Response::redirect('setup/profile/payment/');
			//}
			if (!CMS::verify_nonce('payment', $form['nonce'])) { // verify nonce
    		    $errors[] = array('message' => 'Request denied', 'field' => NULL);
			} elseif (empty($form['address'])) {
    		    $errors[] = array('message' => 'Enter your billing address', 'field' => 'address');
			} elseif (empty($form['city'])) {
    		    $errors[] = array('message' => 'Enter your billing city', 'field' => 'city');
			} elseif (empty($form['state'])) {
    		    $errors[] = array('message' => 'Enter your billing state', 'field' => 'state');
			} elseif (empty($form['zip'])) {
    		    $errors[] = array('message' => 'Enter your billing zip code', 'field' => 'zip');
			}

			if (count($errors) > 0) {
				return $this->ajax_error($errors, 'payment');
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
				$customer = \Braintree\Api::create_customer($credit_card, $billing_address);
			} catch (\Braintree\Exception $e) {
				$code = $e->getCode();
				if ($code == \Braintree\Api::ERROR_INCOMPLETE_CREDIT_CARD_INFO) {
    		        $errors[] = array('message' => 'Please fill all your credit card info', 'field' => 'number');
				} elseif ($code == \Braintree\Api::ERROR_INCOMPLETE_BILLING_ADDRESS) {
    		        $errors[] = array('message' => 'Please fill all your billing address info', 'field' => 'address');
				} elseif ($code == \Braintree\Api::ERROR_TRANSACTION_VALIDATION) {
    		        $errors[] = array('message' => 'An error occurred during credit card validation: ' . $this->build_braintree_error_msg($e), 'field' => NULL);
				} else {
    		        $errors[] = array('message' => 'An error occurred during credit card processing: ' . $this->build_braintree_error_msg($e), 'field' => NULL);
				}
			}

			if (count($errors) > 0) {
				return $this->ajax_error($errors, 'payment');
			}

			$markeplaces_count = 0;
			$merchants_count = 0;
			foreach($user->location_managers as $location_manager) {
				$location_type = $location_manager->location->type;
				if ($location_type == Model_Location::TYPE_MALL) {
					$markeplaces_count++;
				} elseif ($location_type == Model_Location::TYPE_MERCHANT) {
					$merchants_count++;
				}
			}

			$add_ons = array( 'add' => array() );
			if ($markeplaces_count > 0) {
				$add_ons['add'][] = array(
					'id'       => Config::get('cms.payments.mall_monthly_fee_id'),
					'quantity' => $markeplaces_count,
				);
			}
			if ($merchants_count > 0) {
				$add_ons['add'][] = array(
					'id'       => Config::get('cms.payments.merchant_monthly_fee_id'),
					'quantity' => $merchants_count,
				);
			}

			$discounts = null;
			if (!is_null($promo_code) && $promo_code->type == Model_Promocode::PRICE_DISCOUNT) {
				//$promo_code->users[] = $user;
				$user->promocode = $promo_code;
				$discounts = array(
					'add' => array(
						array( 'id' => $promo_code->code )
					),
				);
			}

			try {
				$subscription = \Braintree\Api::subscribe_customer(
						$customer->credit_card_token,
						Config::get('cms.payments.plan_id'),
						NULL,
						NULL,
						$add_ons,
						$discounts
				);
			} catch (\Braintree\Exception $e) {
				$code = $e->getCode();
				if ($code == \Braintree\Api::ERROR_TRANSACTION_VALIDATION) {
    		        $errors[] = array('message' => 'An error occurred during subscription validation: ' . $this->build_braintree_error_msg($e), 'field' => NULL);
				} else {
    		        $errors[] = array('message' => 'An error occurred during subscription processing: ' . $this->build_braintree_error_msg($e), 'field' => NULL);
				}
			}

			if (count($errors) > 0) {
				return $this->ajax_error($errors, 'payment');
			}

			$payment_info = new Model_User_Payment();
			$payment_info->customer_id = $customer->customer_id;
			$payment_info->credit_card_token = $customer->credit_card_token;
			$payment_info->subscription_id = $subscription->subscription_id;
			// This status indicates that the user is allowed to access the platform
			$payment_info->status = Model_User_Payment::STATUS_ACTIVE;
			// When to check if the user actually paid for the service after the trial
			$trial_days = Config::get('cms.payments.fees_info.trial_days');
			$payment_info->next_check_on = date('Y-m-d H:i:s', strtotime("+$trial_days days"));
			$user->payment = $payment_info;
		}

		// Mark user as active to grant access to CMS
		$user->status = Model_User::STATUS_ACTIVE;

		$signup_location_ids = array();
		// Mark new locations as active
		foreach ($user->location_managers as $location_manager) {
			if ($location_manager->location->status == Model_Location::STATUS_SIGNUP) {
				$location_manager->location->status = Model_Location::STATUS_ACTIVE;
				$signup_location_ids[] = $location_manager->location->id;
			}
		}

		// Save the user and redirect to following step. Uses transactions to ensure
		// that all relationships get properly saved
		if ($user->save(NULL, TRUE)) {
			// MixPanel: Track event
			\Package::load('mixpanel');
			\Mixpanel\Api::track_event(Event_Mixpanel::EVENT_SETUP_PROFILE_PAYMENT, $user->id);

			$email_data = array('user' => $user, 'signup_location_ids' => $signup_location_ids);
			$notification_email = Config::get('cms.signup_notification_email');
			$result = CMS::email($notification_email, null, 'A user completed signup', $email_data, 'email/signup_complete');
			if ($result['meta']['status'] == 0) {
			    \Log::warning("An error occurred while sending notification email to admin: {$result['meta']['error']}");
			}

			Session::set('first_time', true); // in order to display welcome message
			
			return $this->ajax_response(array(
				'status' => 'ok',
			));
		} else {
		    $errors[] = array('message' => 'An error occurred while saving payment info', 'field' => NULL);
		    return $this->ajax_error($errors, 'payment');
		}
    }
    */
    private function ajax_error($errors, $nonce_name = NULL) {
        $error_response = array(
            'error' => '1',
            'errors' => $errors,
        );
        if (!is_null($nonce_name)) {
            $error_response['nonce'] = CMS::create_nonce($nonce_name);
        }
        return $this->ajax_response($error_response);
    }
    
    private function ajax_response($response) {
        $this->response->set_header('Cache-Control', 'no-cache, must-revalidate');
        $this->response->set_header('Expires', 'Mon, 26 Jul 1997 05:00:00 GMT');
        $this->response->set_header('Content-Type', 'application/json');
        return \Format::forge($response)->to_json();
    }
}