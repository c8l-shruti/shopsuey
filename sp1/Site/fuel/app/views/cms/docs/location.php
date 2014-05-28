<div id="doc-content" class="mt20">
    <h1><a id="mall"></a>Location</h1>

    <table class="grid" cellspacing="2" cellpadding="5" border="1">
        <tbody>
            <tr>
                <td width="100">Endpoint</td>
                <td><?= Config::get('base_url') ?>api/location/(id)</td>
            </tr>
            <tr>
                <td>Methods</td>
                <td><a href="#mall-get">GET</a></td>
            </tr>
        </tbody>
    </table>

    <h3><a id="mall-get"></a>GET</h3>
    <p>Get a single location</p>
    <br>
    <h4>Parameters</h4>
    <table class="grid" cellspacing="2" cellpadding="5" border="1">
        <tbody>
            <tr>
                <td width="100">include_micello_info</td>
                <td>Set this parameter to 1 if you want to receive the micello_info key in the response.</td>
            </tr>
            <tr>
                <td width="100">format</td>
                <td>json | xml</td>
            </tr>
        </tbody>
    </table>

    <h4>Example</h4>
    <pre class="code"><?= Config::get('base_url') ?>api/location/5?include_micello_info=1</pre>

    <h4>Output</h4>
    <pre class="code">{
    "data": {
        "location": {
            "is_customer": "1",
            "social": {
                "facebook": "https://www.facebook.com/AlaMoanaCenter",
                "foursquare": "",
                "twitter": "@alamoanacenter"
            },
            "hours": {
                "mon": {
                    "open": "08:00AM",
                    "close": "08:00PM"
                },
                "tue": {
                    "open": "08:00AM",
                    "close": "08:00PM"
                },
                "wed": {
                    "open": "08:00AM",
                    "close": "08:00PM"
                },
                "thr": {
                    "open": "02:00AM",
                    "close": "08:00PM"
                },
                "fri": {
                    "open": "08:00AM",
                    "close": "08:00PM"
                },
                "sat": {
                    "open": "08:00AM",
                    "close": "08:00PM"
                },
                "sun": {
                    "open": "10:00AM",
                    "close": "08:00PM"
                }
            },
            "use_instagram": "0",
            "id": "5",
            "type": "Mall",
            "status": "1",
            "name": "Ala Moana Center",
            "mall_id": "0",
            "floor": null,
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
            "description": "",
            "wifi": null,
            "market_place_type": "",
            "auto_generated": null,
            "setup_complete": "1",
            "default_social": "0",
            "default_logo": "0",
            "default_landing_screen_img": "1",
            "manually_updated": "1",
            "user_instagram_id": null,
            "is_favorite": false,
            "micello_info": {
                "map": null,
                "updated_at": 1388078072,
                "id": "1070",
                "location_id": "5",
                "micello_id": "586",
                "type": "community",
                "map_expiracy": "2013-12-26 18:14:32",
                "map_version": "74",
                "geometry_id": null,
                "created_at": "1362533371"
            },
            "categories": [],
            "logo_url": "http://shopsuey/assets/img/logos/small_513eca8ec2105.jpg",
            "landing_url": "http://shopsuey/assets/img/landing/large_5217c74184590.jpg"
        }
    },
    "meta": {
        "error": null,
        "status": 1
    }
}</pre>
</div>