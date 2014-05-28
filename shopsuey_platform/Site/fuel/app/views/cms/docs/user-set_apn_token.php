    <div id="doc-content" class="mt20">
        <h1>User - Set APN Token</h1>
		<p>Sets the user APN Token in order to send him push notifications in the future</p>
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
        <pre class="code"><?=Uri::create('api/user/set_apn_token')?></pre>
		<br>

        <h4>Parameters</h4>
        <table class="grid" cellspacing="2" cellpadding="5" border="1">
            <tbody>
				<tr>
					<td>token</td>
					<td>Hex representation with no spaces of the device token. (e.g.: ef9e8f4e5f011ec15c0c5d66c2e4e5f6a1f6233e457c3b8745a4af8a6137fa93)</td>
				</tr>
                <tr>
					<td>bundleid</td>
					<td>ID of the app bundle (e.g.: com.shoppingmademobile.shopsueydev)</td>
				</tr>
                <tr>
					<td>environment</td>
					<td>dist (for production notifications) or dev (for sandbox notifications)</td>
				</tr>
            </tbody>
        </table>

        <h4>Example</h4>
        <pre class="code"><?=Uri::create('api/user/set_apn_token')?></pre>
        
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