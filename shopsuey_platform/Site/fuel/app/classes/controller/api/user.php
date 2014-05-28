<?php

use Fuel\Core\Input;

use Fuel\Core\Package;

use Fuel\Core\Model;
use Fuel\Core\Database_Exception;

/**
 * The User API Controller.
 * This controllers the CRUD proceedures for users
 *
 * @package  app
 * @extends  Controller_Rest
 */

class Controller_Api_User extends Controller_Api {
    
    const BASE_FB_URL = 'https://graph.facebook.com/';
    const ONLINE_TIME_WINDOW = 3600;
    
	private $_invalid_field = null;
	private $_invalid_error_code = null;

	public function action_fblogin() {
		if (Input::method() != 'POST') { $this->response($this->no_access); return; }

        error_log("fb login reached");
        
        if ($_SERVER['SERVER_PORT'] != 443) { $this->_error_response(Code::ERROR_SSL_REQUIRED); return; }
		
		$app_id = Input::post('app_id', 0);
		$application = Model_Application::query()->where('token', $app_id)->get_one();
			// Validate app_id
		if (!$application) {
			$this->_error_response(Code::ERROR_INVALID_APP_ID);
			return;
		}
        
        $access_token = Input::post('access_token');
        if (empty($access_token)) {
            $this->_error_response(Code::ERROR_ACCESS_TOKEN_REQUIRED);
			return;
        }
        
        try{
            $user_fb_data = $this->get_fb_data($access_token);
        } catch (RequestException $ex) {
            $this->_error_response(Code::ERROR_ACCESS_TOKEN_INVALID);
            return;
        }
        
        if ($user_fb_data && isset($user_fb_data['error']) && $user_fb_data['error']['code'] == 190) {
            $this->_error_response(Code::ERROR_ACCESS_TOKEN_INVALID);
			return;
        } elseif (!$user_fb_data || !isset($user_fb_data['id'], $user_fb_data['name'], $user_fb_data['email'])) {
            $this->_error_response(Code::ERROR_FB_API_UNAVAILABLE);
			return;
        }
        
        $fbuid = $user_fb_data['id'];
        $email = $user_fb_data['email'];
        $meta_fb_info = array(
            'real_name' => $user_fb_data['name'],
            'gender' => $user_fb_data['gender'],
            'language' => $user_fb_data['locale']
        );

        if (isset($user_fb_data['birthday'])) {
            $birthday = $user_fb_data['birthday'];
            $meta_fb_info['dob'] = array('year' => substr($birthday, 6, 4), 'month' => substr($birthday, 0, 2), 'day' => substr($birthday, 3, 2));
        }

		// check if user exists
		$user = Model_User::query()->where('fbuid', $fbuid)->where('status', Model_User::STATUS_ACTIVE)->get_one();

		$auth = Auth::instance('Shopsuey_Stateless');

		if (!$user) {
			$user = new Model_User();
			$user->password = $auth->hash_password('Pass' . uniqid());
			$user->email = $email;
			$user->group = Model_User::GROUP_USER;
			$user->status = Model_User::STATUS_ACTIVE;
            $user->fbuid = $fbuid;
            
            try {
                $user->save();
            } catch (Database_Exception $e) {
                // perhaps there is already a user with the same email address
                // but no facebook account associated, let's link them!
                $user = Model_User::query()->where('email', $email)->get_one();
                if (!$user) {
                    $this->_error_response(Code::ERROR_USER_SAVE_ERROR);
                    return;
                }
                $user->fbuid = $fbuid;
                $user->save();
            } catch (Exception $e) {
                $this->_error_response(Code::ERROR_USER_SAVE_ERROR);
				return;
            }

            $allowed_meta_fields = Config::get('cms.user_meta_fields');
            $json_meta_fields = Config::get('cms.json_meta_fields');
            foreach($meta_fb_info as $key => $value) {
                if (in_array($key, $allowed_meta_fields)) {
                    $meta_value = in_array($key, $json_meta_fields) ? json_encode($value) : $value;
                    Helper_Api::set_user_meta($user, $key, $meta_value);
                }
            }
        }

        if ($auth->force_login($user)) {
			$user_login = $auth->get_user_login_object();
			$data = array(
					'data' => Helper_Api::login_response($user_login),
					'meta' => array('status' => 1, 'error' => NULL)
			);
			$this->response($data);
		} else {
			$this->_error_response(Code::ERROR_AUTOLOGIN_FAILED);
		}
	}
    
    protected function get_fb_data($access_token) {
        $url = self::BASE_FB_URL . "me";
        $curl = Request::forge($url, 'curl');
        $curl->set_method('get');
        $curl->set_params(array(
            'fields' => 'id,name,email,birthday,gender,locale',
            'access_token' => $access_token
        ));
        $response = $curl->execute()->response();
        return json_decode($response, true);
    }
    
	public function action_anonymous_login() {
		if (Input::method() != 'POST') { $this->response($this->no_access); return; }

		$params = Input::post();

		$app_id = Input::post('app_id', 0);
		$application = Model_Application::query()->where('token', $app_id)->get_one();
			// Validate app_id
		if (!$application) {
			$this->_error_response(Code::ERROR_INVALID_APP_ID);
			return;
		}
		
		// check if user exists
		$udid = Input::param('udid');
		if (empty($udid)) {
			$this->_error_response(Code::ERROR_UID_REQUIRED);
			return;
		}
		$meta = Model_User_Metafield::query()->where('key', 'udid')->where('value', $udid)->get_one();

		$auth = Auth::instance('Shopsuey_Stateless');
		
		if (!$meta) {
			$user = new Model_User();
			$uniqid = uniqid();
			$user->password = $auth->hash_password("Pass$uniqid");
			$user->email = "dummy_$uniqid@anonymous";
			$user->group = Model_User::GROUP_ANONYMOUS;
			$user->status = Model_User::STATUS_ACTIVE;
		
			$meta = new Model_User_Metafield();
			$meta->key = 'udid';
			$meta->value = $udid;
				
			$user->meta_fields[] = $meta;
				
			if (! $user->save()) {
				$this->_error_response(Code::ERROR_USER_SAVE_ERROR);
				return;
			}
		} else {
			$user = $meta->user;
		}
		
		if ($auth->force_login($user)) {
			$user_login = $auth->get_user_login_object();
			$data = array(
					'data' => Helper_Api::login_response($user_login),
					'meta' => array('status' => 1, 'error' => NULL)
			);
			$this->response($data);
		} else {
			$this->_error_response(Code::ERROR_AUTOLOGIN_FAILED);
		}
	}

	public function action_forgot() {
		if (Input::method() != 'POST') { $this->response($this->no_access); return; }

		$email = Input::post('email', '');
		
		$user = Model_User::query()->where('email', $email)->where('status', Model_User::STATUS_ACTIVE)->get_one();

		if (!$user) {
			$this->_error_response(Code::ERROR_INVALID_EMAIL);
			return;
		}

		$salt = Config::get('cms.salt');
		
		$user_reset = new Model_User_Reset();
		$user_reset->user = $user;
		$user_reset->hash = base64_encode($user_reset->generate_hash($salt));
		$user_reset->used = FALSE;
		$user_reset->expiracy = date('Y-m-d H:i:s', strtotime('+15 minute')); 

		if ($user_reset->save()) {

			$encoded_hash = urlencode($user_reset->hash);
			$link = Uri::create("login/reset/?hash={$encoded_hash}");

			// Email Template Data
			$email_data = array('username' => $user->email, 'email' => $user->email, 'link' => $link);

			$data = CMS::email($user->email, null, 'ShopSuey :: Forgotten Password', $email_data, 'email/forgot');
		} else {
			$this->_error_response(Code::ERROR_RESET_GENERATION);
			return;
		}

		$this->response($data);
	} // action_forgot -> forgot password routine
	
	public function action_change_password() {
	    if (Input::method() != 'POST') { $this->response($this->no_access); return; }
	
	    $old_password = Input::post('old_password', '');
	    $new_password = Input::post('new_password', '');
	    
	    $auth = Auth::instance('Shopsuey_Stateless');
	    $current_user = $this->user_login->user;
	    $hashed_old_password = $auth->hash_password($old_password);
	    
	    if ($current_user->password == $hashed_old_password) {
	        $current_user->password = $auth->hash_password($new_password);
	    } else {
	        return $this->_error_response(Code::ERROR_WRONG_PASSWORD);
	    }
	    
	    if (!CMS::valid_password($new_password)) {
	        return $this->_error_response(Code::ERROR_INVALID_PASSWORD);
	    }
	
	    if ($current_user->save()) {
	        $data = array(
                'data' => array('status' => true),
                'meta' => array('error' => '', 'status' => 1)
	        );
	        $this->response($data);
	    } else {
	        $this->_error_response(Code::ERROR_CHANGE_PASSWORD);
	    }
	}

	public function action_get() {
		if (Input::method() != 'GET') { $this->response($this->no_access); return; }

		$user_id = $this->param('id', 0);
		$user = Model_User::find($user_id);

		$current_user = $this->user_login->user;

		if ($user && ($current_user->id == $user_id || $current_user->can_edit_group_members($user->group))) {
			$data = array(
				'data' => array('user' => Helper_Api::user_response($user)),
				'meta' => array('error' => '', 'status' => 1)
			);
			$this->response($data);
		} else {
			$this->_error_response(Code::ERROR_INVALID_USER_ID);
		}
	}

	public function action_list() {
		if (Input::method() != 'GET') { $this->response($this->no_access); return; }

		$page      = $this->param('page', 1);
		$string    = $this->param('string', Input::param('string', ''));
        $app_users = (bool)Input::get('app_users', 0);

		$query = Model_User::query()
			->related('meta_fields')
			->where('status', Model_User::STATUS_ACTIVE);
		
		
        if ($app_users && $this->user_login->user->group == Model_User::GROUP_SUPERADMIN) {
            $query->where('group', Model_User::GROUP_USER);
        } else {
            $query->where('group', 'between', array(Model_User::GROUP_MERCHANT, $this->user_login->user->group));
        }
        
		if (!empty($string)) {
			$query->and_where_open();
			$query->or_where('email', 'like', "%$string%");
			$query->or_where('meta_fields.value', 'like', "%$string%");
			$query->and_where_close();
		}
		
		$count = $query->count();

		$meta = array(
			'pagination' => $this->_pagination($count, $page),
			'status' => 1,
			'error' => null,
		);
		ksort($meta);

		$users = $query
			->order_by('email', 'asc')
			// This limit clauses causes issues with the relation
// 			->rows_limit($meta['pagination']['limit'])
// 			->rows_offset($meta['pagination']['offset']['current'])
			->get();

		// TODO: Try to fix the query above instead of manually handling pagination
		$users = array_slice($users, $meta['pagination']['offset']['current'], $meta['pagination']['limit']); 

		// Improve output for easier handling
		$obj_users = array();
		foreach($users as $user) {
			$obj_users[] = Helper_Api::user_response($user);
		}

		$data = array('data' => array('users' => $obj_users), 'meta' => $meta);
		$this->response($data);
	}

	public function action_post() {
		if (Input::method() != 'POST') { $this->response($this->no_access); return; }

		$params = Input::post();

		if (! $this->_validate_user_params($params)) {
			$meta = Helper_Api::build_error_meta($this->_invalid_error_code, array('parameter' => array('name' => $this->_invalid_field, 'value' => @$params[$this->_invalid_field])));
			$data = array('data' => $params, 'meta' => $meta);
		} else {
			$auth = Auth::instance('Shopsuey_Stateless');

			$new_user = new Model_User();
			$new_user->password = $auth->hash_password($params['password']);
			$new_user->email = $params['email'];
			$current_user = $this->user_login->user;
			if (isset($params['group'])) {
				if ($current_user->can_edit_group_members($params['group'])) {
					$new_user->group = $params['group'];
				} else {
					$this->_error_response(Code::ERROR_INVALID_GROUP);
					return;
				}
			} else {
				$new_user->group = Model_User::GROUP_USER;
			}
			$new_user->status = isset($params['status']) ? $params['status'] : Model_User::STATUS_ACTIVE;

			$location_ids = Input::post('location_ids', array());
			if (!$new_user->is_admin() && $current_user->can_edit_group_members($new_user->group)) {
			    if (count($location_ids) == 0) {
			        $this->_error_response(Code::ERROR_INCOMPATIBLE_COMPANY);
			        return;
			    }
			    foreach($location_ids as $location_id) {
			        $location = Model_Location::find($location_id);
			        if ($location && $new_user->can_be_assigned_to_company($location)) {
			            $location_manager = new Model_Location_Manager();
			            $location_manager->location = $location;
			            $location_manager->include_merchants = isset($params["include_merchants_$location_id"]) && $params["include_merchants_$location_id"];
			            $new_user->location_managers[] = $location_manager;
			        } else {
			            $this->_error_response(Code::ERROR_INCOMPATIBLE_COMPANY);
			            return;
			        }
			    }
			}
			$allowed_meta_fields = Config::get('cms.user_meta_fields');
			$json_meta_fields = Config::get('cms.json_meta_fields');
				
			foreach($params as $key => $value) {
				if (in_array($key, $allowed_meta_fields)) {
					$meta = new Model_User_Metafield();
					$meta->key = $key;
					// TODO: Check if json encoding is really necessary for the dob
					$meta->value = in_array($key, $json_meta_fields) ? json_encode($value) : $value;
					$new_user->meta_fields[] = $meta; 
				}
			}

			$new_user->last_activity = time();

			if ($new_user->save()) { // Success
				if ($new_user->group <= Model_User::GROUP_USER) {
				    $template = 'email/newuser';
				    if (isset($params['status']) && $params['status'] == Model_User::STATUS_STEP1) {
				        $template = 'email/signup';
				    }
					// Email Template Data
					$email_data = array('username' => @$params['real_name'], 'email' => $params['email']);
					
					CMS::email($params['email'], null, 'Thanks for signing up for ShopSuey', $email_data, $template);
				}

				$data = array(
					'data' => array('user' => Helper_Api::user_response($new_user)),
					'meta' => array('error' => '', 'status' => 1)
				);
				
			} else {
				$meta = Helper_Api::build_error_meta(Code::ERROR_USER_SAVE_ERROR);
				$data = array('data' => $params, 'meta' => $meta);
			}
		}

		$this->response($data);
	}

	public function action_put() {
		if (Input::method() != 'PUT') { $this->response($this->no_access); return; }

		$user_id = $this->param('id');
		$user = Model_User::find($user_id);
		if (! $user) {
			$this->_error_response(Code::ERROR_INVALID_USER_ID);
			return;
		}

		$params = Input::put();

		if (! $this->_validate_user_params($params, $user)) {
			$meta = Helper_Api::build_error_meta($this->_invalid_error_code, array('parameter' => array('name' => $this->_invalid_field, 'value' => @$params[$this->_invalid_field])));
			$data = array('data' => $params, 'meta' => $meta);
		}
		else {
			$auth = Auth::instance('Shopsuey_Stateless');
			$current_user = $this->user_login->user;

			// Regular users can only change their password using the corresponding service
			if (isset($params['password']) && !empty($params['password']) && $current_user->can_edit_group_members($user->group)) {
				$user->password = $auth->hash_password($params['password']);
			}
			if (isset($params['email'])) {
				$user->email = $params['email'];
			}
			if (isset($params['group'])) {
				if ($current_user->id == $user->id || $current_user->can_edit_group_members($user->group) && $current_user->can_edit_group_members($params['group'])) {
					$user->group = $params['group'];
				} else {
					$this->_error_response(Code::ERROR_INVALID_GROUP);
					return;
				}
			} 
				
			if (isset($params['status'])) {
			    if ($current_user->id == $user_id && $current_user->is_regular_user()) {
			        // For regular users only this promotion is allowed
			        $user->status = Model_User::STATUS_STEP1;
			    } elseif ($current_user->can_edit_group_members($user->group)) {
			        $user->status = $params['status'];
			    }
			}

			$location_ids = Input::put('location_ids', array());
			$existing_location_ids = array_map(function($lm) { return $lm->location_id; }, $user->location_managers);
			$location_ids_to_add    = array_diff($location_ids, $existing_location_ids);
			$location_ids_to_remove = array_diff($existing_location_ids, $location_ids);

			if (!$user->is_admin() && $current_user->can_edit_group_members($user->group)) {
			    if (count($location_ids) == 0) {
			        $this->_error_response(Code::ERROR_INCOMPATIBLE_COMPANY);
			        return;
			    }
			    // Add new entries
			    foreach($location_ids_to_add as $location_id) {
			        $location = Model_Location::find($location_id);
			        if ($location && $user->can_be_assigned_to_company($location)) {
			            $location_manager = new Model_Location_Manager();
			            $location_manager->location = $location;
			            $user->location_managers[] = $location_manager;
			        } else {
			            $this->_error_response(Code::ERROR_INCOMPATIBLE_COMPANY);
			            return;
			        }
			    }
			    // Delete existing entries
			    foreach($user->location_managers as $location_manager) {
			        if (in_array($location_manager->location_id, $location_ids_to_remove)) {
			            unset($user->location_managers[$location_manager->id]);
			            $location_manager->delete();
			        }
			    }
			    // Update existing entries
			    foreach($user->location_managers as $location_manager) {
			        $location_id = $location_manager->location_id;
			        $location_manager->include_merchants = isset($params["include_merchants_$location_id"]) && $params["include_merchants_$location_id"];
			    }
			}

			$existing_meta_fields = array();
			foreach ($user->meta_fields as $index => $meta_field) {
				$existing_meta_fields[$meta_field->key] = $index;
			}
			$allowed_meta_fields = Config::get('cms.user_meta_fields');
			$json_meta_fields = Config::get('cms.json_meta_fields');
			foreach($params as $key => $value) {
				if (in_array($key, $allowed_meta_fields)) {
					if (isset($existing_meta_fields[$key])) {
						// Update an existing entry
						$meta = $user->meta_fields[$existing_meta_fields[$key]];
					} else {
						// Create a new entry
						$meta = new Model_User_Metafield();
						$meta->key = $key;
						$user->meta_fields[] = $meta;
					}
					// TODO: Check if json encoding is really necessary for the dob
					$meta->value = in_array($meta->key, $json_meta_fields) ? json_encode($value) : $value;
				}
			}
			
			if ($user->save()) {
				$data = array(
					'data' => array('user' => Helper_Api::user_response($user)),
					'meta' => array('error' => '', 'status' => 1)
				);
			
			} else {
				$meta = Helper_Api::build_error_meta(Code::ERROR_USER_SAVE_ERROR);
				$data = array('data' => $params, 'meta' => $meta);
			}
		}

		$this->response($data);
	}

	public function action_delete() {
		if (Input::method() != 'DELETE') { $this->response($this->no_access); return; }

		$user_id = $this->param('id');
		$user = Model_User::find($user_id);
		if (! $user) {
			$this->_error_response(Code::ERROR_INVALID_USER_ID);
			return;
		}

		if ($this->user_login->user_id == $user->id) {
			$this->_error_response(Code::ERROR_INVALID_USER_ID);
			return;
		}
		
		if (! $this->user_login->user->can_edit_group_members($user->group)) {
			$this->_error_response(Code::ERROR_ACCESS_DENIED);
			return;
		}

        $queries = array(
            DB::delete('checkins')->where('user_id', $user->id),
            DB::delete('contestants')->where('user_id', $user->id),
            DB::delete('eventlikes')->where('user_id', $user->id),
            DB::delete('eventrsvps')->where('user_id', $user->id),
            DB::delete('flags')->where('owner_id', $user->id),
            DB::delete('flagvotes')->where('user_id', $user->id),
            DB::delete('flag_invited_users')->where('user_id', $user->id),
            DB::delete('location_checkins')->where('user_id', $user->id),
            DB::delete('location_likes')->where('user_id', $user->id),
            DB::delete('location_managers')->where('user_id', $user->id),
            DB::delete('offerlikes')->where('user_id', $user->id),
            DB::delete('offer_redeems')->where('user_id', $user->id),
            DB::delete('subscriptions')->where('user_id', $user->id),
            DB::delete('users_favorite_locations')->where('user_id', $user->id),
            DB::delete('users_offers')->where('user_id', $user->id),
            DB::delete('user_activities')->where('user_id', $user->id),
            DB::delete('user_metafields')->where('user_id', $user->id),
            DB::delete('user_profilings')->where('user_id', $user->id),
            DB::delete('user_sessions')->where('user_id', $user->id),
        );

		$user->delete();
		
		if (is_null($user->id)) {
            foreach ($queries as $query) {
                $query->execute();
            }

			$data = array(
					'data' => array('status' => true),
					'meta' => array('error' => '', 'status' => 1)
			);
		} else {
			$meta = Helper_Api::build_error_meta(Code::ERROR_USER_DELETE);
			$data = array('data' => NULL, 'meta' => $meta);
		}

		$this->response($data);
	}

	/**
	 * @deprecated
	 */
	public function action_reset() {
		if (Input::method() != 'POST') { $this->response($this->no_access); return; }

		$hash = Input::post('hash');
		$user_reset = Model_User_Reset::query()->where('hash', $hash)->where('used', FALSE)->where('expiracy', '>', date('Y-m-d H:i:s'))->get_one();
		
		if (!$user_reset) {
			$this->_error_response(Code::ERROR_RESET_INVALID);
			return;
		}
		
		$auth = Auth::instance('Shopsuey_Stateless');

		$user = $user_reset->user;
		$new_password = CMS::generate_random_string(8);
		$user->password = $auth->hash_password($new_password);
		$user_reset->used = 1;

		if ($user_reset->save()) {
			$email_data = array('password' => $new_password, 'username' => $user->email, 'email' => $user->email);
			$data = (array) CMS::email($user->email, null, 'ShopSuey :: Password Reset', $email_data, 'email/reset');
			unset($data['data']['password']);
			$this->response($data);
		} else {
			$this->_error_response(Code::ERROR_USER_SAVE_ERROR);
		}
	}

	public function action_image_upload() {
		if (Input::method() != 'POST') { $this->response($this->no_access); return; }

		// The save feature of Upload won't be used, just the validation of the upload
		$config = array(
			'ext_whitelist' => array('jpg', 'jpeg', 'gif', 'png'),
			'type_whitelist' => array('image'),
		);
		
		// Check the uploads
		Upload::process($config);
		
		if (!Upload::is_valid()) {
			$files = Upload::get_errors();
			$errors = array();
			if (count($files) > 0) {
				$file = array_shift($files);
				foreach($file['errors'] as $error) {
					$errors[] = isset($error['message']) ? $error['message'] : 'There was an error while uploading the file';
				}
			} else {
				$errors[] = 'No uploaded files';
			}
			$this->_error_response(Code::ERROR_IMAGE_UPLOAD, array('messages' => implode(' / ', $errors)));
			return;
		}

		// Get the first uploaded file
		$file = Upload::get_files(0);
        $file['content'] = base64_encode(file_get_contents($file['file']));
        
        $file_name = Helper_Images_Users::copy_one_image_from_params($file);
        $image_uri = Asset::get_file('large_' . $file_name, 'img', Config::get('cms.user_images_path'));
		
		// Store the image filename on the user metadata
		if (! Helper_Api::set_user_meta($this->user_login->user, 'image', $file_name)) {
			$this->_error_response(Code::ERROR_USER_SAVE_ERROR);
			return;
		}

		$data = array('data' => array('image' => $image_uri), 'meta' => array('status' => 1, 'error' => null));
		$this->response($data);
	}

	public function action_image_delete() {
		if (Input::method() != 'POST') { $this->response($this->no_access); return; }

		// Check if the user has an image
		$image_uri = Helper_Api::get_user_meta($this->user_login->user, 'image');

		if (is_null($image_uri)) {
			$this->_error_response(Code::ERROR_MISSING_IMAGE);
			return;
		}

		if (Helper_Api::delete_user_meta($this->user_login->user, 'image')) {
			$data = array('data' => array('success' => true), 'meta' => array('status' => 1, 'error' => null));
			$this->response($data);
		} else {
			$this->_error_response(Code::ERROR_IMAGE_DELETE);
		}
		
	}
	
	public function action_get_profiling_choices() {
	    if (Input::method() != 'GET') { $this->response($this->no_access); return; }
	    
	    $profiling_choice = new Model_Profilingchoice();
	    $profiling_choices = $profiling_choice->find('all', array(
            'where' => array(
                array('deleted', 0)
            ),
            'order_by' => array('order' => 'asc')
        ));
	    
	    if (!empty($profiling_choices)) {
	        $profiling_choices_objects = array();
	        foreach ($profiling_choices as $profiling_choice) {
	            $profiling_choices_objects[] = Helper_Api::model_to_real_object($profiling_choice);
	        }
	        
            $data = array(
                'data' => array('images' => $profiling_choices_objects),
                'meta' => array('error' => '', 'status' => 1)
            );
            $this->response($data);
        } else {
            $this->_error_response(Code::ERROR_GETTING_PROFILING_CHOICES);
        }
	}
	
        
        
	public function action_update_profiling() {
	    if (Input::method() != 'POST') { $this->response($this->no_access); return; }
	     
	    $profilingChoicesIdsString = Input::post('profiling_choices_ids', '');
	    $profilingChoicesIds = explode(',', $profilingChoicesIdsString);
	    $current_user = $this->user_login->user;
	    $current_user->profilingchoices = array();
	    
	    if (!empty($profilingChoicesIdsString)) {
                foreach ($profilingChoicesIds as $profilingChoicesId) {
                    $current_user->profilingchoices[] = Model_Profilingchoice::find($profilingChoicesId);
                }
	    }
	    
	    if ($current_user->save()) {
                
                //FAVORITE ALL LOCATIONS ASSOCIATED WITH THE PROFILING CHOICES
                
                foreach ($current_user->profilingchoices as $profilingChoice){
                    $profilingChoice->favorite_locations_by_single_user($current_user);
                }
                
                $data = array(
	            'data' => array('status' => true),
	            'meta' => array('error' => '', 'status' => 1)
	        );
	        $this->response($data);
	    } else {
	        $this->_error_response(Code::ERROR_UPDATING_PROFILINGS);
	    }
	}
	
	public function action_get_profiling() {
	    if (Input::method() != 'GET') { $this->response($this->no_access); return; }
	    
	    $current_user = $this->user_login->user;
	    $profiling_choices = $current_user->profilingchoices;
	    
	    if (!empty($profiling_choices)) {
	        $profiling_choices_objects = array();
	        foreach ($profiling_choices as $profiling_choice) {
	            $profiling_choices_objects[] = Helper_Api::model_to_real_object($profiling_choice);
	        }
	         
	        $data = array(
	            'data' => array('images' => $profiling_choices_objects),
	            'meta' => array('error' => '', 'status' => 1)
	        );
	        $this->response($data);
	    } else {
	        $this->_error_response(Code::ERROR_GETTING_PROFILING);
	    }
	}
    
    public function action_set_apn_token() {
        if (Input::method() != 'POST') { $this->response($this->no_access); return; }
        $params = Input::post();
        
        if (!$params['token'] || !preg_match('/^[a-f0-9]{64}$/', $params['token'])) {
            $this->_error_response(Code::ERROR_INVALID_APN_TOKEN);
            return;
        }
        
        $current_user = $this->user_login->user;
        $current_user->apn_token = $params['token'];
        
        if (isset($params['bundleid'])) {
            $current_user->apn_bundle = $params['bundleid'];
        }
        
        if (isset($params['environment'])) {
            $current_user->apn_env = $params['environment'];
        }
        
        $current_user->save();
        
        if ($current_user->save()) {
	        $data = array(
	            'data' => array('status' => true),
	            'meta' => array('error' => '', 'status' => 1)
	        );
	        $this->response($data);
	    } else {
	        $this->_error_response(Code::ERROR_UPDATING_APN_TOKEN);
	    }
    }
    
    public function action_follow() {
        if (Input::method() != 'POST') { $this->response($this->no_access); return; }

        $params = Input::post();
        $followeeid = $params['user_id'];

        $current_user = $this->user_login->user;

        $followee = Model_User::find($followeeid);
        if (is_null($followee)) {
            return $this->_error_response(Code::ERROR_INVALID_FOLLOW_ID);
        }

        $userfollow = Model_Userfollow::query()
            ->where('follower_id', $current_user->id)
            ->where('followee_id', $followee->id)
            ->get_one();

        if ($userfollow) {
            return $this->_error_response(Code::ERROR_ALREADY_FOLLOWING);
        }

        $userfollow = new Model_Userfollow();
        $userfollow->follower_id = $current_user->id;
        $userfollow->followee_id = $followeeid;
        if ($userfollow->save()) {
            $data = array(
                'data' => array('status' => true),
                'meta' => array('error' => '', 'status' => 1),
            );
            
            // send apn in order to notify you are being followed
            
            if ($followee->apn_token) {
                $text = $current_user->get_friendly_name() . " is now following you!";
                $custom_properties = array('follower_id' => $current_user->id);
                Helper_Apn::send_notification($followee, $text, $custom_properties);
            }

            Helper_Activity::log_activity($current_user, 'follow_user', array('user_id' => (int)$followeeid));
            
            $this->response($data);
        } else {
            $this->_error_response(Code::ERROR_PERSIST_FOLLOWING);
        }
    }

    public function action_unfollow() {
        if (Input::method() != 'POST') { $this->response($this->no_access); return; }

        $params = Input::post();
        $followeeid = $params['user_id'];

        $current_user = $this->user_login->user;

        $followee = Model_User::find($followeeid);
        if (is_null($followee)) {
            return $this->_error_response(Code::ERROR_INVALID_FOLLOW_ID);
        }

        $userfollow = Model_Userfollow::query()
            ->where('follower_id', $current_user->id)
            ->where('followee_id', $followee->id)
            ->get_one();

        if (!$userfollow) {
            return $this->_error_response(Code::ERROR_NOT_FOLLOWING);
        }

        if ($userfollow->delete()) {
            $data = array(
                'data' => array('status' => true),
                'meta' => array('error' => '', 'status' => 1),
            );

            Helper_Activity::log_activity($current_user, 'unfollow_user', array('user_id' => (int)$followeeid));

            $this->response($data);
        } else {
            $this->_error_response(Code::ERROR_PERSIST_UNFOLLOWING);
        }
    }

    public function action_following() {
        if (Input::method() != 'GET') { $this->response($this->no_access); return; }

        $current_user = $this->user_login->user;
        $user_id = $this->param('id', 0);
		$user = Model_User::find($user_id);
        
        if ($user) {
            $parameters = Input::get();
            $keyword    = (isset($parameters['keyword'])) ? $parameters['keyword'] : null;
            $resopnse   = $this->_format_user_response('following', $user->get_following(), $current_user, $keyword);
            $this->response($resopnse);
            
        } else {
            $this->_error_response(Code::ERROR_INVALID_USER_ID);
        }
    }

    public function action_followers() {
        if (Input::method() != 'GET') { $this->response($this->no_access); return; }

        $current_user = $this->user_login->user;
        $user_id = $this->param('id', 0);
		$user = Model_User::find($user_id);
        
        if ($user) {
            $parameters = Input::get();
            $keyword    = (isset($parameters['keyword'])) ? $parameters['keyword'] : null;
            $response   = $this->_format_user_response('followers', $user->get_followers(), $current_user, $keyword);
            $this->response($response);
            
        } else {
            $this->_error_response(Code::ERROR_INVALID_USER_ID);
        }
    }
    
    private function _format_user_response($key, $users, $current_user, $keyword = null) {
        $user_array = array();
        
        foreach ($users as $user) {
            $email = $user->email;
            $lname = $user->get_meta_field_value('lname');
            $fname = $user->get_meta_field_value('fname');
            $rname = $user->get_meta_field_value('real_name');

            if ((is_null($keyword) || empty($keyword)) || (stripos($rname, $keyword) !== false || stripos($email, $keyword) !== false || stripos($lname, $keyword) !== false || stripos($fname, $keyword) !== false)) {
                $user_key = strtolower($fname . $lname . $user->email);
                $user_array[$user_key] = Helper_Api::user_standard_response($user, $current_user, false);
            }
        }
        ksort($user_array);
        $user_array = array_values($user_array);

        $count = count($user_array);
        $meta = array('pagination' => $this->_pagination($count, Input::param('page', 1)));
        $meta['status'] = 1;
        $meta['error']  = null;

        $limit  = $meta['pagination']['limit'];
        $offset = $meta['pagination']['offset']['current'];

        $users_for_output = array_slice($user_array, $offset, $limit);

        return array(
            'data' => array($key => $users_for_output),
            'meta' => $meta,
        );
    }
    
    public function action_get_votes() {
        if (Input::method() != 'GET') { $this->response($this->no_access); return; }
        
        $user_id = $this->param('id', 0);
		$user = Model_User::find($user_id);
        
        $parameters  = Input::get();
        $like_status = (isset($parameters['like_status']) && in_array($parameters['like_status'], array(1, -1))) ? $parameters['like_status'] : null;
        
        if ($user) {

            // offers
            $offers = array();
            foreach ($user->offerlikes as $offer_like) {
                $valid_status = (is_null($like_status)) ? $offer_like->status != 0 : $offer_like->status == $like_status; 
                
                $isActive = true;
                foreach ($offer_like->offer->locations as $offer_location){
                    if ($offer_location->status != Model_Location::STATUS_ACTIVE) $isActive = false;
                }
                if (!$isActive) continue;
                
                if ($valid_status && $offer_like->offer->is_active()) {
                    $offers[] = array(
                        'status' => $offer_like->status,
                        'offer'  => Helper_Api::offer_response($offer_like->offer),
                    );
                }
            }

            // regular events
            $events = array();
            foreach ($user->eventlikes as $event_like) {
                $valid_status = (is_null($like_status)) ? $event_like->status != 0 : $event_like->status == $like_status; 
                
                $isActive = true;
                foreach ($event_like->event->locations as $event_location){
                    if ($event_location->status != Model_Location::STATUS_ACTIVE) $isActive = false;
                }
                if (!$isActive) continue;
                
                if ($valid_status && $event_like->event->is_active()) {
                    $events[] = array(
                        'status' => $event_like->status,
                        'event'  => Helper_Api::event_response($event_like->event),
                    );
                }

            }

            // special events
            foreach ($user->specialeventlikes as $event_like) {
                $valid_status = (is_null($like_status)) ? $event_like->status != 0 : $event_like->status == $like_status;
            
                $isActive = true;
                foreach ($event_like->specialevent->locations as $event_location){
                    if ($event_location->status != Model_Location::STATUS_ACTIVE) $isActive = false;
                }
                if (!$isActive) continue;
                
                if ($valid_status && $event_like->specialevent->is_active()) {
                    $events[] = array(
                        'status' => $event_like->status,
                        'event'  => Helper_Api::event_response($event_like->specialevent),
                    );
                }
            }

            // flags
            $flags = array();
            foreach ($user->flagvotes as $flag_vote) {
                $valid_status = (is_null($like_status)) ? $flag_vote->status != 0 : $flag_vote->status == $like_status;

                if ($flag_vote->flag->location->status != Model_Location::STATUS_ACTIVE) continue;
                
                if ($valid_status) {
                    $flags[] = array(
                        'status' => $flag_vote->status,
                        'flag'  => Helper_Api::flag_response($flag_vote->flag, $user),
                    );
                }
            }

            $data = array(
                'data' => array('status' => true, 'events' => $events, 'offers' => $offers, 'flags' => $flags),
                'meta' => array('error' => '', 'status' => 1)
            );
            $this->response($data);
            
        } else {
            $this->_error_response(Code::ERROR_INVALID_USER_ID);
        }
    }
	
    public function action_get_favorites() {
        if (Input::method() != 'GET') { $this->response($this->no_access); return; }
        
        $user_id = $this->param('id', 0);
		$user = Model_User::find($user_id);
        
        $parameters = Input::get();
        
        if ($user) {
            
            $location_query = Model_Location::query()
                ->related('favorited_users')
                ->where('favorited_users.id', '=', $user_id)
                ->order_by('name', 'ASC');
            
            if ((isset($parameters['malls_only']) && $parameters['malls_only'])) {
                $location_query->and_where_open()->where('type', '=', Model_Location::TYPE_MALL)->and_where_close();
            } else if (isset($parameters['merchants_only']) && $parameters['merchants_only']) {
                $location_query->and_where_open()->where('type', '=', Model_Location::TYPE_MERCHANT)->and_where_close();
            }

            if (isset($parameters['keyword']) && !empty($parameters['keyword'])) {
                $search_fields = array('name', 'address', 'city', 'st', 'zip', 'email', 'web', 'description', 'tags');
                $location_query->and_where_open();
                foreach ($search_fields as $field) {
                    $location_query->or_where($field, 'like', "%{$parameters['keyword']}%");
                }
                $location_query->and_where_close();
            }
            
            $locations = $location_query->get();
            
            $formattedLocations = array();
            foreach ($locations as $location) {
                $formattedLocations[] = Helper_Api::location_response($location);               
            }
            
            $data = array(
                'data' => array('status' => true, 'locations' => $formattedLocations),
                'meta' => array('error' => '', 'status' => 1)
            );
            $this->response($data);
            
        } else {
            $this->_error_response(Code::ERROR_INVALID_USER_ID);
        }
    }
    
    public function action_get_details() {
        if (Input::method() != 'GET') { $this->response($this->no_access); return; }
        
        $user_id = $this->param('id', 0);
        $user = Model_User::find($user_id);
        
        if ($user) {
            $current_user = $this->user_login->user;
            $data = Helper_Api::user_standard_response($user, $current_user);

            $data->stats = $user->get_stats();
            
            $data = array(
                'data' => $data,
                'meta' => array('error' => '', 'status' => 1)
            );
            $this->response($data);
        } else {
            $this->_error_response(Code::ERROR_INVALID_USER_ID);
        }
    }

    public function action_get_events() {
        if (Input::method() != 'GET') { $this->response($this->no_access); return; }
        
        $user_id = $this->param('id', 0);
		$user = Model_User::find($user_id);
        
        if ($user) {
            $events = array();
            
            $eventsRsvp = DB::query("SELECT * FROM `suey_eventrsvps`, `suey_events`, `suey_events_locations`, `suey_locations` "
                    . "WHERE "
                    . "suey_eventrsvps.event_id = suey_events.id AND "
                    . "suey_eventrsvps.user_id = :user_id AND "
                    . "suey_events.id = suey_events_locations.event_id AND "
                    . "suey_events_locations.location_id = suey_locations.id AND "
                    . "suey_locations.status = 1 "
                    . "ORDER BY suey_events.title ASC")->bind('user_id', $user_id)->execute();
        
            $specialEventsRsvp = DB::query("SELECT * FROM `suey_specialeventrsvps`, `suey_specialevents`, `suey_locations_specialevents`, `suey_locations` "
                    . "WHERE "
                    . "suey_specialeventrsvps.specialevent_id = suey_specialevents.id AND "
                    . "suey_specialeventrsvps.user_id = :user_id AND "
                    . "suey_specialevents.id = suey_locations_specialevents.specialevent_id AND "
                    . "suey_locations_specialevents.location_id = suey_locations.id AND "
                    . "suey_locations.status = 1 "
                    . "ORDER BY suey_specialevents.title ASC")->bind('user_id', $user_id)->execute();
        
            foreach ($eventsRsvp as $eventRsvp) {
                if ($eventRsvp->event->is_active()) {
                    $events[] = Helper_Api::event_response($eventRsvp->event);
                }
            }
            
            foreach ($specialEventsRsvp as $eventRsvp) {
                if ($eventRsvp->specialevent->is_active()) {
                    $events[] = Helper_Api::event_response($eventRsvp->specialevent);
                }
            }
            
            $data = array(
                'data' => array('events' => $events),
                'meta' => array('error'  => '', 'status' => 1)
            );
            $this->response($data);
            
        } else {
            $this->_error_response(Code::ERROR_INVALID_USER_ID);
        }
    }
    
    
    public function action_get_offers() {
        if (Input::method() != 'GET') { $this->response($this->no_access); return; }
        
        $user_id = $this->param('id', 0);
		$user = Model_User::find($user_id);
        
        
        if ($user) {
            $parameters    = Input::get();
            $saved_only    = (isset($parameters['saved_only']))    ? true : false;
            $redeemed_only = (isset($parameters['redeemed_only'])) ? true : false;
            $user_offers   = array();
            
            if (!$saved_only && !$redeemed_only) {
                return $this->_error_response(Code::ERROR_NO_OFFER_MODE_SPECIFIED);
                
            } else if ($saved_only) {
                $user_offers = Model_Offer::query()
                    ->related('users')
                    ->related('locations')
                    ->where('users.id', '=', $user_id)
                    ->where('locations.status', '=', Model_Location::STATUS_ACTIVE)
                    ->order_by('name', 'ASC')->get();
                
            } else {
                /* Ordered in php for db performance reasons */
                foreach ($user->offer_redeems as $offer_redeem) {
                    
                    $isActive = true;
                    foreach ($offer_redeem->offer_code->offer->locations as $offer_location){
                        if ($offer_location->status != Model_Location::STATUS_ACTIVE) $isActive = false;
                    }
                    if (!$isActive) continue;
                    
                    $user_offers[$offer_redeem->offer_code->offer->name . $offer_redeem->offer_code->offer->id] = $offer_redeem->offer_code->offer;
                }
                ksort($user_offers);
                $user_offers = array_values($user_offers);
            }
            
            $formatted_offers = array();
            foreach ($user_offers as $offer) {
                
                if ($offer->is_active()) {
                    $formatted_offers[] = Helper_Api::offer_response($offer);
                }
            }

            $data = array(
                'data' => array('offers' => $formatted_offers),
                'meta' => array('error'  => '', 'status' => 1)
            );
            $this->response($data);
        } else {
            $this->_error_response(Code::ERROR_INVALID_USER_ID);
        }
    }
            
    public function action_users_list() {
        if (Input::method() != 'GET') { $this->response($this->no_access); return; }
    
        $params = Input::get();
        $current_user = $this->user_login->user;

        $nearby_ids = array();
        $nearby_flag = isset($params['nearby']) && $params['nearby'];
        $location_trackings = array();
    
        // If this has bad performance... I didn't do it! (?)
        $query = DB::select('users.id', 'users.email', 'users.fbuid', array('image.value', 'image'), 
                            array('name.value', 'name'), array('lname.value', 'lname'), array('fname.value', 'fname'))
            ->from('users')
            ->join(array('user_metafields', 'image'), 'left')
            ->on('users.id', '=', 'image.user_id')->on('image.key', '=', "'image'")
            ->join(array('user_metafields', 'name'), 'left')
            ->on('users.id', '=', 'name.user_id')->on('name.key', '=', "'real_name'")
            ->join(array('user_metafields', 'lname'), 'left')
            ->on('users.id', '=', 'lname.user_id')->on('lname.key', '=', "'lname'")
            ->join(array('user_metafields', 'fname'), 'left')
            ->on('users.id', '=', 'fname.user_id')->on('fname.key', '=', "'fname'")
            ->where('status', Model_User::STATUS_ACTIVE)
            ->where('users.id', '<>', $current_user->id);
    
        if ($nearby_flag && isset($params['latitude']) && isset($params['longitude'])) {
            $time = Input::get('time', time());
    
        	$from_time = strtotime('-' . \Config::get('cms.nearby_users_time_frame'), $time);
        	$to_time   = strtotime('+' . \Config::get('cms.nearby_users_time_frame'), $time);
        
        	$radius = isset($params['radius'])
        	    ? $params['radius']
        	    : Input::get('radius', \Config::get('cms.nearby_users_radius'));
        	$accuracy = Input::get('accuracy', \Config::get('cms.nearby_users_accuracy'));

        	$origin_point = Geo::build_coordinates($params['latitude'], $params['longitude']);
        	// Calculate the coordinates of the edges of the rectangle
        	list ($upper_left_point, $lower_right_point) = Geo::get_rectangle_coordinates($origin_point, $radius);

        	$location_trackings = Model_Location_Tracking::get_nearby_users(
                    $upper_left_point,
                    $lower_right_point,
        	        $accuracy,
                    $from_time,
                    $to_time
            );
        	
        	$nearby_ids = array_map(function ($arr) { return $arr['user_id']; }, $location_trackings);
        }
        
        $following_flag = isset($params['following']) && $params['following'];
        $followers_flag = isset($params['followers']) && $params['followers'];
        
        if ($following_flag) {
            $query->join(array('user_follows', 'following'))
                ->on('users.id', '=', 'following.followee_id');
            $query->where('following.follower_id', $current_user->id);
        }
        
        if ($followers_flag) {
            $query->join(array('user_follows', 'followers'))
                ->on('users.id', '=', 'followers.follower_id');
            $query->where('followers.followee_id', $current_user->id);
        }
        
        if (!empty($nearby_ids)) {
            $query->where('users.id', 'in', $nearby_ids);
        }
        
        if (isset($params['name'])) {
            $query->where('name.value', 'LIKE', '%'.$params['name'].'%');
        }
        
        if (isset($params['fbuid'])) {
            $fbuids = explode(',', $params['fbuid']);
            $query->where('users.fbuid', 'in', $fbuids);
        }
        
        if (isset($params['email'])) {
            $emails = explode(',', $params['email']);
            $query->where('users.email', 'in', $emails);
        }
        
        $users = $query->group_by('users.id')->order_by('users.last_activity', 'desc')->execute()->as_array();

        foreach ($users as $key => $user) {
            $user_name  = (isset($user['name']) && !empty($user['name'])) ? $user['name'] : trim($user['fname'] . ' ' . $user['lname']);
            
            $file      = Asset::get_file('large_' . $user['image'], 'img', Config::get('cms.user_images_path'));
            $image_uri = $file != false ? $user['image'] : '';
            
            $empty_user = !$user['fbuid'] && !$user_name && !$image_uri;
            $keyword    = isset($params['keyword']) ? $params['keyword'] : '';
            
            if (!empty($keyword) && stripos($users[$key]['name'], $keyword) === false && stripos($users[$key]['email'], $keyword) === false) {
                unset($users[$key]);
            } else if (($empty_user && empty($keyword)) || ($empty_user && !empty($keyword) && stripos($users[$key]['email'], $keyword) === false)) {
                unset($users[$key]);
            }
        }
    
        if (isset($params['paging']) && $params['paging']) {
            $page = isset($params['page']) ? $params['page'] : 1;
            
            $count = sizeof($users);
            $meta = array(
                'pagination' => $this->_pagination($count, $page),
                'status' => 1,
                'error' => null,
            );
            ksort($meta);
            
            $users = array_slice(
                $users,
                $meta['pagination']['offset']['current'],
                $meta['pagination']['limit']
            );
        }
        
        //Adding data to the response
        $following_ids = array_map(function ($obj) { return $obj->followee_id; }, $current_user->following);
        $followers_ids = array_map(function ($obj) { return $obj->follower_id; }, $current_user->followers);

        foreach ($users as $key => $user) {
            $users[$key]['image'] = Asset::get_file('large_'.$users[$key]['image'], 'img', Config::get('cms.user_images_path'));
            $users[$key]['image'] = $users[$key]['image'] != false ? $users[$key]['image'] : '';
            
            if (!empty($users[$key]['name'])) {
                $users[$key]['name'] =  $users[$key]['name'];
            } else {
                $users[$key]['name'] =  $users[$key]['fname'] . ' ' . $users[$key]['lname'];
            }
            unset($users[$key]['fname']);
            unset($users[$key]['lname']);
            
            $users[$key]['fbuid'] = $users[$key]['fbuid'] != null ? $users[$key]['fbuid'] : '';
            $users[$key]['following'] = in_array($user['id'], $following_ids) ? true : false;
            $users[$key]['follower'] = in_array($user['id'], $followers_ids) ? true : false;
            
            if (!empty($location_trackings)) {
                foreach ($location_trackings as $location_tracking) {
                    $users[$key]['online']  = $location_tracking['created_at'] > time() - self::ONLINE_TIME_WINDOW;
                    $users[$key]['latitude']  = $location_tracking['latitude'];
                    $users[$key]['longitude']  = $location_tracking['longitude'];
                }
            }
        }
    
        $data = array('data' => array('users' => $users));
        if (isset($meta)) {
            $data['meta'] = $meta;
        }
        $this->response($data);
    }
    
	private function _validate_user_params($params, $existing_user = NULL) {
		if (is_null($existing_user)) {
			if (!isset($params['email']) || empty($params['email']) || !isset($params['password']) || empty($params['password'])) {
				$this->_invalid_field = 'email';
				$this->_invalid_error_code = Code::ERROR_EMAIL_AND_PASSWORD_REQUIRED;
				return FALSE;
			}
		}

		if (isset($params['email'])) {
			if (!CMS::valid_email($params['email'])) {
				$this->_invalid_field = 'email';
				$this->_invalid_error_code = Code::ERROR_INVALID_EMAIL;
				return FALSE;
			}

			$user_query = Model_User::query()->where('email', $params['email']);
			if (! is_null($existing_user)) {
				$user_query->where('email', '<>', $existing_user->email);
			}
			$user = $user_query->get_one();
			if ($user) {
				$this->_invalid_field = 'email';
				$this->_invalid_error_code = Code::ERROR_EMAIL_ALREADY_IN_USE;
				return FALSE;
			}
		}

		if (isset($params['password'])) {
			if (!CMS::valid_password($params['password'])) {
				$this->_invalid_field = 'password';
				$this->_invalid_error_code = Code::ERROR_INVALID_PASSWORD;
			}
		}

		return TRUE;
	}
}
