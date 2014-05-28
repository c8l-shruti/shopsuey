<?php

/**
 * Helper class for interaction with Google Analytics API
 */
class Helper_Analytics {

    const COLLECT_URL = "http://www.google-analytics.com/collect";
    const ANALYTICS_VERSION = "1";

    /**
     * @param Model_User $user
     * @param string $event_category
     * @param string $event_action
     * @param string $event_label
     * @param string $event_value
     * @return int
     */
    public static function log_event($user, $event_category, $event_action, $event_label, $event_value = '') {
        $allowed_categories = array('event', 'offer', 'specialevent');

        if (!in_array($event_category, $allowed_categories)) {
            throw new Exception("$event_category is not a valid event category");
        }

        $data = array(
            'ec' => $event_category,
            'ea' => $event_action,
            'el' => $event_label
        );

        if ($event_value) {
            $data['ev'] = $event_value;
        }

        return self::collect($user, 'event', $data);
    }

    /**
     * sends data to the /collect endpoint in order to be logged
     */
    private static function collect($user, $hit_type, $data) {
        $data['v'] = self::ANALYTICS_VERSION;
        $data['tid'] = self::get_tid();
        $data['cid'] = self::get_cid($user);
        $data['t'] = $hit_type;

        $curl = curl_init(self::COLLECT_URL);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        return $status;
    }

    /**
     * returns the tracking id for the current environment
     */
    private static function get_tid() {
        return Config::get('analytics.id');
    }

    /**
     * returns the unique client id for the particular user
     */
    private static function get_cid($user) {
        if (!$user) {
            return self::get_uuid_v4();
        }
        if ($user->analytics_cid) {
            return $user->analytics_cid;
        }
        $cid = self::get_uuid_v4();
        $user->analytics_cid = $cid;
        $user->save();

        return $cid;
    }

    private static function get_uuid_v4() {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                // 32 bits for "time_low"
                mt_rand(0, 0xffff), mt_rand(0, 0xffff),
                // 16 bits for "time_mid"
                mt_rand(0, 0xffff),
                // 16 bits for "time_hi_and_version",
                // four most significant bits holds version number 4
                mt_rand(0, 0x0fff) | 0x4000,
                // 16 bits, 8 bits for "clk_seq_hi_res",
                // 8 bits for "clk_seq_low",
                // two most significant bits holds zero and one for variant DCE1.1
                mt_rand(0, 0x3fff) | 0x8000,
                // 48 bits for "node"
                mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

}
