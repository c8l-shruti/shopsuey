    <div id="doc-content" class="mt20">
        <h1><a id="user-reset"></a>Session Start</h1>
		<p>Saves the time when the session ends</p>
        <table class="grid" cellspacing="2" cellpadding="5" border="1">
            <tbody>
                <tr>
                    <td width="100">Methods</td>
                    <td><a href="#session-end">POST</a></td>
                </tr>
            </tbody>
        </table>

        <h3><a id="#session-end"></a>POST</h3>
        <br>

		<h4>Endpoint</h4>
        <pre class="code"><?=Uri::create('api/session/end')?></pre>
		<br>

        <h4>Parameters</h4>
        <table class="grid" cellspacing="2" cellpadding="5" border="1">
            <tbody>
                <tr>
					<td>session_id</td>
					<td>The id of the session that you want to end</td>
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
    "session_id" : 1
  },
  "meta":{
    "error":null,
    "status":1
  }
}
		</pre>
	</div>