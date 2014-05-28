<?php

/**
 * The Log Out Controller.
 * This controller logs out the current user by destroying the session
 * 
 * @package  app
 * @extends  Controller_Cms
 */
class Controller_Logout extends Controller_Cms {
	
	public function action_index() {
		$auth = Auth::instance('Shopsuey_Session');
		$auth->logout();
		return Response::redirect('login');
	}
}
