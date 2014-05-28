<div id="doc-content" class="mt20">
	<h1>Preferences</h1>

	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td width="100">Endpoint</td>
				<td><?=Config::get('base_url')?>api/preferences/</td>
			</tr>
			<tr>
				<td>Methods</td>
				<td><a href="#preferences-get">GET</a> | <a href="#preferences-post">POST</a></td>
			</tr>
		</tbody>
	</table>

	<h3><a id="preferences-get"></a>GET</h3>
	<br>
	<h4>Parameters</h4>
	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
                <td>access_key</td>
                <td>User's access key</td>
            </tr>
		</tbody>
	</table>

	<h4>Example</h4>
	<pre class="code"><?=Config::get('base_url')?>api/preferences?access_key=14781489abc</pre>

	<h4>Output</h4>
	<pre class="code">
{
    "deal_alerts": false,
    "event_alerts": false,
    "meeting_place_alerts": true,
    "rsvps": true,
    "event_reminders": true,
    "allow_friends_to_see_me": false,
    "allow_friends_to_see_my_location": true
}
    </pre>
    
    
    <h3><a id="preferences-post"></a>POST</h3>
	<br>
	<h4>Parameters</h4>
	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
                <td>access_key</td>
                <td>User's access key</td>
            </tr>
            <tr>
                <td>deal_alerts</td>
                <td>(0 for false, 1 for true) Disable/Enable Offers received by push-notifications</td>
            </tr>
            <tr>
                <td>event_alerts</td>
                <td>(0 for false, 1 for true) Disable/Enable Events received by push-notifications and messsages</td>
            </tr>
            <tr>
                <td>meeting_place_alerts</td>
                <td>(0 for false, 1 for true) Disable/Enable Meeting Points received by push-notifications and messsages</td>
            </tr>
            <tr>
                <td>rsvps</td>
                <td>(0 for false, 1 for true)</td>
            </tr>
            <tr>
                <td>event_reminders</td>
                <td>(0 for false, 1 for true) </td>
            </tr>
            <tr>
                <td>allow_friends_to_see_me</td>
                <td>(0 for false, 1 for true) If this is false, the server doesn't include this user on his friends' list.</td>
            </tr>
            <tr>
                <td>allow_friends_to_see_my_location</td>
                <td>(0 for false, 1 for true) If the user set this true, when the server sends information about this user, it will include current location information.</td>
            </tr>
		</tbody>
	</table>

	<h4>Example</h4>
	<pre class="code"><?=Config::get('base_url')?>api/preferences</pre>

	<h4>Output</h4>
	<pre class="code">
{
    "data": {
        "deal_alerts": false,
        "event_alerts": true,
        "meeting_place_alerts": true,
        "rsvps": false,
        "event_reminders": true,
        "allow_friends_to_see_me": true,
        "allow_friends_to_see_my_location": false
    },
    "meta": {
        "status": 1
    }
}
    </pre>
</div>