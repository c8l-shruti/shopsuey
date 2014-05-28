<div id="doc-content" class="mt20">
    <h1><a id="user-reset"></a>User - Get Favorites</h1>
    <p>Returns a list of favorite marketplaces and merchants for the defined user.</p>
    <table class="grid" cellspacing="2" cellpadding="5" border="1">
        <tbody>
            <tr>
                <td width="100">Methods</td>
                <td><a>GET</a></td>
            </tr>
        </tbody>
    </table>

    <h3><a id="user-reset-method"></a>GET</h3>
    <br>
    
    <h4>Endpoint</h4>
    <pre class="code"><?= Uri::create('api/user/id/favorites') ?></pre>
    <br>
    
    <h4>Parameters</h4>
    <table class="grid" cellspacing="2" cellpadding="5" border="1">
        <tbody>
            <tr>
                <td>id</td>
                <td>The user identifier</td>
            </tr>
            <tr>
                <td>malls_only</td>
                <td>Bool that filter the favorite locations to get only marketplaces</td>
            </tr>
            <tr>
                <td>merchants_only</td>
                <td>Bool that filter the favorite locations to get only merchants</td>
            </tr>
            <tr>
                <td>keyword</td>
                <td>(optional) Filter favorite locations whose name / address / description matches the keyword</td>
            </tr>
        </tbody>
    </table>

    <h4>Example</h4>
    <pre class="code"><?= Uri::create('api/user/180/favorites') ?></pre>
    <br>
    
    <h4>Output</h4>
    <pre class="code">{
    "data": {
        "status": true,
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
                "updated_at": 1368645348,
                "id": "5",
                "type": "Mall",
                "status": "1",
                "is_customer": "1",
                "name": "Ala Moana Center",
                "mall_id": "0",
                "floor": null,
                "address": "1450 Ala Moana Boulevard",
                "city": "Honolulu",
                "st": "HI",
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
                "landing_screen_img": "5140fca078778.jpeg",
                "latitude": "21.2911480000",
                "longitude": "-157.8434980000",
                "description": "As you may have noticed, the Street Level, Center Court renovation project at Ala Moana Center has begun! The project will be completed in two phases and will include the replacement of common area finishes, brand new Centerstage and Street Level public restrooms, new tenant spaces and a larger, single mall entrance. This exciting project is expected to be completed in advance of the 2013 holiday season with the majority of the renovation taking place at night to minimize the impact on business operations. During this time, the Ala Moana Hula Show will continue daily at Center Court on Mall Level at 1 p.m. All other performances will be at the Centertainment Stage in the Nordstrom Wing, Level 3. We are excited about the Center Court Redevelopment project, as it will be another step toward preserving Ala Moana Center’s status as Hawaii’s premier shopping destination.",
                "wifi": null,
                "market_place_type": "",
                "auto_generated": null,
                "setup_complete": "1",
                "default_logo": "0",
                "default_landing_screen_img": "0",
                "manually_updated": "1",
                "created_at": "2012",
                "created_by": "157",
                "edited_by": "188",
                "micello_info": {
                    "map": null,
                    "updated_at": 1369155539,
                    "id": "1070",
                    "location_id": "5",
                    "micello_id": "586",
                    "type": "community",
                    "map_expiracy": "2013-05-09 20:32:12",
                    "map_version": null,
                    "geometry_id": null,
                    "created_at": "1362533371"
                }
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
                "id": "5",
                "type": "Mall",
                "status": "1",
                "is_customer": "1",
                "name": "Ala Moana Center",
                "mall_id": "0",
                "floor": null,
                "address": "1450 Ala Moana Boulevard",
                "city": "Honolulu",
                "st": "HI",
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
                "landing_screen_img": "5140fca078778.jpeg",
                "latitude": "21.2911480000",
                "longitude": "-157.8434980000",
                "description": "As you may have noticed, the Street Level, Center Court renovation project at Ala Moana Center has begun! The project will be completed in two phases and will include the replacement of common area finishes, brand new Centerstage and Street Level public restrooms, new tenant spaces and a larger, single mall entrance. This exciting project is expected to be completed in advance of the 2013 holiday season with the majority of the renovation taking place at night to minimize the impact on business operations. During this time, the Ala Moana Hula Show will continue daily at Center Court on Mall Level at 1 p.m. All other performances will be at the Centertainment Stage in the Nordstrom Wing, Level 3. We are excited about the Center Court Redevelopment project, as it will be another step toward preserving Ala Moana Center’s status as Hawaii’s premier shopping destination.",
                "wifi": null,
                "market_place_type": "",
                "auto_generated": null,
                "setup_complete": "1",
                "default_logo": "0",
                "default_landing_screen_img": "0",
                "manually_updated": "1",
                "is_favorite": false,
                "micello_info": {
                    "map": null,
                    "updated_at": 1369155539,
                    "id": "1070",
                    "location_id": "5",
                    "micello_id": "586",
                    "type": "community",
                    "map_expiracy": "2013-05-09 20:32:12",
                    "map_version": null,
                    "geometry_id": null,
                    "created_at": "1362533371"
                },
                "logo_url": "http://shopsuey/assets/img/logos/small_513eca8ec2105.jpg",
                "landing_url": "http://shopsuey/assets/img/landing/large_5140fca078778.jpeg"
            }
        ]
    },
    "meta": {
        "error": "",
        "status": 1
    }
}
    </pre>
</div>