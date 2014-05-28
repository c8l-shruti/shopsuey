<?php

/**
 * The Admin Controller.
 * 
 * @package  app
 * @extends  Controller_Cms
 */
 
class Controller_Admin extends Controller_Cms {

	public function action_index() {
		// Include .js
		$apnd = array('files/base.js');
		$excl = array('autotab', 'cleditor', 'dualist', 'elfinder', 'fullcalendar', 'flot', 'maskedinput', 'tagsinput', 'upload', 'wizards');
		$scripts = CMS::scripts($apnd, NULL, $excl);
		
		// Set header params
		$header_data = array(
			'style' => 'styles.css',
			'scripts' => $scripts,
			'ie' => 'ie.css'
		);
		
		// Set content params
		$content_data = array();
		
		$wrapper_data = array(
			'page' => array('name' => 'Administration'),
			
			'crumbs' => array(
				array('title'=>'Admin')),
				
			'me' => $this->user_login->user,
			
			'message' => $this->msg,
			
			'content' => View::forge('cms/admin/index', $content_data)
		);
		
		// Compile view
		$header = View::forge('base/header', $header_data);
		$cont = View::forge('cms/wrapper', $wrapper_data);
		$footer = View::forge('base/footer');
		$temp = $header . $cont . $footer;
		
		return Response::forge($temp);
	}
}