    <div id="doc-content" class="mt20">
        <h1><a id="user"></a>User</h1>
        <table class="grid" cellspacing="2" cellpadding="5" border="1">
            <tbody>
                <tr>
                    <td width="100">Endpoint</td>
                    <td><?=Config::get('base_url')?>api/user/anonymous</td>
                </tr>
                <tr>
                    <td>Methods</td>
                    <td><a href="#user-post">POST</a></td>
                </tr>
            </tbody>
        </table>

        <h3><a id="user-post"></a>POST</h3>
		<p>Login an anonymous user with the given udid. If the user does not exist it will be created as well. Note: the access_key is NOT required for this service.</p>
        <br>
        <h4>Parameters</h4>
        <table class="grid" cellspacing="2" cellpadding="5" border="1">
            <tbody>
				<tr>
					<td>app_id</td>
					<td>Your app id</td>
				</tr>
				<tr>
					<td>udid</td>
					<td>The user's unique device ID</td>
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
		"email":"dummy_50f36ff2a3d1b@anonymous-user.com",
		"group":{
		  "name":"Anonymous",
		  "roles":["anonymous"],
		  "level":"2"
		},
		"id":"179",
		"level":"2",
		"meta":{
		  "company":null,
		  "dob":[],
		  "fbuid":null,
		  "real_name":null,
		  "fullname":"",
		  "gender":null,
		  "phone":null,
		  "remember":null,
		  "udid":"ad34bc23ea5689eaf32",
		  "zipcode":null
		},
		"username":"dummy_50f36ff2a3d1b@anonymous-user.com"
    }
  },
  "meta":{
     "status":1,
     "error":""
  }
}
		</pre>

	</div>