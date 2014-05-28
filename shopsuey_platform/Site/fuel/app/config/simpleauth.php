<?php
/**
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    Fuel
 * @version    1.0
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2011 Fuel Development Team
 * @link       http://fuelphp.com
 */

/**
 * NOTICE:
 *
 * If you need to make modifications to the default configuration, copy
 * this file to your app/config folder, and make them in there.
 *
 * This will allow you to upgrade fuel without losing your custom config.
 */

return array(

	/**
	 * DB connection, leave null to use default
	 */
	'db_connection' => null,

	/**
	 * DB table name for the user table
	 */
	'table_name' => 'users',

	/**
	 * Choose which columns are selected, must include: username, password, email, last_login,
	 * login_hash, role & profile_fields
	 */
	'table_columns' => array('*'),

	/**
	 * This will allow you to use the group & acl driver for non-logged in users
	 */
	'guest_login' => true,

	/**
	 * Groups as id => array(name => <string>, roles => <array>)
	 */
	'groups' => array(
		 -1   => array('name' => 'Banned','roles' => array('banned')),
		 0    => array('name' => 'Guest', 'roles' => array()),
		 1    => array('name' => 'User', 'roles' => array('user')),
		 2    => array('name' => 'Anonymous', 'roles' => array('anonymous')),
		 25   => array('name' => 'Manager', 'roles' => array('user', 'manager')),
		 50   => array('name' => 'Retailer', 'roles' => array('user', 'manager', 'retailer')),
		 100  => array('name' => 'Administrator', 'roles' => array('user', 'manager', 'retailer', 'admin')),
		 1000 => array('name' => 'Super Admin', 'roles' => array('user', 'manager', 'retailer', 'admin', 'super'))
	),

	/**
	 * Roles as name => array(location => rights)
	 */
	'roles' => array(
		 'banned' 		=> false,
		 
		 'guest' 		=> array('view' => true),
		 
		 'user' 		=> array('comment' => true),

		 // TODO: Update this role with the corresponding permissions
		 'anonymous'	=> array('comment' => true),

		 'manager' 		=> array('local info' => true,
		 						 'local deals' 				=> true,
								 'local events' 			=> true,
								 'local notifications' 		=> true),
								
		 'retailer' 	=> array('retailer info' => true,
		 						 'regional deals' 			=> true,
								 'regional events' 			=> true,
								 'regional notifications' 	=> true,
								 'users' => true),
								
		 'admin' 		=> array('mall info' => true,
								 'map' 						=> true,
								 'locations' 				=> true,
		 						 'mall deals' 				=> true,
								 'mall events' 				=> true,
								 'mall notifications' 		=> true,
								 'retailers' 				=> true),
								
		 'super' 		=> array('malls' 					=> true,
		 						 'system notifications' 	=> true)
	),

	/**
	 * Salt for the login hash
	 */
	'login_hash_salt' => 'put_some_salt_in_here',

	/**
	 * $_POST key for login username
	 */
	'username_post_key' => 'username',

	/**
	 * $_POST key for login password
	 */
	'password_post_key' => 'password',
);
