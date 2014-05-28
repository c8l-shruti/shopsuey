    <div id="doc-content" class="mt20">
        <h1><a id="user-forgot"></a>User - Get Profiling Choices</h1>
		<p>Get all the profiling choices</p>
        <table class="grid" cellspacing="2" cellpadding="5" border="1">
            <tbody>
                <tr>
                    <td width="100">Methods</td>
                    <td><a href="#user-forgot-method">GET</a></td>
                </tr>
            </tbody>
        </table>

        <h3><a id="user-forgot-method"></a>GET</h3>
        <br>

		<h4>Endpoint</h4>
        <pre class="code"><?=Uri::create('api/user/get_profiling_choices')?>/(email)</pre>
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
        <pre class="code"><?=Config::get('base_url')?>api/user/get_profiling_choices</pre>
        
        <h4>Output</h4>
		<pre class="code">{
  "data":{
    "images": [
        {"id": 1, "url": "example.com/image1"},
        {"id": 2, "url": "example.com/image2"}
    ]
  }
  "meta":{
    "error":"",
    "status":1
  }
}</pre>
	</div>