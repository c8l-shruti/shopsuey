<div id="doc-content" class="mt20">
	<h1><a id="event"></a>Flag</h1>

	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td width="100">Endpoint</td>
				<td><?=Config::get('base_url')?>api/flag/(id)<br>The id is required for PUT method</td>
			</tr>
			<tr>
				<td>Methods</td>
				<td><a href="#event-post">POST</a>, <a href="#event-put">PUT</a></td>
			</tr>
		</tbody>
	</table>

	<h3><a id="event-post"></a>POST</h3>
	<p>Create a new flag</p>
	<br>
	<h4>Parameters</h4>
	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>       
				<td width="200">title</td>
				<td>A title for the flag</td>
			</tr>
			<tr>       
				<td width="200">description (optional)</td>
				<td>A description for the flag </td>
			</tr>
            <tr>
                <td width="200">type</td>
				<td>The flag type. Can be "Aloha", "POI", "Community", "Attraction"</td>
			</tr>
            <tr>
                <td width="200">latitude</td>
				<td>Flag location</td>
			</tr>
            <tr>
                <td width="200">longitude</td>
				<td>Flag location</td>
			</tr>
            <tr>
                <td width="200">location_id (optional)</td>
				<td>The location identifier </td>
			</tr>
            <tr>
                <td width="200">floor (optional)</td>
				<td>The floor number. Only if mall_id was sent</td>
			</tr>
            <tr>
                <td width="200">private</td>
				<td>Bool that determine if the flag should be visible to all users (who are following you) or just the ones you will invite</td>
			</tr>
            <tr>
                <td width="200">invited_users (optional)</td>
				<td>
                    An array with the usernames of the users that you want to invite. This users will see the flag if the private parameter is true. Required only if private parameter is true.
                </td>
			</tr>
            <tr>
                <td width="200">image[content] (optional)</td>
				<td>Base64 of the image for the flag</td>
			</tr>
            <tr>
                <td width="200">image[extension] (optional)</td>
				<td>Extension of the image for the flag</td>
			</tr>
		</tbody>
	</table>

	<h4>Example</h4>
	<pre class="code"><?=Config::get('base_url')?>api/flag/</pre>

	<h4>Output</h4>
	<pre class="code">{
    "data": {
        "success": true,
        "id": 4,
        "image":"http://shopsuey/assets/img/flags/large_5255a71567dc2.jpg"
    },
    "meta": {
        "error": "",
        "status": 1
    }
}</pre>

    
    <h3><a id="event-put"></a>PUT</h3>
	<p>Edit an existing flag</p>
	<br>
	<h4>Parameters</h4>
	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>       
				<td width="200">title (optional)</td>
				<td>A new title for the flag</td>
			</tr>
			<tr>       
				<td width="200">description (optional)</td>
				<td>A new description for the flag </td>
			</tr>
            <tr>
                <td width="200">type (optional)</td>
				<td>The flag type. Can be "Aloha", "POI", "Community", "Attraction"</td>
			</tr>
            <tr>
                <td width="200">latitude (optional)</td>
				<td>New flag location</td>
			</tr>
            <tr>
                <td width="200">longitude (optional)</td>
				<td>new flag location</td>
			</tr>
            <tr>
                <td width="200">private (optional)</td>
				<td>Boolean that change the flag visibility</td>
			</tr>
            <tr>
                <td width="200">image[content] (optional)</td>
				<td>Base64 of the image for the flag</td>
			</tr>
            <tr>
                <td width="200">image[extension] (optional)</td>
				<td>Extension of the image for the flag</td>
			</tr>
		</tbody>
	</table>

	<h4>Example</h4>
	<pre class="code"><?=Config::get('base_url')?>api/flag/2</pre>

	<h4>Output</h4>
	<pre class="code">{
    "data": {
        "success": true,
        "image":"http://shopsuey/assets/img/flags/large_5255a71567dc2.jpg"
    },
    "meta": {
        "error": "",
        "status": 1
    }
}</pre>
    
</div>