<div id="doc-content" class="mt20">
    <h1><a id="user-reset"></a>User - Followers</h1>
    <p>Retrieves the list of followers for the defined user</p>
    <table class="grid" cellspacing="2" cellpadding="5" border="1">
        <tbody>
            <tr>
                <td width="100">Methods</td>
                <td><a>GET</a></td>
            </tr>
        </tbody>
    </table>

    <h3><a id="user-reset-method"></a>GET</h3>
    <br>
    
    <h4>Endpoint</h4>
    <pre class="code"><?= Uri::create('api/user/id/followers') ?></pre>
    <br>
    
    <h4>Parameters</h4>
    <table class="grid" cellspacing="2" cellpadding="5" border="1">
        <tbody>
            <tr>
                <td>id</td>
                <td>The user identifier</td>
            </tr>
            <tr>
                <td>page</td>
                <td>INTEGER value referencing results page to display.</td>
            </tr>
        </tbody>
    </table>

    <h4>Example</h4>
    <pre class="code"><?= Uri::create('api/user/180/followers') ?></pre>
    <br>
    
    <h4>Output</h4>
    <pre class="code">{
    "data": {
        "followers": [
            {
                "id": "113",
                "email": "brian@max.com",
                "fbuid": null,
                "name": "Brian Maxwell",
                "image": "http://shopsuey/image/2dfjf02f09202f0f0239k0f23f9.jpg"
            },
            {
                "id": "110",
                "email": "jam@molloy.com",
                "fbuid": null,
                "name": "Jamie Molloy",
                "image": null
            },
            {
                "id": "112",
                "email": "jason@max.com",
                "fbuid": null,
                "name": "Jason Max",
                "image": null
            },
            {
                "id": "111",
                "email": "lee@max.com",
                "fbuid": null,
                "name": "Leann Maxwell",
                "image": null
            },
            {
                "id": "180",
                "email": "suzannefee@gmail.com",
                "fbuid": null,
                "name": "Suzanne Fee",
                "image": null
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
            "records": 5
        },
        "status": 1,
        "error": null
    }
}
    </pre>
</div>