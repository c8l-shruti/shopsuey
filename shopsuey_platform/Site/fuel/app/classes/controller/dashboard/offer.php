<?php

/**
 * The offer Controller.
 * This controllers the CRUD proceedures for the CMS offers section
 *
 * @package  app
 * @extends  Controller_Cms
 */

class Controller_Dashboard_Offer extends Controller_Dashboard {
    public $err = null;

    public function action_import(){
                
        //Include .js
        $apnd = array('files/base.js', 'files/offers.js', 'files/import.js', 'files/spin.js');
        $excl = array('autotab', 'cleditor', 'dualist', 'elfinder', 'fullcalendar', 'flot', 'maskedinput', 'upload', 'wizards');
        $scripts = CMS::scripts($apnd, NULL, $excl);
 
        // Set header params
        $header_data = array(
            'style' => array('styles.css', 'autoSuggest.css'),
            'scripts' => $scripts,
            'ie' => 'ie.css'
        );
        
        $cities = DB::select('city')->from('locations')->distinct(true)->order_by('city')->execute();
        
        $content_data = array(
            'providers' => array("Sqoot", "8Coupons"),
            'me' => $this->user_login->user,
            'login_hash' => $this->user_login->login_hash,
            'cities'    => $cities,
        );

        $content = View::forge('cms/offer/import', $content_data);
        
        $default_tz  = 'Pacific/Honolulu'; // @todo Meter en config
        
        $timezone = Input::param('timezone', null);
        if (is_null($timezone)) {
            $timezone = $default_tz;
        }
        
        $wrapper_data = array(
            'page' => array(
                'name' => 'Import Offers',
                'subnav' => View::forge('cms/offer/menu', array(
                    'in_offer_list'    => false,
//                    'active'           => $active,
//                    'inactive'         => $inactive,
                    'default_timezone' => $default_tz
                )),
                'icon' => 'icon-cart'),

            'crumbs' => array(
                array('title'=>'Dashboard', 'link'=>Uri::create('dashboard')),
                array('title'=>'Offers', 'link' => Uri::create('dashboard/offers'))
            ),

            'me' => $this->user_login->user,
            'message' => $this->msg,
            'content' => $content,
            'company' => $this->get_current_company()
        );
        
        // Compile view
        $header = View::forge('base/header', $header_data);
        $cont = View::forge('cms/wrapper', $wrapper_data);        
        $footer = View::forge('base/footer');
        $temp = $header.$cont.$footer;

        return Response::forge($temp);
        
    }
    
    public function action_add($action = 'add') {
        $content = '';
        $offer = null;

        if ($action == 'edit') {
            $offer_id = $this->param('id');
            if (!$offer_id || !($offer = CMS::get_offer($offer_id))) {
            	return $this->error_404();
            }
        } else {
            $offer_id = $this->param('id', '');
            if (empty($offer_id)) {
        	    $offer = new stdClass();
        	    foreach (Model_Offer::properties() as $property => $value) {
        	        $offer->$property = '';
        	    }
        	    
        	    $offer->locations = array();
        	    $offer->offer_codes = array();
        	    $offer->all_locations = '0';
            } else {
                if (!($offer = CMS::get_offer($offer_id))) {
                    return $this->error_404();
                }
            }
        	
        	unset($offer->id);
        }

        $offer->location_ids = array();
        foreach ($offer->locations as $loc) {
            $offer->location_ids[] = $loc->id;
        }

        // Set content params
        $content_data = array(
            'action' => $action,
            'offer' => $offer,
            'me' => $this->user_login->user,
            'login_hash' => $this->user_login->login_hash,
        );

        if (Input::post()) {
            if ($action == 'add') { return $this->offer_add($content_data); }
            if ($action == 'edit') { return $this->offer_update($content_data); }
            
        } else {
            $content_data['offer_code'] = '';
            $content_data['offer_code_type'] = '';
            
            // Find the first non automatically generated offer code, if any
            foreach($offer->offer_codes as  $offer_code) {
                if (!$offer_code->auto_generated) {
                    $content_data['offer_code'] = $offer_code->code;
                    $content_data['offer_code_type'] = $offer_code->type;
                    break;
                }
            }
            // Generate random codes
            $content_data['ean13_code'] = Model_Offer_Code::get_random_code(Model_Offer_Code::EAN13_TYPE);
            $content_data['qr_code'] = Model_Offer_Code::get_random_code(Model_Offer_Code::QR_CODE_TYPE);
            $content_data['code_128'] = Model_Offer_Code::get_random_code(Model_Offer_Code::CODE128_TYPE);
            $content = View::forge('cms/offer/edit', $content_data);
            return $this->form_edit($action, $content, $content_data);
        }
    }

    public function action_edit() {
        return $this->action_add('edit');
    }

    public function action_index() {

        // Include .js
        $apnd = array('files/base.js', 'files/offers.js');
        $excl = array('autotab', 'cleditor', 'dualist', 'elfinder', 'fullcalendar', 'flot', 'maskedinput', 'upload', 'wizards');
        $scripts = CMS::scripts($apnd, NULL, $excl);

        // Set header params
        $header_data = array(
            'style' => array('styles.css', 'autoSuggest.css'),
            'scripts' => $scripts,
            'ie' => 'ie.css'
        );
        
        $default_tz  = 'Pacific/Honolulu'; // @todo Meter en config
        $page   = $this->param('page', 1);
        $string = $this->param('string', Input::param('string', ''));
        
        $data = array('page' => $page, 'filter' => $string, 'date_status' => 'cms', 'status_all' => 1);
        $url_parts = array();
        
        $active = Input::param('active', true);
        if ($active) {
            $data['date_status']           = 'active';
            $data['include_active']        = 1;
             
            $url_parts[] = 'active=1';
        } else {
            $url_parts[] = 'active=0';
        }
        
        $inactive = Input::param('inactive', true);
        if ($inactive) {
            $data['date_status']           = 'inactive';
            $data['include_inactive']      = '1';
        
            $url_parts[] = 'inactive=1';
        } else {
            $url_parts[] = 'inactive=0';
        }
        
        $timezone = Input::param('timezone', null);
        if (is_null($timezone)) {
            $timezone = $default_tz;
        }
        $url_parts[] = "timezone=$timezone";
        $data['timezone'] = $timezone;
        
        $data['from_cms'] = '1';
        
        if (!$this->user_login->user->is_admin()) {
            $data['from_user_locations'] = '1';
        }
        if (Input::param('location_id', '')) {
            $data['from_location'] = Input::param('location_id', '');
            $url_parts[] = "location_id={$data['from_location']}";
        }
        if (Input::param('include_merchants', '')) {
            $data['include_merchants'] = 1;
        }
        
        $data['order_direction'] = Input::param('order_direction', 'desc');

        $data['order_by'] = Input::param('order_by', 'date_start');

        $url_parts[] = "gallery=" . Input::param('gallery', '0');

        $this->api->setData($data);
        $this->api->setMethod('GET');
        $this->api->setURL(Uri::create("api/offers"));

        $output = $this->api->execute();

        $url = implode('&', $url_parts);
        
        // Set content params
        $content_data = array(
            'me' => $this->user_login->user,
            'offers' => array(),
            'pagination' => null,
            'search' => $string,
            'query_params' => $url,
            'order_by' => $data['order_by'],
            'order_direction' => $data['order_direction'],
            'title' => 'offers',
            'login_hash' => $this->user_login->login_hash,
            'active' => $active,
            'inactive' => $inactive
        );
        
        /*foreach ($output->data->offers as $offer) {
            error_log($offer->date_start);
        }*/

        if ($output) {
            $content_data['offers'] = $output->data->offers;
            $content_data['pagination'] = $output->meta->pagination;
            $label = ($content_data['pagination']->records > 1) ? ' offers' : ' offer';
            $content_data['title'] = $content_data['pagination']->records;
            $content_data['title'] .= ($string) ? ' results for '.$string : $label;
        }
        
        if (Input::param('gallery', false)) {
            $view_name = 'cms/offer/gallery';
        } else {
            $view_name = 'cms/offer/list';
        }
        
        $content = View::forge($view_name, $content_data);

        $wrapper_data = array(
            'page' => array(
                'name' => 'Offer Management',
                'subnav' => View::forge('cms/offer/menu', array(
                    'in_offer_list'    => true,
                    'active'           => $active,
                    'inactive'         => $inactive,
                    'default_timezone' => $default_tz
                )),
                'icon' => 'icon-cart'),

            'crumbs' => array(
                array('title'=>'Dashboard', 'link'=>Uri::create('dashboard')),
                array('title'=>'Offers', 'link' => Uri::create('dashboard/offers'))
            ),

            'me' => $this->user_login->user,

            'message' => $this->msg,

            'content' => $content,
                
            'company' => $this->get_current_company(),
        );

        // Compile view
        $header = View::forge('base/header', $header_data);
        $cont = View::forge('cms/wrapper', $wrapper_data);
        $footer = View::forge('base/footer');
        $temp = $header . $cont . $footer;

        return Response::forge($temp);
    }


    /** Private function **/
    private function form_edit($action, $content, $data) {
        $data = (object) $data;

        // Include .js
        $apnd = array('files/base.js', 'files/offers.js', 'files/tinymce/tinymce.min.js');
        $excl = array('autotab', 'dualist', 'elfinder', 'fullcalendar', 'flot', 'maskedinput', 'upload');
        $scripts = CMS::scripts($apnd, NULL, $excl);

        // Set header params
        $header_data = array(
            'style' =>  array('styles.css', 'autoSuggest.css'),
            'scripts' => $scripts,
            'ie' => 'ie.css',
            'styles' => 'jquery.Jcrop.min.css',
        );

        $wrapper_data = array(
            'page' => array(
                'name' => ($action == 'add') ? 'Offer Creator' : 'Offer Editor',
                'subnav' => View::forge('cms/offer/menu', array('offer' => $data->offer)),
                'icon' => 'icon-cart'),

            'crumbs' => array(
                array('title'=>'Dashboard', 'link'=>Uri::create('dashboard')),
                array('title'=>'Offers', 'link' => Uri::create('dashboard/offers')),
                array('title'=>($action == 'edit') ? $data->offer->name : ucwords($action), 'link' => '#')
            ),

            'me' => $this->user_login->user,

            'message' => $this->msg,

            'content' => $content,
                
            'company' => $this->get_current_company(),
        );
        // Compile view
        $header = View::forge('base/header', $header_data);
        $cont = View::forge('cms/wrapper', $wrapper_data);
        $footer = View::forge('base/footer');
        $temp = $header . $cont . $footer;

        return Response::forge($temp);
    }

    private function offer_add($content_data) {
        $this->err = null;

        $data = array();

        // process the data
        $post = $this->process_form(Input::post());
        $files = $this->process_files();
        
        if ($this->err) {
            $this->msg = $this->err;

            $content_data['offer'] = (object) $post;
            $content = View::forge('cms/offer/edit', $content_data);
            return $this->form_edit('add', $content, $content_data);

        } else {
          foreach($post as $field => $val) { $data[$field] = $val; }
          $data['description'] = trim($data['description']);
          $data['show_dates'] = (int)(isset($post['show_dates']) && $post['show_dates']);

          foreach($files as $index => $file) {
            // Send files encoded on base64 along with the rest of the params
            // TODO: This is a workaround for the limitations to send files and form fields
            // using PUT method. Definitely needs more research and another solution
            $file['content'] = base64_encode(file_get_contents($file['file']));
            $data["gallery_add[$index]"] = $file;
          }
          
          $url = Uri::create('api/offer/');

          $this->api->setData($data);
          $this->api->setMethod('POST');
          $this->api->setURL($url);

          $output = $this->api->execute();
          
          if ($output) {
            if ($output->meta->status == 1) { // Success
              $offer = $output->data->offer;
              
              if (Input::post('reward_for', null) != null) {
                  $grand_prize = Input::post('grand_prize', '0');
                  $data = array('contest_id' => Input::post('reward_for'), 'grand_prize' => $grand_prize);
                  $url = Uri::create('api/offer/' . $offer->id . '/assign_to_contest');
                  $this->api->setData($data);
                  $this->api->setMethod('POST');
                  $this->api->setURL($url);
                  $this->api->execute();
              }
              
              $msg = array('type' => 'success', 'message' => 'Successfully added offer ', 'autohide' => true);
              Session::set('message', $msg);
              return Response::redirect("dashboard/offers/");
            } else { // Fail
              $this->msg = array('type' => 'fail', 'message' => $output->meta->error);
              return $this->form_edit('add', $this->_generate_error_form($content_data, $post), $content_data);
            }
          } else {
            $this->msg = array('type' => 'fail', 'message' => 'Error: 900 - Unable to process your request');
            return $this->form_edit('add', $this->_generate_error_form($content_data, $post), $content_data);
          }
      	}
    }
    
    public function action_force_top_message() {
        $offer_id = $this->param('id');
        if (!$offer_id || !($offer = Model_Offer::find($offer_id))) {
            return $this->error_404();
        }
        
        $msg = array('type' => 'success', 'message' => 'Offer will be shown at the top of the message list', 'autohide' => true);
        $offer->force_top_message = true;
        if (Input::get('remove')) {
            $msg = array('type' => 'success', 'message' => 'Offer will no longer be shown at the top of the message list', 'autohide' => true);
            $offer->force_top_message = false;
        }
        
        $offer->save();
        
        Session::set('message', $msg);
        return Response::redirect("dashboard/offers/");
    }

    private function offer_update($content_data) {
        $this->err = null;

        $data = array();

        // process the data
        $post = $this->process_form(Input::post());
        $files = $this->process_files();

        if ($this->err) {
            $this->msg = array('type' => 'fail', 'message' => $this->err);
            
            $content_data['offer'] = (object) $post;
            $content = View::forge('cms/offer/edit', $content_data);
            return $this->form_edit('edit', $content, $content_data);
        } else {
            $offer_id = $content_data['offer']->id;

            foreach($post as $field => $val) { $data[$field] = $val; }
            $data['description'] = trim($data['description']);
            $data['show_dates'] = (int)(isset($post['show_dates']) && $post['show_dates']);
            
            foreach($files as $index => $file) {
            	// Send files encoded on base64 along with the rest of the params
            	// TODO: This is a workaround for the limitations to send files and form fields
            	// using PUT method. Definitely needs more research and another solution
            	$file['content'] = base64_encode(file_get_contents($file['file']));
            	$data["gallery_add[$index]"] = $file;
            }

            $url = Uri::create('api/offer/'.$offer_id);

            $this->api->setData($data);
            $this->api->setMethod('PUT');
            $this->api->setURL($url);

            $output = $this->api->execute();

            if ($output) {
                if ($output->meta->status == 1) { // Success
                    
                    if (Input::post('reward_for', null) != null) {
                        $grand_prize = Input::post('grand_prize', '0');
                        $data = array('contest_id' => Input::post('reward_for'), 'grand_prize' => $grand_prize);
                        $url = Uri::create('api/offer/' . $offer_id . '/assign_to_contest');
                        $this->api->setData($data);
                        $this->api->setMethod('POST');
                        $this->api->setURL($url);
                        $this->api->execute();
                    }
                    
                    if (isset($data['status']) && $data['status'] == 0) {
                        $txt = 'Successfully deleted offer ';
                    } else if (Input::post('duplicateOffer', 0)) {
                        $txt = 'Successfully updated and currently editing duplicated offer ';
                    } else {
                        $txt = 'Successfully updated offer ';
                    }
                    $msg = array('type' => 'success', 'message' => $txt, 'autohide' => true);
                    Session::set('message', $msg);
                    if (! Input::post('duplicateOffer', 0)) {
                        return Response::redirect("dashboard/offers/");
                    } else {
                        return Response::redirect("dashboard/offer/".$offer_id."/add");
                    }
                } else { // Fail
                    $msg = array('type' => 'fail', 'message' => $output->meta->error);
                    $this->msg = $msg;

                    // Set content params
                    $data['id'] = $offer_id;
//                     $content_data['offer'] = (object) $data;
//                     $content = View::forge('cms/offer/edit', $content_data);
                    return $this->form_edit('edit', $this->_generate_error_form($content_data, $data), $content_data);
                }

            } else {
                $msg = array('type' => 'fail', 'message' => 'Error: 900 - Unable to process your request');
                $this->msg = $msg;

                // Set content params
                $data['id'] = $offer_id;
//                 $content_data['offer'] = (object) $data;
//                 $content = View::forge('cms/offer/edit', $content_data);
                return $this->form_edit('edit', $this->_generate_error_form($content_data, $data), $content_data);
            }
        }
    }

    private function process_form($data) {

			// Date - time start
			$data['date_start'] = implode(' ', $data['date_start']);
			$data['date_start'] = strtotime($data['date_start']);
	
			// Date - time end
			$data['date_end'] = implode(' ', $data['date_end']);
			$data['date_end'] = strtotime($data['date_end']);
	
			$data['multiple_codes'] = isset($data['multiple_codes']) && $data['multiple_codes'];  
			$data['redeemable'] = isset($data['redeemable']) && $data['redeemable'];  
			return $data;
    }

    private function _generate_error_form(&$content_data, $post_data) {
        $content_data['offer'] = (object) $post_data;
        $content_data['offer_code'] = $post_data['offer_code'];
        $content_data['offer_code_type'] = $post_data['offer_code_type'];
        $content_data['ean13_code'] = Model_Offer_Code::get_random_code(Model_Offer_Code::EAN13_TYPE);
        $content_data['qr_code'] = Model_Offer_Code::get_random_code(Model_Offer_Code::QR_CODE_TYPE);
        $content_data['code_128'] = Model_Offer_Code::get_random_code(Model_Offer_Code::CODE128_TYPE);
        
        return View::forge('cms/offer/edit', $content_data);
    }
}
