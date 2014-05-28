<div id="doc-content" class="mt20">
	<h1><a id="event"></a>Event Count</h1>

	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td width="100">Endpoint</td>
				<td><?=Config::get('base_url')?>api/events/count</td>
			</tr>
			<tr>
				<td>Methods</td>
				<td><a href="#event-count-get">GET</a></td>
			</tr>
		</tbody>
	</table>

	<h3><a id="event-count-get"></a>GET</h3>
	<p>Get the active and upcoming events count (nearby or from a specific location)</p>
	<br>
	<h4>Parameters</h4>
	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
            <tr>
				<td>from_location</td>
				<td>Returns only event count from a specific location (mall or merchant). The value of this parameter should be a merchant or mall ID.</td>
			</tr>
            <tr>
				<td>include_merchants</td>
				<td>When <strong>from_location</strong> is set, and the location id indicates a mall, setting this parameter will make the event count also include the events associated with merchants inside the mall.</td>
			</tr>
            <tr>
				<td>latitude</td>
				<td>Coordinates of the current location. This parameter is required if <strong>from_location</strong> is NOT set.</td>
			</tr>
			<tr>
				<td>longitude</td>
				<td>Coordinates of the current location. This parameter is required if <strong>from_location</strong> is NOT set.</td>
			</tr>
		</tbody>
	</table>

	<h4>Example</h4>
	<pre class="code"><?=Config::get('base_url')?>api/events/count</pre>

	<h4>Output</h4>
	<pre class="code">{
    "data": {
        "count": 4
    },
    "meta": {
        "error": null,
        "status": 1
    }
}</pre>
</div>