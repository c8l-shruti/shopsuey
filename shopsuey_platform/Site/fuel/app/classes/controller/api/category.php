<?php

class Controller_Api_Category extends Controller_Api {

	public function action_list() {
        if (Input::method() != 'GET') { $this->response($this->no_access); return; }
        
        $categories = Model_Category::query()->order_by('name')->get();

        $meta = array('error' => null, 'status' => 1);
        $data = array('data' => array('categories' => $categories), 'meta' => $meta);
        $this->response($data);
	}

}
