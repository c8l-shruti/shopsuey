<div id="doc-content" class="mt20">
	<h1><a id="offers"></a>Offers</h1>
	<p>Outputs a list of offers <small>(20 per request)</small></p>

	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td width="100">Endpoint</td>
				<td><?=Config::get('base_url')?>api/offers/</td>
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
				<td>retailer_id</td>
				<td>Search offers by retailer_id. Does an exact comparison.</td>
			</tr>
			<tr>
				<td>name</td>
				<td>Search offers by name. Does a 'LIKE' comparison.</td>
			</tr>
			<tr>
				<td>description</td>
				<td>Search offers by description. Does a 'LIKE' comparison.</td>
			</tr>
			<tr>
				<td>tags</td>
				<td>Search offers by tags. Does a 'LIKE' comparison.</td>
			</tr>
			<tr>
				<td>code</td>
				<td>Search offer by code. Does an exact comparison.</td>
			</tr>
			<tr>
				<td>date_start</td>
				<td>Search offers by date_start. Does a greater than or equal to comparison.</td>
			</tr>
			<tr>
				<td>date_end</td>
				<td>Search offers by date_end. Does a less than or equal to comparison.</td>
			</tr>
			<tr>
				<td>order_by</td>
				<td>Order results by the specified field.<br>date_start | date_end | name | description | code</td>
			</tr>
			<tr>
				<td>order</td>
				<td>ASC | DESC</td>
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
	<pre class="code"><?=Config::get('base_url')?>api/offers</pre>

	<h4>Output</h4>
<pre class="code">{
  "data":{
    "offers":[
      {
        "categories":"",
        "code":"12345678",
        "created":"2012-10-03 03:40:31",
        "show_dates": "1",
        "date_start": 1368363600,
        "date_end": 1368388800
        "date_start_str": "2013-05-12 13:00:00",
        "date_end_str": "2013-05-12 20:00:00",
        "description":"",
        "gallery":"",
        "id":"1",
        "locations":"["1","2"]",
        "name":"My awesome offer",
        "price_offer":"",
        "price_regular":"100.00",
        "retailer_id":"2",
        "savings":"50%",
        "status":"1",
        "tags":"awesome,offer,sale"
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