<?php

/**
 * This the version of the shopsuey auth driver for stateless logins.
 * 
*/

class Auth_Login_Shopsuey_Stateless extends Auth_Login_Shopsuey {

	protected function perform_check() {
		$login_hash  = \Input::param('login_hash');
	
		if (! empty($login_hash)) {
		    $active_statuses = array(
        		\Model_User::STATUS_ACTIVE,
	    		\Model_User::STATUS_STEP1,
                        \Model_User::STATUS_STEP2,
                        \Model_User::STATUS_STEP3,
		    );
		    
			if (is_null($this->user_login)) {
				$this->user_login = \Model_User_Login::query()
					->related('user')
					->where('login_hash', $login_hash)
					->where('user.status', 'in', $active_statuses)
					->and_where_open()
					->or_where('expiracy', NULL)
					->or_where('expiracy', '>', date('Y-m-d H:i:s'))
					->and_where_close()
					->get_one();
			}
	
			// Check if the login was verified
			if ($this->user_login) {
				// An additional check to make sure that the login hash can be generated again. This is useful to invalidate
				// all sessions created with an application secret and then it must me changed for some reason
				if($this->_create_login_hash($this->user_login->user, $this->user_login->application, $this->user_login->created_at) == $login_hash) {
					return TRUE;
				}
			}
		}

		$this->user_login = new \Model_User_Login();
		$user = new \Model_User();
		$user->group = 0;
		$this->user_login->user = $user;
		return TRUE;
	}
	
	public function login($email = '', $password = '') {
		if (($user = $this->validate_user($email, $password)) === FALSE) {
			return FALSE;
		}

		return $this->_create_user_login($user);
	}

	public function force_login($user) {
		return $this->_create_user_login($user);
	}
}