<div id="doc-content" class="mt20">
	<h1><a id="event"></a>Social Interactions - Location Checkin</h1>

	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td width="100">Endpoint</td>
				<td><?= Uri::create('api/socialinteraction/location/:id/checkin') ?></td>
			</tr>
			<tr>
				<td>Methods</td>
				<td><a href="#">POST</a></td>
			</tr>
		</tbody>
	</table>

	<h3><a id="event-get"></a>POST</h3>
	<p>Adds a new checkin of the current user to the given location</p>
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
	<pre class="code"><?= Uri::create('api/socialinteraction/location/16/checkin') ?></pre>

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