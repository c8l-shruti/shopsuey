<div id="doc-content" class="mt20">
	<h1><a id="locations"></a>Locations</h1>
	<p>Outputs a list of locations <small>(20 per request)</small></p>

	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td width="100">Endpoint</td>
				<td><?=Config::get('base_url')?>api/locations/</td>
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
				<td>page</td>
				<td>INTEGER value referencing results page to display.</td>
			</tr>
			<tr>
			  <td width="100">format</td>
			  <td>json | xml</td>
			</tr>
		</tbody>
	</table>

	<h4>Example</h4>
	<pre class="code"><?=Config::get('base_url')?>api/locations</pre>

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
        "logo":"",
        "mall_id":"0",
        "max_users":"0",
        "name":"Goma Location",
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
        "zip":"10458"
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
        "logo":"",
        "mall_id":"0",
        "max_users":"0",
        "name":"New Location",
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
        "zip":"10458"
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
      "records":2
    },
    "status":1
  }
}
	</pre>
</div>