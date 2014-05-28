<div id="doc-content" class="mt20">
	<h1><a id="event"></a>Get Special Event Offers</h1>

	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td width="100">Endpoint</td>
				<td><?=Config::get('base_url')?>api/specialevent/:id/offers</td>
			</tr>
			<tr>
				<td>Methods</td>
				<td><a href="#event-offers-get">GET</a></td>
			</tr>
		</tbody>
	</table>

	<h3><a id="event-offers-get"></a>POST</h3>
	<p>Get all active or upcoming offers related to the special event</p>
	<br>
	<h4>Parameters</h4>
	<table class="grid" cellspacing="2" cellpadding="5" border="1">

	</table>

	<h4>Example</h4>
	<pre class="code"><?=Config::get('base_url')?>api/specialevent/1/offers</pre>

	<h4>Output</h4>
	<pre class="code">{
    "data": {
        "offers": [
            {
                "gallery": [
                    "5238c7ff0f4be.png"
                ],
                "type": "0",
                "updated_at": 1379452928,
                "id": "356",
                "status": "1",
                "name": "FALL SALE UP TO 50% OFF",
                "description": "COME IN NOW FOR OUR FALL SALE, with new reductions and savings up to 50% OFF.",
                "price_regular": "0.00",
                "price_offer": "",
                "savings": "",
                "show_dates": 0,
                "date_start": 1379322000,
                "date_end": 1383256800,
                "categories": "",
                "tags": "BANANA,republic,style,fashion,slacks,shoes,sweater,dress,work",
                "redeemable": "0",
                "allowed_redeems": "0",
                "multiple_codes": "0",
                "default_code_type": "",
                "force_top_message": "0",
                "created_at": "1379452928",
                "created_by": "140",
                "edited_by": "140",
                "gallery_urls": [
                    {
                        "original": "http://shopsuey/assets/img/offers/5238c7ff0f4be.png",
                        "small": "http://shopsuey/assets/img/offers/small_5238c7ff0f4be.png",
                        "large": "http://shopsuey/assets/img/offers/large_5238c7ff0f4be.png"
                    }
                ],
                "date_start_str": "2013-09-16 09:00:00",
                "date_end_str": "2013-10-31 22:00:00"
            }
        ]
    },
    "meta": {
        "error": null,
        "status": 1
    }
}</pre>
</div>