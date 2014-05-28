<?php

class Controller_Admin_promos extends Controller_Cms {
    
    public function action_enable() {
        return $this->action_change_enabled(Request::active()->param('id'), '1');
    }
    
    public function action_disable() {
        return $this->action_change_enabled(Request::active()->param('id'), '0');
    }
    
    protected function action_change_enabled($id, $status) {
        $id = Request::active()->param('id');
        $contest = Model_Contest::find($id);
        
        if (!$contest) {
            return $this->error_404();
        }
        
        $contest->enabled = $status;
        $contest->save();
        return Response::redirect('admin/promos');
    }

    public function action_getWinner(){
        
        $promoId = Request::active()->param('id');
        $offerId = Request::active()->param('offer_id');
        
        //$contest = Model_Contest::find($promoId);
        $offer = Model_Offer::find($offerId);

        $reward = \Model_Reward::query()->where('offer_id', $offer->id)->get_one();
        
        error_log(var_export($reward->contestant, true));
        
        $userInfoHtml = View::forge('cms/admin/promos/winnerInfo', array("isNew" => false, "winnerUser" => $reward->contestant->user, "promoId" => $promoId, "offerId" => $offerId))->render();
        
        return Response::forge($userInfoHtml);
        
    }
    
    public function action_doSetWinner(){
        
        $promoId = Request::active()->param('id');
        $offerId = Request::active()->param('offer_id');
        $userId = Request::active()->param('user_id');
        
        $contest = Model_Contest::find($promoId);
        $offer = Model_Offer::find($offerId);

        $winnerUser = Model_User::find($userId);
                
        //SAVE OFFER TO USER
        //AGREGAR CONTESTANT (YA NO SE AGREGAN POR SCRIPT)
        
        $is_already_in_contest = Model_Contestant::query()
                ->where('contest_id', $contest->id)
                ->where('user_id', $winnerUser->id)
                ->count();
        
        if (!$is_already_in_contest) {
            
            $contestant = new Model_Contestant();
            $contestant->user = $winnerUser;
            $contestant->contest = $contest;
            $contestant->pn_sent = 0;
            $contestant->save();
            
        }else{
            return "false";
            //$this->_error_response("CRITICAL ERROR: WINNER ID: ".$winnerUser->id." EMAIL: ".$winnerUser->email." IS ALREADY IN CONTEST. THIS SHOULD NOT HAPPEN!");
        }
        
        //GIVE PRIZES TO CONTESTANT
        //BUSCAR REWARD(S) POR OFFER
        
        $reward = \Model_Reward::query()->where('offer_id', $offer->id)->get_one();
        
        if (!$reward) $this->_error_response("There is no reward for offer ".$offer->name." ID: ".$offer->id);

        //error_log("Giving prizes for contest ".$contest->name." ID: ".$contest->id." OFFER ".$offer->name." ID: ".$offer->id);
        
        if (empty($reward->contestant_id)){

            $contestant->rewards[] = $reward;
            $reward->contestant = $contestant;
            
            //error_log("Giving reward " . $reward->offer->name." to user ID:".$contestant->user_id . " EMAIL: ".$contestant->user->email);
            
            $reward->save();
            $contestant->save();

            try {
                $contestant->sendNotificationPNToWinner($reward);
            }catch (Exception $exc){
                error_log("ERROR SENDING PUSH NOTIFICATION: ".$exc->getMessage());
            }

            try {
                $contestant->sendNotificationEmailToWinner($reward);
            }catch (Exception $exc){
                error_log("ERROR SENDING EMAIL NOTIFICATION: ".$exc->getMessage());
            }
            
        }else{
            return "false";
        }
        
        return "true";
    }
    
    public function action_doFindWinner(){
        
        $promoId = Request::active()->param('id');
        $offerId = Request::active()->param('offer_id');
        
        $contest = Model_Contest::find($promoId);
        $offer = Model_Offer::find($offerId);

        try{
            $winnerUser = $contest->findWinner($offer);
        }  catch (Exception $e){
            return "false";
        }
        
        $userInfoHtml = View::forge('cms/admin/promos/winnerInfo', array("isNew" => true, "winnerUser" => $winnerUser, "promoId" => $promoId, "offerId" => $offerId))->render();
        
        //error_log(var_export($userInfoHtml, true));
        
        //TODO: IMPROVE THIS!
        return Response::forge($userInfoHtml);
               
    }
    
    public function action_winner(){
        
        $promoId = Request::active()->param('id');
        $offerId = Request::active()->param('offer_id');
        
        //$contest = Model_Contest::find($promoId);
        $offer = Model_Offer::find($offerId);

        // Include .js
        $apnd = array('files/base.js', 'files/promos.js');
        $excl = array('autotab', 'cleditor', 'dualist', 'elfinder', 'fullcalendar', 'flot', 'maskedinput', 'tagsinput', 'upload', 'wizards');
        $scripts = CMS::scripts($apnd, NULL, $excl);

        // Set header params
        $header_data = array(
                'style' => 'styles.css',
                'scripts' => $scripts,
                'ie' => 'ie.css'
        );
        
        $reward = \Model_Reward::query()->where('offer_id', $offer->id)->get_one();
        
        $contentData = array(
            "promoId" => $promoId,
            "offerId"   => $offerId,
            'login_hash' => $this->user_login->login_hash,
        );
        
        //error_log(var_export($contentData, true));
        
        $content = View::forge('cms/admin/promos/winner', $contentData);
        
        $wrapper_data = array(
                'page' => array(
                        'name' => 'Find a winner!',
                        'subnav' => View::forge('cms/admin/promos/menu', array('promos' => CMS::contests()))
                ),
            
                'crumbs' => array(
                        array('title'=>'Dashboard', 'link'=>Uri::create('dashboard')),
                        array('title'=>'Administration', 'link' => Uri::create('admin')),
                        array('title'=>'Promos', 'link' => Uri::create('admin/promos'))
                ),

                'me' => $this->user_login->user,
                'message' => $this->msg,
                'content' => $content
        );
        
        // Compile view
        $header = View::forge('base/header', $header_data);
        $cont = View::forge('cms/wrapper', $wrapper_data);
        $footer = View::forge('base/footer');
        $temp = $header . $cont . $footer;

        return Response::forge($temp);
        
    }
    
    public function action_index() {
        // Include .js
        
        $apnd = array('files/base.js', 'files/promos.js');
        $excl = array('autotab', 'cleditor', 'dualist', 'elfinder', 'fullcalendar', 'flot', 'maskedinput', 'tagsinput', 'upload', 'wizards');
        $scripts = CMS::scripts($apnd, NULL, $excl);

        // Set header params
        $header_data = array(
                'style' => 'styles.css',
                'scripts' => $scripts,
                'ie' => 'ie.css'
        );
        
        $id = Request::active()->param('id');
        $contest = $rewards = null;
        $title = '';
        if ($id) {   
            $contest = Model_Contest::find($id);
            if (!$contest) {
                return $this->error_404();
            }
            
            $this->api->setMethod('GET');
            $this->api->setData(array());
            $this->api->setURL(Uri::create("api/contests/" . $id . "/rewards"));
            $rewardsOutput = $this->api->execute();
            $rewards = $rewardsOutput->data->rewards;
            
            $title = "Rewards for " . $contest->name;
        }

        // Set content params
        $content_data = array(
            'login_hash' => $this->user_login->login_hash,
            'me' => $this->user_login->user,
            'title' => $title,
            'rewards' => $rewards,
            'promo' => $contest
        );

        $content = View::forge('cms/admin/promos/list', $content_data);

        $wrapper_data = array(
                'page' => array(
                        'name' => 'Promos Management',
                        'subnav' => View::forge('cms/admin/promos/menu', array('promos' => CMS::contests()))
                ),
            
                'crumbs' => array(
                        array('title'=>'Dashboard', 'link'=>Uri::create('dashboard')),
                        array('title'=>'Administration', 'link' => Uri::create('admin')),
                        array('title'=>'Promos', 'link' => Uri::create('admin/promos'))
                ),

                'me' => $this->user_login->user,
                'message' => $this->msg,
                'content' => $content
        );

        // Compile view
        $header = View::forge('base/header', $header_data);
        $cont = View::forge('cms/wrapper', $wrapper_data);
        $footer = View::forge('base/footer');
        $temp = $header . $cont . $footer;

        return Response::forge($temp);
    }
    
    public function action_edit() {
        return $this->action_add('edit');
    }
    
    public function action_add($mode = 'add') {
        
        if ($mode == 'edit') {
            $id = Request::active()->param('id');
            $contest = Model_Contest::find($id);
            if (!$contest) {
                return $this->error_404();
            }
        } else {
            $contest = new Model_Contest();
        }
        
        $content = '';
        $output = null;

        // Set content params
        $content_data = array(
            'me' => $this->user_login->user,
            'output' => $output,
            'login_hash' => $this->user_login->login_hash,
            'contest' => $contest
        );

        if (Input::post()) {

            $data = $this->process_form(Input::post());
            
            error_log(var_export($data, true));
            
            $contest->name = $data['name'];
            $contest->start_date = $data['date_start'];
            $contest->end_date = $data['date_end'];

            $contest->how_favorite_location_id = ($data['how_enter'] == "favorite")?$data['how_favorite_location_id']:null;
            $contest->how_checkin_location_id = ($data['how_enter'] == "checkin")?$data['how_checkin_location_id']:null;
            $contest->how_signup = ($data['how_enter'] == "signup");

            $contest->enabled = 0;
            $contest->save();

            $msg = array('type' => 'success', 'message' => 'Successfully added promo ' . $contest->name, 'autohide' => true);
            Session::set('message', $msg);

            return Response::redirect('admin/promos');

        } else {

            $content = View::forge('cms/admin/promos/edit', $content_data);
            return $this->form_edit($content, $content_data);

        }
        
    }
    
    private function form_edit($content, $data) {
		$data = (object) $data;

		// Include .js
		$apnd = array('files/base.js');
		$excl = array('autotab', 'dualist', 'cleditor', 'elfinder', 'fullcalendar', 'flot', 'maskedinput', 'upload');
		$scripts = CMS::scripts($apnd, NULL, $excl);

		// Set header params
		$header_data = array(
			'style' => 'styles.css',
			'scripts' => $scripts,
			'ie' => 'ie.css',
	        'styles' => 'jquery.Jcrop.min.css',
		);

		$wrapper_data = array(
			'page' => array(
				'name' => 'Promo Creator',
				'subnav' => View::forge('cms/admin/promos/menu', array(
                    'promos' => CMS::contests()
		        )),
             ),

			'crumbs' => array(
				array('title'=>'Dashboard', 'link' => Uri::create('dashboard')),
				array('title'=>'App Admin', 'link' => Uri::create('admin')),
				array('title'=>'Promos', 'link' => Uri::create('admin/promos')),
				array('title'=>'Add', 'link' => '#'),
			),

			'me' => $data->me,
			'message' => $this->msg,
			'content' => $content
		);
		// Compile view
		$header = View::forge('base/header', $header_data);
		$cont = View::forge('cms/wrapper', $wrapper_data);
		$footer = View::forge('base/footer');
		$temp = $header . $cont . $footer;

		return Response::forge($temp);
	}
    
    private function process_form($data) {

		// Date - time start
		$data['date_start'] = implode(' ', $data['date_start']);
		$data['date_start'] = date('Y-m-d H:i:s', strtotime($data['date_start']));

		// Date - time end
		$data['date_end'] = implode(' ', $data['date_end']);
		$data['date_end'] = date('Y-m-d H:i:s', strtotime($data['date_end']));

        return $data;
    }

}
