<?php

return array(

	/**
	 * Groups as id => array(name => <string>, roles => <array>)
	 */
	'groups' => array(
		 Model_User::GROUP_GUEST      => array('name' => 'Guest', 'roles' => array('guest')),
		 Model_User::GROUP_USER       => array('name' => 'User', 'roles' => array('user', 'anonymous')),
		 Model_User::GROUP_ANONYMOUS  => array('name' => 'Anonymous', 'roles' => array('anonymous')),
		 Model_User::GROUP_MERCHANT   => array('name' => 'Merchant', 'roles' => array('user', 'anonymous', 'merchant')),
		 Model_User::GROUP_MANAGER    => array('name' => 'Manager', 'roles' => array('user', 'anonymous', 'manager', 'merchant')),
		 Model_User::GROUP_ADMIN      => array('name' => 'Administrator', 'roles' => array('user', 'anonymous', 'manager', 'merchant', 'admin')),
		 Model_User::GROUP_SUPERADMIN => array('name' => 'Super Admin', 'roles' => array('user', 'anonymous', 'manager', 'merchant', 'admin', 'superadmin'))
	),

	/**
	 * Roles as name => array(resource => rights)
	 */
	'roles' => array(
		'guest' => array(
			'api/user'         => array('create', 'fblogin', 'anonymous_login', 'forgot', 'reset'),
			'api/auth'         => array('create', 'force_upgrade'),
			'welcome'          => array('index'),
			'login'            => array('index', 'reset', 'forgot', 'create'),
            'api/promocode'    => array('check_promo_code'),
		    'setup/profile'    => array('signup', 'ajax_create'),
			'api/category'     => array('list'),
                    'api/offers_events'              => array('search', 'count'),
		),
		'user' => array(
			'api/user'              => array('read', 'update', 'image_upload', 'image_delete', 'change_password', 'update_profiling', 'get_profiling_choices', 'get_profiling', 'set_apn_token', 'follow', 'unfollow', 'following', 'followers', 'get_votes', 'get_favorites', 'get_details', 'get_offers', 'get_events', 'users_list'),
			'api/preferences'       => array('read', 'create'),
		    'login'                 => array('reset', 'create'),
            'api/subscription'      => array('add'),
		    'api/location'          => array('businesses_search'),
            'api/flag'              => array('create', 'list', 'vote', 'private', 'invite', 'uninvite', 'details', 'delete', 'update'),
            'api/socialinteraction' => array('location_like', 'location_dislike', 'location_checkin'),
		    'setup/profile'         => array('businesses', 'signup', 'ajax_create', 'ajax_businesses'),
		    'welcome'               => array('index'),
            'logout'                => array('index'),
            'dashboard'             => array('index'),
	        'dashboard/user'        => array('instagram_code'),
            'externalservices'      => array('address_information'),
		    'api/outdoor'           => array('suggestions', 'around'),
		    'api/indoor'            => array('points'),
			'api/category'          => array('list'),
                    
            'api/offersevents'              => array('search', 'count'),
                    
		),
		'anonymous'	=> array(
			'api/auth'         => array('delete', 'force_upgrade'),
			'api/events'       => array('index'),
		
                        'api/offer'        => array('bookmarks', 'bookmark', 'read', 'list', 'count', 'save', 'assign_to_contest', 'doImport'),
                    
			'api/offer/redeem' => array('create', 'read'),
			'api/event'        => array('rsvps', 'rsvp', 'read', 'search', 'list', 'count'),
			'api/specialevent' => array('rsvp', 'offers', 'locations', 'events'),
            'api/proximity'    => array('index', 'get', 'set_like_status', 'get_likes'),
			'api/location'     => array('read', 'search', 'block', 'unblock', 'blocked', 'map', 'points', 'favorite', 'merchants', 'merchants_at_mall', 'request', 'requested', 'position_supported', 'maps_validity', 'get_current_health_metrics', 'get_historic_health_metrics', 'instagram_feed'),
	        'api/mall'         => array('merchants_count', 'read'),
            'api/socialnetworkrequest' => array('twitter'),
		    'api/session'      => array('start', 'end', 'get_total_time'),
            'api/testapn'      => array('index', 'send'),
		    'api/contest'      => array('list', 'rewards'),
		    'api/merchant'     => array('read'),
		),
		'merchant' => array(
			'api/user'             => array('read', 'update'),
			'welcome'              => array('index', '404'),
			'dashboard'            => array('index', 'timezone', 'subscribers', 'guide', 'agenda', 'active_shoppers', 'health_metrics', 'stores', 'active_stats'),
			'dashboard/profile'    => array('edit', 'billing', 'updatecc'),
			'dashboard/company'    => array('edit', 'images', 'image_upload', 'image_crop'),
	        'dashboard/offer'      => array('index', 'edit', 'add', 'import'),
// 			'dashboard/offer/code' => array('index', 'edit', 'add'),
			'dashboard/event'      => array('index', 'edit', 'add'),
			'api/offer'            => array('create', 'read', 'update', 'list', 'delete_photos'),
			'api/offer/code'       => array('create', 'read', 'update'),
			'api/location'         => array('list', 'read', 'update', 'foursquare_venues', 'foursquare_hours', 'yelp_businesses', 'update_images', 'active_shoppers', 'businesses_search'),
			'api/event'            => array('create', 'read', 'update', 'search', 'delete_photos'),
			'api/country'          => array('list'),
		    'login'                => array('index'),
	        'setup/profile'        => array('content', 'payment', 'ajax_content', 'ajax_payment'),
            'api/promocode'        => array('check_promo_code'),
                    'api/offersevents'              => array('search', 'count'),
		),
		'manager' => array(
                    'api/offersevents'              => array('search', 'count'),
		),
                'admin' => array(
			'dashboard/user'             => array('index', 'edit', 'add'),
			'api/user'                   => array('create', 'read', 'update', 'delete', 'list'),
            'dashboard/specialevent'     => array('index', 'edit', 'add'),
            'dashboard/profilingchoices' => array('index', 'edit', 'add'),
                    'api/offersevents'              => array('search', 'count'),
		),
		'superadmin' => array(
			'admin'                  => array('index'),
			'admin/mall'             => array('index', 'edit', 'add', 'delete', 'micello_import', 'update_merchants'),
			'admin/merchant'         => array('index', 'edit', 'add', 'delete'),
            'admin/promos'           => array('index', 'listrewards', 'add', 'enable', 'disable', 'edit', 'winner', 'doFindWinner', 'doSetWinner', 'getWinner'),
			'developer'              => array('docs', 'index', 'app', 'create', 'refresh', 'update', 'delete', 'test'),
			'api/mall'               => array('create', 'read', 'update', 'list', 'update_merchants', 'delete_photo'),
			'api/merchant'           => array('create', 'read', 'update', 'list', 'logos', 'images', 'delete_photo'),
			'api/location'           => array('micello_community', 'micello_entity'),
            'dashboard/offer'        => array('force_top_message'),
            'dashboard/event'        => array('force_top_message'),
            'dashboard/specialevent' => array('force_top_message'),
            'dashboard/profilingchoices' => array('index', 'edit', 'add', 'update_order'),
                    'api/offersevents'              => array('search', 'count'),
		),
	),

	/**
	 * Salt for the login hash
	 */
	'login_hash_salt' => 'ba99a55d22236db7ab692f7c31f7b5de',
		
	/**
	 * Expiracy time of the sessions (in strtotime relative format)
	 */
	'expiracy_time' => '+1 week',
);
