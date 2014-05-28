<div id="doc-content" class="mt20">
    <h1><a id="user-reset"></a>User - Get Votes</h1>
    <p>Returns a list of votes over different offers/events for the defined user</p>
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
    <pre class="code"><?= Uri::create('api/user/id/votes') ?></pre>
    <br>
    
    <h4>Parameters</h4>
    <table class="grid" cellspacing="2" cellpadding="5" border="1">
        <tbody>
            <tr>
                <td>id</td>
                <td>The user identifier</td>
            </tr>
            <tr>
                <td>like_status</td>
                <td>Filter the offers/events by their like_status. The possible values could be 1 (vote up) or -1 (vote down)</td>
            </tr>
        </tbody>
    </table>

    <h4>Example</h4>
    <pre class="code"><?= Uri::create('api/user/180/votes') ?></pre>
    <br>
    
    <h4>Output</h4>
    <pre class="code">{
    "data": {
        "status": true,
        "events": [
            {
                "status": "-1",
                "event": {
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
                                    "open": "",
                                    "close": ""
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
                            "id": "2478",
                            "type": "Merchant",
                            "status": "0",
                            "is_customer": "0",
                            "name": "Ala Moana",
                            "mall_id": "5",
                            "floor": "1",
                            "address": "1450 Ala Moana Boulevard",
                            "city": "Honolulu",
                            "st": "HI",
                            "zip": "96814",
                            "contact": "",
                            "email": "",
                            "phone": "",
                            "web": "",
                            "newsletter": "",
                            "tags": "",
                            "plan": "0",
                            "max_users": "0",
                            "content": "",
                            "logo": "",
                            "landing_screen_img": "",
                            "latitude": "21.2911480000",
                            "longitude": "-157.8434980000",
                            "description": "",
                            "wifi": null,
                            "market_place_type": null,
                            "auto_generated": null,
                            "setup_complete": "0",
                            "default_logo": "0",
                            "default_landing_screen_img": "0",
                            "manually_updated": "0",
                            "is_favorite": false,
                            "micello_info": {
                                "map": null,
                                "updated_at": 1364863522,
                                "id": "2454",
                                "location_id": "2478",
                                "micello_id": "816271",
                                "type": "entity",
                                "map_expiracy": null,
                                "map_version": null,
                                "geometry_id": "89567",
                                "created_at": "1364863522"
                            },
                            "logo_url": "",
                            "landing_url": ""
                        }
                    ]
                }
            }
        ],
        "offers": [
            {
                "status": "1",
                "offer": {
                    "gallery": [
                        "516920473e527.png"
                    ],
                    "type": "0",
                    "updated_at": 1366614592,
                    "id": "25",
                    "status": "1",
                    "name": "OLD NAVY | Military Discount",
                    "description": "Military Personnel can take 10% off their entire in-store purchase every Monday with proof of a valid Military ID. \r\n\r\n(excludes gift cards).",
                    "price_regular": "0.00",
                    "price_offer": "",
                    "savings": "",
                    "show_dates": 0,
                    "date_start": 1366018200,
                    "date_end": 1388437200,
                    "categories": "",
                    "tags": "",
                    "redeemable": "0",
                    "allowed_redeems": "0",
                    "multiple_codes": "0",
                    "default_code_type": "",
                    "force_top_message": "0",
                    "created_at": "1365844041",
                    "created_by": "212",
                    "edited_by": "140",
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
                            "id": "1186",
                            "type": "Merchant",
                            "status": "1",
                            "is_customer": "1",
                            "name": "Old Navy",
                            "mall_id": "5",
                            "floor": "1",
                            "address": "1450 Ala Moana Boulevard",
                            "city": "Honolulu",
                            "st": "HI",
                            "zip": "96814",
                            "contact": "",
                            "email": "",
                            "phone": "808-951-9938",
                            "web": "http://www.oldnavy.com",
                            "newsletter": "",
                            "tags": "",
                            "plan": "0",
                            "max_users": "0",
                            "content": "Check out the best in denim and T-shirts, plus great clothes at great prices for the whole family.",
                            "logo": "516bb04d990f2.png",
                            "landing_screen_img": "516bb0501e26d.png",
                            "latitude": "21.2911480000",
                            "longitude": "-157.8434980000",
                            "description": "",
                            "wifi": null,
                            "market_place_type": null,
                            "auto_generated": null,
                            "setup_complete": "0",
                            "default_logo": "0",
                            "default_landing_screen_img": "0",
                            "manually_updated": "1",
                            "is_favorite": false,
                            "micello_info": {
                                "map": null,
                                "updated_at": 1364863520,
                                "id": "1164",
                                "location_id": "1186",
                                "micello_id": "62567",
                                "type": "entity",
                                "map_expiracy": null,
                                "map_version": null,
                                "geometry_id": "89564",
                                "created_at": "1363069589"
                            },
                            "logo_url": "http://shopsuey/assets/img/logos/small_516bb04d990f2.png",
                            "landing_url": "http://shopsuey/assets/img/landing/large_516bb0501e26d.png"
                        },
                        {
                            "id": "2837",
                            "type": "Merchant",
                            "status": "1",
                            "is_customer": "0",
                            "name": "Old Navy",
                            "mall_id": "2817",
                            "floor": "1",
                            "address": "",
                            "city": "",
                            "st": "",
                            "zip": "",
                            "contact": "",
                            "email": "",
                            "phone": "",
                            "web": "",
                            "newsletter": "",
                            "tags": "",
                            "plan": "0",
                            "max_users": "0",
                            "content": "",
                            "logo": "",
                            "landing_screen_img": null,
                            "latitude": "21.3992970000",
                            "longitude": "-158.0071090000",
                            "description": null,
                            "wifi": null,
                            "market_place_type": null,
                            "auto_generated": "1",
                            "setup_complete": "0",
                            "default_logo": "0",
                            "default_landing_screen_img": "0",
                            "manually_updated": "0",
                            "is_favorite": false,
                            "micello_info": {
                                "map": null,
                                "updated_at": 1365057683,
                                "id": "2813",
                                "location_id": "2837",
                                "micello_id": "871282",
                                "type": "entity",
                                "map_expiracy": null,
                                "map_version": null,
                                "geometry_id": "4004522",
                                "created_at": "1365057683"
                            },
                            "logo_url": "",
                            "landing_url": ""
                        }
                    ],
                    "gallery_urls": [
                        {
                            "original": "http://shopsuey/assets/img/offers/516920473e527.png",
                            "small": "http://shopsuey/assets/img/offers/small_516920473e527.png",
                            "large": "http://shopsuey/assets/img/offers/large_516920473e527.png"
                        }
                    ],
                    "date_start_str": "2013-04-15 09:30:00",
                    "date_end_str": "2013-12-30 21:00:00"
                }
            }
        ],
        "flags": [
            {
                "status": "1",
                "flag": {
                    "id": "1",
                    "type": "Aloha",
                    "title": "Some Flag",
                    "private": "0",
                    "description": null,
                    "latitude": "-30",
                    "longitude": "-30",
                    "location_id": null,
                    "location_type": null,
                    "image": "",
                    "owner": "188",
                    "vote_status": "1"
                }
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