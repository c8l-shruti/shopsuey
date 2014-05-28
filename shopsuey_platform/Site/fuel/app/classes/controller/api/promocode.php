<?php

/**
 * The Api Controller.
 * This controllers the CRUD proceedures for promo codes
 *
 * @package  app
 * @extends  Controller_Rest
 */
use Fuel\Core\Input;

class Controller_Api_Promocode extends Controller_Api {
 
    public function action_check_promo_code() {
        if (Input::method() != 'GET') { $this->response($this->no_access); return; }
        
        $code = Input::get('code', null);
        
        if (!is_null($code)) {
            $promo_code = Model_Promocode::query()->where('code', '=', $code)
                                                  ->get_one();
            
            if ($promo_code) {
                if ($promo_code->is_active()) {
                $data = array(
                    'promo_code' => Helper_Api::promo_code_response($promo_code)
                );
                
                $this->response($data);
                } else {
                    return $this->_error_response(Code::ERROR_INACTIVE_PROMO_CODE);
                }
            } else {
                return $this->_error_response(Code::ERROR_INVALID_PROMO_CODE);
            }
        } else {
            return $this->_error_response(Code::ERROR_MISSING_CODE_PARAMETER);
        }
    }
    
}
