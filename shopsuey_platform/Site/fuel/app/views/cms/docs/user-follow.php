    <div id="doc-content" class="mt20">
        <h1>User - Follow</h1>
		<p>Follow a user</p>
        <table class="grid" cellspacing="2" cellpadding="5" border="1">
            <tbody>
                <tr>
                    <td width="100">Methods</td>
                    <td><a href="#user-method">POST</a></td>
                </tr>
            </tbody>
        </table>

        <h3><a id="user-method"></a>POST</h3>
        <br>

		<h4>Endpoint</h4>
        <pre class="code"><?=Uri::create('api/user/follow')?></pre>
		<br>

        <h4>Parameters</h4>
        <table class="grid" cellspacing="2" cellpadding="5" border="1">
            <tbody>
				<tr>
					<td>user_id</td>
					<td>Suey user id of who to follow</td>
				</tr>
            </tbody>
        </table>

        <h4>Example</h4>
        <pre class="code"><?=Uri::create('api/user/follow')?></pre>
        
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
