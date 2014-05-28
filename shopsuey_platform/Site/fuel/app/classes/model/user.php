<?php

class Model_User extends \Orm\Model
{
	const STATUS_DELETED = 0;
	const STATUS_ACTIVE = 1;
	const STATUS_BLOCKED = 2;
	const STATUS_STEP1 = 3;
	const STATUS_STEP2 = 4;
	const STATUS_STEP3 = 5;

	const GROUP_GUEST      = 0;
	const GROUP_USER       = 1;
	const GROUP_ANONYMOUS  = 2;
	const GROUP_MERCHANT   = 25;
	const GROUP_MANAGER    = 50;
	const GROUP_ADMIN      = 100;
	const GROUP_SUPERADMIN = 1000;

	private static $_creation_rights = array(
		self::GROUP_GUEST      => array(),
		self::GROUP_USER       => array(),
		self::GROUP_ANONYMOUS  => array(),
		self::GROUP_MERCHANT   => array(),
		self::GROUP_MANAGER    => array(self::GROUP_MERCHANT),
		self::GROUP_ADMIN      => array(self::GROUP_MERCHANT, self::GROUP_MANAGER),
		self::GROUP_SUPERADMIN => array(self::GROUP_MERCHANT, self::GROUP_MANAGER, self::GROUP_ADMIN, self::GROUP_SUPERADMIN, self::GROUP_USER),
	);
	
	private static $_valid_company_assignments = array(
		self::GROUP_MERCHANT   => array(Model_Location::TYPE_MERCHANT),
		self::GROUP_MANAGER    => array(Model_Location::TYPE_MALL, Model_Location::TYPE_MERCHANT),
	);

	private static $_group_names = array(
			self::GROUP_GUEST      => 'Guest',
			self::GROUP_USER       => 'User',
			self::GROUP_ANONYMOUS  => 'Anonymous User',
			self::GROUP_MERCHANT   => 'Merchant',
			self::GROUP_MANAGER    => 'Market Place Manager',
			self::GROUP_ADMIN      => 'Admin',
			self::GROUP_SUPERADMIN => 'Super Admin',
	);

	protected static $_many_many = array(
		'favorite_locations' => array(
			'table_through' => 'users_favorite_locations',
			'key_through_to' => 'location_id',
			'model_to' => 'Model_Location',
		),
	    'profilingchoices' => array(
	        'key_through_to' => 'profiling_choice_id',
	        'table_through' => 'user_profilings',
	    ),
	    'offers' => array(
	        'key_through_to' => 'offer_id',
	        'table_through' => 'users_offers',
	    ),
	);
	
	protected static $_has_many = array(
            
            'offer_redeems',
            
            'offerlikes',
            
                'favorite_locations'=> array(
			'model_to' => 'Model_Favoritelocation',
		),
		
		'location_blockings',
		'meta_fields'=> array(
			'model_to' => 'Model_User_Metafield',
		),
		'logins' => array(
			'model_to' => 'Model_User_Login',
		),
		'user_resets',
        'eventrsvps',
        'specialeventrsvps',
        'eventlikes',
	    'specialeventlikes',
        'flagvotes',
	    'location_requests',
        'subscriptions',
        'twitterrequests',
        'location_managers' => array(
			'key_to' => 'user_id',
		),
	    'sessions' => array(
	        'model_to' => 'Model_User_Session',
	    ),
        'contestants',
        'location_trackings',
        
        'followers' => array(
            'model_to' => 'Model_Userfollow',
            'key_from' => 'id',
            'key_to'   => 'followee_id'
        ),
        'following' => array(
            'model_to' => 'Model_Userfollow',
            'key_from' => 'id',
            'key_to'   => 'follower_id'
        )
	);
    
    protected static $_belongs_to = array(
        'promocode' => array(
            'key_from' => 'promo_code_id',
            'model_to' => 'Model_Promocode',
            'key_to' => 'id',
        ),
    );
    
    protected static $_has_one = array(
		'preferences' => array(
			'key_from' => 'id',
			'model_to' => 'Model_Userpreferences',
			'key_to' => 'user_id',
		),
        'payment' => array(
			'model_to' => 'Model_User_Payment',
		),
        'instagram' => array(
			'model_to' => 'Model_User_Instagram',
		),
    );

	protected static $_properties = array(
		'id',
		'status',
		'password',
		'email',
		'group',
		'created_at',
		'updated_at' => array('data_type' => 'int'),
        'fbuid',
        'apn_token',
        'apn_bundle',
        'apn_env',
        'promo_code_id',
        'last_activity',
        'analytics_cid' => array(
	        'default' => '',
        )
	);

	protected static $_observers = array(
        'Orm\\Observer_Typing' => array(
            'events' => array('before_save', 'after_save', 'after_load')
        ),
		'Orm\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'mysql_timestamp' => false,
		),
		'Orm\Observer_UpdatedAt' => array(
			'events' => array('before_save'),
			'mysql_timestamp' => false,
		),
        'Orm\\Observer_Self' => array(
			'events' => array('before_save')
		),
	);
	
	/**
	 * Check if the user can edit users of the given group
	 * @param unknown $group
	 */
	public function can_edit_group_members($group) {
		return in_array($group, self::$_creation_rights[$this->group]);
	}
	
	public function get_meta_field_value($key) {
		foreach($this->meta_fields as $meta_field) {
			if ($meta_field->key == $key) {
				return $meta_field->value;
			}
		}
		return NULL;
	}
    
    public function get_like_message_object($type, $id) {
        if ($type == 'offer') {
            $likes = $this->offerlikes;
            foreach ($likes as $like) {
                if ($like->offer_id == $id) {
                    return $like;
                }
            }
        } elseif ($type == 'specialevent') {
            $likes = $this->specialeventlikes;
            foreach ($likes as $like) {
                if ($like->specialevent_id == $id) {
                    return $like;
                }
            }
        } elseif ($type == 'event') {
            $likes = $this->eventlikes;
            foreach ($likes as $like) {
                if ($like->event_id == $id) {
                    return $like;
                }
            }
        }
        return null;
    }
    
    public function get_like_message_status($type, $id) {
        $like = $this->get_like_message_object($type, $id);
        if ($like)
            return (int)$like->status;
        return 0;
    }
    
    public function has_subscribed($location_id) {
        foreach ($this->subscriptions as $subscription) {
            if ($subscription->location_id == $location_id) {
                return true;
            }
        }
        return false;
    }
    
    public function has_requested_twitter($location_id) {
        foreach ($this->twitterrequests as $req) {
            if ($req->location_id == $location_id) {
                return true;
            }
        }
        return false;
    }
	
	public function set_meta_field_value($key, $value) {
		$meta = null;
		foreach($this->meta_fields as $meta_field) {
			if ($meta_field->key == $key) {
				$meta = $meta_field;
				break;
			}
		}
		if (is_null($meta)) {
			$meta = new Model_User_Metafield();
			$meta->key = $key;
			$this->meta_fields[] = $meta;
		}
		$meta->value = $value;
	}
	
	public function is_admin() {
		return $this->group == self::GROUP_ADMIN || $this->group == self::GROUP_SUPERADMIN;
	}
	
	/**
	 * Returns a list of groups up to the given group
	 * @param string $up_to
	 */
	public static function get_groups($from = self::GROUP_MERCHANT, $up_to = self::GROUP_SUPERADMIN) {
		$groups = array();
		foreach(self::$_group_names as $key => $name) {
			if ($key < $from) { continue; }
			$groups[$key] = $name;
			if ($key == $up_to) { break; }
		}
		return $groups;
	}
    
    public static function get_group_name($group_id) {
        return self::$_group_names[$group_id];
    } 
	
	public function search($string) {
		self::query()->_join($join);
	}
	
	public function can_be_assigned_to_company($company) {
		return in_array($company->type, self::$_valid_company_assignments[$this->group]);
	}
	
	public function is_regular_user() {
		return $this->group < self::GROUP_MERCHANT && $this->group > self::GROUP_GUEST;
	}
	
	public function is_guest() {
	    return $this->group == self::GROUP_GUEST;
	}

	public function is_new_user() {
	    return $this->status == self::STATUS_STEP1 || 
	           $this->status == self::STATUS_STEP2 ||
	           $this->status == self::STATUS_STEP3;
	}
	
	public function get_assigned_companies($include_sub_locations = true, $fetch_only_ids = false, $only_active = FALSE) {
	    if (count($this->location_managers) == 0) {
	        return array();
	    }

	    // Main assigned locations
	    $assigned_location_ids = array();
	    // Assigned market places that must include their merchants
	    $assigned_container_ids = array();
	    
	    foreach($this->location_managers as $location_manager) {
	        $assigned_location_ids[] = $location_manager->location_id;
	        if ($location_manager->include_merchants && $include_sub_locations) {
	            $assigned_container_ids[] = $location_manager->location_id;
	        }
	    }

	    $query = Model_Location::query();
	    
	    if ($fetch_only_ids) {
	        $query->select('id');
	    }

	    $statuses = array(Model_Location::STATUS_ACTIVE);
	    if (! $only_active) {
	        $statuses[] = Model_Location::STATUS_SIGNUP;
	    }
	    $query->where('status', 'in', $statuses);
	    
	    if (count($assigned_container_ids) > 0) {
	        $query->and_where_open();
	        $query->or_where('id', 'in', $assigned_location_ids);
	        $query->or_where('mall_id', 'in', $assigned_container_ids);
	        $query->and_where_close();
	    } else {
	        $query->where('id', 'in', $assigned_location_ids);
	    }
	    
	    return $query->order_by('name')->get();
	}
    
    public function get_favorite_locations_ids() {
        $favs = $this->favorite_locations;
        $ids = array();
        foreach ($favs as $fav) {
                $ids[] = $fav->id;
            }
        return $ids;
    }
    
    public function get_followers_count() {
        return count($this->followers);
    }

    public function get_followers() {
        $followers = array();
        foreach ($this->followers as $follower) {
            $followers[] = $follower->follower_user;
        }
        
        return $followers;
    }
    
    public function get_following_count() {
        return count($this->following);
    }
    
    public function get_following() {
        $following = array();
        foreach ($this->following as $followee) {
            $following[] = $followee->followee_user;
        }
        
        return $following;
    }
    
    public function is_followed_by($user) {
        foreach ($this->followers as $follower) {
            if ($follower->follower_id == $user->id) {
                return true;
            }
        }
        return false;
        
        /*foreach ($this->get_followers() as $follower) {
            if ($user->same_as($follower)) {
                return true;
            }
        }
        return false;*/
    }
    
    public function is_following($user) {
        foreach ($this->following as $followee) {
            if ($followee->followee_id == $user->id) {
                return true;
            }
        }
        return false;
        
        /*foreach ($this->get_following() as $followee) {
            if ($user->same_as($followee)) {
                return true;
            }
        }
        return false;*/
    }
    
    public function same_as($user) {
        return $this->id == $user->id;
    }
    
    public function get_friendly_name() {
        $fname = $this->get_meta_field_value("real_name");
        if ($fname) {
            return $fname;
        } else {
            $email_parts = explode("@", $this->email);
            return $email_parts[0];
        }
    }
    
    public function get_favorite_locations($location_type = null) {
        $locations = array();
        
        foreach ($this->favorite_locations as $location) {
            if (is_null($location_type) || $location_type == $location->type) {
                $locations[trim($location->name) . $location->id] = $location;
            }
        }
        
        ksort($locations);
        
        return array_values($locations);
    }

    public function get_stats() {
        $votes = $favorites = $events = $offers = $flags = 0;
        
        // Count votes on active offers
        $voted_offers = DB::select('offerlikes.offer_id')
                                ->from('offerlikes')
                                ->where('offerlikes.user_id', '=', $this->id)
                                ->as_assoc()->execute();
        
        $voted_offers_ids = array();
        foreach ($voted_offers as $voted_offer) {
            $voted_offers_ids[] = $voted_offer['offer_id'];
        }
        
        $votes = count(Model_Offer::filter_active_offer_ids($voted_offers_ids));
        
        
        // Count votes on active events
        $voted_events = DB::select('eventlikes.event_id')
                                ->from('eventlikes')
                                ->where('eventlikes.user_id', '=', $this->id)
                                ->as_assoc()->execute();
        
        $voted_events_ids = array();
        foreach ($voted_events as $voted_event) {
            $voted_events_ids[] = $voted_event['event_id'];
        }
        
        $votes += count(Model_Event::filter_active_event_ids($voted_events_ids));
        
        
        // Count flag votes
        $votes += count($this->flagvotes);

        
        // Count votes on Events rsvps
        $voted_events_rsvps = DB::select('eventrsvps.event_id')
                                ->from('eventrsvps')
                                ->where('eventrsvps.user_id', '=', $this->id)
                                ->as_assoc()->execute();
        
        $voted_events_rsvps_ids = array();
        foreach ($voted_events_rsvps as $voted_event) {
            $voted_events_rsvps_ids[] = $voted_event['event_id'];
        }
        
        $events = count(Model_Event::filter_active_event_ids($voted_events_rsvps_ids));
        
        // Favorites count
        $favorites = DB::select('users_favorite_locations.location_id')
                     ->from('users_favorite_locations')
                     ->where('users_favorite_locations.user_id', '=', $this->id)
                     ->as_assoc()->execute()->count();
        
        
        // Count my active offers
        $my_offers = DB::select('offer_id')->from('users_offers')->where('user_id', '=', $this->id)->as_assoc()->execute();
        $my_offers_ids = array();
        foreach ($my_offers as $offer) {
            $my_offers_ids[] = $offer['offer_id'];
        }
        $offers = count(Model_Offer::filter_active_offer_ids($my_offers_ids));
        
        
        // Add my redeem offers to my offers count
        $redeem_offers = DB::select('offer_codes.offer_id')
                                ->from('offer_redeems')
                                ->join('offer_codes')->on('offer_redeems.offer_code_id', '=', 'offer_codes.id')
                                ->where('offer_redeems.user_id', '=', $this->id)
                                ->as_assoc()->execute();
        
        $redeem_offers_ids = array();
        foreach ($redeem_offers as $offer) {
            $redeem_offers_ids[] = $offer['offer_id'];
        }
        $offers += count(Model_Offer::filter_active_offer_ids($redeem_offers_ids));
        
            
        // Count my flags
        $flags = count(Model_Flag::get_flags(array($this->id), false, $this));

        return array(
            'votes' => $votes,
            'favorites' => $favorites,
            'events' => $events,
            'offers' => $offers,
            'flags'  => $flags
        );
    }

    public function getProfilePicUrl(){
        
        $image       = $this->get_meta_field_value('image');
        $image_url   = Asset::get_file('large_' . $image, 'img', Config::get('cms.user_images_path'));
        return $image_url != false ? $image_url : '';
        
    }

    public function _event_before_save() {
        if (is_null($this->last_activity)) {
            $this->last_activity = time();
        }
	}
        
    public function favorite_location($location){

        $is_favorite = array_key_exists($location->id, $this->favorite_locations);

        if ($is_favorite) return false;

        $this->favorite_locations[$location->id] = $location;
        Helper_Activity::log_activity($this, 'favorite_location', array('location_id' => (int)$location->id));


        if ($this->save()){
            return true;
        }else{
            throw new Exception("ERROR FAVORITING LOCATION ID: ".$location->id." USER ID: ".$this->id);
        }
        
    }
}
