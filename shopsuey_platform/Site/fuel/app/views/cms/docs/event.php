<div id="doc-content" class="mt20">
	<h1><a id="event"></a>Event Details</h1>

	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td width="100">Endpoint</td>
				<td><?=Config::get('base_url')?>api/event/</td>
			</tr>
			<tr>
				<td>Methods</td>
				<td><a href="#event-get">GET</a></td>
			</tr>
		</tbody>
	</table>

	<h3><a id="event-get"></a>GET</h3>
	<p>Get a single event</p>
	<br>
	<h4>Parameters</h4>
	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td width="100">page</td>
				<td>INTEGER value referencing results page to display</td>
			</tr>
		</tbody>
	</table>

	<h4>Example</h4>
	<pre class="code"><?=Config::get('base_url')?>api/event/1</pre>

	<h4>Output</h4>
	<pre class="code">{
    "data": {
        "event": {
            "id": "2",
            "location_id": "1",
            "title": "Some Event",
            "description": "Lorem ipsum, dolor sit amet",
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
            "tags": "tag7",
            "fb_event_id": "3904714",
            "foursquare_venue_id": "1238613",
            "foursquare_event_id": "124071298",
            "created_at": "1358451751",
            "updated_at": "1360268581",
            "like_status": 1,
            "gallery_urls": [
                {
                    "original": "http://shopsuey/assets/img/events/511c0371a0d63.jpg",
                    "small": "http://shopsuey/assets/img/events/small_511c0371a0d63.jpg",
                    "large": "http://shopsuey/assets/img/events/large_511c0371a0d63.jpg"
                },
                {
                    "original": "http://shopsuey/assets/img/events/511c037d76132.gif",
                    "small": "http://shopsuey/assets/img/events/small_511c037d76132.gif",
                    "large": "http://shopsuey/assets/img/events/large_511c037d76132.gif"
                }
            ],
            "location": {
                "social": null,
                "hours": null,
                "id": "1",
                "type": "",
                "status": "1",
                "name": "Some MAll",
                "mall_id": null,
                "address": "123 Sesame Str",
                "city": "New York City",
                "st": "NY",
                "zip": "10458",
                "contact": "John Contact",
                "email": "john@example.com",
                "phone": "6145554967",
                "web": "http://www.example.com",
                "newsletter": "news@email.com",
                "categories": "",
                "tags": "tag,it,up",
                "plan": "0",
                "max_users": "0",
                "content": "Some Content Here",
                "logo": "",
                "latitude": "14.5504",
                "longitude": "-58.4806",
                "description": null,
                "wifi": null,
                "market_place_type": null,
                "use_instagram" : "1",
                "user_instagram_id": "2"
            },
            "rsvpd": true,
            "attending_friends": []
        }
    },
    "meta": {
        "error": null,
        "status": 1
    }
}</pre>

</div>