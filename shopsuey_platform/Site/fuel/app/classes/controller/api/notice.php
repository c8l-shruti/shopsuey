<?php

/**
 * The Api Controller.
 * This controllers the CRUD proceedures for notices
 *
 * @package  app
 * @extends  Controller_Rest
 */

class Controller_Api_Notice extends Controller_Api {

	private $json_fields = array();

	/**
	 * Delete a notice and it's locations
	 */
	public function action_delete() {
		$id = Input::param('id', $this->param('id'));

		// Delete the notice
		$results = DB::delete('notices')->where('id', '=', $id)->limit(1)->execute();

		$data = array('affected_rows' => $results, 'notice_id' => $id);
		$meta = array(
		    'error' => ($results > 0) ? null : 'Unable to delete notice '.$id,
		    'status' => ($results > 0) ? 1 : 0);

		// Delete locations associated with the notice
		if ($results > 0) {
		    $ids = array();
		    $locations = DB::select('id')->from('locations')->where('notice_id', '=', $id)->execute();
		    foreach($locations as $item) { array_push($ids, $item['id']); }

		    $results += DB::delete('locations')->where('notice_id', '=', $id)->execute();
		    $data['affected_rows'] = $results;
		    $data['location_ids'] = $ids;
		}

		ksort($data);
		ksort($meta);

		$output = array('data' => $data, 'meta' => $meta);
		$this->response($output);
	} // ---> action_delete()

	/**
	 * Get a single notice
	 */
	public function action_get($notice_id = null) {
		if ($notice_id) { $return = true; }

		$id = ($notice_id) ? $notice_id : Input::param('id', $this->param('id'));
		$dfields = array('created_by', 'edited_by');
		$jfields = array();
		$notices = array();
		$meta = array('error' => null, 'status' => 1);

		// Pagination query
		$cresults = DB::select('id')->from('notices')->where('status', '=', 1)->execute();

		// Query
		$results = DB::select()
		->from('notices')
		->where('status', '=', 1)
		->and_where('id', '=', $id)
		->limit(1)
		->execute();

		$item = $results[0];

		if ($item) {
			// format json fields
			foreach($jfields as $field) {
			    $val = json_decode($item[$field], true);
			    $item[$field] = $val;
			}

			// Remove unnecessary fields
			foreach($dfields as $field) { unset($item[$field]); }

			ksort($item);
			array_push($notices, $item);
			ksort($meta);

			// Set the output
			$data = array('data' => array('notice'=>$notices), 'meta' => $meta);
		}
		else {
			$meta['error'] = 'Invalid notice id';
			$meta['status'] = 0;
			$data = array('data' => null, 'meta' => $meta);
		}

		if (!@$return) { $this->response($data); }
		else { return $data; }
	} // ---> action_get()

	/**
	 * Get 25 notices with pagination meta
	 */
	public function action_list() {

		$notices = array();
		$dfields = array('created_by', 'edited_by', 'edited');

		// Pagination query
		$cresults = DB::select('id')->from('notices')->where('status', '>', 0)->execute();
		$meta = array('pagination' => $this->_pagination(count($cresults), Input::param('page', 1)));
		$meta['status'] = 1;
		$meta['error'] = null;
		ksort($meta);

		// Query
		$results = DB::select()
		->from('notices')
		->where('status', '>', 0)
		->order_by('created', 'desc')
		->limit($meta['pagination']['limit'])
		->offset($meta['pagination']['offset']['current'])
		->execute();

		// Parse the results
		foreach($results as $item) {
			// Remove unnecessary fields
			foreach($dfields as $field) { unset($item[$field]); }

			ksort($item);
			array_push($notices, $item);
		}

		// Set the output
		$data = array('data' => array('notices'=>$notices), 'meta' => $meta);
		$this->response($data);
	} // ---> action_list()

	/**
	 * Create a new notice
	 */
	public function action_post() {
		$data = array();
		$exclude = array('id', 'created', 'edited', 'edited_by', 'status');
		$fields = DB::list_columns('notices');
		$json_fld = $this->json_fields;

		foreach($fields as $key=>$field) {
			if (in_array($key, $exclude)) { continue; }
			$val = Input::post($key, $this->param($key, ''));
			$data[$key] = $val;
		}

		$data['created_by'] = $this->userObj->id;
		$data['created'] = date('Y-m-d H:i:s');
		$data['status'] = 2;

		foreach($json_fld as $field) {
			if (isset($data[$field])) {
				$data[$field] = json_encode($data[$field]);
			}
		}
		list($insert_id, $rows_affected) = DB::insert('notices')->set($data)->execute();

		if ($insert_id) {
			$note = '<small><em>Created by '.$this->userObj->meta->fullname.'</em></small>';
			$type = 'notice_edit';
			$new = $this->notice_get_by('id', $insert_id);
			CMS::comment($this->userObj->id, $insert_id, $type, $note, '', $new);
			$output = array('data' => $new, 'meta' => array('status' => 1));
		}
		else {
			$output = array('data' => Input::post(), 'meta' => array('error' => 'Unable to create notice', 'status' => 0));
		}

		$this->response($output);

	} // ---> action_post()

	/**
	 * Update a notice
	 */
	public function action_put() {

		$data = array();
		$exclude = array('id', 'created', 'edited', 'created_by');
		$fields = DB::list_columns('notices');
		$params = Input::put();
		$id = Input::param('id', $this->param('id'));
		if (!$id) { $id= $params['id']; }

		$json_fld = $this->json_fields;

		foreach($params as $key=>$field) {
		    if (in_array($key, $exclude)) { continue; }
		    if (!isset($fields[$key])) { continue; }
		    $val =  $this->param($key, $params[$key]);

		    $data[$key] = $val;
		}

		$data['edited'] = date('Y-m-d H:i:s');

		foreach($json_fld as $field) {
			if (isset($data[$field])) {
				$data[$field] = json_encode($data[$field]);
			}
		}

		$old = $this->notice_get_by('id', $id);
		$upd = DB::update('notices')->set($data)->where('id', '=', $id)->execute();

		if ($upd) {
		    $new = $this->notice_get_by('id', $id);
		    $note = '<small><em>Updated by '.$this->userObj->meta->fullname.'</em></small>';
		    $type = 'notice_edit';
		    CMS::comment($data['edited_by'], $id, $type, $note, $old, $new);
		    $output = array('data' => $new, 'meta' => array('status' => 1));
		}
		else {
		    $output = array('data' => $params, 'meta' => array('error' => 'Unable to update notice', 'status' => 0, 'qry' => DB::last_query()));
		}

		$this->response($output);

	} // ---> action_put()

	/**
	 * Get 25 notices with pagination meta that match the supplied search parameters
	 */
	public function action_search() {
		$notices = array();
		$dfields = array('created_by', 'edited_by', 'created', 'edited');

		$params = Input::get();
		$search = $this->param('name', Input::get('name'));

		// Pagination query
		$cresults = DB::select('id')->from('notices')->where('status', '>', 0)->and_where('name', 'like', '%'.$search.'%')->execute();
		$meta = array('pagination' => $this->_pagination(count($cresults), Input::param('page', 1)));
		$meta['status'] = 1;
		$meta['error'] = null;
		ksort($meta);

		// Query
		$results = DB::select()
		->from('notices')
		->where('status', '>', 0)
		->and_where('name', 'like', '%'.$search.'%')
		->order_by('name', 'asc')
		->limit($meta['pagination']['limit'])
		->offset($meta['pagination']['offset']['current'])
		->execute();

		// Parse the results
		foreach($results as $item) {
			// Remove unnecessary fields
			foreach($dfields as $field) { unset($item[$field]); }

			ksort($item);
			array_push($notices, $item);
		}

		// Set the output
		$data = array('data' => array('notices'=>$notices), 'meta' => $meta);
		$this->response($data);
	}


	// Internal functions
	private function notice_get_by($field = 'id', $value, $type ='object') {
		$qry = DB::select()->from('notices')->where($field, '=', $value)->limit(1)->execute();

		$json_fld = $this->json_fields;

		if (isset($qry[0])) {
		    $notice = $qry[0];

		    foreach($json_fld as $fld) {
			$notice[$fld] = json_decode(stripcslashes($notice[$fld]));
		    }

		    if ($type == 'object') { $notice = (object) $notice; }
		    return $notice;
		}
		return;

	}
}






































// EOF