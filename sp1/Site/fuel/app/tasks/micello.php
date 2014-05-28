<?php

namespace Fuel\Tasks;

class Micello
{
    public static function run($args = null)
    {
    	echo "Nothing over here\n";
    }
    
    /**
     * Scan all marketplaces and for those that are empty, i.e., they have no
     * merchants configured, it creates the merchants using Micello's API
     */
    public static function fill_empty_marketplaces() {
        // To prevent buffering of the output
        ob_end_flush();

        $update_existing = FALSE;
        
        $malls = \Model_Mall::query()
            ->where('status', '>', '0')
            ->get();

        $malls_count = count($malls);
        $mall_index = 1;
        $location_ids = array_keys($malls);
        
        for($i = 0; $i < count($location_ids); $i++) {
            $location_id = $location_ids[$i];
            $mall = $malls[$location_id];
            
            // Flush model cache periodically
            if ($mall_index % 5 == 0) {
                \Model_Mall::flush_cache();
            }
            
            echo \Cli::color('(' . $mall_index++ . ' / ' . $malls_count . ') ', 'blue');
            
            $active_merchants_count = \Model_Merchant::query()
                ->where('mall_id', $mall->id)
                ->where('status', '>', 0)
                ->count();
            
            $micello_info_count = \Model_Micello_Info::query()
                ->where('location_id', $mall->id)
                ->count();
            
            if ($micello_info_count > 0 && $active_merchants_count == 0) {
                echo \Cli::color("Adding merchants for empty marketplace '{$mall->name}' ({$mall->id})\n", 'blue');
                self::load_merchants_for_mall($mall, $update_existing);
            } else {
                echo \Cli::color("Omitting marketplace '{$mall->name}' ({$mall->id})", 'blue');
                if ($micello_info_count == 0) {
                    echo \Cli::color(" - does not have a Micello community_id configured\n", 'blue');
                } else {
                    echo \Cli::color(" - has $active_merchants_count active merchants\n", 'blue');
                }
                continue;
            }

            if (!$mall->save(NULL, TRUE)) {
            	echo \Cli::color("There was an error while processing marketplace '{$mall->name}'\n\n", 'red');
            } else {
                $count = count($mall->merchants);
            	echo \Cli::color("Added $count merchants to marketplace '{$mall->name}' ({$mall->id})\n\n", 'green');
            }

            // Release memory used for mall
            unset($malls[$location_id]);
        }
        
    } 
    
    /**
     * Process a set of csv files with micello communities and creates the required
     * marketplaces/merchants
     * @param string $path Path of a csv file or a folder with csv files
     * @param string $create_merchants If set to "true", it will create merchants within marketplaces
     * @param string $update_existing If set to "true", it will update existing marketplaces/merchants
     */
    public static function process_csv($path, $create_merchants, $update_existing) {
        // To prevent buffering of the output
        ob_end_flush();

        $create_merchants = $create_merchants == 'true';
        $update_existing = $update_existing == 'true';

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

        self::process_micello_csv_files($files, $create_merchants, $update_existing);
    }

    /**
     * Scan all marketplaces and updates the coordinates of their merchants to the actual
     * value using the info from the Micello map
     */
    public static function update_coordinates() {
        // To prevent buffering of the output
        ob_end_flush();
        
        // Get only the ids of the malls, the full info will be fetched later. This
        // is done this way to avoid memory issues loading Micello maps
        // TODO: When using select, the later query fails to populate all fields of the model
        $malls = \Model_Mall::query()
//             ->select('id')
            ->where('status', '>', '0')
            ->get();

        $mall_ids = array_keys($malls);
        $malls_count = count($malls);
        $mall_index = 1;
        
        unset($malls);
        
        foreach($mall_ids as $mall_id) {
            // Flush model cache periodically
            if ($mall_index % 5 == 0) {
            	\Model_Mall::flush_cache();
            	\Model_Merchant::flush_cache();
            	\Model_Micello_Info::flush_cache();
            }
            
            $mall = \Model_Mall::query()
//                ->related('micello_info')
                ->related('merchants')
                ->related('merchants.micello_info')
                ->where('id', $mall_id)
                ->get_one();

            echo \Cli::color('(' . $mall_index++ . ' / ' . $malls_count . ') ', 'blue');
            echo \Cli::color("Updating merchants coordinates for marketplace '{$mall->name}' ({$mall->id})\n", 'blue');
            
            if (! $mall->micello_info || empty($mall->micello_info->micello_id)) {
            	echo \Cli::color("Omitting marketplace '{$mall->name}' - Micello info not set\n", 'red');
                continue;
            }

            try {
                \Helper_Micello::update_merchants_coordinates($mall);
            } catch (\Exception $e) {
                echo \Cli::color("There was an error while fetching map for marketplace '{$mall->name}' with community id {$mall->micello_info->micello_id}: ", 'red');
                echo \Cli::color($e->getMessage() . "\n", 'red');
                continue;
            }
            
            if (!$mall->save(NULL, TRUE)) {
            	echo \Cli::color("There was an error while saving marketplace '{$mall->name}'\n", 'red');
            } else {
            	echo \Cli::color("Updated coordinates for merchants within marketplace '{$mall->name}'\n", 'green');
            }
        }
    }
    
    private static function process_micello_csv_files($files, $create_merchants, $update_existing) {
	    foreach($files as $file) {
            if (($handle = fopen($file, "r")) === FALSE) {
                echo "There was an error while opening '$file'. Skipping...\n";
                continue;
            }
            // Discard first row
            fgetcsv($handle, 1000, ",");
            $row_number = 2;

            while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
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
			        $location = self::load_mall($data, $create_merchants, $update_existing);
		        } elseif($data->ct == 'Retail')  {
		            $location = self::load_standalone_merchant($data, $update_existing);
                }

                if (!$location->save(NULL, TRUE)) {
                    echo \Cli::color("There was an error while processing row '$row_number' from '$file'\n\n", 'red');
                } else {
                    echo \Cli::color("Added/updated community '{$data->name}' at row '$row_number' from '$file'\n", 'green');
                }

                $row_number++;
            }

            fclose($handle);
        }
    }
    
    private static function load_mall($micello_data, $create_merchants = FALSE, $update_existing = FALSE) {
        // Check if the location already exists
        $malls = \Model_Mall::query()
            ->related('micello_info')
            ->where('status', '>', '0')
            ->where('micello_info.micello_id', $micello_data->id)
            ->where('micello_info.type', \Model_Micello_Info::TYPE_COMMUNITY)
            ->get();
        $mall = count($malls) > 0 ? array_shift($malls) : NULL;

        if ($mall && $update_existing) {
	        echo \Cli::color("Updating mall '{$mall->name}' ({$mall->id})\n", 'purple');
            \Helper_Micello::update_location_from_community($mall, $micello_data);
            \Helper_Micello::add_social_network_info($mall, 'mall');
        } elseif(!$mall) {
	        echo \Cli::color("Creating mall from community '{$micello_data->name}' ({$micello_data->id})\n", 'purple');
            $mall = \Helper_Micello::create_mall($micello_data);
            \Helper_Micello::add_social_network_info($mall, 'mall');
        } else {
	        echo \Cli::color("Omitting mall '{$mall->name}' ({$mall->id}) - No updates allowed\n", 'purple');
        }
        
        if ($create_merchants) {
        	self::load_merchants_for_mall($mall, $update_existing);
        }
        
        return $mall;
    }
    
    private static function load_merchants_for_mall($mall, $update_existing) {
    	// Create merchants for mall if needed and copy info from mall for each one
    	\Package::load('micello');
    	try {
    	    if ($mall->is_new()) {
    	        $micello_info = $mall->micello_info;
    	    } else {
        	    // This can be used to avoid fetching of the map
        	    $micello_info = \Model_Micello_Info::query()
        	        ->select('id', 'micello_id', 'type', 'location_id')
            	    ->where('location_id', $mall->id)
            	    ->get_one();
    	    }
    	    $result = \Micello\Api::get_entities($micello_info->micello_id);
//    		$result = \Micello\Api::get_entities($mall->micello_info->micello_id);
    	} catch (\Micello\MicelloException $e) {
    		return FALSE;
    	}

    	$entities = $result->results;
    
    	foreach($entities as $entity) {
    	    if (empty($mall->id)) {
    	        $merchant = NULL;
    	    } else {
        	    // Check if the merchant already exists
        	    $merchants = \Model_Merchant::query()
            	    ->related('micello_info')
            	    ->where('status', '>', '0')
            	    ->where('mall_id', $mall->id)
            	    ->where('micello_info.micello_id', $entity->eid)
            	    ->where('micello_info.type', \Model_Micello_Info::TYPE_ENTITY)
            	    ->get();
        	    $merchant = count($merchants) > 0 ? array_shift($merchants) : NULL;
    	    }

    	    if ($merchant && $update_existing) {
    	        echo \Cli::color("Updating merchant '{$merchant->name}' ({$merchant->id})\n", 'cyan');
    	        \Helper_Micello::update_merchant_from_entity($mall->merchants[$merchant->id], $entity);
    	    } elseif(! $merchant) {
    	        echo \Cli::color("Creating merchant from entity '{$entity->nm}' ({$entity->eid})\n", 'cyan');
    	        $mall->merchants[] = \Helper_Micello::create_merchant($entity, $mall);
    	    } else {
    	        echo \Cli::color("Omitting merchant '{$merchant->name}' ({$merchant->id}) - No updates allowed\n", 'cyan');
    	    }
    	}
    }

    private static function load_standalone_merchant($micello_data, $update_existing = FALSE) {
    	// Check if the location already exists
    	$merchants = \Model_Merchant::query()
        	->related('micello_info')
        	->where('status', '>', '0')
        	->where('micello_info.micello_id', $micello_data->id)
        	->where('micello_info.type', \Model_Micello_Info::TYPE_COMMUNITY)
        	->get();
    	$merchant = count($merchants) > 0 ? array_shift($merchants) : NULL;

    	if ($merchant && $update_existing) {
	        echo \Cli::color("Updating standalone merchant '{$merchant->name}' ({$merchant->id})\n", 'purple');
    	    \Helper_Micello::update_location_from_community($merchant, $micello_data);
    		\Helper_Micello::add_social_network_info($merchant, 'standalone_merchant');
    	} elseif (!$merchant) {
	        echo \Cli::color("Creating standalone merchant from community '{$micello_data->name}' ({$micello_data->id})\n", 'purple');
    	    $merchant = \Helper_Micello::create_standalone_merchant($micello_data);
    		\Helper_Micello::add_social_network_info($merchant, 'standalone_merchant');
    	} else {
    	    echo \Cli::color("Omitting standalone merchant '{$merchant->name}' ({$merchant->id}) - No updates allowed\n", 'purple');
    	}
    
    	return $merchant;
    }
    
}
