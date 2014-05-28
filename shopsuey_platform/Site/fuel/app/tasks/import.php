<?php

namespace Fuel\Tasks;

class Import
{
    private static $_create_submerchants;
    private static $_days = array(1 => 'mon', 2 => 'tue', 3 => 'wed', 4 => 'thr', 5 => 'fri', 6 => 'sat', 7 => 'sun');
    private static $_csv_report;
    private static $_csv_report_row;
    
	public static function run($args = null)
	{
	    echo "Nothing over here\n";
	}

	public static function micello_csv($path, $csv_report, $create_submerchants = false)
	{
	    // To prevent buffering of the output
	    ob_end_flush();
	    
	    self::$_create_submerchants = $create_submerchants ? TRUE : FALSE;
	    
	    if (!file_exists($path)) {
	        exit("The path '$path' does not exists\n\n");
	    }
	    
	    if (!is_readable($path)) {
	        exit("The path '$path' is not readable by current user\n\n");
	    }
	    
	    $files = array();
	    if (is_file($path)) {
	        $files[] = $path;
	    } elseif (is_dir($path)) {
	        foreach (scandir($path) as $file) {
	            if (preg_match('/\.csv$/i', $file)) {
	                $files[] = "$path/$file";
	            }
	        }
	    } else {
	        exit("The path '$path' does not seem to be a valid resource\n\n");
	    }
	    
	    if ((self::$_csv_report = fopen($csv_report, 'w')) === FALSE) {
	        exit("The file '$csv_report' could not be opened for writting\n\n");
	    }

	    self::_add_header_to_csv_report();
	    
	    self::_process_micello_csv_files($files);
	    
	    fclose(self::$_csv_report);
	}
	
	private static function _process_micello_csv_files($files) {
	    foreach($files as $file) {
	        if (($handle = fopen($file, "r")) === FALSE) {
	            echo "There was an error while opening '$file'. Skipping...\n";
	            continue;
	        }
	        // Discard first row
	        fgetcsv($handle, 1000, ",");
	        $row_number = 2;

            while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                
                self::$_csv_report_row = array();
                
                $data = new \stdClass();
                $data->id = $row[0];
                $data->name = $row[1];
                $data->ct = $row[2];
                $data->street1 = $row[3];
                $data->street2 = $row[4];
                $data->city = $row[5];
                $data->state = $row[6];
                $data->country = $row[7];
                $data->zipcode = $row[8];
                $data->lat = $row[9];
                $data->lon = $row[10];
                
                if ($data->ct == 'Shopping Mall') {
                    $result = self::_load_mall($data);
                } elseif($data->ct == 'Retail')  {
                    $result = self::_load_standalone_merchant($data);
                }
                
                if (!$result) {
                    echo \Cli::color("There was an error while processing row '$row_number' from '$file'\n\n", 'red');
                } else {
                    echo \Cli::color("Added/updated entity '{$data->name}' at row '$row_number' from '$file'\n", 'white');
                    self::_add_row_to_csv_report();
                    
                    if ($data->ct == 'Shopping Mall') {
                        if ($result && self::$_create_submerchants) {
                            self::_load_merchants_for_mall($data, $result);
                        }
                    }
                }

                $row_number++;
            }

            fclose($handle);
	    }
	}

	private static function _add_header_to_csv_report() {
	    $csv_row = array(
            'Micello Id', 
            'Entity Id', 
            'Geometry Id',
            'Micello Name',
            'Status',
            'ShopSuey Id',
            'Fields updated from Micello data',
            'Foursquare Venue found',
            'Fields updated from Foursquare venue',
            'Yelp business found',
            'Fields updated from Yelp business',
            'Fields updated from parent mall',
            'Micello entity found',
            'Fields updated from Micello entity',
            'Old name',
            'New name',
            'Old address',
            'New address',
            'Old city',
            'New city',
            'Old st',
            'New st',
            'Old zip',
            'New zip',
            'Old latitude',
            'New latitude',
            'Old longitude',
            'New longitude',
            'Old phone',
            'New phone',
            'Old twitter',
            'New twitter',
            'Old web',
            'New web',
            'Old email',
            'New email',
            'Old description',
            'New description',
            'Old hours',
            'New hours',
            'Old floor',
            'New floor',
	    );
	    
	    fputcsv(self::$_csv_report, $csv_row);
	}
	
	private static function _add_row_to_csv_report() {
	    $csv_row = array(
            isset(self::$_csv_report_row['micello_id']) ? self::$_csv_report_row['micello_id'] : '', 
            isset(self::$_csv_report_row['entity_id']) ? self::$_csv_report_row['entity_id'] : '', 
            isset(self::$_csv_report_row['geometry_id']) ? self::$_csv_report_row['geometry_id'] : '',
            self::$_csv_report_row['micello_name'],
            self::$_csv_report_row['status'],
            isset(self::$_csv_report_row['shopsuey_id']) ? self::$_csv_report_row['shopsuey_id'] : '',
            self::$_csv_report_row['micello_updated_fields'],
            isset(self::$_csv_report_row['foursquare_info']) ? self::$_csv_report_row['foursquare_info'] : 'Not searched',
            isset(self::$_csv_report_row['foursquare_updated_fields']) ? self::$_csv_report_row['foursquare_updated_fields'] : '',
            isset(self::$_csv_report_row['yelp_info']) ? self::$_csv_report_row['yelp_info'] : 'Not searched',
            isset(self::$_csv_report_row['yelp_updated_fields']) ? self::$_csv_report_row['yelp_updated_fields'] : '',
            isset(self::$_csv_report_row['updated_fields_from_parent_mall']) ? self::$_csv_report_row['updated_fields_from_parent_mall'] : '',
            isset(self::$_csv_report_row['micello_entity_info']) ? self::$_csv_report_row['micello_entity_info'] : 'N/A',
            isset(self::$_csv_report_row['micello_entity_updated_fields']) ? self::$_csv_report_row['micello_entity_updated_fields'] : 'N/A',
            self::$_csv_report_row['old_name'],
            isset(self::$_csv_report_row['new_name']) ? self::$_csv_report_row['new_name'] : self::$_csv_report_row['old_name'],
            self::$_csv_report_row['old_address'],
            isset(self::$_csv_report_row['new_address']) ? self::$_csv_report_row['new_address'] : self::$_csv_report_row['old_address'],
            self::$_csv_report_row['old_city'],
            isset(self::$_csv_report_row['new_city']) ? self::$_csv_report_row['new_city'] : self::$_csv_report_row['old_city'],
            self::$_csv_report_row['old_st'],
            isset(self::$_csv_report_row['new_st']) ? self::$_csv_report_row['new_st'] : self::$_csv_report_row['old_st'],
            self::$_csv_report_row['old_zip'],
            isset(self::$_csv_report_row['new_zip']) ? self::$_csv_report_row['new_zip'] : self::$_csv_report_row['old_zip'],
            self::$_csv_report_row['old_latitude'],
            isset(self::$_csv_report_row['new_latitude']) ? self::$_csv_report_row['new_latitude'] : self::$_csv_report_row['old_latitude'],
            self::$_csv_report_row['old_longitude'],
            isset(self::$_csv_report_row['new_longitude']) ? self::$_csv_report_row['new_longitude'] : self::$_csv_report_row['old_longitude'],
            self::$_csv_report_row['old_phone'],
            isset(self::$_csv_report_row['new_phone']) ? self::$_csv_report_row['new_phone'] : self::$_csv_report_row['old_phone'],
            self::$_csv_report_row['old_twitter'],
            isset(self::$_csv_report_row['new_twitter']) ? self::$_csv_report_row['new_twitter'] : self::$_csv_report_row['old_twitter'],
            self::$_csv_report_row['old_web'],
            isset(self::$_csv_report_row['new_web']) ? self::$_csv_report_row['new_web'] : self::$_csv_report_row['old_web'],
            self::$_csv_report_row['old_email'],
            isset(self::$_csv_report_row['new_email']) ? self::$_csv_report_row['new_email'] : self::$_csv_report_row['old_email'],
            self::$_csv_report_row['old_description'],
            isset(self::$_csv_report_row['new_description']) ? self::$_csv_report_row['new_description'] : self::$_csv_report_row['old_description'],
            self::$_csv_report_row['old_hours'],
            isset(self::$_csv_report_row['new_hours']) ? self::$_csv_report_row['new_hours'] : self::$_csv_report_row['old_hours'],
            self::$_csv_report_row['old_floor'],
            isset(self::$_csv_report_row['new_floor']) ? self::$_csv_report_row['new_floor'] : self::$_csv_report_row['old_floor'],
	    );
	    
	     
	    fputcsv(self::$_csv_report, $csv_row);
	}
	
	private static function _load_mall($data) {
	    echo \Cli::color("\nStarted processing of micello community '{$data->name}'\n", 'purple');
        self::$_csv_report_row['micello_id'] = $data->id;
	    self::$_csv_report_row['micello_name'] = $data->name;
	    
	    // Check if micello id already exists. Create new mall/micello info otherwise
	    $micello_infos = \Model_Micello_Info::query()
	        ->related('location')
	        ->where('micello_id', $data->id)
	        ->where('type', \Model_Micello_Info::TYPE_COMMUNITY)
	        ->where('location.status', '>', '0')
	        ->get();
	    
	    if (count($micello_infos) == 0) {
            echo \Cli::color("Creating new entry on the locations database\n", 'white');
	        self::$_csv_report_row['status'] = 'New entry';
            $mall = new \Model_Mall();
	        
	        $mall->name = '';
	        $mall->address = '';
	        $mall->city = '';
	        $mall->st = '';
	        $mall->zip = '';
	        $mall->latitude = '';
	        $mall->longitude = '';
	         
	        $mall->status = 1;
//	        $mall->is_customer = 1;
	        $mall->contact = '';
	        $mall->email = '';
	        $mall->phone = '';
	        $mall->social = self::_new_social_object();
	        $mall->web = '';
	        $mall->newsletter = '';
	        $mall->tags = '';
	        $mall->plan = '0';
	        $mall->max_users = '0';
	        $mall->content = '';
	        $mall->hours = self::_new_hours_array();
	        $mall->logo = '';
	        $mall->description = '';
	        $mall->wifi = '';
	        $mall->market_place_type = '';
	        $mall->auto_generated = 1;
	        $mall->setup_complete = 0;
	        $mall->manually_updated = 0;
	        $mall->created_by = $mall->edited_by = '0';
	        
	        $micello_info = new \Model_Micello_Info();
	        $micello_info->micello_id = $data->id;
	        $micello_info->type = \Model_Micello_Info::TYPE_COMMUNITY;
	        $mall->micello_info = $micello_info;
	    } else {
	        $micello_info = array_shift($micello_infos);
	        echo \Cli::color("Retrieving existing entry on the locations database\n", 'white');
	        self::$_csv_report_row['status'] = 'Existing entry';
	        self::$_csv_report_row['shopsuey_id'] = $micello_info->location_id;
	         
	        $mall = $micello_info->location;
	        if (!self::_valid_social_object($mall->social)) {
	            echo \Cli::color("Invalid social networks info, resetting to default (empty) object\n", 'red');
	            $mall->social = self::_new_social_object();
	        }
	        if (!self::_valid_hours_object($mall->hours)) {
	            echo \Cli::color("Invalid hours info, resetting to default (empty) times\n", 'red');
	            $mall->hours = self::_new_hours_array();
	        }
	    }
	    
	    self::_fill_previous_values($mall);
	    
	    $micello_fields_updated = array();
	    // Fill info from micello for mall. Only empty fields are set/updated
	    if (empty($mall->name) || !$mall->manually_updated) { $mall->name = $data->name; $micello_fields_updated[] = 'name'; self::$_csv_report_row['new_name'] = $mall->name; }
	    if (empty($mall->address) || !$mall->manually_updated) { $mall->address = $data->street1; $micello_fields_updated[] = 'address'; self::$_csv_report_row['new_address'] = $mall->address; }
	    if (empty($mall->city) || !$mall->manually_updated) { $mall->city = $data->city; $micello_fields_updated[] = 'city'; self::$_csv_report_row['new_city'] = $mall->city; }
	    if (empty($mall->st) || !$mall->manually_updated) { $mall->st = \Helper_Api::get_state_code($data->state); $micello_fields_updated[] = 'st'; self::$_csv_report_row['new_st'] = $mall->st; }
	    if (empty($mall->zip) || !$mall->manually_updated) { $mall->zip = $data->zipcode; $micello_fields_updated[] = 'zip'; self::$_csv_report_row['new_zip'] = $mall->zip; }
	    if (empty($mall->latitude) || !$mall->manually_updated) { $mall->latitude = $data->lat; $micello_fields_updated[] = 'latitude'; self::$_csv_report_row['new_latitude'] = $mall->latitude; }
	    if (empty($mall->longitude) || !$mall->manually_updated) { $mall->longitude = $data->lon; $micello_fields_updated[] = 'longitude'; self::$_csv_report_row['new_longitude'] = $mall->longitude; }

	    self::$_csv_report_row['micello_updated_fields'] = implode(', ', $micello_fields_updated);
	    
	    // Try to fetch data from foursquare. Fill additional info if found
	    $venue_full_info = self::_get_foursquare_info($mall, 'mall');

	    if (!is_null($venue_full_info)) {
	        echo \Cli::color("Filling info from foursquare venue called '{$venue_full_info->venue->name}' for mall\n", 'green');
	        self::$_csv_report_row['foursquare_info'] = 'Yes';
	        self::_fill_foursquare_info_into_location($venue_full_info, $mall);
	    } else {
	        self::$_csv_report_row['foursquare_info'] = 'No';
	        echo \Cli::color("Failed to get foursquare info for {$data->name}\n", 'red');
	        // If no data from foursquare is found, try yelp
	        $business_info = self::_get_yelp_info($mall, 'mall');
	        if (!is_null($business_info)) {
    	        self::$_csv_report_row['yelp_info'] = 'Yes';
	            echo \Cli::color("Filling info from yelp business called '{$business_info->name}' for mall\n", 'green');
	            self::_fill_yelp_info_into_location($business_info, $mall);
	        } else {
	            echo \Cli::color("Failed to get yelp info for {$data->name}\n", 'red');
    	        self::$_csv_report_row['yelp_info'] = 'No';
	        }
	    }

	    echo \Cli::color("Finished processing of micello community '{$data->name}'\n", 'purple');
	     
	    if ($mall->save()) {
            return $mall; 
	    } else {
	        return FALSE;
	    }
	}

	private static function _fill_previous_values($location) {
	    self::$_csv_report_row['old_name'] = $location->name;
	    self::$_csv_report_row['old_address'] = $location->address;
	    self::$_csv_report_row['old_city'] = $location->city;
	    self::$_csv_report_row['old_st'] = $location->st;
	    self::$_csv_report_row['old_zip'] = $location->zip;
	    self::$_csv_report_row['old_latitude'] = $location->latitude;
	    self::$_csv_report_row['old_longitude'] = $location->longitude;
	    self::$_csv_report_row['old_phone'] = $location->phone;
	    self::$_csv_report_row['old_twitter'] = isset($location->social) && isset($location->social->twitter) ? $location->social->twitter : '';
	    self::$_csv_report_row['old_web'] = $location->web;
	    self::$_csv_report_row['old_email'] = $location->email;
	    self::$_csv_report_row['old_description'] = $location->description;
	    self::$_csv_report_row['old_hours'] = json_encode($location->hours);
	    self::$_csv_report_row['old_floor'] = isset($location->floor) ? $location->floor : '';
	}
	
	private static function _load_merchants_for_mall($data, $mall) {
	    // Create merchants for mall if needed and copy info from mall for each one
        \Package::load('micello');
        try {
            $result = \Micello\Api::get_entities($data->id);
        } catch (\Micello\MicelloException $e) {
            echo "An error occurred while fetching entities for mall '{$data->name}' - " . $e->getMessage() . ' [' . $e->getCode() . "]\n";
            return TRUE;
        }
    
        $entities = $result->results;
        $added_merchants = 0;
        $active_geometry_ids = array();
    
        foreach($entities as $entity) {
            self::$_csv_report_row = array();
            if (self::_load_merchant($entity, $mall)) {
                $added_merchants++;
                $active_geometry_ids[] = $entity->gid;
                self::_add_row_to_csv_report();
            } else {
                echo "An error occurred while adding merchant '{$entity->nm}'\n";
            }
        }

        /*
         * The info returned by the micello API for the entities of a community is not consistent
         * with the info contained on the actual map, so this piece of code may delete valid merchants
         * not returned by the API (mainly empty stores or info not yet loaded on micello)
         * 
         */
        if (count($active_geometry_ids) > 0) {
            $inactive_merchants = \Model_Merchant::query()
                ->related('micello_info')
                ->where('micello_info.geometry_id', 'not in', $active_geometry_ids)
                ->where('micello_info.type', \Model_Micello_Info::TYPE_ENTITY)
                ->where('mall_id', $mall->id)
                ->where('status', '1')
                ->get();

            if (count($inactive_merchants) > 0) {
                echo \Cli::color(count($inactive_merchants) ." active merchants are not present on micello's response for mall '{$data->name}'\n", 'blue');
    
                $inactive_merchant_ids = array();
                // TODO: Set as "In Review" or something similar
                foreach($inactive_merchants as $inactive_merchant) {
                    $inactive_merchant_ids[] = $inactive_merchant->id;
    //                 $inactive_merchant->status = 0;
    //                 $inactive_merchant->save();
                }
                
                echo \Cli::color("The active merchants with ids " . implode(', ', $inactive_merchant_ids) ." are 'tainted' for mall '{$data->name}'\n", 'blue');
            }
        }
        
        echo \Cli::color("$added_merchants merchants were added/updated to/from mall '{$data->name}'\n", 'blue');
	}
	
	private static function _new_social_object() {
        $social = new \stdClass();
        $social->twitter = '';
        $social->facebook = '';
        $social->foursquare = '';
        $social->pintrest = '';
        return $social;
	}

	private static function _new_hours_array() {
	    $hours = array();
	    foreach (self::$_days as $key => $name) {
	        $hours[$name] = array('open' => '', 'close' => '');
	    }
        return $hours;
	}
	
	private static function _valid_social_object($social) {
	    return !is_null($social)
    	    && is_object($social)
    	    && isset($social->twitter)
    	    && isset($social->facebook)
    	    && isset($social->foursquare)
    	    && isset($social->pintrest);
	}
	
	private static function _valid_hours_object($hours) {
	    $hours = (array)$hours;
	    foreach (self::$_days as $key => $name) {
	        if (isset($hours[$name])) {
	            $day = (array)$hours[$name];
	            if (!isset($day['open']) || !isset($day['close'])) {
	                return FALSE;
	            }
	        } else {
	            return FALSE;
	        }
	    }
	    return TRUE;
	}

	private static function _empty_hours_object($hours) {
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
	
	private static function _get_foursquare_info($location, $type) {
	    static $categories = array(
            'mall' => array('Mall'),
            'standalone_merchant' => array(
                'Hardware Store', 'Furniture / Home Store', 'Electronics Store',
                'Department Store', 'Grocery Store', 'Bar', 'Miscellaneous Shop',
            ),
        );
	    
	    \Package::load('foursquare');
	    
	    try {
	        $venues = \Foursquare\Api::search_exact_venue($location->latitude, $location->longitude, $location->name);
        } catch (\Foursquare\FoursquareException $e) {
            echo \Cli::color("Communication error when searching foursquare info\n", 'red');
            return NULL;
        }
	         
        if (!isset($venues->venues)) {
            return NULL;
        }
        
	    if ($type == 'standalone_merchant' && preg_match('/.*-.*/', $location->name)) {
	        list($location_name, $location_geo) = explode('-', $location->name);
	    } else {
	        $location_name = $location->name;
	    }
	    
	    $allowed_categories = $categories[$type];
	    // Distance should be 500 meters as most
	    $min_distance = 500;
	    $min_similarity_percent = 80;
	    $venue_match = NULL;
	    foreach($venues->venues as $venue) {
	        $valid_category = FALSE;
	        foreach ($venue->categories as $category) {
	            echo \Cli::color("Comparing with foursquare venue: {$venue->name} => {$category->name}\n", 'cyan');
	            if (in_array($category->name, $allowed_categories)) {
	                $valid_category = TRUE;
	                break;
	            }
	        }
	        // similar_text function returns different values when the parameters are passed in different order
	        similar_text($location_name, $venue->name, $similarity_percent1);
	        similar_text($venue->name, $location_name, $similarity_percent2);
	        $similarity_percent = max($similarity_percent1, $similarity_percent2);
	        if ($valid_category && isset($venue->location->distance) && $venue->location->distance < $min_distance && $similarity_percent >= $min_similarity_percent) {
	            $min_distance = $venue->location->distance;
	            $venue_match = $venue;
	        }
	    }
	    
	    if (!is_null($venue_match)) {
	        try {
	            $venue_full_info = \Foursquare\Api::get_venue($venue_match->id);
            } catch (\Foursquare\FoursquareException $e) {
                echo \Cli::color("Communication error when fetching foursquare venue info\n", 'red');
                return $venue_match;
            }

            try {
	            $venue_full_info->hours = self::_process_foursquare_hours(\Foursquare\Api::get_venue_hours($venue_match->id));
            } catch (\Foursquare\FoursquareException $e) {
                echo \Cli::color("Communication error when fetching foursquare venue hours\n", 'red');
                $venue_full_info->hours = NULL;
            }
	             
    	    return $venue_full_info;
	    } else {
	        return NULL;
	    }
	}
	
	private static function _process_foursquare_hours($foursquare_hours) {
	    $hours = self::_new_hours_array();
	
	    if (!isset($foursquare_hours->hours) || !isset($foursquare_hours->hours->timeframes)) {
	        echo \Cli::color("Failed to get hours info from foursquare\n", 'red');
	        return $hours;
	    }
	     
	    foreach($foursquare_hours->hours->timeframes as $time_frame) {
	        $open_times = array_shift($time_frame->open);
	        $times = array(
                'open' => date('h:iA', strtotime($open_times->start)),
                'close' => date('h:iA', strtotime($open_times->end)),
	        );
	        foreach($time_frame->days as $key => $number) {
	            $hours[self::$_days[$number]]['open'] = $times['open'];
	            $hours[self::$_days[$number]]['close'] = $times['close'];
	        }
	    }
	
	    return $hours;
	}
	
	private static function _get_yelp_info($location, $type) {
	    \Package::load('yelp');

	    try {
	        $result = \Yelp\Api::search_businesses_by_term_and_location($location->name, $location->latitude, $location->longitude);
        } catch (\Yelp\YelpException $e) {
            echo \Cli::color("Communication error when searchig for yelp info\n", 'red');
            return NULL;
        }

        if (!isset($result->businesses)) {
            return NULL;
        }
        
	    if ($type == 'standalone_merchant' && preg_match('/.*-.*/', $location->name)) {
	        list($location_name, $location_geo) = explode('-', $location->name);
	    } else {
	        $location_name = $location->name;
	    }
	    
	    // Distance should be 500 meters as most
	    $min_distance = 500;
	    $min_similarity_percent = 80;
	    $business_match = NULL;
	    foreach($result->businesses as $business) {
	        // similar_text function returns different values when the parameters are passed in different order
	        similar_text($location_name, $business->name, $similarity_percent1);
	        similar_text($business->name, $location_name, $similarity_percent2);
	        $similarity_percent = max($similarity_percent1, $similarity_percent2);
	        if (isset($business->distance) && $business->distance < $min_distance && $similarity_percent >= $min_similarity_percent) {
	            $min_distance = $business->distance;
	            $business_match = $business;
	        }
	    }

	    return $business_match;
	}
	
	private static function _fill_foursquare_info_into_location($venue_full_info, &$location) {
	    $foursquare_fields_filled = array();
	    
	    if (isset($venue_full_info->venue->contact->formattedPhone) && (empty($location->phone) || !$location->manually_updated)) {
	        $location->phone = $venue_full_info->venue->contact->formattedPhone;
	        $foursquare_fields_filled[] = 'phone';
	        self::$_csv_report_row['new_phone'] = $location->phone;
	    }
	    if (isset($venue_full_info->venue->contact->twitter) && (empty($location->social->twitter) || !$location->manually_updated)) {
	        $location->social->twitter = "https://twitter.com/" . $venue_full_info->venue->contact->twitter;
	        $foursquare_fields_filled[] = 'social[twitter]';
	        self::$_csv_report_row['new_twitter'] = $location->social->twitter;
	    }
	    if (isset($venue_full_info->venue->url) && (empty($location->web) || !$location->manually_updated)) {
	        $location->web = $venue_full_info->venue->url;
	        $foursquare_fields_filled[] = 'web';
	        self::$_csv_report_row['new_web'] = $location->web;
	    }
	    if (isset($venue_full_info->venue->description) && (empty($location->description) || !$location->manually_updated)) {
	        $location->description = $venue_full_info->venue->description;
	        $foursquare_fields_filled[] = 'description';
	        self::$_csv_report_row['new_description'] = $location->description;
	    }
// 	    if (isset($venue_full_info->venue->tags) && empty($location->tags)) {
// 	        $location->tags = $venue_full_info->venue->tags;
// 	    }
        if ((self::_empty_hours_object($location->hours) || !$location->manually_updated) && !self::_empty_hours_object($venue_full_info->hours)) {
            $location->hours = $venue_full_info->hours;
            $foursquare_fields_filled[] = 'hours';
            self::$_csv_report_row['new_hours'] = json_encode($location->hours);
        }

        self::$_csv_report_row['foursquare_updated_fields'] = implode(', ', $foursquare_fields_filled);
	}

	private static function _fill_yelp_info_into_location($business_info, &$location) {
	    $yelp_fields_filled = array();
	     
	    if (isset($business_info->display_phone) && (empty($location->phone) || !$location->manually_updated)) {
	        $location->phone = $business_info->display_phone;
	        $yelp_fields_filled[] = 'phone';
	        self::$_csv_report_row['new_phone'] = $location->phone;
	    }
	    
	    self::$_csv_report_row['yelp_updated_fields'] = implode(', ', $yelp_fields_filled);
	}
	
	private static function _load_standalone_merchant($data) {
	    echo \Cli::color("\nStarted processing of micello community '{$data->name}'\n", 'purple');
	    self::$_csv_report_row['micello_id'] = $data->id;
	    self::$_csv_report_row['micello_name'] = $data->name;

	    // Check if micello id already exists. Create new mall/micello info otherwise
	    $micello_infos = \Model_Micello_Info::query()
	        ->related('location')
    	    ->where('micello_id', $data->id)
    	    ->where('type', \Model_Micello_Info::TYPE_COMMUNITY)
    	    ->where('location.status', '>', '0')
    	    ->get();

	    if (count($micello_infos) == 0) {
	        echo \Cli::color("Creating new entry on the locations database\n", 'white');
	        self::$_csv_report_row['status'] = 'New entry';

	        $merchant = new \Model_Merchant();
	        $merchant->name = '';
	        $merchant->status = 1;
// 	        $merchant->is_customer = 1;
	        $merchant->mall_id = NULL;
	        $merchant->floor = NULL;
	        $merchant->address = '';
	        $merchant->city = '';
	        $merchant->st = '';
	        $merchant->zip = '';
	        $merchant->contact = '';
	        $merchant->email = '';
	        $merchant->phone = '';
	        $merchant->social = self::_new_social_object();
	        $merchant->web = '';
	        $merchant->newsletter = '';
	        $merchant->tags = '';
	        $merchant->content = '';
	        $merchant->hours = self::_new_hours_array();
	        $merchant->logo = '';
	        $merchant->max_users = '0';
	        $merchant->plan = '0';
	        $merchant->latitude = '';
	        $merchant->longitude = '';
	        $merchant->description = '';
	        $merchant->auto_generated = 1;
	        $merchant->setup_complete = 0;
	        $merchant->manually_updated = 0;
	        $merchant->created_by = $merchant->edited_by = '0';

	        $micello_info = new \Model_Micello_Info();
	        $micello_info->micello_id = $data->id;
	        $micello_info->type = \Model_Micello_Info::TYPE_COMMUNITY;
	        $merchant->micello_info = $micello_info;
	    } else {
	        $micello_info = array_shift($micello_infos);
	        $merchant = $micello_info->location;
	        
	        self::$_csv_report_row['status'] = 'Existing entry';
	        self::$_csv_report_row['shopsuey_id'] = $micello_info->location_id;

	        if (!self::_valid_social_object($merchant->social)) {
	            echo \Cli::color("Invalid social networks info, resetting to default (empty) object\n", 'red');
	            $merchant->social = self::_new_social_object();
	        }
	        if (!self::_valid_hours_object($merchant->hours)) {
	            echo \Cli::color("Invalid hours info, resetting to default (empty) times\n", 'red');
	            $merchant->hours = self::_new_hours_array();
	        }
	    }

	    self::_fill_previous_values($merchant);
	     
	    $micello_fields_updated = array();
	    // Fill info from micello for merchant. Only empty fields are set/updated
	    if (empty($merchant->name) || !$merchant->manually_updated) { $merchant->name = $data->name; $micello_fields_updated[] = 'name'; self::$_csv_report_row['new_name'] = $merchant->name; }
	    if (empty($merchant->address) || !$merchant->manually_updated) { $merchant->address = $data->street1; $micello_fields_updated[] = 'address'; self::$_csv_report_row['new_address'] = $merchant->address; }
	    if (empty($merchant->city) || !$merchant->manually_updated) { $merchant->city = $data->city; $micello_fields_updated[] = 'city'; self::$_csv_report_row['new_city'] = $merchant->city; }
	    if (empty($merchant->st) || !$merchant->manually_updated) { $merchant->st = \Helper_Api::get_state_code($data->state); $micello_fields_updated[] = 'st'; self::$_csv_report_row['new_st'] = $merchant->st; }
	    if (empty($merchant->zip) || !$merchant->manually_updated) { $merchant->zip = $data->zipcode; $micello_fields_updated[] = 'zip'; self::$_csv_report_row['new_zip'] = $merchant->zip; }
	    if (empty($merchant->latitude) || !$merchant->manually_updated) { $merchant->latitude = $data->lat; $micello_fields_updated[] = 'latitude'; self::$_csv_report_row['new_latitude'] = $merchant->latitude; }
	    if (empty($merchant->longitude) || !$merchant->manually_updated) { $merchant->longitude = $data->lon; $micello_fields_updated[] = 'longitude'; self::$_csv_report_row['new_longitude'] = $merchant->longitude; }
	    
	    self::$_csv_report_row['micello_updated_fields'] = implode(', ', $micello_fields_updated);
	     
	    // Try to fetch data from foursquare. Fill additional info if found
	    $venue_full_info = self::_get_foursquare_info($merchant, 'standalone_merchant');
	    
	    if (!is_null($venue_full_info)) {
	        echo \Cli::color("Filling info from foursquare venue called '{$venue_full_info->venue->name}' for merchant\n", 'green');
	        self::$_csv_report_row['foursquare_info'] = 'Yes';
	        self::_fill_foursquare_info_into_location($venue_full_info, $merchant);
	    } else {
	        self::$_csv_report_row['foursquare_info'] = 'No';
	        echo \Cli::color("Failed to get foursquare info for {$data->name}\n", 'red');
	        // If no data from foursquare is found, try yelp
	        $business_info = self::_get_yelp_info($merchant, 'standalone_merchant');
	        if (!is_null($business_info)) {
	            self::$_csv_report_row['yelp_info'] = 'Yes';
	            echo \Cli::color("Filling info from yelp business called '{$business_info->name}' for merchant\n", 'green');
	            self::_fill_yelp_info_into_location($business_info, $merchant);
	        } else {
	            echo \Cli::color("Failed to get yelp info for {$data->name}\n", 'red');
	            self::$_csv_report_row['yelp_info'] = 'No';
	        }
	    }
	     
	    echo \Cli::color("Finished processing of micello community '{$data->name}'\n", 'purple');

	    return $merchant->save();
	}
	
	private static function _load_merchant($entity, $mall) {
	    self::$_csv_report_row['entity_id'] = $entity->eid;
	    self::$_csv_report_row['geometry_id'] = $entity->gid;
	    self::$_csv_report_row['micello_name'] = $entity->nm;
	     
	    // Check if micello id already exists. Create new merchant/micello info otherwise
	    $micello_infos = \Model_Micello_Info::query()
	        ->related('location')
    	    ->where('geometry_id', $entity->gid)
    	    ->where('type', \Model_Micello_Info::TYPE_ENTITY)
    	    ->where('location.status', '>', '0')
    	    ->get();

	    if (count($micello_infos) == 0) {
	        self::$_csv_report_row['status'] = 'New entry';

	        $merchant = new \Model_Merchant();

	        $merchant->mall_id = '';
	        $merchant->name = '';
	        $merchant->floor = '';

	        $merchant->status = 1;
// 	        $merchant->is_customer = 1;
	        $merchant->address = '';
	        $merchant->city = '';
	        $merchant->st = '';
	        $merchant->zip = '';
	        $merchant->contact = '';
	        $merchant->email = '';
	        $merchant->phone = '';
	        $merchant->social = self::_new_social_object();
	        $merchant->web = '';
	        $merchant->newsletter = '';
	        $merchant->tags = '';
	        $merchant->content = '';
	        $merchant->hours = self::_new_hours_array();;
	        $merchant->logo = '';
	        $merchant->max_users = '0';
	        $merchant->plan = '0';
	        $merchant->auto_generated = 1;
	        $merchant->setup_complete = 0;
	        $merchant->manually_updated = 0;
	        $merchant->created_by = $merchant->edited_by = '0';

	        $micello_info = new \Model_Micello_Info();
	        $micello_info->type = \Model_Micello_Info::TYPE_ENTITY;
	        $micello_info->geometry_id = $entity->gid;
	        $merchant->micello_info = $micello_info;
	    } else {
	        $micello_info = array_shift($micello_infos);
	        self::$_csv_report_row['status'] = 'Existing entry';
	        self::$_csv_report_row['shopsuey_id'] = $micello_info->location_id;
	         
	        $merchant = $micello_info->location;
	        if (!self::_valid_social_object($merchant->social)) {
	            echo \Cli::color("Invalid social networks info, resetting to default (empty) object\n", 'red');
	            $merchant->social = self::_new_social_object();
	        }
	        if (!self::_valid_hours_object($merchant->hours)) {
	            echo \Cli::color("Invalid hours info, resetting to default (empty) times\n", 'red');
	            $merchant->hours = self::_new_hours_array();
	        }
	    }

	    // This is done this way because there might be manually loaded merchants for which the only
	    // correct info is the geometry id
	    $micello_info->micello_id = $entity->eid;

	    self::_fill_previous_values($merchant);

	    // Force mall id
	    $merchant->mall_id = $mall->id;

	    $micello_fields_updated = array();

	    // Fill info from micello for mall. Only empty fields are set/updated
	    if (empty($merchant->name) || !$merchant->manually_updated) { $merchant->name = $entity->nm; $micello_fields_updated[] = 'name'; self::$_csv_report_row['new_name'] = $merchant->name; }
	    if (empty($merchant->floor) || !$merchant->manually_updated) { $merchant->floor = $entity->lnm; $micello_fields_updated[] = 'floor'; self::$_csv_report_row['new_floor'] = $merchant->floor; }

	    self::$_csv_report_row['micello_updated_fields'] = implode(', ', $micello_fields_updated);

	    $merchant_fields_updated = array();

	    // Fill info from mall. Only empty fields are set/updated
	    if (empty($merchant->address) || !$merchant->manually_updated) { $merchant->address = $mall->address; $merchant_fields_updated[] = 'address';  self::$_csv_report_row['new_address'] = $mall->address; }
	    if (empty($merchant->city) || !$merchant->manually_updated) { $merchant->city = $mall->city; $merchant_fields_updated[] = 'city'; self::$_csv_report_row['new_city'] = $mall->city; }
	    if (empty($merchant->st) || !$merchant->manually_updated) { $merchant->st = $mall->st; $merchant_fields_updated[] = 'st'; self::$_csv_report_row['new_st'] = $mall->st; }
	    if (empty($merchant->zip) || !$merchant->manually_updated) { $merchant->zip = $mall->zip; $merchant_fields_updated[] = 'zip'; self::$_csv_report_row['new_zip'] = $mall->zip; }
	    if (empty($merchant->latitude) || !$merchant->manually_updated) { $merchant->latitude = $mall->latitude; $merchant_fields_updated[] = 'latitude'; self::$_csv_report_row['new_latitude'] = $mall->latitude; }
	    if (empty($merchant->longitude) || !$merchant->manually_updated) { $merchant->longitude = $mall->longitude; $merchant_fields_updated[] = 'longitude'; self::$_csv_report_row['new_longitude'] = $mall->longitude; }
	    if ((self::_empty_hours_object($merchant->hours) || !$merchant->manually_updated) && !self::_empty_hours_object($mall->hours)) {
    	    $merchant->hours = $mall->hours;
    	    $merchant_fields_updated[] = 'hours';
    	    self::$_csv_report_row['new_hours'] = json_encode($mall->hours);
	    }

	    self::$_csv_report_row['updated_fields_from_parent_mall'] = implode(', ', $merchant_fields_updated);

	    // Fetch info from micello
	    $micello_entity_info = self::_get_micello_entity_info($entity->eid);
	    
	    if (!is_null($micello_entity_info)) {
	        self::$_csv_report_row['micello_entity_info'] = 'Yes';
	        self::_fill_micello_info_into_merchant($micello_entity_info, $merchant);
	    } else {
	        self::$_csv_report_row['micello_entity_info'] = 'No';
	        echo \Cli::color("Failed to get micello entity info for entity id {$entity->eid}\n", 'red');
	    }
	    
        return $merchant->save();
	}
	
	private static function _get_micello_entity_info($entity_id) {
	    \Package::load('micello');
	    try {
	        $entity_info = \Micello\Api::get_entity_info($entity_id);
	    } catch (\Micello\MicelloException $e) {
	        echo \Cli::color("Communication error when searching micello info\n", 'red');
	        return NULL;
	    }
	    return $entity_info;
	}
	
	private static function _fill_micello_info_into_merchant($entity_info, &$merchant) {
	    $micello_fields_filled = array();

	    if (isset($entity_info->results)) {
    	    foreach ($entity_info->results as $info_field) {
    	        $field = NULL;
    	        switch($info_field->name) {
    	            case 'phone':
    	                $field = 'phone';
    	                break;
                    case 'email':
                        $field = 'email';
                        break;
	                case 'url':
                        $field = 'web';
                        break;
                    case 'description':
                        $field = 'description';
                        break;
                    default:
                        echo \Cli::color("Unknow field info for micello entity!!\n", 'red');
                        print_r($info_field);
//                         exit();
    	        }
    	        if (!is_null($field) && (empty($merchant->$field) || !$merchant->manually_updated)) {
        	        $merchant->$field = $info_field->value;
        	        $micello_fields_filled[] = $field;
        	        self::$_csv_report_row["new_$field"] = $merchant->$field;
    	        }
    	    }
	    }
	    	    
	    self::$_csv_report_row['micello_entity_updated_fields'] = implode(', ', $micello_fields_filled);
	}
	
}
