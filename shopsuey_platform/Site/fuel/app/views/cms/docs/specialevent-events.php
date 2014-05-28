<div id="doc-content" class="mt20">
	<h1><a id="event"></a>Get Special Event Events</h1>

	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td width="100">Endpoint</td>
				<td><?=Config::get('base_url')?>api/specialevent/:id/events</td>
			</tr>
			<tr>
				<td>Methods</td>
				<td><a href="#event-events-get">GET</a></td>
			</tr>
		</tbody>
	</table>

	<h3><a id="event-events-get"></a>POST</h3>
	<p>Get all active or upcoming events related to the special event</p>
	<br>
	<h4>Parameters</h4>
	<table class="grid" cellspacing="2" cellpadding="5" border="1">

	</table>

	<h4>Example</h4>
	<pre class="code"><?=Config::get('base_url')?>api/specialevent/1/events</pre>

	<h4>Output</h4>
	<pre class="code">{
    "data": {
        "events": [
            {
                "gallery": [
                    "516933c91803c.jpg"
                ],
                "updated_at": 1365849033,
                "id": "28",
                "created_by_id": "212",
                "edited_by_id": "212",
                "title": "HULA SHOW | Ala Moana Center Stage",
                "description": "Discover the beauty of Hawaiian hula with our complimentary shows 7 days a week.",
                "featured_image": null,
                "coupon_image": null,
                "coordinator_phone": "",
                "coordinator_email": "",
                "website": "http://alamoanacenter.com/Events/Ala-Moana-Hula-Show",
                "show_dates": 0,
                "date_start": 1357034400,
                "date_end": 1388398800,
                "status": "1",
                "code": "",
                "tags": "Hula",
                "fb_event_id": "",
                "foursquare_venue_id": "",
                "foursquare_event_id": "",
                "force_top_message": "0",
                "created_at": "1365849033",
                "gallery_urls": [
                    {
                        "original": "http://shopsuey/assets/img/events/516933c91803c.jpg",
                        "small": "http://shopsuey/assets/img/events/small_516933c91803c.jpg",
                        "large": "http://shopsuey/assets/img/events/large_516933c91803c.jpg"
                    }
                ],
                "date_start_str": "2013-01-01 10:00:00",
                "date_end_str": "2013-12-30 10:20:00",
                "special": 0
            }
        ]
    },
    "meta": {
        "error": null,
        "status": 1
    }
}</pre>
</div>