    <div id="doc-content" class="mt20">
        <h1><a id="user-forgot"></a>User - Update User Profiling</h1>
		<p>Updates the user profiling</p>
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
        <pre class="code"><?=Uri::create('api/user/update_profiling')?>/(email)</pre>
		<br>

        <h4>Parameters</h4>
        <table class="grid" cellspacing="2" cellpadding="5" border="1">
            <tbody>
				<tr>
					<td>profiling_choices_ids</td>
					<td>Comma separated profiling choices ids (e.g.: '3,4,7,12')</td>
				</tr>
                <tr>
                  <td width="100">format</td>
                  <td>json | xml</td>
                </tr>
            </tbody>
        </table>

        <h4>Example</h4>
        <pre class="code"><?=Config::get('base_url')?>api/user/update_profiling</pre>
        
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