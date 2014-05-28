<div id="doc-content" class="mt20">
	<h1><a id="locations"></a>Location Maps Validity</h1>
	<p>Returns information about maps expiracy</p>

	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td width="100">Endpoint</td>
				<td><?=Config::get('base_url')?>api/location/maps_validity</td>
			</tr>
			<tr>
				<td>Methods</td>
				<td><a href="#locations-get">POST</a></td>
			</tr>
		</tbody>
	</table>

	<h3><a id="locations-get"></a>POST</h3>
	<br>
	<h4>Parameters</h4>
	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td>communities</td>
				<td>JSON encoded string representing the communities and their versions. Example: [{"cid":665,"v":66},{"cid":334,"v":25}]</td>
			</tr>
			<tr>
			  <td width="100">format</td>
			  <td>json | xml</td>
			</tr>
		</tbody>
	</table>

	<h4>Example</h4>
	<pre class="code"><?=Config::get('base_url')?>api/location/maps_validity</pre>

	<h4>Output</h4>
	<pre class="code">{
  "data":{
    "outdated_communities": [665]
  },
  "meta":{
    "error":null,
    "status":1
  }
}
	</pre>
</div>