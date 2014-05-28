<?php

/**
 * The Restful Controller.
 *
 * This controller holds functions related to the rest based curl connectivity
 * 
 * @package app
 * @extends Controller
 */
class Restful {
	protected $appid = null;
	protected $ch = null;
	protected $username = null;
	protected $password = null;
	
	public $login_hash = null;
	public $data = array();
	public $error = null;
	public $method = 'GET';
	public $raw = null;
	public $response = null;
	public $url = null;
	
	public function init() {
		$this->ch = curl_init();	
	}
	
	public function setLoginHash($login_hash = null) { 
		$this->login_hash = $login_hash;
	}
	
	public function setAppid($appid = null) {
		$this->appid = $appid;	
	}
	
	public function setData($data = null) {
		$this->data = ($data) ? $data : array();
	}
	
	public function setMethod($method = null) {
		$this->method = $method;
	}
	
	public function setPassword($password = null) { 
		$this->password = $password;
	}
	
	public function setURL($url = null) {
		$this->url = $url;	
	}
	
	public function setUsername($username = null) {
		$this->username = $username;
	}
	
	public function options() {
		$opt = array('access_key' => $this->access_key, 'data' => $this->data, 'method' => $this->method, 'response' => $this->response, 'url' => $this->url);
		return $opt;	
	}
	
// 	public function authorize($username = null, $password = null, $appid = null) {
// 		$username = ($username) ? $username : $this->username;
// 		$password = ($password) ? $password : $this->password;
// 		$appid = ($appid) ? $appid : $this->appid;
		
// 		if (!$appid || !$username || !$password) { return false; }
		
// 		// Set fields
// 		$fields = array('username' => $username, 'password' => $password);
// 		$url = Uri::create('api/auth/' . $appid);
		
// 		$ch = curl_init();
// 		curl_setopt($ch, CURLOPT_URL, $url);
// 		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// 		curl_setopt($ch, CURLOPT_POST, 1);
// 		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		
// 		// Execute
// 		$response = curl_exec($ch);
		
// 		// Close connection
// 		curl_close($ch);
		
// 		// Return response
// 		return json_decode($response);
// 	}
	
	public function execute($data = null, $method = null, $url = null) {
		$this->raw = null;
		$this->response = null;
		$this->error = null;
		
		$url = ($url) ? $url : $this->url;
		$method = ($method) ? strtoupper($method) : strtoupper($this->method);
		$data = ($data) ? $data : $this->data;
		$ch = ($this->ch) ? $this->ch : curl_init();
		
		// Append access_key
		if (is_array($data)) {
			$data['login_hash'] = (isset($data['login_hash'])) ? $data['login_hash'] : $this->login_hash;
		}
		
		// Set fields
		$fields = (is_array($data)) ? http_build_query($data) : $data;

		// If GET -> append fields to url
		if ($fields) { $url = ($method == 'GET') ? $url .'?'.$fields : $url; }
		
		// Set options
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
		curl_setopt($ch, CURLOPT_TIMEOUT, 120);
		if ($method == 'POST') { curl_setopt($ch, CURLOPT_POST, 1); }
		else { curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method); }
		
		$headers = array('Cache-Control: no-cache');
		if ($method == 'PUT') { array_push($headers, 'Content-Length: '.strlen($fields)); }
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		 
		if ($method != 'GET') { curl_setopt($ch, CURLOPT_POSTFIELDS, $fields); }
		
		$this->raw = curl_exec($ch);
		$this->response = json_decode($this->raw);
		
		$this->error = curl_error($ch);
		curl_close($ch);

        if (!$this->response) {
            error_log("API call failed\nURL: $url\nResponse:$this->raw\n\n");
        }
        
		return $this->response;
	}	
}



































// EOF