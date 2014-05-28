<?php

/**
 * The Api Controller.
 * This controllers the CRUD proceedures for offer redeems
 *
 * @package  app
 * @extends  Controller_Rest
 */

class Controller_Api_Offer_Redeem extends Controller_Api {

	/**
	 * Redeem an offer
	 */
	public function action_post() {
		$offer_id = $this->param('id');
		// Get the offer
		$offer = Model_Offer::find($offer_id);
		if (!$offer) {
			return $this->_error_response(Code::ERROR_INVALID_OFFER_ID);
		}
	
		// Check status and availability dates for the offer
		$current_time = time();
		if ($offer->status != 1 || !$offer->redeemable || $current_time < strtotime($offer->date_start) || $current_time > strtotime($offer->date_end)) {
			return $this->_error_response(Code::ERROR_OFFER_UNAVAILABLE);
		}

		// Get available codes for the offer
		$offer_codes = Model_Offer_Code::query()->where('offer_id', $offer_id)->where('status', 1)->get();

		if (! $offer->multiple_codes) {
		    $offer_code = NULL;
		    // Search the first non auto generated code (if any)
		    foreach($offer_codes as $code) {
		        if (! $code->auto_generated) {
		            $offer_code = $code;
		            break;
		        }
		    }
		    if (is_null($offer_code)) {
			    return $this->_error_response(Code::ERROR_NO_AVAILABLE_CODES);
		    }
		}

		$offer_code_ids = array_map(function ($e) { return $e->id; }, $offer_codes);

		// Get user redeems for the offer
		if (count($offer_code_ids) == 0) {
			$redeems = array();
		} else {
			$redeems = Model_Offer_Redeem::query()
			->where('offer_code_id', 'in', $offer_code_ids)
			->where('user_id', $this->user_login->user_id)
			->get();
		}

		if ($offer->allowed_redeems != 0 && count($redeems) >= $offer->allowed_redeems) {
			return $this->_error_response(Code::ERROR_ALREADY_REDEEMED_ALLOWED_TIMES);
		}
		
		if($offer->multiple_codes) {
			// Generate a new code for the redeem
			$offer_code = new Model_Offer_Code();
			if (empty($offer->default_code_type)) {
				return $this->_error_response(Code::ERROR_NO_DEFAULT_AUTO_TYPE);
			}
			$offer_code->type = $offer->default_code_type;
			$offer_code->code = Model_Offer_Code::get_random_code($offer->default_code_type);
			$offer_code->offer = $offer;
			$offer_code->auto_generated = TRUE;
			$offer_code->status = 1;
			if (!$offer_code->save()) {
				return $this->_error_response(Code::ERROR_CREATE_ERROR);
			}
		}

		// Create the entry for the redeem
		$redeem = new Model_Offer_Redeem();
		$redeem->date = date('Y-m-d H:i:s');
		$redeem->offer_code = $offer_code;
		$redeem->user_id = $this->user_login->user_id;
		if (!$redeem->save()) {
			return $this->_error_response(Code::ERROR_REDEEM_CREATE_ERROR);
		}

		Helper_Activity::log_activity($this->user_login->user, 'redeem_offer', array('offer_id' => (int)$offer_id));
        Helper_Analytics::log_event($this->user_login->user, 'offer', 'redeem', 'offer' . $offer->id);
		$redeem_info = $this->_get_redeem_info($redeem);
		$output = array('meta' => array('error' => '', 'status' => 1), 'data' => array('redeem' => $redeem_info));
		$this->response($output);
	}

	/**
	 * Get offer redeem info
	 */
	public function action_get() {
		$redeem_id = $this->param('id');
		// Check if the redeem actually belongs to the current user
		$redeem = Model_Offer_Redeem::query()
			->where('id', $redeem_id)
			->where('user_id', $this->user_login->user_id)
			->get_one();

		if (! $redeem) {
			return $this->_error_response(Code::ERROR_INVALID_REDEEM_ID);
		}

		$redeem_info = $this->_get_redeem_info($redeem);
		$output = array('meta' => array('error' => '', 'status' => 1), 'data' => array('redeem' => $redeem_info));
		$this->response($output);
	}

	private function _get_redeem_info($redeem) {
		$redeem_ar = array();
		$redeem_ar['id']         = $redeem->id;
		$redeem_ar['offer_id']   = $redeem->offer_code->offer_id;
		$redeem_ar['code']       = $redeem->offer_code->code;
		$redeem_ar['type']       = $redeem->offer_code->type;
		$sub_query = Model_Offer_Code::query()
		->select('id')
		->where('offer_id', $redeem->offer_code->offer_id);
		$redeem_ar['times_used'] = Model_Offer_Redeem::query()
		->where('user_id', $this->user_login->user_id)
		->where('offer_code_id', 'in', $sub_query->get_query())
		->count();
		return $redeem_ar;
	}
}
