<div id="doc-content" class="mt20">
	<h1><a id="event"></a>Request Twitter</h1>

	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td width="100">Endpoint</td>
				<td><?=Config::get('base_url')?>api/socialnetworkrequest/twitter</td>
			</tr>
			<tr>
				<td>Methods</td>
				<td><a href="#sn-req-post">POST</a></td>
			</tr>
		</tbody>
	</table>

	<h3><a id="sn-req-post"></a>POST</h3>
	<br>
	<h4>Parameters</h4>
	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
            <tr>
			  <td>location_id</td>
			  <td>The unique identifier of the merchant or mall</td>
			</tr>
		</tbody>
	</table>

	<h4>Example</h4>
	<pre class="code"><?=Config::get('base_url')?>api/socialnetworkrequest/twitter</pre>

	<h4>Output</h4>
	<pre class="code">{
    "data": [],
    "meta": {
        "error": null,
        "status": 1
    }
}</pre>
</div>