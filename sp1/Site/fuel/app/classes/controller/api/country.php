<?php

class Controller_Api_Country extends Controller_Api {

	public function action_list() {
		if (Input::method() != 'GET') { $this->response($this->no_access); return; }

		$countries = Model_Country::query()->order_by('position')->get();

		$meta = array('error' => null, 'status' => 1);
		$data = array('data' => array('countries' => $countries), 'meta' => $meta);
		$this->response($data);
	}
}
