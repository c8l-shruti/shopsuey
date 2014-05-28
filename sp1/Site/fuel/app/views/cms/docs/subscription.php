<div id="doc-content" class="mt20">
	<h1><a id="event"></a>Newsletter Subscription</h1>

	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td width="100">Endpoint</td>
				<td><?=Config::get('base_url')?>api/subscription</td>
			</tr>
			<tr>
				<td>Methods</td>
				<td><a href="#subscription-post">POST</a></td>
			</tr>
		</tbody>
	</table>

	<h3><a id="subscription-post"></a>POST</h3>
	<br>
	<h4>Parameters</h4>
	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
            <tr>
			  <td>location_id</td>
			  <td>The unique identifier of the merchant or mall to subscribe to</td>
			</tr>
		</tbody>
	</table>

	<h4>Example</h4>
	<pre class="code"><?=Config::get('base_url')?>api/subscription</pre>

	<h4>Output</h4>
	<pre class="code">{
    "data": [],
    "meta": {
        "error": null,
        "status": 1
    }
}</pre>
</div>