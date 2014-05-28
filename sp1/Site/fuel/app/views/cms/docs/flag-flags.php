<div id="doc-content" class="mt20">
	<h1><a id="event"></a>Flags</h1>

	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td width="100">Endpoint</td>
				<td><?= Uri::create('api/flags') ?></td>
			</tr>
			<tr>
				<td>Methods</td>
				<td><a href="#">GET</a></td>
			</tr>
		</tbody>
	</table>

	<h3><a id="event-get"></a>GET</h3>
	<p>Lists all of the flags that you should be able to see</p>
	<br>
	<h4>Parameters</h4>
	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>=0&=12&=120&=1000&=188&=1
			<tr>       
				<td width="200">nearby (optional)</td>
				<td>bool parameter. If it's true only nearby flags will be listed</td>
			</tr>
			<tr>
				<td width="200">latitude (optional)</td>
				<td>latitude of the current location (required if nearby is true)</td>
			</tr>
			<tr>
				<td width="200">longitude (optional)</td>
				<td>longitude of the current location (required if nearby is true)</td>
			</tr>
			<tr>       
				<td width="200">radius (optional)</td>
				<td>
                    radius of the area around current location to search for flags. Required only if nearby is true.
				</td>
			</tr>
			<tr>       
				<td width="200">owner (optional)</td>
				<td>filter flags created by the specified user id</td>
			</tr>
			<tr>       
				<td width="200">private (optional)</td>
				<td>bool parameter. If it's true only show private flags, otherwise shows any type of flags</td>
			</tr>
            <tr>
				<td width="200">outdoor_only (optional)</td>
				<td>bool parameter. If it's true only returns outdoor flags (those that are not associated to a particular location)</td>
			</tr>
            <tr>       
				<td width="200">following_user_flags (optional)</td>
				<td>bool parameter that returns the flags that were created by the users that the current user is following if it's true</td>
			</tr>
            <tr>       
				<td width="200">page (optional)</td>
				<td>integer value referencing results page to display if paging is true</td>
			</tr>
            
		</tbody>
	</table>

	<h4>Example</h4>
	<pre class="code"><?= Uri::create('api/flags') ?></pre>

	<h4>Output (without paging)</h4>
	<pre class="code">{
    "data": {
        "success": true,
        "flags": [
            {
                id: "1"
                longitude: "123.546"
                latitude: "12.234"
                owner: "188"
                description: "My description"
                location_id: "5"
                location_type: "Mall"
                floor: null
                type: "POI"
                private: "0"
                title: "My First Flag"
                vote_status: 0
                image: ""
            },
            {
                id: "2"
                longitude: "123.546"
                latitude: "12.234"
                owner: "152"
                description: "My description"
                location_id: null
                location_type: null
                type: "POI"
                private: "0"
                title: "my new title"
                vote_status: "-1"
                image: ""
            }
        ]
    },
    "meta": {
        "error": "",
        "status": 1
    }
}</pre>

    <h4>Output (with paging)</h4>
    <pre class="code">{
    "data": {
        "success": true,
        "flags": [
            {
                id: "1"
                longitude: "123.546"
                latitude: "12.234"
                owner: "188"
                description: "My description"
                location_id: "5"
                location_type: "Mall"
                floor: null
                type: "POI"
                private: "0"
                title: "My First Flag"
                vote_status: 0
                image: ""
            },
            {
                id: "2"
                longitude: "123.546"
                latitude: "12.234"
                owner: "152"
                description: "My description"
                location_id: null
                location_type: null
                type: "POI"
                private: "0"
                title: "my new title"
                vote_status: "-1"
                image: ""
            }
        ]
    },
    "meta": {
        "pagination": {
            "limit": 20,
            "offset": {
                "current": 0,
                "next": null,
                "prev": null
            },
            "page": {
                "count": 1,
                "current": 1,
                "next": null,
                "prev": null
            },
            "records": 2
        },
        "status": 1,
        "error": null
    }
}</pre>
    
</div>