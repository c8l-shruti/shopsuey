<?php

/**
 * The Welcome Controller.
 *
 * A basic controller example.  Has examples of how to set the
 * response body and status.
 * 
 * @package  app
 * @extends  Controller_Cms
 */
class Controller_Welcome extends Controller_Cms {
	
	/**
	 * The basic welcome message
	 * 
	 * @access  public
	 * @return  Response
	 */
	public function action_index() {
		$auth = Auth::instance('Shopsuey_Session');

		if ($auth->user_logged_in()) {
		    $user = $this->user_login->user;
		    if ($user->is_new_user()) {
		        return Response::redirect('setup/profile/signup');
		    } else {
			    return Response::redirect(Config::get('cms.landing_page'));
		    }
		} else {
			return Response::redirect('login');
		}
	}
	
	public function action_404() {
		return $this->error_404();
	}
}
