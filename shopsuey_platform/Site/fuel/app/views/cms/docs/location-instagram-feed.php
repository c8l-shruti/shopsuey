<div id="doc-content" class="mt20">
	<h1><a id="locations"></a>Location's Instagram feed</h1>
	<p>Returns the instagram feed configured for a location.</p>
	<p>The structure of the feed is the same described on <a target="_blank" href="http://instagram.com/developer/endpoints/users#get_users_feed">Instagram's API "/users/self/feed" endpoint</a></p>

	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td width="100">Endpoint</td>
				<td><?=Config::get('base_url')?>api/location/:id/instagram_feed</td>
			</tr>
			<tr>
				<td>Methods</td>
				<td><a href="#locations-get">GET</a></td>
			</tr>
		</tbody>
	</table>

	<h3><a id="locations-get"></a>GET</h3>
	<br>
	<h4>Parameters</h4>
	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td>id</td>
				<td>Id of the location</td>
			</tr>
			<tr>
			  <td width="150">format</td>
			  <td>json | xml</td>
			</tr>
		</tbody>
	</table>

	<h4>Example</h4>
	<pre class="code"><?=Config::get('base_url')?>api/location/1/instagram_feed</pre>

	<h4>Output</h4>
	<pre class="code">{
  "data":{
    "feed" : [ {
      "attribution" : null,
      "tags" : [ ],
      "type" : "image",
      "location" : null,
      "comments" : {
        "count" : 0,
        "data" : [ ]
      },
      "filter" : "X-Pro II",
      "created_time" : "1378501921",
      "link" : "http://instagram.com/p/d7v_m_GEUA/",
      "likes" : {
        "count" : 0,
        "data" : [ ]
      },
      "images" : {
        "low_resolution" : {
          "url" : "http://distilleryimage8.s3.amazonaws.com/f91a080a173811e39f8422000ae90872_6.jpg",
          "width" : 306,
          "height" : 306
        },
        "thumbnail" : {
          "url" : "http://distilleryimage8.s3.amazonaws.com/f91a080a173811e39f8422000ae90872_5.jpg",
          "width" : 150,
          "height" : 150
        },
        "standard_resolution" : {
          "url" : "http://distilleryimage8.s3.amazonaws.com/f91a080a173811e39f8422000ae90872_7.jpg",
          "width" : 612,
          "height" : 612
        }
      },
      "users_in_photo" : [ ],
      "caption" : {
        "created_time" : "1378501949",
        "text" : "Api test",
        "from" : {
          "username" : "lucasmacosta",
          "profile_picture" : "http://images.ak.instagram.com/profiles/profile_543146552_75sq_1378501813.jpg",
          "id" : "543146552",
          "full_name" : "Lucas Acosta"
        },
        "id" : "539235894966109611"
      },
      "user_has_liked" : false,
      "id" : "539235659774706944_543146552",
      "user" : {
        "username" : "lucasmacosta",
        "website" : "",
        "profile_picture" : "http://images.ak.instagram.com/profiles/profile_543146552_75sq_1378501813.jpg",
        "full_name" : "Lucas Acosta",
        "bio" : "",
        "id" : "543146552"
      }
    } ]
  },
  "meta":{
    "error":null,
    "status":1
  }
}
	</pre>
</div>
