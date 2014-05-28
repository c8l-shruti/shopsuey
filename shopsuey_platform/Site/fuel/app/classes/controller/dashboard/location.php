<?php

/**
 * The Location Controller.
 * This controllers the CRUD proceedures for managing locations
 *
 * @package  app
 * @extends  Controller_Dashboard
 */

class Controller_Dashboard_location extends Controller_Dashboard {
	public function action_index() { $this->action_list(); }

	// List items
	public function action_list() {
		// Include .js
		$apnd = array('files/base.js', 'files/locations.js');
		$excl = array('autotab', 'cleditor', 'dualist', 'elfinder', 'fullcalendar', 'flot', 'maskedinput', 'tagsinput', 'upload', 'wizards');
		$scripts = CMS::scripts($apnd, NULL, $excl);

		// Set header params
		$header_data = array(
			'style' => 'styles.css',
			'scripts' => $scripts,
			'ie' => 'ie.css'
		);

		$page = Request::active()->param('page', 1);
		$search = Request::active()->param('f', Input::get('f'));
		$search = (strlen($search) > 0) ? $search : null;

		$url = ($search) ? Uri::create('api/location/search') : Uri::create('api/locations');

		$this->api->setMethod('GET');
		$this->api->setURL($url);

		$output = $this->api->execute();

		// Set content params
		$content_data = array(
			'me' => $this->me,
			'locations' => array(),
			'pagination' => null,
			'search' => $search,
			'title' => 'Locations');

		if ($output) {
			$content_data['locations'] = $output->data->locations;
			$content_data['pagination'] = $output->meta->pagination;
			$label = ($content_data['pagination']->records > 1) ? ' Locations' : ' Location';
			$content_data['title'] = $content_data['pagination']->records;
			$content_data['title'] .= ($search) ? ' results for '.$search : $label;
		}

		$content = View::forge('cms/location/list', $content_data);

		$wrapper_data = array(
			'page' => array(
				'name' => 'Location Management',
				'subnav' => View::forge('cms/location/menu'),
				'icon' => 'icon-location'),

			'crumbs' => array(
				array('title'=>'Dashboard', 'link'=>Uri::create('dashboard')),
				array('title'=>'Locations', 'link' => Uri::create('dashboard/locations'))
			),

			'me' => $this->me,

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

	// Delete an item
	public function action_delete() {

	}

	// Get an item
	public function action_get() {

	}

	// Update UI
	public function action_edit() { return $this->action_add('edit'); }

	// Create an item
	public function action_add($action = 'add') {
		$content = '';
		$location = null;
		$output = null;

		if ($action == 'edit') {
			$locationid = Request::active()->param('id');
			if ($locationid) { $location = $this->location_get_by('id', $locationid); }
			else { return $this->action_404(); }
		}

		// Set content params
		$content_data = array(
			'action' => $action,
			'location' => $location,
			'me' => $this->me,
			'output' => $output);

		if (Input::post()) {
			if ($action == 'add') { return $this->location_add($content_data); }
			if ($action == 'edit') { return $this->location_update($content_data); }
		}
		else {
			$content = View::forge('cms/location/edit', $content_data);
			return $this->form_edit($action, $content, $content_data);
		}
	}

	private function form_edit($action, $content, $data) {
		$data = (object) $data;

		// Include .js
		$apnd = array('files/base.js', 'files/locations.js');
		$excl = array('autotab', 'dualist', 'cleditor', 'elfinder', 'fullcalendar', 'flot', 'maskedinput', 'upload');
		$scripts = CMS::scripts($apnd, NULL, $excl);

		// Set header params
		$header_data = array(
			'style' => 'styles.css',
			'scripts' => $scripts,
			'ie' => 'ie.css'
		);

		$wrapper_data = array(
			'page' => array(
				'name' => ($action == 'add') ? 'Location Creator' : 'Location Editor',
				'subnav' => View::forge('cms/location/menu'),
				'icon' => 'icon-location'),

			'crumbs' => array(
				array('title'=>'Dashboard', 'link' => Uri::create('dashboard')),
				array('title'=>'Locations', 'link' => Uri::create('dashboard/locations')),
				array('title'=>($action == 'edit') ? ucwords($data->location->name) : ucwords($action), 'link' => '#'),
			),

			'me' => $data->me,

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


	private function location_add($content_data) {
		$data = array();
		$err = null;
		$post = Input::post();

		if ($err) {
			Session::set('message', $err);

			$content_data['location'] = (object) $post;
			$content = View::forge('cms/location/edit', $content_data);
			return $this->form_edit('add', $content, $content_data);
		}

		else {
			foreach($post as $field => $val) { $data[$field] = $val; }
			$data['created_by'] = $this->me->id;

			$data['hours'] = json_encode($data['hours']);
			$data['social'] = json_encode($data['social']);
			$data['gps'] = json_encode($data['gps']);
				
			// file upload process here


			$url = Uri::create('api/location/');

			$this->api->setData($data);
			$this->api->setMethod('POST');
			$this->api->setURL($url);

			$output = $this->api->execute();

			// Error test
			//print_r($this->api->raw);
			//return Response::forge('');

			if ($output) {
				if ($output->meta->status == 1) { // Success
					$location = $output->data;
					$msg = array('type' => 'success', 'message' => 'Successfully added location '.$location->name, 'autohide' => true);
					Session::set('message', $msg);
					return Response::redirect('dashboard/location/edit/'.$location->id);
				}
				else { // Fail
					$msg = array('type' => 'fail', 'message' => $output->meta->error);
					Session::set('message', $msg);

					// Set content params
					$content_data['location'] = (object) $post;
					$content = View::forge('cms/location/edit', $content_data);
					return $this->form_edit('add', $content, $content_data);
				}
			}
			else {
				$msg = array('type' => 'fail', 'message' => 'Error: 900 - Unable to process your request');
				Session::set('message', $msg);

				// Set content params
				$content_data['location'] = (object) $post;
				$content = View::forge('cms/location/edit', $content_data);
				return $this->form_edit('add', $content, $content_data);

			}
		}
	}

	private function location_update($content_data) {
		$data = array();
		$err = null;
		$post = Input::post();

		if ($err) {
			Session::set('message', $err);

			$content_data['location'] = (object) $post;
			$content = View::forge('cms/location/edit', $content_data);
			return $this->form_edit('edit', $content, $content_data);
		}

		else {
			$locationid = Request::active()->param('id');
			foreach($post as $field => $val) { $data[$field] = $val; }
			$data['edited_by'] = $this->me->id;
			$data['id'] = $locationid;
			$data['hours'] = json_encode($data['hours']);
			$data['social'] = json_encode($data['social']);
			$data['gps'] = json_encode($data['gps']);
				
			if (@$data['remove-logo']) { $data['logo'] = ''; }
			else {

			}

			unset($data['remove-logo']);

			$url = Uri::create('api/location/'.$locationid);

			$this->api->setData($data);
			$this->api->setMethod('PUT');
			$this->api->setURL($url);

			$output = $this->api->execute();



			// Error test
			//print_r($this->api->raw);
			//return Response::forge('');

			if ($output) {
				if ($output->meta->status == 1) { // Success
					$location = $output->data;
					$msg = array('type' => 'success', 'message' => 'Successfully updated location '.$location->name, 'autohide' => true);
					Session::set('message', $msg);

					return Response::redirect('dashboard/location/edit/'.$location->id);
				}
				else { // Fail
					$msg = array('type' => 'fail', 'message' => $output->meta->error);
					Session::set('message', $msg);

					// Set content params
					$data['id'] = $locationid;
					$content_data['location'] = (object) $data;
					$content = View::forge('cms/location/edit', $content_data);
					return $this->form_edit('edit', $content, $content_data);
				}

			}
			else {
				$msg = array('type' => 'fail', 'message' => 'Error: 900 - Unable to process your request');
				Session::set('message', $msg);

				// Set content params
				$data['id'] = $locationid;
				$content_data['location'] = (object) $data;
				$content = View::forge('cms/location/edit', $content_data);
				return $this->form_edit('edit', $content, $content_data);

			}
		}
	}

	// Internal functions
	private function location_get_by($field = 'id', $value, $type ='object') {
		$qry = DB::select()->from('locations')->where($field, '=', $value)->limit(1)->execute();
		if (isset($qry[0])) {
			$location = $qry[0];
			$location['hours'] = json_decode(stripslashes($location['hours']));
			$location['social'] = json_decode(stripslashes($location['social']));
			$location['gps'] = json_decode(stripslashes($location['gps']));
				
			if ($type == 'object') { $location = (object) $location; }
			return $location;
		}
		return;
	}












// EOF
}