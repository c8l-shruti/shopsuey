    <div id="doc-content" class="mt20">
        <h1><a id="user-reset"></a>User - Reset</h1>
		<p>Resets a user's password and sends it to them via the email address associated with their account</p>
        <table class="grid" cellspacing="2" cellpadding="5" border="1">
            <tbody>
                <tr>
                    <td width="100">Methods</td>
                    <td><a href="#user-reset-method">PUT</a></td>
                </tr>
            </tbody>
        </table>

        <h3><a id="user-reset-method"></a>PUT</h3>
        <br>

		<h4>Endpoint</h4>
        <pre class="code"><?=Uri::create('api/user/reset')?>/(email)</pre>
		<br>

        <h4>Parameters</h4>
        <table class="grid" cellspacing="2" cellpadding="5" border="1">
            <tbody>
				<tr>
					<td>app_id</td>
					<td>Application ID</td>
				</tr>
                <tr>
                  <td width="100">format</td>
                  <td>json | xml</td>
                </tr>
            </tbody>
        </table>

		<h4>Output</h4>
		<pre class="code">{
  "data":{
    "username":"c.tullos@illumifi.net",
    "email":"c.tullos@illumifi.net"
  },
  "meta":{
    "error":"",
    "status":1
  }
}
		</pre>
	</div>