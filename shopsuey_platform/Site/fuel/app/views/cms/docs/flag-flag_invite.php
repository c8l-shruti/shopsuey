<div id="doc-content" class="mt20">
	<h1><a id="event"></a>Flag Invite</h1>

	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td width="100">Endpoint</td>
				<td><?= Uri::create('api/flag/id/invite') ?></td>
			</tr>
			<tr>
				<td>Methods</td>
				<td><a href="#">PUT</a></td>
			</tr>
		</tbody>
	</table>

	<h3><a id="event-get"></a>PUT</h3>
	<p>Invite users to the specified flag. The flag must be private</p>
	<br>
	<h4>Parameters</h4>
	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>       
				<td width="200">id</td>
				<td>The flag identifier</td>
			</tr>
			<tr>       
				<td width="200">invited_users</td>
				<td>a list of user identifiers that we want to invite</td>
			</tr>
		</tbody>
	</table>

	<h4>Example</h4>
	<pre class="code"><?= Uri::create('api/flag/4/invite') ?></pre>

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