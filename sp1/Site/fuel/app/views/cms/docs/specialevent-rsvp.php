<div id="doc-content" class="mt20">
	<h1><a id="event"></a>Special Event RSVP</h1>

	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td width="100">Endpoint</td>
				<td><?=Config::get('base_url')?>api/specialevent/:id/rsvp</td>
			</tr>
			<tr>
				<td>Methods</td>
				<td><a href="#event-rsvp-post">POST</a></td>
			</tr>
		</tbody>
	</table>

	<h3><a id="event-rsvp-post"></a>POST</h3>
	<p>RSVP to a special event</p>
	<br>
	<h4>Parameters</h4>
	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
			  <td>rsvp_status</td>
			  <td>0 for "not attending", 1 for "attending"</td>
			</tr>
		</tbody>
	</table>

	<h4>Example</h4>
	<pre class="code"><?=Config::get('base_url')?>api/specialevent/1/rsvp</pre>

	<h4>Output</h4>
	<pre class="code">{
    "data": {
        "event": {
            "social": {
                "facebook": "http://www.facebook.com/event",
                "foursquare": "",
                "pintrest": "",
                "twitter": ""
            },
            "updated_at": 1380230579,
            "id": "1",
            "created_by_id": "188",
            "edited_by_id": "188",
            "title": "Some Cool Special Event",
            "description": "Something here",
            "logo": "",
            "landing_screen_img": "",
            "main_location_id": "5",
            "coordinator_phone": "123 456 7890",
            "coordinator_email": "coord@event.com",
            "website": "http://event.com/",
            "show_dates": "1",
            "date_start": "2013-09-03 18:17:00",
            "date_end": "2013-09-03 00:00:00",
            "status": "1",
            "tags": "tag1,tag2,tag3",
            "force_top_message": "0",
            "created_at": "1380226208"
        }
    },
    "meta": {
        "error": null,
        "status": 1
    }
}</pre>
</div>