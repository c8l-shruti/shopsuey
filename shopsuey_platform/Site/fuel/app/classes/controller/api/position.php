<?php

/**
 * The Api Controller.
 * This controllers the CRUD proceedures for position
 *
 * @package  app
 * @extends  Controller_Rest
 */

class Controller_Api_Position extends Controller_Api {

	private $json_fields = array();

	/**
	 * Delete a position
	 */
	public function action_delete() {
		$id = Input::param('id', $this->param('id'));

		// Delete the position
		$results = DB::delete('gps')->where('created_by', '=', $id)->execute();

		$data = array('affected_rows' => $results, 'created_by' => $id);
		$meta = array(
		    'error' => ($results > 0) ? null : 'Unable to delete position '.$id,
		    'status' => ($results > 0) ? 1 : 0);

		// Delete locations associated with the position
		if ($results > 0) {
		    $ids = array();
		    $locations = DB::select('id')->from('locations')->where('position_id', '=', $id)->execute();
		    foreach($locations as $item) { array_push($ids, $item['id']); }

		    $results += DB::delete('locations')->where('position_id', '=', $id)->execute();
		    $data['affected_rows'] = $results;
		    $data['location_ids'] = $ids;
		}

		ksort($data);
		ksort($meta);

		$output = array('data' => $data, 'meta' => $meta);
		$this->response($output);
	} // ---> action_delete()

	/**
	 * Get a single users positions
	 */
	public function action_get($user_id = null) {
		if ($user_id) { $return = true; }

		$user_id = ($user_id) ? $user_id : Input::param('user', $this->param('user'));
		$dfields = array('edited_by', 'edited', 'created_by');
		$jfields = $this->json_fields;

		$meta = array('error' => null, 'status' => 1);

		// Query
		$results = DB::select()
		->from('gps')
		->where('created_by', '=', $user_id)
		->order_by('created', 'desc')
		->execute();

		$position = array();
		foreach($results as $item) {
			$item['user_id'] = $item['created_by'];

			// Remove unnecessary fields
			foreach($dfields as $field) { unset($item[$field]); }

			ksort($item);
			array_push($position, $item);
		}

		$data = array('data' => array('positions' => $position), 'meta' => $meta);

		if (!@$return) { $this->response($data); }
		else { return $data; }
	} // ---> action_get()

	/**
	 * Get 25 position with pagination meta
	 */
	public function action_list() {

		$position = array();
		$dfields = array('edited_by', 'edited', 'created_by');

		// Pagination query
		$cresults = DB::select('id')->from('gps')->execute();
		$meta = array('pagination' => $this->_pagination(count($cresults), Input::param('page', 1)));
		$meta['status'] = 1;
		$meta['error'] = null;
		ksort($meta);

		// Query
		$results = DB::select()
		->from('gps')
		->order_by('created', 'desc')
		->limit($meta['pagination']['limit'])
		->offset($meta['pagination']['offset']['current'])
		->execute();

		// Parse the results
		foreach($results as $item) {
			$item['user_id'] = $item['created_by'];

			// Remove unnecessary fields
			foreach($dfields as $field) { unset($item[$field]); }

			ksort($item);
			array_push($position, $item);
		}

		// Set the output
		$data = array('data' => array('positions'=>$position), 'meta' => $meta);
		$this->response($data);
	} // ---> action_list()

	/**
	 * Create a new position
	 */
	public function action_post() {
		$data = array();
		$exclude = array('id', 'created', 'created_by', 'edited', 'edited_by');
		$fields = DB::list_columns('gps');
		$json_fld = $this->json_fields;

		foreach($fields as $key=>$field) {
			if (in_array($key, $exclude)) { continue; }
			$val = Input::post($key, $this->param($key, ''));
			$data[$key] = $val;
		}

		$user_id = Input::param('user', $this->param('user'));
		if ($user_id != $this->userObj->id) { $user_id = $this->userObj->id; }

		$data['created_by'] = $user_id;
		$data['created'] = date('Y-m-d H:i:s');

		foreach($json_fld as $field) {
			if (in_array($field, $exclude) || !isset($data[$field])) { continue; }
			$data[$field] = json_encode($data[$field]);
		}
		list($insert_id, $rows_affected) = DB::insert('gps')->set($data)->execute();

		if ($insert_id) {
			$meta = array('status' => 1, 'error' => '');
			$data = $this->action_get($user_id);
			$output = array('data' => $data, 'meta' => $meta);
		}
		else {
			$output = array('data' => Input::post(), 'meta' => array('error' => 'Unable to create position', 'status' => 0));
		}

		$this->response($output);

	} // ---> action_post()


	// Internal functions
	private function position_get_by($field = 'id', $value, $type ='object') {
		$qry = DB::select()->from('gps')->where($field, '=', $value)->limit(1)->execute();

		$json_fld = $this->json_fields;

		if (isset($qry[0])) {
		    $position = $qry[0];

		    foreach($json_fld as $fld) {
				$position[$fld] = json_decode(stripslashes($position[$fld]));
		    }

		    if ($type == 'object') { $position = (object) $position; }
		    return $position;
		}
		return;
	}
}






































// EOF