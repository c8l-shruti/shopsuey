<?php

/**
 * The Api Controller.
 * This controllers the CRUD proceedures for offers codes
 *
 * @package  app
 * @extends  Controller_Rest
 */

class Controller_Api_Offer_Code extends Controller_Api {

	public function action_get() {
		$id = $this->param('id');
		$offer_code = Model_Offer_Code::query()->where('status', '>', 0)->where('id', $id)->get_one();
		if (empty($id) || !$offer_code) {
			$this->_error_response(Code::ERROR_INVALID_OFFER_CODE_ID);
			return;
		}

		// Set the output
		$data = array('data' => array('offer_code' => Helper_Api::offer_code_response($offer_code)), 'meta' => array('status' => 1, 'error' => null));
		$this->response($data);
	}
	
	public function action_post() {
		$exclude_fields = array('id', 'created_at', 'updated_at', 'status');

		$offer_id = $this->param('offer_id');
		$offer = Model_Offer::query()->where('status', '>', 0)->where('id', $offer_id)->get_one();
		if (empty($offer_id) || !$offer) {
			$this->_error_response(Code::ERROR_INVALID_OFFER_ID);
			return;
		}

		$params = Input::post();
		
		foreach ($exclude_fields as $field) {
			unset($params[$field]);
		}
		
		$offer_code = Model_Offer_Code::forge($params);
		$offer_code->auto_generated = FALSE;
		$offer_code->status = 1;
		$offer_code->offer = $offer;
		
		if (! $offer_code->is_valid_code()) {
			$this->_error_response(Code::ERROR_INVALID_OFFER_CODE);
			return;
		}
		
		try {
			$offer_code->save();
			$output = array('data' => array('offer_code' => $offer_code), 'meta' => array('error' => null, 'status' => 1));
		} catch (Orm\ValidationFailed $e) {
			$output = array('data' => Input::post(), 'meta' => array('error' => join(' / ', $e->get_fieldset()->error()), 'status' => 0));
		}

		$this->response($output);
	}

	public function action_put() {
		$exclude_fields = array('id', 'created_at', 'updated_at', 'offer_id', 'auto_generated');

		$id = $this->param('id');
		$offer_code = Model_Offer_Code::query()->where('status', '>', 0)->where('id', $id)->get_one();
		if (empty($id) || !$offer_code) {
			$this->_error_response(Code::ERROR_INVALID_OFFER_CODE_ID);
			return;
		}
		
		$params = Input::put();

		foreach ($exclude_fields as $field) {
			unset($params[$field]);
		}

		$offer_code->from_array($params);

		try {
			$offer_code->save();
			$output = array('data' => array('offer_code' => $offer_code), 'meta' => array('error' => null, 'status' => 1));
		} catch (Orm\ValidationFailed $e) {
			$output = array('data' => Input::post(), 'meta' => array('error' => join(' / ', $e->get_fieldset()->error()), 'status' => 0));
		}
		
		$this->response($output);
	}
}
