<div id="doc-content" class="mt20">
	<h1><a id="locations"></a>Position Supported regarding Locations</h1>
	<p>Checks if the user's current position is supported by ShopSuey, that is, if there are locations nearby the current user's coordinates</p>

	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td width="100">Endpoint</td>
				<td><?=Config::get('base_url')?>api/location/position_supported</td>
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
				<td>latitude</td>
				<td>Latitude of the current user's position</td>
			</tr>
			<tr>
				<td>longitude</td>
				<td>Longitude of the current user's position</td>
			</tr>
			<tr>
				<td>radius (optional)</td>
				<td>Radius of the area around current user's position to search for.
				If omitted, it defaults to a 5 miles radius
				</td>
			</tr>
			<tr>
			  <td width="150">format</td>
			  <td>json | xml</td>
			</tr>
		</tbody>
	</table>

	<h4>Example</h4>
	<pre class="code"><?=Config::get('base_url')?>api/location/position_supported?latitude=47.9558&longitude=-122.25447&radius=20</pre>

	<h4>Output</h4>
	<pre class="code">{
  "data":{
    "supported": true
  },
  "meta":{
    "error":null,
    "status":1
  }
}
	</pre>
</div>