<div id="doc-content" class="mt20">
	<h1><a id="offers"></a>Merchants at a Mall count</h1>
	<p>Return a count of merchants inside a mall</p>

	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td width="100">Endpoint</td>
				<td><?=Config::get('base_url')?>api/mall/:id/merchants_count</td>
			</tr>
			<tr>
				<td>Methods</td>
				<td><a href="#offers-get">GET</a></td>
			</tr>
		</tbody>
	</table>

	<h3><a id="offers-get"></a>GET</h3>
	<br>
	<h4>Parameters</h4>
	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td>id</td>
				<td>The id of the mall (required)</td>
			</tr>
			<tr>
			  <td with="100">format</td>
			  <td>json | xml</td>
			</tr>
		</tbody>
	</table>

	<h4>Example</h4>
	<pre class="code"><?=Config::get('base_url')?>api/mall/234/merchants_count</pre>

	<h4>Output</h4>
<pre class="code">{
  "data":{
    "count":4
  },
  "meta":{
    "error":null,
    "status":1
  }
}</pre>
</div>
