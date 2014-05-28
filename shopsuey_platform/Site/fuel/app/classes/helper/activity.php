<?php

/**
 * Helper Class to create and retrieve user activities
 */
class Helper_Activity {

    /**
     * @param Model_User $user
     * @param string $activity_type
     * @param array $details
     * @param boolean $save
     */
    public static function log_activity(Model_User $user, $activity_type, $details, $save = true) {
        $activity = new Model_User_Activity();
        $activity->user_id = $user->id;
        $activity->activity_type = $activity_type;
        $activity->details = $details;

        $user->last_activity = time();

        if ($save) {
            $activity->save();
            $user->save();
        }
    }

    /**
     * @param Model_User $user
     * @param string|null $activity_type
     * @return array
     */
    public static function get_activities(Model_User $user, $activity_type = null) {
        $query = Model_User_Activity::query();
        $query->where('user_id', $user->id);

        if (!is_null($activity_type)) {
            $query->where('activity_type', $activity_type);
        }

        return $query->get();
    }
}
