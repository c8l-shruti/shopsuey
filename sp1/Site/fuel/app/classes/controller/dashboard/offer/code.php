<?php

/**
 * The offer Controller.
 * This controllers the CRUD proceedures for the CMS offers codes section
 *
 * @package  app
 * @extends  Controller_Cms
 */

class Controller_Dashboard_Offer_Code extends Controller_Cms {

	function action_index() {

		// Include .js
		$apnd = array('files/base.js', 'files/offers.js');
		$excl = array('autotab', 'cleditor', 'dualist', 'elfinder', 'fullcalendar', 'flot', 'maskedinput', 'upload', 'wizards');
		$scripts = CMS::scripts($apnd, NULL, $excl);
		
		// Set header params
		$header_data = array(
				'style' => 'styles.css',
				'scripts' => $scripts,
				'ie' => 'ie.css'
		);

		$offer_id = $this->param('offer_id');
		if (!$offer_id || !($offer = CMS::get_offer($offer_id))) {
			return $this->error_404();
		}

		// Set content params
		$content_data = array(
				'me' => $this->user_login->user,
				'offer' => $offer,
				'codes' => $offer->offer_codes,
				'title' => $offer->name
		);
		
		$content = View::forge('cms/offer/code/list', $content_data);
		
		$wrapper_data = array(
				'page' => array(
						'name' => 'Offer Codes Management',
						'subnav' => View::forge('cms/offer/code/menu', array('offer' => $offer)),
						'icon' => 'icon-cart'),
		
				'crumbs' => array(
						array('title'=>'Dashboard', 'link'=>Uri::create('dashboard')),
						array('title'=>'Offers', 'link' => Uri::create('dashboard/offers'))
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

	function action_save() {
		$offer_id = $this->param('offer_id');
		if (!$offer_id || !($offer = CMS::get_offer($offer_id))) {
			return $this->error_404();
		}

		$offer = Model_Offer::find($this->param('id'));
		$offer_code_id = Input::param('offer_code_id');
		if (!empty($offer_code_id)) {
			$offer_code = new Model_Offer_Code();
			$offer_code->offer = $offer;
			$offer_code->auto_generated = FALSE;
			$offer_code->status = 1;
		} else {
			$offer_code = Model_Offer_Code::find($offer_code_id);
		}
		$offer_code->from_array(Input::post());

		try {
			$offer_code->save();
		} catch (Orm\ValidationFailed $e) {
			$this->msg = array('type' => 'fail', 'message' => join(' / ', $e->get_fieldset()->error()));
			return $this->_form_edit($offer, $offer_code);
		}

		$msg = array('type' => 'success', 'message' => 'The offer code was successfully saved');
		Session::set('message', $msg);
		return Response::redirect("dashboard/offer/{$offer->id}/codes");
	}

	function action_add() {
		$offer_id = $this->param('offer_id');
		if (!$offer_id || !($offer = CMS::get_offer($offer_id))) {
			return $this->error_404();
		}

		$action = Uri::create("dashboard/offer/$offer_id/code/add");
		
		if (Input::method() == 'POST') {
			$params = Input::post();
			
			$url = Uri::create("api/offer/$offer_id/code");
			
			$this->api->setData($params);
			$this->api->setMethod('POST');
			$this->api->setURL($url);
			
			$output = $this->api->execute();

			if ($output && $output->meta->status == '1') {
				$msg = array('type' => 'success', 'message' => 'Successfully added offer code', 'autohide' => true);
				Session::set('message', $msg);
				return Response::redirect("dashboard/offer/$offer_id/codes");
			} else {
				$this->msg = array('type' => 'fail', 'message' => $output->meta->error);
				return $this->_form_edit($action, $offer, (object)$params);
			}
		} else {
			return $this->_form_edit($action, $offer);
		}
	}

	function action_edit() {
		$id = $this->param('id');
		if (!$id || !($offer_code = CMS::get_offer_code($id))) {
			return $this->error_404();
		}
		$offer = $offer_code->offer;
		
		$action = Uri::create("dashboard/offer/code/$id/edit");
		
		if (Input::method() == 'POST') {
			$params = Input::post();
				
			$url = Uri::create("api/offer/code/$id");
				
			$this->api->setData($params);
			$this->api->setMethod('PUT');
			$this->api->setURL($url);
				
			$output = $this->api->execute();
		
			if ($output && $output->meta->status == '1') {
				$msg = array('type' => 'success', 'message' => 'Successfully updated offer code', 'autohide' => true);
				Session::set('message', $msg);
				return Response::redirect("dashboard/offer/{$offer_code->offer_id}/codes");
			} else {
				$this->msg = array('type' => 'fail', 'message' => $output->meta->error);
				return $this->_form_edit($action, $offer, (object)$params);
			}
		} else {
			return $this->_form_edit($action, $offer, $offer_code);
		}
	}
	
	private function _form_edit($action, $offer, $offer_code = null) {
		// Include .js
		$apnd = array('files/base.js', 'files/offers.js');
		$excl = array('autotab', 'cleditor', 'dualist', 'elfinder', 'fullcalendar', 'flot', 'maskedinput', 'upload', 'wizards');
		$scripts = CMS::scripts($apnd, NULL, $excl);
		
		// Set header params
		$header_data = array(
				'style' => 'styles.css',
				'scripts' => $scripts,
				'ie' => 'ie.css'
		);

		// Set content params
		$content_data = array(
				'action' => $action,
				'me' => $this->user_login->user,
				'offer' => $offer,
				'offer_code' => is_null($offer_code) ? new stdClass() : $offer_code,
				'title' => $offer->name
		);
		
		$content = View::forge('cms/offer/code/edit', $content_data);
		
		$wrapper_data = array(
				'page' => array(
						'name' => 'Offer Codes Management',
						'subnav' => View::forge('cms/offer/code/menu', array('offer' => $offer)),
						'icon' => 'icon-cart'),
		
				'crumbs' => array(
						array('title'=>'Dashboard', 'link'=>Uri::create('dashboard')),
						array('title'=>'Offers', 'link' => Uri::create('dashboard/offers')),
						array('title'=> $offer->name, 'link' => Uri::create("dashboard/offer/{$offer->id}/codes")),
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

	
}