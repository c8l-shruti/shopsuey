<div id="doc-content" class="mt20">
	<h1><a id="offers"></a>Offer Search</h1>
	<p>Search for offers <small>(20 per request)</small></p>

	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td width="100">Endpoint</td>
				<td><?=Config::get('base_url')?>api/offers</td>
			</tr>
			<tr>
				<td>Methods</td>
				<td><a href="#offers-get">GET</a></td>
			</tr>
		</tbody>
	</table>

	<h3><a id="offers-get"></a>GET</h3>
	<br>
	<h4>Parameters</h4>
	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td>from_favorites</td>
				<td>Return only offers provided by the user's favorites locations.</td>
			</tr>
			<tr>
				<td>from_nearby</td>
				<td>Return only offers located on locations within a 50 miles radius.</td>
			</tr>
            <tr>
				<td>from_location</td>
				<td>Returns only offers from a specific location (mall or merchant). The value of this parameter should be a merchant or mall ID.</td>
			</tr>
            <tr>
				<td>include_merchants</td>
				<td>When <strong>from_location</strong> is set, and the location id indicates a mall, setting this parameter will make the offer list also include the offers associated with merchants inside the mall.</td>
			</tr>
			<tr>
				<td>latitude</td>
				<td>Coordinates of the current location. Must be passed when the <strong>from_nearby</strong> parameter is set.</td>
			</tr>
			<tr>
				<td>longitude</td>
				<td>Coordinates of the current location. Must be passed when the <strong>from_nearby</strong> parameter is set.</td>
			</tr>
			<tr>
				<td>date_status</td>
				<td>
					Search for offers based on preset date criterias. Valid values are:
					<ul>
						<li><strong>active:</strong> Offers that have started and are not yet finished</li>
						<li><strong>upcoming:</strong> Offers that will start during next week</li>
						<li><strong>expiring_soon:</strong> Offers that will end during the next two days</li>
						<li><strong>all:</strong> A combination of all of the above criterias. This is also the default.</li>
					</ul>
				</td>
			</tr>
			<tr>
				<td>filter</td>
				<td>Search offers by name, description, tags and location name. Does a 'LIKE' comparison.</td>
			</tr>
			<tr>
				<td>redeemed_only</td>
				<td>Return only offers already redeemed by the user.</td>
			</tr>
			<tr>
				<td>order_by</td>
				<td>Order results by the specified field.<br>date_start | date_end | name | description</td>
			</tr>
			<tr>
				<td>order_direction</td>
				<td>asc | desc</td>
			</tr>
			<tr>
				<td>page</td>
				<td>INTEGER value referencing results page to display.</td>
			</tr>
			<tr>
			  <td with="100">format</td>
			  <td>json | xml</td>
			</tr>
		</tbody>
	</table>

	<h4>Example</h4>
	<pre class="code"><?=Config::get('base_url')?>api/offer/search</pre>

	<h4>Output</h4>
<pre class="code">{
  "data":{
    "offers":[
      {
        "categories":"",
        "created_at":"1358294674",
        "updated_at":"1358294674",
        "show_dates": "1",
        "date_start": 1368363600,
        "date_end": 1368388800
        "date_start_str": "2013-05-12 13:00:00",
        "date_end_str": "2013-05-12 20:00:00",
        "description":"",
        "id":"1",
        "name":"My awesome offer",
        "price_offer":"50.00",
        "price_regular":"100.00",
        "savings":"50%",
        "status":"1",
        "gallery" : ["50ef11bb9f321.jpg"],
        "gallery_urls" : [ {
        	"original" : "http://shopsuey/assets/img/offers/50ef11bb9f321.jpg",
        	"small" : "http://shopsuey/assets/img/offers/small_50ef11bb9f321.jpg",
        	"large" : "http://shopsuey/assets/img/offers/large_50ef11bb9f321.jpg"
      	} ],
        /* Number of times the offer can be redeemed (0 for no limit) */
      	"allowed_redeems" : "1",
        /* A new code should be generated for each redeem? */
      	"multiple_codes" : "0",
        /* Default type for auto generated codes */
      	"default_code_type" : "",
        "tags":"awesome,offer,sale",
        "locations":[ /* Array of locations, either malls or merchants */ ],
        "like_status" : 1
      }
    ]
  },
  "meta":{
    "error":null,
    "pagination":{
      "limit":20,
      "offset":{
        "current":0,
        "next":null,
        "prev":null
      },
      "page":{
        "count":1,
        "current":1,
        "next":null,
        "prev":null
      },
      "records":1
    },
    "status":1
  }
}</pre>
</div>