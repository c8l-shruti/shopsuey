<?php

/**
 * The event Controller.
 * This controllers the CRUD proceedures for the CMS events section
 *
 * @package  app
 * @extends  Controller_Cms
 */

class Controller_Dashboard_specialevent extends Controller_Cms {
    private $err = null;

    public function action_add($action = 'add') {
        $content = '';
        $event = null;
        $output = null;

        if ($action == 'edit') {
            $eventid = Request::active()->param('id');
            if ($eventid) {
                $event = $this->event_get($eventid);
                if (!$event) { return Response::redirect('dashboard/events'); }
            }
            else { return $this->error_404(); }
        } else {
            $event = new Model_Specialevent();
        }
        
        // Set content params
        $content_data = array(
            'action' => $action,
            'event' => $event,
            'me' => $this->user_login->user,
            'output' => $output,
            'login_hash' => $this->user_login->login_hash,
        );

        if (Input::post()) {
            return $this->event_add_or_update($content_data, $event);
        } else {
            $content = View::forge('cms/specialevent/edit', $content_data);
            return $this->form_edit($action, $content, $content_data);
        }

    }

    public function action_edit() {
        return $this->action_add('edit');
    }

    /** Private function **/
    private function form_edit($action, $content, $data) {
        $data = (object) $data;

        // Include .js
        $apnd = array('files/base.js', 'files/events.js');
        $excl = array('autotab', 'dualist', 'cleditor', 'elfinder', 'fullcalendar', 'flot', 'maskedinput', 'upload');
        $scripts = CMS::scripts($apnd, NULL, $excl);

        // Set header params
        $header_data = array(
            'style' => array('styles.css', 'autoSuggest.css'),
            'scripts' => $scripts,
            'ie' => 'ie.css',
            'styles' => 'jquery.Jcrop.min.css',
        );

        $wrapper_data = array(
            'page' => array(
                'name' => ($action == 'add') ? 'Special Event Creator' : 'Special Event Editor',
                'subnav' => View::forge('cms/event/menu', array('event' => $data->event)),
                'icon' => 'icon-calender'),

            'crumbs' => array(
                array('title'=>'Dashboard', 'link'=>Uri::create('dashboard')),
                array('title'=>'Events', 'link' => Uri::create('dashboard/events')),
                array('title'=>($action == 'edit') ? $data->event->title : ucwords($action), 'link' => '#')
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
    
    public function action_force_top_message() {
        $event_id = $this->param('id');
        if (!$event_id || !($event = Model_Specialevent::find($event_id))) {
            return $this->error_404();
        }
        
        $msg = array('type' => 'success', 'message' => 'Event will be shown at the top of the message list', 'autohide' => true);
        $event->force_top_message = true;
        if (Input::get('remove')) {
            $msg = array('type' => 'success', 'message' => 'Event will no longer be shown at the top of the message list', 'autohide' => true);
            $event->force_top_message = false;
        }
        
        $event->save();
        
        Session::set('message', $msg);
        return Response::redirect("dashboard/events/");
    }

    private function event_add_or_update($content_data, $event) {
        $this->err = null;

        // process the data
        $post = $this->process_form(Input::post());
        $files = $this->process_files();
        
        foreach ($files as $file) {
            $file['content'] = base64_encode(file_get_contents($file['file']));

            if ($file['field'] == 'logo') {
                $post['logo'] = Helper_Images_Specialevents_Logos::copy_one_image_from_params($file, $post);
            } elseif ($file['field'] == 'landing') {
                $post['landing_screen_img'] = Helper_Images_Specialevents_Landing::copy_one_image_from_params($file, $post);
            }
        }
        
        if ($this->err) {
            Session::set('message', $this->err);

            $content_data['event'] = (object) $post;
            $content = View::forge('cms/specialevent/edit', $content_data);
            return $this->form_edit('add', $content, $content_data);
        } else {
            try {
                $properties = array(
                    'title',
                    'description',
                    'logo',
                    'landing_screen_img',
                    'main_location_id',
                    'coordinator_phone',
                    'coordinator_email',
                    'website',
                    'show_dates',
                    'date_start',
                    'date_end',
                    'status',
                    'tags',
                    'social',
                    'force_top_message',
                );
                
                $post['show_dates'] = (int)(isset($post['show_dates']) && $post['show_dates']);
                
                foreach ($properties as $property) {
                    $event->$property = $post[$property];
                }
                $event->description = trim($event->description);

                $locations = array();
                foreach ($post['location_ids'] as $loc_id) {
                    $locations[] = Model_Location::find($loc_id);
                }
                
                if (count($locations) == 0) {
                    throw new Exception('No locations were selected for the event');
                }

                $event->locations = $locations;
                
                if (isset($post['status'])) {
                    $event->status = $post['status'];
                } else {
                    $event->status = 1;
                }
                
                if (!$event->id) {
                    // new event, we have to specify who created it
                    $event->created_by = $this->user_login->user;
                }
                
                $event->edited_by = $this->user_login->user;
                $event->save();

                if ($event->status == 0) {
                	$txt = 'Successfully deleted special event';
                } else {
                	$txt = 'Special event information successfully saved';
                }

                $msg = array('type' => 'success', 'message' => $txt, 'autohide' => true);
                Session::set('message', $msg);
                return Response::redirect('dashboard/events');

            } catch(Exception $e) {
                $msg = array('type' => 'fail', 'message' => 'Unable to process your request: ' . $e->getMessage());
                Session::set('message', $msg);
                $this->msg = $msg;

                // Set content params
                $content_data['event'] = new Model_Specialevent($post);
                $content = View::forge('cms/specialevent/edit', $content_data);
                return $this->form_edit('add', $content, $content_data);
            }
        }
    }
    
    private function populate_images_from_location($event) {
        $destination_helper = 'Helper_Images_Events';
        $location = $event->main_location;

        if ($location->landing_screen_img) {
            // this location has an image set, let's copy it to the event
            $img_to_copy = $location->landing_screen_img;
            $filename = Helper_Images_Landing::copy_image($img_to_copy, $destination_helper);
            $event->landing_screen_img = $filename;
        }

        if ($location->logo) {
            $img_to_copy = $location->logo;
            $filename = Helper_Images_Logos::copy_image($img_to_copy, $destination_helper);
            $event->logo = $filename;
        } 
    }

    private function event_get($eventid) {
        $event = Model_Specialevent::find($eventid);
        return $event;
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