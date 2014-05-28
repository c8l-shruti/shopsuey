<div id="doc-content" class="mt20">
	<h1><a id="retailer"></a>Retailer</h1>

	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td width="100">Endpoint</td>
				<td><?=Config::get('base_url')?>api/retailer/</td>
			</tr>
			<tr>
				<td>Methods</td>
				<td><a href="#retailer-get">GET</a></td>
			</tr>
		</tbody>
	</table>

	<h3><a id="retailer-get"></a>GET</h3>
	<p>Get a single retailer</p>
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
	<pre class="code"><?=Config::get('base_url')?>api/retailer/2</pre>

	<h4>Output</h4>
	<pre class="code">{
  "data":{
    "retailer":[
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
        "hours":null,
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
      }
    ]
  },
  "meta":{
    "error":null,
    "status":1
  }
}</pre>
</div>