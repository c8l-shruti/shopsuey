<div id="doc-content" class="mt20">
	<h1><a id="locations"></a>Get Merchants from Location</h1>
    <p>Return a list of merchants at a mall</p>

	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td width="100">Endpoint</td>
				<td><?=Config::get('base_url')?>api/location/:id/merchants</td>
			</tr>
			<tr>
				<td>Methods</td>
				<td><a href="#locations-get">GET</a></td>
			</tr>
		</tbody>
	</table>

	<h3><a id="locations-get"></a>GET</h3>
	<br>
	<h4>Parameters</h4>
	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td>id</td>
				<td>Id of the location</td>
			</tr>
			<tr>
				<td>keyword (optional)</td>
				<td>String to search on the merchant's data</td>
			</tr>
			<tr>
				<td>order_by (optional)</td>
				<td>Orders by three possible options: name, relevance or simple_relevance. Note that &quot;relevance&quot; or &quot;simple_relevance&quot; only make sense when the <strong>keyword</strong> param is passed as well</td>
			</tr>
			<tr>
				<td>page (optional)</td>
				<td>Integer value referencing results page to display.</td>
			</tr>
			<tr>
			  <td width="150">format</td>
			  <td>json | xml</td>
			</tr>
		</tbody>
	</table>

	<h4>Example</h4>
	<pre class="code"><?=Config::get('base_url')?>api/location/5/merchants/?page=1</pre>

	<h4>Output</h4>
	<pre class="code">{
    "data": {
        "merchants": [
            {
                "social": {
                    "facebook": "https://www.facebook.com/islandsole",
                    "foursquare": "",
                    "pintrest": "",
                    "twitter": "https://twitter.com/IslandSoleCA"
                },
                "hours": {
                    "mon": {
                        "open": "09:30AM",
                        "close": "09:00PM"
                    },
                    "tue": {
                        "open": "09:30AM",
                        "close": "09:00PM"
                    },
                    "wed": {
                        "open": "09:30AM",
                        "close": "09:00PM"
                    },
                    "thr": {
                        "open": "09:30AM",
                        "close": "09:00PM"
                    },
                    "fri": {
                        "open": "09:30AM",
                        "close": "09:00PM"
                    },
                    "sat": {
                        "open": "09:30AM",
                        "close": "09:00PM"
                    },
                    "sun": {
                        "open": "10:00AM",
                        "close": "07:00PM"
                    }
                },
                "use_instagram": "0",
                "id": "2479",
                "type": "Merchant",
                "status": "1",
                "is_customer": "1",
                "name": " Island Sole",
                "mall_id": "5",
                "floor": "1",
                "address": "1450 Ala Moana Boulevard",
                "city": "Honolulu",
                "st": "HI",
                "country_id": "1",
                "zip": "96814",
                "contact": "",
                "email": "",
                "phone": "808-599-9590",
                "web": "http://islandsole.com/",
                "newsletter": "",
                "tags": "slipper,flip,flop,sandles,shoes,foot,wear,sport,beach",
                "plan": "0",
                "max_users": "0",
                "content": "",
                "logo": "516638a1e521e.jpeg",
                "landing_screen_img": "5178f930e5a2b.jpg",
                "latitude": "21.2911480000",
                "longitude": "-157.8434980000",
                "timezone": "Pacific/Honolulu",
                "description": "",
                "auto_generated": null,
                "setup_complete": "1",
                "default_social": "0",
                "default_logo": "0",
                "default_landing_screen_img": "0",
                "manually_updated": "1",
                "user_instagram_id": null,
                "is_favorite": false,
                "logo_url": "http://shopsuey/assets/img/logos/small_516638a1e521e.jpeg",
                "landing_url": "http://shopsuey/assets/img/landing/large_5178f930e5a2b.jpg",
                "offers_count": 0,
                "events_count": 0
            },
            {
                "social": {
                    "facebook": "",
                    "foursquare": "",
                    "pintrest": "",
                    "twitter": ""
                },
                "hours": {
                    "mon": {
                        "open": "09:30AM",
                        "close": "09:00PM"
                    },
                    "tue": {
                        "open": "09:30AM",
                        "close": "09:00PM"
                    },
                    "wed": {
                        "open": "09:30AM",
                        "close": "09:00PM"
                    },
                    "thr": {
                        "open": "09:30AM",
                        "close": "09:00PM"
                    },
                    "fri": {
                        "open": "09:30AM",
                        "close": "09:00PM"
                    },
                    "sat": {
                        "open": "09:30AM",
                        "close": "09:00PM"
                    },
                    "sun": {
                        "open": "10:00AM",
                        "close": "07:00PM"
                    }
                },
                "use_instagram": "0",
                "id": "1279",
                "type": "Merchant",
                "status": "1",
                "is_customer": "1",
                "name": "7 For All Mankind",
                "mall_id": "5",
                "floor": "2",
                "address": "1450 Ala Moana Boulevard",
                "city": "Honolulu",
                "st": "HI",
                "country_id": "1",
                "zip": "96814",
                "contact": "",
                "email": "",
                "phone": "808.946.9622",
                "web": "http://www.7forallmankind.com/",
                "newsletter": "",
                "tags": "",
                "plan": "0",
                "max_users": "0",
                "content": "",
                "logo": "514ccd742aa35.jpg",
                "landing_screen_img": "514ccd74bd874.jpg",
                "latitude": "21.2911480000",
                "longitude": "-157.8434980000",
                "timezone": "Pacific/Honolulu",
                "description": "7 For All Mankind - The leader in premium denim now offers its collections online  Shop for womens, mens and kids products now",
                "auto_generated": null,
                "setup_complete": "1",
                "default_social": "0",
                "default_logo": "0",
                "default_landing_screen_img": "0",
                "manually_updated": "1",
                "user_instagram_id": null,
                "is_favorite": false,
                "logo_url": "http://shopsuey/assets/img/logos/small_514ccd742aa35.jpg",
                "landing_url": "http://shopsuey/assets/img/landing/large_514ccd74bd874.jpg",
                "offers_count": 0,
                "events_count": 0
            },

            . . .

        ]
    },
    "meta": {
        "pagination": {
            "limit": 20,
            "offset": {
                "current": 0,
                "next": 20,
                "prev": null
            },
            "page": {
                "count": 15,
                "current": "1",
                "next": 2,
                "prev": null
            },
            "records": 295
        },
        "status": 1,
        "error": null
    }
}</pre>
</div>