<div id="doc-content" class="mt20">
	<h1><a id="event"></a>Get Special Event Locations</h1>

	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td width="100">Endpoint</td>
				<td><?=Config::get('base_url')?>api/specialevent/:id/locations</td>
			</tr>
			<tr>
				<td>Methods</td>
				<td><a href="#event-locations-get">GET</a></td>
			</tr>
		</tbody>
	</table>

	<h3><a id="event-locations-get"></a>POST</h3>
	<p>Get all locations (except the main location) for the special event</p>
	<br>
	<h4>Parameters</h4>
	<table class="grid" cellspacing="2" cellpadding="5" border="1">

	</table>

	<h4>Example</h4>
	<pre class="code"><?=Config::get('base_url')?>api/specialevent/1/locations</pre>

	<h4>Output</h4>
	<pre class="code">{
    "data": {
        "locations": [
            {
                "social": {
                    "facebook": "",
                    "foursquare": "",
                    "pintrest": "",
                    "twitter": ""
                },
                "hours": {
                    "mon": {
                        "open": "10:00AM",
                        "close": "08:30PM"
                    },
                    "tue": {
                        "open": "10:00AM",
                        "close": "08:30PM"
                    },
                    "wed": {
                        "open": "10:00AM",
                        "close": "08:30PM"
                    },
                    "thr": {
                        "open": "10:00AM",
                        "close": "08:30PM"
                    },
                    "fri": {
                        "open": "10:00AM",
                        "close": "08:30PM"
                    },
                    "sat": {
                        "open": "10:00AM",
                        "close": "08:30PM"
                    },
                    "sun": {
                        "open": "10:00AM",
                        "close": "06:00PM"
                    }
                },
                "use_instagram": "0",
                "id": "1042",
                "type": "Merchant",
                "status": "1",
                "is_customer": "1",
                "name": "Banana Republic",
                "mall_id": "940",
                "floor": "3",
                "address": "865 Market St.",
                "city": "San Francisco",
                "st": "CA",
                "country_id": "1",
                "zip": "94103",
                "contact": "",
                "email": "",
                "phone": ": 415.546.0340",
                "web": "http://www.bananarepublic.com",
                "newsletter": "",
                "tags": "",
                "plan": "0",
                "max_users": "0",
                "content": "Banana Republic delivers elevated design and luxurious fabrications in coveted, uncomplicated style. Banana Republic offers exceptional essentials and sophisticated seasonal collections of accessories, shoes, personal care products and intimate apparel.",
                "logo": "515009623c081.jpg",
                "landing_screen_img": "51410dc40b500.jpg",
                "latitude": "37.7840850000",
                "longitude": "-122.4061070000",
                "timezone": "America/Los_Angeles",
                "description": "",
                "wifi": null,
                "market_place_type": null,
                "auto_generated": null,
                "setup_complete": "0",
                "default_logo": "0",
                "default_landing_screen_img": "0",
                "manually_updated": "1",
                "user_instagram_id": null,
                "is_favorite": false,
                "micello_info": {
                    "map": null,
                    "updated_at": 1362208332,
                    "id": "1020",
                    "location_id": "1042",
                    "micello_id": "273188",
                    "type": "entity",
                    "map_expiracy": null,
                    "map_version": null,
                    "geometry_id": "750237",
                    "created_at": "1362208332"
                },
                "logo_url": "http://shopsuey/assets/img/logos/small_515009623c081.jpg",
                "landing_url": "http://shopsuey/assets/img/landing/large_51410dc40b500.jpg"
            }
        ]
    },
    "meta": {
        "error": null,
        "status": 1
    }
}</pre>
</div>