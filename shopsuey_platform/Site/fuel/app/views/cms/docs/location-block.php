<div id="doc-content" class="mt20">
	<h1><a id="merchant"></a>Location Block</h1>

	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td>Methods</td>
				<td><a href="#merchant-block">POST</a></td>
			</tr>
		</tbody>
	</table>

	<h3><a id="merchant-block"></a>POST</h3>
	<p>Block a single location</p>
	<br>
	<h4>Parameters</h4>
	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
			  <td width="100">id</td>
			  <td>Id of the location to block</td>
			</tr>
			<tr>
			  <td width="100">time_lapse</td>
			  <td>
			  	Determines the amount of time that the location must remain blocked, unless explicity unblocked
			  	<ul>
			  		<li><strong>permanently:</strong> Blocked permanently</li>
			  		<li><strong>today:</strong> Blocked just for today</li>
			  		<li><strong>this_week:</strong> Blocked until the end of the current week</li>
			  		</ul>
			  </td>
			</tr>
			<tr>
			  <td width="100">format</td>
			  <td>json | xml</td>
			</tr>
		</tbody>
	</table>

	<h4>Example</h4>
	<pre class="code"><?=Config::get('base_url')?>api/location/2/block</pre>

	<h4>Output</h4>
	<pre class="code">{
  "data" : {
    "blocking" : {
      "type" : "permanently",
      "user_id" : "166",
      "location_id" : "2",
      "start_date" : "2013-01-14 14:45:00",
      "end_date" : "2063-01-14 14:45:00",
      "id" : 1
    }
  },
  "meta":{
    "error":null,
    "status":1
  }
}</pre>

	<h3><a id="merchant-unblock"></a>DELETE</h3>
	<p>Unblock a single location</p>
	<br>
	<h4>Parameters</h4>
	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
			  <td width="100">id</td>
			  <td>Id of the location to unblock</td>
			</tr>
			<tr>
			  <td width="100">format</td>
			  <td>json | xml</td>
			</tr>
		</tbody>
	</table>

	<h4>Example</h4>
	<pre class="code"><?=Config::get('base_url')?>api/location/2/unblock</pre>

	<h4>Output</h4>
	<pre class="code">{
  "data" : [],
  "meta":{
    "error":null,
    "status":1
  }
}</pre>

	<h3><a id="merchant-get"></a>GET</h3>
	<p>Get blocked locations for user</p>
	<br>
	<h4>Parameters</h4>
	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
			  <td width="100">format</td>
			  <td>json | xml</td>
			</tr>
		</tbody>
	</table>

	<h4>Example</h4>
	<pre class="code"><?=Config::get('base_url')?>api/location/blocked</pre>

	<h4>Output</h4>
	<pre class="code">{
  "data" : {
    "blockings" : {
      "2" : {
        "id" : "2",
        "type" : "permanently",
        "location_id" : "2",
        "user_id" : "166",
        "start_date" : "2013-01-14 14:54:45",
        "end_date" : "2063-01-14 14:54:45"
      }
    }
  },
  "meta":{
    "error":null,
    "status":1
  }
}</pre>

</div>