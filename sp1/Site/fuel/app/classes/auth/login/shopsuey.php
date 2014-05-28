<?php

/**
 * This is a slightly different implementation of simpleauth driver.
 * The main difference is the usage of an apps table and a table
 * to register the login of a user on a given application.
 * 
*/

abstract class Auth_Login_Shopsuey extends \Auth_Login_Driver {

	public static function _init() {
		\Config::load('shopsueyauth', true, true, true);
	}

	protected $user_login = null;
	protected $app_id = null;
	
	protected $config = array(
			'drivers' => array('group' => array('Shopsuey')),
	);

	public function validate_user($email = '', $password = '') {
		if (empty($email) || empty($password)) {
			return FALSE;
		}
		
		$active_statuses = array(
		    \Model_User::STATUS_ACTIVE,
		    \Model_User::STATUS_STEP1,
		    \Model_User::STATUS_STEP2,
		    \Model_User::STATUS_STEP3
		);
	
		$hashed_password = $this->hash_password($password);
		$user = \Model_User::query()
			->where('email', $email)
			->where('password', $hashed_password)
			->where('status', 'in', $active_statuses)
			->get_one();
	
		return $user ?: FALSE;
	}
	
	protected function _create_login_hash($user, $application, $timestamp) {
		$login_hash = sha1(\Config::get('shopsueyauth.login_hash_salt') . $user->id . $application->secret . $timestamp);
		return $login_hash;		
	}

	protected function _create_user_login($user, $set_expiracy = FALSE) {
		if (is_null($this->app_id)) {
			$this->app_id = \Input::param('app_id');
		}
		$application = \Model_Application::query()->where('token', $this->app_id)->get_one();
		if (! $application) {
			return FALSE;
		}
		// TODO: Probably it would be a good idea to delete previous logins for the same user/app.
		// However, this would cause other sessions with the same app to be overriden

		$this->user_login = new Model_User_Login();
		$this->user_login->user = $user;
		$this->user_login->application = $application;
		$created_at = time();
		$this->user_login->created_at = $created_at;
		$this->user_login->login_hash = $this->_create_login_hash($user, $application, $created_at);
		$this->user_login->expiracy = $set_expiracy ? date('Y-m-d H:i:s', strtotime(\Config::get('shopsueyauth.expiracy_time'))) : NULL;
		$this->user_login->ip = Input::ip();

		return $this->user_login->save() ? TRUE : FALSE;
	}

	public function logout() {
		$this->user_login->delete();		
	}
	
	public function get_user_id() {
		if (empty($this->user_login)) {
			return FALSE;
		}
	
		return array($this->id, $this->user_login->user_id);
	}
	
	public function get_groups() {
		if (empty($this->user_login)) {
			return FALSE;
		}
	
		return array(array('Shopsuey', $this->user_login->user->group));
	}
	
	public function get_email() {
		if (empty($this->user_login)) {
			return FALSE;
		}
	
		return $this->user_login->user->email;
	}
	
	public function get_screen_name() {
		if (empty($this->user_login)) {
			return FALSE;
		}
	
		return $this->user_login->user->email;
	}
	
	/**
	 * Extension of base driver method to default to user group instead of user id
	 */
	public function has_access($condition, $driver = null, $user = null) {
		if (is_null($user)) {
			$groups = $this->get_groups();
			$user = reset($groups);
		}
		return parent::has_access($condition, $driver, $user);
	}
	
	// Guest users will be used for public areas
	public function guest_login() {
		return TRUE;
	}
	
	public function get_user_login_object() {
		return $this->user_login;
	}
	
	public function set_app_id($app_id) {
		$this->app_id = $app_id;
	}
	
	/**
	 * Checks if there is a user logged in
	 */
	public function user_logged_in() {
		return !empty($this->user_login->user_id);
	}
}