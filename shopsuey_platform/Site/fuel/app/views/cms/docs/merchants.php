<div id="doc-content" class="mt20">
	<h1><a id="merchants"></a>Merchants</h1>
	<p>Outputs a list of merchants <small>(20 per request)</small></p>

	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td width="100">Endpoint</td>
				<td><?=Config::get('base_url')?>api/merchants/</td>
			</tr>
			<tr>
				<td>Methods</td>
				<td><a href="#merchants-get">GET</a></td>
			</tr>
		</tbody>
	</table>

	<h3><a id="merchants-get"></a>GET</h3>
	<br>
	<h4>Parameters</h4>
	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td>page</td>
				<td>INTEGER value referencing results page to display.</td>
			</tr>
			<tr>
			  <td width="100">format</td>
			  <td>json | xml</td>
			</tr>
		</tbody>
	</table>

	<h4>Example</h4>
	<pre class="code"><?=Config::get('base_url')?>api/merchants</pre>

	<h4>Output</h4>
<pre class="code">{
  "data":{
    "merchants":[
      {
        "address":"123 Sesame Str.",
        "categories":null,
        "city":"New York City",
        "contact":null,
        "content":"blah blah blah",
        "email":"juan@abcproperties.com",
        "hours":{
          "fri":{
            "open":"",
            "close":""
          },
          "mon":{
            "open":"09:28AM",
            "close":"10:29PM"
          },
          "sat":{
            "open":"",
            "close":""
          },
          "sun":{
            "open":"",
            "close":""
          },
          "thr":{
            "open":"09:29AM",
            "close":"08:29PM"
          },
          "tue":{
            "open":"",
            "close":""
          },
          "wed":{
            "open":"",
            "close":""
          }
        },
        "id":"1",
        "logo":"",
        "mall_id":"0",
        "max_users":"10",
        "name":"ABC Properties",
        "newsletter":"newsletter@test.com",
        "phone":"888-555-1234",
        "plan":"2",
        "social":{
          "facebook":"facebook",
          "foursquare":"foursquare",
          "pintrest":"pintrest",
          "twitter":"twitter"
        },
        "st":"NY",
        "status":"1",
        "tags":null,
        "web":"http:\/\/www.abcproperties.com",
        "zip":"10458"
      },
      {
        "address":"700 N. Nimitz Hwy",
        "categories":null,
        "city":"Honolulu",
        "contact":null,
        "content":"",
        "email":"tester1@shoppingmademobile.com",
        "hours":null,
        "id":"2",
        "logo":"",
        "mall_id":"0",
        "max_users":"10",
        "name":"Hilo Hattie",
        "newsletter":"",
        "phone":"808-555-5555",
        "plan":"2",
        "social":null,
        "st":"HI",
        "status":"1",
        "tags":null,
        "web":"http:\/\/www.hilohattie.com",
        "zip":"96817"
      },
      {
        "address":"222 Kaiulani Ave #304",
        "categories":null,
        "city":"Honolulu",
        "contact":null,
        "content":"The creators",
        "email":"c.tullos@illumifi.net",
        "hours":{
          "fri":{
            "open":"",
            "close":""
          },
          "mon":{
            "open":"",
            "close":""
          },
          "sat":{
            "open":"",
            "close":""
          },
          "sun":{
            "open":"",
            "close":""
          },
          "thr":{
            "open":"",
            "close":""
          },
          "tue":{
            "open":"",
            "close":""
          },
          "wed":{
            "open":"",
            "close":""
          }
        },
        "id":"3",
        "logo":"",
        "mall_id":"0",
        "max_users":"0",
        "name":"Illumifi Interactive",
        "newsletter":"",
        "phone":"614-787-4966",
        "plan":"5",
        "social":{
          "facebook":"",
          "foursquare":"",
          "pintrest":"",
          "twitter":""
        },
        "st":"HI",
        "status":"1",
        "tags":null,
        "web":"http:\/\/www.illumifi.net",
        "zip":"96815"
      },
      {
        "address":"",
        "categories":null,
        "city":"",
        "contact":null,
        "content":"",
        "email":"adam@shoppingmademobile.com",
        "hours":{
          "fri":{
            "open":"",
            "close":""
          },
          "mon":{
            "open":"",
            "close":""
          },
          "sat":{
            "open":"",
            "close":""
          },
          "sun":{
            "open":"",
            "close":""
          },
          "thr":{
            "open":"",
            "close":""
          },
          "tue":{
            "open":"",
            "close":""
          },
          "wed":{
            "open":"",
            "close":""
          }
        },
        "id":"5",
        "logo":"",
        "mall_id":"0",
        "max_users":"0",
        "name":"Shopping Made Mobile",
        "newsletter":"",
        "phone":"",
        "plan":"5",
        "social":{
          "facebook":"",
          "foursquare":"",
          "pintrest":"",
          "twitter":""
        },
        "st":"",
        "status":"1",
        "tags":null,
        "web":"",
        "zip":""
      }
    ]
  },
  "meta":{
    "error":null,
    "pagination":{
      "limit":20,
      "offset":{
        "current":0,
        "next":null,
        "prev":null
      },
      "page":{
        "count":1,
        "current":1,
        "next":null,
        "prev":null
      },
      "records":4
    },
    "status":1
  }
}</pre>
</div>