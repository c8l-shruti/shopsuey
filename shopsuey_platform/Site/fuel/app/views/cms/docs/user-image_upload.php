    <div id="doc-content" class="mt20">
        <h1><a id="user-reset"></a>User - Image Upload</h1>
		<p>Uploads an avatar image for the user</p>
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
        <pre class="code"><?=Uri::create('api/user/image_upload')?></pre>
		<br>

        <h4>Parameters</h4>
        <table class="grid" cellspacing="2" cellpadding="5" border="1">
            <tbody>
				<tr>
					<td>image</td>
					<td>The image to upload</td>
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
    "image" : "http://72d850253b2317faabc3-3ba6dc17ba28e57eb884debb91d8cc56.r70.cf1.rackcdn.com/avatar_511a599c3baeaYNrVaf3w"
  },
  "meta":{
    "error":null,
    "status":1
  }
}
		</pre>
	</div>