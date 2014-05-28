<div id="doc-content" class="mt20">
	<h1><a id="event"></a>Proximity Messaging - Get Likes</h1>

	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td width="100">Endpoint</td>
				<td><?=Config::get('base_url')?>api/proximity/get_like</td>
			</tr>
			<tr>
				<td>Methods</td>
				<td><a href="#proximity-like-post">GET</a></td>
			</tr>
		</tbody>
	</table>

	<h3><a id="proximity-like-post"></a>GET</h3>
	<p>Get the list of likes for offers and events</p>
	<br>
	<h4>Parameters</h4>
	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
            <tr>
				<td>like_status (optional)</td>
				<td>If it's equal to 1, the service returns only liked messages. If it's equal to -1 only returns disliked and if the param is not present or is equal to 0 it returns all messages (liked and disliked).</td>
			</tr>
		</tbody>
	</table>

	<h4>Example</h4>
	<pre class="code"><?=Config::get('base_url')?>api/proximity/get_likes</pre>

	<h4>Output</h4>
	<pre class="code">{
    "data": {
        "offers": {
            "1": {
                "status": 1
                "offer": {
                    "gallery": null,
                    "id": "1",
                    "status": "1",
                    "name": "My awesome offer PUT test",
                    "description": "Something about this offer",
                    "price_regular": "100.00",
                    "price_offer": "50.00",
                    "savings": "50%",
                    "show_dates": "1",
                    "date_start": 1368363600,
                    "date_end": 1368388800
                    "date_start_str": "2013-05-12 13:00:00",
                    "date_end_str": "2013-05-12 20:00:00",
                    "categories": "",
                    "tags": "awesome,offer,sale",
                    "redeemable": "0",
                    "allowed_redeems": "1",
                    "multiple_codes": "0",
                    "default_code_type": "",
                    "created_at": "2012",
                    "updated_at": "2012",
                    "created_by": "2",
                    "edited_by": "162",
                    "locations": [],
                    "gallery_urls": []
                }
            }
        },
        "events": []
    },
    "meta": {
        "error": null,
        "status": 1
    }
}</pre>
</div>