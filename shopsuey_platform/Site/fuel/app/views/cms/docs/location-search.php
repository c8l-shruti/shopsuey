<div id="doc-content" class="mt20">
	<h1><a id="locations"></a>Locations Search</h1>
	<p>Return a list of malls and standalone merchants to be shown on a map</p>

	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td width="100">Endpoint</td>
				<td><?=Config::get('base_url')?>api/location/search</td>
			</tr>
			<tr>
				<td>Methods</td>
				<td><a href="#locations-get">GET</a></td>
			</tr>
		</tbody>
	</table>

	<h3><a id="locations-get"></a>GET</h3>
	<br>
	<h4>Parameters</h4>
	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td>latitude (optional*)</td>
				<td>Latitude of the current location (used to search by nearby)</td>
			</tr>
			<tr>
				<td>longitude (optional*)</td>
				<td>Longitude of the current location (used to search by nearby)</td>
			</tr>
			<tr>
				<td>radius (optional)</td>
				<td>Radius of the area around current location to search for (used to search by nearby).
				Only applicable when the <strong>latitude</strong> and <strong>longitude</strong> parameters are passed.
				If omitted, only the first 10 nearest locations will be returned in a 100 miles radius
				</td>
			</tr>
			<tr>
				<td>keyword (optional*)</td>
				<td>String to search on the location's data</td>
			</tr>
			<tr>
				<td>malls_only (optional)</td>
				<td>Only return malls</td>
			</tr>
            <tr>
				<td>merchants_only (optional)</td>
				<td>Only return merchants. All of them will be returned if <strong>all_merchants</strong> is sent; only standalone merchants otherwise.</td>
			</tr>
            <tr>
				<td>all_merchants (optional)</td>
				<td>Also return merchants inside malls. The default behavior is to only return malls and standalone merchants, not including merchants within malls.</td>
			</tr>
            <tr>
				<td>include_parent_mall (optional)</td>
				<td>When used with all_merchants also includes some information about the mall that contains each merchant.</td>
			</tr>
			<tr>
				<td>favorites (optional*)</td>
				<td>If set to 1 it will show only user favorite locations</td>
			</tr>
			<tr>
				<td>order_by (optional)</td>
				<td>Orders by four possible options: name, simple_relevance, relevance or distance (String). Note that &quot;relevance&quot; or &quot;simple_relevance&quot; only make sense when the <strong>keyword</strong> param is passed as well</td>
			</tr>
            <tr>
				<td>pagination (optional)</td>
				<td>If <strong>pagination</strong> is true, the results will be split in pages of 25 elements. The default behavior is not to paginate (i.e: send all of the results at once).</td>
			</tr>
			<tr>
			  <td width="150">format</td>
			  <td>json | xml</td>
			</tr>
			<tr>
			  <td colspan="2"><strong>* note:</strong> All parameters are optional, but at least the <strong>latitude/longitude</strong> pair, <strong>keyword</strong> or <strong>favorites</strong> must be passed</td>
			</tr>
		</tbody>
	</table>

	<h4>Example</h4>
	<pre class="code"><?=Config::get('base_url')?>api/location/search?latitude=47.9558&longitude=-122.25447&radius=20</pre>

	<h4>Output</h4>
	<pre class="code">{
  "data":{
    "locations":[
      {
        "address":"123 Sesame Str",
        "categories":null,
        "city":"New York City",
        "contact":null,
        "content":"testing 1 2 3",
        "email":"c.tullos@illumifi.net",
        "gps":"",
        "hours":{
          "fri":{
            "open":"10:32PM",
            "close":"11:32PM"
          },
          "mon":{
            "open":"06:32PM",
            "close":"11:32PM"
          },
          "sat":{
            "open":"10:32PM",
            "close":"12:32AM"
          },
          "sun":{
            "open":"10:32PM",
            "close":"12:32AM"
          },
          "thr":{
            "open":"05:32PM",
            "close":"01:32AM"
          },
          "tue":{
            "open":"06:32PM",
            "close":"11:32PM"
          },
          "wed":{
            "open":"06:32PM",
            "close":"11:32PM"
          }
        },
        "id":"1",
        "type":"Mall",
        "is_customer":"1",
        "logo_url":"http://example.com/logo.jpg",
        "landing_url":"http://example.com/landing.jpg",
        "explore_url":"http://example.com/explore.jpg",
        "mall_id":null,
        "floor": null,
        "max_users":"0",
        "name":"Mall Name",
        "newsletter":"news@email.com",
        "phone":"6147874967",
        "plan":"0",
        "social":{
          "facebook":"facebook",
          "foursquare":"foursquare",
          "pintrest":"pintrest",
          "twitter":"twitter"
        },
        "st":"NY",
        "status":"1",
        "tags":null,
        "web":"http:\/\/www.illumifi.net",
        "zip":"10458",
        "latitude":"47.8137030000",
        "longitude":"-122.3500000000",
        "auto_generated":"0",
        "use_instagram" : "1",
        "user_instagram_id": "2"
        "micello_info":null,
        "is_favorite":true
      },
      {
        "address":"123 Sesame Str",
        "categories":null,
        "city":"New York City",
        "contact":null,
        "content":"ougblv.kalwkejlkmnt",
        "email":"c.tullos@illumifi.net",
        "gps":"",
        "hours":{
          "fri":{
            "open":"03:36PM",
            "close":"11:36PM"
          },
          "mon":{
            "open":"05:35PM",
            "close":"11:35PM"
          },
          "sat":{
            "open":"10:36PM",
            "close":"11:36PM"
          },
          "sun":{
            "open":"10:36PM",
            "close":"11:36PM"
          },
          "thr":{
            "open":"08:36PM",
            "close":"11:36PM"
          },
          "tue":{
            "open":"06:35PM",
            "close":"11:35PM"
          },
          "wed":{
            "open":"06:35PM",
            "close":"11:36PM"
          }
        },
        "id":"2",
        "type":"Merchant",
        "is_customer":"0",
        "logo_url":"",
        "landing_url":"",
        "explore_url":"",
        "mall_id":null,
        "floor": null,
        "max_users":"0",
        "name":"Standalone Merchant",
        "newsletter":"news@email.com",
        "phone":"6147874967",
        "plan":"0",
        "retailer_id":"0",
        "social":{
          "facebook":"facebook",
          "foursquare":"foursquare",
          "pintrest":"pintrest",
          "twitter":"twitter"
        },
        "st":"NY",
        "status":"1",
        "tags":null,
        "web":"http:\/\/www.illumifi.net",
        "zip":"10458",
        "latitude":"47.7127030000",
        "longitude":"-122.1803930000",
        "auto_generated":"0",
        "use_instagram" : "1",
        "user_instagram_id": "2"
        "micello_info":{
            "map" : null,
            "id" : "39",
            "location_id" : "2",
            "micello_id" : "5031",
            "type" : "community",
            "map_expiracy" : "2013-02-28 15:48:18",
            "geometry_id" : null,
            "created_at" : "1361398822",
            "updated_at" : "1361461698"
        },
        "is_favorite":false
      }
    ]
  },
  "meta":{
    "error":null,
    "status":1
  }
}
	</pre>
</div>