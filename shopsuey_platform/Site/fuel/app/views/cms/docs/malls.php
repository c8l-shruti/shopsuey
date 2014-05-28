<div id="doc-content" class="mt20">
	<h1><a id="malls"></a>Malls</h1>
	<p>Outputs a list of malls <small>(20 per request)</small></p>

	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td width="100">Endpoint</td>
				<td><?=Config::get('base_url')?>api/malls/</td>
			</tr>
			<tr>
				<td>Methods</td>
				<td><a href="#malls-get">GET</a></td>
			</tr>
		</tbody>
	</table>

	<h3><a id="malls-get"></a>GET</h3>
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
	<pre class="code"><?=Config::get('base_url')?>api/malls</pre>

	<h4>Output</h4>
<pre class="code">{
  "data":{
    "malls":[
      {
        "address":"1450 Ala Moana Blvd",
        "categories":[
          "mall"
        ],
        "city":"Honolulu",
        "contact":{
          "email":"jack@gmail.com",
          "name":"Jack Daniels",
          "phone":"123-456-7890"
        },
        "description":"Something about the mall",
        "gps":null,
        "hours":{
          "fri":{
            "open":"",
            "close":""
          },
          "mon":{
            "open":"02:42PM",
            "close":"04:42PM"
          },
          "sat":{
            "open":"",
            "close":""
          },
          "sun":{
            "open":"",
            "close":""
          },
          "thr":{
            "open":"",
            "close":""
          },
          "tue":{
            "open":"11:42AM",
            "close":"04:42PM"
          },
          "wed":{
            "open":"09:42AM",
            "close":"07:42PM"
          }
        },
        "id":"1",
        "map":"1",
        "name":"Ala Moana",
        "newsletter":"news@email.com",
        "social":{
          "facebook":"alamoanacenter",
          "foursquare":"",
          "pintrest":"",
          "twitter":"alamoanacenter"
        },
        "st":"HI",
        "status":"1",
        "tags":null,
        "type":"outdoor",
        "web":"http:\/\/www.alamoanacenter.com",
        "wifi":"password1",
        "zip":"96814"
      },
      {
        "address":"222 Kaiulani Ave #304",
        "categories":null,
        "city":"Honolulu",
        "contact":null,
        "description":"blah again",
        "gps":null,
        "hours":null,
        "id":"20",
        "map":"",
        "name":"Easton Mall",
        "newsletter":"",
        "social":null,
        "st":"OH",
        "status":"1",
        "tags":null,
        "type":"",
        "web":"",
        "wifi":"",
        "zip":"96815"
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