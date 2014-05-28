    <div id="doc-content" class="mt20">
        <h1><a id="user"></a>User</h1>
        <table class="grid" cellspacing="2" cellpadding="5" border="1">
            <tbody>
                <tr>
                    <td width="100">Endpoint</td>
                    <td><?=Config::get('base_url')?>api/user/(id)<br>The id is required for GET, and PUT methods</td>
                </tr>
                <tr>
                    <td>Methods</td>
                    <td><a href="#user-get">GET</a> | <a href="#user-post">POST</a> | <a href="#user-put">PUT</a></td>
                </tr>
            </tbody>
        </table>

        <h3><a id="user-get"></a>GET</h3>
		<p>Retrieve a user by id</p>
        <br>
        <h4>Parameters</h4>
        <table class="grid" cellspacing="2" cellpadding="5" border="1">
            <tbody>
                <tr>
                  <td width="100">format</td>
                  <td>json | xml</td>
                </tr>
            </tbody>
        </table>

        <h4>Example</h4>
        <pre class="code"><?=Config::get('base_url')?>api/user/2/?login_hash=5007d09b45929</pre>

        <h4>Output</h4>
		<pre class="code">{
  "data":{
    "user":{
      "created_at":"1342678458",
      "email":"ctullos@illumifi.net",
      "group":1000,
      "id":"2",
      "meta":{
        "dob":{
          "month":"4",
          "day":"22",
          "year":"1978"
        },
        "fbuid":"ctullos",
        "real_name":"Administrator",
        "fullname":"Administrator",
        "gender":"male",
        "phone":"614-787-4966",
        "remember":null,
        "zipcode":"96815"
      }
    }
  },
  "meta":{
    "error":"",
    "status":1
  }
}</pre>
        <h3><a id="user-post"></a>POST</h3>
		<p>Create a new user. Note: the login hash is NOT required when creating a new user.</p>
        <br>
        <h4>Parameters</h4>
        <table class="grid" cellspacing="2" cellpadding="5" border="1">
            <tbody>
				<tr>
					<td>email</td>
					<td>The user's email will be used as the 'username'</td>
				</tr>
				<tr>
					<td>password</td>
					<td>Password must be 8 characters and contain at least one capital letter and one number. Example: Password1</td>
				</tr>
				<tr>
					<td>real_name</td>
					<td>The user's real name (required if emailpass is specified).</td>
				</tr>
				<tr>
					<td>gender (optional)</td>
					<td>The user's gender. <br>male | female | other</td>
				</tr>
				<tr>
					<td>phone (optional)</td>
					<td>The user's contact phone number</td>
				</tr>
				<tr>
					<td>zipcode (optional)</td>
					<td>The user's zip code</td>
				</tr>
				<tr>
					<td>dob (optional)</td>
					<td>The user's date of birth<br>mm/dd/YYYY format</td>
				</tr>
				<tr>
					<td>fbuid (optional)</td>
					<td>The user's FBUID</td>
				</tr>
				<tr>
					<td>emailpass</td>
					<td>Whether or not to email the password to the user. </td>
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
    "user":{
      "created_at":"1346207762",
      "email":"c.tullos@illumifi.net",
      "group":1000,
      "location_id": null,
      "id":"52",
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
    "error":"",
    "status":1
  }
}
		</pre>
        <h3><a id="user-put"></a>PUT</h3>
		<p>Update a user. Note: the login_hash IS required when updating a user.</p>
        <br>
        <h4>Parameters</h4>
        <table class="grid" cellspacing="2" cellpadding="5" border="1">
            <tbody>
				<tr>
					<td>login_hash</td>
					<td>User's login hash</td>
				</tr>
				<tr>
					<td>email</td>
					<td>The user's email will be used as the 'username'</td>
				</tr>
				<tr>
					<td>password</td>
					<td>Password must be 8 characters and contain at least one capital letter and one number. Example: Password1</td>
				</tr>
				<tr>
					<td>real_name</td>
					<td>The user's real name (required if emailpass is specified).</td>
				</tr>
				<tr>
					<td>gender (optional)</td>
					<td>The user's gender. <br>male | female | other</td>
				</tr>
				<tr>
					<td>phone (optional)</td>
					<td>The user's contact phone number</td>
				</tr>
				<tr>
					<td>zipcode (optional)</td>
					<td>The user's zip code</td>
				</tr>
				<tr>
					<td>dob (optional)</td>
					<td>The user's date of birth<br>mm/dd/YYYY format</td>
				</tr>
				<tr>
					<td>fbuid (optional)</td>
					<td>The user's FBUID</td>
				</tr>
				<tr>
					<td>emailpass</td>
					<td>Whether or not to email the password to the user. </td>
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
    "user":{
      "created_at":"1346207762",
      "email":"c.tullos@illumifi.net",
      "group":1000,
      "id":"52",
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
    "error":"",
    "status":1
  }
}
		</pre>
	</div>