<div id="doc-content" class="mt20">
	<h1><a id="message"></a>Message</h1>

	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td width="100">Endpoint</td>
				<td><?=Config::get('base_url')?>api/message/</td>
			</tr>
			<tr>
				<td>Methods</td>
				<td><a href="#message-get">GET</a></td>
			</tr>
		</tbody>
	</table>

	<h3><a id="message-get"></a>GET</h3>
	<p>Get a single message</p>
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
	<pre class="code"><?=Config::get('base_url')?>api/message/1</pre>

	<h4>Output</h4>
	<pre class="code">{
  "data":{
    "message":[
      {
        "action_meta":"5",
        "action_type":"navto",
        "content":"Buy 1 get 1 free of equal or lesser value!  Good for the next 2 hours only!!!",
        "created":"2012-10-25 00:20:44",
        "date_end":"1970-01-01 12:00:00",
        "date_start":"1970-01-01 12:00:00",
        "edited":"2012-11-02 21:27:41",
        "filter_behavior":{
          "offer":{
            "viewed":[
              "0",
              "0"
            ],
            "redeemed":[
              "0",
              "0"
            ],
            "rejected":[
              "0",
              "0"
            ]
          },
          "event":{
            "viewed":[
              "0",
              "0"
            ],
            "rsvp":[
              "0",
              "0"
            ],
            "attended":[
              "0",
              "0"
            ],
            "rejected":[
              "0",
              "0"
            ]
          },
          "newuser":{
            "min":"0",
            "max":"0",
            "multiplier":"days"
          },
          "list":[
            "0",
            "0"
          ],
          "tracker":{
            "percent":[
              "0",
              "0"
            ],
            "amount":[
              "0.00",
              "0.00"
            ]
          }
        },
        "filter_demographic":{
          "age":"0",
          "zip":""
        },
        "filter_frequency":{
          "range":[
            "",
            ""
          ],
          "last":{
            "multiplier":"days"
          },
          "visits":{
            "count":"0",
            "interval":"0",
            "multiplier":"days"
          }
        },
        "filter_proximity":{
          "distance":"0",
          "multiplier":"feet"
        },
        "id":"16",
        "repeat_type":"weekly",
        "sender_meta":{
          "mall":"1"
        },
        "sender_type":"mall",
        "status":"1",
        "trigger_meta":{
          "repeat":{
            "type":"weekly",
            "days":[
              "6"
            ],
            "time":""
          }
        },
        "trigger_type":"repeat"
      }
    ]
  },
  "meta":{
    "error":null,
    "status":1
  }
}</pre>
</div>