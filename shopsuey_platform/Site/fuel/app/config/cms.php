<?php
/**
 *
 * @package    Fuel
 * @version    1.0
 * @author     Cam
 * @license    MIT License
 * @copyright  2010 - 2012 Fuel Development Team
 * @link       http://fuelphp.com
 */

return array(
	'appid' => 'c81e728d9d4c2f636f067f89cc14862c',

	'landing_page' => 'dashboard',

	'title' => 'ShopSuey | CMS',

	'min_scripts' => array(
		'jquery',
		'jquery_ui',
		'jquery_mousewheel',
		'uniform',
		'bootstrap',
		'ibutton'),

	'salt' => md5('gs2'),

	'user_meta_fields' => array(
	    'remember', 'real_name', 'dob', 'gender', 'phone', 'zipcode', 'udid', 
	    'fbuid', 'image', 'fbimage', 'language', 'location', 'use_default_image',
	    'role', 'name_of_business'),
	'json_meta_fields' => array('dob'),
		
	'scripts' => array(
		'jquery' => 'jquery.js',
		'jquery.infinitescroll.min' => 'jquery.infinitescroll.min.js',
		'search' => 'search.js',
		'spinner' => 'plugins/forms/ui.spinner.js',
		'jquery_mousewheel' => 'plugins/forms/jquery.mousewheel.js',
		'jquery_ui' => 'jquery-ui.js',
		'flot' => array('plugins/charts/excanvas.min.js',
			'plugins/charts/jquery.flot.js',
			'plugins/charts/jquery.flot.orderBars.js',
			'plugins/charts/jquery.flot.pie.js',
			'plugins/charts/jquery.flot.resize.js',
			'plugins/charts/jquery.sparkline.min.js'),
		'dataTables' => array('plugins/tables/jquery.dataTables.js',
			'plugins/tables/jquery.sortable.js',
			'plugins/tables/jquery.resizable.js'),
		'autogrowtextarea' => 'plugins/forms/autogrowtextarea.js',
		'uniform' => 'plugins/forms/jquery.uniform.js',
		'inputlimiter' => 'plugins/forms/jquery.inputlimiter.min.js',
		'tagsinput' => 'plugins/forms/jquery.tagsinput.min.js',
		'maskedinput' => 'plugins/forms/jquery.maskedinput.min.js',
		'autotab' => 'plugins/forms/jquery.autotab.js',
		'chosen' => 'plugins/forms/jquery.chosen.min.js',
		'autoSuggest' => 'plugins/forms/jquery.autoSuggest.js',
		'dualist' => 'plugins/forms/jquery.dualListBox.js',
		'cleditor' => 'plugins/forms/jquery.cleditor.js',
		'ibutton' => 'plugins/forms/jquery.ibutton.js',
		'validation' => array('plugins/forms/jquery.validationEngine-en.js',
			'plugins/forms/jquery.validationEngine.js'),
		'upload' => array('plugins/uploader/plupload.js',
			'plugins/uploader/plupload.html4.js',
			'plugins/uploader/plupload.html5.js',
			'plugins/uploader/jquery.plupload.queue.js'),
		'wizards' => array('plugins/wizards/jquery.form.wizard.js',
			'plugins/wizards/jquery.validate.js',
			'plugins/wizards/jquery.form.js'),
		'ui' => array('plugins/ui/jquery.collapsible.min.js',
			'plugins/ui/jquery.breadcrumbs.js',
			'plugins/ui/jquery.tipsy.js',
			'plugins/ui/jquery.progress.js',
			'plugins/ui/jquery.timeentry.min.js',
			'plugins/ui/jquery.colorpicker.js',
			'plugins/ui/jquery.jgrowl.js',
			'plugins/ui/jquery.fancybox.js',
			'plugins/ui/jquery.fileTree.js',
			'plugins/ui/jquery.sourcerer.js',
			'plugins/ui/jquery.easytabs.min.js',
			'plugins/ui/jquery.animatescroll.noeasing.js',
			'plugins/ui/jquery.blockUI.js',
			'plugins/ui/jquery.slimscroll.min.js',
			'plugins/ui/jquery.cycle.all.js'),
			'fullcalendar' => 'plugins/others/jquery.fullcalendar.js',
		'elfinder' => 'plugins/others/jquery.elfinder.js',
		'bootstrap' => 'files/bootstrap.js',
        'jcrop' => 'plugins/ui/jquery.Jcrop.min.js',
        'form' => 'plugins/forms/jquery.form.js'),

        'signup_scripts' => array(
        	'jquery.js', 'jquery-ui.js', 'plugins/forms/jquery.autoSuggest.js',
    	    'plugins/ui/jquery.animatescroll.noeasing.js', 'plugins/ui/jquery.blockUI.js',
    		'files/bootstrap.js', 'plugins/ui/jquery.Jcrop.min.js',
        	'plugins/forms/jquery.form.js'
        ),
        
		'assets_upload_path' => DOCROOT.'assets'.DS.'img',
		'offer_images_path' => 'offers',
		'offer_images_sizes' => array('small' => '200', 'large' => '400'),
    
        'event_images_path' => 'events',
		'event_images_sizes' => array('small' => '200', 'large' => '400'),
    
        'logo_images_path' => 'logos',
		'logo_images_sizes' => array('icon' => '110', 'small' => '200', 'large' => '400'),
    
        'user_images_path' => 'users',
		'user_images_sizes' => array('large' => '400'),
    
        'landing_images_path' => 'landing',
		'landing_images_sizes' => array('icon' => '320', 'small' => '200', 'large' => '640'),

        'flag_images_path' => 'flags',
		'flag_images_sizes' => array('large' => '400'),
    
        'profiling_choices_images_path' => 'profiling_choices',
		'profiling_choices_images_sizes' => array('large' => '250'),
    
		'temporary_images_path' => 'temp',
		'temporary_images_sizes' => array(),
		
		'micello_map_validity' => '+1 hours',
		
		'nearby_users_time_frame' => '15 days',
		'nearby_users_radius'     => 1,
		'nearby_users_accuracy'   => 1,
		
		'payments' => array(
		    'plan_id'                 => 'shopsuey_fee',
		    'one_time_setup_id'       => 'one_time_setup',
		    'mall_monthly_fee_id'     => 'mall_monthly_fee',
		    'merchant_monthly_fee_id' => 'merchant_monthly_fee',
		    // These should be consistent with the values set on braintree's config
		    'fees_info' => array(
    		    'trial_days'           => 14,
    		    'mall_monthly_fee'     => 50.00,
    		    'merchant_monthly_fee' => 5.99,
		    ),
	    ),
    
    '8coupons_api_key'  => "19012c6931662f26bdd3ec011be1603ffe4927e809703cd4b9d344f87af60278e3565f944ea0edd83996ef4ce77727d7",
    'sqoot_api_key'  => "GEeIpu1dggvBBfPKES3O",
    
    'rich_editor_allowed_tags' => '<b><em><strong><i><u><div><br><strike><sub><sup><font><h1><h2><h3><h4><h5><h6><p><ul><li><ol><blockquote><a><hr><span>',
    'signup_notification_email' => 'admin@local.com',
    'map_update_notification_email' => 'admin@local.com',
    
);
