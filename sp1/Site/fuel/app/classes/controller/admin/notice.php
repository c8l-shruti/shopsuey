<?php

/**
 * The notice Controller.
 * This controllers the CRUD proceedures for the Admin notices section
 *
 * @package  app
 * @extends  Controller_Admin
 */

class Controller_Admin_notice extends Controller_Admin {
    private $err = null;
    private $json_fields = array();

    public function action_add($action = 'add') {
        $content = '';
        $notice = null;
        $output = null;

        if ($action == 'edit') {
            $noticeid = Request::active()->param('id');
            if ($noticeid) {
                $notice = $this->notice_get($noticeid);
                if (!$notice) { return Response::redirect('admin/notices'); }
            }
            else { return $this->action_404(); }
        }

        // Set content params
        $content_data = array(
            'action' => $action,
            'notice' => $notice,
            'me' => $this->me,
            'output' => $output);

        if (Input::post()) {
            if ($action == 'add') { return $this->notice_add($content_data); }
            if ($action == 'edit') { return $this->notice_update($content_data); }
        }
        else {
            $content = View::forge('cms/admin/notices/edit', $content_data);
            return $this->form_edit($action, $content, $content_data);
        }

    }

    public function action_edit() {
        return $this->action_add('edit');
    }

    public function action_index() { $this->action_list(); }

    public function action_list() {

        // Include .js
        $apnd = array('files/base.js', 'files/notices.js');
        $excl = array('autotab', 'cleditor', 'dualist', 'elfinder', 'fullcalendar', 'flot', 'maskedinput', 'upload', 'wizards');
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

        $url = Uri::create('api/notices');

        $output = null;

        $this->api->setMethod('GET');
        $this->api->setURL($url);

        $output = $this->api->execute();

        //print_r($this->api->raw);

        // Set content params
        $content_data = array(
            'me' => $this->me,
            'notices' => array(),
            'pagination' => null,
            'search' => $search,
            'title' => 'notices');

        if ($output) {
            $content_data['notices'] = $output->data->notices;
            $content_data['pagination'] = $output->meta->pagination;
            $label = ($content_data['pagination']->records > 1) ? ' notices' : ' notice';
            $content_data['title'] = $content_data['pagination']->records;
            $content_data['title'] .= ($search) ? ' results for '.$search : $label;
        }

        $content = View::forge('cms/admin/notices/list', $content_data);

        $wrapper_data = array(
            'page' => array(
                'name' => 'Notice Management',
                'subnav' => View::forge('cms/admin/notices/menu'),
                'icon' => 'icon-microphone'),

            'crumbs' => array(
                array('title'=>'Admin', 'link'=>Uri::create('dashboard')),
                array('title'=>'Notices', 'link' => Uri::create('admin/notices'))
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

	public function action_view() {

        // Include .js
        $apnd = array('files/base.js');
        $excl = array('autotab', 'cleditor', 'dualist', 'elfinder', 'fullcalendar', 'flot', 'maskedinput', 'upload', 'wizards');
        $scripts = CMS::scripts($apnd, NULL, $excl);

        // Set header params
        $header_data = array(
            'style' => 'styles.css',
            'scripts' => $scripts,
            'ie' => 'ie.css'
        );

        $id = Request::active()->param('id', 1);
		$notice = $this->notice_get($id);

        // Set content params
        $content_data = array(
            'me' => $this->me,
            'notice' => $notice,
            'title' => 'notice');

        $content = View::forge('cms/admin/notices/view', $content_data);

        $wrapper_data = array(
            'page' => array(
                'name' => $notice->name,
                'subnav' => View::forge('cms/admin/notices/menu'),
                'icon' => 'icon-microphone'),

            'crumbs' => array(
                array('title'=>'Dashboard', 'link'=>Uri::create('dashboard')),
                array('title'=>'Notices', 'link' => Uri::create('dashboard/notices')),
				array('title'=>$notice->name)
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
    private function form_edit($action, $content, $data) {
        $data = (object) $data;

        // Include .js
        $apnd = array('files/base.js', 'files/notices.js');
        $excl = array('autotab', 'dualist', 'elfinder', 'fullcalendar', 'flot', 'maskedinput', 'upload');
        $scripts = CMS::scripts($apnd, NULL, $excl);

        // Set header params
        $header_data = array(
            'style' => 'styles.css',
            'scripts' => $scripts,
            'ie' => 'ie.css'
        );

        $wrapper_data = array(
            'page' => array(
                'name' => ($action == 'add') ? 'Notice Creator' : 'Notice Editor',
                'subnav' => View::forge('cms/admin/notices/menu'),
                'icon' => 'icon-microphone'),

            'crumbs' => array(
                array('title'=>'Dashboard', 'link'=>Uri::create('dashboard')),
				array('title'=>'Admin', 'link'=>Uri::create('admin')),
                array('title'=>'Notices', 'link' => Uri::create('admin/notices')),
                array('title'=>($action == 'edit') ? $data->notice->name : ucwords($action), 'link' => '#')
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

    private function notice_add($content_data) {
        $this->err = null;

        $data = array();

        // process the data
        $post = $this->process_form(Input::post());

        if ($this->err) {
            Session::set('message', $this->err);

            $content_data['notice'] = (object) $post;
            $content = View::forge('cms/admin/notices/edit', $content_data);
            return $this->form_edit('add', $content, $content_data);
        }
        else {

            foreach($post as $field => $val) { $data[$field] = $val; }
            $data['created_by'] = $this->me->id;

            $url = Uri::create('api/notice/');

            $this->api->setData($data);
            $this->api->setMethod('POST');
            $this->api->setURL($url);

            $output = $this->api->execute();

            //return Response::forge($this->api->raw);

            if ($output) {
                if ($output->meta->status == 1) { // Success
                    $notice = $output->data;
                    $msg = array('type' => 'success', 'message' => 'Successfully added notice ', 'autohide' => true);
                    Session::set('message', $msg);
                    return Response::redirect('admin/notice/edit/'.$notice->id);
                }
                else { // Fail
                    $msg = array('type' => 'fail', 'message' => $output->meta->error);
                    Session::set('message', $msg);

                    // Set content params
                    $content_data['notice'] = (object) $post;
                    $content = View::forge('cms/admin/notices/edit', $content_data);
                    return $this->form_edit('add', $content, $content_data);
                }
            }
            else {
                $msg = array('type' => 'fail', 'message' => 'Error: 900 - Unable to process your request');
                Session::set('message', $msg);

                // Set content params
                $content_data['notice'] = (object) $post;
                $content = View::forge('cms/admin/notices/edit', $content_data);
                return $this->form_edit('add', $content, $content_data);
            }
        }
    }

    private function notice_get($noticeid) {
        $notice = $this->notice_get_by('id', $noticeid);
        return $notice;
    }

    private function notice_get_by($field, $value, $type = 'object') {
        $qry = DB::select()->from('notices')->where($field, '=', $value)->and_where('status', '>', 0)->limit(1)->execute();

        $json_fld = $this->json_fields;

        if (isset($qry[0])) {
            $notice = $qry[0];

            foreach($json_fld as $fld) {
                $notice[$fld] = json_decode(stripcslashes($notice[$fld]));
            }

            if ($type == 'object') { $notice = (object) $notice; }
            return $notice;
        }
        return;
    }

    private function notice_update($content_data) {
        $this->err = null;

        $data = array();

        // process the data
        $post = $this->process_form(Input::post());

        if ($this->err) {
            Session::set('message', $this->err);

            $content_data['notice'] = (object) $post;
            $content = View::forge('cms/admin/notices/edit', $content_data);
            return $this->form_edit('edit', $content, $content_data);
        }

        else {
            $noticeid = Request::active()->param('id');
            foreach($post as $field => $val) { $data[$field] = $val; }

            $data['edited_by'] = $this->me->id;
            $data['id'] = $noticeid;

            $url = Uri::create('api/notice/'.$noticeid);

            $this->api->setData($data);
            $this->api->setMethod('PUT');
            $this->api->setURL($url);

            $output = $this->api->execute();

			Session::set('data', stripslashes(json_encode($data)));

            //return Response::forge(json_encode($data));

            if ($output) {
                if ($output->meta->status == 1) { // Success
                    $notice = $output->data;
                    $msg = array('type' => 'success', 'message' => 'Successfully updated notice ', 'autohide' => true);
                    Session::set('message', $msg);

                    return Response::redirect('admin/notice/edit/'.$notice->id);
                }
                else { // Fail
                    $msg = array('type' => 'fail', 'message' => $output->meta->error);
                    Session::set('message', $msg);

                    // Set content params
                    $data['id'] = $noticeid;
                    $content_data['notice'] = (object) $data;
                    $content = View::forge('cms/admin/notices/edit', $content_data);
                    return $this->form_edit('edit', $content, $content_data);
                }

            }
            else {
                // Error test
                print_r($this->api->raw);

                $msg = array('type' => 'fail', 'message' => 'Error: 900 - Unable to process your request');
                Session::set('message', $msg);

                // Set content params
                $data['id'] = $noticeid;
                $content_data['notice'] = (object) $data;
                $content = View::forge('cms/admin/notices/edit', $content_data);
                return $this->form_edit('edit', $content, $content_data);
            }
        }
    }

    private function process_form($data) {

		// Date - time start
		$data['date_start'] = ($data['date_start']) ? date('Y-m-d H:i:s', strtotime($data['date_start'])) : '';

		// Date - time end
		$data['date_end'] = ($data['date_end']) ? date('Y-m-d H:i:s', strtotime($data['date_end'])) : '';

        return $data;
    }


























// Class dismissed!
}