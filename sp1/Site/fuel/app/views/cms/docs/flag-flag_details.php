<div id="doc-content" class="mt20">
	<h1><a id="event"></a>Flag Details</h1>

	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td width="100">Endpoint</td>
				<td><?= Uri::create('api/flag/id/details') ?></td>
			</tr>
			<tr>
				<td>Methods</td>
				<td><a href="#">GET</a></td>
			</tr>
		</tbody>
	</table>

	<h3><a id="event-get"></a>GET</h3>
	<p>Return the details of the specified flag</p>
	<br>
	<h4>Parameters</h4>
	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>       
				<td width="200">id</td>
				<td>The flag identifier</td>
			</tr>
		</tbody>
	</table>

	<h4>Example</h4>
	<pre class="code"><?= Uri::create('api/flag/4/details') ?></pre>

	<h4>Output for public flag</h4>
	<pre class="code">{
    "data": {
        "success": true,
        "flag": {
            "id": "4",
            "type": "Aloha",
            "title": "Title",
            "private": "0",
            "description": "Description",
            "latitude": "123.23",
            "longitude": "123.43",
            "location_id": "5",
            "location_type": "Mall",
            "floor": null,
            "image": "",
            "owner": "188",
            "vote_status": 0
        }
    },
    "meta": {
        "error": "",
        "status": 1
    }
}</pre>

    <h4>Output for private flag</h4>
	<pre class="code">{
    "data": {
        "success": true,
        "flag": {
            "id": "4",
            "type": "Aloha",
            "title": "Title",
            "private": "1",
            "description": "Description",
            "latitude": "123.23",
            "longitude": "123.43",
            "location_id": "5",
            "location_type": "Mall",
            "floor": null,
            "image": "",
            "invited_users": [
                {
                    "id": "110",
                    "email": "jam@molloy.com",
                    "fbuid": null,
                    "image": "",
                    "name": "Jamie Molloy"
                },
                {
                    "id": "111",
                    "email": "lee@max.com",
                    "fbuid": null,
                    "image": "",
                    "name": "Leann Maxwell"
                }
            ],
            "owner": "188",
            "vote_status": 0
        }
    },
    "meta": {
        "error": "",
        "status": 1
    }
}</pre>
    
</div>