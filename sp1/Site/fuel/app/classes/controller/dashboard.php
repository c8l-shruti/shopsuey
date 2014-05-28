<?php

/**
 * The Dashboard Controller.
 *
 * The Main CMS page
 *
 * @package  app
 * @extends  Controller_Cms
 */
class Controller_Dashboard extends Controller_Cms {

	/**
	 * The main dashboard page
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_index() {
        $user = $this->user_login->user;
        if ($user->status == Model_User::STATUS_STEP1 || $user->status == Model_User::STATUS_STEP2 || $user->status == Model_User::STATUS_STEP3) {
            return Response::redirect(Uri::create('welcome'));
        }
        
		// Landing page differs according to user role
		if ($this->user_login->user->is_admin()) {
		    $content = $this->action_agenda();
		} else {
		    $company_id = Input::param('company_id', NULL);
		    if (!is_null($company_id)) {
		        $company = Model_Location::find($company_id);
		        $assigned_locations = $this->user_login->user->get_assigned_companies();
		        if ($company && isset($assigned_locations[$company_id])) {
		            $this->set_current_company($company);
		        }
		    } else {
		        $company = $this->get_current_company();
		    }
		    // TODO: This check seems to be superfluous. Check if users can be created
		    // without a company (merchants and managers)
		    if ($company) {
		        // Check if the user has completed the company setup
		        if ($company->setup_complete) {
		            $content = $this->action_health_metrics();
                } else {
		            return Response::redirect(Uri::create('dashboard/company/edit'));
		        }
		    }
		}
		
		return $content;
	}

	public function action_agenda() {

		// TODO: Why the API is not used in this case?!?!
		// TODO: All events and offers are being fetch, only those
		// from the corresponding merchant/market place should be returned
		// instead!
		$events = Model_Event::query()
			->where('status', 1)
			->where('date_start', '<>', '0000-00-00 00:00:00');

		$offers = Model_Offer::query()
		->where('status', 1)
		->where('date_start', '<>', '0000-00-00 00:00:00');

		$assigned_location_ids = array_keys($this->user_login->user->get_assigned_companies());
		
        if (!$this->user_login->user->is_admin()) {
    		if (count($assigned_location_ids) > 0) {
                $events->related('locations')->where('locations.id', 'in', $assigned_location_ids);
                $offers->related('locations')->where('locations.id', 'in', $assigned_location_ids);
            } else {
                // Make sure the queries return empty sets
                $events->where('id', NULL);
                $offers->where('id', NULL);
            }
        }
        
// 		$nqry = DB::select('id', 'name', 'date_start', 'date_end')
// 		->from('notices')
// 		->where('status', '=', 1)
// 		->and_where('date_start', '!=', '0000-00-00 00:00:00')
// 		->execute();

		$items = array();

		foreach ($events->get() as $event) {
			$item = $event->to_array();
			$item['start'] = $item['date_start'];
			$item['end'] = ($item['date_end']) ? $item['date_end'] : '';
			$item['url'] = Uri::create('dashboard/event/edit/'.$item['id']);
			$item['title'] = $item['title'];
			$item['allDay'] = false;
			// TODO: Fix this, causes repeated ids with offers and notices
			$item['id'] = 'calendar_item_'.$item['id'];
			
			unset($item['date_start'], $item['date_end']);
			
			array_push($items, $item);
		}

		foreach ($offers->get() as $offer) {
			$item = $offer->to_array();
			$item['start'] = $item['date_start'];
			$item['end'] = ($item['date_end']) ? $item['date_end'] : '';
			$item['url'] = Uri::create('dashboard/offer/edit/'.$item['id']);
			$item['title'] = $item['name'];
			$item['allDay'] = false;
			$item['backgroundColor'] = '#999999';
			$item['id'] = 'calendar_item_'.$item['id'];

			unset($item['date_start'], $item['date_end'], $item['name']);

			array_push($items, $item);
		}

// 		foreach($nqry as $item) {
// 			$item['start'] = $item['date_start'];
// 			$item['end'] = ($item['date_end']) ? $item['date_end'] : '';
// 			$item['url'] = Uri::create('dashboard/notice/view/'.$item['id']);
// 			$item['title'] = $item['name'];
// 			$item['allDay'] = 'false';
// 			$item['backgroundColor'] = '#CC0000';
// 			$item['id'] = 'calendar_item_'.$item['id'];

// 			unset($item['date_start'], $item['date_end'], $item['name']);

// 			array_push($items, $item);
// 		}

		// Include .js
		$apnd = array('files/base.js', 'files/dashboard.js');
		$excl = array('elfinder', 'wizards', 'cleditor', 'flot', 'upload');
		$scripts = CMS::scripts($apnd, NULL, $excl);

		// Set header params
		$header_data = array(
			'style' => 'styles.css',
			'scripts' => $scripts,
			'ie' => 'ie.css'
		);

		// Set content params
		$content_data = array(
			'dates' => $items,
			'checkins' => array(),
            'subscribers' => 0,
            'twitterrequests' => 0,
            'user' => $this->user_login->user,
	        'company' => $this->get_current_company(),
		);
        
        $company = $this->get_current_company();
		if (!$this->user_login->user->is_admin() && $company) {
            $content_data['subscribers'] = Model_Subscription::query()->where('location_id', $company->id)->count();
            $content_data['twitterrequests'] = Model_Twitterrequest::query()->where('location_id', $company->id)->count();
        }

		$wrapper_data = array(
			'page' => array('name' => 'Dashboard', 'subnav' => $this->generate_menu()),

			'crumbs' => array(
				array('title'=>'Logged in as '.$this->user_login->user->email)),

			'me' => $this->user_login->user,

			'message' => $this->msg,

			'content' => View::forge('cms/dashboard', $content_data),
		        
	        'company' => $this->get_current_company(),
		);

		// Compile view
		$header = View::forge('base/header', $header_data);
		$cont = View::forge('cms/wrapper', $wrapper_data);
		$footer = View::forge('base/footer');
		$temp = $header . $cont . $footer;

		return Response::forge($temp);
	}
    
	public function action_guide() {
	    // Admin users are not allowed to access this section
	    if ($this->user_login->user->is_admin()) {
	        return Response::redirect(Uri::create('dashboard/agenda'));
	    }
	    
		// Include .js
		$apnd = array('files/base.js');
		$excl = array('elfinder', 'wizards', 'cleditor', 'flot', 'upload');
		$scripts = CMS::scripts($apnd, NULL, $excl);

		// Set header params
		$header_data = array(
			'style' => 'styles.css',
			'scripts' => $scripts,
			'ie' => 'ie.css'
		);

		// Set content params
		$content_data = array(
			'company' => $this->get_current_company(),
            'subscribers' => 0
		);

		$company = $this->get_current_company();
		
        if ($company && !$this->user_login->user->is_admin()) {
            $content_data['subscribers'] = Model_Subscription::query()->where('location_id', $company->id)->count();
        }

		$wrapper_data = array(
			'page' => array('name' => 'Setup Guide', 'subnav' => $this->generate_menu()),

			'crumbs' => array(
				array('title'=>'Logged in as '.$this->user_login->user->email)),

			'me' => $this->user_login->user,

			'message' => $this->msg,

			'content' => View::forge('cms/dashboard/guide', $content_data),
		        
	        'company' => $this->get_current_company(),
		);

		// Compile view
		$header = View::forge('base/header', $header_data);
		$cont = View::forge('cms/wrapper', $wrapper_data);
		$footer = View::forge('base/footer');
		$temp = $header . $cont . $footer;

		return Response::forge($temp);
	}
	
    public function action_subscribers() {
        $csv = Input::get('csv', 0);
        $company = $this->get_current_company();

        if ($this->user_login->user->is_admin() && Input::get('company_id', null)) {
            $company = Model_Location::find(Input::get('company_id'));
        }

        $emails = array();
        if ($company) {
            $subscribers = Model_Subscription::query()->related('user')->where('location_id', $company->id)->get();
            foreach ($subscribers as $subscriber) {
                $emails[] = $subscriber->user->email;
            }
        }

        if ($csv) {
            $headers = array(
                'Content-Disposition' => 'attachment; filename=subscribers.csv'
            );
            return Fuel\Core\Response::forge(implode("\n", $emails), 200, $headers);
        } else {
            // Include .js
            $apnd = array('files/base.js');
            $excl = array('elfinder', 'wizards', 'cleditor', 'flot', 'upload');
            $scripts = CMS::scripts($apnd, NULL, $excl);

            // Set header params
            $header_data = array(
                'style' => 'styles.css',
                'scripts' => $scripts,
                'ie' => 'ie.css'
            );

            // Set content params
            $content_data = array(
                'company' => $company,
                'subscribers' => $emails
            );

            $wrapper_data = array(
                'page' => array('name' => 'Subscribers'),
                'crumbs' => array(
                    array('title' => 'Dashboard', 'link' => Uri::create('dashboard')),
                    array('title' => 'Subscribers')
                 ),
                'me' => $this->user_login->user,
                'content' => View::forge('cms/dashboard/subscribers', $content_data),
                'company' => $this->get_current_company(),
            );

            // Compile view
            $header = View::forge('base/header', $header_data);
            $cont = View::forge('cms/wrapper', $wrapper_data);
            $footer = View::forge('base/footer');
            $temp = $header . $cont . $footer;

            return Response::forge($temp);
        }
    }

	public function action_timezone() {
		$ref = Input::referrer();
		$zone = Input::param('zone');
		Session::set('timezone', $zone);

		return Response::redirect($ref);
	}
    
    public function action_health_metrics() {
        $forced_company_id = Input::get('id', null);
        if ($this->user_login->user->is_admin() && !$forced_company_id) {
            return Response::redirect(Uri::create('dashboard/agenda'));
	    }
        
        if ($this->user_login->user->is_admin() && $forced_company_id) {
            $company = Model_Location::find($forced_company_id);
            if (!$company) {
                return $this->error_404();
            }
        } else {
            $company = $this->get_current_company();
        }
        
//         if (!$company || $company->mall_id) {
//             $msg = array('type' => 'error', 'message' => 'Health metrics is only available for Market Places or Stand Alone Merchants', 'autohide' => true);
//             Session::set('message', $msg);
//             return Response::redirect('dashboard');
//         }

        // Include .js
        $apnd = array('files/base.js');
        $excl = array('elfinder', 'wizards', 'cleditor', 'flot', 'upload');
        $scripts = CMS::scripts($apnd, NULL, $excl);
        
        // Set header params
        $header_data = array(
        		'style' => 'styles.css',
        		'scripts' => $scripts,
        		'ie' => 'ie.css'
        );
        
        $this->api->setMethod('GET');
        $this->api->setURL(Uri::create("api/location/get_current_health_metrics"));
        $this->api->setData(array('location_ids' => $company->id));
        $output = $this->api->execute();
        if (isset($output->data->health_metrics->{$company->id})) {
            $current_health_metric = $output->data->health_metrics->{$company->id};
        } else {
            $current_health_metric = (object)array(
                    'favorites_count' => 0,
                    'offers_count' => 0,
                    'sign_ups_count' => 0,
                    'check_ins_count' => 0,
                    'follows_count' => 0,
                    'events_count' => 0,
                    'likes_count' => 0,
                );
        }
        
        $this->api->setURL(Uri::create("api/location/" . $company->id . "/get_historic_health_metrics"));
        $output = $this->api->execute();
        $historic_health_metric = $output->data->health_metrics;
        
        // Set content params
        $content_data = array(
            'location_id' => $company->id,
            'super_admin' => $this->user_login->user->is_admin(),
            'current' => $current_health_metric,
            'historic' => $historic_health_metric,
            'subscribers' => Model_Subscription::query()->where('location_id', $company->id)->count()
        );
        
        $wrapper_data = array(
            'page' => array(
                'name' => $company->name,
                'subnav' => $this->generate_menu()
            ),
    		'crumbs' => array(
		        array('title'=>'Dashboard', 'link'=>Uri::create('dashboard')),
	        ),
    		'me' => $this->user_login->user,
    		'message' => $this->msg,
    		'content' => View::forge('cms/dashboard/health_metrics', $content_data),
    		'company' => $forced_company_id ? null : $company,
    		'forced_company' => $forced_company_id ? $company : null,
        );
        
        // Compile view
        $header = View::forge('base/header', $header_data);
        $cont = View::forge('cms/wrapper', $wrapper_data);
        $footer = View::forge('base/footer');
        $temp = $header . $cont . $footer;
        
        return Response::forge($temp);
    }
    
    public function action_stores() {
        if ($this->user_login->user->is_admin()) {
	    	return Response::redirect(Uri::create('dashboard/agenda'));
	    }
        
        $company = $this->get_current_company();
        
        if (!$company || $company->mall_id) {
            $msg = array('type' => 'error', 'message' => 'Stores module is only available for Market Places', 'autohide' => true);
            Session::set('message', $msg);
            return Response::redirect('dashboard');
        }

        // Include .js
        $apnd = array('files/base.js');
        $excl = array('elfinder', 'wizards', 'cleditor', 'flot', 'upload');
        $scripts = CMS::scripts($apnd, NULL, $excl);
        
        // Set header params
        $header_data = array(
        		'style' => 'styles.css',
        		'scripts' => $scripts,
        		'ie' => 'ie.css'
        );
        
        $stores = Model_Merchant::query()->where('mall_id', $company->id)->where('status', 1)->order_by('name')->get();
        $stores_ids = array_map(function($store) { return $store->id; }, $stores);
        
        $this->api->setMethod('GET');
        $this->api->setURL(Uri::create("api/location/get_current_health_metrics"));
        $this->api->setData(array('location_ids' => implode(",", array_merge(array($company->id), $stores_ids))));
        $output = $this->api->execute();
        $current_health_metric = $output->data->health_metrics->{$company->id};
        
        $stores_metrics = array();
        foreach ($stores as $store) {
            if (isset($output->data->health_metrics->{$store->id})) {
                $metric = $output->data->health_metrics->{$store->id};
                $stores_metrics[$store->id] = $metric;
            } else {
                $stores_metrics[$store->id] = (object)array(
                    'favorites_count' => 0,
                    'offers_count' => 0,
                    'sign_ups_count' => 0,
                    'check_ins_count' => 0,
                    'follows_count' => 0,
                    'events_count' => 0,
                    'likes_count' => 0,
                );
            }
        }
        
        $this->_apply_sorting($stores, $stores_metrics);
        
        // Set content params
        $content_data = array(
            'stores' => $stores,
            'current' => $current_health_metric,
            'stores_metrics' => $stores_metrics,
            'sortby' => Input::get('sortby', ''),
            'sort' => Input::get('sort', 'desc')
        );
        
        $wrapper_data = array(
    		'page' => array('name' => 'Stores', 'subnav' => $this->generate_menu()),
    		'crumbs' => array(
		        array('title'=>'Dashboard', 'link'=>Uri::create('dashboard')),
	        ),
    		'me' => $this->user_login->user,
    		'message' => $this->msg,
    		'content' => View::forge('cms/dashboard/stores', $content_data),
    		'company' => $company,
        );
        
        // Compile view
        $header = View::forge('base/header', $header_data);
        $cont = View::forge('cms/wrapper', $wrapper_data);
        $footer = View::forge('base/footer');
        $temp = $header . $cont . $footer;
        
        return Response::forge($temp);
    }
    
    protected function _apply_sorting(&$stores, $metrics) {
        if (Input::get('sortby', null)) {
            $property = Input::get('sortby');
            usort($stores, function($a, $b) use ($metrics, $property) {
                $a_value = isset($metrics[$a->id]->$property) ? $metrics[$a->id]->$property : 0;
                $b_value = isset($metrics[$b->id]->$property) ? $metrics[$b->id]->$property : 0;
                if (Input::get('sort', null) == 'asc') {
                    return ($a_value < $b_value) ? -1 : 1;
                }
                return ($a_value < $b_value) ? 1 : -1;
            });
        }
    }
	
	public function action_active_shoppers() {
	    if ($this->user_login->user->is_admin()) {
	    	return Response::redirect(Uri::create('dashboard/agenda'));
	    }

        $company = $this->get_current_company();
        
        if (!$company || $company->mall_id) {
            $msg = array('type' => 'error', 'message' => 'Active Shoppers Map is only available for Market Places or Stand Alone Merchants', 'autohide' => true);
            Session::set('message', $msg);
            return Response::redirect('dashboard');
        }

        // Include .js
        $apnd = array('files/base.js');
        $excl = array('elfinder', 'wizards', 'cleditor', 'flot', 'upload');
        $scripts = CMS::scripts($apnd, NULL, $excl);
        
        // Set header params
        $header_data = array(
        		'style' => 'styles.css',
        		'scripts' => $scripts,
        		'ie' => 'ie.css'
        );
        
        $stores = Model_Merchant::query()
            ->related('micello_info')
            ->where('mall_id', $company->id)
            ->where('status', 1)
            ->order_by('name')
            ->get();

        $micello_stores = array();
        foreach ($stores as $store) {
        	if ($store->micello_info && $store->micello_info->geometry_id) {
        		$micello_store = array(
    				'name' => $store->name,
        		);
        		$micello_stores[$store->micello_info->geometry_id] = $micello_store;
        	}
        }
        
        $start_date_parts = Input::post('start_date', array());
        $end_date_parts = Input::post('end_date', array());
        
        if (count($start_date_parts) > 0) {
            $start_time = strtotime(implode(' ', $start_date_parts));
        } else {
            $start_time = strtotime('-' . \Config::get('cms.nearby_users_time_frame'), time());
        }

        $end_time = count($end_date_parts) > 0 ? strtotime(implode(' ', $end_date_parts)) : time();
        
        \Config::load('micello', true);

        // Set content params
        $content_data = array(
    		'micello_id'      => $company->micello_info ? $company->micello_info->micello_id : '',
    		'company_zipcode' => $company->zip,
            'active_shoppers' => CMS::active_shoppers($company, array(
                'start_time' => $start_time,
                'end_time' => $end_time
                )
            ),
            'micello_api_key' => \Config::get('micello.api_key'),
            'start_time'      => $start_time,
            'end_time'        => $end_time,
            'micello_stores'  => $micello_stores,
        );
        
        $wrapper_data = array(
    		'page' => array('name' => 'Active Shoppers', 'subnav' => $this->generate_menu()),
    		'crumbs' => array(
		        array('title'=>'Dashboard', 'link'=>Uri::create('dashboard')),
	        ),
    		'me' => $this->user_login->user,
    		'message' => $this->msg,
    		'content' => View::forge('cms/dashboard/active_shoppers', $content_data),
    		'company' => $company,
        );
        
        // Compile view
        $header = View::forge('base/header', $header_data);
        $cont = View::forge('cms/wrapper', $wrapper_data);
        $footer = View::forge('base/footer');
        $temp = $header . $cont . $footer;
        
        return Response::forge($temp);
	}
    
	public function action_active_stats() {
		if ($this->user_login->user->is_admin()) {
			return Response::redirect(Uri::create('dashboard/agenda'));
		}
	
		$company = $this->get_current_company();
	
		if (!$company || $company->mall_id) {
			$msg = array('type' => 'error', 'message' => 'Active Stats Map is only available for Market Places or Stand Alone Merchants', 'autohide' => true);
			Session::set('message', $msg);
			return Response::redirect('dashboard');
		}
	
		// Include .js
		$apnd = array('files/base.js');
		$excl = array('elfinder', 'wizards', 'cleditor', 'flot', 'upload');
		$scripts = CMS::scripts($apnd, NULL, $excl);
	
		// Set header params
		$header_data = array(
				'style' => 'styles.css',
				'scripts' => $scripts,
				'ie' => 'ie.css'
		);
	
		$stores = Model_Merchant::query()
		    ->related('micello_info')
		    ->where('mall_id', $company->id)
		    ->where('status', 1)
		    ->order_by('name')
		    ->get();
		$stores_ids = array_map(function($store) { return $store->id; }, $stores);
		
		$this->api->setMethod('GET');
		$this->api->setURL(Uri::create("api/location/get_current_health_metrics"));
		$this->api->setData(array('location_ids' => implode(",", array_merge(array($company->id), $stores_ids))));
		$output = $this->api->execute();
		
		$most_active_count = 0;
		$micello_stores = array();
		foreach ($stores as $store) {
		    if ($store->micello_info && $store->micello_info->geometry_id && isset($output->data->health_metrics->{$store->id})) {
		        $metrics = $output->data->health_metrics->{$store->id};
		        $micello_store = array(
	                'name'           => $store->name,
	                'short_name'     => Str::truncate($store->name, 7, '.'),
	                'metrics'        => $metrics,
	                'activity_count' => $metrics->offers_count + $metrics->events_count,
                );
		        if ($micello_store['activity_count'] > $most_active_count) {
		            $most_active_count = $micello_store['activity_count'];
		        }
		        $micello_stores[$store->micello_info->geometry_id] = $micello_store;
		        
		    }
		}
		
		\Config::load('micello', true);
	
		// Set content params
		$content_data = array(
			'micello_id'        => $company->micello_info ? $company->micello_info->micello_id : '',
	        'micello_stores'    => $micello_stores,
			'micello_api_key'   => \Config::get('micello.api_key'),
	        'most_active_count' => $most_active_count,
		);
	
		$wrapper_data = array(
			'page' => array('name' => 'Active Stats', 'subnav' => $this->generate_menu()),
			'crumbs' => array(
				array('title'=>'Dashboard', 'link'=>Uri::create('dashboard')),
			),
			'me' => $this->user_login->user,
			'message' => $this->msg,
			'content' => View::forge('cms/dashboard/active_stats', $content_data),
			'company' => $company,
		);
	
		// Compile view
		$header = View::forge('base/header', $header_data);
		$cont   = View::forge('cms/wrapper', $wrapper_data);
		$footer = View::forge('base/footer');
		$temp   = $header . $cont . $footer;
	
		return Response::forge($temp);
	}
	
    protected function generate_menu() {
        $current_user = $this->user_login->user;
        if ($current_user->group == Model_User::GROUP_SUPERADMIN) {
            
            $forced_company_id = Input::get('id', null);
            $current_company   = Model_Mall::find($forced_company_id); 
            
            if (!is_null($forced_company_id) && $current_company) {
                $merchants = $current_company->merchants;
                
                $ordered_merchants = array();
                foreach ($merchants as $merchant) {
                    $ordered_merchants[trim($merchant->name)] = $merchant;
                }
                ksort($ordered_merchants);
                $merchants = array_values($ordered_merchants);
                
                $data = array(
                    'count'     => count($merchants),
                    'merchants' => $merchants,
                    'company'   => $current_company
                );
                
                return View::forge('cms/dashboard/superadmin-menu', $data);
            } 
            
            return '';
        }
        
        $company = $this->get_current_company();
        if (!$company || $company->mall_id) {
           return '';
        }
        
        $merchants = Model_Merchant::query()->where('mall_id', $company->id)->where('status', 1)->get();
        $merchants_ids = array_map(function($m) { return $m->id; }, $merchants);
        $total_merchants = count($merchants);
        
        if ($total_merchants) {
            $this->api->setMethod('GET');
            $this->api->setURL(Uri::create("api/location/get_current_health_metrics"));
            $this->api->setData(array('location_ids' => implode(",", $merchants_ids)));
            $output = $this->api->execute();
            
            $active_merchants = 0;
            foreach ($merchants as $merchant) {
                if (isset($output->data->health_metrics->{$merchant->id})) {
                    $metrics = $output->data->health_metrics->{$merchant->id};
                    if ($metrics->offers_count || $metrics->events_count) {
                        $active_merchants++;
                    }
                }
            }
            $active_percentage = round(100 * ($active_merchants / $total_merchants));
        } else {
            $active_percentage = 0;
        }
        
        $active_shoppers_params = array(
            'start_time' => strtotime('-' . \Config::get('cms.nearby_users_time_frame'), time()),
            'end_time'   => time(),
        );
        
        $data = array(
            'number_of_stores' => $total_merchants,
            'active_percentage' => $active_percentage,
            'shoppers' => count(CMS::active_shoppers($company, $active_shoppers_params)),
            'active_shoppers_dates' => $active_shoppers_params,
        );
        return View::forge('cms/dashboard/menu', $data);
    }
}
