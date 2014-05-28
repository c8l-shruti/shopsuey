<div id="doc-content" class="mt20">
    <h1><a id="user-reset"></a>User - Get Offers</h1>
    <p>Retrieve the offers (saved or redeemed) for a specific user</p>
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
    <pre class="code"><?= Uri::create('api/user/id/offers') ?></pre>
    <br>
    
    <h4>Parameters</h4>
    <table class="grid" cellspacing="2" cellpadding="5" border="1">
        <tbody>
            <tr>
                <td>id</td>
                <td>The user identifier</td>
            </tr>
            <tr>
                <td>saved_only</td>
                <td>Return only saved offers</td>
            </tr>
            <tr>
                <td>redeemed_only</td>
                <td>Return only offers already redeemed by the user</td>
            </tr>
        </tbody>
    </table>

    <h4>Example</h4>
    <pre class="code"><?= Uri::create('api/user/180/offers') ?></pre>
    <br>
    
    <h4>Output</h4>
    <pre class="code">{
    "data": {
        "offers": [
            {
                "gallery": [
                    "51bb748ae202a.jpg"
                ],
                "type": "1",
                "updated_at": 1371239563,
                "id": "34",
                "status": "1",
                "name": "$500 in Free Storage Accessories!",
                "description": "The magic is in the details!\r\n\r\nDrawers and hanging spaces are only part of your solution. What about your shoes, scarves, and jewelry? Specialized storage accessories add the finishing touches that make your new system perfect.\r\n\r\nReceive Up to $500 in Free Storage Accessories!\r\n\r\nOrganizing your dresses, pants and shirts isn't enough. California Closets' extensive accessory line can take care of the little things that often get left behind.\r\n\r\n- Racks for ties and scarves so they stay neat\r\n- Drawer dividers to keep socks and underwear contained\r\n- Belt racks to keep belts visible and accessible\r\n- Shoe racks that store your footwear off the floor\r\n- Hanging bins and baskets for seasonal items or exercise gear\r\n- Jewelry dividers to keep your earrings, bracelets, and rings safe\r\n- Imagine the possibilities! Your entire home can benefit from well chosen accessories. \r\n- There is no better way to personalize a new storage solution.\r\n\r\nSchedule a free design consultation today\r\n \r\n\r\nAvailable at participating locations. Not to exceed 10% of purchase price. This offer cannot be combined with other offers. Valid through May 31, 2013. Visit our showroom for more details. All franchises independently owned and operated.",
                "price_regular": "0.00",
                "price_offer": "",
                "savings": "",
                "show_dates": 0,
                "date_start": 1366452000,
                "date_end": 1370026800,
                "categories": "",
                "tags": "closet,california,accessories",
                "redeemable": "1",
                "allowed_redeems": "0",
                "multiple_codes": "0",
                "default_code_type": "",
                "force_top_message": "0",
                "created_at": "1366538483",
                "created_by": "140",
                "edited_by": "188",
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
                        "id": "1389",
                        "type": "Merchant",
                        "status": "1",
                        "is_customer": "1",
                        "name": "California Closets",
                        "mall_id": "15",
                        "floor": "1",
                        "address": "4211 Waialae Avenue",
                        "city": "Honolulu",
                        "st": "HI",
                        "zip": "96816",
                        "contact": "",
                        "email": "",
                        "phone": "808-739-7300",
                        "web": "http://www.californiaclosets.com/",
                        "newsletter": "",
                        "tags": "",
                        "plan": "0",
                        "max_users": "0",
                        "content": "Specialty Retail",
                        "logo": "51661b0878a24.jpg",
                        "landing_screen_img": "51661b08a8414.jpg",
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
                            "id": "1367",
                            "location_id": "1389",
                            "micello_id": "256602",
                            "type": "entity",
                            "map_expiracy": null,
                            "map_version": null,
                            "geometry_id": "142879",
                            "created_at": "1363070228"
                        },
                        "logo_url": "http://shopsuey/assets/img/logos/small_51661b0878a24.jpg",
                        "landing_url": "http://shopsuey/assets/img/landing/large_51661b08a8414.jpg"
                    }
                ],
                "gallery_urls": [
                    {
                        "original": "http://shopsuey/assets/img/offers/51bb748ae202a.jpg",
                        "small": "http://shopsuey/assets/img/offers/small_51bb748ae202a.jpg",
                        "large": "http://shopsuey/assets/img/offers/large_51bb748ae202a.jpg"
                    }
                ],
                "date_start_str": "2013-04-20 10:00:00",
                "date_end_str": "2013-05-31 19:00:00"
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