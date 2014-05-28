    <div id="doc-content" class="mt20">
        <h1><a id="user"></a>User</h1>
        <table class="grid" cellspacing="2" cellpadding="5" border="1">
            <tbody>
                <tr>
                    <td width="100">Endpoint</td>
                    <td><?=Config::get('base_url')?>api/user/facebook</td>
                </tr>
                <tr>
                    <td>Methods</td>
                    <td><a href="#user-post">POST</a></td>
                </tr>
            </tbody>
        </table>

        <h3><a id="user-post"></a>POST</h3>
		<p>Login the user with the given access_token. If the user does not exist it will be created as well. Only call this method via HTTPS!</p>
        <br>
        <h4>Parameters</h4>
        <table class="grid" cellspacing="2" cellpadding="5" border="1">
            <tbody>
				<tr>
					<td>app_id</td>
					<td>Your app id</td>
				</tr>
				<tr>
					<td>access_token</td>
					<td>access_token obtained from Facebook</td>
				</tr>
                <tr>
                  <td>format</td>
                  <td>json | xml</td>
                </tr>
            </tbody>
        </table>

        <h4>Output</h4>
		<pre class="code">{
  "data":{
     "app":{
        "access_key":"5007d09b45929",
        "appid":"c4ca4238a0b923820dcc509a6f75849b",
        "description":"ShopSuey iPhone app",
        "name":"ShopSuey iOS"
     },
     "user":{
		"created_at":"1346207762",
		"email":"c.tullos@illumifi.net",
		"group":{
		  "name":"User",
		  "roles":["user"],
		  "level":"1"
		},
		"id":"52",
		"level":"1",
		"meta":{
		  "company":null,
		  "dob":[],
		  "fbuid":"ctullos",
		  "real_name":null,
		  "fullname":"",
		  "gender":null,
		  "phone":null,
		  "remember":null,
		  "udid":null,
		  "zipcode":null
		},
		"username":"c.tullos@illumifi.net"
    }
  },
  "meta":{
     "status":1,
     "error":""
  }
}
		</pre>

	</div>