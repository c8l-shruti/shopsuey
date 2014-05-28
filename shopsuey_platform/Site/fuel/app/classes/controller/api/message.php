<?php

/**
 * The Api Controller.
 * This controllers the CRUD proceedures for messages
 *
 * @package  app
 * @extends  Controller_Rest
 */

class Controller_Api_Message extends Controller_Api {

	private $json_fields = array('sender_meta', 'trigger_meta', 'filter_demographic', 'filter_proximity', 'filter_behavior', 'filter_frequency');

	public function test() { return $this->response(array('testing'=>'this out')); }

	/**
	 * Delete a message and it's locations
	 */
	public function action_delete() {
		$id = Input::param('id', $this->param('id'));

		// Delete the message
		$results = DB::delete('messages')->where('id', '=', $id)->limit(1)->execute();

		$data = array('affected_rows' => $results, 'message_id' => $id);
		$meta = array(
		    'error' => ($results > 0) ? null : 'Unable to delete message '.$id,
		    'status' => ($results > 0) ? 1 : 0);

		// Delete locations associated with the message
		if ($results > 0) {
		    $ids = array();
		    $locations = DB::select('id')->from('locations')->where('message_id', '=', $id)->execute();
		    foreach($locations as $item) { array_push($ids, $item['id']); }

		    $results += DB::delete('locations')->where('message_id', '=', $id)->execute();
		    $data['affected_rows'] = $results;
		    $data['location_ids'] = $ids;
		}

		ksort($data);
		ksort($meta);

		$output = array('data' => $data, 'meta' => $meta);
		$this->response($output);
	} // ---> action_delete()

	/**
	 * Get a single message
	 */
	public function action_get($message_id = null) {
		if ($message_id) { $return = true; }

		$id = ($message_id) ? $message_id : Input::param('id', $this->param('id'));
		$dfields = array('created_by', 'edited_by');
		$jfields = $this->json_fields;
		$messages = array();
		$meta = array('error' => null, 'status' => 1);

		// Pagination query
		$cresults = DB::select('id')->from('messages')->where('status', '=', 1)->execute();

		// Query
		$results = DB::select()
		->from('messages')
		->where('status', '=', 1)
		->and_where('id', '=', $id)
		->limit(1)
		->execute();

		$item = $results[0];

		if ($item) {
			// format json fields
			foreach($jfields as $field) {
			    $val = json_decode(stripslashes($item[$field]), true);
			    $item[$field] = $val;
			}

			// Remove unnecessary fields
			foreach($dfields as $field) { unset($item[$field]); }

			ksort($item);
			array_push($messages, $item);
			ksort($meta);

			// Set the output
			$data = array('data' => array('message'=>$messages), 'meta' => $meta);
		}
		else {
			$meta['error'] = 'Invalid message id';
			$meta['status'] = 0;
			$data = array('data' => null, 'meta' => $meta);
		}

		if (!@$return) { $this->response($data); }
		else { return $data; }
	} // ---> action_get()

	/**
	 * Get 25 messages with pagination meta
	 */
	public function action_list() {

		$messages = array();
		$dfields = array('created_by', 'edited_by', 'edited');
		$jfields = $this->json_fields;

		// Pagination query
		$cresults = DB::select('id')->from('messages')->where('status', '>', 0)->execute();
		$meta = array('pagination' => $this->_pagination(count($cresults), Input::param('page', 1)));
		$meta['status'] = 1;
		$meta['error'] = null;
		ksort($meta);

		// Query
		$results = DB::select()
		->from('messages')
		->where('status', '>', 0)
		->order_by('created', 'desc')
		->limit($meta['pagination']['limit'])
		->offset($meta['pagination']['offset']['current'])
		->execute();

		// Parse the results
		foreach($results as $item) {
			// Remove unnecessary fields
			foreach($dfields as $field) { unset($item[$field]); }
			
			foreach($jfields as $field) {
			    $val = json_decode(stripslashes($item[$field]), true);
			    $item[$field] = $val;
			}

			ksort($item);
			array_push($messages, $item);
		}

		// Set the output
		$data = array('data' => array('messages'=>$messages), 'meta' => $meta);
		$this->response($data);
	} // ---> action_list()

	/**
	 * Create a new message
	 */
	public function action_post() {
		$data = array();
		$exclude = array('id', 'created', 'edited', 'edited_by', 'status');
		$fields = DB::list_columns('messages');
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
		list($insert_id, $rows_affected) = DB::insert('messages')->set($data)->execute();

		if ($insert_id) {
			$note = '<small><em>Created by '.$this->userObj->meta->fullname.'</em></small>';
			$type = 'message_edit';
			$new = $this->message_get_by('id', $insert_id);
			CMS::comment($this->userObj->id, $insert_id, $type, $note, '', $new);
			$output = array('data' => $new, 'meta' => array('status' => 1));
		}
		else {
			$output = array('data' => Input::post(), 'meta' => array('error' => 'Unable to create message', 'status' => 0));
		}

		$this->response($output);

	} // ---> action_post()

	/**
	 * Update a message
	 */
	public function action_put() {

		$data = array();
		$exclude = array('id', 'created', 'edited', 'created_by');
		$fields = DB::list_columns('messages');
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

		$old = $this->message_get_by('id', $id);
		$upd = DB::update('messages')->set($data)->where('id', '=', $id)->execute();

		if ($upd) {
		    $new = $this->message_get_by('id', $id);
		    $note = '<small><em>Updated by '.$this->userObj->meta->fullname.'</em></small>';
		    $type = 'message_edit';
		    CMS::comment($data['edited_by'], $id, $type, $note, $old, $new);
		    $output = array('data' => $new, 'meta' => array('status' => 1));
		}
		else {
		    $output = array('data' => $params, 'meta' => array('error' => 'Unable to update message', 'status' => 0, 'qry' => DB::last_query()));
		}

		$this->response($output);

	} // ---> action_put()

	/**
	 * Get 25 messages with pagination meta that match the supplied search parameters
	 */
	public function action_search() {
            $messages = array();
            $dfields = array('created_by', 'edited_by', 'created', 'edited');

            $params = Input::get();
            $search = $this->param('name', Input::get('name'));

            // Pagination query
            $cresults = DB::select('id')->from('messages')->where('status', '>', 0)->and_where('name', 'like', '%'.$search.'%')->execute();
            $meta = array('pagination' => $this->_pagination(count($cresults), Input::param('page', 1)));
            $meta['status'] = 1;
            $meta['error'] = null;
            ksort($meta);

            // Query
            $results = DB::select()
            ->from('messages')
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
                array_push($messages, $item);
            }

            // Set the output
            $data = array('data' => array('messages'=>$messages), 'meta' => $meta);
            $this->response($data);
	}


	// Internal functions
	private function message_get_by($field = 'id', $value, $type ='object') {
		$qry = DB::select()->from('messages')->where($field, '=', $value)->limit(1)->execute();

		$json_fld = $this->json_fields;

		if (isset($qry[0])) {
		    $message = $qry[0];

		    foreach($json_fld as $fld) {
			$message[$fld] = json_decode(stripcslashes($message[$fld]));
		    }

		    if ($type == 'object') { $message = (object) $message; }
		    return $message;
		}
		return;

	}
}






































// EOF