<?php

namespace Fuel\Tasks;

class Generatecontests {
    
    public static function run($qty = 3) {
        ob_end_flush();
        
        echo "\nWill generate $qty contests\n\n";
        
        $offers = \Model_Offer::find('all', array(
            'where' => array(
                array('type', 1),
            )
        ));
        $users = \Model_User::find('all');
        
        for ($i = 0; $i < $qty; $i++) {
            $start_ts = strtotime("-" . rand(1,15) . " days");
            $end_ts = $start_ts + 86400 * rand(10,30);
            
            $contest = new \Model_Contest();
            $contest->name = self::generate_contest_name();
            $contest->start_date = date("Y-m-d H:i:s", $start_ts);
            $contest->end_date = date("Y-m-d H:i:s", $end_ts);
            
            echo "Creating contest: " . $contest->name . "\n";
            
            for ($j = 0; $j < 10; $j++) {
                $random_offer = $offers[array_rand($offers)];
                $reward = new \Model_Reward();
                $reward->offer = $random_offer;
                $reward->contest = $contest;
                $reward->grand_prize = rand(1, 100) < 5 ? 1 : 0;
                try {
                    $reward->save();
                } catch (\Exception $e) {
                    
                }
            }
            echo "Added 10 rewards to the contest\n";
            
            $contest->save();
            
            echo "Do you want to add some contestants to this contest? (y/n): ";
            
            $handle = fopen("php://stdin","r");
            $line = fgets($handle);
            if (trim($line) == 'y') {
                for ($j = 0; $j < 20; $j++) {
                    $random_user = $users[array_rand($users)];
                    $contestant = new \Model_Contestant();
                    $contestant->contest = $contest;
                    $contestant->user = $random_user;
                    $contestant->pn_sent = 0;
                    try {
                        $contestant->save();
                    } catch (\Exception $e) {
                        
                    }
                }
                echo "Added 20 contestants to the contest\n";
            }
            echo "\n";
        }
    }
    
    protected static function generate_contest_name() {
        $first = array(
            'Incredible', 'Amazing', 'Awesome', 'Superb', 'New', 'Some', 
            'Marvelous', 'Exclusive', 'Wonderful'
        );
        
        $second = array(
            'Promo', 'Contest', 'Prizes', 'Rewards', 'Promotion', 'Sweepstake'
        );
        
        $first_part = $first[array_rand($first)];
        $second_part = $second[array_rand($second)];
        
        return "$first_part $second_part";
    }
    
}
