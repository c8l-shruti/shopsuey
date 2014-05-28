<div id="doc-content" class="mt20">
	<h1><a id="offer-bookmarks"></a>Offer - Bookmarks</h1>
	<p>Outputs a list of offer bookmarks</p>

	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td width="100">Endpoint</td>
				<td><?=Config::get('base_url')?>api/offer/bookmarks/</td>
			</tr>
			<tr>
				<td>Methods</td>
				<td><a href="#BLANKS-get">GET</a></td>
			</tr>
		</tbody>
	</table>

	<h3><a id="offer-bookmarks-get"></a>GET</h3>
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
	<pre class="code"><?=Config::get('base_url')?>api/offer/bookmarks/</pre>

	<h4>Output</h4>
	<pre class="code">{
  "meta":{
    "error":"",
    "status":1
  },
  "data":{
    "offers":[
      {
        "code":"12345678",
        "created":"2012-10-03 03:40:31",
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
    ]
  }
}</pre>
</div>