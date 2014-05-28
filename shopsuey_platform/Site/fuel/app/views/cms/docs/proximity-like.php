<div id="doc-content" class="mt20">
	<h1><a id="event"></a>Proximity Messaging - Like/Dislike</h1>

	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td width="100">Endpoint</td>
				<td><?=Config::get('base_url')?>api/proximity/like</td>
			</tr>
			<tr>
				<td>Methods</td>
				<td><a href="#proximity-like-post">POST</a></td>
			</tr>
		</tbody>
	</table>

	<h3><a id="proximity-like-post"></a>POST</h3>
	<p>Marks a message (event or offer) as liked/disliked in order to customize the future messages sent to the user.</p>
	<br>
	<h4>Parameters</h4>
	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
			  <td>status</td>
			  <td>-1 for "dislike", 0 for "no opinion", 1 for "like"</td>
			</tr>
            <tr>
			  <td>entity_id</td>
			  <td>The unique identifier of the offer or event to like/dislike</td>
			</tr>
            <tr>
			  <td>type</td>
			  <td>Should be "offer" or "event"</td>
			</tr>
		</tbody>
	</table>

	<h4>Example</h4>
	<pre class="code"><?=Config::get('base_url')?>api/proximity/like</pre>

	<h4>Output</h4>
	<pre class="code">{
    "data": [],
    "meta": {
        "error": null,
        "status": 1
    }
}</pre>
</div>