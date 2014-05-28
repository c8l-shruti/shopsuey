    <div id="doc-content" class="mt20">
        <h1><a id="user-reset"></a>User - Image Delete</h1>
		<p>Deletes the avatar image for the user</p>
        <table class="grid" cellspacing="2" cellpadding="5" border="1">
            <tbody>
                <tr>
                    <td width="100">Methods</td>
                    <td><a href="#user-reset-method">POST</a></td>
                </tr>
            </tbody>
        </table>

        <h3><a id="user-reset-method"></a>POST</h3>
        <br>

		<h4>Endpoint</h4>
        <pre class="code"><?=Uri::create('api/user/image_delete')?></pre>
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

		<h4>Output</h4>
		<pre class="code">{
  "data":{
    "success" : true
  },
  "meta":{
    "error":null,
    "status":1
  }
}
		</pre>
	</div>