<?php

/**
 * Helper functions for API endpoints
 */

class Helper_Api {

    private static $_days = array(1 => 'mon', 2 => 'tue', 3 => 'wed', 4 => 'thr', 5 => 'fri', 6 => 'sat', 7 => 'sun');
    private static $_social_networks = array('facebook', 'twitter', 'foursquare', 'pintrest');
    
    private static $_states = array(
        'AL' => 'ALABAMA',
        'AK' => 'ALASKA',
        'AS' => 'AMERICAN SAMOA',
        'AZ' => 'ARIZONA',
        'AR' => 'ARKANSAS',
        'CA' => 'CALIFORNIA',
        'CO' => 'COLORADO',
        'CT' => 'CONNECTICUT',
        'DE' => 'DELAWARE',
        'DC' => 'DISTRICT OF COLUMBIA',
        'FM' => 'FEDERATED STATES OF MICRONESIA',
        'FL' => 'FLORIDA',
        'GA' => 'GEORGIA',
        'GU' => 'GUAM GU',
        'HI' => 'HAWAII',
        'ID' => 'IDAHO',
        'IL' => 'ILLINOIS',
        'IN' => 'INDIANA',
        'IA' => 'IOWA',
        'KS' => 'KANSAS',
        'KY' => 'KENTUCKY',
        'LA' => 'LOUISIANA',
        'ME' => 'MAINE',
        'MH' => 'MARSHALL ISLANDS',
        'MD' => 'MARYLAND',
        'MA' => 'MASSACHUSETTS',
        'MI' => 'MICHIGAN',
        'MN' => 'MINNESOTA',
        'MS' => 'MISSISSIPPI',
        'MO' => 'MISSOURI',
        'MT' => 'MONTANA',
        'NE' => 'NEBRASKA',
        'NV' => 'NEVADA',
        'NH' => 'NEW HAMPSHIRE',
        'NJ' => 'NEW JERSEY',
        'NM' => 'NEW MEXICO',
        'NY' => 'NEW YORK',
        'NC' => 'NORTH CAROLINA',
        'ND' => 'NORTH DAKOTA',
        'MP' => 'NORTHERN MARIANA ISLANDS',
        'OH' => 'OHIO',
        'OK' => 'OKLAHOMA',
        'OR' => 'OREGON',
        'PW' => 'PALAU',
        'PA' => 'PENNSYLVANIA',
        'PR' => 'PUERTO RICO',
        'RI' => 'RHODE ISLAND',
        'SC' => 'SOUTH CAROLINA',
        'SD' => 'SOUTH DAKOTA',
        'TN' => 'TENNESSEE',
        'TX' => 'TEXAS',
        'UT' => 'UTAH',
        'VT' => 'VERMONT',
        'VI' => 'VIRGIN ISLANDS',
        'VA' => 'VIRGINIA',
        'WA' => 'WASHINGTON',
        'WV' => 'WEST VIRGINIA',
        'WI' => 'WISCONSIN',
        'WY' => 'WYOMING',
    );
    
	public static function build_error_meta($error_code, $extra_fields = array()) {
		$meta = array(
			'error_code' => $error_code,
			'error'			 => Code::get_message($error_code),
			'status' 		 => 0,
		);
		return array_merge($meta, $extra_fields);
	}
	
	public static function model_to_real_object($orm_object) {
		$properties = $orm_object::properties();
		$object = new stdClass();
		foreach(array_keys($properties) as $property) {
			$object->$property = $orm_object->$property;
		}
		return $object;
	}
	
	/**
	 * Appropiate response for a login
	 * @param Model_User_Login $user_login
	 */
	public static function login_response($user_login) {
		$response = self::model_to_real_object($user_login);
		$response->user = self::user_response($user_login->user);
		$response->application = self::application_response($user_login->application);
		return $response;
	}
	
	public static function user_response($user_model) {
		$user = self::model_to_real_object($user_model);
		unset($user->meta_fields);
		unset($user->password);
		$user->meta = array('email' => $user_model->email);
		$json_meta_fields = Config::get('cms.json_meta_fields');
        
		foreach($user_model->meta_fields as $meta_field) {
            
            if ($meta_field->key == 'image') {
                // Make the absolute image url
                $image_url = Asset::get_file('large_' . $meta_field->value, 'img', Config::get('cms.user_images_path'));
                $user->meta[$meta_field->key] = $image_url != false ? $image_url : '';
            
            } else {
                $user->meta[$meta_field->key] = in_array($meta_field->key, $json_meta_fields) ? json_decode($meta_field->value) : $meta_field->value;
            }
		}
 
		$user->location_managers = $user_model->location_managers;
		return $user;
	}
    
    public static function user_standard_response($user_model, $current_user = null, $full = true) {
        $fields_to_remove = array('updated_at', 'created_at', 'meta_fields', 'password', 'apn_token', 'apn_bundle', 'apn_env', 'group', 'status');
        $user = self::model_to_real_object($user_model);
        
        foreach ($fields_to_remove as $field_to_remove) {
            unset($user->$field_to_remove);
        }
        
        if ($full) {
            $user->followers_count = $user_model->get_followers_count();
            $user->following_count = $user_model->get_following_count();

            if (!is_null($current_user)) {
                $user->following = $current_user->is_following($user_model);
                $user->follower  = $user_model->is_following($current_user);
            }

            $last_tracking = Model_Location_Tracking::get_last_location_tracking($user_model);

            $is_online = null;
            $latitude  = null;
            $longitude = null;
            if ($last_tracking) {
                $is_online = ($last_tracking->created_at > time() - Controller_Api_User::ONLINE_TIME_WINDOW);
                $latitude  = $last_tracking->latitude;
                $longitude = $last_tracking->longitude;
            }

            $user->online    = $is_online;
            $user->latitude  = $latitude;
            $user->longitude = $longitude;
        }
        
        $real_name = $user_model->get_meta_field_value('real_name');
        if (is_null($real_name)) {
            $first_name = $user_model->get_meta_field_value('fname');
            $last_name  = $user_model->get_meta_field_value('lname');
            $real_name  = $first_name . ' ' . $last_name;
        }
        
        $image       = $user_model->get_meta_field_value('image');
        $image_url   = Asset::get_file('large_' . $image, 'img', Config::get('cms.user_images_path'));
        $user->image = $image_url != false ? $image_url : '';
        
        $user->name  = $real_name;
        
        return $user;
    }
    
    public static function promo_code_response($model_promo_code, $full = false) {
        $promo_code = self::model_to_real_object($model_promo_code);
        
        if (!$full) {
            $fields_to_remove = array('updated_at', 'created_at', 'date_start', 'date_end', 'id');
            foreach ($fields_to_remove as $field_to_remove) {
                unset($promo_code->$field_to_remove);
            }
        }
        
        $promo_code->type_name = $model_promo_code->get_promo_code_type_name();
        
        return $promo_code;
    }
    
    public static function flag_response($model_flag, $current_user) {
        $flag = self::model_to_real_object($model_flag);
        
        $fields_to_remove = array('updated_at', 'owner_id');
        foreach ($fields_to_remove as $field_to_remove) {
            unset($flag->$field_to_remove);
        }
        
        $image_url = Asset::get_file('large_' . $flag->image_uri, 'img', Config::get('cms.flag_images_path'));
        $flag->image = $image_url != false ? $image_url : '';
        
        unset($flag->image_uri);
        
        if ($model_flag->private) {
            $flag->invited_users = array();
            foreach ($model_flag->invited_users as $invited_user) {
                $flag->invited_users[] = self::user_standard_response($invited_user, $current_user, false);
            }
        }
        
        $flag->owner = $model_flag->owner->id;
            
        if (is_null($flag->location_id)) {
            unset($flag->floor);
        }
        
        $flag->vote_status = 0;
        foreach ($current_user->flagvotes as $vote) {
            if ($vote->flag->same_as($flag)) {
                $flag->vote_status = $vote->status;
                break;
            }
        }
        
        return $flag;
    }
	
	public static function application_response($application) {
		$application = self::model_to_real_object($application);
		unset($application->secret);
		return $application;
	}
	
	public static function offer_response($offer_model, $include_locations = TRUE, $include_micello_info = true, $favorite_locations = array()) {
		$offer = self::model_to_real_object($offer_model);
		if ($include_locations) {
		    $offer->locations = static::locations_response($offer_model->locations, $favorite_locations, $include_micello_info);
		}
        $offer->gallery_urls = Helper_Images_Offers::get_gallery_urls($offer_model->gallery);
		$offer->date_start = strtotime($offer_model->date_start);
		$offer->date_end = strtotime($offer_model->date_end);
        $offer->date_start_str = date('Y-m-d H:i:s', strtotime($offer_model->date_start));
        $offer->date_end_str = date('Y-m-d H:i:s', strtotime($offer_model->date_end));
        $offer->show_dates = (int)$offer->show_dates;
        
        $offer->likescount = sizeof($offer_model->offerlikes);
        
		return $offer;
	}
	
	public static function reward_response($reward_model, $offer, $redeemed) {
	    //$offer = self::offer_response($reward->offer);
	    $reward = self::model_to_real_object($reward_model);
	    $reward->offer = self::offer_response($offer);
	    $reward->redeemed = $redeemed;
	    
	    return $reward;
	}
    
    public static function event_response($event_model, $include_location = TRUE, $include_micello_info = true) {
		$event = self::model_to_real_object($event_model);
        if (isset($event_model->gallery)) {
            $event->gallery_urls = Helper_Images_Events::get_gallery_urls($event_model->gallery);
        } else {
            // special event, has no gallery, but has logo & landing screen
            if ($event_model->landing_screen_img) {
                $event->landing_url = Asset::get_file("large_" . $event_model->landing_screen_img, 'img', Config::get('cms.event_images_path'));
            }
            if (!$event_model->landing_screen_img || !$event->landing_url) {
                $event->landing_url = Asset::get_file("../images/default-landing.png", 'img') . "?v=2";
            }

            if ($event_model->logo) {
                $event->logo_url = Asset::get_file("large_" . $event_model->logo, 'img', Config::get('cms.event_images_path'));
            }
            if (!$event_model->logo || !$event->logo_url) {
                $event->logo_url = Asset::get_file("../images/default-logo.png", 'img');
            }
        }
		$event->date_start = strtotime($event_model->date_start);
		$event->date_end = strtotime($event_model->date_end);
        $event->date_start_str = date('Y-m-d H:i:s', strtotime($event_model->date_start));
        $event->date_end_str = date('Y-m-d H:i:s', strtotime($event_model->date_end));
        $event->show_dates = (int)$event->show_dates;
		
        if (isset($event_model->main_location)) {
            $event->main_location = static::location_response($event_model->main_location, array(), $include_micello_info);
            $event->special = 1;
        } else {
            $event->special = 0;
            // we don't include locations for special events because there could be a lot of them!
            if ($include_location) {
                $event->locations = static::locations_response($event_model->locations, array(), $include_micello_info);
            }
        }
        
        $event->likescount = isset($event_model->main_location)
                             ? sizeof($event_model->specialeventlikes)
                             : sizeof($event_model->eventlikes);

		return $event;
	}

	public static function offer_code_response($offer_code_model) {
		$offer_code = self::model_to_real_object($offer_code_model);
		$offer_code->offer = $offer_code_model->offer;
		return $offer_code;
	}

	public static function redeem_response($redeem_model) {
		if (!$redeem_model) {
			return NULL;
		}
		$redeem = self::model_to_real_object($redeem_model);
		$redeem->offer_code = self::model_to_real_object($redeem_model->offer_code);
		$redeem->date = strtotime($redeem_model->date);
		unset($redeem->created_at);
		unset($redeem->updated_at);
		unset($redeem->user_id);
		unset($redeem->offer_code_id);

		unset($redeem->offer_code->created_at);
		unset($redeem->offer_code->updated_at);
		unset($redeem->offer_code->offer_id);
		
		return $redeem;
	}
	
	public static function locations_response($location_models, $favorites = array(), $include_micello_info = true) {
		$locations = array();
		foreach($location_models as $location_model) {
			array_push($locations, self::location_response($location_model, $favorites, $include_micello_info));
		}
		return $locations;
	}
	
	public static function location_response($location_model, $favorites=array(), $include_micello_info = true, $include_categories = false) {
		$dfields = array('created_by', 'edited_by', 'created_at', 'updated_at');
		$location = self::model_to_real_object($location_model);
		$location->is_favorite = false;
        
        if ($include_micello_info) {
            $location->micello_info = $location_model->micello_info;
        }
		
		if (in_array($location->id, $favorites)) {
		    $location->is_favorite = true;
		}
		
		if ($include_micello_info && $location->micello_info) {
		    // There's a service to fetch this info
		    $location->micello_info->map = null;
		}

		if ($include_categories) {
		    $location->categories = $location_model->categories;
		}
		
                $location->profilings = $location_model->profilings;
                
        // per ticket SSP-110 (do not include hours and social keys if they are empty)
        if (empty($location->hours)) {
            unset($location->hours);
        }
        
        if ((!isset($location->hours) || $location_model->empty_hours()) && $location->type == Model_Location::TYPE_MERCHANT && isset($location->mall_id) && $location->mall_id) {
            $mall = Model_Mall::find($location->mall_id);
            $location->hours = $mall->hours;
            $location->hours_inherited_from_mall = true;
        }
        
        if (empty($location->social)) {
            unset($location->social);
        }
        
        if ($location->logo) {
            $location->logo_url = Asset::get_file("small_" . $location->logo, 'img', Config::get('cms.logo_images_path'));
        } else {
            $location->logo_url = Asset::get_file("default-logo.png", "img");
            
            // try to fetch the logo of the corresponding marketplace
            if (isset($location->mall_id) && $location->mall_id) {
                $mall = Model_Mall::find($location->mall_id);
                if ($mall && $mall->logo) {
                    $location->logo_url = Asset::get_file("small_" . $mall->logo, 'img', Config::get('cms.logo_images_path'));
                }
            }
        }
        
        if ($location->landing_screen_img) {
            $location->landing_url = Asset::get_file("large_" . $location->landing_screen_img, 'img', Config::get('cms.landing_images_path'));
        } else {
            $location->landing_url = Asset::get_file("../images/default-landing.png", 'img') . "?v=2";
            
            // try to fetch the logo of the corresponding marketplace
            if (isset($location->mall_id) && $location->mall_id) {
                $mall = Model_Mall::find($location->mall_id);
                if ($mall && $mall->landing_screen_img) {
                    $location->landing_url = Asset::get_file("large_" . $mall->landing_screen_img, 'img', Config::get('cms.landing_images_path'));
                }
            }
        }

        // just in case something went wrong (e.g.: missing or damaged image files in the server)
        if (!$location->landing_url) {
            $location->landing_url = Asset::get_file("../images/default-landing.png", 'img') . "?v=2";
        }
        if (!$location->logo_url) {
            $location->logo_url = Asset::get_file("default-logo.png", "img");
        }

        // cast fields to int where necessary
        $int_fields = array('default_logo', 'default_social', 'setup_complete', 'max_users', 'auto_generated');
        foreach ($int_fields as $int_field) {
            if (property_exists($location, $int_field)) {
                $location->$int_field = (int)$location->$int_field;
            }
        }

		// Remove unnecessary fields
		foreach($dfields as $field) { unset($location->$field); }
		return $location;		
	} 
	
	public static function set_user_meta($user, $key, $value) {
		$user_meta = Model_User_Metafield::query()->where('key', $key)->where('user_id', $user->id)->get_one();
		if (!$user_meta) {
			$user_meta = new Model_User_Metafield();
			$user_meta->user = $user;
			$user_meta->key = $key;
		}
		$user_meta->value = $value;
		return $user_meta->save();
	}
	
	public static function delete_user_meta($user, $key) {
		$user_meta = Model_User_Metafield::query()->where('key', $key)->where('user_id', $user->id)->get_one();
		if ($user_meta) {
			$user_meta->delete();
			return TRUE;
		}
		return FALSE;
	}
	
	public static function get_user_meta($user, $key) {
		$user_meta = Model_User_Metafield::query()->where('key', $key)->where('user_id', $user->id)->get_one();
		if ($user_meta) {
			return $user_meta->value;
		} else {
			return NULL;
		}
	}
	
	public static function new_social_object() {
		$social = new \stdClass();
		foreach(self::$_social_networks as $social_network) {
		    $social->$social_network = '';
		}
		return $social;
	}
	
	public static function new_hours_array() {
		$hours = array();
		foreach (self::$_days as $key => $name) {
			$hours[$name] = array('open' => '', 'close' => '');
		}
		return $hours;
	}
	
	public static function empty_hours_object($hours) {
		if (empty($hours)) {
			return TRUE;
		}
		$hours = (array)$hours;
		foreach (self::$_days as $key => $name) {
			$day = (array)$hours[$name];
			if (!empty($day['open']) || !empty($day['close'])) {
				return FALSE;
			}
		}
		return TRUE;
	}

	public static function empty_social_object($social) {
		if (empty($social)) {
			return TRUE;
		}
		$social = (array)$social;

		foreach(self::$_social_networks as $social_network) {
		    if (! empty($social[$social_network])) {
		        return FALSE;
		    }
		}
		
		return TRUE;
	}
	
	public static function get_state_code($name) {
        $flipped_states = array_flip(self::$_states);
        $name = strtoupper($name);
        return isset($flipped_states[$name]) ? $flipped_states[$name] : '';
	}
	
	public static function strip_tags($str) {
	    // Cleanup description
	    return strip_tags($str, Config::get('cms.rich_editor_allowed_tags'));
	}

	public static function get_location_friendly_name($location) {
        $name = $location->name;
        if ($location->type == Model_Location::TYPE_MERCHANT && !empty($location->mall_id)) {
            $mall = Model_Mall::find($location->mall_id);
            $name = "{$location->name} [{$location->type}, {$mall->name}, {$mall->city}]";
        } else {
            $name = "{$location->name} [{$location->type}, {$location->city}]";
        }
        return $name;
	}
}
