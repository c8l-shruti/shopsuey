<?php

Package::load('apn');

/**
 * Helper Class to send Apple Push Notifications
 */
class Helper_Apn {
    
    public static function send_notification($user, $text, $custom_properties = array()) {
        $logger = new Custom_APN_Logger();
        $environment = self::get_environment($user);
        $cert = self::get_certificate($user, $environment);
        $token = $user->apn_token;
        
        $logger->log("Using certificate $cert in environment $environment");
        
        $push = new ApnsPHP_Push($environment, $cert);
        $push->setLogger($logger);
        
        $push->connect();
        
        $message = new ApnsPHP_Message($token);
        $message->setText($text);
        
        foreach ($custom_properties as $ck => $cv) {
            $message->setCustomProperty($ck, $cv);
        }
        
        $push->add($message);
        $push->send();
        $push->disconnect();
        
        $errorQueue = $push->getErrors();
        if (!empty($errorQueue)) {
            return false;
        }
        return true;
    }
    
    public static function get_environment($user) {
        $env = \Config::get('apn.environment') == 'production' ? ApnsPHP_Abstract::ENVIRONMENT_PRODUCTION : ApnsPHP_Abstract::ENVIRONMENT_SANDBOX;
        
        if ($user->apn_env == 'dist') {
            $env = ApnsPHP_Abstract::ENVIRONMENT_PRODUCTION;
        } else if ($user->apn_env == 'debug') {
            $env = ApnsPHP_Abstract::ENVIRONMENT_SANDBOX;
        }
        
        return $env;
    }


    public static function get_certificate($user, $environment) {
        $cert = \Config::get('apn.certificate');
        
        $environment = ($environment == ApnsPHP_Abstract::ENVIRONMENT_PRODUCTION) ? "dist" : "debug"; // just for readability
        if ($user->apn_bundle) {
            
            if ($environment == 'dist' && $user->apn_bundle == 'com.shoppingmademobile.shopsueydev') {
                $cert = 'apns-dev-dist.pem';
                
            } else if ($environment == 'debug' && $user->apn_bundle == 'com.shoppingmademobile.shopsueydev') {
                $cert = 'apns-dev-debug.pem';
                
            } else if ($environment == 'dist' && $user->apn_bundle == 'com.shoppingmademobile.shopsuey-prod') {
                $cert = 'apns-prod-dist.pem';
                
            } else if ($environment == 'debug' && $user->apn_bundle == 'com.shoppingmademobile.shopsuey-prod') {
                $cert = 'apns-prod-debug.pem';
                
            } else if ($environment == 'dist' && $user->apn_bundle == 'com.shoppingmademobile.shopsuey') {
                $cert = 'apns-appStore-dist.pem';
            }
        }
        
        return PKGPATH . "apn/certs/" . $cert;
    }
    
}

class Custom_APN_Logger implements ApnsPHP_Log_Interface {
    public function log($sMessage) {
        error_log("APN: " . $sMessage);
    }
}