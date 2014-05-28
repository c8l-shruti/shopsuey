<?php

class Code {
	/* Auth and users */
	const ERROR_CREATE_ACCESS_KEY = '1000';
	const ERROR_INVALID_PROCEDURE = '1001';
	const ERROR_INVALID_EMAIL_OR_PASSWORD = '1002';
	const ERROR_EMAIL_AND_PASSWORD_REQUIRED = '1003';
	const ERROR_INVALID_APP_ID = '1004';
	const ERROR_AUTOLOGIN_FAILED = '1005';
	const ERROR_ACCESS_DENIED = '1006';
	const ERROR_UID_REQUIRED = '1007';
	const ERROR_EMAIL_REQUIRED = '1008';
	const ERROR_INVALID_USER_ID = '1009';
	const ERROR_REQUIRED_ACCESS_KEY = '1010';
	const ERROR_USERNAME_ALREADY_IN_USE = '1011';
	const ERROR_EMAIL_ALREADY_IN_USE = '1012';
	const ERROR_INVALID_EMAIL = '1013';
	const ERROR_INVALID_PASSWORD = '1014';
	const ERROR_USER_SAVE_ERROR = '1015';
	const ERROR_MISSING_SETTING = '1016';
	const ERROR_FBUID_REQUIRED = '1017';
	const ERROR_INVALID_GROUP = '1018';
	const ERROR_INCOMPATIBLE_COMPANY = '1019';
	const ERROR_USER_DELETE = '1020';
	const ERROR_RESET_GENERATION = '1021';
	const ERROR_RESET_INVALID = '1022';
	const ERROR_IMAGE_UPLOAD = '1023';
	const ERROR_IMAGE_DELETE = '1024';
	const ERROR_MISSING_IMAGE = '1025';
	const ERROR_CHANGE_PASSWORD = '1026';
	const ERROR_WRONG_PASSWORD = '1027';
	const ERROR_GETTING_PROFILING_CHOICES = '1028';
	const ERROR_UPDATING_PROFILINGS = '1029';
	const ERROR_GETTING_PROFILING = '1030';
	const ERROR_INVALID_LOGIN_HASH = '1031';
    const ERROR_ACCESS_TOKEN_REQUIRED = '1032';
    const ERROR_ACCESS_TOKEN_INVALID = '1033';
    const ERROR_FB_API_UNAVAILABLE = '1034';
    const ERROR_SSL_REQUIRED = '1035';
    const ERROR_INVALID_APN_TOKEN = '1036';
    const ERROR_UPDATING_APN_TOKEN = '1037';
    const ERROR_INVALID_FOLLOW_ID = '1038';
    const ERROR_ALREADY_FOLLOWING = '1039';
    const ERROR_NOT_FOLLOWING = '1040';
    const ERROR_PERSIST_FOLLOWING= '1041';
    const ERROR_PERSIST_UNFOLLOWING= '1042';
	
	/* Offers */
	const ERROR_INVALID_OFFER_ID = '2000';
	const ERROR_NO_AVAILABLE_CODES = '2001';
	const ERROR_ALREADY_REDEEMED_ALLOWED_TIMES = '2002';
	const ERROR_CREATE_ERROR = '2003';
	const ERROR_REDEEM_CREATE_ERROR = '2004';
	const ERROR_INVALID_REDEEM_ID = '2005';
	const ERROR_OFFER_UNAVAILABLE = '2006';
	const ERROR_NO_DEFAULT_AUTO_TYPE = '2007';
	const ERROR_NO_FAVORITES = '2008';
	const ERROR_NO_LOCATION = '2009';
	const ERROR_NO_NEARBY_LOCATIONS = '2010';
	const ERROR_NO_LOCATION_FOR_OFFER = '2011';
    const ERROR_NO_OFFER_MODE_SPECIFIED = '2012';
    const ERROR_NO_TAGS_FOR_OFFER = '2013';
    const ERROR_SAVING_OFFER = '2014';
    
    /* Merchants */
	const ERROR_INVALID_MERCHANT_ID = '3000';
	const ERROR_MERCHANT_REQUIRES_MICELLO_INFO = '3001';
	const ERROR_MERCHANT_MICELLO_ID_ALREADY_EXISTS = '3002';
	const ERROR_MERCHANT_GEOMETRY_ID_ALREADY_EXISTS = '3003';
	
	/* Malls */
	const ERROR_INVALID_MALL_ID = '4000';
	const ERROR_MICELLO_ID_ALREADY_EXISTS = '4001';

	/* Offer codes */
	const ERROR_INVALID_OFFER_CODE_ID = '5000';
	const ERROR_INVALID_OFFER_CODE = '5001';
	const ERROR_DEFAULT_OFFER_CODE_TYPE_REQUIRED = '5002';
	
	/* Locations */
	const ERROR_INVALID_LOCATION_ID = '6000';
	const ERROR_INVALID_BLOCKING_TIME_LAPSE = '6001';
	const ERROR_WHILE_BLOCKING = '6002';
	const ERROR_LOCATION_NOT_BLOCKED = '6003';
	const ERROR_WHILE_UNBLOCKING = '6004';
	const ERROR_NO_FILTERS = '6005';
	const ERROR_NO_MICELLO_INFO = '6006';
	const ERROR_INCORRECT_MICELLO_TYPE = '6007';
	const ERROR_MICELLO_REQUEST = '6008';
	const ERROR_SAVE_MICELLO_INFO = '6009';
	const ERROR_MISSING_POSITION = '6010';
	const ERROR_COORDINATES_REQUIRED = '6011';
	const ERROR_SAVING_LOCATION_REQUEST = '6012';
	const ERROR_EXISTENT_LOCATION_REQUEST = '6013';
    const ERROR_NO_FAVORITE_LOCATIONS = '6014';
    const ERROR_UPDATING_FAVORITE_LOCATIONS = '6015';
    const ERROR_WRONG_PHONE_FORMAT = '6016';
    const ERROR_INVALID_WEBSITE_URL = '6017';
    const ERROR_INVALID_LOCATION_EMAIL = '6018';
    const ERROR_FOURSQUARE_REQUEST = '6019';
    const ERROR_YELP_REQUEST = '6020';
    const ERROR_INVALID_COMMUNITIES = '6021';
    const ERROR_INVALID_FACEBOOK_URL = '6022';
    const ERROR_NO_INSTAGRAM_CONFIGURED = '6023';
    const ERROR_INSTAGRAM_FEED = '6024';
    const ERROR_NO_TAGS_FOR_LOCATION = '6025';
    
    /* Events */
    const ERROR_INVALID_EVENT_ID = '7000';
    const ERROR_NO_LOCATION_FOR_EVENT = '7001';
    const ERROR_NO_NEARBY_EVENTS = '7002';
    const ERROR_NO_FAVORITES_FOR_EVENT = '7003';
    const ERROR_NO_RSVPS = '7004';
    const ERROR_SAVING_EVENT = '7005';
    
    
    /* Proximity Messaging */
    const ERROR_INVALID_ENTITY_ID = '8000';
    const ERROR_INVALID_ENTITY_TYPE = '8001';
    const ERROR_INVALID_LIKE_STATUS = '8002';
    
    /* Subscription to newsletter */
    const ERROR_INVALID_SUBSCRIPTION_LOCATION_ID = '9000';
    const ERROR_ALREADY_SUBSCRIBED = '9001';
    
    /* Request social networks */
    const ERROR_INVALID_SN_REQUEST_LOCATION_ID = '10000';
    const ERROR_ALREADY_REQUESTED_SN = '10001';
    
    /* User sessions time traking */
    const ERROR_SAVING_SESSION_START_TIME = '11000';
    const ERROR_SESSION_NOT_STARTED = '11001';
    const ERROR_SESSION_ALREADY_ENDED = '11002';
    const ERROR_SAVING_SESSION_END_TIME = '11003';
    
    
    /* Flags */
    const ERROR_INVALID_TITLE = '12000';
    const ERROR_INVALID_FLAG_TYPE = '12001';
    const ERROR_INVALID_FLAG_LOCATION = '12002';
    const ERROR_FLAG_SAVE_ERROR = '12003';
    const ERROR_INVALID_PRIVATE = '12004';
    const ERROR_MISSING_NEARBY_LOCATION_DATA = '12005';
    const ERROR_MISSING_FLAG_IDENTIFIER = '12006';
    const ERROR_INVALID_FLAG_IDENTIFIER = '12007';
    const ERROR_PRIVATE_FLAG_FOR_USER = '12008';
    const ERROR_MISSING_VOTE_STATUS = '12009';
    const ERROR_INVALID_VOTE_STATUS = '12010';
    const ERROR_CURRENT_USER_DOES_NOT_OWN_FLAG = '12011';
    const ERROR_CANT_INVITE_ON_PUBLIC_FLAG = '12012';
    const ERROR_FLAG_DELETE = '12013';
    const ERROR_INVALID_FLAG_IMAGE = '12014';
    const ERROR_FLAG_UNSUPPORTED_POSITION = '12015';
    const ERROR_INVALID_SORT_COMBINATION = '12016';
    
    /* Promo Codes */
    const ERROR_INVALID_PROMO_CODE = '13000';
    const ERROR_MISSING_CODE_PARAMETER = '13001';
	const ERROR_INACTIVE_PROMO_CODE = '13002';
    
    /* Social Interactions */
    const ERROR_INVALID_LOCATION = '14000';
    const ERROR_LOCATION_ALREADY_HAS_LIKE_FOR_THIS_USER = '14001';
    const ERROR_SAVING_SOCIAL_INTERACTION = '14002';
    
    
	private static $_errors = array(
			// Auth and users
			self::ERROR_CREATE_ACCESS_KEY => 'Unable to create access_key',
			self::ERROR_INVALID_PROCEDURE => 'Invalid procedure',
			self::ERROR_INVALID_EMAIL_OR_PASSWORD => 'Invalid email or password',
			self::ERROR_EMAIL_AND_PASSWORD_REQUIRED => 'Email and password are required parameters',
			self::ERROR_INVALID_APP_ID => 'Invalid app_id',
			self::ERROR_AUTOLOGIN_FAILED => 'Failed autologin',
			self::ERROR_ACCESS_DENIED => 'Access Denied',
			self::ERROR_UID_REQUIRED => 'The udid parameter is required',
			self::ERROR_EMAIL_REQUIRED => 'The email parameter is required',
			self::ERROR_INVALID_USER_ID => 'Invalid user_id',
			self::ERROR_REQUIRED_ACCESS_KEY => 'The access_key parameter is required',
			self::ERROR_USERNAME_ALREADY_IN_USE => 'Username already in use',
			self::ERROR_EMAIL_ALREADY_IN_USE => 'Email address already in use',
			self::ERROR_INVALID_EMAIL => 'Invalid email address',
			self::ERROR_INVALID_PASSWORD => 'Password must be at least 8 characters long, contain at least 1 capital letter, and contain at least 1 number. Example: P4ssword',
			self::ERROR_USER_SAVE_ERROR => 'Unable to save user information',
			self::ERROR_MISSING_SETTING => 'A required setting parameter is missing',
			self::ERROR_FBUID_REQUIRED => 'The fbuid parameter is required',
			self::ERROR_INVALID_GROUP => 'The group for the user is invalid',
			self::ERROR_INCOMPATIBLE_COMPANY => 'The company is incompatible with the group',
			self::ERROR_USER_DELETE => 'Unable to delete user',
			self::ERROR_RESET_GENERATION => 'The password reset could not be generated',
			self::ERROR_RESET_INVALID => 'The password reset is invalid',
			self::ERROR_IMAGE_UPLOAD => 'An error occured while uploading the image',
			self::ERROR_IMAGE_DELETE => 'An error occured while deleting the image',
			self::ERROR_MISSING_IMAGE => 'The user does not have an image',
			self::ERROR_CHANGE_PASSWORD => 'The password could not be changed',
			self::ERROR_WRONG_PASSWORD => 'Invalid current password',
	    	self::ERROR_GETTING_PROFILING_CHOICES => 'Cannot find profiling choices',
	        self::ERROR_GETTING_PROFILING => 'Cannot find user profiling',
	    	self::ERROR_UPDATING_PROFILINGS => 'An error has ocurred updating user profiling',
	        self::ERROR_INVALID_LOGIN_HASH => 'Invalid login hash',
            self::ERROR_ACCESS_TOKEN_REQUIRED => 'access_token parameter is mandatory',
            self::ERROR_ACCESS_TOKEN_INVALID => 'Invalid FB access_token',
            self::ERROR_FB_API_UNAVAILABLE => 'Facebook API is not responding right now, try again later',
            self::ERROR_SSL_REQUIRED => 'This method should only be called via a secure connection (HTTPS)',
            self::ERROR_INVALID_APN_TOKEN => 'A valid APN token should be 64 characters long (hex representation, no spaces)',
            self::ERROR_UPDATING_APN_TOKEN => 'Could not update APN token for an unknown reason',
            self::ERROR_INVALID_FOLLOW_ID => 'User attempting to follow does not exist',
            self::ERROR_ALREADY_FOLLOWING => 'Already following specified user',
            self::ERROR_NOT_FOLLOWING => 'Not following specified user',
            self::ERROR_PERSIST_FOLLOWING => 'There was an error persisting the user follow',
            self::ERROR_PERSIST_UNFOLLOWING => 'There was an error persisting the user unfollow',
			// Offers
			self::ERROR_INVALID_OFFER_ID => 'Invalid offer_id',
			self::ERROR_NO_AVAILABLE_CODES => 'The offer does not have any available codes',
			self::ERROR_ALREADY_REDEEMED_ALLOWED_TIMES => 'The offer has been already redeemed the allowed number of times',
			self::ERROR_CREATE_ERROR => 'Could not create a new code for the offer',
			self::ERROR_REDEEM_CREATE_ERROR => 'Could not create redeem info for the offer',
			self::ERROR_INVALID_REDEEM_ID => 'Invalid redeem_id',
			self::ERROR_OFFER_UNAVAILABLE => 'The offer you are trying to redeem has expired, is not active or not redeemable',
			self::ERROR_NO_DEFAULT_AUTO_TYPE => 'Can not determine the default type of offer code to create for the offer',
			self::ERROR_NO_FAVORITES => 'The user does not have any favorites',
			self::ERROR_NO_LOCATION => 'The location is required to search for nearby locations, either as param or stored as a setting',
			self::ERROR_NO_NEARBY_LOCATIONS => 'There are no nearby malls or retailers',
	        self::ERROR_NO_LOCATION_FOR_OFFER => 'No locations were selected for the offer',
            self::ERROR_NO_OFFER_MODE_SPECIFIED => 'redeemed_only or saved_only should be specified in order to return offers',
            self::ERROR_NO_TAGS_FOR_OFFER => 'At least one tag must be specified',
            self::ERROR_SAVING_OFFER => 'Error saving offer',
			// Merchants
			self::ERROR_INVALID_MERCHANT_ID => 'Invalid merchant id',
	        self::ERROR_MERCHANT_REQUIRES_MICELLO_INFO => "A merchant inside a market place requires the Micello geometry id to be set",
	        self::ERROR_MERCHANT_MICELLO_ID_ALREADY_EXISTS => 'A merchant with the same micello id already exists',
	        self::ERROR_MERCHANT_GEOMETRY_ID_ALREADY_EXISTS => 'A merchant with the same geometry id already exists',
	        // Malls
			self::ERROR_INVALID_MALL_ID => 'Invalid Mall id',
	        self::ERROR_MICELLO_ID_ALREADY_EXISTS => 'A market place with the same micello id already exists',
			// Offer codes
			self::ERROR_INVALID_OFFER_CODE_ID => 'Invalid offer code id',
			self::ERROR_INVALID_OFFER_CODE => 'The offer code is not valid for the selected type',
	        self::ERROR_DEFAULT_OFFER_CODE_TYPE_REQUIRED => 'The default offer code type is required for the auto generation of codes',
			// Locations
			self::ERROR_INVALID_LOCATION_ID => 'Invalid location id',
			self::ERROR_INVALID_BLOCKING_TIME_LAPSE => 'The time lapse for blocking the location is invalid',
			self::ERROR_WHILE_BLOCKING => 'An error occurred while blocking the location for the current user',
			self::ERROR_LOCATION_NOT_BLOCKED => 'The location is not blocked for the current user',
			self::ERROR_WHILE_UNBLOCKING => 'An error occurred while unblocking the location for the current user',
			self::ERROR_NO_FILTERS => 'At least one filter must be set for the search to proceed',
			self::ERROR_NO_MICELLO_INFO => "The location does not have any Micello's info loaded",
			self::ERROR_INCORRECT_MICELLO_TYPE => "The location has the incorrect Micello's type",
			self::ERROR_MICELLO_REQUEST => "An error occurred while querying info from Micello's API",
			self::ERROR_SAVE_MICELLO_INFO => "An error occurred while saving Micello's info",
	        self::ERROR_MISSING_POSITION => "Latitude and longitude are required to order by distance or supported position",
	        self::ERROR_COORDINATES_REQUIRED => 'The latitude and longitude coordinates are required for the location',
	        self::ERROR_SAVING_LOCATION_REQUEST => 'Error saving location request',
	        self::ERROR_EXISTENT_LOCATION_REQUEST => 'A location request for the current user already exists',
            self::ERROR_NO_FAVORITE_LOCATIONS => 'The user has no favorite locations',
	        self::ERROR_UPDATING_FAVORITE_LOCATIONS => 'Error updating favorite locations',
	        self::ERROR_WRONG_PHONE_FORMAT => 'Phone format should be: 888-555-1234 ext 567',
	        self::ERROR_INVALID_WEBSITE_URL => 'Invalid URL for website',
	        self::ERROR_INVALID_LOCATION_EMAIL => 'The contact email for the location is invalid',
			self::ERROR_FOURSQUARE_REQUEST => "An error occurred while querying info from Foursquare's API",
	        self::ERROR_INVALID_COMMUNITIES => 'The communities list format is invalid',
            self::ERROR_INVALID_FACEBOOK_URL => 'Invalid Facebook Page URL',
	        self::ERROR_NO_INSTAGRAM_CONFIGURED => 'Instagram is not configured for the location',
	        self::ERROR_INSTAGRAM_FEED => 'An error occurred while accessing the instagram feed',
            self::ERROR_NO_TAGS_FOR_LOCATION => 'At least one tag must be specified', 
	        // Events
            self::ERROR_INVALID_EVENT_ID => 'Invalid event id',
            self::ERROR_NO_LOCATION_FOR_EVENT => 'User\'s location could not be determined',
            self::ERROR_NO_NEARBY_EVENTS => 'No nearby event locations could be found',
            self::ERROR_NO_FAVORITES_FOR_EVENT => 'The user does not have any favorites',
            self::ERROR_NO_RSVPS => 'The user hasn\'t RSPV\'d any events',
            self::ERROR_SAVING_EVENT => 'Error saving event',
            // Proximity messaging
            self::ERROR_INVALID_ENTITY_ID => 'No entity could be found with that id',
            self::ERROR_INVALID_ENTITY_TYPE => 'Invalid entity type. It should be event or offer.',
            self::ERROR_INVALID_LIKE_STATUS => 'Like status should only take the values -1, 0 or 1 (dislike, no opinion and like respectively)',
            // Newsletter subscriptions
            self::ERROR_INVALID_SUBSCRIPTION_LOCATION_ID => 'Invalid location id',
            self::ERROR_ALREADY_SUBSCRIBED => 'This user has already subscribed to the newsletter',
            // Request social network
            self::ERROR_INVALID_SN_REQUEST_LOCATION_ID => 'Invalid location id',
            self::ERROR_ALREADY_REQUESTED_SN => 'This user has already requested this social network for this location',
    	    // User sessions time traking
    	    self::ERROR_SAVING_SESSION_START_TIME => 'Error saving session start time',
    	    self::ERROR_SESSION_NOT_STARTED => 'The session you are trying to end was not started',
    	    self::ERROR_SESSION_ALREADY_ENDED => 'The session you are trying to end is already ended',
    	    self::ERROR_SAVING_SESSION_END_TIME => 'Error saving session end time',
            // Flags
            self::ERROR_INVALID_TITLE => 'Invalid title parameter',
            self::ERROR_INVALID_FLAG_TYPE => 'Invalid flag type (it must be Aloha, POI, Community or Attraction)',
            self::ERROR_INVALID_FLAG_LOCATION => 'Invalid flag location',
            self::ERROR_FLAG_SAVE_ERROR => 'An error occurred while saving Flag',
            self::ERROR_INVALID_PRIVATE => 'Invalid private parameter',
            self::ERROR_MISSING_NEARBY_LOCATION_DATA => 'Latitude, Longitude and Radius parameters are required when nearby is true',
            self::ERROR_MISSING_FLAG_IDENTIFIER => 'Parameter flag_id is required for this service',
            self::ERROR_INVALID_FLAG_IDENTIFIER => 'Invalid flag id',
            self::ERROR_PRIVATE_FLAG_FOR_USER => 'The user doesn\'t have permission to see this flag',
            self::ERROR_MISSING_VOTE_STATUS => 'Parameter status is required for this services. It could be 1 or -1',
            self::ERROR_INVALID_VOTE_STATUS => 'Parameter must be 1 or -1',
            self::ERROR_CURRENT_USER_DOES_NOT_OWN_FLAG => 'Current user is not the owner of the flag',
            self::ERROR_CANT_INVITE_ON_PUBLIC_FLAG => 'A public flag doesn\'t support invitations', 
            self::ERROR_FLAG_DELETE => 'Unable to delete flag',
            self::ERROR_INVALID_FLAG_IMAGE => 'Error uploading image',
            self::ERROR_FLAG_UNSUPPORTED_POSITION => 'Specified coordinates are not currently supported by ShopSuey',
            self::ERROR_INVALID_SORT_COMBINATION => 'Invalid sort combination',
            // Promo Codes
            self::ERROR_INVALID_PROMO_CODE => 'Invalid promo code',
            self::ERROR_MISSING_CODE_PARAMETER => 'Parameter code is required for this service',
            self::ERROR_INACTIVE_PROMO_CODE => 'Invalid promo code',
            // Social Interactions 
            self::ERROR_INVALID_LOCATION => 'Invalid location id',
            self::ERROR_LOCATION_ALREADY_HAS_LIKE_FOR_THIS_USER => 'User had already liked this location',
            self::ERROR_SAVING_SOCIAL_INTERACTION => 'Error saving social interaction',
	);
	
	public static function get_message($code) {
		return self::$_errors[$code];
	}
	
}
