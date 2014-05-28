<div id="doc-content" class="mt20">
	<h1><a id="events"></a>Events List</h1>
	<p>Outputs a list of events. If the parameters today, upcoming and rsvp are not specified, it defaults to show both active and upcoming events. <small>(20 per request)</small></p>

	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td width="100">Endpoint</td>
				<td><?=Config::get('base_url')?>api/events/</td>
			</tr>
			<tr>
				<td>Methods</td>
				<td><a href="#events-get">GET</a></td>
			</tr>
		</tbody>
	</table>

	<h3><a id="events-get"></a>GET</h3>
	<br>
	<h4>Parameters</h4>
	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td>from_favorites</td>
				<td>Returns only events from favorite malls or merchants. If this parameter is sent, <strong>from_nearby</strong> and <strong>from_location</strong> are ignored.</td>
			</tr>
            <tr>
				<td>from_nearby</td>
				<td>Returns only events from nearby locations. When using this filter, two additional parameters should be included: <strong>latitude</strong> and <strong>longitude</strong>. If this parameter is sent, <strong>from_location</strong> is ignored.</td>
			</tr>
            <tr>
				<td>from_location</td>
				<td>Returns only events from a specific location (mall or merchant). The value of this parameter should be a merchant or mall ID.</td>
			</tr>
            <tr>
				<td>include_merchants</td>
				<td>When <strong>from_location</strong> is set, and the location id indicates a mall, setting this parameter will make the event list also include the events associated with merchants inside the mall.</td>
			</tr>
            <tr>
				<td>latitude</td>
				<td>Coordinates of the current location. Must be passed when the <strong>from_nearby</strong> parameter is set.</td>
			</tr>
			<tr>
				<td>longitude</td>
				<td>Coordinates of the current location. Must be passed when the <strong>from_nearby</strong> parameter is set.</td>
			</tr>
            <tr>
				<td>today</td>
				<td>Returns only events that are currently active. If this parameter is sent, <strong>upcoming</strong> and <strong>rsvp</strong> are ignored.</td>
			</tr>
            <tr>
				<td>upcoming</td>
				<td>Returns only events that are scheduled for the next seven days. If this parameter is sent, <strong>rsvp</strong> is ignored.</td>
			</tr>
            <tr>
				<td>rsvp</td>
				<td>Returns only events that have been RSVP'd by the current user.</td>
			</tr>
            <tr>
				<td>keyword</td>
				<td>Returns only events that are tagged by the specified keyword.</td>
			</tr>
			<tr>
				<td>page</td>
				<td>INTEGER value referencing results page to display.</td>
			</tr>
		</tbody>
	</table>

	<h4>Example</h4>
	<pre class="code"><?=Config::get('base_url')?>api/events?from_favorites=1&amp;today=1</pre>

	<h4>Output</h4>
<pre class="code">{
    "data": {
        "events": {
            "2": {
                "id": "2",
                "location_id": "1",
                "title": "Some Event",
                "description": "Lorem ipsum, dolor sit amet.",
                "coordinator_phone": "358 555 7241",
                "coordinator_email": "coordinator@example.com",
                "website": "http://google.com/",
                "show_dates": "1",
                "date_start": 1368363600,
                "date_end": 1368388800
                "date_start_str": "2013-05-12 13:00:00",
                "date_end_str": "2013-05-12 20:00:00",
                "status": "1",
                "code": "PROMO123",
                "tags": "tag1,tag2,tag3",
                "fb_event_id": "84621831",
                "foursquare_venue_id": "162381231",
                "foursquare_event_id": "9814621",
                "created_at": "1358451751",
                "updated_at": "1360268581",
                "like_status": 1
            }
        }
    },
    "meta": {
        "pagination": {
            "limit": 20,
            "offset": {
                "current": 0,
                "next": null,
                "prev": null
            },
            "page": {
                "count": 1,
                "current": 1,
                "next": null,
                "prev": null
            },
            "records": 1
        },
        "status": 1,
        "error": null
    }
}</pre>
</div>