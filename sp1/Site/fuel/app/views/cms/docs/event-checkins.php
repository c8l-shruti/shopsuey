<div id="doc-content" class="mt20">
	<h1><a id="checkins"></a>Event - Checkins</h1>
	<p>Outputs a list of event checkins for a user</p>

	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td width="100">Methods</td>
				<td><a href="#checkins-get">GET</a></td>
			</tr>
		</tbody>
	</table>

	<h3><a id="checkins-get"></a>GET</h3>
	<pre class="code"><?=Uri::create('api/event/checkins')?></pre>
	<br>
	<h4>Parameters</h4>
	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td>access_key</td>
				<td>The access key <small>see: <a href="<?=Uri::create('developer/docs/auth')?>">Auth</a></small></td>
			</tr>
			<tr>
			  <td width="100">format</td>
			  <td>json | xml</td>
			</tr>
		</tbody>
	</table>

	<h4>Example</h4>
	<pre class="code"><?=Config::get('base_url')?>api/event/checkins</pre>

	<h4>Output</h4>
	<pre class="code">
{
  "data":{
    "checkins":[
      {
        "code":"9876543g",
        "content":"yakkity smackity blah blah ",
        "created":"2012-10-02 06:22:07",
        "date_end":"2012-10-01 12:21:00",
        "date_start":"2012-10-01 08:21:00",
        "edited":"2012-10-04 08:52:16",
        "gallery":"",
        "id":"2",
        "locations":[
          {
            "location_id":"1",
            "location_name":"Goma Location",
            "mall_id":"1",
            "mall_name":"Ala Moana"
          }
        ],
        "malls":null,
        "name":"New event test",
        "retailer_id":"0",
        "status":"1",
        "tags":"tag,it,up"
      }
    ]
  },
  "meta":{
    "error":"",
    "status":1
  }
}
	</pre>
</div>