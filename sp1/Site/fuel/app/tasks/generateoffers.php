<?php

namespace Fuel\Tasks;

class Generateoffers {
    
    public static function run($qty = 50) {
        echo "Will generate $qty offers\n";
        
        $locations = \Model_Location::find('all');
        
        for ($i = 0; $i < $qty; $i++) {
            $price = rand(1,50) * 10;
            $discount = rand(1,4) * 10;
            $start_ts = strtotime("-" . rand(1,15) . " days");
            $end_ts = $start_ts + 86400 * rand(2,30);
            
            $offer = new \Model_Offer();
            $offer->status = 1;
            $offer->name = static::generate_offer_name();
            $offer->description = "Just a test offer";
            $offer->price_regular = $price;
            $offer->price_offer = round($price * (1 - $discount / 100), 2);
            $offer->savings = $discount . "%";
            $offer->date_start = date('Y-m-d H:i:s', $start_ts);
            $offer->date_end = date('Y-m-d H:i:s', $end_ts);
            $offer->gallery = array();
            $offer->allowed_redeems = rand(1,5);
            $offer->multiple_codes = 0;
            $offer->default_code_type = 'qr_code';
            $offer->categories = '';
            $offer->tags = static::generate_offer_tags();
            $offer->created_by = 1;
            $offer->edited_by = 1;
            $offer->redeemable = 1;
            $offer->show_dates = 0;
            $offer->force_top_message = 0;
            $offer->type = rand(0, 1);
            
            $locations_qty = rand(1,3);
            for ($j = 0; $j < $locations_qty; $j++) {
                $random_location = $locations[array_rand($locations)];
                $offer->locations[] = $random_location;
            }
            
            $offer->save();
            echo "Offer " . ($i+1) . " saved!\n";
        }
    }
    
    protected static function generate_offer_tags() {
        $tags = array(
            'promo', 'offer', 'clothes', 'food', 'love', 'discount', 'free', 
            'coupon', 'printable', 'shoes', 'photo', 'sale', 'dvd', 'bluray',
            'clearance', 'free shipping', 'books', 'restaurant', 'gift card',
            'amazon', 'cyber monday', 'christmas', 'music', 'giveaway'
        );
        
        $return = array();
        $qty = rand(2,5);
        $keys = array_rand($tags, $qty);
        
        foreach ($keys as $key) {
            $return[] = $tags[$key];
        }
        
        return implode(',', $return);
    }
    
    protected static function generate_offer_name() {
        $first = array(
            'Giveaway:', 'Discount in', 'Special offer in', 'Clearance sale:',
            'Free', 'Amazing deal in', 'Awesome deal:', 'Save in',
            'Great savings in', 'Hot deal:', 'Discount coupon for'
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
