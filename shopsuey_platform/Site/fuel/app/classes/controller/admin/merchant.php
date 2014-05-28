<?php

/**
 * The Merchant Controller.
 * This controllers the CRUD proceedures for managing merchants
 *
 * @package  app
 * @extends  Controller_Dashboard
 */

class Controller_Admin_merchant extends Controller_Cms {
	
	public function action_index() {
		// Include .js
		$apnd = array('files/base.js', 'files/merchants.js');
		$excl = array('autotab', 'cleditor', 'dualist', 'elfinder', 'fullcalendar', 'flot', 'maskedinput', 'tagsinput', 'upload', 'wizards');
		$scripts = CMS::scripts($apnd, NULL, $excl);

		// Set header params
		$header_data = array(
			'style' => 'styles.css',
			'scripts' => $scripts,
			'ie' => 'ie.css'
		);

		$page     = $this->param('page', 1);
		$string   = $this->param('string', Input::param('string', ''));
        $order_by = Input::param('sort', 'name');
        
        $string = html_entity_decode($string, ENT_QUOTES);

        $this->api->setMethod('GET');
		$this->api->setData(array('string' => $string, 'order_by' => $order_by));
		$this->api->setURL(Uri::create("api/merchants/$page"));

		$output = $this->api->execute();

		// Set content params
		$content_data = array(
			'me' => $this->user_login->user,
			'merchants' => array(),
			'pagination' => null,
                        'search' => urlencode($string),
                        'sort_string' => 'sort=' . $order_by,
			'title' => 'Merchants');

		if ($output) {
			$content_data['merchants'] = $output->data->merchants;
			$pagination_meta = $output->meta->pagination;
			$content_data['pagination'] = $this->get_pagination($pagination_meta);
			$label = ($pagination_meta->records > 1) ? ' Merchants' : ' Merchant';
			$content_data['title'] = $pagination_meta->records;
			$content_data['title'] .= ($string) ? ' results for '.$string : $label;
		}

		$content = View::forge('cms/admin/merchants/list', $content_data);

		$wrapper_data = array(
			'page' => array(
				'name' => 'Merchant Management',
				'subnav' => View::forge('cms/admin/merchants/menu'),
				'icon' => 'icon-user-3'),

			'crumbs' => array(
				array('title'=>'Dashboard', 'link'=>Uri::create('dashboard')),
				array('title'=>'Administration', 'link' => Uri::create('admin')),
				array('title'=>'Merchants', 'link' => Uri::create('admin/merchants'))
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

	// Update UI
	public function action_edit() { return $this->action_add('edit'); }

	// Create an item
	public function action_add($action = 'add') {
            $content = '';
            $merchant = null;
            $output = null;
            $mall = null;
        
		if ($action == 'edit') {
                    $id = $this->param('id');
                    if (!$id) {
                            return $this->error_404();
                    }
                    $this->api->setMethod('GET');
                    $this->api->setURL(Uri::create("api/merchant/$id"));
                    $output = $this->api->execute();

                    if ($output->meta->status == 1) {
                        $merchant = $output->data->merchant;

                        $merchant->category_ids = array();
                        foreach ($merchant->categories as $category) {
                            $merchant->category_ids[] = $category->id;
                        }
                    } else {
                            return $this->error_404();
                    }
		} else {
                    	$merchant = new Model_Merchant();
            
            $forced_mall = Input::get('mall');
            if (!is_null($forced_mall)) {
                $mall = Model_Mall::find($forced_mall);
                
                if ($mall) {
                    $merchant->inherit_data_from_mall($mall);
                }
            }
		}
        
        $timezones = Helper_Timezone::get_timezone_list(true);
        $instagram_info = $this->get_instagram_info_for_location($merchant);

        	// Set content params
		$content_data = array(
			'action' => $action,
			'merchant' => $merchant,
			'me' => $this->user_login->user,
            'hours' => $this->create_hours_groups($merchant),
			'output' => $output,
    		'login_hash' => $this->user_login->login_hash,
            'timezones' => $timezones,
		    'instagram_set' => $instagram_info->set,
		    'instagram_latest_post' => $instagram_info->latest_post,
		);		

		if (Input::post()) {
                    
                    $content_data['merchant'] = $merchant;
                            
                    if ($action == 'add') { 
                        return $this->merchant_add($content_data); 
                    }
                        
                    if ($action == 'edit') { 
                        return $this->merchant_update($content_data); 
                    }
                        
		} else {
			$content = View::forge('cms/admin/merchants/edit', $content_data);
			return $this->form_edit($action, $content, $content_data);
		}
	}

	public function action_delete() {
	    $merchantid = Request::active()->param('id');
	     
	    if ($merchantid) {
	        $merchant = Model_Merchant::find($merchantid);
	        $merchant->status = 0;
	        $merchant->save();
	    }
	    else {
	        return $this->error_404();
	    }
	     
	    $msg = array('type' => 'success', 'message' => 'Successfully deleted merchant '.$merchant->name, 'autohide' => true);
	    Session::set('message', $msg);
	    Response::redirect('admin/merchants');
	}
	
	private function form_edit($action, $content, $data) {
		$data = (object) $data;

		// Include .js
		$apnd = array('files/base.js', 'files/merchants.js', 'files/timezones.js', 'files/times.js', 'files/places.js');
		$excl = array('autotab', 'dualist', 'elfinder', 'fullcalendar', 'flot', 'maskedinput', 'upload');
		$scripts = CMS::scripts($apnd, NULL, $excl);

		// Set header params
		$header_data = array(
			'style' => array('styles.css', 'autoSuggest.css'),
			'scripts' => $scripts,
			'ie' => 'ie.css',
	        'styles' => 'jquery.Jcrop.min.css',
		);

		$wrapper_data = array(
			'page' => array(
				'name' => ($action == 'add') ? 'Merchant Creator' : 'Merchant Editor',
				'subnav' => View::forge('cms/admin/merchants/menu', array(
			        'search_engines' => View::forge('cms/company/utility', array(
			            'login_hash' => $this->user_login->login_hash,
			        )),
				    'merchantId' => isset($data->merchant->id) ? $data->merchant->id : 0
		        )),
				'icon' => 'icon-user-3'),

			'crumbs' => array(
				array('title'=>'Dashboard', 'link' => Uri::create('dashboard')),
				array('title'=>'App Admin', 'link' => Uri::create('admin')),
				array('title'=>'Merchants', 'link' => Uri::create('admin/merchants')),
				array('title'=>($action == 'edit') ? mb_convert_case($data->merchant->name, MB_CASE_TITLE) : ucwords($action), 'link' => '#'),
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

	private function merchant_add($content_data) {
		$data = array();
		$err = null;
		$post = Input::post();

		if ($err) {
			Session::set('message', $err);

			$content_data['merchant'] = $this->post_to_object($post);
			$content = View::forge('cms/admin/merchants/edit', $content_data);
			return $this->form_edit('add', $content, $content_data);
		}

		else {
			foreach($post as $field => $val) { 
                if ($field == 'hours') {
                    $val = $this->process_hours_info($val);
                }
                $data[$field] = $val; 
            }
            
            if (!$this->validate_hours(Input::post('hours'))) {
                $this->msg = array('type' => 'fail', 'message' => 'Please input valid opening and closing times for each day.');

                // Set content params
                $content_data['merchant'] = $this->post_to_object($post);
                $content_data['hours'] = $this->create_hours_groups($data);
                $content = View::forge('cms/admin/merchants/edit', $content_data);
                return $this->form_edit('edit', $content, $content_data);
            }
            
		    if (count(Input::post('category_ids', array())) > 3) {
                $this->msg = array('type' => 'fail', 'message' => 'Up to three categories can be selected.');
                $content_data['merchant'] = $this->post_to_object($post);
                $content = View::forge('cms/admin/merchants/edit', $content_data);
                return $this->form_edit('edit', $content, $content_data);
            }
            
            $social_error = $this->check_social_fields(Input::post('social'));
            
            if ($social_error) {
                $content_data['merchant'] = $this->post_to_object($post);
                $content = View::forge('cms/admin/merchants/edit', $content_data);
                return $this->form_edit('edit', $content, $content_data);
            }
            
            $default_social = isset($post['default_social']) && $post['default_social'];
            $this->process_default_social($data, $default_social);

			//$data['is_customer'] = isset($post['is_customer']) && $post['is_customer'];

			$default_logo = isset($post['default_logo']) && $post['default_logo'];
			$default_landing_screen_img = isset($post['default_landing_screen_img']) && $post['default_landing_screen_img'];
		
			$url = Uri::create('api/merchant/');
	
			$this->process_images($data);
			$this->process_default_images($data, $default_logo, $default_landing_screen_img);
			
			$this->api->setData($data);
			$this->api->setMethod('POST');
			$this->api->setURL($url);

			$output = $this->api->execute();

			//return Response::forge(json_encode($output));

			if ($output) {
				if ($output->meta->status == 1) { // Success
					$merchant = $output->data->merchant;
					$msg = array('type' => 'success', 'message' => 'Successfully added merchant '.$merchant->name, 'autohide' => true);
					Session::set('message', $msg);
                                        return Response::redirect($this->get_edit_url($merchant->id));
				}
				else { // Fail
					$this->msg = array('type' => 'fail', 'message' => $output->meta->error);

					// Set content params
					$content_data['merchant'] = $this->post_to_object($post);
                    $content_data['hours'] = $this->create_hours_groups($data);
					$content = View::forge('cms/admin/merchants/edit', $content_data);
					return $this->form_edit('add', $content, $content_data);
				}
			}
			else {
				$this->msg = array('type' => 'fail', 'message' => 'Error: 900 - Unable to process your request');

				// Set content params
				$content_data['merchant'] = $this->post_to_object($post);
                $content_data['hours'] = $this->create_hours_groups($data);
				$content = View::forge('cms/admin/merchants/edit', $content_data);
				return $this->form_edit('add', $content, $content_data);

			}
		}
	}
    
    private function process_images(&$data) {
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

	private function merchant_update($content_data) {
		$data = array();
		$err = null;
		$post = Input::post();

		if ($err) {
			Session::set('message', $err);

			$content_data['merchant'] = $this->post_to_object($post);
			$content = View::forge('cms/admin/merchants/edit', $content_data);
			return $this->form_edit('edit', $content, $content_data);
		}else {
                    
			$merchantid = Request::active()->param('id');
			foreach($post as $field => $val) { 
                if ($field == 'hours') {
                    $val = $this->process_hours_info($val);
                }
                $data[$field] = $val; 
            }
            
            if (!$this->validate_hours(Input::post('hours'))) {
                $this->msg = array('type' => 'fail', 'message' => 'Please input valid opening and closing times for each day.');

                // Set content params
                $content_data['merchant'] = $this->post_to_object($post);
                $content_data['hours'] = $this->create_hours_groups($data);
                $content = View::forge('cms/admin/merchants/edit', $content_data);
                return $this->form_edit('edit', $content, $content_data);
            }
            
            if (count(Input::post('category_ids', array())) > 3) {
                $this->msg = array('type' => 'fail', 'message' => 'Up to three categories can be selected.');
                $post['id'] = $merchantid;
                $content_data['merchant'] = $this->post_to_object($post);
                $content = View::forge('cms/admin/merchants/edit', $content_data);
                return $this->form_edit('edit', $content, $content_data);
            }
            
            $social_error = $this->check_social_fields(Input::post('social'));
            
            if ($social_error) {
                $post['id'] = $merchantid;
                $content_data['merchant'] = $this->post_to_object($post);
                $content = View::forge('cms/admin/merchants/edit', $content_data);
                return $this->form_edit('edit', $content, $content_data);
            }
            
            if (isset($post['inherited_hours']) && $post['inherited_hours']) {
                unset($data['hours']);
            }
            
            $default_social = isset($post['default_social']) && $post['default_social'];
            $this->process_default_social($data, $default_social);

			//$data['is_customer'] = isset($post['is_customer']) && $post['is_customer'];
			$default_logo = isset($post['default_logo']) && $post['default_logo'];
			$default_landing_screen_img = isset($post['default_landing_screen_img']) && $post['default_landing_screen_img'];
			
			$this->process_images($data);
			$this->process_default_images($data, $default_logo, $default_landing_screen_img);

			$url = Uri::create('api/merchant/'.$merchantid);
			$this->api->setData($data);
			$this->api->setMethod('PUT');
			$this->api->setURL($url);

			$output = $this->api->execute();

			if ($output) {
				if ($output->meta->status == 1) { // Success
					$merchant = $output->data->merchant;
					$msg = array('type' => 'success', 'message' => 'Successfully updated merchant '.$merchant->name, 'autohide' => true);
					Session::set('message', $msg);

                    if ($merchant->mall_id)
                        return Response::redirect("dashboard/health_metrics?id={$merchant->mall_id}");
                    else
                        return Response::redirect($this->get_edit_url($merchant->id));
				}
				else { // Fail
					$this->msg = array('type' => 'fail', 'message' => $output->meta->error);

					// Set content params
					$data['id'] = $merchantid;
					$content_data['merchant'] = (object) $data;
                    $content_data['hours'] = $this->create_hours_groups($data);
					$content = View::forge('cms/admin/merchants/edit', $content_data);
					return $this->form_edit('edit', $content, $content_data);
				}

			}
			else {
				$this->msg = array('type' => 'fail', 'message' => 'Error: 900 - Unable to process your request');

				// Set content params
				$data['id'] = $merchantid;
				$content_data['merchant'] = (object) $data;
                $content_data['hours'] = $this->create_hours_groups($data);
				$content = View::forge('cms/admin/merchants/edit', $content_data);
				return $this->form_edit('edit', $content, $content_data);

			}
		}
	}

	private function check_social_fields($social) {
	    $facebook_pattern = '/^http(s)?:\/\/(www.)?facebook.com\/(pages\/)?\w[\w\.\-]*(\/\w[\w\.]*)?$/';
	    $foursquare_pattern = '/^http(s)?:\/\/(www.)?foursquare.com\/v\/\w+?\/\w+?$/';
	    $twitter_pattern = '/^@\w+$/';
	    if (!empty($social['facebook']) &&
	        preg_match($facebook_pattern, $social['facebook']) == 0) {
	        $this->msg = array('type' => 'fail', 'message' => 'Incorrect facebook page format.');
	        return true;
	    } else if (!empty($social['foursquare']) &&
	        preg_match($foursquare_pattern, $social['foursquare']) == 0) {
	        $this->msg = array('type' => 'fail', 'message' => 'Incorrect foursquare venue format.');
	        return true;
	    } else if (!empty($social['twitter']) &&
	        preg_match($twitter_pattern, $social['twitter']) == 0) {
	        $this->msg = array('type' => 'fail', 'message' => 'Incorrect twitter user format.');
	        return true;
	    }
	     
	    return false;
	}
	
	private function post_to_object($post) {
	    $post = (object) $post;
	    $post->social = (object) $post->social;
	     
	    return $post;
	}
    
    private function validate_hours($data) {
        $pattern = '/^[0-9]{1,2}\:[0-9]{2}[AP]M$/';
        $days = array('mon', 'tue', 'wed', 'thr', 'fri', 'sat', 'sun');
        for ($i = 0; $i < 3; $i++) {
            foreach ($days as $day) {
                if (isset($data[$i][$day]) && $data[$i][$day]) {
                    if (!preg_match($pattern, $data[$i]['open']) || !preg_match($pattern, $data[$i]['close'])) {
                        return false;
                    }
                }
            }
        }
        return true;
    }
    
    private function create_hours_groups($merchant) {
        $merchant = json_decode(json_encode($merchant)); //very primitive and disgusting cast to stdClass, sorry!
        $groups = array(
            array('days' => array(), 'open' => '', 'close' => ''),
            array('days' => array(), 'open' => '', 'close' => ''),
            array('days' => array(), 'open' => '', 'close' => ''),
        );
        
        if (!isset($merchant->hours) || empty($merchant->hours)) {
            return $groups;
        }
        
        $hours = $merchant->hours;
        
        $days = array('mon', 'tue', 'wed', 'thr', 'fri', 'sat', 'sun');
        foreach ($days as $day) {
            $found = false;
            foreach ($groups as $k => $group) {
                if ($hours->$day->open && $hours->$day->close && $group['open'] == $hours->$day->open && $group['close'] == $hours->$day->close) {
                    $groups[$k]['days'][] = $day;
                    $found = true;
                    break;
                }
            }
            
            if (!$found) {
                foreach ($groups as $k => $group) {
                    if (!count($group['days']) && $hours->$day->open && $hours->$day->close) {
                        $groups[$k]['days'][] = $day;
                        $groups[$k]['open'] = $hours->$day->open;
                        $groups[$k]['close'] = $hours->$day->close;
                        break;
                    }
                }
            }
        }
        return $groups;
    
    }
    
    private function process_hours_info($data) {
        $return = array();
        $days = array('mon', 'tue', 'wed', 'thr', 'fri', 'sat', 'sun');
        for ($i = 0; $i < 3; $i++) {
            foreach ($days as $day) {
                if (!isset($return[$day])) {
                    $return[$day] = array('open' => '', 'close' => '');
                }
                if (isset($data[$i][$day]) && $data[$i][$day]) {
                    $return[$day] = array('open' => $data[$i]['open'], 'close' => $data[$i]['close']);
                }
            }
        }
        return $return;
    }
    
    private function process_default_social(&$data, $default_social) {
        $data['default_social'] = $default_social;
        
        if ($default_social) {
            $result = DB::update('locations')
                ->value('default_social', 0)
                ->where('type', 'Merchant')
                ->where('name', $data['name'])
                ->execute();
    
            $facebook = !empty($data['social']['facebook']) ? $data['social']['facebook'] : '';
            $twitter = !empty($data['social']['twitter']) ? $data['social']['twitter'] : '';
            $foursquare = !empty($data['social']['foursquare']) ? $data['social']['foursquare'] : '';
    
            if ($result > 0) {    
                $locations = Model_Location::query()
                    ->where('type', 'Merchant')->where('name', $data['name'])
                    ->where('default_social', 0)->get();
    
                foreach ($locations as $location) {
                    $change = false;
                    if ($location->social->facebook == '') {
                        $location->social->facebook = $facebook;
                        $change = true;
                    }
                    if ($location->social->twitter == '') {
                        $location->social->twitter = $twitter;
                        $change = true;
                    }
                    if ($location->social->foursquare == '') {
                        $location->social->foursquare = $foursquare;
                        $change = true;
                    }
    
                    if ($change) {
                        $location->save();
                    }
                }
            }
        } else {
            $social = DB::select('social')->from('locations')
                ->where('type', 'Merchant')->where('name', $data['name'])
                ->where('default_social', 1)->execute()->current();
            $social = json_decode($social['social'], true);
            
            $facebook = !empty($social['facebook']) ? $social['facebook'] : '';
            $twitter = !empty($social['twitter']) ? $social['twitter'] : '';
            $foursquare = !empty($social['foursquare']) ? $social['foursquare'] : '';
        
            if (!isset($data['social']['facebook']) || empty($data['social']['facebook'])) {
                $data['social']['facebook'] = $facebook;
            }
            if (!isset($data['social']['twitter']) || empty($data['social']['twitter'])) {
                $data['social']['twitter'] = $twitter;
            }
            if (!isset($data['social']['foursquare']) || empty($data['social']['foursquare'])) {
                $data['social']['foursquare'] = $foursquare;
            }
        
        }
    }
    
    private function process_default_images(&$data, $default_logo, $default_landing_screen_img) {
        if (!isset($data['logo']) || empty($data['logo'])) {
            $logo = DB::select('logo')->from('locations')
                        ->where('type', 'Merchant')->where('name', $data['name'])
                        ->where('default_logo', true)->execute()->current();
            $data['logo'] = $logo['logo'] != null ? $logo['logo'] : '';
        }
        	
        if (!isset($data['landing_screen_img']) || empty($data['landing_screen_img'])) {
            $image = DB::select('landing_screen_img')->from('locations')
                        ->where('type', 'Merchant')->where('name', $data['name'])
                        ->where('default_landing_screen_img', true)->execute()->current();
            $data['landing_screen_img'] = $image['landing_screen_img'] != null ? $image['landing_screen_img'] : '';
        }
        
        if ($default_logo) {
            $result = DB::update('locations')
                        ->value('default_logo', 0)
                        ->where('name', $data['name'])
                        ->execute();
                
            if ($result > 0) {
                $data['default_logo'] = true;
                
                $result = DB::update('locations')
                            ->value('logo', $data['logo'])
                            ->where('name', $data['name'])
                            ->where('logo', ' ')
                            ->execute();
            }
        } else {
            $data['default_logo'] = false;
        }
        
        if ($default_landing_screen_img) {
            $result = DB::update('locations')
                        ->value("default_landing_screen_img", 0)
                        ->where('name', $data['name'])
                        ->execute();
            
            if ($result > 0) {
                $data['default_landing_screen_img'] = true;
                
                $result = DB::update('locations')
                            ->value('landing_screen_img', $data['landing_screen_img'])
                            ->where('name', $data['name'])
                            ->where('landing_screen_img', ' ')
                            ->execute();
            }
        } else {
            $data['default_landing_screen_img'] = false;
        }
    }
    
    private function get_edit_url($merchant_id) {
        return Uri::create("admin/merchant/{$merchant_id}/edit", array(), Input::get());
    }
}
