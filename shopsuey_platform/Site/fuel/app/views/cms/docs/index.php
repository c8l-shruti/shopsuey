<div id="doc-content" class="mt20">
    <h1><a id="accessing-the-api"></a>Introduction</h1>
    <p>To access the API you must first set up an application via the ShopSuey CMS' <a href="<?=URI::create('developer')?>">Developer Portal</a>. All calls to API endpoints must supply an access_key. The access_key is supplied when a user authenticates.</p>
	<p>When sending requests to the API, send the content as "<span class="monospace">application/x-www-form-urlencoded</span>".<br>Parameters are to be sent in the body of the request for <span class="monospace">POST</span> and <span class="monospace">PUT</span> methods and in the url for <span class="monospace">GET</span> and <span class="monospace">DELETE</span></p>
    <br>
    <h4><a id="accessing-the-api-ouptput"></a>Output</h4>
    <p>The API outputs data in various formats via the 'format' parameter. Valid formats are as follows:</p>
    <ul>
        <li>JSON <i>(default)</i></li>
        <li>XML</li>
    </ul>
	<br>
	<h4>Testing</h4>
	<p>You can make calls to the API using our <a href="<?=Uri::create('developer/test')?>">web interface</a>. This is a good place to test parameters and view output.</p>
	<br>
</div>