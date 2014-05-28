<div id="doc-content" class="mt20">
	<h1><a id="event"></a>Flag Vote</h1>

	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td width="100">Endpoint</td>
				<td><?= Uri::create('api/flag/id/vote') ?></td>
			</tr>
			<tr>
				<td>Methods</td>
				<td><a href="#">POST</a></td>
			</tr>
		</tbody>
	</table>

	<h3><a id="event-get"></a>POST</h3>
	<p>Create a new vote for the given flag, related with the current user</p>
	<br>
	<h4>Parameters</h4>
	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>       
				<td width="200">id</td>
				<td>The flag identifier</td>
			</tr>
			<tr>       
				<td width="200">status</td>
				<td>Vote up (1) or down (-1)</td>
			</tr>
		</tbody>
	</table>

	<h4>Example</h4>
	<pre class="code"><?= Uri::create('api/flag/4/vote') ?></pre>

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