<?php

/**
 * The User Controller.
 * This controllers the CRUD proceedures for the CMS users section
 *
 * @package  app
 * @extends  Controller_Cms
 */

class Controller_Dashboard_user extends Controller_Cms {
	private $test = true;

	public function action_add($action = 'add') {
		if ($action == 'edit') {
			$id = $this->param('id');
			if (!$id || !($user = CMS::user($id))) {
				return $this->error_404();
			}
		} else {
			$user = new stdClass();
			$user->location_managers = array();
		}
		
		$user->location_ids = array();
		$user->include_merchants_location_ids = array();
		foreach ($user->location_managers as $location_manager) {
		    $user->location_ids[] = $location_manager->location_id;
		    if ($location_manager->include_merchants) {
		        $user->include_merchants_location_ids[] = $location_manager->location_id;
		    }
		}

		if (Input::post()) {
			// verify nonce
			$nonce = Input::post('nonce');
			$is_nonce = CMS::verify_nonce('user_'.$action, $nonce);
			if (!$is_nonce) { return $this->error_403(); }

			if ($action == 'edit' && Input::param('delete')) {
				return $this->user_delete($user);
			} elseif ($action == 'edit') {
				return $this->user_update($user);
			} else {
				return $this->user_add();
			}

		} else {
			if ($action == 'edit' && $this->user_login->user_id != $user->id) {
				if (CMS::has_access('dashboard/user.index')) {
					if ($action == 'edit' && !$this->user_login->user->can_edit_group_members($user->group)) {
						return $this->error_403();
					}
				}
			}

			$content_data = $this->_build_content_data($action, $this->user_login->user, $user);

			$content = View::forge('cms/user/edit', $content_data);
		}

		return $this->form_edit($action, $content, $user);
	}

	public function action_edit() { return $this->action_add('edit'); }

	public function action_index() {

		$page      = $this->param('page', 1);
		$string    = $this->param('string', Input::param('string', ''));
        $app_users = (bool)\Fuel\Core\Input::get('app_users', 0);
                
        $this->api->setData(array('string' => $string, 'app_users' => $app_users));
		$this->api->setMethod('GET');
		$this->api->setURL(Uri::create("api/users/$page/"));

		$output = $this->api->execute();

		if ($output) {
			if (!empty($string)) {
				$results = $output->meta->pagination->records;
				if ($results) {
					if ($results > 1) { $title = $results.' search results for '.$string; }
					else { $title = $results.' search result for '.$string; }
				}
				else { $title = 'No results for '.$string; }
			}
			else { $title = $output->meta->pagination->records . ' Users'; }

			// Set content params
			$content_data = array(
				'users'      => $output->data->users,
				'me'         => $this->user_login->user,
				'pagination' => $output->meta->pagination,
				'search'     => $string,
                'app_users'  => $app_users,
				'title'      => $title
            );

			$content = View::forge('cms/user/list', $content_data);
		} else {
			$content = '';
			$this->msg = array('type' => 'fail', 'message' => 'Error: 900 - Unable to process your request');
		}

		// Include .js
		$apnd = array('files/base.js', 'files/users.js');
		$excl = array('autotab', 'cleditor', 'dualist', 'elfinder', 'fullcalendar', 'flot', 'maskedinput', 'tagsinput', 'upload', 'wizards');
		$scripts = CMS::scripts($apnd, NULL, $excl);

		// Set header params
		$header_data = array(
			'style'   => 'styles.css',
			'scripts' => $scripts,
			'ie'      => 'ie.css'
		);

		$wrapper_data = array(
			'page' => array(
				'name'   => 'User Management',
				'subnav' => View::forge('cms/user/menu', array(
                    'app_users' => $app_users,
                    'me'        => $this->user_login->user,
                    'in_list'   => true
                )),
				'icon'   => 'icon-users'
            ),

			'crumbs' => array(
				array('title'=>'Dashboard', 'link' => Uri::create('dashboard')),
				array('title'=>'Users', 'link' => Uri::create('dashboard/users'))
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

	public function action_instagram_code() {
	    $user = $this->user_login->user;
	    
	    if (!$user) {
	    	$msg = array('type' => 'warning', 'message' => 'You have to be authenticated in order to do this.');
		    return $this->instagram_code_response($msg, \Uri::create('login'));
	    }

		// Get code
		$code = \Input::get('code');
		$state = \Input::get('state');
	
		if (is_null($code)) {
			$error_description = \Input::get('error_description', 'An error occurred while authenticating with Instagram');
			$msg = array('type' => 'fail', 'message' => $error_description, 'field' => NULL);
			return $this->instagram_code_response($msg, $state);
		}

		\Package::load('instagram');
		try {
			$response = \Instagram\Api::get_access_token($code);
		} catch(\Instagram\Exception $e) {
			$msg = array('type' => 'fail', 'message' => $e->getMessage(), 'field' => NULL);
		    return $this->instagram_code_response($msg, $state);
		}

		if (!$user->instagram) {
		    $user->instagram = new Model_User_Instagram();
		}
		
		$user->instagram->access_token = $response->access_token;
		$user->instagram->username = $response->username;
		$user->instagram->instagram_user_id = $response->user_id;		

		if ($user->save(NULL, TRUE)) {
		    $msg = array('type' => 'success', 'message' => 'Successfully configured Instagram account', 'autohide' => true);
		} else {
		    $msg = array('type' => 'fail', 'message' => 'Error while saving user information', 'field' => NULL);
		}	

		return $this->instagram_code_response($msg, $state);
	}

	private function instagram_code_response($msg, $url) {
		Session::set('message', $msg);
		return Response::redirect($url);
	}
	
	private function form_edit($action, $content, $user = null) {
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
				'name' => ($action == 'add') ? 'User Creator' : 'User Editor',
				'subnav' => View::forge('cms/user/menu'),
				'icon' => 'icon-contact'),

			'crumbs' => array(
				array('title'=>'Dashboard', 'link' => Uri::create('dashboard')),
				array('title'=>'Users', 'link' => Uri::create('dashboard/users')),
				array('title'=>($action == 'edit') ? $user->email : ucwords($action), 'link' => '#'),
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

	private function user_add() {
		$err = null;
		$post = Input::post();
		$include_merchant_ids = $this->get_posted_include_merchant_ids();
		
		if ($err) {
			$this->msg = $err;

			$post['include_merchants_location_ids'] = $include_merchant_ids;
			$content_data = $this->_build_content_data('add', $this->user_login->user, $post);

			$content = View::forge('cms/user/edit', $content_data);
            
			return $this->form_edit('add', $content);
		} else {
			$data = array_merge($post, $post['meta']);
			unset($data['meta']);

			$url = Uri::create('api/user/');

			$this->api->setData($data);
			$this->api->setMethod('POST');
			$this->api->setURL($url);

			$output = $this->api->execute();

			if ($output->meta->status == 1) {
				$user = $output->data->user;
				$msg = array('type' => 'success', 'message' => 'Successfully added user '.$user->email, 'autohide' => true);
				Session::set('message', $msg);
				return Response::redirect("dashboard/user/{$user->id}/edit");
			} else {
				$err = $output->meta->error;
                
                if ($output->meta->error_code == Code::ERROR_EMAIL_ALREADY_IN_USE) {
                    // perhaps trying to transform a regular user into an admin?
                    $user = Model_User::query()->where('email', $post['email'])->get_one();
                    if ($user->group == Model_User::GROUP_USER && $this->user_login->user->can_edit_group_members($post['group'])) {
                        $user->group = $post['group'];
                        $user->save();
                        $message = 'User with email ' . $user->email . ' already existed as a regular App user and was promoted to ' . Model_User::get_group_name($user->group) . '. No further changes were made, just to be sure. Please make the necessary changes now and save them.';
                        $msg = array('type' => 'success', 'message' => $message, 'autohide' => false);
                        Session::set('message', $msg);
                        return Response::redirect("dashboard/user/{$user->id}/edit");
                    }
                }
                
				if (!$err) { $err = 'Error: 900 - Unable to process request'; }
				$this->msg = array('type' => 'fail', 'message' => $err);

			    $post['include_merchants_location_ids'] = $include_merchant_ids;
				// Set content params
				$content_data = $this->_build_content_data('add', $this->user_login->user, $post);
				
				$content = View::forge('cms/user/edit', $content_data);
                
				return $this->form_edit('add', $content);
			}
		}
	}

	private function user_update($user) {
		$post = Input::post();
		$include_merchant_ids = $this->get_posted_include_merchant_ids();
		
		$data = array_merge($post, $post['meta']);
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
			return Response::redirect("dashboard/user/{$user->id}/edit");
		} else {
			$err = $output->meta->error;
			if (!$err) { $err = 'Error: 900 - Unable to process request'; }
			$this->msg = array('type' => 'fail', 'message' => $err);
		
			// Set content params
			$post['id'] = $user->id;
			$post['include_merchants_location_ids'] = $include_merchant_ids;
			$content_data = $this->_build_content_data('edit', $this->user_login->user, $post);

			$content = View::forge('cms/user/edit', $content_data);
            
			return $this->form_edit('edit', $content, $user);
		}
	}

	private function user_delete($user) {
	
		if (! $this->user_login->user->can_edit_group_members($user->group)) {
			return $this->error_403();
		}
	
		if ($this->user_login->user_id == $user->id) {
			$msg = array('type' => 'fail', 'message' => 'Error: You cannot remove your own account');
			Session::set('message', $msg);
			return Response::redirect("dashboard/user/{$user->id}/edit");
		}

		$this->api->setMethod('DELETE');
		$this->api->setURL(Uri::create("api/user/{$user->id}"));

		$output = $this->api->execute();

		if ($output->meta->status == 1) {
			$msg = array('type' => 'success', 'message' => 'Successfully deleted user '.$user->email, 'autohide' => true);
			$url = "dashboard/users";
		} else {
			$err = $output->meta->error;
			if (!$err) { $err = 'Error: 900 - Unable to process request'; }
			$msg = array('type' => 'fail', 'message' => $err);
			$url = "dashboard/user/{$user->id}/edit";
		}
	
		Session::set('message', $msg);
		return Response::redirect($url);
	}
	
	private function get_posted_include_merchant_ids() {
	    $post = Input::post();
	    $include_merchant_ids = array();
	    foreach ($post as $key => $value) {
	        if (preg_match('/include_merchants_(\d+)/', $key, $matches)) {
	            $include_merchant_ids[] = $matches[1];
	        }
	    }
	    return $include_merchant_ids;
	}
	
	private function _build_content_data($action, $current_user, $user) {
		if (is_array($user)) {
			// TODO: Improve this
			$user = json_decode(json_encode($user));
		}

        $groups = Model_User::get_groups(Model_User::GROUP_MERCHANT, $current_user->group);
        if (isset($user->group) && !array_key_exists($user->group, $groups)) {
            $groups[$user->group] = Model_User::get_group_name($user->group);
        }
        
		// Set content params
		return array(
            'action'     => $action,
            'groups'     => $groups,
            'me'         => $current_user,
            'user'       => $user,
            'login_hash' => $this->user_login->login_hash,
		);
	}
}
