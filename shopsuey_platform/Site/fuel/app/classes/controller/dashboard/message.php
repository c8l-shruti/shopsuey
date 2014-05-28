<?php

/**
 * The Message Controller.
 * This controllers the CRUD proceedures for the CMS messages section 
 * 
 * @package  app
 * @extends  Controller_Dashboard
 */
 
class Controller_Dashboard_message extends Controller_Dashboard {
    private $err = null;
    private $json_fields = array('sender_meta', 'trigger_meta', 'filter_demographic', 'filter_proximity', 'filter_behavior', 'filter_frequency');
    
    public function action_add($action = 'add') {
        $content = '';
        $message = null;
        $output = null;
        
        if ($action == 'edit') {
            $messageid = Request::active()->param('id');
            if ($messageid) {
                $message = $this->message_get($messageid);
                if (!$message) { return Response::redirect('dashboard/messages'); }
            }
            else { return $this->action_404(); }
        }
        
        // Set content params
        $content_data = array(
            'action' => $action,
            'message' => $message,
            'me' => $this->me,
            'behaviors' => $this->behaviors(),
            'output' => $output);
        
        if (Input::post()) {
            if ($action == 'add') { return $this->message_add($content_data); }
            if ($action == 'edit') { return $this->message_update($content_data); }
        }
        else {
            $content = View::forge('cms/message/edit', $content_data);
            return $this->form_edit($action, $content, $content_data);
        }
        
    }
    
    public function action_edit() {
        return $this->action_add('edit');
    }
    
    public function action_index() { $this->action_list(); }
        
    public function action_list() {
        
        // Include .js
        $apnd = array('files/base.js', 'files/messages.js');
        $excl = array('autotab', 'cleditor', 'dualist', 'elfinder', 'fullcalendar', 'flot', 'maskedinput', 'tagsinput', 'upload', 'wizards');
        $scripts = CMS::scripts($apnd, NULL, $excl);
         
        // Set header params
        $header_data = array(
            'style' => 'styles.css',
            'scripts' => $scripts,
            'ie' => 'ie.css'
        );
        
        $page = Request::active()->param('page', 1);
        $search = Request::active()->param('f', Input::get('f'));
        $search = (strlen($search) > 0) ? $search : null;
        
        $url = Uri::create('api/messages');
        
        $output = null;
        
        $this->api->setMethod('GET');
        $this->api->setURL($url);
        
        $output = $this->api->execute();
        
        //print_r($this->api->raw);
        
        // Set content params
        $content_data = array(
            'me' => $this->me,
            'messages' => array(),
            'pagination' => null,
            'search' => $search,
            'title' => 'Messages');
        
        if ($output) {
            $content_data['messages'] = $output->data->messages;
            $content_data['pagination'] = $output->meta->pagination;
            $label = ($content_data['pagination']->records > 1) ? ' Messages' : ' Message';
            $content_data['title'] = $content_data['pagination']->records;
            $content_data['title'] .= ($search) ? ' results for '.$search : $label;
        }
        
        $content = View::forge('cms/message/list', $content_data);		
        
        $wrapper_data = array(
            'page' => array(
                'name' => 'Message Management',
                'subnav' => View::forge('cms/message/menu'),
                'icon' => 'icon-comments-2'),
            
            'crumbs' => array(
                array('title'=>'Dashboard', 'link'=>Uri::create('dashboard')),
                array('title'=>'Messages', 'link' => Uri::create('dashboard/messages'))
            ),
            
            'me' => $this->me,
            
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
    
    
    
    /** Private function **/
    private function behaviors() {
        return array(
            'subscribe' => "Subscribes to email list",
            'checkin' => "Checks into Facebook or Foursquare",
            'follow' => "Twitter follow or Facebook like",
            'share' => "Shares an offer via social media",
            'favorite' => "Favorites the retailer",
            'redeem' => "Redeems an offer",
            'rsvp' => "RSVP to an event",
            'attend' => "Attends an event",
            'park' => "Sets a parking pin",
            'meet' => "Sets a meeting spot"
        );
    }
    
    
    private function form_edit($action, $content, $data) {
        $data = (object) $data;
        
        // Include .js
        $apnd = array('files/base.js', 'files/messages.js');
        $excl = array('autotab', 'dualist', 'cleditor', 'elfinder', 'fullcalendar', 'flot', 'maskedinput', 'upload');
        $scripts = CMS::scripts($apnd, NULL, $excl);
        
        // Set header params
        $header_data = array(
            'style' => 'styles.css',
            'scripts' => $scripts,
            'ie' => 'ie.css'
        );
        
        $wrapper_data = array(
            'page' => array(
                'name' => ($action == 'add') ? 'Message Creator' : 'Message Editor',
                'subnav' => View::forge('cms/message/menu'),
                'icon' => 'icon-comments'),
            
            'crumbs' => array(
                array('title'=>'Dashboard', 'link'=>Uri::create('dashboard')),
                array('title'=>'Messages', 'link' => Uri::create('dashboard/messages')),
                array('title'=>($action == 'edit') ? 'Edit message '.$data->message->id : ucwords($action), 'link' => '#')
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
    
    
    private function message_add($content_data) {
        $this->err = null;
        
        $data = array();
        
        // process the data
        $post = $this->process_form(Input::post());

        if ($this->err) {
            Session::set('message', $this->err);
            
            $content_data['message'] = (object) $post;											
            $content = View::forge('cms/message/edit', $content_data);
            return $this->form_edit('add', $content, $content_data);
        }
        
        else {
            
            foreach($post as $field => $val) { $data[$field] = $val; }
            $data['created_by'] = $this->me->id;
            
            $url = Uri::create('api/message/');
            
            $this->api->setData($data);
            $this->api->setMethod('POST');
            $this->api->setURL($url);
            
            $output = $this->api->execute();
            
            //eturn Response::forge($this->api->raw);
            
            if ($output) { 
                if ($output->meta->status == 1) { // Success
                    $message = $output->data;
                    $msg = array('type' => 'success', 'message' => 'Successfully added message ', 'autohide' => true); 
                    Session::set('message', $msg);
                    return Response::redirect('dashboard/message/edit/'.$message->id);
                }
                else { // Fail
                    $msg = array('type' => 'fail', 'message' => $output->meta->error); 
                    Session::set('message', $msg);
                    
                    // Set content params
                    $content_data['message'] = (object) $post;											
                    $content = View::forge('cms/message/edit', $content_data);
                    return $this->form_edit('add', $content, $content_data);
                }
            }
            else {
                $msg = array('type' => 'fail', 'message' => 'Error: 900 - Unable to process your request'); 
                Session::set('message', $msg);
                
                // Set content params
                $content_data['message'] = (object) $post;											
                $content = View::forge('cms/message/edit', $content_data);
                return $this->form_edit('add', $content, $content_data);
            }
        }
    }
    
    private function message_get($messageid) {
        $message = $this->message_get_by('id', $messageid);
        return $message;
    }
    
    private function message_get_by($field, $value, $type = 'object') {
        $qry = DB::select()->from('messages')->where($field, '=', $value)->and_where('status', '>', 0)->limit(1)->execute();
        
        $json_fld = $this->json_fields;
        
        if (isset($qry[0])) {
            $message = $qry[0];
            
            foreach($json_fld as $fld) {
                $message[$fld] = json_decode(stripcslashes($message[$fld])); 
            }
            
            if ($type == 'object') { $message = (object) $message; }
            return $message;
        }
        return;
    }
    
    private function message_update($content_data) {
        $this->err = null;
        
        $data = array();
        
        // process the data
        $post = $this->process_form(Input::post());

        if ($this->err) {
            Session::set('message', $this->err);
            
            $content_data['message'] = (object) $post;											
            $content = View::forge('cms/message/edit', $content_data);
            return $this->form_edit('edit', $content, $content_data);
        }
        
        else {
            $messageid = Request::active()->param('id');
            foreach($post as $field => $val) { $data[$field] = $val; }
            
            $data['edited_by'] = $this->me->id;
            $data['id'] = $messageid;
            
            $url = Uri::create('api/message/'.$messageid);
            
            $this->api->setData($data);
            $this->api->setMethod('PUT');
            $this->api->setURL($url);
            
            $output = $this->api->execute();
            
            //return Response::forge(json_encode($data));
        
            if ($output) { 
                if ($output->meta->status == 1) { // Success
                    $message = $output->data;
                    $msg = array('type' => 'success', 'message' => 'Successfully updated message ', 'autohide' => true); 
                    Session::set('message', $msg);
                    
                    return Response::redirect('dashboard/message/edit/'.$message->id);
                }
                else { // Fail					
                    $msg = array('type' => 'fail', 'message' => $output->meta->error); 
                    Session::set('message', $msg);
                    
                    // Set content params
                    $data['id'] = $messageid;
                    $content_data['message'] = (object) $data;											
                    $content = View::forge('cms/message/edit', $content_data);
                    return $this->form_edit('edit', $content, $content_data);
                }
                
            }
            else {
                // Error test
                print_r($this->api->raw);
                
                $msg = array('type' => 'fail', 'message' => 'Error: 900 - Unable to process your request'); 
                Session::set('message', $msg);
                
                // Set content params
                $data['id'] = $messageid;
                $content_data['message'] = (object) $data;											
                $content = View::forge('cms/message/edit', $content_data);
                return $this->form_edit('edit', $content, $content_data);
            }
        }
    }
    
    private function process_form($data) {
        
        // content
        $data['content'] = strip_tags(@$data['content']);
        $data['content'] = str_replace(array("\r\n", "\r", "\n"), "<br />", @$data['content']);
        if (!$data['content']) { $this->err = "Content is a required field"; return $data; }
        
        // sender_type & sender_meta
        if (isset($data['sender_type'])) {
            switch($data['sender_type']) {
                case 'mall':
                    $data['sender_meta'] = array('mall' => (int) @$data['sender_type_mall_id']);
                    break;
                
                case 'retailer':
                    $data['sender_meta'] = array('retailer' => (int) @$data['sender_type_retailer_id']);
                    break;
                
                case 'location':
                    $data['sender_meta'] = array('mall' => (int) @$data['sender_type_location_mall_id'],
                                                'retailer' => (int) @$data['sender_type_location_retailer_id'],
                                                'location' => (int) @$data['sender_type_location_id']);
                    break;
            }
        }
        
        // action_type & action_meta
        if (isset($data['action_type'])) {
            $data['action_meta'] = strip_tags(@$data['action_type_'.@$data['action_type']]);
        }
        
        // trigger type
        if (isset($data['trigger_type'])) {
            
            // Effective dates
            if (isset($data['repeat_date']) && $data['trigger_type'] != 'datetime' && $data['trigger_type'] != 'manual') {
                sort($data['repeat_date']);
                
                if (isset($data['repeat_date'][0])) { $data['date_start'] = date('Y-m-d h:i:s', strtotime($data['repeat_date'][0])); }
                if (isset($data['repeat_date'][1])) { $data['date_end'] = date('Y-m-d h:i:s', strtotime($data['repeat_date'][1])); }
            }
            else {
                $data['date_start'] = '';
                $data['date_end'] = '';
            }
            
            switch($data['trigger_type']) {
                case 'datetime':
                    $data['trigger_meta'] = array('date' => @$data['trigger_type_datetime_date'],
                                                  'time' => @$data['trigger_type_datetime_time']);
                    break;
                
                case 'proximity':
                    $data['trigger_meta'] = array('proximity' => (int) @$data['trigger_type_proximity']);
                    break;
                
                case 'behavior':
                    $data['trigger_meta'] = array('behavior' => @$data['trigger_type_behavior']);
                    break;
                
                case 'repeat':
                    
                    $repeat_type = @$data['repeat_type'];
                    $repeat = array('type' => $repeat_type);
                    
                    if ($repeat_type) {
                        if ($repeat_type == 'daily') {
                            $repeat['time'] = @$data['repeat_type_daily_time'];
                        }
                        
                        if ($repeat_type == 'weekly') {
                            $repeat['days'] = @$data['repeat_type_weekly_days'];
                            $repeat['time'] = @$data['repeat_type_weekly_time'];
                        }
                        
                        if ($repeat_type == 'monthly') {
                            $repeat['day'] = @$data['repeat_type_monthly_day'];
                            $repeat['time'] = @$data['repeat_type_monthly_time'];
                        }
                    }
                    
                    $data['trigger_meta'] = array('repeat' => $repeat);
            }
        }
        
        return $data;
    }


























// Class dismissed!    
}