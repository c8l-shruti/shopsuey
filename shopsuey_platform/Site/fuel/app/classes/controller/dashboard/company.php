<?php

/**
 * The Company Controller.
 * This controllers the CRUD proceedures for the CMS company section
 *
 * @package  app
 * @extends  Controller_Cms
 */

class Controller_Dashboard_Company extends Controller_Cms {

	public function action_edit() {
	    $company = $this->get_current_company();
	    $location_id = $company ? $company->id : NULL;
        $location = CMS::location($location_id);
        if (!$location) {
            return $this->error_404();
        }

		if (Input::post()) {
            return $this->location_update($location);
		}

		$location->category_ids = array();
		foreach ($location->categories as $category) {
		    $location->category_ids[] = $category->id;
		}
		
		$content_data = $this->_build_content_data($location);

		return $this->form_edit($content_data);
	}

	public function action_images() {
	    $company = $this->get_current_company();
	    $location_id = $company ? $company->id : NULL;
	    $location = CMS::location($location_id);
	    if (!$location) {
	        return $this->error_404();
	    }
	     
	    if (\Input::method() == 'POST') {
	        $data = array(
        		'use_instagram' => \Input::post('use_instagram', FALSE),
	        );
	         
	        $set_user_account = \Input::post('use_instagram', FALSE);

	        $user = $this->user_login->user;
	        if ($set_user_account && $user->instagram) {
	            $data['user_instagram_id'] = $user->instagram->id;
	        }

	        $url = Uri::create("api/location/{$location->id}");
	        
	        $this->api->setData($data);
	        $this->api->setMethod('PUT');
	        $this->api->setURL($url);
	        
	        $output = $this->api->execute();
	        
	        if (@$output->meta->status == 1) {
	        	$msg = array('type' => 'success', 'message' => 'Successfully updated company', 'autohide' => true);
	        	$redirect_to = "dashboard";
	        } else {
	        	$err = @$output->meta->error;
	        	if (!$err) { $err = 'Error: 900 - Unable to process request'; }
	        	$msg = array('type' => 'fail', 'message' => $err);
	        	$redirect_to ="dashboard/company/images";
	        }

        	Session::set('message', $msg);
	        return Response::redirect($redirect_to);
	    } else {
    	    return $this->images_form($location);
	    }
	}
	
	public function action_image_upload() {
	    $files = $this->process_files();
	    $file = array_shift($files);

        $file['content'] = base64_encode(file_get_contents($file['file']));
    
        $file_name = Helper_Images_Temporary::copy_one_image_from_params($file);
        $folder = Config::get('cms.temporary_images_path');

        $aspect_ratio = Input::post('aspect_ratio', '1');
        
        $image = Image::load(Config::get('file.areas.assets.basedir') . "/$folder/$file_name");
        $sizes = $image->sizes();

        $image->resize($sizes->height * $aspect_ratio, $sizes->height, true, true)
            ->config('bgcolor', '#fff')
            ->save(Config::get('file.areas.assets.basedir') . "/$folder/$file_name");
        $sizes = $image->sizes();
        
        $response = array(
            'success' => TRUE,
            'url'     => Asset::get_file($file_name, 'img', $folder),
            'path'    => "/$folder/$file_name",
            'width'   => $sizes->width,
            'height'  => $sizes->height,
        );

        $this->response->set_header('Cache-Control', 'no-cache, must-revalidate');
        $this->response->set_header('Expires', 'Mon, 26 Jul 1997 05:00:00 GMT');
        $this->response->set_header('Content-Type', 'application/json');
        return \Format::forge($response)->to_json();
	}
	
	public function action_image_crop() {
	    $company = $this->get_current_company();
	    $location_id = $company ? $company->id : NULL;
	    $location = CMS::location($location_id);
	    if (!$location) {
	        return $this->error_404();
	    }

	    $path = Input::post('image');

	    $image = Image::load(Config::get('file.areas.assets.basedir') . $path);
	     
	    $sizes = $image->sizes();

	    $x1 = Input::post('x1');
	    $y1 = Input::post('y1');
	    $x2 = Input::post('x2');
	    $y2 = Input::post('y2');
	    $preview_width = Input::post('preview_width');
	    $preview_height = Input::post('preview_height');

	    $x_ratio = $sizes->width / $preview_width;
	    $y_ratio = $sizes->height / $preview_height;

	    // Overwrite tmp image with cropped version
	    $image->crop(round($x1 * $x_ratio), round($y1 * $y_ratio), round($x2 * $x_ratio), round($y2 * $y_ratio))
            ->save(Config::get('file.areas.assets.basedir') . $path);

	    if (preg_match('/.+\.(jpg|jpeg|png|gif)/i', $path, $matches)) {
	        $extension = strtolower($matches[1]);
	    } else {
	        $msg = array('type' => 'error', 'message' => 'Invalid image format', 'autohide' => true);
	        Session::set('message', $msg);
	        return Response::redirect("dashboard/company/images");
	    }

	    $file = array(
            'content' => base64_encode(File::read(Config::get('file.areas.assets.basedir') . $path, true)),
            'extension' => $extension,
	    );

	    // Get image type
	    $type = Input::post('type');
        $params = array();
        
	    if ($type == 'logo') {
	        $image_url = Helper_Images_Logos::copy_one_image_from_params($file);
	        $params['logo'] = $image_url;
	    } elseif ($type == 'landing') {
	        $image_url = Helper_Images_Landing::copy_one_image_from_params($file);
	        $params['landing'] = $image_url;
	    } elseif ($type == 'explore') {
	        $image_url = Helper_Images_Explore::copy_one_image_from_params($file);
	        $params['explore'] = $image_url;
	    }

	    $url = Uri::create("api/location/{$location_id}/update_images");
	    
	    $this->api->setData($params);
	    $this->api->setMethod('PUT');
	    $this->api->setURL($url);
	    
	    $output = $this->api->execute();
	    
	    if (@$output->meta->status == 1) {
    	    $msg = array('type' => 'success', 'message' => 'Successfully updated image', 'autohide' => true);
    	    Session::set('message', $msg);
    	    return Response::redirect("dashboard/company/images");
	    } else {
	        $err = @$output->meta->error;
	        if (!$err) { $err = 'Error: 900 - Unable to process request'; }
    	    $msg = array('type' => 'error', 'message' => $err, 'autohide' => true);
	        Session::set('message', $msg);
    	    return Response::redirect("dashboard/company/images");
	    }
	    
	    $msg = array('type' => 'success', 'message' => 'Successfully updated image', 'autohide' => true);
	    Session::set('message', $msg);
	    return Response::redirect("dashboard/company/images");
	}
	
	private function form_edit($content_data) {
	    
	    $content = View::forge('cms/company/edit', $content_data);

		// Include .js
		$apnd = array('files/base.js', 'files/users.js', 'files/timezones.js', 'files/timezones.js');
		$excl = array('autotab', 'cleditor', 'dualist', 'elfinder', 'fullcalendar', 'flot', 'maskedinput', 'tagsinput', 'upload');
		$scripts = CMS::scripts($apnd, NULL, $excl);

		// Set header params
		$header_data = array(
			'style' => 'styles.css',
			'scripts' => $scripts,
			'ie' => 'ie.css'
		);
		
		if ($content_data['location']->type == 'Mall') {
		    $mall = CMS::mall($content_data['location']->id);
		    
		    $subnav = View::forge('cms/company/menu', array(
        		'search_engines' => View::forge('cms/company/utility', array(
        		    'login_hash' => $this->user_login->login_hash,
    		    )),
    		    'logo' => $content_data['logo'],
    		    'merchant_count' => $mall->merchant_count,
		    ));
		} else {
		    $subnav = View::forge('cms/company/menu', array(
		        'search_engines' => View::forge('cms/company/utility', array(
		            'login_hash' => $this->user_login->login_hash,
		        )),
		        'logo' => $content_data['logo'],
		    ));
		}

		$wrapper_data = array(
			'page' => array(
				'name' => 'Profile Editor',
				'subnav' => $subnav,
				'icon' => 'icon-contact'),

			'crumbs' => array(
				array('title'=>'Dashboard', 'link' => Uri::create('dashboard')),
				array('title'=>'Edit Company Profile', 'link' => '#'),
			),

			'me' => $this->user_login->user,
			'message' => $this->msg,
			'content' => $content,
	        'company' => $this->get_current_company(),
		);
		
		// Compile view
		$header = View::forge('base/header', $header_data);
		$cont = View::forge('cms/wrapper', $wrapper_data);
		$footer = View::forge('base/footer');
		$temp = $header . $cont . $footer;

		return Response::forge($temp);
	}

	private function images_form($location) {

	    $location_instagram_info = $this->get_instagram_info_for_location($location);
        $user_instagram_info = $this->get_instagram_info_for_user($this->user_login->user);
        $instagram_callback = \Input::get('instagram_callback', FALSE);
        
        $instagram_set = $location_instagram_info->set || $user_instagram_info->set;
        if ($instagram_set) {
            if ($user_instagram_info->set && ($instagram_callback || !$location_instagram_info->set)) {
                $instagram_latest_post = $user_instagram_info->latest_post;
                $username = $user_instagram_info->username;
            } else {
                $instagram_latest_post = $location_instagram_info->latest_post;
                $username = $location_instagram_info->username;
            }
        } else {
            $instagram_latest_post = NULL;
            $username = NULL;
        }

	    $content_params = array(
	        'location' => $location,
	        'instagram_set' => $instagram_set,
	        'instagram_latest_post' => $instagram_latest_post,
            'instagram_username' => $username,
            'instagram_auth_url' => $user_instagram_info->auth_url,
            'instagram_callback' =>  $instagram_callback,
            'instagram_logout_url' => $user_instagram_info->logout_url,
	    );
	    $content = View::forge('cms/company/images', $content_params);
	
	    // Include .js
	    $apnd = array('files/base.js', 'files/users.js');
	    $excl = array('autotab', 'cleditor', 'dualist', 'elfinder', 'fullcalendar', 'flot', 'maskedinput', 'tagsinput', 'upload');
	    $scripts = CMS::scripts($apnd, NULL, $excl);
	
	    // Set header params
	    $header_data = array(
            'style' => 'styles.css',
            'scripts' => $scripts,
            'ie' => 'ie.css',
            'styles' => 'jquery.Jcrop.min.css',
	    );
	
	    $wrapper_data = array(
            'page' => array(
                'name' => 'Images Management',
                'subnav' => View::forge('cms/company/images_utility', array(
                    'location' => $location,
                )),
                'icon' => 'icon-contact'),

            'crumbs' => array(
                array('title'=>'Dashboard', 'link' => Uri::create('dashboard')),
                array('title'=>'Edit Company Images', 'link' => '#'),
            ),

            'me' => $this->user_login->user,
            'message' => $this->msg,
            'content' => $content,
	        'company' => $this->get_current_company(),
	    );
	    // Compile view
	    $header = View::forge('base/header', $header_data);
	    $cont = View::forge('cms/wrapper', $wrapper_data);
	    $footer = View::forge('base/footer');
	    $temp = $header . $cont . $footer;
	
	    return Response::forge($temp);
	}
	
	private function location_update($location) {
		$post = Input::post();
		$data = array();
		
		foreach($post as $field => $val) {
		    if ($field == 'hours') {
		        $val = $this->process_hours_info($val);
		    }
		    $data[$field] = $val;
		}
		
        if (!($location->type == Model_Location::TYPE_MERCHANT && is_null($location->mall_id))) {
            // It isn't a stand alone merchant, so I'll not allow them to update their coordinates
            if (isset($data['latitude']))  unset($data['latitude']);
            if (isset($data['longitude'])) unset($data['longitude']);
        }
        
		if (!$this->validate_hours(Input::post('hours'))) {
		    $this->msg = array('type' => 'fail', 'message' => 'Please input valid opening and closing times for each day.');
		    return $this->form_edit($this->_build_error_content_data($location, $post, $data));
		}

		if (count(Input::post('category_ids', array())) > 3) {
		    $this->msg = array('type' => 'fail', 'message' => 'Up to three categories can be selected.');
			return $this->form_edit($this->_build_error_content_data($location, $post, $data));
		}
		
		$default_social = isset($post['default_social']) && $post['default_social'];
		$this->process_default_social($data, $default_social);
		
		$url = Uri::create("api/location/{$location->id}");
        
		$this->api->setData($data);
		$this->api->setMethod('PUT');
		$this->api->setURL($url);

		$output = $this->api->execute();

		if (@$output->meta->status == 1) {
			$msg = array('type' => 'success', 'message' => 'Successfully updated company', 'autohide' => true);
			Session::set('message', $msg);
			return Response::redirect("dashboard/company/images");
		} else {
			$err = @$output->meta->error;
			if (!$err) { $err = 'Error: 900 - Unable to process request'; }
			$this->msg = array('type' => 'fail', 'message' => $err);

			return $this->form_edit($this->_build_error_content_data($location, $post, $data));
		}
	}

	private function _build_content_data($location) {
		// Set content params
		$to_return = array(
			'location' => $location,
	        'hours' => $this->create_hours_groups($location),
	        'logo' => $location->logo,
            'first_time' => Session::get('first_time', false),
            'timezones' => Helper_Timezone::get_timezone_list(true),
		);
        
        Session::delete('first_time');
        
        return $to_return;
	}
	
	private function _build_error_content_data($location, $post, $data) {
	    $post = (object)$post;
	    $post->type = $location->type;
	    return array(
            'location' => $post,
            'hours' => $this->create_hours_groups($data),
            'logo' => $location->logo,
            'first_time' => FALSE,
	    );
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
}
