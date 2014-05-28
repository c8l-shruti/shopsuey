<?php

/**
 * The Profiling Choices Controller.
 * This controllers the CRUD proceedures for the CMS profiling choices section
 *
 * @package  app
 * @extends  Controller_Cms
 */
use Fuel\Core\Input;

class Controller_Dashboard_Profilingchoices extends Controller_Cms {

    public function action_index() {
        $profiling_choices = Model_Profilingchoice::query()
                                ->where('deleted', '=', false)
                                ->order_by('order', 'ASC')
                                ->get();
        
        // Set content params
        $content_data = array(
            'profiling_choices' => $profiling_choices,
            'login_hash' => $this->user_login->login_hash
        );
        $content = View::forge('cms/profilingchoices/list', $content_data);
        
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
				'name'   => 'Profiling Choices',
				'subnav' => View::forge('cms/profilingchoices/menu'),
				'icon'   => 'icon-users'
            ),
            'crumbs' => array(
				array('title'=>'Dashboard', 'link' => Uri::create('dashboard')),
				array('title'=>'Profiling Choices', 'link' => Uri::create('dashboard/profilingchoices'))
			),
			'content' => $content,
            'me' => $this->user_login->user
		);
		// Compile view
		$header = View::forge('base/header', $header_data);
		$cont   = View::forge('cms/wrapper', $wrapper_data);
		$footer = View::forge('base/footer');
		$temp   = $header . $cont . $footer;

		return Response::forge($temp);
    }
    
    public function action_update_order() {
        $new_order = Input::post('order', array());
        
        
        if (!empty($new_order)) {
            $formatted_orders = array();
            foreach ($new_order as $profiling_choice_order) {
                $formatted_orders[$profiling_choice_order['id']] = $profiling_choice_order['order'];
            }
            
            $profiling_choices = Model_Profilingchoice::query()
                                ->where('deleted', '=', false)
                                ->order_by('order', 'ASC')
                                ->get();
        
            $totalSaved = 0;
            foreach ($profiling_choices as $profiling_choice) {
                if (isset($formatted_orders[$profiling_choice->id]) && $profiling_choice->order != $formatted_orders[$profiling_choice->id]) {
                    $profiling_choice->order = $formatted_orders[$profiling_choice->id];
                    $profiling_choice->save();
                    $totalSaved++;
                }
            }
        }
        
        return Response::forge(
            json_encode(array('success'=> true, 'total_updated' => $totalSaved))
        );
    }
    
    public function action_edit() {
        return $this->action_add('edit');
    }
    
    public function action_add($action = 'create') {
        if ($action == 'edit') {
            $id = $this->param('id');
            if (!$id || !($profiling_choice = CMS::profiling_choice($id))) {
                return $this->error_404();
            }
        } else {
            $profiling_choice = new Model_Profilingchoice();
            $profiling_choice->deleted = false;
        }
	
        $content_data = array(
            'profiling_choice' => $profiling_choice,
            'action'           => $action
        );
        
		if (Input::method() == 'POST') {
            // Check if the user deleted the profiling choice:
            $status = Input::post('status', null);
            
            $profiling_choice->name = Input::post('name');
            
            // $status = 1 == Deleted
            if (!is_null($status) && $status == 1) {
                $profiling_choice->logic_delete($this->user_login->user);
                $profiling_choice->save();
                
                return Response::redirect("dashboard/profilingchoices");
            }
            
            $category_ids = Input::post('category_ids', array());
            
            if (count($category_ids) > 3) {
                $this->msg = array('type' => 'fail', 'message' => 'Up to three categories can be selected.');
                return $this->_display_form($action, $content_data);
            }
            
            // Update categories
            foreach ($profiling_choice->categories as $category) {
                unset($profiling_choice->categories[$category->id]);
            }
            
            if (!empty($category_ids)) {
                $categories = Model_Category::query()->where('id', 'IN', $category_ids)->get();
                $profiling_choice->categories = $categories;
            }
            
            // Upload
            $uploaded_image = $this->_upload_profiling_choice_image();
            if ($uploaded_image) {
                $s3_image_uri = Helper_Aws::upload_image_to_S3('ss-brands-logos', $uploaded_image['name'], $uploaded_image['file']);
                
                $profiling_choice->url = $s3_image_uri;
            }
            
            if ($profiling_choice->save()) {
                $action_name = ($action == 'create') ? 'created' : 'updated';
                $this->msg   = array('type' => 'success', 'message' => 'Successfully ' . $action_name . ' profiling choice', 'autohide' => true);
            }
            
            return $this->_display_form($action, $content_data);
            
		} else {
			return $this->_display_form($action, $content_data);
		}

		
    }
    
    private function _upload_profiling_choice_image() {
        $config = array(
            'ext_whitelist'  => array('jpg', 'jpeg', 'gif', 'png'),
            'type_whitelist' => array('image'),
        );
        Upload::process($config);

        $uploadedImages = count(Upload::get_files());
        if ($uploadedImages == 0) {
            return false;
        }
        
        if (!Upload::is_valid()) {
            $files = Upload::get_errors();
            
            $errors = array();
            if (count($files) > 0) {
                $file = array_shift($files);
                foreach($file['errors'] as $error) {
                    throw new Exception('An error ocured while uploading the image');
                }
            } else {
                return false;
            }
        }
        
        // Get the first uploaded file
        $file = Upload::get_files(0);
        $file['content'] = base64_encode(file_get_contents($file['file']));

        $path = Helper_Images_Profilingchoices::prepare_image_for_S3($file, Input::post());
        
        return array(
            'name' => $file['name'],
            'file' => $path 
        );
    }
    
    
    private function _display_form($action, $content_data) {
        $content = View::forge('cms/profilingchoices/edit', $content_data);
        
        // Include .js
		$apnd = array('files/base.js', 'files/users.js');
		$excl = array('autotab', 'cleditor', 'dualist', 'elfinder', 'fullcalendar', 'flot', 'maskedinput', 'tagsinput', 'upload');
		$scripts = CMS::scripts($apnd, NULL, $excl);

		// Set header params
		$header_data = array(
			'style'   => array('styles.css', 'autoSuggest.css'),
			'scripts' => $scripts,
			'ie'      => 'ie.css',
            'styles' => 'jquery.Jcrop.min.css',
		);

		$wrapper_data = array(
			'page' => array(
				'name' => ($action == 'add') ? 'Profiling Choice Creator' : 'Profiling Choice Editor',
				'subnav' => View::forge('cms/profilingchoices/menu'),
				'icon' => 'icon-contact'),

			'crumbs' => array(
				array('title'=>'Dashboard', 'link' => Uri::create('dashboard')),
				array('title'=>'Profiling Choices', 'link' => Uri::create('dashboard/profilingchoices')),
				array('title'=>($action == 'edit') ? 'Edit' : 'Add', 'link' => '#'),
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
    
}
