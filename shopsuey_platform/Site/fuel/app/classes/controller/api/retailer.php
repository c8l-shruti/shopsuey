<?php

/**
 * The Api Controller.
 * This controllers the CRUD proceedures for retailers
 *
 * @package  app
 * @extends  Controller_Rest
 */

class Controller_Api_Retailer extends Controller_Api {

	/**
	 * Delete a retailer and it's locations
	 */
	public function action_delete() {
		$id =Input::param('id', $this->param('id'));

		// Delete the retailer
		$results = DB::delete('retailers')->where('id', '=', $id)->limit(1)->execute();

		$data = array('affected_rows' => $results, 'retailer_id' => $id);
		$meta = array(
			'error' => ($results > 0) ? null : 'Unable to delete retailer '.$id,
			'status' => ($results > 0) ? 1 : 0);

		// Delete locations associated with the retailer
		if ($results > 0) {
			$ids = array();
			$locations = DB::select('id')->from('locations')->where('retailer_id', '=', $id)->execute();
			foreach($locations as $item) { array_push($ids, $item['id']); }

			$results += DB::delete('locations')->where('retailer_id', '=', $id)->execute();
			$data['affected_rows'] = $results;
			$data['location_ids'] = $ids;
		}

		ksort($data);
		ksort($meta);

		$output = array('data' => $data, 'meta' => $meta);
		$this->response($output);
	} // ---> action_delete()

	/**
	 * Get a single retailer
	 */
	public function action_get($retailer_id = null) {
		if ($retailer_id) { $return = true; }

		$id = ($retailer_id) ? $retailer_id : Input::param('id', $this->param('id'));
		$dfields = array('created_by', 'edited_by');
		$jfields = array('contact', 'social', 'categories', 'tags', 'hours');
		$retailers = array();
		$meta = array('error' => null, 'status' => 1);

		// Pagination query
		$cresults = DB::select('id')->from('retailers')->where('status', '=', 1)->execute();

		// Query
		$results = DB::select()
		->from('retailers')
		->where('status', '=', 1)
		->and_where('id', '=', $id)
		->limit(1)
		->execute();

		$item = $results[0];

		if ($item) {
			// format json fields
			foreach($jfields as $field) {
				$val = json_decode(stripslashes($item[$field]), true);
				if ($val) {
					if ($field != 'categories' && $field != 'tags') { ksort($val); }
					else { sort($val); }
				}
				$item[$field] = $val;
			}

			// Remove unnecessary fields
			foreach($dfields as $field) { unset($item[$field]); }
			$item['malls'] = $this->_malls($item['id']);
			//$item['hours'] = $this->_hours($item['id'], 'retailer');

			ksort($item);
			array_push($retailers, $item);
			ksort($meta);

			// Set the output
			$data = array('data' => array('retailer'=>$retailers), 'meta' => $meta);
		}
		else {
			$meta['error'] = 'Invalid retailer id';
			$meta['error_code'] = 3001;
			$meta['status'] = 0;
			$data = array('data' => null, 'meta' => $meta);
		}

		if (!@$return) { $this->response($data);	}
		else { return $data; }
	} // ---> action_get()

	/**
	 * Get 25 retailers with pagination meta
	 */
	public function action_list() {
		$retailers = array();
		$dfields = array('created_by', 'edited_by');
		$jfields = array('contact', 'social', 'categories', 'tags', 'hours');

		// Pagination query
		$cresults = DB::select('id')->from('retailers')->where('status', '=', 1)->execute();
		$meta = array('pagination' => $this->_pagination(count($cresults), Input::param('page', 1)));
		$meta['status'] = 1;
		$meta['error'] = null;
		ksort($meta);

		// Query
		$results = DB::select()
		->from('retailers')
		->where('status', '=', 1)
		->order_by('name', 'asc')
		->limit($meta['pagination']['limit'])
		->offset($meta['pagination']['offset']['current'])
		->execute();

		// Parse the results
		foreach($results as $item) {
			// format json fields
			foreach($jfields as $field) {
				$val = json_decode($item[$field], true);
				if ($val) {
					if ($field != 'categories' && $field != 'tags') { ksort($val); }
					else { sort($val); }
				}
				$item[$field] = $val;
			}

			// Remove unnecessary fields
			foreach($dfields as $field) { unset($item[$field]); }
			$item['malls'] = $this->_malls($item['id']);

			ksort($item);
			array_push($retailers, $item);
		}

		// Set the output
		$data = array('data' => array('retailers'=>$retailers), 'meta' => $meta);
		$this->response($data);
	} // ---> action_list()

	/**
	 * Get 25 malls with pagination meta
	 */
	public function action_malls($retailer_id = null) {
		if ($retailer_id) { $return = true; }

		$id = ($retailer_id) ? $retailer_id : Input::param('id', $this->param('id'));
		$data = array();
		if (!$id) {
			$meta = array('error'=> 'Error: Invalid retailer id', 'status'=> 0, 'retailer_id' => $id);
			$output = array('data' => $data, 'meta' => $meta);
			$this->response($output);
			return;
		}

		// Get the retailer data
		$retailer = DB::select('id', 'name')->from('retailers')->where('id', '=', $id)->and_where('status', '=', 1)->limit(1)->execute();
		if (!@$retailer[0]) {
			$meta = array('error' => 'Error: Invalid retailer id', 'status' => 0, 'retailer_id' => $id);
			$output = array('data' => $data, 'meta' => $meta);
			$this->response($output);
			return;
		}
		$data['retailer'] = $retailer[0];
		ksort($data['retailer']);

		// Pagination
		$cresults = DB::select('malls.id')->from('locations')
		->join('malls')->on('locations.mall_id', '=', 'malls.id')
		->where('locations.retailer_id', '=', $id)
		->and_where('locations.status', '=', 1)
		->and_where('malls.status', '=', 1)
		->execute();
		$meta = array('pagination' => $this->_pagination(count($cresults), Input::param('page', 1)));

		// Results
		$results = DB::select('locations.id', 'locations.gps', 'locations.mall_id', 'malls.name')->from('locations')
		->join('malls')->on('locations.mall_id', '=', 'malls.id')
		->where('locations.retailer_id', '=', $id)
		->and_where('locations.status', '=', 1)
		->and_where('malls.status', '=', 1)
		->limit($meta['pagination']['limit'])
		->offset($meta['pagination']['offset']['current'])
		->execute();

		$malls = array();

		foreach($results as $item) {
			$item['gps'] = json_decode($item['gps'], true);
			if ($item['gps']) { ksort($item['gps']); }

			$item['location'] = array('location_id' => $item['id'], 'gps' => $item['gps']);

			unset($item['gps']);
			unset($item['id']);

			ksort($item);

			array_push($malls, $item);
		}

		$data['retailer']['malls'] = $malls;

		$meta['status'] = 1;
		$meta['error'] = null;
		ksort($meta);

		$output = array('data' => $data, 'meta' => $meta);

		$this->response($output);
	}

	/**
	 * Create a new retailer
	 */
	public function action_post() {
		$data = array();
		$exclude = array('id', 'created', 'edited', 'edited_by', 'created_by', 'status');
		$fields = DB::list_columns('retailers');
		$jfields = array('contact', 'social', 'categories', 'tags');

		foreach($fields as $key=>$field) {
			if (in_array($key, $exclude)) { continue; }
			$val = Input::post($key, $this->param($key, ''));
			//if ($val) { if (in_array($key, $jfields)) { $val = json_encode($val); } }
			$data[$key] = $val;
		}

		list($insert_id, $rows_affected) = DB::insert('retailers')->set($data)->execute();

		if ($insert_id) {
			$output = $this->action_get($insert_id);
		}
		else {
			$output = array('data' => Input::post(), 'meta' => array('error' => 'Unable to create retailer', 'status' => 0));
		}

		$this->response($output);

	} // ---> action_post()

	/**
	 * Update a retailer
	 */
	public function action_put() {
		$data = array();
		$exclude = array('id', 'created', 'edited', 'edited_by', 'created_by', 'status');
		$fields = DB::list_columns('retailers');
		$id = Input::param('id', $this->param('id'));
		$jfields = array('contact', 'social', 'categories', 'tags');

		$params = Input::put();

		foreach($params as $key=>$field) {
			if (in_array($key, $exclude)) { continue; }
			if (!isset($fields[$key])) { continue; }
			$val =  Input::put($key, $this->param($key, ''));
			//if ($val) { if (in_array($key, $jfields)) { $val = json_encode($val); } }
			$data[$key] = $val;
		}

		$data['edited'] = date('Y-m-d H:i:s');

		$upd = DB::update('retailers')->set($data)->where('id', '=', $id)->execute();

		if ($upd) {
			$output = $this->action_get($id);
		}
		else {
			$output = array('data' => $params, 'meta' => array('error' => 'Unable to update retailer', 'status' => 0));
		}

		$this->response($output);

	} // ---> action_put()

	/**
	 * Get 25 retailers with pagination meta that match the supplied search parameters
	 */
	public function action_search() {

		$dfields = array('created_by', 'edited_by', 'created', 'edited');
		$exact_match = array('st');
		$exclude = array('id', 'created', 'edited', 'edited_by', 'created_by', 'status', 'st', 'zip');
		$fields = DB::list_columns('malls');
		$find = array();
		$jfields = array('contact', 'social', 'categories', 'tags');
		$params = Input::get();
		$retailers = array();
		$starts_with = array('zip');

		foreach($params as $key => $field) {
			if (in_array($key, $exclude)) { continue; }
			if (!isset($fields[$key])) { continue; }

			$val = Input::get($key, $this->param($key, ''));
			$val = str_replace(', ', ',', $val);
			$val = str_replace(' ,', ',', $val);

			$find[$key] = explode(',', $val);
		}

		$sql = "SELECT :selects FROM suey_retailers WHERE status = 1";
		foreach($find as $key => $vals) {
			$ors = array();
			foreach ($vals as $val) {
				if (in_array($key, $exact_match)) {
					array_push($ors, "$key = '$val'");
				}
				else {
					$val = (in_array($key, $starts_with)) ? "$val%" : "%$val%";
					array_push($ors, "$key LIKE '$val'");
				}
			}
			$sql .= " AND (".implode(' OR ', $ors).")";
		}

		$isql = str_replace(' :selects ', ' id ', $sql);

		if (isset($params['mall'])) {
			$ids = array();
			$iresults = DB::query($isql)->execute();
			foreach($iresults as $item) {
				$malls = $this->_malls($item['id'], $params['mall']);
				if (count($malls) < 1) { continue; }
				array_push($ids, $item['id']);
			}
			$ids = (count($ids) > 0) ? implode(',', $ids) : 0;
			$sql = "SELECT :selects FROM suey_retailers WHERE id IN ($ids)";
		}
		elseif (isset($params['st'])) {
			$ids = array();
			$iresults = DB::query($isql)->execute();
			foreach($iresults as $item) {
				$malls = $this->_malls($item['id'], array('field' => 'malls.st', 'operand' => '=', 'value' => $params['st']));
				if (count($malls) < 1) { continue; }
				array_push($ids, $item['id']);
			}
			$ids = (count($ids) > 0) ? implode(',', $ids) : 0;
			$sql = "SELECT :selects FROM suey_retailers WHERE id IN ($ids)";
		}

		elseif (isset($params['zip'])) {
			$ids = array();
			$iresults = DB::query($isql)->execute();
			foreach($iresults as $item) {
				$malls = $this->_malls($item['id'], array('field' => 'malls.zip', 'operand' => '=', 'value' => $params['zip']));
				if (count($malls) < 1) { continue; }
				array_push($ids, $item['id']);
			}
			$ids = (count($ids) > 0) ? implode(',', $ids) : 0;
			$sql = "SELECT :selects FROM suey_retailers WHERE id IN ($ids)";
		}

		// Pagination query
		$psql = str_replace(' :selects ', ' id ', $sql);
		$cresults = DB::query($psql)->execute();
		$meta = array('pagination' => $this->_pagination(count($cresults), Input::param('page', 1)));
		$meta['status'] = 1;
		$meta['error'] = null;
		ksort($meta);

		// Query
		$sql = str_replace(' :selects ', ' * ', $sql);
		$sql .= " ORDER BY name ASC LIMIT ".$meta['pagination']['offset']['current'].", ".$meta['pagination']['limit'];
		$results = DB::query($sql)->execute();

		// Parse the results
		foreach($results as $item) {
			// format json fields
			foreach($jfields as $field) {
				$val = json_decode($item[$field], true);
				if ($val) {
					if ($field != 'categories' && $field != 'tags') { ksort($val); }
					else { sort($val); }
				}
				$item[$field] = $val;
			}

			// Remove unnecessary fields
			foreach($dfields as $field) { unset($item[$field]); }

			//$find_mall = (isset($params['mall'])) ? $params['mall'] : null;
			$item['malls'] = $this->_malls($item['id']);
			//$item['hours'] = $this->_hours($item['id'], 'retailer');

			ksort($item);
			array_push($retailers, $item);
		}

		$meta['search'] = $find;
		if (isset($params['mall'])) { $meta['search']['mall'] = $params['mall']; }
		if (isset($params['st'])) { $meta['search']['st'] = $params['st']; }
		if (isset($params['zip'])) { $meta['search']['zip'] = $params['zip']; }

		// Set the output
		$data = array('data' => array('retailers'=>$retailers), 'meta' => $meta);
		$this->response($data);
	}

	// Internal functions
	/**
	 * Get a retailers retailers
	 */
	protected function _malls($retailer_id, $find = null) {
		$malls = array();

		$qry = DB::select('locations.id', 'locations.gps', 'locations.mall_id', 'malls.name')
		->from('locations')
		->join('malls')
		->on('malls.id', '=', 'locations.mall_id')
		->where('locations.retailer_id', '=', $retailer_id)
		->and_where('locations.status', '=', 1)
		->and_where('malls.status', '=', 1)
		->order_by('malls.name', 'asc')
		->limit(5);

		if ($find) {

			if (is_array($find)) {
				$field = $find['field'];
				$op = $find['operand'];
				$find = $find['value'];
			}

			else {
				$field = (is_numeric($find)) ? 'malls.id' : 'malls.name';
				$op = (is_numeric($find)) ? '=' : 'like';
				$find = (is_numeric($find)) ? $find : "%$find%";
			}
			$qry->and_where($field, $op, $find);
		}

		$results = DB::query($qry)->execute();

		foreach($results as $item) {
			$item['gps'] = json_decode($item['gps'], true);
			if ($item['gps']) { ksort($item['gps']); }

			$item['location'] = array('location_id' => $item['id'], 'gps' => $item['gps']);

			unset($item['gps']);
			unset($item['id']);

			ksort($item);

			array_push($malls, $item);
		}

		return $malls;
	} // ---> _malls()

}






































// EOF