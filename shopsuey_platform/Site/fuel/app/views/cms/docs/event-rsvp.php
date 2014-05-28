<div id="doc-content" class="mt20">
	<h1><a id="event"></a>Event RSVP</h1>

	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td width="100">Endpoint</td>
				<td><?=Config::get('base_url')?>api/event/:id/rsvp</td>
			</tr>
			<tr>
				<td>Methods</td>
				<td><a href="#event-rsvp-post">POST</a></td>
			</tr>
		</tbody>
	</table>

	<h3><a id="event-rsvp-post"></a>POST</h3>
	<p>RSVP to an event</p>
	<br>
	<h4>Parameters</h4>
	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
			  <td>rsvp_status</td>
			  <td>0 for "not attending", 1 for "attending"</td>
			</tr>
		</tbody>
	</table>

	<h4>Example</h4>
	<pre class="code"><?=Config::get('base_url')?>api/event/1/rsvp</pre>

	<h4>Output</h4>
	<pre class="code">{
    "data": {
        "event": {
            "id": "13",
            "location_id": "3",
            "title": "Some Event",
            "description": "Lorem ipsum, dolor sit amet",
            "coordinator_phone": "841 555 1712",
            "coordinator_email": "",
            "website": "http://www.google.com/",
            "show_dates": "1",
            "date_start": "2013-01-29 08:00:00",
            "date_end": "2013-01-30 00:00:00",
            "status": "1",
            "code": "PROMO",
            "tags": "tag1,tag2",
            "fb_event_id": "1232398",
            "foursquare_venue_id": "73128989",
            "foursquare_event_id": "456358",
            "created_at": "1360789916",
            "updated_at": "1360963396"
        }
    },
    "meta": {
        "error": null,
        "status": 1
    }
}</pre>
</div>