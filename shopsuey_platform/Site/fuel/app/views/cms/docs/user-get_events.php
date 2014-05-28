<div id="doc-content" class="mt20">
    <h1><a id="user-reset"></a>User - Get Events</h1>
    <p>Retrieve the events for a specific user (Only RSVPed)</p>
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
    <pre class="code"><?= Uri::create('api/user/id/events') ?></pre>
    <br>
    
    <h4>Parameters</h4>
    <table class="grid" cellspacing="2" cellpadding="5" border="1">
        <tbody>
            <tr>
                <td>id</td>
                <td>The user identifier</td>
            </tr>
        </tbody>
    </table>

    <h4>Example</h4>
    <pre class="code"><?= Uri::create('api/user/180/events') ?></pre>
    <br>
    
    <h4>Output</h4>
    <pre class="code">{
    "data": {
        "events": [
            {
                "gallery": [
                    "514123bbcdc83.jpg"
                ],
                "updated_at": 1364494742,
                "id": "5",
                "created_by_id": "140",
                "edited_by_id": "140",
                "title": "T&C - Hurley Promotion",
                "description": "Free $20 Hurley Gift Card with any $40 purchase of Hurley men’s apparel or accessories.",
                "featured_image": null,
                "coupon_image": null,
                "coordinator_phone": "",
                "coordinator_email": "",
                "website": "http://www.kahalamallcenter.com/promotions",
                "show_dates": 0,
                "date_start": 1364468400,
                "date_end": 1367449200,
                "status": "1",
                "code": "",
                "tags": "Hurley,Surf,T&C,Shirts",
                "fb_event_id": "",
                "foursquare_venue_id": "",
                "foursquare_event_id": "",
                "force_top_message": "0",
                "created_at": "1363223484",
                "gallery_urls": [
                    {
                        "original": "http://shopsuey/assets/img/events/514123bbcdc83.jpg",
                        "small": "http://shopsuey/assets/img/events/small_514123bbcdc83.jpg",
                        "large": "http://shopsuey/assets/img/events/large_514123bbcdc83.jpg"
                    }
                ],
                "date_start_str": "2013-03-28 11:00:00",
                "date_end_str": "2013-05-01 23:00:00",
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
                                "close": "09:00PM"
                            },
                            "tue": {
                                "open": "10:00AM",
                                "close": "09:00PM"
                            },
                            "wed": {
                                "open": "10:00AM",
                                "close": "09:00PM"
                            },
                            "thr": {
                                "open": "10:00AM",
                                "close": "09:00PM"
                            },
                            "fri": {
                                "open": "10:00AM",
                                "close": "09:00PM"
                            },
                            "sat": {
                                "open": "10:00AM",
                                "close": "09:00PM"
                            },
                            "sun": {
                                "open": "10:00AM",
                                "close": "06:00PM"
                            }
                        },
                        "id": "1384",
                        "type": "Merchant",
                        "status": "1",
                        "is_customer": "1",
                        "name": "T&C Surf Designs",
                        "mall_id": "15",
                        "floor": "1",
                        "address": "4211 Waialae Avenue",
                        "city": "Honolulu",
                        "st": "HI",
                        "zip": "96816",
                        "contact": "",
                        "email": "",
                        "phone": "808-733-5699",
                        "web": "http://www.tcsurf.com",
                        "newsletter": "",
                        "tags": "",
                        "plan": "0",
                        "max_users": "0",
                        "content": "",
                        "logo": "51660dea02f6f.png",
                        "landing_screen_img": "51660deaa9f4f.gif",
                        "latitude": "21.2769280000",
                        "longitude": "-157.7861170000",
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
                            "updated_at": 1363070228,
                            "id": "1362",
                            "location_id": "1384",
                            "micello_id": "76295",
                            "type": "entity",
                            "map_expiracy": null,
                            "map_version": null,
                            "geometry_id": "142873",
                            "created_at": "1363070228"
                        },
                        "logo_url": "http://shopsuey/assets/img/logos/small_51660dea02f6f.png",
                        "landing_url": "http://shopsuey/assets/img/landing/large_51660deaa9f4f.gif"
                    }
                ]
            },
            {
                "gallery": [
                    "514139c4d0a1e.png"
                ],
                "updated_at": 1365837577,
                "id": "8",
                "created_by_id": "169",
                "edited_by_id": "212",
                "title": "Whole Foods Daily Events & Classes",
                "description": "Whole Foods Market at Kahala Mall is open daily from 7:00 AM to 10:00 PM. All events are free and open to the public. Class sizes are limited. For any questions, or to sign up in advance, please go to Whole Foods Market's Customer Service Desk or call (808) 738-0820.",
                "featured_image": null,
                "coupon_image": null,
                "coordinator_phone": "",
                "coordinator_email": "",
                "website": "http://wholefoodsmarket.com/events?store=6591",
                "show_dates": 0,
                "date_start": 1365739200,
                "date_end": 1367607600,
                "status": "1",
                "code": "",
                "tags": "",
                "fb_event_id": "",
                "foursquare_venue_id": "",
                "foursquare_event_id": "",
                "force_top_message": "0",
                "created_at": "1363229125",
                "gallery_urls": [
                    {
                        "original": "http://shopsuey/assets/img/events/514139c4d0a1e.png",
                        "small": "http://shopsuey/assets/img/events/small_514139c4d0a1e.png",
                        "large": "http://shopsuey/assets/img/events/large_514139c4d0a1e.png"
                    }
                ],
                "date_start_str": "2013-04-12 04:00:00",
                "date_end_str": "2013-05-03 19:00:00",
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
                                "open": "07:00AM",
                                "close": "10:00PM"
                            },
                            "tue": {
                                "open": "07:00AM",
                                "close": "10:00PM"
                            },
                            "wed": {
                                "open": "07:00AM",
                                "close": "10:00PM"
                            },
                            "thr": {
                                "open": "07:00AM",
                                "close": "10:00PM"
                            },
                            "fri": {
                                "open": "07:00AM",
                                "close": "10:00PM"
                            },
                            "sat": {
                                "open": "07:00AM",
                                "close": "10:00PM"
                            },
                            "sun": {
                                "open": "07:00AM",
                                "close": "10:00PM"
                            }
                        },
                        "id": "1428",
                        "type": "Merchant",
                        "status": "1",
                        "is_customer": "1",
                        "name": "Whole Foods Market",
                        "mall_id": "15",
                        "floor": "1",
                        "address": "4211 Waialae Avenue",
                        "city": "Honolulu",
                        "st": "HI",
                        "zip": "96816",
                        "contact": "Lindsay Mucha",
                        "email": "",
                        "phone": "808-738-0820",
                        "web": "http://www.wholefoods.com",
                        "newsletter": "",
                        "tags": "groceries,food,organic,lifestyle,health,beauty",
                        "plan": "0",
                        "max_users": "0",
                        "content": "Market & Specialty Foods",
                        "logo": "5167b7bc84f05.jpeg",
                        "landing_screen_img": "5167b7bca76be.png",
                        "latitude": "21.2769280000",
                        "longitude": "-157.7861170000",
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
                            "updated_at": 1363070228,
                            "id": "1406",
                            "location_id": "1428",
                            "micello_id": "76315",
                            "type": "entity",
                            "map_expiracy": null,
                            "map_version": null,
                            "geometry_id": "142920",
                            "created_at": "1363070228"
                        },
                        "logo_url": "http://shopsuey/assets/img/logos/small_5167b7bc84f05.jpeg",
                        "landing_url": "http://shopsuey/assets/img/landing/large_5167b7bca76be.png"
                    }
                ]
            },
            {
                "gallery": [
                    "5153ee793a2ae.jpg"
                ],
                "updated_at": 1364455033,
                "id": "14",
                "created_by_id": "140",
                "edited_by_id": "140",
                "title": "Ke Oahu - Live Entertainment",
                "description": "Royal Hawaiian Center's Pau Hana Jam showcases the best in Hawaiian Music and Hula 'Auana as part of our award-winning Hawaiian cultural programming which features top island artists and performing groups.",
                "featured_image": null,
                "coupon_image": null,
                "coordinator_phone": "",
                "coordinator_email": "",
                "website": "",
                "show_dates": 0,
                "date_start": 1364932800,
                "date_end": 1367528400,
                "status": "1",
                "code": "",
                "tags": "",
                "fb_event_id": "",
                "foursquare_venue_id": "",
                "foursquare_event_id": "",
                "force_top_message": "0",
                "created_at": "1364455033",
                "gallery_urls": [
                    {
                        "original": "http://shopsuey/assets/img/events/5153ee793a2ae.jpg",
                        "small": "http://shopsuey/assets/img/events/small_5153ee793a2ae.jpg",
                        "large": "http://shopsuey/assets/img/events/large_5153ee793a2ae.jpg"
                    }
                ],
                "date_start_str": "2013-04-02 20:00:00",
                "date_end_str": "2013-05-02 21:00:00",
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
                                "close": "10:00PM"
                            },
                            "tue": {
                                "open": "10:00AM",
                                "close": "10:00PM"
                            },
                            "wed": {
                                "open": "10:00AM",
                                "close": "10:00PM"
                            },
                            "thr": {
                                "open": "10:00AM",
                                "close": "10:00PM"
                            },
                            "fri": {
                                "open": "10:00AM",
                                "close": "10:00PM"
                            },
                            "sat": {
                                "open": "10:00AM",
                                "close": "10:00PM"
                            },
                            "sun": {
                                "open": "10:00AM",
                                "close": "10:00PM"
                            }
                        },
                        "id": "3",
                        "type": "Mall",
                        "status": "1",
                        "is_customer": "1",
                        "name": "Royal Hawaiian Shopping Center",
                        "mall_id": null,
                        "floor": null,
                        "address": "2201 Kalakaua Avenue",
                        "city": "Honolulu",
                        "st": "HI",
                        "zip": "96815",
                        "contact": "",
                        "email": "",
                        "phone": "(808) 922-2299.",
                        "web": "http://www.royalhawaiiancenter.com/",
                        "newsletter": "",
                        "tags": "",
                        "plan": "0",
                        "max_users": "0",
                        "content": "",
                        "logo": "515497b2605ea.jpg",
                        "landing_screen_img": "515497b2d7284.jpg",
                        "latitude": "21.2785180000",
                        "longitude": "-157.8289840000",
                        "description": "Royal Hawaiian Center offers 310,000 square feet of delight for Hawaii shoppers. With its more than 110 shops and restaurants, the Center is one of Hawaii’s largest shopping malls. Along the four-tiered three buildings, you can purchase everything from fine designer apparel to fun-in-the-sun apparel, from fine jewelry to costume jewelry and Hawaiian treasures, from fine dining in many restaurants to dining on hot dogs and ice cream. There are boutiques, sporting-good stores, Hawaii’s top surf shops, jewelry stores, craft shops and practically everything else conceivable — all in the very center of Waikiki.All profits from Royal Hawaiian Center go to the education of Hawaiian students. The Center is owned by Kamehameha Schools.",
                        "wifi": "",
                        "market_place_type": "",
                        "auto_generated": null,
                        "setup_complete": "0",
                        "default_logo": "0",
                        "default_landing_screen_img": "0",
                        "manually_updated": "0",
                        "is_favorite": false,
                        "micello_info": {
                            "map": null,
                            "updated_at": 1369344311,
                            "id": "1597",
                            "location_id": "3",
                            "micello_id": "19501",
                            "type": "community",
                            "map_expiracy": "2013-05-08 19:42:21",
                            "map_version": null,
                            "geometry_id": null,
                            "created_at": "1363224249"
                        },
                        "logo_url": "http://shopsuey/assets/img/logos/small_515497b2605ea.jpg",
                        "landing_url": "http://shopsuey/assets/img/landing/large_515497b2d7284.jpg"
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
                                "open": "10:00AM",
                                "close": "10:00PM"
                            },
                            "tue": {
                                "open": "10:00AM",
                                "close": "10:00PM"
                            },
                            "wed": {
                                "open": "10:00AM",
                                "close": "10:00PM"
                            },
                            "thr": {
                                "open": "10:00AM",
                                "close": "10:00PM"
                            },
                            "fri": {
                                "open": "10:00AM",
                                "close": "10:00PM"
                            },
                            "sat": {
                                "open": "10:00AM",
                                "close": "10:00PM"
                            },
                            "sun": {
                                "open": "10:00AM",
                                "close": "10:00PM"
                            }
                        },
                        "id": "1658",
                        "type": "Merchant",
                        "status": "1",
                        "is_customer": "1",
                        "name": "Apple Store",
                        "mall_id": "3",
                        "floor": "1",
                        "address": "2201 Kalakaua Avenue",
                        "city": "Honolulu",
                        "st": "HI",
                        "zip": "96815",
                        "contact": "",
                        "email": "",
                        "phone": "(808) 931-2480",
                        "web": "http://www.apple.com",
                        "newsletter": "",
                        "tags": "",
                        "plan": "0",
                        "max_users": "0",
                        "content": "Visit Apple's fifth flagship store here in Royal Hawaiian Center for a wide selection of innovative Apple products including: the imac, ipod, iphone, Apple TV, software, and accessories",
                        "logo": "514fa30d96deb.jpeg",
                        "landing_screen_img": "514fa30e0a4ee.jpg",
                        "latitude": "21.2785180000",
                        "longitude": "-157.8289840000",
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
                            "updated_at": 1364502971,
                            "id": "1636",
                            "location_id": "1658",
                            "micello_id": "834216",
                            "type": "entity",
                            "map_expiracy": null,
                            "map_version": null,
                            "geometry_id": "3548536",
                            "created_at": "1363285461"
                        },
                        "logo_url": "http://shopsuey/assets/img/logos/small_514fa30d96deb.jpeg",
                        "landing_url": "http://shopsuey/assets/img/landing/large_514fa30e0a4ee.jpg"
                    }
                ]
            },
            {
                "gallery": [
                    "51692ec46b2cb.jpg"
                ],
                "updated_at": 1367958021,
                "id": "27",
                "created_by_id": "212",
                "edited_by_id": "326",
                "title": "Ala Moana Center | APRIL SIDEWALK SALE",
                "description": "Shop to Support Schools during Ala Moana APRIL SIDEWALK SALE!\r\n\r\nAla Moana Center has partnered with DonorsChoose.org, a non-profit organization, to help fund classroom projects in our community. Customers who spend $75 or more during the Sidewalk Sale will receive a $10 Donors Choose Gift Card that can be used to donate to any school with a project listed. Visit DonorsChoose.org for a list of current projects. \r\n\r\nWhile supplies last. Limit one DonorsChoose.org Gift Card per person. Receipts must be dated 4/19/13 – 4/21/13 and redeemed at the Customer Service Center within the same time period. Must be 13 years of age or older to participate. Gift card purchases are excluded. No more than 3 receipts per store are allowed. Other restrictions may apply.",
                "featured_image": null,
                "coupon_image": null,
                "coordinator_phone": "",
                "coordinator_email": "",
                "website": "",
                "show_dates": 0,
                "date_start": 1366390800,
                "date_end": 1366567200,
                "status": "0",
                "code": "",
                "tags": "",
                "fb_event_id": "",
                "foursquare_venue_id": "",
                "foursquare_event_id": "",
                "force_top_message": "0",
                "created_at": "1365847748",
                "gallery_urls": [
                    {
                        "original": "http://shopsuey/assets/img/events/51692ec46b2cb.jpg",
                        "small": "http://shopsuey/assets/img/events/small_51692ec46b2cb.jpg",
                        "large": "http://shopsuey/assets/img/events/large_51692ec46b2cb.jpg"
                    }
                ],
                "date_start_str": "2013-04-19 17:00:00",
                "date_end_str": "2013-04-21 18:00:00",
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
                        "id": "1116",
                        "type": "Merchant",
                        "status": "1",
                        "is_customer": "1",
                        "name": "Ala Moana Management Office",
                        "mall_id": "5",
                        "floor": "1",
                        "address": "1450 Ala Moana Boulevard",
                        "city": "Honolulu",
                        "st": "HI",
                        "zip": "96814",
                        "contact": "",
                        "email": "",
                        "phone": "808-946-2811",
                        "web": "http://www.alamoanacenter.com/Stores/",
                        "newsletter": "",
                        "tags": "",
                        "plan": "0",
                        "max_users": "0",
                        "content": "",
                        "logo": "514d3431b9c9c.jpg",
                        "landing_screen_img": "514d3431e880b.jpeg",
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
                            "updated_at": 1363069588,
                            "id": "1094",
                            "location_id": "1116",
                            "micello_id": "62724",
                            "type": "entity",
                            "map_expiracy": null,
                            "map_version": null,
                            "geometry_id": "89477",
                            "created_at": "1363069588"
                        },
                        "logo_url": "http://shopsuey/assets/img/logos/small_514d3431b9c9c.jpg",
                        "landing_url": "http://shopsuey/assets/img/landing/large_514d3431e880b.jpeg"
                    }
                ]
            },
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
            },
            {
                "gallery": [
                    "5174caf08db90.jpg"
                ],
                "updated_at": 1366608624,
                "id": "36",
                "created_by_id": "212",
                "edited_by_id": "212",
                "title": "Widest selection of slippers and shoes ",
                "description": "We love slippers. We love them so much we dream about them. We dream about a place where the best brands in slippers come together under one roof. We dream about having the widest selection in those brand.",
                "featured_image": null,
                "coupon_image": null,
                "coordinator_phone": "",
                "coordinator_email": "",
                "website": "http://islandsole.com/",
                "show_dates": 0,
                "date_start": 1364782980,
                "date_end": 1367547780,
                "status": "1",
                "code": "",
                "tags": "shoes",
                "fb_event_id": "",
                "foursquare_venue_id": "",
                "foursquare_event_id": "",
                "force_top_message": "0",
                "created_at": "1366608184",
                "gallery_urls": [
                    {
                        "original": "http://shopsuey/assets/img/events/5174caf08db90.jpg",
                        "small": "http://shopsuey/assets/img/events/small_5174caf08db90.jpg",
                        "large": "http://shopsuey/assets/img/events/large_5174caf08db90.jpg"
                    }
                ],
                "date_start_str": "2013-04-01 02:23:00",
                "date_end_str": "2013-05-03 02:23:00",
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
                                "close": "09:00PM"
                            },
                            "tue": {
                                "open": "10:00AM",
                                "close": "09:00PM"
                            },
                            "wed": {
                                "open": "10:00AM",
                                "close": "09:00PM"
                            },
                            "thr": {
                                "open": "10:00AM",
                                "close": "09:00PM"
                            },
                            "fri": {
                                "open": "10:00AM",
                                "close": "09:00PM"
                            },
                            "sat": {
                                "open": "10:00AM",
                                "close": "09:00PM"
                            },
                            "sun": {
                                "open": "10:00AM",
                                "close": "06:00PM"
                            }
                        },
                        "id": "1397",
                        "type": "Merchant",
                        "status": "1",
                        "is_customer": "1",
                        "name": "Island Sole",
                        "mall_id": "15",
                        "floor": "1",
                        "address": "4211 Waialae Avenue",
                        "city": "Honolulu",
                        "st": "HI",
                        "zip": "96816",
                        "contact": "",
                        "email": "",
                        "phone": "808-738-8430",
                        "web": "http://www.islandsole.com",
                        "newsletter": "",
                        "tags": "",
                        "plan": "0",
                        "max_users": "0",
                        "content": "Specialty Retail",
                        "logo": "516638a1e521e.jpeg",
                        "landing_screen_img": "516638a20b4b2.jpg",
                        "latitude": "21.2769280000",
                        "longitude": "-157.7861170000",
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
                            "updated_at": 1363070228,
                            "id": "1375",
                            "location_id": "1397",
                            "micello_id": "497572",
                            "type": "entity",
                            "map_expiracy": null,
                            "map_version": null,
                            "geometry_id": "142887",
                            "created_at": "1363070228"
                        },
                        "logo_url": "http://shopsuey/assets/img/logos/small_516638a1e521e.jpeg",
                        "landing_url": "http://shopsuey/assets/img/landing/large_516638a20b4b2.jpg"
                    },
                    {
                        "id": "2239",
                        "type": "Merchant",
                        "status": "1",
                        "is_customer": "0",
                        "name": "Island Sole",
                        "mall_id": "2232",
                        "floor": "1",
                        "address": "226 Lewers Ave",
                        "city": "Honolulu",
                        "st": "HI",
                        "zip": "96815",
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
                        "latitude": "21.2797443000",
                        "longitude": "-157.8306969000",
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
                            "updated_at": 1363783487,
                            "id": "2213",
                            "location_id": "2239",
                            "micello_id": "868054",
                            "type": "entity",
                            "map_expiracy": null,
                            "map_version": null,
                            "geometry_id": "3939868",
                            "created_at": "1363783487"
                        },
                        "logo_url": "",
                        "landing_url": ""
                    }
                ]
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