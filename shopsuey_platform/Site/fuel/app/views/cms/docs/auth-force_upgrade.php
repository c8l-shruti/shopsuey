    <div id="doc-content" class="mt20">
        <h1><a id="auth"></a>Force Upgrade Check</h1>
        <p>Checks if the iOS app needs to be upgrade</p>
        <table class="grid" cellspacing="2" cellpadding="5" border="1">
            <tbody>
                <tr>
                    <td width="100">Endpoint</td>
                    <td><?=Config::get('base_url')?>api/auth/force_upgrade</td>
                </tr>
                <tr>
                    <td>Methods</td>
                    <td><a href="#auth-post">GET</a>
                </tr>
            </tbody>
        </table>

        <h3><a id="auth-post"></a>GET</h3>
        <br>
        <h4>Parameters</h4>
        <table class="grid" cellspacing="2" cellpadding="5" border="1">
            <tbody>
                <tr>
                    <td width="100">version</td>
                    <td>The current version of the app on the device (string)</td>
                </tr>
                <tr>
                  <td>format</td>
                  <td>json | xml</td>
                </tr>
            </tbody>
        </table>

        <h4>Example</h4>
        <pre class="code"><?=Config::get('base_url')?>api/auth/force_upgrade/?version=0.6</pre>

        <h4>Output</h4>
        <pre class="code">{
  "data":{
	 "upgrade" : true,
  },
  "meta":{
     "status":1,
     "error":""
  }
}</pre>
	</div>