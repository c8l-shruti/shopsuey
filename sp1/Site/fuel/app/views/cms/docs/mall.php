<div id="doc-content" class="mt20">
	<h1><a id="mall"></a>Mall</h1>

	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td width="100">Endpoint</td>
				<td><?=Config::get('base_url')?>api/mall/</td>
			</tr>
			<tr>
				<td>Methods</td>
				<td><a href="#mall-get">GET</a></td>
			</tr>
		</tbody>
	</table>

	<h3><a id="mall-get"></a>GET</h3>
	<p>Get a single mall</p>
	<br>
	<h4>Parameters</h4>
	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
			  <td width="100">format</td>
			  <td>json | xml</td>
			</tr>
		</tbody>
	</table>

	<h4>Example</h4>
	<pre class="code"><?=Config::get('base_url')?>api/mall/1</pre>

	<h4>Output</h4>
	<pre class="code">{
    "data": {
        "mall": {
            "social": {
                "facebook": "https://www.facebook.com/AlaMoanaCenter",
                "foursquare": "",
                "pintrest": "",
                "twitter": "https://twitter.com/alamoanacenter"
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
            "id": "5",
            "type": "Mall",
            "status": "1",
            "is_customer": "1",
            "name": "Ala Moana Center",
            "address": "1450 Ala Moana Boulevard",
            "city": "Honolulu",
            "st": "HI",
            "country_id": "1",
            "zip": "96814",
            "contact": "",
            "email": "",
            "phone": "(808) 955-9517",
            "web": "http://www.alamoanacenter.com/",
            "newsletter": "",
            "tags": "Ala,Moana,Ala Moana",
            "plan": "0",
            "max_users": "0",
            "content": "",
            "logo": "513eca8ec2105.jpg",
            "landing_screen_img": "5217c74184590.jpg",
            "latitude": "21.2911480000",
            "longitude": "-157.8434980000",
            "timezone": "Pacific/Honolulu",
            "description": "As you may have noticed, the Street Level, Center Court renovation project at Ala Moana Center has begun! The project will be completed in two phases and will include the replacement of common area finishes, brand new Centerstage and Street Level public restrooms, new tenant spaces and a larger, single mall entrance. This exciting project is expected to be completed in advance of the 2013 holiday season with the majority of the renovation taking place at night to minimize the impact on business operations. During this time, the Ala Moana Hula Show will continue daily at Center Court on Mall Level at 1 p.m. All other performances will be at the Centertainment Stage in the Nordstrom Wing, Level 3. We are excited about the Center Court Redevelopment project, as it will be another step toward preserving Ala Moana Center’s status as Hawaii’s premier shopping destination.",
            "wifi": null,
            "market_place_type": "",
            "auto_generated": null,
            "setup_complete": "1",
            "default_social": "0",
            "default_logo": "0",
            "default_landing_screen_img": "1",
            "manually_updated": "1",
            "user_instagram_id": null,
            "is_favorite": true,
            "micello_info": {
                "map": null,
                "updated_at": 1380036217,
                "id": "1070",
                "location_id": "5",
                "micello_id": "586",
                "type": "community",
                "map_expiracy": "2013-09-24 16:23:37",
                "map_version": "56",
                "geometry_id": null,
                "created_at": "1362533371"
            },
            "categories": [],
            "logo_url": "http://shopsuey/assets/img/logos/small_513eca8ec2105.jpg",
            "landing_url": "http://shopsuey/assets/img/landing/large_5217c74184590.jpg",
            "merchant_count": 297,
            "offers_count": 7,
            "events_count": 3
        }
    },
    "meta": {
        "error": null,
        "status": 1
    }
}</pre>


</div>