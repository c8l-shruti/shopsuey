<?php

use Fuel\Core\Package;

use Fuel\Core\Model;
use Fuel\Core\Database_Exception;

/**
 * The User API Controller.
 * This controllers the CRUD proceedures for contests
 *
 * @package  app
 * @extends  Controller_Rest
 */

class Controller_Api_Contest extends Controller_Api {
    public function action_list() {
        if (Input::method() != 'GET') { $this->response($this->no_access); return; }
    
        $contests = Model_Contest::query()->order_by('start_date', 'desc')->get();

        $obj_contests = array();
        foreach($contests as $contest) {
            $obj_contests[] = Helper_Api::model_to_real_object($contest);
        }
    
        $meta = array(
            'status' => 1,
            'error' => null,
        );
        
        $data = array('data' => array('contests' => $obj_contests), 'meta' => $meta);
        $this->response($data);
    }
    
    public function action_rewards() {
        if (Input::method() != 'GET') { $this->response($this->no_access); return; }
    
        $contest_id = $this->param('id', 0);
        
        $rewards = Model_Reward::query()->where('contest_id', $contest_id)->get();
    
        $obj_rewards = array();
        foreach($rewards as $reward) {
            $offer_code = array_slice($reward->offer->offer_codes, 0, 1);
            $redeemed = $offer_code && !empty($offer_code[0]->offer_redeems);
            $obj_rewards[] = Helper_Api::reward_response($reward, $reward->offer, $redeemed);
        }
    
        $meta = array(
            'status' => 1,
            'error' => null,
        );
    
        $data = array('data' => array('rewards' => $obj_rewards), 'meta' => $meta);
        $this->response($data);
    }
}
