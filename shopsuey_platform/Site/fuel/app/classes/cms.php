<?php

/**
 * The CMS Controller.
 *
 * This controller holds functions related to the CMS portion of the site
 *
 * @package app
 * @extends Controller
 */
class CMS extends Controller {

	public static function access_key() {
		$user = CMS::current_user();
		if ($user) {
			$qry = DB::select('access_key')->from('users')->where('id', '=', $user->id)->execute();
			return @$qry[0]['access_key'];
		}
	}

	public static function can($perm, $id = null) {
		if (!$id) { $id = CMS::current_user('id'); }
		return CMS::get_user_meta($id, $perm);
	}

	public static function clear_permissions($id = null) {
		if (!$id) { $user = CMS::current_user(); $id = $user->id; }
		if (!$id) { return; }
		DB::delete('usermeta')->where('user_id', '=', $id)->and_where('meta_key', 'like', 'can_manage_%')->execute();
	}

	public static function comment($user_id, $parent_id, $type, $comment, $before = '', $after = '') {
		$data = array('type' => $type, 'user_id' => $user_id, 'parent_id' => $parent_id, 'comment' => $comment, 'before' => '', 'after' => '');
		if ($before) { $data['before'] = json_encode($before); }
		if ($after) { $data['after'] = json_encode($after); }

		DB::insert('comments')->set($data)->execute();
	}

	public static function comments($parent_id, $type) {
		$qry = DB::select()->from('comments')->where('parent_id', '=', $parent_id)->and_where('type', '=', $type)->execute();
		return $qry;
	}

	public static function create_nonce($name) {
		$session_id = Session::key();

		$results = DB::select('nonce')->from('nonce')->where('session_id', '=', $session_id)->and_where('name', '=', $name)->execute();
		$item = $results[0];

		if (isset($item['nonce'])) { $val = $item['nonce']; }
		else {
			$salt = Config::get('cms.salt');
			$val = base64_encode(hash_hmac('sha256', uniqid(), $salt, true));

			DB::insert('nonce')->set(array('name' => $name, 'nonce' => $val, 'session_id' => $session_id))->execute();
		}
		return $val;
	}

	public static function create_nonce_field($nonce_name, $field_name, $field_id = null) {
		$nonce = CMS::create_nonce($nonce_name);
		$id = ($field_id == null) ? $nonce_name : $field_id;
		return Form::hidden(array('name' => $field_name, 'value' => $nonce, 'id' => $id));
	}

	// Removes unused keys from key table
	public static function cull($action = null) {
		switch($action) {
			case 'keys':
				$keys = array();
				$sql = "SELECT `suey_keys`.`id` FROM `suey_keys` JOIN `suey_users` ON (`suey_keys`.`key` = `suey_users`.`access_key`)";
				$qry = DB::query($sql)->execute();

				foreach($qry as $item) { array_push($keys, $item['id']); }

				$keys = implode(',', $keys);
				$sql = "DELETE FROM suey_keys WHERE id NOT IN ('$keys') LIMIT 100";
				$rows = DB::query($sql)->execute();

				break;

			case 'nonce':
				$qry = DB::select()->from('nonce')->join('sessions')->on('nonce.session_id', '=', 'sessions.previous_id')->on('nonce.session_id', '!=', 'sessions.session_id')->execute();

				$items = array();
				foreach($qry as $item) { array_push($items, $item); }
				//$qry = DB::query($sql)->execute();
				echo json_encode($items);
				break;

		}
	}

	public static function current_user($field = null) {
		$auth = Session::get('auth');
		if (isset($auth)) {
			$suser = Session::get('user');
			$user = CMS::user($suser['id'], 'object');

			if ($field) { return $user->$field; }
			else { return $user; }
		}
		return null;
	}

	public static function delete_nonce($val) {
		DB::delete('nonce')->where('nonce', '=', $val)->limit(1)->execute();
	}

	public static function delete_user_meta($id, $key, $value = null) {
		if (!$value) { DB::delete('user_metafields')->where('user_id', '=', $id)->and_where('key', '=', $key)->execute(); }
		else { DB::delete('user_metafields')->where('user_id', '=', $id)->and_where('key', '=', $key)->and_where('value', '=', $value)->execute(); }
	}

	public static function email($to = null, $from = null, $subject = 'ShopSuey', $data, $template) {
		if (!$to) { return false; }
		else { if (!is_array($to)) { $to = array('email' => $to, 'name' => $to); } }

		$from = (!$from) ? array('email' => 'no-reply@thesuey.com', 'name' => 'ShopSuey Admin') : $from;

		// Load the email package
		\Package::load('email');

		// Email Template Data
		$template = View::forge($template, $data);

		// Email instance
		$email = Email::forge();
		$email->from($from['email'], $from['name']);
		$email->to($to['email'], $to['name']);
		$email->subject($subject);
		$email->html_body($template);
		$email->header('X-SMTPAPI', '{
                  "filters": {
                    "subscriptiontrack": {
                      "settings":
                        {
                          "enable": 0
                        }
                    }
                  }
                }
		');

		// Send the email
		try {
			$email->send();
			$data = array('data' => $data, 'meta' => array('error' => '', 'status' => 1));
		}
		catch(\FuelException $e) {
			// Unable to send the mail (smtp server down or credentials rejected probably)
            Log::warning('An error occurred while trying to send an email to: ' . $to['email'] . ' (subject: ' . $subject . ')');
		}

		return $data;
	}

	public static function get_app($appid) {
		$app = DB::select()->from('applications')->where('token', '=', $appid)->limit(1)->execute();
		if (isset($app[0])) { return $app[0]; }
	}

	public static function get_groups() {
		$groups = Config::get('simpleauth.groups');

		$output = array();
		foreach($groups as $level => $group) {
			//array_push($output, (object) array('level' => $level, 'name' => $group['name'], 'roles' => $group['roles']));
			if (isset($output[$group['name']])) { continue; }
			$output[$group['name']] = (object) array('level' => $level, 'roles' => $group['roles']);
		}
		return (object) $output;
	}

	public static function get_merchant_by($field, $value, $type = 'object') {
		$qry = DB::select()->from('merchants')->where($field, '=', $value)->limit(1)->execute();
		if (isset($qry[0])) {
			$merchant = $qry[0];
			$merchant['hours'] = json_decode(stripslashes($merchant['hours']));
			$merchant['social'] = json_decode(stripslashes($merchant['social']));

			if ($type == 'object') { $merchant = (object) $merchant; }
			return $merchant;
		}
		return;
	}

	public static function get_note() {
		$data = CMS::get_user_meta(1, 'note');
		CMS::delete_user_meta(1, 'note');
		return $data;
	}

	public static function get_user_by($field, $value, $type = 'array') {

		$qry = DB::select('id', 'username', 'email', 'group', 'created_at')->from('users')->where($field, '=', $value)->and_where('status', '=', 1)->limit(1)->execute();
		if (isset($qry[0])) {
			$user = $qry[0];

			if (isset($user)) {

				$user['level'] = $user['group'];
				$user['group'] = CMS::user_group($user['group']);

				$user['meta'] = array();

				ksort($user);

				$jfields = array('dob');
				$fields = Config::get('cms.user_meta_fields');
				foreach($fields as $field) {
					$value = CMS::get_user_meta($user['id'], $field);
					if (in_array($field, $jfields)) { $value = (object) $value; }
					$user['meta'][$field] = $value;
				}
				$user['meta']['fullname'] = trim($user['meta']['real_name']);

				ksort($user['meta']);
				$user['meta'] = (object) $user['meta'];

				return (strtolower($type) == 'object') ? (object) $user : $user;
			}
			return;
		}
		return;
	}

	public static function get_user_by_meta($key, $value, $type = 'array') {
		$qry = DB::select('user_id')->from('usermeta')->where('meta_key', '=', $key)->and_where('meta_value', '=', $value)->limit(1)->execute();
		if (isset($qry[0])) {
			$user = CMS::user($qry[0]['user_id'], $type);
			return $user;
		}
	}

	public static function get_user_meta($id = 0, $key, $singular = null) {
		if ($singular !== FALSE) { $singular = TRUE; }
		if ($id == 0) { return; }

		$query = DB::select('value')->from('user_metafields')->where('user_id', '=', $id)->and_where('key', '=', $key)->order_by('id', 'DESC');

		if ($singular == TRUE) {
			$items = $query->limit(1)->execute();
			$output = $items[0]['value'];

			$chr = substr($output, 0, 1);
			if ($chr == '{' || $chr == '[') { $output = json_decode($output, TRUE); }
		}
		else {
			$arr = array();
			$items = $query->execute()->as_array();
			foreach($items as $item) {
				$output = $item['value'];

				$chr = substr($output, 0, 1);
				if ($chr == '{' || $chr == '[') { $output = json_decode($output, TRUE); }
				array_push($arr, $output);
			}
			$output = $arr;
		}

		return $output;
	}

	public static function get_user_permissions($id = null, $type = 'array') {
		if (!$id) { $id = CMS::current_user('id'); }
		if (!$id) { return; }
		$qry = DB::select('meta_key')->from('usermeta')->where('user_id', '=', $id)->and_where('meta_key', 'like', 'can_manage_%')->and_where('meta_value', '=', 1)->execute();
		if (count($qry) > 0) {
			$perms = array();
			foreach($qry as $perm) {
				array_push($perms, $perm['meta_key']);
			}
			if (strtolower($type) == 'string') { $perms = implode(',', $perms); }
			return $perms;
		}
	}

	public static function is_admin($userID = null) {
		$user = ($userID == null) ? CMS::current_user() : (object) CMS::user($userID);
		$roles = array('admin', 'super');
		$valid = false;

		foreach($roles as $role) {
			if ($valid == true) { break; }
			$valid = in_array($role, $user->group->roles);
		}
		return $valid;
	}

	public static function is_associate($username = null, $mename = NULL) {
		if (!$username) { return true; }

		$user = CMS::get_user_by('username', $username, 'object');
		$me = (!isset($mename)) ? CMS::current_user() : CMS::get_user_by('username', $mename, 'object');

		return ($me->meta->company == $user->meta->company) ? true : false;
	}

	public static function is_me($username) {
		$me = CMS::current_user();
		if (is_numeric($username)) { return (bool) ($me->id == $username); }
		else { return (bool) ($username == $me->username); }
	}

	public static function is_moderator($userID = null) {
		$user = ($userID == null) ? CMS::current_user() : (object) CMS::user($userID);
		$roles = array('moderator', 'admin', 'super');
		$valid = false;

		foreach($roles as $role) {
			if ($valid == true) { break; }
			$valid = in_array($role, $user->group->roles);
		}
		return $valid;
	}

	public static function is_super_admin($userID = null) {
		$user = ($userID == null) ? CMS::current_user() : (object) CMS::user($userID);
		return in_array('super', $user->group->roles);
	}

	public static function is_user($username, $type = 'username') {
		$uqry = DB::select('id')->from('users')->where($type, '=', $username)->limit(1)->execute();
		if (isset($uqry[0])) { return 'user name'; }
	}

	public static function jsonize($fields, $data) {
		foreach($fields as $fld) { $data[$fld] = json_encode($data[$fld]); }
		return $data;
	}

	public static function unjsonize($fields, $data) {
		foreach($fields as $fld) { $data[$fld] = json_decode(stripslashes($data[$fld])); }
		return $data;
	}

	public static function local_date($format = 'm/d/Y', $date = null) {
		$date = ($date) ? $date : date('Y-m-d H:i:s');

		$offset = null;

		// get user timezone
		if (Session::get('timezone')) {
			$offset = Session::get('timezone')/60;
		}
		if ($offset) { return date($format, strtotime($offset." hours", strtotime($date)));}
		else { return date($format, strtotime($date)); }
	}

	public static function locations() {
		$data = self::do_api_call('GET', Uri::create("api/locations"), array('pagination' => '0', 'compact' => '1'), TRUE);
		
		if (!is_null($data)) {
			return $data;
		} else {
			return array();
		}
	}
    
    public static function contests() {
        $data = self::do_api_call('GET', Uri::create("api/contests"), array(), TRUE);
        
		if (!is_null($data)) {
			return $data->data->contests;
		} else {
			return array();
		}
    }
    
	public static function main_nav($selected = -1, $prepend = NULL, $append = NULL) {
		// Default links
		$links = array(
			array('title' => 'Dashboard', 	'icon' => 'icons/mainnav/dashboard.png', 	'url' => Uri::create('dashboard')),
	        // TODO: Change this back when the agenda landing page is built 
// 	        array('title' => 'Dashboard', 	'icon' => 'icons/mainnav/dashboard.png', 	'url' => Uri::create('dashboard/agenda')),
			array('title' => 'Offers', 		 'icon' => 'icons/mainnav/deals.png', 		 'url' => Uri::create('dashboard/offers?gallery=1')),
			array('title' => 'Events', 		 'icon' => 'icons/mainnav/events.png', 	 'url' => Uri::create('dashboard/events?gallery=1')),
		);

		$auth = Auth::instance('Shopsuey_Session');
		$user_login = $auth->get_user_login_object();
		
		if ($auth->has_access('admin.index')) {
			$links[] = array('title' => 'Marketplaces', 'icon' => 'icons/mainnav/marketplaces.png', 'url' => Uri::create('admin/malls'));
			$links[] = array('title' => 'Merchants', 	 'icon' => 'icons/mainnav/merchants.png',    'url' => Uri::create('admin/merchants'));
                        $links[] = array('title' => 'Promos', 	    'icon' => 'icons/mainnav/tables.png',    'url' => Uri::create('admin/promos'));
                        $links[] = array('title' => 'Profiling Choices', 'icon' => 'icons/mainnav/tables.png',    'url' => Uri::create('dashboard/profilingchoices'));  
		}

		if ($auth->has_access('dashboard/user.index')) {
			$links[] = array('title' => 'Users', 'icon' => 'icons/mainnav/ui.png', 'url' => Uri::create('dashboard/users'), 'children' => CMS::main_nav_children('users'));
		}

		if ($auth->has_access('admin.index')) {
			$links[] = array('title' => 'Developers', 'icon' => 'icons/mainnav/developers.png', 'url' => Uri::create('developer/docs'));
		}

		// Prepend links
		if (is_array($prepend)) {
			$links = array_merge($prepend, $links);
		}

		// Append links
		if (is_array($append)) {
			$links = array_merge($append, $links);
		}

		?>
		<!-- .mainNav -->
		<div class="mainNav">
		    <ul class="nav">
		    <?php foreach($links as $key => $link) :
			if ($selected == -1) {
			    $current = Uri::current();
			    $active = ($current == $link['url']) ? ' active' : '';

			    if (isset($link['children'])) { $active = (in_array($current, $link['children'])) ? ' active' : ''; }
			}
			else { $active = ($selected == $key) ? ' active' : ''; }
		    ?>
			<li class="<?=($active) ? 'selected' : ''?>"><a href="<?=$link['url']?>" title="" class="btn<?=$active?>"><?=Asset::img($link['icon'])?><span><?=$link['title']?></span></a></li>
		    <?php endforeach; ?>
		    </ul>
		</div>
		<!-- End .mainNav -->
        <?php
	}

	public static function location($id) {
        $data = self::do_api_call('GET', Uri::create("api/location/$id"));
        
        if (!is_null($data)) {
            return $data->location;
        } else {
            return NULL;
        }
	}

	public static function location_instagram_feed($location_id) {
		$data = self::do_api_call('GET', Uri::create("api/location/$location_id/instagram_feed"));
	
		if (!is_null($data)) {
			return $data;
		} else {
			return array();
		}
	}

	public static function location_micello_entities($location_id, $include_entity_info = FALSE) {
        $params = array(
            'location_id' => $location_id,
            'include_entity_info' => $include_entity_info ? '1' : '0',
        );
		$data = self::do_api_call('GET', Uri::create("api/location/micello_entity"), $params, TRUE);
	
		if (isset($output->meta) && !$output->meta->status) {
            return NULL;
        }

		return !empty($data) ? $data : array();
	}
	
	public static function location_with_merchants($location_id) {
		$data = self::do_api_call('GET', Uri::create("api/location/$location_id/merchants_at_mall"));
	
		if (!is_null($data)) {
			return $data->location;
		} else {
			return NULL;
		}
	}
	
	public static function get_offer($id) {
		$data = self::do_api_call('GET', Uri::create("api/offer/$id"), array('include_offer_codes' => 1));
	
		if (!is_null($data)) {
			return $data->offer;
		} else {
			return NULL;
		}
	}

	public static function get_offer_code($id) {
		$data = self::do_api_call('GET', Uri::create("api/offer/code/$id"));
	
		if (!is_null($data)) {
			return $data->offer_code;
		} else {
			return NULL;
		}
	}
	
	public static function mall($id) {
		$data = self::do_api_call('GET', Uri::create("api/mall/$id"));
		
		if (!is_null($data)) {
			return $data->mall;
		} else {
			return NULL;
		}
	}

	public static function malls() {
		$data = self::do_api_call('GET', Uri::create("api/malls"), array('pagination' => '0'));
		
		if (!is_null($data)) {
			return $data->malls;
		} else {
			return NULL;
		}
	}

	public static function malls_by_id($ids) {
        $params = array(
            'compact' => '1',
            'ids' => $ids,
        );

        $restful = new Restful();
        $restful->setAppid(Config::get('cms.appid'));
        
        $auth = Auth::instance('Shopsuey_Session');
        $user_login = $auth->get_user_login_object();
        
        $restful->setLoginHash($user_login->login_hash);
        
        $restful->setMethod('GET');
        $restful->setURL(Uri::create("api/malls"));
        
        $restful->setData($params);
        
        $output = $restful->execute();

	    if ($output) {
	        return $output;
	    } else {
	        return NULL;
	    }
	}

	public static function locations_by_id($ids) {
	    $params = array(
            'compact' => '1',
            'ids' => $ids,
            'pagination' => '0',
	    );
	
	    $restful = new Restful();
	    $restful->setAppid(Config::get('cms.appid'));
	
	    $auth = Auth::instance('Shopsuey_Session');
	    $user_login = $auth->get_user_login_object();
	
	    $restful->setLoginHash($user_login->login_hash);
	
	    $restful->setMethod('GET');
	    $restful->setURL(Uri::create("api/locations"));
	
	    $restful->setData($params);
	
	    $output = $restful->execute();
	
	    if ($output) {
	        return $output;
	    } else {
	        return NULL;
	    }
	}
	
	public static function merchant($id) {
		$data = self::do_api_call('GET', Uri::create("api/merchant/$id"));

		if (!is_null($data)) {
			return $data->merchant;
		} else {
			return NULL;
		}
	}

	public static function do_api_call($method, $url, $data = null, $raw_response = FALSE) {
		$restful = new Restful();
		$restful->setAppid(Config::get('cms.appid'));
		
		$auth = Auth::instance('Shopsuey_Session');
		$user_login = $auth->get_user_login_object();
		
		$restful->setLoginHash($user_login->login_hash);

		$restful->setMethod($method);
		$restful->setURL($url);
		
		if (!is_null($data)) {
			$restful->setData($data);
		}

		$output = $restful->execute();
		
		if ($output && $raw_response) {
            return $output;
		} elseif ($output && $output->meta->status == 1) {
			return $output->data;
		} else {
			return NULL;
		}
	}
	
	public static function merchants($selected = null) {
		$json_fields = array('social', 'hours');

		$qry = DB::select()->from('merchants')->where('status', '>', 0)->execute();
		if (count($qry) > 0) {
			$items = array();
			foreach($qry as $merchant) {
				$merchant = CMS::unjsonize($json_fields, $merchant);
				$merchant['selected'] = $selected == $merchant['id'] ? 'selected="selected"' : '';
// 				if ($selected == $merchant['id']) { $merchant['selected'] = 'selected="selected"'; }
				$merchant = (object) $merchant;
				array_push($items, $merchant);
			}
			return $items;
		}
	}

	public static function mini_nav($selected = -1, $prepend = NULL, $append = NULL) {
		// Default links
		$links = array(
			array('title' => 'Dashboard', 	'icon' => 'icon-screen', 	'url' => Uri::create('dashboard')),
                        
                        array('title' => 'Offers', 	'icon' => 'icon-cart', 	'url' => Uri::create('dashboard/offers')),
                       	array('title' => 'Events', 	'icon' => 'icon-calendar-2', 	'url' => Uri::create('dashboard/events')),
			
                        
			array('title' => 'Messages', 	'icon' => 'icon-comments-4',    'url' => Uri::create('dashboard/messages')),
		
                    
			array('title' => 'Locations', 	'icon' => 'icon-map_pin_fill', 	'url' => Uri::create('dashboard/locations')),
			array('title' => 'Statistics', 	'icon' => 'icon-chart', 	'url' => Uri::create('dashboard/stats'))
		);

		$auth = Auth::instance('Shopsuey_Session');

		if ($auth->has_access('dashboard/user.index')) {
			$links[] = array('title' => 'Users', 'icon' => 'icon-users', 'url' => Uri::create('dashboard/users'), 'children' => CMS::main_nav_children('users'));
		}

		if ($auth->has_access('admin.index')) {
			$links[] = array('title' => 'App Admin', 'icon' => 'icon-settings', 'url' => Uri::create('admin'), 'children' => CMS::main_nav_children('admin'));
		}

		// Prepend links
		if (is_array($prepend)) {
			$links = array_merge($prepend, $links);
		}

		// Append links
		if (is_array($append)) {
			$links = array_merge($append, $links);
		}
                
		?>
        <!-- .miniNav -->
		<?php foreach($links as $key => $link) :
            if ($selected == -1) {
                $current = Uri::current();
                $active = ($current == $link['url']) ? ' active' : '';

                if (isset($link['children'])) { $active = (in_array($current, $link['children'])) ? ' active' : ''; }
            }
            else { $active = ($selected == $key) ? ' active' : ''; }
        ?>
        <li>
            <a alt="<?=$link['title']?>" href="<?=$link['url']?>">
                <span class="<?=$link['icon']?>"></span>
                <?=$link['title']?>
            </a>
        </li>

        <?php endforeach; ?>
        <!-- end .miniNav -->
        <?php
	} 

	public static function main_nav_children($parent) {

		$auth = Auth::instance('Shopsuey_Session');
		$user_login = $auth->get_user_login_object();
		
		switch(strtolower($parent)) {
			case 'users':
				$children = array(
					Uri::create('dashboard/user/add'),
					Uri::create('dashboard/users'),
					Uri::create('dashboard/user/'.$user_login->user_id),
					Uri::create('dashboard/user/search'),
					Uri::create('dashboard/user/settings/'.$user_login->user_id));

				$username = Request::active()->param('id');
				if ($username) {
					array_push($children, Uri::create('dashboard/user/edit/'.$username));
					array_push($children, Uri::create('dashboard/user/view/'.$username));
				}

				$page = Request::active()->param('page', 1);
				if ($page) { array_push($children, Uri::create('dashboard/users/'.$page)); }

				$search = Request::active()->param('f', Input::get('f'));
				if ($search) { array_push($children, Uri::create('dashboard/user/search/'.$search)); }

				break;

			case 'admin':
				$children = array(
					Uri::create('admin/clients'));

				break;
		}

		return $children;
	}

	public static function note($message = NULL, $type = NULL) {
		$message = ($message) ? $message : '';

		switch(strtolower($type)) {
			case 'success':		$class = 'nSuccess'; 	break;
			case 'warning':		$class = 'nWarning';	break;
			case 'fail':
			case 'error':		$class = 'nFailure';	break;
			default: 		$class = 'nInformation';
		}
		?>
        <!-- Message -->
    	<div class="fluid">
            <div class="nNote <?=$class?>">
                <p><?=$message?></p>
            </div>
        </div>
        <!-- End Message -->
		<?php
	}
    
    public static function field_error($notice, $field) {
        if ($notice && array_key_exists('field', $notice) && $field == $notice['field'] && in_array($notice['type'], array('fail', 'error'))) {
            $message = $notice['message'];
            
            ?>
            <!-- Message -->
            <div class="fieldError">
                <p><?=$message?></p>
            </div>
            <!-- End Message -->
            <?php
        }
    }
    
    public static function field_error_wrapper_class($notice, $field) {
        if ($notice && array_key_exists('field', $notice) && $field == $notice['field'] && in_array($notice['type'], array('fail', 'error'))) {
            echo "class='fieldErrorWrapper'";
        }
    }

	public static function objectify($data) {
		$json = json_encode($data);
		$object = json_decode($json);
		return $object;
	}

	public static function pretty_json($input) {
		$json = json_encode($input);
		$result      = '';
		$pos         = 0;
		$strLen      = strlen($json);
		$indentStr   = '  ';
		$newLine     = "\n";
		$prevChar    = '';
		$outOfQuotes = true;

		for ($i=0; $i<=$strLen; $i++) {

			// Grab the next character in the string.
			$char = substr($json, $i, 1);

			// Are we inside a quoted string?
			if ($char == '"' && $prevChar != '\\') {
				$outOfQuotes = !$outOfQuotes;

			// If this character is the end of an element,
			// output a new line and indent the next line.
			} else if(($char == '}' || $char == ']') && $outOfQuotes) {
				$result .= $newLine;
				$pos --;
				for ($j=0; $j<$pos; $j++) {
					$result .= $indentStr;
				}
			}

			// Add the character to the result string.
			$result .= $char;

			// If the last character was the beginning of an element,
			// output a new line and indent the next line.
			if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes) {
				$result .= $newLine;
				if ($char == '{' || $char == '[') {
					$pos ++;
				}

				for ($j = 0; $j < $pos; $j++) {
					$result .= $indentStr;
				}
			}

			$prevChar = $char;
		}

		return $result;
	}

	public static function set_note($message = NULL, $type = NULL, $autohide = TRUE, $data = NULL) {
		if (!isset($message)) { return; }

		$data = ($message != NULL) ? array('message' => $message, 'type' => $type, 'autohide' => $autohide, 'data'=>$data) : NULL;
		CMS::set_user_meta(1, 'note', $data, TRUE);
	}

	public static function set_user_meta($id, $key, $value = NULL, $unique = FALSE) {

		if ($value == NULL) { CMS::delete_user_meta($id, $key); return; }

		if ($unique == TRUE) { CMS::delete_user_meta($id, $key); }

		if (is_array($value)) { $value = json_encode($value); }

		$data = array('user_id' => $id, 'key' => $key, 'value' => $value);
		DB::insert('user_metafields')->set($data)->execute();
	}

	public static function scripts($append = NULL, $include = NULL, $exclude = NULL) {
		if (!$append) { $append = array(); }
		if (!$include) { $include = array(); }
		if (!$exclude) { $exclude = array(); }

		$newScripts = array();
		$scriptList = array();
		$scripts = Config::get('cms.scripts', array());

		// List specific scripts
		if (count($include) > 0) {
			foreach($scripts as $key => $script) {
				if (!in_array($key, $include)) { continue; }
				$newScripts[$key] = $script;
			}
		}
		// No scripts specified
		if (count($include) < 1) {
			foreach($scripts as $key => $script) {
				$newScripts[$key] = $script;
			}
		}

		if (count($newScripts) < 1) { $newScripts = $scripts; }

		// Exclude specific scripts
		if (count($exclude) > 0) {
			foreach($newScripts as $key => $script) {
				if (in_array($key, $exclude)) { unset($newScripts[$key]); }
			}
		}

		// Create scripts list
		foreach($newScripts as $key => $script) {
			if (is_array($script)) { $scriptList = array_merge($scriptList, $script); }
			else { array_push($scriptList, $script); }
		}

		// Append scripts to list
		if (count($append) > 0) { $scriptList = array_merge($scriptList, $append); }
		return $scriptList;
	}

	public static function status($value) {
		$statuses = CMS::statuses($value);

		foreach ($statuses as $status) {
			if (!isset($status->selected)) { continue; }
			return $status;
		}
	}

	public static function statuses($selected = null, $exclude = null) {
		$statuses = array(
            'Draft'    => Model_Location::STATUS_DRAFT,
            'Active'   => Model_Location::STATUS_ACTIVE,
            'Inactive' => Model_Location::STATUS_INACTIVE,
            'Deleted'  => Model_Location::STATUS_DELETED,
            'Blocked'  => Model_Location::STATUS_BLOCKED,
            'Signup'   => Model_Location::STATUS_SIGNUP,
        );

		$icons = array(
            'Draft'    => '&#xe003;',
            'Active'   => '&#xe097;',
            'Inactive' => '&#xe056;',
            'Deleted'  => '&#xe095;',
            'Blocked'  => '&#x26a0;',
            'Signup'   => '&#xe003;',
        );

		$exclude = ($exclude) ? $exclude : array();
		if (!is_array($exclude)) { $exclude = array(); }

		$items = array();
		foreach($statuses as $status => $val) {
			if (in_array($status, $exclude)) { continue; }
			// TODO: Check this restriction
// 			if (!CMS::is_super_admin() && $status == 'Pending') { continue; }

			$item = array('value' => $val, 'label' => $status, 'icon' => $icons[$status], 'name' => str_replace(' ', '_', strtolower($status)));
			if ($selected == $val) { $item['selected'] = 'selected="selected"'; }

			ksort($item);

			$item = (object) $item;
			array_push($items, $item);
		}

		return $items;
	}

	public static function system_notifications($count = null, $offset = 0, $limit = 10) {
		$date = CMS::local_date('Y-m-d'). ' 00:00:00';

		$cqry = DB::select()->from('notices')
		->where('status', '=', 1)
		->and_where_open()
		->where('date_start', '=', '0000-00-00 00:00:00')
		->or_where('date_start', '<=', $date)
		->and_where_close()
		->and_where_open()
		->where('date_end', '=', '0000-00-00 00:00:00')
		->or_where('date_end', '>=', $date)
		->and_where_close()
		->execute();

		$total = count($cqry);

		if ($count) { return $total; }

		// Limit to 10
		$qry = DB::select()->from('notices')
		->where('status', '=', 1)
		->and_where_open()
		->where('date_start', '=', '0000-00-00 00:00:00')
		->or_where('date_start', '<=', $date)
		->and_where_close()
		->and_where_open()
		->where('date_end', '=', '0000-00-00 00:00:00')
		->or_where('date_end', '>=', $date)
		->and_where_close()
		->order_by('date_start', 'desc')
		->limit($limit)
		->offset($offset)
		->execute();

		if (count($qry) > 0 && !$count) :
		?>
        <div class="sub-menu">
			<ul class="subNav" style="margin-top: -1px;">
			<li>
				<a href="<?=Uri::create('dashboard/notices')?>">
					<span class="icon-microphone"></span>System Notifications
					<?php if ($total > $limit) : ?>
					<span class="dataNumRed"><?=($total < 100) ? $total : '99+'?></span>
					<?php endif; ?>
				</a>
			</li>
			<?php foreach($qry as $notice) : $notice = (object) $notice; ?>
			<li>
				<a href="<?=Uri::create('dashboard/notice/view/'.$notice->id)?>">
					<span class="uAlert">
						<?=$notice->name?>
					</span>
					<div class="clear"></div>
				</a>
			</li>
			<?php endforeach; ?>
			</ul>
		</div>
		<div class="divider"><span></span></div>

		<?php else : ?>
        <div class="sub-menu mb10">
            <div class="hdr noBorderT">
                <span class="text"></span>
            </div>
			<div class="clear"></div>
		</div>

		<?php

		endif;
	}

    public static function profiling_choice($id) {
        return Model_Profilingchoice::find($id);
    }
    
	public static function user($id) {
		$data = self::do_api_call('GET', Uri::create("api/user/$id"));
		
		if (!is_null($data)) {
			return $data->user;
		} else {
			return NULL;
		}
	}

	public static function user_group($group_id) {
		$groups = Config::get('simpleauth.groups');
		$group = @$groups[$group_id];
		if ($group) { $group['level'] = $group_id; return (object) $group; }
	}

	public static function userID($key = null) {
		$qry = DB::select('id')->from('users')->where('access_key', '=', $key)->and_where('status', '=', 1)->limit(1)->execute();
		if (isset($qry[0])) { return $qry[0]['id']; }
	}

	public static function valid_email($email) {
		return (filter_var($email, FILTER_VALIDATE_EMAIL)) ? true : false;
	}

	public static function valid_password($pwd) {
		return strlen($pwd) >= 6;
	}

	public static function verify_nonce($name, $val, $keep = false) {
		$results = DB::select()->from('nonce')->where('name', '=', $name)->and_where('nonce', '=', $val)->limit(1)->execute();
		$item = $results[0];
		$valid = ($item['id']) ? true : false;

		if ($keep != true) { CMS::delete_nonce($item['nonce']); }
		return $valid;
	}

	public static function code_type_label($value) {
		foreach (self::code_types() as $code_type) {
			if ($code_type->type == $value) {
				return $code_type->label;
			}
		}
		return null;
	}
	
	public static function code_types($selected = null) {
		$labels = array(
				Model_Offer_Code::QR_CODE_TYPE => 'QR Code',
				Model_Offer_Code::EAN13_TYPE => 'EAN-13',
				Model_Offer_Code::CODE128_TYPE => 'Code 128',
		);
		$code_types = array();

		foreach ($labels as $key => $label) {
				$code_type = new stdClass();
				$code_type->type     = $key;
				$code_type->label    = $label;
				$code_type->selected = $selected == $key;
				$code_types[] = $code_type;
		}

		return $code_types;
	}
	
	public static function get_setting_for_user($user_id, $key, $default_value = NULL) {
		$settings = self::get_user_meta($user_id, 'settings');
		if (is_array($settings) && isset($settings[$key])) {
				return $settings[$key];
		} else {
				return $default_value;
		}
	}

	/**
	 * Checks if the current user has access to the given resource
	 * @param string $resource
	 */
	public static function has_access($resource) {
		$auth = Auth::instance('Shopsuey_Session');
		return $auth->has_access($resource);
	}
	
	/**
	 * Random string generation for passwords
	 * @param number $length
	 * @return string
	 */
	public static function generate_random_string($length = 8) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$random_string = '';
		for ($i = 0; $i < $length; $i++) {
			$random_string .= $characters[rand(0, strlen($characters) - 1)];
		}
		return $random_string;
	}
	
	public static function categories() {
	    $data = self::do_api_call('GET', Uri::create("api/categories"));

	    if (!is_null($data)) {
	        return $data->categories;
	    } else {
	        return NULL;
	    }
	}

	public static function countries() {
		$data = self::do_api_call('GET', Uri::create("api/countries"));
	
		if (!is_null($data)) {
			return $data->countries;
		} else {
			return NULL;
		}
	}
	
	public static function active_shoppers($location, $params = array()) {
		$data = self::do_api_call('GET', Uri::create("api/location/{$location->id}/active_shoppers"), $params);
	
		if (!is_null($data)) {
			return $data->active_shoppers;
		} else {
			return NULL;
		}
	}

	public static function location_points($location) {
		$data = self::do_api_call('GET', Uri::create("api/location/{$location->id}/points"), array('ignore_like_status' => '1'));
	
		if (!is_null($data)) {
			return $data->location;
		} else {
			return NULL;
		}
	}
	
	public static function build_order_url($sel_order_by, $order_by, $sel_order_dir, $order_dir) {
        if ($sel_order_by == $order_by) {
            $order_dir = $sel_order_dir == 'desc' ? 'asc' : 'desc';
        }
        return "order_by=$order_by&order_direction=$order_dir";
    }

    public static function strip_tags($str) {
    	// Cleanup description
    	return strip_tags($str, Config::get('cms.rich_editor_allowed_tags'));
    }
    
    public static function get_cms_location_url($location) {
        $path = '';
        if ($location->type == Model_Location::TYPE_MALL) {
            $path = 'mall';
        } elseif ($location->type == Model_Location::TYPE_MERCHANT) {
            $path = 'merchant';
        }
        return Uri::create("admin/{$path}/{$location->id}/edit");
    }
}
