<?php

/**
 * The Developer Controller.
 * This controller manages apps created that access the API
 *
 * @package  app
 * @extends  Controller
 */
class Controller_Developer extends Controller_Cms {
	/**
	 * Developer Portal -> Default landing page : /developer
	 */
	public function action_index() {

		// Include .js
		$apnd = array('files/developer.js', 'files/base.js');
		$excl = array('elfinder', 'fullcalendar','wizards', 'flot', 'upload');
		$scripts = CMS::scripts($apnd, NULL, $excl);

		// Set header params
		$header_data = array(
			'style' => 'styles.css',
			'scripts' => $scripts,
			'ie' => 'ie.css'
		);

		// Set content params
		$app_data = array(
			// Apps list
			'items' => DB::select('name', 'token', 'contact', 'created_at', 'updated_at')->from('applications')->execute()->as_array()
		);

		$page_data = array(
			'page' => array('name' => 'Developer Portal', 'subnav' => View::forge('cms/docs/menu')),
			'crumbs' => array(
				array('title'=>'Active apps')),
			'me' => $this->user_login->user,
			'message' => CMS::get_note(),
			'content' => View::forge('cms/developer', $app_data)
		);

		// Compile view
		$header = View::forge('base/header', $header_data);
		$cont = View::forge('cms/wrapper', $page_data);
		$footer = View::forge('base/footer');
		$temp = $header . $cont . $footer;

		return Response::forge($temp);

	}// --> End action_index()


	/**
	 * App Editor -> Create a new app or edit an existing one : /developer/app (/appid)
	 */
	public function action_app($appid = NULL) {

		// Include .js
		$apnd = array('files/appeditor.js', 'files/base.js');
		$incl = Config::get('cms.min_scripts');
		array_push($incl, 'jquery_ui');
		array_push($incl, 'validation');
		array_push($incl, 'tagsinput');
		array_push($incl, 'ibutton');
		$scripts = CMS::scripts($apnd, $incl);

		// Set header params
		$header_data = array(
			'style' => 'styles.css',
			'scripts' => $scripts,
			'ie' => 'ie.css'
		);

		// Set content params
		$note = CMS::get_note();
		$app_data = array('save_title'=> 'Submit Application', 'edit' => FALSE);

		if (isset($note['data'])) { // check for previously submitted form data
			$app = $note['data'];
			$app_data = array('data' => $app, 'save_title' => 'Submit Application', 'edit' => FALSE);
			unset($note['data']);
		}

		elseif (URI::segment(3)) { // check for appid url segment
			$appid = URI::segment(3);
			$app = CMS::get_app($appid);

			if (isset($app['token'])) { // set the form data if appid and page status to edit
				$edit = TRUE;
				$app_data = array('data' => $app, 'save_title' => 'Update Application', 'edit' => TRUE);
			}
		}

		$page_data = array(
			'page' => array('name' => 'Developer Portal',' edit' => @$edit, 'subnav' => View::forge('cms/docs/menu')),
			'crumbs' => array(
				array('title' => 'Active apps', 'link' => Uri::create('developer')),
				array('title' => (isset($edit)) ? @$app['name'] : 'Create an application', 'link' => Uri::create('developer/app'))
			),
			'me' => $this->user_login->user,
			'message' => @$note,
			'content' => View::forge('cms/appeditor', @$app_data)
		);

		// Compile view
		$header = View::forge('base/header', $header_data);
		$cont = View::forge('cms/wrapper', $page_data);
		$footer = View::forge('base/footer');
		$temp = $header . $cont . $footer;

		return Response::forge($temp);

	}// --> End action_app()


	/**
	 * Create a new app function -> redirects to app editor on completion
	 */
	public function action_create() { echo "test";exit;

		// Form data -> post only
		$form = Input::post();
		$form['name'] = trim(strip_tags($form['name']));
		$form['name'] = preg_replace("/[^a-zA-Z0-9\s]/", "", $form['name']);
		$form['name'] = preg_replace('!\s+!', ' ', $form['name']);

		// Check db for app name
		$app = DB::select('id')->from('applications')->where('name', '=', $form['name'])->limit(1)->execute();

		if (isset($app[0])) { // App already exists -> return error note and form data
			CMS::set_note($form['name'] .' already exists', 'error', FALSE, $form);
			return Response::redirect(Uri::create('developer/app'));
		}
		else { // No app -> create it
			$form['contact'] = strip_tags($form['contact']);
			$form['description'] = strip_tags($form['description']);
			$form['slug'] = $form['name'];
			$form['slug'] = preg_replace("/[^a-zA-Z0-9\s]/", "", $form['slug']);
			$form['slug'] = preg_replace('!\s+!', '-', $form['slug']);
			$form['created_at'] = time();
			$uniqid = uniqid();
			$form['secret'] = md5(microtime() . '-' . $uniqid);
			$form['token'] = md5($uniqid);
				
			// Insert the app and get insert id
			list($insert_id, $rows_affected) = DB::insert('applications')->set($form)->execute();

			if ($insert_id > 0 && $rows_affected > 0) { // verify that the insert actually took place
				CMS::set_note($form['name'] . ' has been created!', 'success');
				return Response::redirect(Uri::create('developer/app/').$form['token']);
			}

			else { // no insert -> return error note and form data
				CMS::set_note('Error: unable to create '.$form['name'], 'error', FALSE, $form);
				return Response::redirect(Uri::create('developer/app'));
			}
		}

	}// --> End action_create()


	/**
	 * Update app function -> redirects to app editor on completion
	 */
	public function action_update() {

		// Form data -> post only
		$form = Input::post();
		$id = $form['id'];
		$appname = $form['name'];

		// Append edited and strip description
		$form['updated_at'] = time();
		$form['description'] = strip_tags($form['description']);

		// Remove unnecessary fields from form data
		unset($form['id']);
		unset($form['delete']);
		unset($form['name']);

		// Execute the update
		$upd = DB::update('applications')->set($form)->where('id', '=', $id)->execute();

		// Create note
		if ($upd) { CMS::set_note($appname . ' updated!', 'success'); }
		else { CMS::set_note('Error: unable to update ' . $appname, 'error', FALSE, $form); }

		$app = Model_Application::find($id);
		$appid = $app->token;
		// Redirect to app editor
		return Response::redirect(Uri::create('developer/app').'/'.$appid);

	}// --> End action_update()


	/**
	 * Delete app function -> redirects to developer portal
	 */
	public function action_delete() {

		// Form data -> post only
		$form = Input::post();
		$id = $form['id'];
		$appname = $form['name'];

		// Execute the delete
		$del = DB::delete('applications')->where('id', '=', $id)->execute();

		// Create note
		if ($del) { CMS::set_note($appname . ' deleted!', 'success'); }
		else { CMS::set_note('Error: unable to delete ' . $appname, 'error', FALSE); }

		// Redirect to developer portal
		return Response::redirect(Uri::create('developer'));

	}// --> End action_delete()


	/**
	 * Update app secret key -> redirects to app editor on completion
	 */
	public function action_refresh() {

		// Form data -> post only
		$form = Input::post();
		$id = $form['id'];
		$appname = $form['name'];

		// Append edited and new secret key
		$form['updated_at'] = time();
		$form['secret'] = md5(microtime() . '-' .$id);

		// Remove unnecessay fields from form data
		unset($form['id']);
		unset($form['name']);

		// Execute the update
		$upd = DB::update('applications')->set($form)->where('id', '=', $id)->execute();

		// Create note
		if ($upd) { CMS::set_note($appname . ' secret key updated!', 'info'); }
		else { CMS::set_note('Error: unable to reset '.$appname.' secret key', 'error', FALSE); }

		$app = Model_Application::find($id);
		$appid = $app->token;
		
		// Redirect to app editor
		return Response::redirect(Uri::create('developer/app').'/'.$appid);

	}// --> End action_refresh()


	/**
	 * Documentation
	 */
	public function action_docs() {

		$page = Request::active()->param('page');
		if (!$page) { $page = 'index'; }

		// Include .js
		$apnd = array('files/developer.js', 'files/base.js');
		$excl = array('elfinder', 'fullcalendar','wizards', 'flot', 'upload');
		$scripts = CMS::scripts($apnd, NULL, $excl);

		// Set header params
		$header_data = array(
			'style' => 'styles.css',
			'scripts' => $scripts,
			'ie' => 'ie.css'
		);

		// Set content params
		$app_data = array(
			// Apps list
			'items' => DB::select('name', 'token', 'contact', 'created_at', 'updated_at')->from('applications')->execute()->as_array()
		);

		$page_data = array(
			'page' => array('name' => 'Developer Portal', 'subnav' => View::forge('cms/docs/menu')),
			'crumbs' => array(
				array('title'=>'Documentation')),
			'me' => $this->user_login->user,
			'message' => CMS::get_note(),
			'content' => View::forge('cms/docs/'.$page, $app_data)
		);

		// Compile view
		$header = View::forge('base/header', $header_data);
		$cont = View::forge('cms/wrapper', $page_data);
		$footer = View::forge('base/footer');
		$temp = $header . $cont . $footer;

		return Response::forge($temp);
	}

	/**
	 * App Editor -> Create a new app or edit an existing one : /developer/app (/appid)
	 */
	public function action_test($appid = NULL) {
        $output = null;
		$raw = null;

		$form = (Input::post('method')) ? Input::post() : array();
		$form = (object) $form;

		if (@$form->method) {
			$key = @$form->access_key;
			$data = @$form->param;
			$url = @$form->endpoint;
			$method = @$form->method;

			if ($method && $url) {
				$this->api = new Restful();
				$this->api->setAppid(Config::get('cms.appid'));

				if ($key) { $this->api->setLoginHash($key); }
				if ($data) { $this->api->setData($data); }

				$this->api->setMethod($method);
				$this->api->setURL($url);

				$output = $this->api->execute();

				if (!$output) {
					$raw = $this->api->raw;
				}
			}
		}

        // Set content params
        $content_data = array(
            'me' => $this->user_login->user,
						'form' => $form,
						'raw' => $raw,
            'output' => $output,
        		'login_hash' => $this->user_login->login_hash,
				);

        $content = View::forge('cms/docs/apitest', $content_data);
        return $this->form_test($content, $content_data);

	}// --> End action_app()

    /** Private function **/
    private function form_test($content, $data) {
        $data = (object) $data;

        // Include .js
        $apnd = array('files/base.js', 'files/apitest.js');
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
                'name' => 'API Test Tool',
                'subnav' => View::forge('cms/docs/menu'),
                'icon' => 'icon-lab'),

            'crumbs' => array(
                array('title'=>'Developer Portal', 'link' => Uri::create('developer')),
                array('title'=>'API Test Tool', 'link' => '#')
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


}








// EOF