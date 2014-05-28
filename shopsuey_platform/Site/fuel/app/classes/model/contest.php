<?php

class Model_Contest extends \Orm\Model
{
	protected static $_properties = array(
        'id',
		'name',
		'start_date',
		'end_date',
        'enabled',
		'created_at',
		'updated_at',
                'how_favorite_location_id',
                'how_checkin_location_id',
                'how_signup',
	);
	
	protected static $_has_many = array(
	    'contestant',
	    'reward',
	);

	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'mysql_timestamp' => false,
		),
		'Orm\Observer_UpdatedAt' => array(
			'events' => array('before_save'),
			'mysql_timestamp' => false,
		),
	);
        
    public function isFavoriteLocation(){
        if ($this->how_favorite_location_id){
            return true;
        }else{
            return false;
        }
    }
    
    public function isCheckin(){
        if ($this->how_checkin_location_id){
            return true;
        }else{
            return false;
        }
    }
    
    public function isSignup(){
        if ($this->how_signup){
            return true;
        }else{
            return false;
        }
    }
    
    public function findWinner(){
        
        if ($this->isFavoriteLocation()) $winnerUser = $this->findWinnerByFavoriteLocation();
        if ($this->isCheckin()) $winnerUser = $this->findWinnerByCheckin();
        if ($this->isSignup()) $winnerUser = $this->findWinnerBySignup();
        
        if (empty($winnerUser)) throw new Exception("COULD NOT FIND WINNER FOR CONTEST ID ".$this->id);
        
        return $winnerUser;

    }
    
    private function findWinnerByFavoriteLocation(){
        
        $locationId = $this->how_favorite_location_id;
        $startTs = strtotime($this->start_date);
        $endTs = strtotime($this->end_date);
	                
        $favoritedRecords = Model_Favoritelocation::query()
            ->where('created_at', '>=', $startTs)
            ->where('created_at', '<', $endTs)
            ->where('location_id', $locationId)
            ->get();

        if (!count($favoritedRecords)) return null;
        
        $keys = array_keys($favoritedRecords);
                
        $winnerPos = mt_rand(0, count($keys)-1);
        
        $winnerRecord = $favoritedRecords[$keys[$winnerPos]];
        
        //error_log(var_export($winnerRecord, true));
        //error_log($winnerRecord->user_id);
        
        $winner = Model_User::find($winnerRecord->user_id);
        
        return $winner;
        
    }
    
    private function findWinnerByCheckin(){
        
        $locationId = $this->how_checkin_location_id;
        
        $query = "SELECT user_id, COUNT(location_id) AS checkins 
            FROM suey_checkins 
            WHERE 
                created >= :start_date AND 
                created < :end_date AND 
                location_id = :location_id 
            GROUP BY user_id 
            ORDER BY RAND() 
            LIMIT 1";

        $queryObject = DB::query($query)->parameters(array('start_date' => $this->start_date, 'end_date' => $this->end_date, 'location_id' => $locationId));

        $checkinRecord = $queryObject->execute();

        if (!count($checkinRecord)) return null;

        //error_log(var_export($checkinRecord[0]["user_id"], true));

        $winner = Model_User::find($checkinRecord[0]["user_id"]);

        //error_log(var_export($winner, true));
        
        return $winner;
        
    }
    
    private function findWinnerBySignup(){
        
        $startTs = strtotime($this->start_date);
        $endTs = strtotime($this->end_date);
        
        $query = "SELECT id 
            FROM suey_users 
            WHERE 
                created_at >= :start_ts AND 
                created_at < :end_ts 
            ORDER BY RAND() 
            LIMIT 1";
        
        $queryObject = DB::query($query)->parameters(array('start_ts' => $startTs, 'end_ts' => $endTs));

        $userRecord = $queryObject->execute();
        
        if (!count($userRecord)) return null;
        
        //error_log(var_export($userRecord[0]["id"], true));
        
        $winner = Model_User::find($userRecord[0]["id"]);
        
        //error_log(var_export($winner, true));
        
        return $winner;
        
    }
    
}
