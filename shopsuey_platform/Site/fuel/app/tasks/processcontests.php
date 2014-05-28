<?php

namespace Fuel\Tasks;

use Fuel\Core\DB;

class Processcontests {
    
    const TIME_BEFORE_SENDING_PN = 10;
    const CONTESTANT_NOTIFICATION_TEXT = "Congratulations for downloading ShopSuey. You could win a $500 shopping spree!";
    const WINNER_NOTIFICATION_TEXT = "OMG! You're a ShopSuey winner!";
    
    public static function run() {
        
        self::log("This script is deprecated. Kill it with fire.");
        return;
        
        /*
        self::log("Retrieving active contests");
        $active_contests = self::get_active_contests();
        self::log(count($active_contests). " contests found!");
        
        self::add_contestants($active_contests);
        self::notify_contestants($active_contests);
        self::give_prizes($active_contests);
        self::notify_winners($active_contests);
        */
    }
    
    protected static function add_contestants($contests) {
        foreach ($contests as $contest) {
            self::log("====> Adding contestants to contest " . $contest->name);
            $users = \Model_User::query()
                    ->where('created_at', '>', strtotime($contest->start_date))
                    ->where('created_at', '<', strtotime($contest->end_date))
                    ->get();
            
            $new_contestants = 0;
            foreach ($users as $user) {
                $is_already_in_contest = \Model_Contestant::query()
                        ->where('contest_id', $contest->id)
                        ->where('user_id', $user->id)
                        ->count();
                if (!$is_already_in_contest) {
                    $contestant = new \Model_Contestant();
                    $contestant->user = $user;
                    $contestant->contest = $contest;
                    $contestant->pn_sent = 0;
                    $contestant->save();
                    $new_contestants++;
                }
            }
            self::log("$new_contestants contestants have been added to " . $contest->name);
        }
    }
    
    protected static function notify_contestants($contests) {
        foreach ($contests as $contest) {
            self::log("====> Sending notifications to contestants of " . $contest->name);
            $to_notify = \Model_Contestant::query()
                    ->where('contest_id', $contest->id)
                    ->where('pn_sent', '0')
                    ->where('created_at', '<', time() - self::TIME_BEFORE_SENDING_PN)
                    ->get();
            
            $text = self::CONTESTANT_NOTIFICATION_TEXT;
            foreach ($to_notify as $contestant) {
                if ($contestant->user->apn_token) {
                    self::log("Sending PN to user " . $contestant->user->id);
                    
                    if (\Helper_Apn::send_notification($contestant->user, $text, array('type' => 'contestant'))) {
                        $contestant->pn_sent = 1;
                        $contestant->save();
                    }
                    
                }
            }
        }
    }
    
    protected static function notify_winners($contests) {
        foreach ($contests as $contest) {
            self::log("====> Sending notifications to winners of " . $contest->name);
            $to_notify = \Model_Reward::query()
                    ->where('contest_id', $contest->id)
                    ->where('sent', null, \DB::expr('IS NULL'))
                    ->where('contestant_id', null, \DB::expr('IS NOT NULL'))
                    ->where('created_at', '<', time() - self::TIME_BEFORE_SENDING_PN)
                    ->get();
            
            $text = self::WINNER_NOTIFICATION_TEXT;
            foreach ($to_notify as $reward) {
                if ($reward->contestant->user->apn_token) {
                    self::log("Sending PN to user " . $reward->contestant->user->id);
                    if (\Helper_Apn::send_notification($reward->contestant->user, $text, array('type' => 'winner'))) {
                        $reward->sent = date("Y-m-d H:i:s");
                        $reward->save();
                    }
                }
            }
        }
    }

    protected static function give_prizes($contests) {
        foreach ($contests as $contest) {
            $contest_rewards = \Model_Reward::query()->where('contest_id', $contest->id)->order_by('grand_prize')->get();
            $contestants = \Model_Contestant::query()->where('contest_id', $contest->id)->get();
            
            if (!count($contest_rewards)) {
                self::log("There are no rewards for contest " . $contest->name. ", it will be ignored!");
                continue;
            }
        
            if (!count($contestants)) {
                self::log("There are no contestants for contest " . $contest->name. ", it will be ignored!");
                continue;
            }
            
            self::log("====> Giving prizes for contest {$contest->name}");
            
            $number_of_rewards = count($contest_rewards);
            $time_between_rewards = round((strtotime($contest->end_date) - strtotime($contest->start_date)) / $number_of_rewards);
            $time_since_contest_started = time() - strtotime($contest->start_date);
            
            self::log("Contest {$contest->name} has started $time_since_contest_started seconds ago and has $number_of_rewards different rewards. Time between rewards: $time_between_rewards");
            
            $reward_number = 0;
            foreach ($contest_rewards as $reward) {
                $reward_number++;
                if ($reward->contestant_id == null && $time_since_contest_started >= $reward_number * $time_between_rewards) {
                    // this reward hasn't been handed out to anybody yet and
                    // it's time to give a reward!
                    if (!$reward->grand_prize) {
                        self::give_reward_to_random_contestant($reward, $contest);
                    } else {
                        self::give_reward_to_engaged_contestant($reward, $contest);
                    }
                }
            }
        }
    }
    
    protected static function give_reward_to_random_contestant($reward, $contest) {
        $contestants = \Model_Contestant::query()->where('contest_id', $contest->id)->get();
        
        $winner = $contestants[array_rand($contestants)];
        $winner->rewards[] = $reward;
        
        self::log("Giving reward " . $reward->offer->name . " to user " . $winner->user_id . " ({$winner->user->email})");
        
        $winner->save();
        $reward->save();
    }
    
    protected static function give_reward_to_engaged_contestant($reward, $contest) {
        $contestants = \Model_Contestant::query()->where('contest_id', $contest->id)->get();
        $contestants_ids = array_map(function($u) { return $u->user_id; }, $contestants);
        $winner_query = DB::select('user_id', DB::expr('SUM(total_time) AS ttime'))
                ->from('user_sessions')
                ->where('user_id', 'in', $contestants_ids)
                ->group_by('user_id')
                ->order_by('ttime', 'desc');
        $result = $winner_query->execute()->as_array();
        
        $winner_id = $result[0]['user_id'];
        
        foreach ($contestants as $contestant) {
            if ($contestant->user->id == $winner_id) {
                $contestant->rewards[] = $reward;
                
                self::log("Giving grand prize reward " . $reward->offer->name . " to user " . $contestant->user_id . " ({$contestant->user->email})");
                
                $contestant->save();
                $reward->save();
            }
        }
    }

    protected static function get_active_contests() {
        $current_date = date('Y-m-d H:i:s');
        
        $contests_query = \Model_Contest::query();
        $contests_query->where('enabled', '1')->where('start_date', '<=', $current_date);
        
        // commented out this because we have to consider what happens
        // with contests that have already ended but didn't give all of
        // their rewards
        //$contests_query->where('end_date', '>=', $current_date);
        
        return $contests_query->get();
    }
    
    protected static function log($message) {
        echo "[ " . date("Y-m-d H:i:s") . "] " . $message . "\n";
        ob_flush();
    }
    
}
