<div id="doc-content" class="mt20">
	<h1><a id="position"></a>Position</h1>

	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td width="100">Methods</td>
				<td><a href="#position-get">GET</a> | <a href="#position-post">POST</a></td>
			</tr>
		</tbody>
	</table>

	<h3><a id="position-get"></a>GET</h3>
	<br>

	<h4>Endpoint</h4>
	<pre class="code"><?=Uri::create('api/position')?>/user_id</pre>
	<br>

	<h4>Parameters</h4>
	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td>access_key</td>
				<td>user access key <small>see: <a href="<?=Uri::create('developer/docs/auth')?>">Auth</a></small></td>
			</tr>
			<tr>
			  <td width="100">format</td>
			  <td>json | xml</td>
			</tr>
		</tbody>
	</table>

	<h4>Example</h4>
	<pre class="code"><?=Uri::create('api/position')?>/2</pre>

	<h4>Output</h4>
	<pre class="code">{
  "data":{
    "positions":[
      {
        "created":"2012-10-12 06:55:03",
        "id":"3",
        "lat":"12348906",
        "lng":"45678901",
        "user_id":"2"
      },
      {
        "created":"2012-10-12 06:52:51",
        "id":"2",
        "lat":"12348906",
        "lng":"45678901",
        "user_id":"2"
      },
      {
        "created":"2012-10-12 06:44:53",
        "id":"1",
        "lat":"1234",
        "lng":"4567",
        "user_id":"2"
      }
    ]
  },
  "meta":{
    "error":null,
    "status":1
  }
}</pre>

	<h3><a id="position-post"></a>POST</h3>
	<br>

	<h4>Endpoint</h4>
	<pre class="code"><?=Uri::create('api/position')?>/user_id</pre>
	<br>

	<h4>Parameters</h4>
	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td>access_key</td>
				<td>user access key <small>see: <a href="<?=Uri::create('developer/docs/auth')?>">Auth</a></small></td>
			</tr>
			<tr>
				<td>lat</td>
				<td>Latitude</td>
			</tr>
			<tr>
				<td>lng</td>
				<td>Longitude</td>
			</tr>
			<tr>
			  <td width="100">format</td>
			  <td>json | xml</td>
			</tr>
		</tbody>
	</table>

	<h4>Example</h4>
	<pre class="code"><?=Uri::create('api/position')?>/2</pre>

	<h4>Output</h4>
	<pre class="code">{
  "data":{
    "positions":[
      {
        "created":"2012-10-12 06:55:03",
        "id":"3",
        "lat":"12348906",
        "lng":"45678901",
        "user_id":"2"
      },
      {
        "created":"2012-10-12 06:52:51",
        "id":"2",
        "lat":"12348906",
        "lng":"45678901",
        "user_id":"2"
      },
      {
        "created":"2012-10-12 06:44:53",
        "id":"1",
        "lat":"1234",
        "lng":"4567",
        "user_id":"2"
      }
    ]
  },
  "meta":{
    "error":null,
    "status":1
  }
}</pre>
</div>