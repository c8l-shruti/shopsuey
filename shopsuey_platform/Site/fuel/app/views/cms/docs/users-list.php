    <div id="doc-content" class="mt20">
        <h1><a id="users"></a>Users List (People module)</h1>
		<p>Outputs a list of users</p>
        <table class="grid" cellspacing="2" cellpadding="5" border="1">
            <tbody>
                <tr>
                    <td width="100">Endpoint</td>
                    <td><?=Config::get('base_url')?>api/users/list</td>
                </tr>
                <tr>
                    <td>Methods</td>
                    <td><a href="#users-get">GET</a></td>
                </tr>
            </tbody>
        </table>

        <h3><a id="users-get"></a>GET</h3>
        <br>
        <h4>Parameters</h4>
        <table class="grid" cellspacing="2" cellpadding="5" border="1">
            <tbody>
                <tr>
                    <td>following</td>
                    <td>Get following users only (possible values: 1, 0)</td>
                </tr>
                <tr>
                    <td>followers</td>
                    <td>Get followers users only (possible values: 1, 0)</td>
                </tr>
                <tr>
                    <td>nearby</td>
                    <td>Get nearby users only (possible values: 1, 0)</td>
                </tr>
                <tr>
                    <td>latitude</td>
                    <td>Current user latitude</td>
                </tr>
                <tr>
                    <td>longitude</td>
                    <td>Current user longitude</td>
                </tr>
                <tr>
                    <td>paging</td>
                    <td>Get paged results (possible values: 1, 0)</td>
                </tr>
                <tr>
                    <td>page</td>
                    <td>INTEGER value referencing results page to display</td>
                </tr>
                <tr>
                    <td>radius</td>
                    <td>INTEGER value for the radius</td>
                </tr>
                <tr>
                    <td>name</td>
                    <td>STRING value to filter by name (partial match)</td>
                </tr>
                <tr>
                    <td>email</td>
                    <td>STRING Comma separated list of emails (exact match)</td>
                </tr>
                <tr>
                    <td>fbuid</td>
                    <td>STRING Comma separated list of fbuids (exact match)</td>
                </tr>
                <tr>
                  <td>format</td>
                  <td>json | xml</td>
                </tr>
            </tbody>
        </table>

        <h4>Example</h4>
        <pre class="code"><?=Config::get('base_url')?>api/users/list?login_hash=1cd58d83268b94b4a2493561302202f817469387&longitude=1&latitude=1&nearby=1&following=1</pre>

        <h4>Output</h4>
        <pre class="code">{
    "data": {
        "users": [
            {
                "id": "142",
                "email": "developer@microedition.biz",
                "image": "http://shopsuey/assets/img/users/small_test.jpg",
                "fbuid": "examplefbuid",
                "name": "test user",
                "following": true,
                "follower": true,
                "online": false,
                "latitude": "1",
                "longitude": "1"
            }
        ]
    }
}
</pre>
	</div>