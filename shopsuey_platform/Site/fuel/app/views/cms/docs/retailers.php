<div id="doc-content" class="mt20">
	<h1><a id="retailers"></a>Retailers</h1>
	<p>Outputs a list of retailers <small>(20 per request)</small></p>

	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td width="100">Endpoint</td>
				<td><?=Config::get('base_url')?>api/retailers/</td>
			</tr>
			<tr>
				<td>Methods</td>
				<td><a href="#retailers-get">GET</a></td>
			</tr>
		</tbody>
	</table>

	<h3><a id="retailers-get"></a>GET</h3>
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
	<pre class="code"><?=Config::get('base_url')?>api/retailers</pre>

	<h4>Output</h4>
	<pre class="code">{
  "data":{
    "retailers":[
      {
        "address":"1 ANF Way",
        "categories":[
          "department store"
        ],
        "city":"New Albany",
        "contact":{
          "phone":"866-681-3115"
        },
        "created":"2012-07-13 20:12:46",
        "description":"",
        "edited":"0000-00-00 00:00:00",
        "id":"2",
        "malls":[1,2],
        "name":"Abercrombie & Fitch",
        "social":{
          "facebook":"abercrombie",
          "twitter":"abercrombie"
        },
        "st":"OH",
        "status":"1",
        "tags":[
          "children's clothing",
          "men's clothing",
          "women's clothing"
        ],
        "web":"http:\/\/www.abercrombie.com",
        "zip":"43054"
      },
      {
        "address":"7 W. Seventh Street",
        "categories":[
          "department store"
        ],
        "city":"Cincinnati",
        "contact":{
          "phone":"513-579-7000"
        },
        "created":"2012-07-13 19:59:42",
        "description":"",
        "edited":"2012-07-13 00:00:00",
		"hours":null,
        "id":"1",
        "malls":[1,2],
        "name":"Macy's",
        "social":{
          "facebook":"macys",
          "twitter":"macys"
        },
        "st":"OH",
        "status":"1",
        "tags":[
          "appliances",
          "children's clothing",
          "children's shoes",
          "furniture",
          "houseware",
          "men's clothing",
          "men's shoes",
          "women's clothing",
          "women's shoes"
        ],
        "web":"http:\/\/www.macysinc.com",
        "zip":"45202"
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
}</pre>
</div>