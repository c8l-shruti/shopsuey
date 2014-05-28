<div id="doc-content" class="mt20">
	<h1><a id="BLANK"></a>BLANK</h1>

	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td width="100">Endpoint</td>
				<td><?=Config::get('base_url')?>api/ENDPOINT/</td>
			</tr>
			<tr>
				<td>Methods</td>
				<td><a href="#BLANK-get">GET</a></td>
			</tr>
		</tbody>
	</table>

	<h3><a id="BLANK-get"></a>GET</h3>
	<br>
	<h4>Parameters</h4>
	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td>param</td>
				<td>description</td>
			</tr>
			<tr>
				<td>page</td>
				<td>INTEGER value referencing results page to display</td>
			</tr>
			<tr>
			  <td width="100">format</td>
			  <td>json | xml</td>
			</tr>
		</tbody>
	</table>

	<h4>Example</h4>
	<pre class="code"><?=Config::get('base_url')?>api/BLANK</pre>

	<h4>Output</h4>
	<pre class="code">{}</pre>
</div>