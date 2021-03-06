<?php

namespace Fuel\Tasks;

class Testperformance {
    
    const USERNAME = 'federico.casares@gmail.com';
    const PASSWORD = 'Shop2013Suey';
    const HOSTNAME = 'dev.thesuey.com';
    const APPID    = 'c4ca4238a0b923820dcc509a6f75849b';
    const USERID   = 188;
    
    protected static $login_hash;
    protected static $api;
    protected static $locations;
    protected static $malls;

    # with default values this simulates roughly the load generated by
    # 6 concurrent users (assuming 120 seconds sessions and 20 requests
    # per session)
    public static function run($amount = 120, $sleep_between_reqs = 1) {
        echo " ========= Performance Testing Tool ========= \n";
        echo "Going to execute $amount requests \n\n";
        
        self::$api = new \Restful();
		self::$api->setAppid(self::APPID);
        
        echo "Authenticating user...\n\n";
        self::call_auth();

        $weights = self::get_weights();
        $total_weight = array_sum($weights);
        for ($i = 0; $i < $amount; $i++) {
            $random_number = mt_rand(1, $total_weight);
            $current_sum = 0;
            foreach ($weights as $request => $weight) {
                $current_sum += $weight;
                if ($current_sum >= $random_number) {
                    break;
                }
            }
            echo "Making a request of type $request...\n";
            ob_flush();
            $s = microtime(true);
            call_user_func('self::call_' . $request);
            $e = microtime(true);
            $time = $e - $s;
            if ($time > 2) {
                "===> $request took $time seconds!\n";
            }
            sleep($sleep_between_reqs);
        }
    }
    
    protected static function get_weights() {
        return array (
            'auth' => 11,                // /api/auth
            'force_upgrade' => 512,      // /api/auth/force_upgrade
            'categories' => 177,         // /api/categories
            'events_count' => 1177,      // /api/events/count
            'location_map' => 69,        // /api/location/NUMBER/map
            'location_points' => 100,    // /api/location/NUMBER/points
            'location_search' => 158,    // /api/location/search
            'mall' => 213,               // /api/mall/NUMBER
            'merchant' => 417,           // /api/merchant/NUMBER
            'offer_count' => 1330,       // /api/offer/count
            'proximity' => 2035,         // /api/proximity
            'user' => 19,                // /api/user/NUMBER
        );
    }
    
    protected static function call_auth() {
        $data = array(
            'email' => self::USERNAME,
            'password' => self::PASSWORD,
            'app_id' => self::APPID
        );
        
        self::$api->setData($data);
        self::$api->setMethod('POST');
        self::$api->setURL(self::create_uri("api/auth"));
        $output = self::$api->execute();
        
        if (!$output || !isset($output->data->login_hash)) {
            echo "===> Auth went wrong! Let's sleep for a while!";
            sleep(rand(1,5));
            return self::call_auth();
        }
        
        self::$login_hash = $output->data->login_hash;
        self::$api->setLoginHash(self::$login_hash);
    }
    
    protected static function call_force_upgrade() {
        $data = array(
            'version' => '1.2.0',
        );
        
        self::$api->setData($data);
        self::$api->setMethod('GET');
        self::$api->setURL(self::create_uri("api/auth/force_upgrade"));
        self::$api->execute();
    }
    
    protected static function call_categories() {
        $data = array(
        );
        
        self::$api->setData($data);
        self::$api->setMethod('GET');
        self::$api->setURL(self::create_uri("api/categories"));
        self::$api->execute();
    }
    
    protected static function call_events_count() {
        $data = array(
            'include_merchants' => 1,
            'from_location' => self::random_location_id()
        );
        
        self::$api->setData($data);
        self::$api->setMethod('GET');
        self::$api->setURL(self::create_uri("api/events/count"));
        self::$api->execute();
    }
    
    protected static function call_location_map() {
        $data = array(
        );
        
        self::$api->setData($data);
        self::$api->setMethod('GET');
        self::$api->setURL(self::create_uri("api/location/" . self::random_mall_id() . "/map"));
        self::$api->execute();
    }
    
    protected static function call_location_points() {
        $data = array(
        );
        
        self::$api->setData($data);
        self::$api->setMethod('GET');
        self::$api->setURL(self::create_uri("api/location/" . self::random_mall_id() . "/points"));
        self::$api->execute();
    }
    
    protected static function call_location_search() {
        $data = array(
            'pagination' => 0,
            'compact' => 1,
            'string' => chr(rand(65,90))
        );
        
        self::$api->setData($data);
        self::$api->setMethod('GET');
        self::$api->setURL(self::create_uri("api/locations"));
        self::$api->execute();
    }
    
    protected static function call_mall() {
        $data = array(
        );
        
        self::$api->setData($data);
        self::$api->setMethod('GET');
        self::$api->setURL(self::create_uri("api/mall/" . self::random_mall_id()));
        self::$api->execute();
    }
    
    protected static function call_merchant() {
        $data = array(
        );
        
        self::$api->setData($data);
        self::$api->setMethod('GET');
        self::$api->setURL(self::create_uri("api/merchant/" . self::random_location_id()));
        self::$api->execute();
    }
    
    protected static function call_offer_count() {
        $data = array(
            'include_merchants' => 1,
            'from_location' => self::random_location_id()
        );
        
        self::$api->setData($data);
        self::$api->setMethod('GET');
        self::$api->setURL(self::create_uri("api/offer/count"));
        self::$api->execute();
    }
    
    protected static function call_proximity() {
        $data = array(
            'max_messages' => 6,
            'longitude' => -157.8 + rand(-1000, 1000) / 5000,
            'latitude' => 21.3 + rand(-1000, 1000) / 5000,
            'radius' => rand(2, 10)
        );
        
        self::$api->setData($data);
        self::$api->setMethod('GET');
        self::$api->setURL(self::create_uri("api/proximity"));
        self::$api->execute();
    }
    
    protected static function call_user() {
        $data = array(
        );
        
        self::$api->setData($data);
        self::$api->setMethod('GET');
        self::$api->setURL(self::create_uri("api/user/" . self::USERID));
        self::$api->execute();
    }

    protected static function create_uri($path) {
        return "http://" . self::HOSTNAME . "/" . $path;
    }
    
    protected static function random_location_id() {
        if (empty(self::$locations)) {
            self::$locations = \Model_Location::find('all');
        }
        
        $location = self::$locations[array_rand(self::$locations)];
        return $location->id;
    }
    
    protected static function random_mall_id() {
        if (empty(self::$malls)) {
            self::$malls = \Model_Mall::find('all');
        }
        
        $location = self::$malls[array_rand(self::$malls)];
        return $location->id;
    }
}
