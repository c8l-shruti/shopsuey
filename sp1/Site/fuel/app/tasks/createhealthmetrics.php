<?php

namespace Fuel\Tasks;

use Fuel\Core\DB; 
\Package::load('twitter');
\Package::load('foursquare');

ini_set('memory_limit','512M');

class Createhealthmetrics {
    
    const BASE_FB_URL = 'https://graph.facebook.com/';
    
    public static function run() {
        self::log("Creating health metrics...");
        self::create_health_metrics();
    }
    
    protected static function create_health_metrics() {
        $totalLocations = \Model_Location::query()
            ->where("status", "!=", 0)
            ->count();
        
        $partSize   = 100;
        $totalParts = ceil($totalLocations / $partSize);
        
        $processedLocations = 0;
        for ($i = 0; $i < $totalParts; $i++) {
            
            $offset    = $i * $partSize; 
            $locations = \Model_Location::query()
                ->where("status", "!=", 0)
                ->offset($offset)
                ->limit($partSize)
                ->get();
            
            foreach ($locations as $location) {
                self::log("Generating metrics for location " . $location->name . " [id: " . $location->id . "]");
                $healthmetric = new \Model_Healthmetric();
                $healthmetric->location = null;
                $healthmetric->offers_count = 0;
                $healthmetric->events_count = 0;
                $healthmetric->sign_ups_count = 0;
                $healthmetric->favorites_count = 0;
                $healthmetric->check_ins_count = 0;
                $healthmetric->likes_count = 0;
                $healthmetric->follows_count = 0;
                $healthmetric->redemptions_count = 0;
                $healthmetric->rsvps_count = 0;
                $healthmetric->likes_via_ss_count = 0;
                $healthmetric->check_ins_via_ss_count = 0;

                self::set_metrics($location, $healthmetric);

                $mall = \Model_Mall::find($location->id);

                // We're not aggregating the health metrics now
                // I'm leaving the commented code here just in case...
                /*if (!empty($mall)) {            
                    foreach ($mall->merchants as $merchant) {
                        self::set_metrics($merchant, $healthmetric);
                    }
                }*/

                try {
                    $healthmetric->save();
                } catch (\Exception $e) {
                    self::log("Error!: ".$e->getMessage()."\n");
                    self::log("Trace: ".$e->getTraceAsString());
                }
            }
            
            $processedLocations += sizeof($locations);
            
            \Orm\Model::flush_cache();
        }
        
        $message = $processedLocations == 1 ? " health metric was " : " health metrics were ";
        
        self::log($processedLocations.$message."added!");
    }
    
    private static function set_metrics($location, $healthmetric) {        
        if (empty($healthmetric->location)) {
            $healthmetric->location = $location;
        }
//         $healthmetric->offers_count += sizeof($location->offers);
        $healthmetric->offers_count += $location->active_offers_count();
//         $healthmetric->events_count += sizeof($location->events);
        $healthmetric->events_count += $location->active_events_count();
        $healthmetric->sign_ups_count += \Model_Subscription::query()
            ->where("location_id", $location->id)
            ->count();
        $healthmetric->favorites_count += sizeof($location->favorited_users);

        $result = DB::select(DB::expr('COUNT(*) as total_count'))
            ->from('offer_redeems')
            ->join('offer_codes')
            ->on('offer_redeems.offer_code_id', '=', 'offer_codes.id')
            ->join('offers')
            ->on('offer_codes.offer_id', '=', 'offers.id')
            ->join('locations_offers')
            ->on('offers.id', '=', 'locations_offers.offer_id')
            ->where('locations_offers.location_id', $location->id)
            ->execute();
        $result_arr = $result->current();
        $healthmetric->redemptions_count += $result_arr['total_count'];
        
//         $healthmetric->redemptions_count += \Model_Offer_Redeem::query()
//             ->related('offer_code')
//             ->related('offer_code.offer')
//             ->related('offer_code.offer.locations')
//             ->where('offer_code.offer.locations.id', $location->id)
//             ->count();

        $result = DB::select(DB::expr('COUNT(*) as total_count'))
            ->from('eventrsvps')
            ->join('events')
            ->on('eventrsvps.event_id', '=', 'events.id')
            ->join('locations_events')
            ->on('events.id', '=', 'locations_events.event_id')
            ->where('locations_events.location_id', $location->id)
            ->execute();
        $result_arr = $result->current();
        $healthmetric->rsvps_count += $result_arr['total_count'];
        
//         $healthmetric->rsvps_count += \Model_Eventrsvp::query()
//             ->related('event')
//             ->related('event.locations')
//             ->where('event.locations.id', $location->id)
//             ->count();
        
        if (!empty($location->social)) {
            $healthmetric->likes_via_ss_count     += sizeof($location->location_likes);
            $healthmetric->check_ins_via_ss_count += sizeof($location->location_checkins);
            
            if (isset($location->social->facebook)) {
                $facebookpage = $location->social->facebook;
                if (isset($facebookpage) && !empty($facebookpage)) {
                    try {
                        $info = self::get_facebook_info($facebookpage);
                        if ($info && !isset($info['error'])) {
                            $healthmetric->check_ins_count += isset($info['checkins']) ? $info['checkins'] : 0;
                            $healthmetric->likes_count += isset($info['likes']) ? $info['likes'] : 0;
                        }
                    } catch (\Exception $e) {

                    }
                }
            }
            
            if (isset($location->social->foursquare)) {
                $foursquarevenue = $location->social->foursquare;
                if (isset($foursquarevenue) && !empty($foursquarevenue)) {
                    try {
                        $check_ins_count = self::get_foursquare_checkins($foursquarevenue);
                        $healthmetric->check_ins_count += $check_ins_count;
                    } catch (\Exception $e) {

                    }
                }
            }
            
            if (isset($location->social->twitter)) {
                $twitteraccount = $location->social->twitter;
                if (isset($twitteraccount) && !empty($twitteraccount)) {
                    $follows_count = self::get_twitter_followers($twitteraccount);
                    if (!isset($info['error'])) {
                        $healthmetric->follows_count += $follows_count;
                    }
                }
            }
        }
    }
    
    private static function get_facebook_info($pagename) {
        $url = self::BASE_FB_URL . $pagename;
        $curl = \Request::forge($url, 'curl');
        $curl->set_method('get');

        $info = $curl->execute()->response();
        $info = json_decode($info, true);
        
        return $info;
    }
    
    private static function get_foursquare_checkins($venuename) {
        $venue_info = \Foursquare\Api::get_venue($venuename);
        $check_ins = 0;
        
        if (isset($venue_info->venue->stats)) {
            $check_ins = $venue_info->venue->stats->checkinsCount;
        }
        
        return $check_ins;
    }
    
    private static function get_twitter_followers($account) {
        $url = \Config::get('twitter.base_url');
        $key = \Config::get('twitter.consumer_key');
        $secret = \Config::get('twitter.consumer_secret');
        $access_token = \Config::get('twitter.access_token');
        $access_token_secret = \Config::get('twitter.access_token_secret');
        
        $twitteroauth = new \Twitter\TwitterOAuth($key, $secret, $access_token, $access_token_secret);
        $twitter_info = $twitteroauth->get('users/show', array('screen_name' => $account));
        
        return isset($twitter_info->followers_count) ? $twitter_info->followers_count : 0;
    }
    
    protected static function log($message) {
        echo "[ " . date("Y-m-d H:i:s") . "] " . $message . "\n";
        ob_flush();
    }
    
}