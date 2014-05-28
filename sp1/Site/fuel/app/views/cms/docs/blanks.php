<div id="doc-content" class="mt20">
	<h1><a id="BLANK"></a>BLANKS</h1>
	<p>Outputs a list of BLANKS <small>(25 per request)</small></p>
	
	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td width="100">Endpoint</td>
				<td><?=Config::get('base_url')?>api/ENDPOINT/</td>
			</tr>
			<tr>
				<td>Methods</td>
				<td><a href="#BLANKS-get">GET</a></td>
			</tr>
		</tbody>
	</table>

	<h3><a id="BLANKS-get"></a>GET</h3>
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
			  <td>format</td>
			  <td>json | xml</td>
			</tr>
		</tbody>
	</table>

	<h4>Example</h4>
	<pre class="code"><?=Config::get('base_url')?>api/BLANKS</pre>

	<h4>Output</h4>
	<pre class="code">{}</pre>
</div>