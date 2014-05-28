<div id="doc-content" class="mt20">
	<h1><a id="merchant"></a>Location Fav/Unfav</h1>

	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
		    <tr>
				<td width="100">Endpoint</td>
				<td><?=Config::get('base_url')?>api/location/:id/favorite</td>
			</tr>
			<tr>
				<td>Methods</td>
				<td><a href="#merchant-block">POST</a></td>
			</tr>
		</tbody>
	</table>

	<h3><a id="merchant-block"></a>POST</h3>
	<p>Changes favorite state on a single location</p>
	<br>
	<h4>Parameters</h4>
	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
			  <td width="100">id</td>
			  <td>Id of the location to make favorite/unfavorite</td>
			</tr>
			<tr>
			  <td width="100">status</td>
			  <td>1 = favorite, 0 = unfavorite</td>
			</tr>
			<tr>
			  <td width="100">format</td>
			  <td>json | xml</td>
			</tr>
		</tbody>
	</table>

	<h4>Example</h4>
	<pre class="code"><?=Config::get('base_url')?>api/location/2/favorite</pre>

	<h4>Output</h4>
	<pre class="code">{
  "data" : {
    "status" : true
  },
  "meta":{
    "error": null,
    "status":1
  }
}</pre>
</div>