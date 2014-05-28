<?php

/**
 * Wrapper class for the rackspace Cloud Files API PHP's binding
 * @author lucas
 *
 */
namespace Rackspace;

class RackspaceException extends \FuelException {}

class File {

	private static $_username;
	private static $_api_key;

	private static $_auth;

	const ERROR_MISSING_CONTAINER = 1;
	const ERROR_MISSING_OBJECT = 2;
	const ERROR_UPLOAD = 3;
	const ERROR_DELETE = 4;
	const ERROR_AUTH = 5;
	const ERROR_CONTAINER_CREATE = 5;
	const ERROR_GENERAL = 6;
	const ERROR_EXISTENT_OBJECT = 7;
	
	final private function __construct() {}

	private static function _init()
	{
		if (is_null(static::$_auth)) {
			\Config::load('rackspace', true);

			static::$_username = \Config::get('rackspace.username', '');
			static::$_api_key = \Config::get('rackspace.api_key', '');
	
			static::$_auth = new \CF_Authentication(static::$_username, static::$_api_key);
			# $auth->ssl_use_cabundle();  # bypass cURL's old CA bundle
			try {
				static::$_auth->authenticate();
			} catch (\Exception $e) {
				throw new RackspaceException($e->getMessage(), static::ERROR_AUTH);
			}
		}
	}

	public static function upload_file($dir_name, $file_name, $local_file_path) {
		
		if (($container = static::_get_container($dir_name)) === FALSE) {
			$container = static::_create_container($dir_name);
		}
		
		if (static::_get_object($container, $file_name) !== FALSE) {
			throw new RackspaceException("The object already exists", static::ERROR_EXISTENT_OBJECT);
		}
		
		try {
			$object = $container->create_object($file_name);
			$object->load_from_filename($local_file_path);
		} catch (\Exception $e) {
			throw new RackspaceException($e->getMessage(), static::ERROR_UPLOAD);
		}
		
		return $object->public_uri();
	}
	
	public static function update_file($dir_name, $file_name, $local_file_path) {

		if (($container = static::_get_container($dir_name)) === FALSE) {
			throw new RackspaceException("The container does not exists", static::ERROR_MISSING_CONTAINER);
		}

		if (($object = static::_get_object($container, $file_name)) === FALSE) {
			throw new RackspaceException("The object does not exists", static::ERROR_MISSING_OBJECT);
		}

		try {
			$object->load_from_filename($local_file_path);
		} catch (\Exception $e) {
			throw new RackspaceException($e->getMessage(), static::ERROR_UPLOAD);
		}
	}
	
	public static function remove_file($dir_name, $file_name) {

		if (($container = static::_get_container($dir_name)) === FALSE) {
			throw new RackspaceException("The container does not exists", static::ERROR_MISSING_CONTAINER);
		}
		
		if (($object = static::_get_object($container, $file_name)) === FALSE) {
			throw new RackspaceException("The object does not exists", static::ERROR_MISSING_OBJECT);
		}

		try {
			$container->delete_object($object);
		} catch (\Exception $e) {
			throw new RackspaceException($e->getMessage(), static::ERROR_DELETE);
		}
	}
	
	private static function _get_container($dir_name) {
	
		static::_init();
	
		$conn = new \CF_Connection(static::$_auth);
	
		try {
			// Check if the container exists
			$container = $conn->get_container($dir_name);
		} catch (\NoSuchContainerException $e) {
			return FALSE;
		} catch (\Exception $e) {
			throw new RackspaceException($e->getMessage(), static::ERROR_GENERAL);
		}

		return $container;
	}
	
	private static function _create_container($dir_name) {

		static::_init();
		
		$conn = new \CF_Connection(static::$_auth);
		
		try {
			// Create the container
			$container = $conn->create_container($dir_name);
			$container->make_public();
		} catch (\Exception $e) {
			throw new RackspaceException($e->getMessage(), static::ERROR_GENERAL);
		}
		
		return $container;
	}
	
	private static function _get_object($container, $file_name) {
		
		try {
			// Check if the container exists
			$object = $container->get_object($file_name);
		} catch (\NoSuchObjectException $e) {
			return FALSE;
		} catch (\Exception $e) {
			throw new RackspaceException($e->getMessage(), static::ERROR_GENERAL);
		}
		
		return $object;
	} 
}
