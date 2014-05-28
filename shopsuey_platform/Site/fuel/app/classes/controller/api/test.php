<?php

/**
 * The Api Controller.
 * This controller is the base api controller and is to be extended by all api functions
 * 
 * @package  app
 * @extends  Controller_Rest
 */
 
class Controller_Api_Test extends Controller_Api {
	
	public function action_get() {
		$this->response(array(
            'foo' => Input::param('foo'),
            'baz' => array(
                1, 50, 219
            ),
			'empty' => $this->param('user'),
			'method' => $this->param('method')
        ));		
	}
	
}










// EOF