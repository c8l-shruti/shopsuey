<div id="doc-content" class="mt20">
	<h1><a id="event"></a>Proximity Messaging</h1>

	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td width="100">Endpoint</td>
				<td><?=Config::get('base_url')?>api/proximity</td>
			</tr>
			<tr>
				<td>Methods</td>
				<td><a href="#event-proximity-get">GET</a></td>
			</tr>
		</tbody>
	</table>

	<h3><a id="event-proximity-get"></a>GET</h3>
	<p>Returns a list of nearby events and offers.</p>
	<br>
	<h4>Parameters</h4>
	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
            <tr>
				<td>latitude</td>
				<td>Coordinates of the current location.</td>
			</tr>
			<tr>
				<td>longitude</td>
				<td>Coordinates of the current location.</td>
			</tr>
            <tr>
				<td>accuracy (optional)</td>
				<td>Error radius, in miles, of the specified coordinates. (Only used for user's location tracking, not relevant to actually getting messages)</td>
			</tr>
			<tr>
			  <td>max_messages (optional)</td>
			  <td>Maximum number of messages to be returned. Defaults to 50.</td>
			</tr>
            <tr>
			  <td>radius (optional)</td>
			  <td>Radius, in miles, of the circle centered in the current user position where the messages will be searched. Defaults to 50.</td>
			</tr>
		</tbody>
	</table>
 
	<h4>Example</h4>
	<pre class="code"><?=Config::get('base_url')?>api/proximity?radius=100&amp;latitude=-34&amp;longitude=-58</pre>

	<h4>Output</h4>
	<pre class="code">{
    "data": {
        "messages": [
            {
                "type": "event",
                "like_status": 1,
                "details": {
                    "gallery": null,
                    "id": "13",
                    "location_id": "3",
                    "created_by_id": "167",
                    "edited_by_id": "167",
                    "title": "Some Awesome Event",
                    "description": "",
                    "coordinator_phone": "",
                    "coordinator_email": "",
                    "website": "http://event.example.com/",
                    "show_dates": "1",
                    "date_start": 1368363600,
                    "date_end": 1368388800
                    "date_start_str": "2013-05-12 13:00:00",
                    "date_end_str": "2013-05-12 20:00:00",
                    "status": "1",
                    "code": "EVENT-123",
                    "tags": "tag1,tag2,tag3",
                    "fb_event_id": "123318385971",
                    "foursquare_venue_id": "71248891",
                    "foursquare_event_id": "45652734",
                    "created_at": "1360789916",
                    "updated_at": "1360963396",
                    "gallery_urls": [],
                    "location": {
                        "social": {
                            "facebook": "",
                            "foursquare": "",
                            "pintrest": "",
                            "twitter": ""
                        },
                        "hours": {
                            "mon": {
                                "open": "",
                                "close": ""
                            },
                            "tue": {
                                "open": "",
                                "close": ""
                            },
                            "wed": {
                                "open": "",
                                "close": ""
                            },
                            "thr": {
                                "open": "",
                                "close": ""
                            },
                            "fri": {
                                "open": "",
                                "close": ""
                            },
                            "sat": {
                                "open": "",
                                "close": ""
                            },
                            "sun": {
                                "open": "",
                                "close": ""
                            }
                        },
                        "id": "3",
                        "type": "",
                        "status": "1",
                        "is_customer": "0",
                        "name": "Cool Location",
                        "mall_id": "32",
                        "floor": null,
                        "address": "",
                        "city": "",
                        "st": "",
                        "zip": "",
                        "contact": "",
                        "email": "",
                        "phone": "",
                        "web": "",
                        "newsletter": "",
                        "categories": "",
                        "tags": "",
                        "plan": "0",
                        "max_users": "0",
                        "content": "",
                        "logo": "",
                        "latitude": "-34.5255012512",
                        "longitude": "-58.6581001282",
                        "description": null,
                        "wifi": null,
                        "market_place_type": null,
                        "auto_generated": "0",
                        "created_at": "1360789916",
                        "updated_at": "1360789916",
                        "created_by": "162",
                        "edited_by": "162"
                    }
                }
            },
            {
                "type": "offer",
                "like_status": 0,
                "details": {
                    "gallery": null,
                    "id": "1",
                    "status": "1",
                    "name": "My awesome offer",
                    "description": "Something about this offer",
                    "price_regular": "100.00",
                    "price_offer": "50.00",
                    "savings": "50%",
                    "show_dates": "1",
                    "date_start": 1368363600,
                    "date_end": 1368388800
                    "date_start_str": "2013-05-12 13:00:00",
                    "date_end_str": "2013-05-12 20:00:00",
                    "categories": "",
                    "tags": "awesome,offer,sale",
                    "allowed_redeems": "1",
                    "multiple_codes": "0",
                    "default_code_type": "",
                    "created_at": "0",
                    "updated_at": "1361475094",
                    "created_by": "2",
                    "edited_by": "167",
                    "locations": {
                        "3": {
                            "social": {
                                "facebook": "",
                                "foursquare": "",
                                "pintrest": "",
                                "twitter": ""
                            },
                            "hours": {
                                "mon": {
                                    "open": "",
                                    "close": ""
                                },
                                "tue": {
                                    "open": "",
                                    "close": ""
                                },
                                "wed": {
                                    "open": "",
                                    "close": ""
                                },
                                "thr": {
                                    "open": "",
                                    "close": ""
                                },
                                "fri": {
                                    "open": "",
                                    "close": ""
                                },
                                "sat": {
                                    "open": "",
                                    "close": ""
                                },
                                "sun": {
                                    "open": "",
                                    "close": ""
                                }
                            },
                            "id": "3",
                            "type": "",
                            "status": "1",
                            "is_customer": "0",
                            "name": "Cool Location",
                            "mall_id": "32",
                            "floor": null,
                            "address": "",
                            "city": "",
                            "st": "",
                            "zip": "",
                            "contact": "",
                            "email": "",
                            "phone": "",
                            "web": "",
                            "newsletter": "",
                            "categories": "",
                            "tags": "",
                            "plan": "0",
                            "max_users": "0",
                            "content": "",
                            "logo": "",
                            "latitude": "-34.5255012512",
                            "longitude": "-58.6581001282",
                            "description": null,
                            "wifi": null,
                            "market_place_type": null,
                            "auto_generated": "0",
                            "created_at": "1360789916",
                            "updated_at": "1360789916",
                            "created_by": "162",
                            "edited_by": "162"
                        }
                    },
                    "gallery_urls": []
                }
            }
        ]
    },
    "meta": {
        "status": 1,
        "error": null
    }
}</pre>
</div>