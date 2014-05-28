    <div id="doc-content" class="mt20">
        <h1><a id="auth"></a>Auth</h1>
        <p>Authenticates a user and the application</p>
        <table class="grid" cellspacing="2" cellpadding="5" border="1">
            <tbody>
                <tr>
                    <td width="100">Endpoint</td>
                    <td><?=Config::get('base_url')?>api/auth</td>
                </tr>
                <tr>
                    <td>Methods</td>
                    <td><a href="#auth-post">POST</a> | <a href="#auth-delete">DELETE</a></td>
                </tr>
            </tbody>
        </table>

        <h3><a id="auth-post"></a>POST</h3>
        <br>
        <h4>Parameters</h4>
        <table class="grid" cellspacing="2" cellpadding="5" border="1">
            <tbody>
                <tr>
                    <td width="100">app_id</td>
                    <td>The app token</td>
                </tr>
            		<tr>
                    <td width="100">username</td>
                    <td>The user login name</td>
                </tr>
                <tr>
                    <td>password</td>
                    <td>The user's password</td>
                </tr>
                    <tr>
                      <td>format</td>
                      <td>json | xml</td>
                    </tr>
            </tbody>
        </table>

        <h4>Example</h4>
        <pre class="code"><?=Config::get('base_url')?>api/auth/?app_id=c4ca4238a0b923820dcc509a6f75849b&username=admin&password=mypass123</pre>

        <h4>Output</h4>
        <pre class="code">{
  "data":{
		"id" : 301,
    "login_hash" : "8663da6dec2721623b7f7ff0e26cf3159ce66054",
    "ip": "127.0.0.1",
    "user_id": "187",
    "application_id": "3",
    "expiracy": null,
    "created_at": 1360110103,
    "updated_at": 1360110103,
		"application":{
        "token":"c4ca4238a0b923820dcc509a6f75849b",
        "description":"ShopSuey iPhone app",
        "name":"ShopSuey iOS"
    },
		"user":{
			"created_at":"1346207762",
			"email":"c.tullos@illumifi.net",
			"group":1000,
			"id":"52",
			"level":"1000",
			"location_id": null,
			"meta":{
			  "dob":{
					"month":"05",
					"day":"26",
					"year":"1977"
			  },
			  "fbuid":"ctullos",
			  "real_name":"Cam Tullos",
			  "fullname":"Cam Tullos",
			  "gender":"male",
			  "phone":"614-787-4966",
			  "remember":"1",
			  "zipcode":"96816"
			}
    }
  },
  "meta":{
     "status":1,
     "error":""
  }
}</pre>
        <h3><a id="auth-delete"></a>DELETE</h3>
        <p>Removes a created access key, essentially invalidating the user</p>
        <br>
        <h4>Parameters</h4>
        <table class="grid" cellspacing="2" cellpadding="5" border="1">
            <tbody>
                <tr>
                    <td width="100">login_hash</td>
                    <td>Login hash provided by auth/post object</td>
                </tr>
            </tbody>
        </table>

        <h4>Example</h4>
        <pre class="code"><?=Config::get('base_url')?>api/auth/?login_hash=5007d09b45929</pre>

        <h4>Output</h4>
        <pre class="code">{
  "data":{
    "logout":true
  },
  "meta":{
     "status":1,
     "error":""
  }
}</pre>
	</div>