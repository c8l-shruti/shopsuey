<div id="doc-content" class="mt20">
	<h1><a id="offer"></a>Offer</h1>

	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td width="100">Endpoint</td>
				<td><?=Config::get('base_url')?>api/offer/</td>
			</tr>
			<tr>
				<td>Methods</td>
				<td><a href="#offer-get">GET</a> | <a href="#offer-post">POST</a> | <a href="#offer-put">PUT</a></td>
			</tr>
		</tbody>
	</table>

	<h3><a id="offer-get"></a>GET</h3>
	<p>Get a single offer</p>
	<br>
	<h4>Parameters</h4>
	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
			  <td>format</td>
			  <td>json | xml</td>
			</tr>
		</tbody>
	</table>

	<h4>Example</h4>
	<pre class="code"><?=Config::get('base_url')?>api/offer/1</pre>

	<h4>Output</h4>
	<pre class="code">{
  "data":{
    "offer":{
      /* Number of times the offer can be redeemed (0 for no limit) */
      "allowed_redeems" : "1",
      /* A new code should be generated for each redeem? */
      "multiple_codes" : "0",
      /* Default type for auto generated codes */
      "default_code_type" : "",
      /* Is the retailer blocked by the user? */
      "blocked":false,
      "created_at":"1358294674",
      "updated_at":"1358294674",
      "show_dates": "1",
      "date_start": 1368363600,
      "date_end": 1368388800
      "date_start_str": "2013-05-12 13:00:00",
      "date_end_str": "2013-05-12 20:00:00",
      "description":"Something about this offer",
      "edited":"2012-10-12 00:36:04",
      "gallery":["50ef11bb9f321.jpg"],
      "gallery_urls" : [ {
        	"original" : "http://shopsuey/assets/img/offers/50ef11bb9f321.jpg",
        	"small" : "http://shopsuey/assets/img/offers/small_50ef11bb9f321.jpg",
        	"large" : "http://shopsuey/assets/img/offers/large_50ef11bb9f321.jpg"
      	} ],
      "id":"1",
      "locations":[ /* Array of locations, either malls or merchants */ ],
      "name":"My awesome offer",
      "price_offer":"50.00",
      "price_regular":"100.00",
      "times_redeemed": 2,
      "like_status" : 1,
      "last_redeem" : {
				"id" : "4",
				"date" : 1360791714,
				"offer_code" : {
					"type" : "ean13",
					"code" : "1457832408713",
					"id" : "48",
					"auto_generated" : "1",
					"status" : "1"
				}
      },
      "savings":"50%",
      "tags":"awesome,offer,sale"
    }
  },
  "meta":{
    "error":null,
    "status":1
  }
}</pre>


	<br>
	<h3><a id="offer-put"></a>PUT</h3>
	<p>Updates an offer</p>
	<br>
	<h4>Parameters</h4>
	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td width="100">retailer_id</td>
				<td>INTEGER value referencing a valid retailer id</td>
			</tr>
			<tr>
				<td>malls</td>
				<td>One dimensional array containing mall ids</td>
			</tr>
			<tr>
				<td>locations</td>
				<td>One dimensional array containing location ids</td>
			</tr>
			<tr>
				<td>name</td>
				<td>The offer name</td>
			</tr>
			<tr>
				<td>description</td>
				<td>Info about the offer</td>
			</tr>
			<tr>
				<td>price_regular</td>
				<td>The regular price of the item(s) being offered</td>
			</tr>
			<tr>
				<td>price_offer</td>
				<td>The adjusted price of the item(s) being offered</td>
			</tr>
			<tr>
				<td>savings</td>
				<td>The difference between the price_regular and price_offer values<br>This can be a % or monitary value</td>
			</tr>
			<tr>
				<td>date_start</td>
				<td>The effective start date<br>Example: <?=date('Y-m-d H:i:s')?></td>
			</tr>
			<tr>
				<td>date_end</td>
				<td>The effective end date<br>Example: <?=date('Y-m-d H:i:s')?></td>
			</tr>
			<tr>
				<td>code</td>
				<td>Internal code used for tracking</td>
			</tr>
			<tr>
				<td>tags</td>
				<td>CSV string of tags used for taxonomy of the offer<br>Example: this,is,a,tag,string</td>
			</tr>
			<tr>
			  <td>format</td>
			  <td>json | xml</td>
			</tr>
		</tbody>
	</table>

	<h4>Example</h4>
	<pre class="code"><?=Uri::create('api/offer')?>/1/</pre>

	<br>

	<h4>Output</h4>
	<pre class="code">{
  "data":{
    "offer":{
      "code":"12345678",
      "created":"2012-10-03 03:40:31",
      "show_dates": "1",
      "date_start": 1368363600,
      "date_end": 1368388800
      "date_start_str": "2013-05-12 13:00:00",
      "date_end_str": "2013-05-12 20:00:00",
      "description":"Something about this offer",
      "edited":"2012-10-12 01:35:49",
      "gallery":"",
      "id":"1",
      "locations":[
        {
          "location_id":"1",
          "location_name":"Location 1 again",
          "mall_id":"1",
          "mall_name":"Ala Moana"
        }
      ],
      "malls":[
        {
          "id":"1",
          "name":"Ala Moana"
        },
        {
          "id":"20",
          "name":"Easton Mall"
        }
      ],
      "name":"My awesome offer PUT test",
      "price_offer":"50.00",
      "price_regular":"100.00",
      "retailer":{
        "id":"2",
        "name":"Hilo Hattie"
      },
      "savings":"50%",
      "tags":"awesome,offer,sale"
    }
  },
  "meta":{
    "error":null,
    "status":1,
    "updated_by":"2"
  }
}</pre>
	<h3><a id="offer-post"></a>POST</h3>
	<p>Creates a new offer</p>
	<br>
	<h4>Parameters</h4>
	<p>See <a href="#offer-put">PUT</a></p>

</div>