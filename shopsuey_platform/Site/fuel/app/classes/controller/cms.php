<?php

/**
 * Base controller for the CMS sections
 * Deals with the basic permissions handling by group
 * @author lucas
 *
 */
abstract class Controller_Cms extends Controller {

    const PAGINATION_WINDOW = 5;
    
	protected $msg = NULL;
	
	protected $landing = NULL;
	
	protected $api = NULL;

	protected $auth = NULL;
	protected $user_login = NULL;

	protected $company = NULL;

	public function before() {
		$this->auth = Auth::instance('Shopsuey_Session');
		// The auth login driver always return an object, even if the user is guest
		$this->user_login = $this->auth->get_user_login_object();
		
		$this->landing = Config::get('cms.landing_page');

		$this->api = new Restful();
		$this->api->setAppid(Config::get('cms.appid'));
		$this->api->setLoginHash($this->user_login->login_hash);
		
		if (Session::get('message')) { $this->msg = Session::get('message'); Session::delete('message'); }

		parent::before();
	}
	
	public function router($method, $params) {
		// Build the name of the resource to check if the user is allowed to use it
		$controller = strtolower(Request::active()->controller);
		$resource_name = implode('/', array_slice(explode('_', $controller), 1));

        if (function_exists('newrelic_name_transaction')) {
            newrelic_name_transaction("$resource_name/$method");
        }
		
		error_log("$resource_name => $method");
		
		// This router does essentially the same as the original, but it checks for allowed access
		if ($this->auth->has_access("{$resource_name}.{$method}")) {
			error_log("Access granted!!");
	
			$action_name = 'action_' . $method;
	
			$rc = new ReflectionClass($this);
			if ($rc->hasMethod($action_name)) {
				return $rc->getMethod($action_name)->invokeArgs($this, $params);
			} else {
				throw new \HttpNotFoundException();
			}
		} else {
			if ($this->auth->user_logged_in()) {
				return $this->error_403();
			} else {
				Session::set('message', array('message' => 'Please log in', 'type' => 'error'));
				return Response::redirect('login/');
			}
		}
	}
	
	protected function error_404() {
		// Set header params
		$header_data = array(
				'style' => 'styles.css',
				'ie' => 'ie.css'
		);
	
		// Compile view
		$header = View::forge('base/header', $header_data);
		$cont = View::forge('error/404');
		$footer = View::forge('base/footer');
		$temp = $header . $cont . $footer;
	
		return Response::forge($temp);
	}
	
	protected function error_403() {
		// Set header params
		$header_data = array(
				'style' => 'styles.css',
				'ie' => 'ie.css'
		);
	
		// Compile view
		$header = View::forge('base/header', $header_data);
		$cont = View::forge('error/403');
		$footer = View::forge('base/footer');
		$temp = $header . $cont . $footer;
	
		return Response::forge($temp);
	}
    
    protected function process_files() {
        // The save feature of Upload won't be used, just the validation of the upload
        $config = array(
            'ext_whitelist' => array('jpg', 'jpeg', 'gif', 'png'),
            'type_whitelist' => array('image'),
        );

        // Check the uploads
        Upload::process($config);

        // Fields with no uploaded files are marked as errors, so they must be avoided in this case
        $errors = array();
        if (!Upload::is_valid()) {
            foreach (Upload::get_errors() as $file) {
                foreach ($file['errors'] as $error) {
                    if ($error['error'] != Upload::UPLOAD_ERR_NO_FILE) {
                        $error_message = isset($error['message']) ? $error['message'] : 'There was an error while uploading the file';
                        $errors[] = "{$file['name']}: {$error['message']}";
                    }
                }
            }
        }
        if (!empty($errors)) {
            $this->err = implode(' / ', $errors);
            return NULL;
        }

        $files = Upload::get_files();
        return $files;
    }
    
    protected function get_current_company() {
        if ($this->company) {
            return $this->company;
        }

        if ($this->user_login->user->is_admin()) {
            // Admin users don't have assigned companies
            return NULL;
        }

        $company_id = \Session::get('company_id');
        $assigned_locations = $this->user_login->user->get_assigned_companies();
        
        if (count($assigned_locations) == 0) {
            return NULL;
        }
            
        if (empty($company_id) || !in_array($company_id, array_keys($assigned_locations))) {
            if ($this->user_login->user->group == Model_User::GROUP_MANAGER) {
                // If the logged in user is a Mall Manager, I'll take the first mall in their assinged locations
                $found = false;
                foreach ($assigned_locations as $location) {
                    if ($location->type == Model_Location::TYPE_MALL) {
                        $found      = true;
                        $company_id = $location->id;
                        break;
                    }
                }
                
                if (!$found) {
                    $assigned_locations_values = array_values($assigned_locations);
                    $location = array_shift($assigned_locations_values);
                    $company_id = $location->id;
                }
                
            } else {
                // Take the first entry. TODO: An improvement could be to select a default location
                $assigned_locations_values = array_values($assigned_locations);
                $location = array_shift($assigned_locations_values);
                $company_id = $location->id;
            }
            \Session::set('company_id', $company_id);
        }

        $this->company = Model_Location::find($company_id);
        return $this->company;
    }
    
    protected function set_current_company($company) {
        $this->company = $company;
        \Session::set('company_id', $company->id);
    }
    
    protected function get_instagram_info_for_location($location) {
        $instagram_info = new \stdClass();
        $instagram_latest_post = NULL;
        $username = NULL;

        if (isset($location->id) && !empty($location->id)) {
            $instagram_feed = CMS::location_instagram_feed($location->id);
            if ($instagram_feed) {
                $instagram_latest_post = array_shift($instagram_feed->feed);
                $username = $instagram_feed->username;
            }
        }
        
        $instagram_info->set = ! is_null($instagram_latest_post);
        $instagram_info->latest_post = $instagram_latest_post;
        $instagram_info->username = $username;
        
        return $instagram_info;
    }
    
    protected function get_instagram_info_for_user($user) {
        $instagram_info = new \stdClass();
        $instagram_latest_post = NULL;
        $username = NULL;

        \Package::load('instagram');

        if ($user->instagram) {
        	try {
        		$feed = \Instagram\Api::get_user_recent_media($user->instagram->access_token, $user->instagram->instagram_user_id);
        		$instagram_latest_post = array_shift($feed);
        	} catch (\Instagram\Exception $e) {
        	}
    		$username = $user->instagram->username;
        }

        $instagram_info->set = ! is_null($instagram_latest_post);
        $instagram_info->latest_post = $instagram_latest_post;
        $instagram_info->username = $username;
        $return_url = \Uri::create(\Uri::current(), array(), array('instagram_callback' => '1'));
        $instagram_info->auth_url = \Instagram\Api::get_auth_url($return_url);
        $instagram_info->logout_url = \Instagram\Api::get_logout_url();

        return $instagram_info;
    }
    
    protected function get_pagination($pagination_meta, $window = NULL) {
        if (is_null($window)) {
            $window = static::PAGINATION_WINDOW;
        }
        
        $pagination = new stdClass();
        
        $current_page = $pagination->current_page = $pagination_meta->page->current;
        $total_pages = $pagination->total_pages = $pagination_meta->page->count;
        
        $first_page = $current_page - $window < 1 ? 1 : $current_page - $window;
        $last_page = $current_page + $window > $total_pages ? $total_pages : $current_page + $window;
        
        $pagination->pages = array();
        for($i = $first_page; $i <= $last_page; $i++) {
        	$pagination->pages[] = array(
        		'number' => $i,
        		'active' => $i == $current_page,
        	);
        }

        if ($current_page > 1) {
        	$pagination->previous = $current_page - 1;
        }
        if ($first_page > 1) {
        	$pagination->first = 1;
        }
        if ($current_page < $total_pages) {
        	$pagination->next = $current_page + 1;
        }
        if ($last_page < $total_pages) {
        	$pagination->last = $total_pages;
        }
        
        return $pagination;
    }
}
