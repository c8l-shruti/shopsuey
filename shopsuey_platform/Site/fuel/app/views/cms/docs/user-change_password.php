    <div id="doc-content" class="mt20">
        <h1><a id="user-forgot"></a>User - Forgot</h1>
		<p>Changes the password</p>
        <table class="grid" cellspacing="2" cellpadding="5" border="1">
            <tbody>
                <tr>
                    <td width="100">Methods</td>
                    <td><a href="#user-forgot-method">POST</a></td>
                </tr>
            </tbody>
        </table>

        <h3><a id="user-forgot-method"></a>POST</h3>
        <br>

		<h4>Endpoint</h4>
        <pre class="code"><?=Uri::create('api/user/change_password')?>/(email)</pre>
		<br>

        <h4>Parameters</h4>
        <table class="grid" cellspacing="2" cellpadding="5" border="1">
            <tbody>
				<tr>
					<td>old_password</td>
					<td>Current user password</td>
				</tr>
				<tr>
					<td>new_password</td>
					<td>New password</td>
				</tr>
                <tr>
                  <td width="100">format</td>
                  <td>json | xml</td>
                </tr>
            </tbody>
        </table>

        <h4>Example</h4>
        <pre class="code"><?=Config::get('base_url')?>api/user/change_password</pre>
        
        <h4>Output</h4>
		<pre class="code">{
  "data":{
    "status": true
  }
  "meta":{
    "error":"",
    "status":1
  }
}</pre>
	</div>