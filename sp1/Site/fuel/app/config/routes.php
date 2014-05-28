<?php
return array(
    '_root_'  						=> 'welcome/index',  // The default route
    '_404_'   						=> 'welcome/404',    // The main 404 route

	'api/checkin/:location'			=> 'api/checkin',
    'api/testapn'                   => 'api/testapn/send',
    
    'api/events'                  => 'api/event/list',
    'api/events/count'            => 'api/event/count',
    'api/event/search'            => 'api/event/search',
	'api/event/checkins'          => 'api/event/checkins',
	'api/event/rsvps'             => 'api/event/rsvps',
	'api/event/:id/rsvp'          => 'api/event/rsvp',
	'api/event/:id/checkin'       => 'api/event/checkin',
    'api/event/:id/delete_photos' => 'api/event/delete_photos',
    'api/event/:id'               => 'api/event',
    

	'api/specialevent/:id/rsvp'      => 'api/specialevent/rsvp',
	'api/specialevent/:id/locations' => 'api/specialevent/locations',
	'api/specialevent/:id/offers'    => 'api/specialevent/offers',
	'api/specialevent/:id/events'    => 'api/specialevent/events',
    
    'api/socialinteraction/location/:id/like'    => 'api/socialinteraction/location_like',
    'api/socialinteraction/location/:id/dislike' => 'api/socialinteraction/location_dislike',
    'api/socialinteraction/location/:id/checkin' => 'api/socialinteraction/location_checkin',
    
    'api/flags(/:page)?'     => 'api/flag/list',
    'api/flag/:id/delete'    => 'api/flag/delete',
    'api/flag/:id/details'   => 'api/flag/details',
    'api/flag/:id/invite'    => 'api/flag/invite',
    'api/flag/:id/private'   => 'api/flag/private',
    'api/flag/:id/uninvite'  => 'api/flag/uninvite',
    'api/flag/:id/vote'      => 'api/flag/vote',
    'api/flag/:id'           => 'api/flag',

    'api/locations(/:page)?(/:string)?'            => 'api/location/list',
    'api/location/search'                          => 'api/location/search',
    'api/location/micello_community'               => 'api/location/micello_community',
    'api/location/micello_entity'                  => 'api/location/micello_entity',
    'api/location/foursquare_venues'               => 'api/location/foursquare_venues',
    'api/location/foursquare_hours'                => 'api/location/foursquare_hours',
    'api/location/yelp_businesses'                 => 'api/location/yelp_businesses',
    'api/location/position_supported'              => 'api/location/position_supported',
    'api/location/maps_validity'                   => 'api/location/maps_validity',
    'api/location/get_current_health_metrics'      => 'api/location/get_current_health_metrics',
    'api/location/businesses_search'               => 'api/location/businesses_search',
    'api/location/:id/map'                         => 'api/location/map',
    'api/location/:id/points'                      => 'api/location/points',
    'api/location/:id/block'                       => 'api/location/block',
    'api/location/:id/unblock'                     => 'api/location/unblock',
    'api/location/:id/favorite'                    => 'api/location/favorite',
    'api/location/:id/merchants_at_mall'           => 'api/location/merchants_at_mall', // deprecated!
    'api/location/:id/merchants'                   => 'api/location/merchants',
    'api/location/:id/request'                     => 'api/location/request',
    'api/location/:id/requested'                   => 'api/location/requested',
    'api/location/:id/update_images'               => 'api/location/update_images',
    'api/location/:id/active_shoppers'             => 'api/location/active_shoppers',
    'api/location/:id/get_historic_health_metrics' => 'api/location/get_historic_health_metrics',
    'api/location/:id/instagram_feed'              => 'api/location/instagram_feed',
    'api/location/:id'                             => 'api/location',

    'api/malls(/:page)?(/:string)?'   => 'api/mall/list',
    'api/mall/:id/merchants_count'    => 'api/mall/merchants_count',
    'api/mall/:id/update_merchants'   => 'api/mall/update_merchants',
    'api/mall/:id/delete_photo/:type' => 'api/mall/delete_photo',
    'api/mall/:id'                    => 'api/mall',

    'api/merchants(/:page)?(/:string)?'   => 'api/merchant/list',
    'api/merchant/logos'                  => 'api/merchant/logos',
    'api/merchant/images'                 => 'api/merchant/images',
    'api/merchant/:id/delete_photo/:type' => 'api/merchant/delete_photo',
    'api/merchant/:id'                    => 'api/merchant',

    'api/indoor/:id/points'         => 'api/indoor/points',
    
    'api/messages' 					=> 'api/message/list',
    'api/message/search' 			=> 'api/message/search',
    'api/message/:id/malls' 		=> 'api/message/malls',
    'api/message/:id' 				=> 'api/message',

    'api/notices' 					=> 'api/notice/list',
    'api/notice/search' 			=> 'api/notice/search',
    'api/notice/:id/malls' 			=> 'api/notice/malls',
    'api/notice/:id' 				=> 'api/notice',

    'api/offer/:offer_id/codes'     => 'api/offer/code/list',
    'api/offer/code/:id'            => 'api/offer/code',
    'api/offer/:offer_id/code'      => 'api/offer/code',
		
    'api/offers'                      => 'api/offer/list',
    'api/offer/count'     			  => 'api/offer/count',
    'api/offer/bookmarks' 			  => 'api/offer/bookmarks',
    'api/offer/:id/bookmark'          => 'api/offer/bookmark',
    'api/offer/:id/redeem'            => 'api/offer/redeem',
    'api/offer/:id/delete_photos'     => 'api/offer/delete_photos',
    'api/offer/redeem/:id'            => 'api/offer/redeem',
    'api/offer/save'                  => 'api/offer/save',
    'api/offer/:id/assign_to_contest' => 'api/offer/assign_to_contest',
	'api/offer/:id'                   => 'api/offer',

    'api/preferences'                 => 'api/preferences',
    
    'api/promocode/check'             => 'api/promocode/check_promo_code',
    
    'api/proximity'                   => 'api/proximity/get',
    'api/messages'                    => 'api/proximity/get', // alias
    'api/proximity/like'              => 'api/proximity/set_like_status',
    'api/proximity/get_likes'         => 'api/proximity/get_likes',

    'api/retailers'                   => 'api/retailer/list',
    'api/retailer/search'             => 'api/mall/search',
    'api/retailer/:id/malls'          => 'api/retailer/malls',
    'api/retailer/:id'                => 'api/retailer',
    
    'api/subscription'              => 'api/subscription/add',
    
    'api/users/list(/:page)?'        => 'api/user/users_list',
    'api/users(/:page)?(/:string)?'  => 'api/user/list',
    'api/user/facebook'              => 'api/user/fblogin',
    'api/user/anonymous'             => 'api/user/anonymous_login',
    'api/user/reset'                 => 'api/user/reset',
    'api/user/forgot'                => 'api/user/forgot',
    'api/user/image_upload'          => 'api/user/image_upload',
    'api/user/image_delete'          => 'api/user/image_delete',
    'api/user/change_password'       => 'api/user/change_password',
    'api/user/get_profiling_choices' => 'api/user/get_profiling_choices',
    'api/user/update_profiling'      => 'api/user/update_profiling',
    'api/user/get_profiling'         => 'api/user/get_profiling',
    'api/user/set_apn_token'         => 'api/user/set_apn_token',
    'api/user/follow'                => 'api/user/follow',
    'api/user/unfollow'              => 'api/user/unfollow',
    'api/user/:id/following'         => 'api/user/following',
    'api/user/:id/followers'         => 'api/user/followers',
    'api/user/:id/votes'             => 'api/user/get_votes',
    'api/user/:id/favorites'         => 'api/user/get_favorites',
    'api/user/:id/details'           => 'api/user/get_details',
    'api/user/:id/offers'            => 'api/user/get_offers',
    'api/user/:id/events'            => 'api/user/get_events',
    'api/user/:id'                   => 'api/user',

    'api/categories'                 => 'api/category/list',

    'api/countries'                  => 'api/country/list',
    
    'api/session/start'              => 'api/session/start',
    'api/session/end'                => 'api/session/end',
    'api/session/get_total_time'     => 'api/session/get_total_time',
    
    'api/contests'                   => 'api/contest/list',
    'api/contests/:id/rewards'       => 'api/contest/rewards',
        
    'dashboard/events(/:page)?(/:string)?'         => 'dashboard/event',
    'dashboard/event/:id/edit'                     => 'dashboard/event/edit',
    'dashboard/event/:id/force_top_message'        => 'dashboard/event/force_top_message',
    'dashboard/specialevent/:id/edit'              => 'dashboard/specialevent/edit',
    'dashboard/specialevent/:id/force_top_message' => 'dashboard/specialevent/force_top_message',

    'dashboard/locations(/:page)?(/:string)?' => 'dashboard/location',
    'dashboard/location/:id/edit'             => 'dashboard/location/edit',

    'dashboard/offer/:offer_id/codes'    => 'dashboard/offer/code',
    'dashboard/offer/:offer_id/code/add' => 'dashboard/offer/code/add',
    'dashboard/offer/code/:id/edit'      => 'dashboard/offer/code/edit',
		
    'dashboard/offers(/:page)?(/:string)?'  => 'dashboard/offer',
    'dashboard/offer/:id/edit'              => 'dashboard/offer/edit',
    'dashboard/offer/:id/add'               => 'dashboard/offer/add',
    'dashboard/offer/:id/force_top_message' => 'dashboard/offer/force_top_message',

    'dashboard/offer/import'    =>  'dashboard/offer/import',
    'api/offers/doImport/:provider'        =>  'api/offer/doImport',
    
    'dashboard/messages/:page'                  => 'dashboard/message/list',
    'dashboard/messages'                        => 'dashboard/message/list',
    'dashboard/message/edit/:id'                => 'dashboard/message/edit',
    'dashboard/message/search/:f'               => 'dashboard/message/list',
    'dashboard/message/search'                  => 'dashboard/message/list',

    'dashboard/users(/:page)?(/:string)?' => 'dashboard/user',
    'dashboard/user/:id/edit'             => 'dashboard/user/edit',
    'dashboard/user/:id/settings'         => 'dashboard/user/settings',

    'dashboard/profilingchoices'              => 'dashboard/profilingchoices',
    'dashboard/profilingchoices/update_order' => 'dashboard/profilingchoices/update_order',
    'dashboard/profilingchoices/:id/edit'     => 'dashboard/profilingchoices/edit',
    'dashboard/profilingchoices/:id/delete'   => 'dashboard/profilingchoices/delete',
    
    'api/offersevents/search/(:from_favorites)?(/:from_nearby)?(/:from_location)?(/:include_merchants)?(/:latitude)?(/:longitude)?(/:keyword)?(/:page)?' => 'api/offersevents/search',
    'api/offersevents/count/(:from_location)?(/:include_merchants)?(/:latitude)?(/:longitude)?' => 'api/offersevents/count',
    
    'admin/malls(/:page)?(/:string)?' => 'admin/mall',
    'admin/mall/:id/micello_import'   => 'admin/mall/micello_import',
    'admin/mall/:id/update_merchants' => 'admin/mall/update_merchants',
    'admin/mall/:id/edit'             => 'admin/mall/edit',
    'admin/mall/:id/delete'           => 'admin/mall/delete',
		
    'admin/merchants(/:page)?(/:string)?' => 'admin/merchant',
    'admin/merchant/:id/edit'             => 'admin/merchant/edit',
    'admin/merchant/:id/delete'           => 'admin/merchant/delete',
    
    'admin/promos/winner/:id/:offer_id'           => 'admin/promos/winner',
    'admin/promos/doFindWinner/:id/:offer_id'     => 'admin/promos/doFindWinner',
    'admin/promos/doSetWinner/:id/:offer_id/:user_id'     => 'admin/promos/doSetWinner',
    'admin/promos/getWinner/:id/:offer_id'     => 'admin/promos/getWinner',
    
    'admin/promos'             => 'admin/promos',
    'admin/promos/add'         => 'admin/promos/add',
    'admin/promos/:id/edit'    => 'admin/promos/edit',
    'admin/promos/:id/disable' => 'admin/promos/disable',
    'admin/promos/:id/enable'  => 'admin/promos/enable',
    'admin/promos/:id'         => 'admin/promos',

    'admin/notices/:page'                     	=> 'admin/notice/list',
    'admin/notices' 							=> 'admin/notice/list',
    'admin/notice/search/:f'                  	=> 'admin/notice/list',
    'admin/notice/search'                     	=> 'admin/notice/list',
    'admin/notice/edit/:id'                   	=> 'admin/notice/edit',
	'dashboard/notice/view/:id'					=> 'admin/notice/view',
	'dashboard/notices/:page'					=> 'admin/notice/list',
	'dashboard/notices'							=> 'admin/notice/list',

    'developer/docs/:page'						=> 'developer/docs',
    'auth/force_upgrade'						=> 'auth/force_upgrade',
    
    
    'externalservices/address_information'      => 'externalservices/address_information',
);
