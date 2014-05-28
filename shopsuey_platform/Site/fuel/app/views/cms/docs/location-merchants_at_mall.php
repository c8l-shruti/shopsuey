<div id="doc-content" class="mt20">
	<h1><a id="locations"></a>[DEPRECATED] Get Merchants at Mall</h1>
    <p>This method has been deprecated. Use <a href="<?=Uri::create('developer/docs/location-merchants')?>">/api/location/:id/merchants instead</a>. Return a list of merchants at a mall</p>

	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td width="100">Endpoint</td>
				<td><?=Config::get('base_url')?>api/location/:id/merchants_at_mall</td>
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
				<td>id</td>
				<td>Id of the location</td>
			</tr>
			<tr>
				<td>keyword (optional)</td>
				<td>String to search on the merchant's data</td>
			</tr>
			<tr>
			  <td width="150">format</td>
			  <td>json | xml</td>
			</tr>
		</tbody>
	</table>

	<h4>Example</h4>
	<pre class="code"><?=Config::get('base_url')?>api/location/:id/merchants_at_mall</pre>

	<h4>Output</h4>
	<pre class="code">{
  "data":{
    "location":{
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
        "logo":"",
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
        "merchants" : [ {
            "social" : {
              "facebook" : "facebook",
              "foursquare" : "",
              "pintrest" : "",
              "twitter" : "twitter"
            },
            "hours" : {
              "mon" : {
                "open" : "02:24PM",
                "close" : "11:24PM"
              },
              "tue" : {
                "open" : "06:25PM",
                "close" : "09:25PM"
              },
              "wed" : {
                "open" : "05:25PM",
                "close" : "09:25PM"
              },
              "thr" : {
                "open" : "05:25PM",
                "close" : "08:25PM"
              },
              "fri" : {
                "open" : "",
                "close" : ""
              },
              "sat" : {
                "open" : "",
                "close" : ""
              },
              "sun" : {
                "open" : "",
                "close" : ""
              }
            },
            "id" : "9",
            "type" : "Merchant",
            "status" : "1",
            "is_customer" : "0",
            "name" : "Merchant 1",
            "mall_id" : "2",
            "floor" : null,
            "address" : "Calle 13",
            "city" : "Miami",
            "st" : "FL",
            "zip" : "554",
            "contact" : "Lucas Acosta",
            "email" : "lucas.acosta@local",
            "phone" : "445566",
            "web" : "http://coolsite.com",
            "newsletter" : "newsletter@local",
            "categories" : "",
            "tags" : "cool",
            "plan" : "2",
            "max_users" : "10",
            "content" : "Super description",
            "logo" : "",
            "latitude" : "35.4000015259",
            "longitude" : "-122.4000015259",
            "description" : "",
            "auto_generated" : "0",
            "use_instagram" : "1",
            "user_instagram_id": "2"
            "micello_info" : {
                "map" : null,
                "id" : "43",
                "location_id" : "9",
                "micello_id" : "291041",
                "type" : "entity",
                "map_expiracy" : null,
                "geometry_id" : "498117",
                "created_at" : "1361398822",
                "updated_at" : "1361398822"
            }
        } ]
    },
  },
  "meta":{
    "error":null,
    "status":1
  }
}
	</pre>
</div>