<?php

namespace Fuel\Tasks;

use Fuel\Core\DB;

class Processunclaimedrewards {
    
    //Time to claim is 5 days (5 * 24 * 3600 seconds)
    const TIME_TO_CLAIM = 432000;
    
    public static function run() {
        self::log("Processing unclaimed rewards");
        $active_contests = self::process_unclaimed_rewards();
    }
    
    protected static function process_unclaimed_rewards() {
        $rewards_query = \Model_Reward::query();
        $rewards_query->where('sent', '!=', NULL);
        
        $rewards = $rewards_query->get();
        
        foreach ($rewards as $reward) {
            $max_claim_date = strtotime($reward->sent) + self::TIME_TO_CLAIM;
            $offer_code = array_slice($reward->offer->offer_codes, 0, 1);
            $unclaimed = $offer_code && empty($offer_code[0]->offer_redeems);
            if ($max_claim_date < time() && $unclaimed) {
                $reward->contestant_id = null;
                $reward->sent = null;
                try {
                    $reward->save();
                } catch (\Exception $e) {
                    self::log("Error!: ".$e->getTraceAsString());
                }
            }
        }
        
        $message = sizeof($rewards) == 1 ? " reward was " : " rewards were ";
        
        self::log(sizeof($rewards).$message."processed!");
    }
    
    protected static function log($message) {
        echo "[ " . date("Y-m-d H:i:s") . "] " . $message . "\n";
        ob_flush();
    }
    
}