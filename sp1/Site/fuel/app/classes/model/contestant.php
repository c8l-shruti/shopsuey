<?php

class Model_Contestant extends \Orm\Model {
    
    public static $winnerText = "OMG! You're a ShopSuey winner!";
    
    protected static $_belongs_to = array(
        'contest',
        'user'
    );
    
    protected static $_properties = array(
        'id',
        'contest_id',
        'user_id',
        'pn_sent',
        'created_at',
        'updated_at',
        'email_sent',
    );

    protected static $_has_many = array(
        'rewards',
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
        
    public function sendNotificationEmailToWinner(&$reward){
        
        if ($this->email_sent){
            throw new Exception("ALREADY SENT");
        }
        
        $email_data = array("user" => $this->user);
        
	$data = (array) CMS::email($this->user->email, null, 'ShopSuey :: You won!', $email_data, 'email/winner');
        
        if ($data["meta"]["error"] == ""){
            
            $this->email_sent = date("Y-m-d H:i:s");
            $this->save();
                
            $reward->email_sent = date("Y-m-d H:i:s");
            $reward->save();
            
        }else{
            throw new Exception($data["meta"]["error"]);
        }
        
        return true;
        
    }
    
    public function sendNotificationPNToWinner(&$reward){
        
        if ($this->pn_sent){
            throw new Exception("ALREADY SENT");
        }
        
        $text = static::$winnerText;
        
        if ($this->user->apn_token) {
            
            error_log("SENDING PUSH NOTIFICATION TO WINNER CONTESTANT_ID: ".$this->id." - CONTEST ".$this->contest->name);
            
            if (\Helper_Apn::send_notification($this->user, $text, array('type' => 'winner'))) {
                
                $this->email_sent = date("Y-m-d H:i:s");
                $this->save();
                
                $reward->sent = date("Y-m-d H:i:s");
                $reward->save();
                
                return true;
            }else{
                throw new Exception("UNKNOWN ERROR");
            }
            
        }else{
            throw new Exception("NO APN TOKEN");
        }
        
    }
    
}
