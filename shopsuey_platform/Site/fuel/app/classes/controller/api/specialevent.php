<?php

class Controller_Api_Specialevent extends Controller_Api {

    public function action_rsvp() {
        if (Input::method() != 'POST') { $this->response($this->no_access); return; }

        $event_id = $this->param('id');
        $user = $this->user_login->user;

        $params = Input::post();

        $status = true;
        if (isset($params['rsvp_status'])) {
            $status = (bool)($params['rsvp_status']);
        }

        $event = Model_Specialevent::find($event_id);

        if (!$event) {
            return $this->_error_response(Code::ERROR_INVALID_EVENT_ID);
        }

        $rsvpd_query = Model_Specialeventrsvp::query()
                ->where('user_id', $user->id)
                ->where('specialevent_id', $event->id);
        $already_rsvpd = $rsvpd_query->count();

        if ($status && !$already_rsvpd) {
            $rsvp = new Model_Specialeventrsvp();
            $rsvp->user_id = $user->id;
            $rsvp->specialevent_id = $event->id;
            $rsvp->save();

            Helper_Activity::log_activity($user, 'rsvp_specialevent', array('specialevent_id' => (int)$event->id));
            Helper_Analytics::log_event($this->user_login->user, 'specialevent', 'rsvp', 'specialevent' . $event->id);
        } elseif (!$status && $already_rsvpd) {
            $rsvp = $rsvpd_query->get_one();
            $rsvp->delete();
        }

        $meta = array('error' => null, 'status' => 1);
        $data = array('data' => array('event' => $event), 'meta' => $meta);
        $this->response($data);
    }

    public function action_locations() {
        $event_id = $this->param('id');
        $event = Model_Specialevent::find($event_id);

        if (!$event) {
            return $this->_error_response(Code::ERROR_INVALID_EVENT_ID);
        }

        $locations = Helper_Api::locations_response($event->locations);
        $meta = array('error' => null, 'status' => 1);
        $data = array('data' => array('locations' => $locations), 'meta' => $meta);
        $this->response($data);
    }

    public function action_offers() {
        $event_id = $this->param('id');
        $event = Model_Specialevent::find($event_id);

        if (!$event) {
            return $this->_error_response(Code::ERROR_INVALID_EVENT_ID);
        }
        
        $location_ids = array($event->main_location->id);
        foreach ($event->locations as $loc) {
            $location_ids[] = $loc->id;
        }

        $query = \Model_Offer::query()->related('locations')->where('status', '1');
        $query->where('type', Model_Offer::TYPE_DEFAULT);
        $query->where('locations.id', 'in', $location_ids);

        $current_date = date('Y-m-d H:i:s', time());
        $upcoming_date = date('Y-m-d H:i:s', strtotime(Controller_Api_Offer::UPCOMING_TIME));
        $query->and_where_open();
        $query->or_where('date_start', 'between', array($current_date, $upcoming_date));
        $query->or_where('date_start', '<=', $current_date);
        $query->and_where_close();
        $query->where('date_end', '>=', $current_date);

        $offers = array();

        foreach ($query->get() as $offer) {
            $offer_response = Helper_Api::offer_response($offer, true, false);
            $offer_response->like_status = $this->user_login->user->get_like_message_status('offer', $offer->id);

            if ($offer_response->like_status > -1) {
                $offers[] = $offer_response;
            }
        }

        $meta = array('error' => null, 'status' => 1);
        $data = array('data' => array('offers' => $offers), 'meta' => $meta);
		$this->response($data);
    }

    public function action_events() {
        $event_id = $this->param('id');
        $specialevent = Model_Specialevent::find($event_id);

        if (!$specialevent) {
            return $this->_error_response(Code::ERROR_INVALID_EVENT_ID);
        }

        $location_ids = array($specialevent->main_location->id);
        foreach ($specialevent->locations as $loc) {
            $location_ids[] = $loc->id;
        }

        $query = \Model_Event::query()->related('locations')->where('status', '1');
        $query->where('locations.id', 'in', $location_ids);

        $current_date = date('Y-m-d H:i:s', time());
        $upcoming_date = date('Y-m-d H:i:s', strtotime(Controller_Api_Event::UPCOMING_TIME));
        $query->and_where_open();
        $query->or_where('date_start', 'between', array($current_date, $upcoming_date));
        $query->or_where('date_start', '<=', $current_date);
        $query->and_where_close();
        $query->where('date_end', '>=', $current_date);

        $events = array();

        foreach ($query->get() as $event) {
            $event_response = Helper_Api::event_response($event, true, false);
            $event_response->like_status = $this->user_login->user->get_like_message_status('event', $event->id);

            if ($event_response->like_status > -1) {
                $events[] = $event_response;
            }
        }

        $meta = array('error' => null, 'status' => 1);
        $data = array('data' => array('events' => $events), 'meta' => $meta);
		$this->response($data);
    }
}
