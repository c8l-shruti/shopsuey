<?php

/**
 * The Api Controller.
 * This controllers the CRUD proceedures for flags
 *
 * @package  app
 * @extends  Controller_Rest
 */
use Fuel\Core\Input;

class Controller_Api_Flag extends Controller_Api {
    
    private $_invalid_error_code = null;
    private $_invalid_field      = null;
    
    public function action_delete() {
        if (Input::method() != 'DELETE') { $this->response($this->no_access); return; }
        
        $current_user = $this->user_login->user;
        $flag_id = $this->param('id', null);
        $flag    = Model_Flag::find($flag_id);
        
        if ($flag) {
            if (!$flag->owner->same_as($current_user)) {
                return $this->_error_response(Code::ERROR_CURRENT_USER_DOES_NOT_OWN_FLAG);
            } else {
                
                $votes = Model_Flagvote::query()->where('flag_id', '=', $flag_id)->get();
                foreach ($votes as $vote) {
                    $vote->delete();
                }
                
                if ($flag->delete()) {
                    $data = array(
                        'data' => array('success' => true),
                        'meta' => array('error' => '', 'status' => 1)
                    );
                    $this->response($data);
                } else {
                    return $this->_error_response(Code::ERROR_FLAG_DELETE);
                }
            }
            
        } else {
            return $this->_error_response(Code::ERROR_INVALID_FLAG_IDENTIFIER);
        }
    }
    
    public function action_details() {
        if (Input::method() != 'GET') { $this->response($this->no_access); return; }
        
        $current_user = $this->user_login->user;
        $flag_id = $this->param('id', null);
        $flag    = Model_Flag::find($flag_id);
        
        if ($flag) {
            if ($flag->is_visible_for($current_user)) {
                $data = array(
                    'data' => array('success' => true, 'flag' => Helper_Api::flag_response($flag, $current_user)),
                    'meta' => array('error' => '', 'status' => 1)
                );
                $this->response($data);
            } else {
                return $this->_error_response(Code::ERROR_PRIVATE_FLAG_FOR_USER);
            }
            
        } else {
            return $this->_error_response(Code::ERROR_INVALID_FLAG_IDENTIFIER);
        }
    }
    
    public function action_invite() {
        if (Input::method() != 'PUT') { $this->response($this->no_access); return; }
        
        $current_user = $this->user_login->user;
        $flag_id = $this->param('id', null);
        $flag    = Model_Flag::find($flag_id);
        
        if ($flag) {
            if ($flag->owner->same_as($current_user)) {
                
                if (!$flag->private) {
                    return $this->_error_response(Code::ERROR_CANT_INVITE_ON_PUBLIC_FLAG);
                }
                
                $new_invited_user_ids = Input::put('invited_users', array());
                
                if (!empty($new_invited_user_ids)) {
                    $users = Model_User::query()->where('id', 'in', $new_invited_user_ids)->get();
                
                    foreach ($users as $user) {
                        if (!$flag->is_invited($user)) {
                            
                            if ($user->apn_token) {
                                $text = $current_user->get_friendly_name() . " has invited you to a flag";
                                $custom_properties = array('id' => $flag->id);
                                if ($flag->mall_id) {
                                    $custom_properties['mall_id'] = $flag->mall_id;
                                }
                                Helper_Apn::send_notification($user, $text, $custom_properties);
                            }
                            
                            $flag->invited_users[] = $user;
                        }
                    }
                    $flag->save();
                }
                
                $data = array(
                    'data' => array('success' => true),
                    'meta' => array('error' => '', 'status' => 1)
                );
                $this->response($data);
                
            } else {
                return $this->_error_response(Code::ERROR_CURRENT_USER_DOES_NOT_OWN_FLAG);
            }
            
            
        } else {
            return $this->_error_response(Code::ERROR_INVALID_FLAG_IDENTIFIER);
        }
    }
    
    public function action_uninvite() {
        if (Input::method() != 'PUT') { $this->response($this->no_access); return; }
        
        $current_user = $this->user_login->user;
        $flag_id = $this->param('id', null);
        $flag    = Model_Flag::find($flag_id);
        
        if ($flag) {
            if ($flag->owner->same_as($current_user)) {
                
                if (!$flag->private) {
                    return $this->_error_response(Code::ERROR_CANT_INVITE_ON_PUBLIC_FLAG);
                }
                
                $uninvited_user_ids = Input::put('uninvited_users', array());
                
                if (!empty($uninvited_user_ids)) {
                    $users = Model_User::query()->where('id', 'in', $uninvited_user_ids)->get();
                
                    foreach ($users as $user) {
                        if ($flag->is_invited($user)) {
                            
                            unset($flag->invited_users[$user->id]);
                        }
                    }
                    $flag->save();
                }
                
                $data = array(
                    'data' => array('success' => true),
                    'meta' => array('error' => '', 'status' => 1)
                );
                $this->response($data);
                
            } else {
                return $this->_error_response(Code::ERROR_CURRENT_USER_DOES_NOT_OWN_FLAG);
            }
            
            
        } else {
            return $this->_error_response(Code::ERROR_INVALID_FLAG_IDENTIFIER);
        }
    }
    
    public function action_private() {
        if (Input::method() != 'PUT') { $this->response($this->no_access); return; }
        
        $current_user = $this->user_login->user;
        $flag_id = $this->param('id', null);
        $flag = Model_Flag::find($flag_id);
        if ($flag) {
            
            if ($flag->owner->same_as($current_user)) {
                
                $flag_private = Input::put('private');
                
                if (!is_null($flag_private)) {
                    $flag->private = $flag_private;
                    
                    if ($flag->save()) {
                        $data = array(
                            'data' => array('success' => true),
                            'meta' => array('error' => '', 'status' => 1)
                        );
                        $this->response($data);
                        
                    } else {
                        return $this->_error_response(Code::ERROR_FLAG_SAVE_ERROR);
                    }
                    
                } else {
                    return $this->_error_response(Code::ERROR_INVALID_PRIVATE);
                }
                
                
            } else {
                return $this->_error_response(Code::ERROR_CURRENT_USER_DOES_NOT_OWN_FLAG);
            }
            
            
        } else {
            return $this->_error_response(Code::ERROR_INVALID_FLAG_IDENTIFIER);
        }
        
    }
    
    public function action_vote() {
        if (Input::method() != 'POST') { $this->response($this->no_access); return; }
        
        $current_user = $this->user_login->user;
        
        $flag_id = $this->param('id', null);
        if ($flag_id) {
            
            $flag = Model_Flag::find($flag_id);
            if ($flag) {
                
                if (!$flag->is_visible_for($current_user)) {
                    return $this->_error_response(Code::ERROR_PRIVATE_FLAG_FOR_USER);
                } else {
                    $status = Input::post('status');
                    if (!$status) {
                        return $this->_error_response(Code::ERROR_MISSING_VOTE_STATUS);
                    }
                    
                    if (!in_array($status, array(1,-1))) {
                        return $this->_error_response(Code::ERROR_INVALID_VOTE_STATUS);
                    }
                    
                    $vote = Model_Flagvote::query()->where('flag_id', '=', $flag->id)
                                                   ->where('user_id', '=', $current_user->id)
                                                   ->get_one();
                    
                    if (!$vote) {
                        $flag_vote = new Model_Flagvote();
                        $flag_vote->flag   = $flag;
                        $flag_vote->user   = $current_user;
                    } else {
                        $flag_vote = $vote;
                    }
                    
                    $flag_vote->status = $status;
                    
                    if ($flag_vote->save()) {
                        $data = array(
                            'data' => array('success' => true),
                            'meta' => array('error' => '', 'status' => 1)
                        );
                        $this->response($data);
                    } else {
                        return $this->_error_response(Code::ERROR_FLAG_SAVE_ERROR);
                    }
                }
                
            } else {
                return $this->_error_response(Code::ERROR_INVALID_FLAG_IDENTIFIER);
            }
            
        } else {
            return $this->_error_response(Code::ERROR_MISSING_FLAG_IDENTIFIER);
        }
    }
 
    
    public function action_list() {
        if (Input::method() != 'GET') { $this->response($this->no_access); return; }
        $current_user = $this->user_login->user;
        
        $parameters = Input::get();
        $nearby = isset($parameters['nearby']) && $parameters['nearby'];
        if ($nearby) {
            if (!isset($parameters['latitude'], $parameters['longitude'], $parameters['radius'])) {
                return $this->_error_response(Code::ERROR_MISSING_NEARBY_LOCATION_DATA);
            }
            
            $latitude  = $parameters['latitude'];
            $longitude = $parameters['longitude'];
            $radius    = $parameters['radius'];
        }
         
        $sortByName = (isset($parameters['sort_title']))?$parameters['sort_title']:null;
        $sortByCreationDate = (isset($parameters['sort_date']))?$parameters['sort_date']:null;
        
        if ($sortByName && $sortByCreationDate){
            return $this->_error_response(Code::ERROR_INVALID_SORT_COMBINATION);
        }
        
        $owner_ids = array();
        $owner_id  = (isset($parameters['owner'])) ? $parameters['owner'] : null;
        if (!is_null($owner_id)) {
            $owner = Model_User::find($owner_id);
            
            if (empty($owner)){
                $this->_error_response(Code::ERROR_INVALID_USER_ID);
            }
            
            $owner_ids = array($owner->id);
        }
        
        $following_users_flags = (isset($parameters['following_user_flags']) && $parameters['following_user_flags'] && !isset($parameters['owner']));
        
        if ($following_users_flags) {
            $following = $current_user->get_following();
            foreach ($following as $following_user) {
                $owner_ids[] = $following_user->id;
            }
        }
        
        $private = isset($parameters['private']) && $parameters['private'] ? true : false;
        
        $keyword = null;
        if (isset($parameters['keyword']) && !empty($parameters['keyword'])) {
            $keyword = $parameters['keyword'];
        }
        
        // Location and floor filter
        if (isset($parameters['location_id'], $parameters['floor']) && is_numeric($parameters['location_id']) && is_numeric($parameters['floor'])) {
            $location = $parameters['location_id'];
            $floor    = $parameters['floor'];
        } elseif (isset($parameters['outdoor_only']) && $parameters['outdoor_only']) {
            $location = -1;
            $floor = -1;
        } else {
            $location = null;
            $floor    = null;
        }
        
        if ($following_users_flags && empty($owner_ids)) {
            /* The user is not following any other user */
            $flags = array();
        } else {
            if ($nearby) {
                $flags = Model_Flag::get_nearby_flags($latitude, $longitude, $radius, $owner_ids, $private, $current_user, $keyword, $location, $floor, $sortByName, $sortByCreationDate);
            } else {
                $flags = Model_Flag::get_flags($owner_ids, $private, $current_user, $keyword, $location, $floor, $sortByName, $sortByCreationDate);
            }
        }
        
        $formatted_flags = array();
        foreach ($flags as $flag_id => $flag) {
            $formatted_flags[] = Helper_Api::flag_response($flag, $current_user);
        }
        
        if (isset($parameters['paging']) && $parameters['paging']) {
            $count = count($formatted_flags);
            $meta = array('pagination' => $this->_pagination($count, Input::param('page', 1)));
            $meta['status'] = 1;
            $meta['error']  = null;

            $limit  = $meta['pagination']['limit'];
            $offset = $meta['pagination']['offset']['current'];

            $flags = array_slice($formatted_flags, $offset, $limit);

            $data = array(
                'data' => array('success' => true, 'flags' => $flags),
                'meta' => $meta
            );
        } else {
            $data = array(
                'data' => array('success' => true, 'flags' => $formatted_flags),
                'meta' => array('error' => '', 'status' => 1)
            );
        }

		$this->response($data);
    }
    
    public function action_put() {
        if (Input::method() != 'PUT') { $this->response($this->no_access); return; }
        
        $current_user = $this->user_login->user;
        $flag_id = $this->param('id', null);
        $flag = Model_Flag::find($flag_id);
        if ($flag) {
            
            if ($flag->owner->same_as($current_user)) {
                $parameters = Input::put();
                
                if (isset($parameters['title'])) {
                    if (empty($parameters['title'])) {
                        return $this->_error_response(Code::ERROR_INVALID_TITLE);
                    }
                    $flag->title = $parameters['title'];
                }
                
                if (isset($parameters['description'])) {
                    $flag->description = $parameters['description'];
                }
                
                if (isset($parameters['latitude'])) {
                    if (empty($parameters['latitude'])) {
                        return $this->_error_response(Code::ERROR_INVALID_FLAG_LOCATION);
                    }
                    $flag->latitude = $parameters['latitude'];
                }
                
                if (isset($parameters['longitude'])) {
                    if (empty($parameters['longitude'])) {
                        return $this->_error_response(Code::ERROR_INVALID_FLAG_LOCATION);
                    }
                    $flag->longitude = $parameters['longitude'];
                }
                
                if (isset($parameters['private'])) {
                    $flag->private = $parameters['private']; 
                }
                
                if (isset($parameters['type'])) {
                    if (!in_array($parameters['type'], Model_Flag::get_valid_types())) {
                        return $this->_error_response(Code::ERROR_INVALID_FLAG_TYPE);
                    }
                    $flag->type = $parameters['type'];
                }
                
                
                if (isset($parameters['image'])) {
                    $image_uri = Helper_Images_Flags::copy_one_image_from_params($parameters['image']);
                    if ($image_uri) {
                        $flag->image_uri = $image_uri;
                    } else if (!is_null($this->_invalid_error_code)) {
                        // Error uploading image
                        return $this->_error_response(Code::ERROR_INVALID_FLAG_IMAGE);
                    }
                }
                
                // check if the coordinates are valid
                if (!$this->_check_coordinates_support($flag)) {
                    return $this->_error_response(Code::ERROR_FLAG_UNSUPPORTED_POSITION);
                }

                if ($flag->save()) {
                    $image_url = Asset::get_file('large_' . $flag->image_uri, 'img', Config::get('cms.flag_images_path'));
                    $image_uri = $image_url != false ? $image_url : '';

                    Helper_Activity::log_activity($current_user, 'edit_flag', array('flag_id' => (int)$flag->id));

                    return $this->response(array(
                        'data' => array('success' => true, 'image' => $image_uri),
                        'meta' => array('error' => '', 'status' => 1)
                    ));
                } else {
                    return $this->_error_response(Code::ERROR_FLAG_SAVE_ERROR);
                }
                
            } else {
                return $this->_error_response(Code::ERROR_CURRENT_USER_DOES_NOT_OWN_FLAG);
            }
            
            
        } else {
            return $this->_error_response(Code::ERROR_INVALID_FLAG_IDENTIFIER);
        }
    }
    
    /**
	 * Create a new flag
	 */
	public function action_post() {
		if (Input::method() != 'POST') { $this->response($this->no_access); return; }
		
        $current_user   = $this->user_login->user;
		$exclude_fields = array('id', 'created_at', 'updated_at');
        
		$parameters = Input::post();
        foreach ($exclude_fields as $excluded_field) {
            unset($parameters[$excluded_field]);
        }
        
        $new_flag = Model_Flag::forge($parameters);
        
        if ($this->_validate_flag_params($parameters)) {
            $new_flag->owner       = $current_user;
            $new_flag->location_id = null;
            $new_flag->floor       = null;
            
            if (isset($parameters['location_id'])) {
                $location = Model_Location::find($parameters['location_id']);

                if (is_null($location)) {
                    return $this->_error_response(Code::ERROR_INVALID_MALL_ID);
                }
                
                $new_flag->location      = $location;
                $new_flag->location_type = $location->type;
                $new_flag->floor         = (isset($parameters['floor'])) ? $parameters['floor'] : null;
            }
            
            if (isset($parameters['private']) && $parameters['private']) {
                $new_flag->private = true;
                
                if (isset($parameters['invited_users']) && is_array($parameters['invited_users'])) {
                    $users = Model_User::query()->where(array('email', 'in', $parameters['invited_users']))->get();
                    
                    foreach ($users as $user) {
                        
                        if ($user->apn_token) {
                            $text = $current_user->get_friendly_name() . " has invited you to a flag";
                            $custom_properties = array('id' => $new_flag->id);
                            if ($new_flag->mall_id) {
                                $custom_properties['mall_id'] = $new_flag->mall_id;
                            }
                            Helper_Apn::send_notification($user, $text, $custom_properties);
                        }
                        
                        $new_flag->invited_users[] = $user;
                    }
                }
                
            } else {
                $new_flag->private =false;
            }
            
            if (isset($parameters['image'])) {
                $image_uri = Helper_Images_Flags::copy_one_image_from_params($parameters['image']);
                if ($image_uri) {
                    $new_flag->image_uri = $image_uri;
                } else if (!is_null($this->_invalid_error_code)) {
                    // Error uploading image
                    return $this->_error_response(Code::ERROR_INVALID_FLAG_IMAGE);
                }
            }
            
            // check if the coordinates are valid
            if (!$this->_check_coordinates_support($new_flag)) {
                return $this->_error_response(Code::ERROR_FLAG_UNSUPPORTED_POSITION);
            }

            if ($new_flag->save()) {
                $image_url = Asset::get_file('large_' . $new_flag->image_uri, 'img', Config::get('cms.flag_images_path'));
                $image_uri = $image_url != false ? $image_url : '';
        
    			$data = array(
					'data' => array('success' => true, 'id' => $new_flag->id, 'image' => $image_uri),
					'meta' => array('error' => '', 'status' => 1)
				);

                Helper_Activity::log_activity($current_user, 'create_flag', array('flag_id' => (int)$new_flag->id));
				
			} else {
				$meta = Helper_Api::build_error_meta(Code::ERROR_FLAG_SAVE_ERROR);
				$data = array('data' => $parameters, 'meta' => $meta);
			}
        } else {
            $meta = Helper_Api::build_error_meta($this->_invalid_error_code, array('parameter' => array('name' => $this->_invalid_field, 'value' => @$params[$this->_invalid_field])));
			$data = array('data' => $parameters, 'meta' => $meta);
		}
    		
		$this->response($data);
	}
    
    private function _validate_flag_params(array $params = array()) {
        
        if (!isset($params['title'])) {
            $this->_invalid_field      = 'title';
            $this->_invalid_error_code = Code::ERROR_INVALID_TITLE;
            
            return false;
        }
        
        
        if (!isset($params['type']) || (isset($params['type']) && !in_array($params['type'], Model_Flag::get_valid_types()))) {
            $this->_invalid_field = 'type';
            $this->_invalid_error_code = Code::ERROR_INVALID_FLAG_TYPE;
            
            return false;
        }
        
        if (!isset($params['latitude'])) {
            $this->_invalid_field = 'latitude';
            $this->_invalid_error_code = Code::ERROR_INVALID_FLAG_LOCATION;
            
            return false;
        }
        
        if (!isset($params['longitude'])) {
            $this->_invalid_field = 'longitude';
            $this->_invalid_error_code = Code::ERROR_INVALID_FLAG_LOCATION;
            
            return false;
        }
        
		return true;
    }
    
    private function _check_coordinates_support($flag) {
        if ($flag->longitude && $flag->latitude) {
            $radius = Controller_Api_Location::DEFAULT_RADIUS;
            $nearby_locations_ids = Model_Location::get_nearby_location_ids($flag->latitude, $flag->longitude, $radius, null);
            return count($nearby_locations_ids) > 0;
        }
        return true;
    }
}
