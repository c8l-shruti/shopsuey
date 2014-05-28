<?php

/**
 * The Mall Controller.
 * This controllers the CRUD proceedures for managing malls
 *
 * @package  app
 * @extends  Controller_Cms
 */

class Controller_Admin_mall extends Controller_Cms {

    private static $diff_fields = array(
        'name',
        'entity_id',
        'geometry_id',
//         'floor',
//         'phone',
//         'email',
//         'url',
//         'description',
    );

	public function action_index() {
		// Include .js
		$apnd = array('files/base.js', 'files/malls.js');
		$excl = array('autotab', 'cleditor', 'dualist', 'elfinder', 'fullcalendar', 'flot', 'maskedinput', 'tagsinput', 'upload', 'wizards');
		$scripts = CMS::scripts($apnd, NULL, $excl);

		// Set header params
		$header_data = array(
			'style' => 'styles.css',
			'scripts' => $scripts,
			'ie' => 'ie.css'
		);

		$page   = $this->param('page', 1);
		$string = $this->param('string', Input::param('string', ''));
                $order_by = Input::param('sort', 'name');
		
                $string = html_entity_decode($string, ENT_QUOTES);
                
		$this->api->setMethod('GET');
		$this->api->setData(array('string' => $string, 'order_by' => $order_by));
		$this->api->setURL(Uri::create("api/malls/$page"));

		$output = $this->api->execute();

		// Set content params
		$content_data = array(
			'me' => $this->user_login->user,
			'malls' => array(),
			'pagination' => null,
			'search' => urlencode($string),
                        'sort_string' => 'sort=' . $order_by,
			'title' => 'Malls');

		if ($output) {
			$content_data['malls'] = $output->data->malls;
			$pagination_meta = $output->meta->pagination;
			$content_data['pagination'] = $this->get_pagination($pagination_meta);
			$label = ($pagination_meta->records > 1) ? ' Marketplaces' : ' Marketplace';
			$content_data['title'] = $pagination_meta->records;
			$content_data['title'] .= ($string) ? ' results for '.$string : $label;
		}

		$content = View::forge('cms/admin/malls/list', $content_data);

		$wrapper_data = array(
			'page' => array(
				'name' => 'Marketplace Management',
				'subnav' => View::forge('cms/admin/malls/menu'),
				'icon' => 'icon-basket'),

			'crumbs' => array(
				array('title'=>'Dashboard', 'link'=>Uri::create('dashboard')),
				array('title'=>'Administration', 'link' => Uri::create('admin')),
				array('title'=>'Marketplaces', 'link' => Uri::create('admin/malls'))
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
		$mall = null;
		$output = null;

		if ($action == 'edit') {
			$mallid = Request::active()->param('id');
			if ($mallid) { 
			    $mall = CMS::mall($mallid);

			    $mall->category_ids = array();
			    foreach ($mall->categories as $category) {
			        $mall->category_ids[] = $category->id;
			    }
			}
			else { return $this->error_404(); }
		} else {
			$mall = new Model_Mall();
			$mallid = '';
		}
        
        $timezones = Helper_Timezone::get_timezone_list(true);
        $instagram_info = $this->get_instagram_info_for_location($mall);

		// Set content params
		$content_data = array(
			'action' => $action,
			'mall' => $mall,
            'hours' => $this->create_hours_groups($mall),
			'me' => $this->user_login->user,
			'output' => $output,
			'login_hash' => $this->user_login->login_hash,
		    'micello_import_url' => Uri::create("admin/mall/$mallid/micello_import"),
		    'update_merchants_url' => Uri::create("admin/mall/$mallid/update_merchants"),
	        'timezones' => $timezones,
		    'instagram_set' => $instagram_info->set,
		    'instagram_latest_post' => $instagram_info->latest_post,
		);
		
		if (Input::post()) {
            if ($action == 'add') { return $this->mall_add($content_data); }
			if ($action == 'edit') { return $this->mall_update($content_data); }
		} else {
		    

			$content = View::forge('cms/admin/malls/edit', $content_data);
			return $this->form_edit($action, $content, $content_data);
		}
	}
	
	public function action_delete() {
	    $mallid = Request::active()->param('id');
	    
	    if ($mallid) {
	        $mall = Model_Mall::find($mallid);
	        $mall->status = 0;
	        $mall->save();
	    }
	    else {
	        return $this->error_404();
	    }
	    
	    Response::redirect('admin/malls');
	}

	/**
	 * Gathers info for the given location from database and fetchs
	 * info for its micello community id using the API
	 */
    public function action_micello_import() {
        $location_id = Request::active()->param('id');
        if (!$location_id || !($location = CMS::location_with_merchants($location_id))) {
        	return $this->error_404();
        }
        
        $response = new \stdClass();

        // TODO: Check if getting micello info is faster on prod
        $micello_info = CMS::location_micello_entities($location_id);
        
        if (is_null($micello_info)) {
            $response->error = TRUE;
        } else {
            $response->error = FALSE;
            $response->data = $this->compare_micello_info($location, $micello_info);
        }
        
        $this->response->set_header('Cache-Control', 'no-cache, must-revalidate');
        $this->response->set_header('Expires', 'Mon, 26 Jul 1997 05:00:00 GMT');
        $this->response->set_header('Content-Type', 'application/json');
        return \Format::forge($response)->to_json();
    }

    /**
     * Update/creates merchants for the given mall
     */
    public function action_update_merchants() {
        $mall_id = Request::active()->param('id');
        if (!$mall_id || !($mall = CMS::mall($mall_id))) {
            return $this->error_404();
        }
        
        $form = \Input::post();
        
        $this->api->setData($form);
        $this->api->setMethod('POST');
        $this->api->setURL(Uri::create("api/mall/$mall_id/update_merchants"));
        
        $output = $this->api->execute();
        
        if ($output) {
        	if ($output->meta->status == 1) { // Success
        		$msg = array('type' => 'success', 'message' => 'Successfully updated merchants', 'autohide' => true);
        	} else { // Fail
        		$msg = array('type' => 'fail', 'message' => $output->meta->error);
        	}
        } else {
        	$msg = array('type' => 'fail', 'message' => 'Error: 900 - Unable to process your request');
        }

        Session::set('message', $msg);
        return Response::redirect($this->get_edit_url($mall->id));
    }
    
	private function form_edit($action, $content, $data) {
		$data = (object) $data;

		// Include .js
		$apnd = array('files/base.js', 'files/malls.js', 'files/timezones.js', 'files/times.js', 'files/places.js');
		$excl = array('autotab', 'dualist', 'cleditor', 'elfinder', 'fullcalendar', 'flot', 'maskedinput', 'upload');
		$scripts = CMS::scripts($apnd, NULL, $excl);

		// Set header params
		$header_data = array(
			'style' => 'styles.css',
			'scripts' => $scripts,
			'ie' => 'ie.css',
                        'styles' => 'jquery.Jcrop.min.css',
		);

                $back_link = $action == 'edit' ? Uri::create('dashboard/health_metrics?id=' . $data->mall->id) : '#';
                
		$wrapper_data = array(
			'page' => array(
				'name' => ($action == 'add') ? 'Mall Creator' : 'Mall Editor',
				'subnav' => View::forge('cms/admin/malls/menu', array(
			        'search_engines' => View::forge('cms/company/utility', array(
			            'login_hash' => $this->user_login->login_hash,
			        ))
		        )),
				'icon' => 'icon-user-3'),

			'crumbs' => array(
				array('title'=>'Dashboard', 'link' => Uri::create('dashboard')),
				array('title'=>'App Admin', 'link' => Uri::create('admin')),
				array('title'=>'Malls', 'link' => Uri::create('admin/malls')),
				array('title'=>($action == 'edit') ? mb_convert_case($data->mall->name, MB_CASE_TITLE) : ucwords($action), 'link' => $back_link),
			),

			'me' => $data->me,

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


	private function mall_add($content_data) {
		$data = array();
		$err = null;
		$post = Input::post();
		$post = $this->process_form($post);

		if ($err) {
			Session::set('message', $err);

			$content_data['mall'] = $this->post_to_object($post);
			$content = View::forge('cms/admin/malls/edit', $content_data);
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
                $content_data['mall'] = $this->post_to_object($post);
                $content_data['hours'] = $this->create_hours_groups($data);
                $content = View::forge('cms/admin/malls/edit', $content_data);
                return $this->form_edit('edit', $content, $content_data);
            }
            
            
            if (count(Input::post('category_ids', array())) > 3) {
                $this->msg = array('type' => 'fail', 'message' => 'Up to three categories can be selected.');
                $content_data['mall'] = $this->post_to_object($post);
                $content = View::forge('cms/admin/malls/edit', $content_data);
                return $this->form_edit('edit', $content, $content_data);
            }
            
		    $social_error = $this->check_social_fields(Input::post('social'));

            if ($social_error) {
                $content_data['mall'] = $this->post_to_object($post);
                $content = View::forge('cms/admin/malls/edit', $content_data);
                return $this->form_edit('edit', $content, $content_data);
            }

			$url = Uri::create('api/mall/');
			
			$default_social = isset($post['default_social']) && $post['default_social'];
			$this->process_default_social($data, $default_social);

            $default_logo = isset($post['default_logo']) && $post['default_logo'];
            $default_landing_screen_img = isset($post['default_landing_screen_img']) && $post['default_landing_screen_img'];
            	
            $this->process_images($data);
            $this->process_default_images($data, $default_logo, $default_landing_screen_img);

			$this->api->setData($data);
			$this->api->setMethod('POST');
			$this->api->setURL($url);

			$output = $this->api->execute();

			if ($output) {
				if ($output->meta->status == 1) { // Success
					$mall = $output->data->mall;
					$msg = array('type' => 'success', 'message' => 'Successfully added mall '.$mall->name, 'autohide' => true);
					Session::set('message', $msg);
					return Response::redirect("admin/mall/{$mall->id}/edit/");
				}
				else { // Fail
					$this->msg = array('type' => 'fail', 'message' => $output->meta->error);

					// Set content params
					$content_data['mall'] = $this->post_to_object($post);
                    $content_data['hours'] = $this->create_hours_groups($data);
					$content = View::forge('cms/admin/malls/edit', $content_data);
					return $this->form_edit('add', $content, $content_data);
				}
			}
			else {
				$this->msg = array('type' => 'fail', 'message' => 'Error: 900 - Unable to process your request');

				// Set content params
				$content_data['mall'] = (object) $post;
				$content = View::forge('cms/admin/malls/edit', $content_data);
				return $this->form_edit('add', $content, $content_data);

			}
		}
	}

	private function mall_update($content_data) {
		$data = array();
		$post = Input::post();
		$post = $this->process_form($post);

        $mallid = Request::active()->param('id');
        foreach($post as $field => $val) { 
            if ($field == 'hours') {
                $val = $this->process_hours_info($val);
            }
            $data[$field] = $val; 
        }
        
        if (!$this->validate_hours(Input::post('hours'))) {
            $this->msg = array('type' => 'fail', 'message' => 'Please input valid opening and closing times for each day.');

            // Set content params
            $data['id'] = $mallid;
            $content_data['mall'] = $this->post_to_object($data);
            $content_data['hours'] = $this->create_hours_groups($data);
            $content = View::forge('cms/admin/malls/edit', $content_data);
            return $this->form_edit('edit', $content, $content_data);
        }
        
        if (count(Input::post('category_ids', array())) > 3) {
            $this->msg = array('type' => 'fail', 'message' => 'Up to three categories can be selected.');
            $post['id'] = $mallid;
            $content_data['mall'] = $this->post_to_object($post);
            $content = View::forge('cms/admin/malls/edit', $content_data);
            return $this->form_edit('edit', $content, $content_data);
        }
        
        $social_error = $this->check_social_fields(Input::post('social'));

        if ($social_error) {
            $post['id'] = $mallid;
            $content_data['mall'] = $this->post_to_object($post);
            $content = View::forge('cms/admin/malls/edit', $content_data);
            return $this->form_edit('edit', $content, $content_data);
        }
        
        $default_social = isset($post['default_social']) && $post['default_social'];
        $this->process_default_social($data, $default_social);

        $default_logo = isset($post['default_logo']) && $post['default_logo'];
        $default_landing_screen_img = isset($post['default_landing_screen_img']) && $post['default_landing_screen_img'];
        	
        $this->process_images($data);
        $this->process_default_images($data, $default_logo, $default_landing_screen_img);

        $url = Uri::create('api/mall/'.$mallid);
        $this->api->setData($data);
        $this->api->setMethod('PUT');
        $this->api->setURL($url);

        $output = $this->api->execute();

        if ($output) {
            if ($output->meta->status == 1) {
                $mall = $output->data->mall;
                $msg = array('type' => 'success', 'message' => 'Successfully updated mall '.$mall->name, 'autohide' => true);
                Session::set('message', $msg);

                return Response::redirect($this->get_edit_url($mall->id));
            }
            else { // Fail
                $this->msg = array('type' => 'fail', 'message' => $output->meta->error);

                // Set content params
                $data['id'] = $mallid;
                $content_data['mall'] = (object) $data;
                $content_data['hours'] = $this->create_hours_groups($data);
                $content = View::forge('cms/admin/malls/edit', $content_data);
                return $this->form_edit('edit', $content, $content_data);
            }
        } else {
            $this->msg = array('type' => 'fail', 'message' => 'Error: 900 - Unable to process your request');

            // Set content params
            $data['id'] = $mallid;
            $content_data['mall'] = (object) $data;
            $content = View::forge('cms/admin/malls/edit', $content_data);
            return $this->form_edit('edit', $content, $content_data);
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

	private function process_form($data) {
		// Description
        if (isset($data['description'])) {
            $data['description'] = strip_tags($data['description']);
            $data['description'] = str_replace(array("\r\n", "\r", "\n"), "<br>", $data['description']);
        }
		//$data['is_customer'] = isset($data['is_customer']) && $data['is_customer'];

		return $data;
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
    
    private function create_hours_groups($mall) {
        $mall = json_decode(json_encode($mall)); //very primitive and disgusting cast to stdClass, sorry!
        $groups = array(
            array('days' => array(), 'open' => '', 'close' => ''),
            array('days' => array(), 'open' => '', 'close' => ''),
            array('days' => array(), 'open' => '', 'close' => ''),
        );
        
        if (!isset($mall->hours) || empty($mall->hours)) {
            return $groups;
        }
        
        $hours = $mall->hours;
        
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
                ->where('type', 'Mall')
                ->where('name', $data['name'])
                ->execute();
            
            $facebook = !empty($data['social']['facebook']) ? $data['social']['facebook'] : '';
            $twitter = !empty($data['social']['twitter']) ? $data['social']['twitter'] : '';
            $foursquare = !empty($data['social']['foursquare']) ? $data['social']['foursquare'] : '';
            
            if ($result > 0) {                
                $locations = Model_Location::query()
                    ->where('type', 'Mall')->where('name', $data['name'])
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
                ->where('type', 'Mall')->where('name', $data['name'])
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
                        ->where('type', 'Mall')->where('name', $data['name'])
                        ->where('default_logo', true)->execute()->current();
            $data['logo'] = $logo['logo'] != null ? $logo['logo'] : '';
        }
         
        if (!isset($data['landing_screen_img']) || empty($data['landing_screen_img'])) {
            $image = DB::select('landing_screen_img')->from('locations')
                        ->where('type', 'Mall')->where('name', $data['name'])
                        ->where('default_landing_screen_img', true)->execute()->current();
            $data['landing_screen_img'] = $image['landing_screen_img'] != null ? $image['landing_screen_img'] : '';
        }
    
        if ($default_logo) {
            $result = DB::update('locations')
                        ->value('default_logo', 0)
                        ->where('type', 'Mall')
                        ->where('name', $data['name'])
                        ->execute();
    
            if ($result > 0) {
                $data['default_logo'] = true;
    
                $result = DB::update('locations')
                            ->value('logo', $data['logo'])
                            ->where('type', 'Mall')
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
                        ->where('type', 'Mall')
                        ->where('name', $data['name'])
                        ->where("default_landing_screen_img", 1)
                        ->execute();
    
            $data['default_landing_screen_img'] = true;

            $result = DB::update('locations')
                        ->value('landing_screen_img', $data['landing_screen_img'])
                        ->where('type', 'Mall')
                        ->where('name', $data['name'])
                        ->where('landing_screen_img', ' ')
                        ->execute();
        } else {
            $data['default_landing_screen_img'] = false;
        }
    }
    
    private function compare_micello_info($location, $micello_entities) {
        $merchants = array();
        foreach($location->merchants as $merchant) {
            $merchant_info = $this->extract_info_from_location($merchant);
            $merchants[$merchant_info['id']] = $merchant_info;
        }

        $entities = array();
        foreach($micello_entities as $micello_entity) {
            $entity_info = $this->extract_info_from_entity($micello_entity);
            $entities[$entity_info['id']] = $entity_info;
        }
        
        $diffs = array();
        foreach ($merchants as &$merchant) {
            if (empty($merchant['entity_id']) && empty($merchant['geometry_id'])) {
                continue;
            }
            foreach ($entities as &$entity) {
                if ($entity['match_found']) {
                    continue;
                }
                $merchant_name = strtolower($merchant['name']);
                $entity_name = strtolower($entity['name']);
                // similar_text function returns different values when the parameters are passed in different order
                similar_text($merchant_name, $entity_name, $similarity_percent1);
                similar_text($entity_name, $merchant_name, $similarity_percent2);
                $similarity_percent = max($similarity_percent1, $similarity_percent2);
                
                if ($merchant['entity_id'] == $entity['entity_id']
//                     || $merchant['geometry_id'] == $entity['geometry_id']
                    || $similarity_percent > 80) {
                    // We found a match. Calculate the diff
                    $diff = $this->get_diff($merchant, $entity);
                    $diffs[$diff['info']['id']] = $diff;
                    $merchant['match_found'] = $entity['match_found'] = TRUE;
                    break;
                }
            }
            unset($entity);
        }
        unset($merchant);
        
        // Get new entities from micello api
        foreach ($entities as $entity) {
        	if (! $entity['match_found']) {
                $diff = $this->get_diff(NULL, $entity);
                $diffs[$diff['info']['id']] = $diff;
        	}
        }

        // Get locations not present on micello api
        foreach ($merchants as $merchant) {
        	if (! $merchant['match_found']) {
                $diff = $this->get_diff($merchant, NULL);
                $diffs[$diff['info']['id']] = $diff;
        	}
        }

        uasort($diffs, function($d1, $d2) {
            return strcasecmp($d1['info']['name'], $d2['info']['name']);
        });

        uasort($merchants, function($m1, $m2) {
        	return strcasecmp($m1['name'], $m2['name']);
        });

        uasort($entities, function($e1, $e2) {
        	return strcasecmp($e1['name'], $e2['name']);
        });

        return array(
            'import_info' => $diffs,
            'merchants'   => $merchants,
            'entities'    => $entities,
        );
    }
    
    private function get_diff($location, $entity) {
        $diff = array(
            'diffs' => array(),
        );
        $match = NULL;

        if (is_null($location)) {
            $diff['status'] = 'new_entity';
            $location = $this->extract_info_from_location();
            $info_source = $entity;
        } elseif (is_null($entity)) {
            $diff['status'] = 'additional_location';
            $entity = $this->extract_info_from_entity();
            $info_source = $location;
        } else {
            $diff['status'] = 'match';
            $info_source = $location;
            $match = $entity;
        }

        $diff['info'] = array(
    		'id'   => $info_source['id'],
    		'name' => $info_source['name'],
        );
        
        if (!is_null($match)) {
            $diff['info']['match_id'] = $match['id'];
        }

        foreach (self::$diff_fields as $field) {
            if ($location[$field] != $entity[$field]) {
                $diff['diffs'][$field] = array(
                    'location' => $location[$field],
                    'micello'  => $entity[$field],
                );
            }
        }
        
        return $diff;
    }
    
    private function extract_info_from_location($location = NULL) {
        return array(
            'id'          => $location ? "location_{$location->id}" : '',
            'name'        => $location ? $location->name : '',
            'entity_id'   => $location && $location->micello_info ? $location->micello_info->micello_id : '',
            'geometry_id' => $location && $location->micello_info ? $location->micello_info->geometry_id : '',
            'floor'       => $location ? $location->floor : '',
            'phone'       => $location ? $location->phone : '',
            'email'       => $location ? $location->email : '',
            'url'         => $location ? $location->web : '',
            'description' => $location && !is_null($location->description) ? $location->description : '',
            'match_found' => FALSE,
        );
    }

    private function extract_info_from_entity($entity = NULL) {
        $info = array(
            'id'          => $entity ? "entity_{$entity->data->gid}" : '',
            'name'        => $entity ? $entity->data->nm : '',
            'entity_id'   => $entity ? (string)$entity->data->eid : '',
            'geometry_id' => $entity ? (string)$entity->data->gid : '',
            'floor'       => $entity ? $entity->data->lnm : '',
            'phone'       => '',
            'email'       => '',
            'url'         => '',
            'description' => '',
            'match_found' => FALSE,
        );
        
        if ($entity && isset($entity->info)) {
            foreach($entity->info as $info_field) {
                $info[$info_field->name] = $info_field->value;
            }
        }
        
        return $info;
    }
    
    private function get_edit_url($mall_id) {
       return Uri::create("admin/mall/{$mall_id}/edit", array(), Input::get());
    }

}
