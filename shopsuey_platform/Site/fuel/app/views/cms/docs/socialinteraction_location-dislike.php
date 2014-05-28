<div id="doc-content" class="mt20">
	<h1><a id="event"></a>Social Interactions - Location Dislike</h1>

	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td width="100">Endpoint</td>
				<td><?= Uri::create('api/socialinteraction/location/:id/dislike') ?></td>
			</tr>
			<tr>
				<td>Methods</td>
				<td><a href="#">DELETE</a></td>
			</tr>
		</tbody>
	</table>

	<h3><a id="event-get"></a>DELETE</h3>
	<p>Delete an existent location like for the given location and the current user</p>
	<br>
	<h4>Parameters</h4>
	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>       
				<td width="200">id</td>
				<td>The location identifier</td>
			</tr>
		</tbody>
	</table>

	<h4>Example</h4>
	<pre class="code"><?= Uri::create('api/socialinteraction/location/16/dislike') ?></pre>

	<h4>Output</h4>
	<pre class="code">{
    "data": {
        "success": true
    },
    "meta": {
        "error": "",
        "status": 1
    }
}</pre>

</div>