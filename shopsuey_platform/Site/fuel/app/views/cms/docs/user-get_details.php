<div id="doc-content" class="mt20">
    <h1><a id="user-reset"></a>User - Get Details</h1>
    <p>Retrieve the details for a specific user.</p>
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
    <pre class="code"><?= Uri::create('api/user/id/details') ?></pre>
    <br>
    
    <h4>Parameters</h4>
    <table class="grid" cellspacing="2" cellpadding="5" border="1">
        <tbody>
            <tr>
                <td>id</td>
                <td>The user identifier</td>
            </tr>
        </tbody>
    </table>

    <h4>Example</h4>
    <pre class="code"><?= Uri::create('api/user/188/details') ?></pre>
    <br>
    
    <h4>Output</h4>
    <pre class="code">{
    "data": {
        "id": "188",
        "email": "federico@gmail.com",
        "fbuid": null,
        "followers_count": 6,
        "following_count": 6,
        "following": false,
        "follower": false,
        "is_online": false,
        "latitude": "21.291148",
        "longitude": "-157.843498",
        "name": "federico",
        "image": null
    },
    "meta": {
        "error": "",
        "status": 1
    }
}
    </pre>
    
    <h4>Output for users without location data</h4>
    <pre class="code">{
    "data": {
        "id": "113",
        "email": "brian@max.com",
        "fbuid": null,
        "followers_count": 1,
        "following_count": 1,
        "following": true,
        "follower": true,
        "is_online": null,
        "latitude": null,
        "longitude": null,
        "name": "Brian Maxwell",
        "image": "http://shopsuey/image/2dfjf02f09202f0f0239k0f23f9.jpg"
    },
    "meta": {
        "error": "",
        "status": 1
    }
}
    </pre>
</div>