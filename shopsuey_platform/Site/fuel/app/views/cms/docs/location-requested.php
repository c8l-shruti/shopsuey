<div id="doc-content" class="mt20">
	<h1><a id="locations"></a>Location Requested</h1>
	<p>Gets ShopSuey request status for a location</p>

	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td width="100">Endpoint</td>
				<td><?=Config::get('base_url')?>api/location/:id/requested</td>
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
			  <td width="150">format</td>
			  <td>json | xml</td>
			</tr>
		</tbody>
	</table>

	<h4>Example</h4>
	<pre class="code"><?=Config::get('base_url')?>api/location/1/requested</pre>

	<h4>Output</h4>
	<pre class="code">{
  "data":{
    "requested": true
  },
  "meta":{
    "error":null,
    "status":1
  }
}
	</pre>
</div>