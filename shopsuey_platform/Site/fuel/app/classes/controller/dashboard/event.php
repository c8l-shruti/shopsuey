<?php

/**
 * The event Controller.
 * This controllers the CRUD proceedures for the CMS events section
 *
 * @package  app
 * @extends  Controller_Cms
 */

class Controller_Dashboard_event extends Controller_Cms {
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
            else { return $this->action_404(); }
        } else {
            $event = new Model_Event();
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
            $content = View::forge('cms/event/edit', $content_data);
            return $this->form_edit($action, $content, $content_data);
        }

    }

    public function action_edit() {
        return $this->action_add('edit');
    }

    public function action_index() {

        // Include .js
        $apnd = array('files/base.js', 'files/events.js');
        $excl = array('autotab', 'cleditor', 'dualist', 'elfinder', 'fullcalendar', 'flot', 'maskedinput', 'upload', 'wizards');
        $scripts = CMS::scripts($apnd, NULL, $excl);

        // Set header params
        $header_data = array(
            'style' => array('styles.css', 'autoSuggest.css'),
            'scripts' => $scripts,
            'ie' => 'ie.css'
        );

        $page   = $this->param('page', 1);
        $string = $this->param('string', Input::param('string', ''));
        $default_tz  = 'Pacific/Honolulu'; // @todo Meter en config
        
        $data = array('name' => $string, 'page' => $page);
        
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
            $data['location_id'] = Input::param('location_id', '');
            $url_parts[] = "location_id={$data['location_id']}";
        }
        if (Input::param('include_merchants', '')) {
            $data['include_merchants'] = 1;
        }
        
        $data['order_direction'] = Input::param('order_direction', 'desc');
        $data['order_by'] = Input::param('order_by', 'date_start');
        
        $url_parts[] = "gallery=" . Input::param('gallery', '0');
        
        $this->api->setData($data);
        $this->api->setMethod('GET');
        $this->api->setURL(Uri::create("api/event/search"));
        
        $output = $this->api->execute();
        
        $url = implode('&', $url_parts);
        
        // Set content params
        $content_data = array(
            'me' => $this->user_login->user,
            'events' => array(),
            'pagination' => null,
            'search' => $string,
            'query_params' => $url,
            'order_by' => $data['order_by'],
            'order_direction' => $data['order_direction'],
            'title' => 'events',
            'login_hash' => $this->user_login->login_hash,
            'active' => $active,
            'inactive' => $inactive
		);

        if ($output) {
            $content_data['events'] = $output->data->events;
            $content_data['pagination'] = $output->meta->pagination;
            $label = ($content_data['pagination']->records > 1) ? ' events' : ' event';
            $content_data['title'] = $content_data['pagination']->records;
            $content_data['title'] .= ($string) ? ' results for '.$string : $label;
        }
        
        if (Input::param('gallery', false)) {
            $view_name = 'cms/event/gallery';
        } else {
            $view_name = 'cms/event/list';
        }
        $content = View::forge($view_name, $content_data);

        $wrapper_data = array(
            'page' => array(
                'name'   => 'Event Management',
                'subnav' => View::forge('cms/event/menu', array(
                    'in_event_list'    => true,
                    'active'           => $active,
                    'inactive'         => $inactive,
                    'default_timezone' => $default_tz
                )),
                'icon' => 'icon-calender'),

            'crumbs' => array(
                array('title'=>'Dashboard', 'link'=>Uri::create('dashboard')),
                array('title'=>'Events', 'link' => Uri::create('dashboard/events'))
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
        $apnd = array('files/base.js', 'files/events.js', 'files/tinymce/tinymce.min.js');
        $excl = array('autotab', 'dualist', 'elfinder', 'fullcalendar', 'flot', 'maskedinput', 'upload');
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
                'name' => ($action == 'add') ? 'Event Creator' : 'Event Editor',
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
        if (!$event_id || !($event = Model_Event::find($event_id))) {
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
        
        $files_data = $post;
        foreach ($files as $index => $file) {
            $file['content'] = base64_encode(file_get_contents($file['file']));
            $files_data["gallery_add"][$index] = $file;
        }
        
        $location_ids = \Input::post('location_ids', array());
        $all_locations = Input::post('all_locations', FALSE);
        
        if ($this->err) {
            Session::set('message', $this->err);

            $content_data['event'] = (object) $post;
            $content_data['event']->locations = $this->get_locations($location_ids);
            $content_data['all_locations'] = $all_locations;
            $content = View::forge('cms/event/edit', $content_data);
            return $this->form_edit('add', $content, $content_data);
        }
        else {
            try {
                $properties = array(
                    'title',
                    'description',
                    'coordinator_phone',
                    'coordinator_email',
                    'website',
                    'show_dates',
                    'date_start',
                    'date_end',
                    'code',
                    'tags',
                    'fb_event_id',
                    'foursquare_event_id',
                    'foursquare_venue_id',
                    'force_top_message'
                );
                
                $post['show_dates'] = (int)(isset($post['show_dates']) && $post['show_dates']);
                
                foreach ($properties as $property) {
                    $event->$property = $post[$property];
                }
                // Cleanup description
                $event->description = CMS::strip_tags($event->description);

                $locations = array();

                if ($all_locations) {
                	$locations = $this->user_login->user->get_assigned_companies();
                } elseif (count($location_ids) > 0) {
                    $locations = Model_Location::query()->where('id', 'in', $location_ids)->get();
                    $include_merchants_mall_ids = array();
                    foreach($locations as $location) {
                        if (\Input::post("include_merchants_{$location->id}", FALSE)) {
                            $include_merchants_mall_ids[] = $location->id;
                        }
                    }
                    if (count($include_merchants_mall_ids) > 0) {
                        $additional_merchants = Model_Merchant::query()->where('mall_id', 'in', $include_merchants_mall_ids)->get();
                        $locations = array_merge($locations, $additional_merchants);
                    }
                }
                
                if (count($locations) == 0) {
                    throw new Exception('No locations were selected for the event');
                }

                if (!$this->user_login->user->is_admin()) {
                    if (count(array_diff($location_ids, array_keys($this->user_login->user->get_assigned_companies()))) > 0) {
                        throw new Exception('Invalid location selected for the event');
                    }
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
                    
                    // every image is a new image!
                    $event->gallery = Helper_Images_Events::copy_images_from_params($files_data);
                } else {
                    // the event already existed, let's check the images
                    $new_images = Helper_Images_Events::copy_images_from_params($files_data);
                    
                    // Check if there are images to add
                    if(!empty($new_images)) {
                        $event->gallery = $new_images;
                    } elseif (!isset($event->gallery) || is_null($event->gallery)) {
                        $event->gallery = array();
                    }

                    // Check if there are images to remove
                    if (isset($post['gallery_remove'])) {
                        $remove = is_array($post['gallery_remove']) ? $post['gallery_remove'] : array($post['gallery_remove']);
                        Helper_Images_Events::delete_images($remove);
                        $event->gallery = array();
                    }
                }
                
                $gallery_remove = \Fuel\Core\Input::post('gallery_remove', array());        
                if (empty($gallery_remove) && !is_null(\Fuel\Core\Input::post('deleted_image', null)) && (bool)\Fuel\Core\Input::post('deleted_image')) {
                    $this->populate_images_with_default_image($event);
                }
                
                if (empty($event->gallery)) {
                    $this->populate_images_from_location($event);
                }
                
                $event->edited_by = $this->user_login->user;
                $event->save();

                if ($event->status == 0) {
                	$txt = 'Successfully deleted event';
                } else {
                	$txt = 'Event information successfully saved';
                }

                $msg = array('type' => 'success', 'message' => $txt, 'autohide' => true);
                Session::set('message', $msg);
                return Response::redirect('dashboard/events');

            } catch(Exception $e) {
//                 throw $e;
                $msg = array('type' => 'fail', 'message' => 'Unable to process your request: ' . $e->getMessage());
                Session::set('message', $msg);
                $this->msg = $msg;

                // Set content params
                $content_data['event'] = new Model_Event($post);
                $content_data['event']->locations = $this->get_locations($location_ids);
                $content_data['all_locations'] = $all_locations;
                $content = View::forge('cms/event/edit', $content_data);
                return $this->form_edit('add', $content, $content_data);
            }
        }
    }
    
    private function populate_images_with_default_image($event) {
        $event->gallery = array();
        
        // Copy default image from site assets
        $default_image = 'default-logo.png';
        //Fuel\Core\Config::load('asset'); Somthing strange occurs when I uncomment this line
        
        $image_dir = 'images/'; //Fuel\Core\Config::get('img_dir');
        $asset_dir = 'assets/'; //Fuel\Core\Config::get('paths');
        
        $default_image_path = DOCROOT . $asset_dir . $image_dir . $default_image;

        $image_name = Helper_Images_Events::copy_image_from_path($default_image_path);
        $event->gallery[] = $image_name;
        
        return $image_name;
    }
    
    private function populate_images_from_location($event) {
        $destination_helper = 'Helper_Images_Events';
        foreach ($event->locations as $location) {
            if ($location->landing_screen_img) {
                // this location has an image set, let's copy it to the event
                $img_to_copy = $location->landing_screen_img;
                $filename = Helper_Images_Landing::copy_image($img_to_copy, $destination_helper);
            } elseif ($location->logo) {
                $img_to_copy = $location->logo;
                $filename = Helper_Images_Logos::copy_image($img_to_copy, $destination_helper);
            } else {
                continue;
            }
            $event->gallery[] = $filename;
            break;
        }
    }

    private function event_get($eventid) {
        $event = Model_Event::find($eventid);
        return $event;
    }

    private function get_locations($location_ids) {
        $locations = array();
        if (count($location_ids) > 0) {
            $locations = Model_Location::query()
                ->where('id', 'in', $location_ids)
                ->get();
        }
        return $locations;
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
