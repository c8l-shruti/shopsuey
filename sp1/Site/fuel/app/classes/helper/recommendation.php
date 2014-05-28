<?php

/**
 * Recommendation engine
 */
class Helper_recommendation {

    const PROFILING_CHOICE_WEIGHT = 5;
    const MESSAGE_LIKE_WEIGHT = 1;
    const DISTANCE_FACTOR = 0.005; // no effect up to 0.005 miles

    public static function sort_by_recommendation_weight($messages, $user, $lat, $long) {
        $category_weights = self::calculate_category_weights($user);
        $msg_weights = array();

        foreach ($messages as $k => $msg) {
            $this_msg_weight = 0;
            $locations = $msg['details']->locations;
            foreach ($locations as $location) {
                $distance = self::get_distance_to($location, $lat, $long);
                $distance_factor = 1 / max(1, $distance / self::DISTANCE_FACTOR);
                foreach ($location->categories as $category) {
                    // weight is formed by a combination of user's interests
                    // and proximity to the locations
                    $this_msg_weight += $category_weights[$category->id] * $distance_factor;
                }
            }
            $msg_weights[$k] = $this_msg_weight;
        }
        array_multisort($msg_weights, SORT_DESC, $messages);
        return $messages;
    }

    private static function get_distance_to($location, $lat, $long) {
        $origin_point = Geo::build_coordinates($lat, $long);
        $destination_point = Geo::build_coordinates($location->latitude, $location->longitude);
        return Geo::calculate_distance($origin_point, $destination_point);
    }

    public static function calculate_category_weights($user) {
        # TODO: add a caching layer, calculating this every time is too expensive!

        $all_categories = Model_Category::find('all');
        $cat_weights = array();

        foreach ($all_categories as $cat) {
            $cat_weights[$cat->id] = 0;
        }

        // weights because of user's profiling choices
        foreach ($user->profilingchoices as $pfc) {
            foreach ($pfc->categories as $category) {
                $cat_weights[$category->id] += self::PROFILING_CHOICE_WEIGHT;
            }
        }

        // weights because of user's offer likes
        // workaround in order to prevent 1000s of queries from being executed
        $offerlikes = Model_Offerlike::query()->related('offer')->related('offer.locations')->related('offer.locations.categories')->where('user_id', '=', $user->id)->get();
        foreach ($offerlikes as $offer_like) {
            foreach ($offer_like->offer->locations as $location) {
                foreach ($location->categories as $category) {
                    $cat_weights[$category->id] += self::MESSAGE_LIKE_WEIGHT * $offer_like->status;
                }
            }
        }

        // weights because of user's event likes
        // workaround in order to prevent 1000s of queries from being executed
        $eventlikes = Model_Eventlike::query()->related('event')->related('event.locations')->related('event.locations.categories')->where('user_id', '=', $user->id)->get();
        foreach ($eventlikes as $event_like) {
            foreach ($event_like->event->locations as $location) {
                foreach ($location->categories as $category) {
                    $cat_weights[$category->id] += self::MESSAGE_LIKE_WEIGHT * $event_like->status;
                }
            }
        }

        $cat_weights = array_map(array('self', 'weight_conversion_function'), $cat_weights);
        return $cat_weights;
    }

    protected static function weight_conversion_function($w) {
        return max(0, $w + pow(1.1, $w));
    }

}