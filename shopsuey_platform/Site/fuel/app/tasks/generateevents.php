<?php

namespace Fuel\Tasks;

class Generateevents {
    
    public static function run($qty = 50) {
        echo "Will generate $qty events\n";
        
        $locations = \Model_Location::find('all');
        
        for ($i = 0; $i < $qty; $i++) {
            $start_ts = strtotime("-" . rand(1,15) . " days");
            $end_ts = $start_ts + 86400 * rand(2,30);
            
            $event = new \Model_Event();
            $event->status = 1;
            $event->title = static::generate_event_name();
            $event->description = "Just a test event";
            $event->date_start = date('Y-m-d H:i:s', $start_ts);
            $event->date_end = date('Y-m-d H:i:s', $end_ts);
            $event->gallery = array();
            $event->tags = static::generate_event_tags();
            $event->created_by_id = 1;
            $event->edited_by_id = 1;
            $random_location = $locations[array_rand($locations)];
            $event->locations = array($random_location);
            $event->fb_event_id = rand(100000000, 999999999);
            $event->foursquare_venue_id = rand(100000000, 999999999);
            $event->foursquare_event_id = rand(100000000, 999999999);
            $event->code = '';
            $event->show_dates = true;
            $event->force_top_message = false;
            $event->website = 'http://www.example.com/';
            $event->coordinator_phone = '813 555 1278';
            $event->coordinator_email = 'coord@example.com';
            
            $event->save();
            echo "Event " . ($i+1) . " saved!\n";
        }
    }
    
    protected static function generate_event_tags() {
        $tags = array(
            'promo', 'offer', 'clothes', 'food', 'love', 'discount', 'free', 
            'coupon', 'printable', 'shoes', 'photo', 'sale', 'dvd', 'bluray',
            'clearance', 'free shipping', 'books', 'restaurant', 'gift card',
            'amazon', 'cyber monday', 'christmas', 'music', 'giveaway',
            'launch', 'presentation', 'workshop', 'new product', 'new line'
        );
        
        $return = array();
        $qty = rand(2,5);
        $keys = array_rand($tags, $qty);
        
        foreach ($keys as $key) {
            $return[] = $tags[$key];
        }
        
        return implode(',', $return);
    }
    
    protected static function generate_event_name() {
        $first = array(
            'Presentation of new line of', 'Introducing our new',
            'Get to know our new', 'Exhibition of our new',
            'Launching our new', 'Meetup about', 'Workshop on',
            'Presentation on'
        );
        
        $second = array(
            'Men\'s fashion', 'Women\'s fashion', 'Electronics', 'TVs',
            'Computers', 'Tablets', 'Cell phones', 'Home appliances',
            'Toys', 'Video games', 'Cameras & Photo', 'DVDs and Movies',
            'Shoes', 'Watches', 'Jewelry', 'Pet supplies', 'Books'
        );
        
        $first_part = $first[array_rand($first)];
        $second_part = $second[array_rand($second)];
        
        return "$first_part $second_part";
    }
    
}
