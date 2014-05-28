<div id="doc-content" class="mt20">
	<h1><a id="suggestions"></a>Indoor points</h1>
	<p>Return a list of indoor merchants and flags from a location</p>

	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td width="100">Endpoint</td>
				<td><?=Config::get('base_url')?>api/indoor/:id/points</td>
			</tr>
			<tr>
				<td>Methods</td>
				<td><a href="#suggestions-get">GET</a></td>
			</tr>
		</tbody>
	</table>

	<h3><a id="suggestions-get"></a>GET</h3>
	<br>
	<h4>Parameters</h4>
	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td>id (required)</td>
				<td>Id of the location</td>
			</tr>
			<tr>
			  <td width="150">format</td>
			  <td>json | xml</td>
			</tr>
		</tbody>
	</table>

	<h4>Example</h4>
	<pre class="code"><?=Config::get('base_url')?>api/indoor/5/points</pre>

	<h4>Output</h4>
	<pre class="code">
{
  "data" : {
    "merchants" : [ {
      "id" : "1092",
      "name" : "Jeans Warehouse",
      "description" : "",
      "geometry_id" : "5836880",
      "favorite" : false,
      "offers_count" : 0,
      "events_count" : 0
    }, {
      "id" : "1122",
      "name" : "McDonald's",
      "description" : "",
      "geometry_id" : "89485",
      "favorite" : false,
      "offers_count" : 1,
      "events_count" : 0
    }, {
      "id" : "1127",
      "name" : "Hilo Hattie",
      "description" : "",
      "geometry_id" : "3987889",
      "favorite" : false,
      "offers_count" : 2,
      "events_count" : 0
    }, {
      "id" : "1156",
      "name" : "Boardwalk Treats",
      "description" : "",
      "geometry_id" : "89528",
      "favorite" : false,
      "offers_count" : 0,
      "events_count" : 0
    }, {
      "id" : "1157",
      "name" : "GameStop",
      "description" : "GameStop has a huge selection of new and used games at fantastic prices.  Save by trading your old video games at over 4,500 store locations worldwide. Shop online at GameStop.com for popular PS 3, PlayStation 3, playstation3, PS3, PS 2, PS2, PlayStation 2, playstation2, PSP, Nintendo Wii, Wii, Nintendo DS, DS, X Box, Xbox360, Xbox 360, Game Cube, GameCube and PC Games.",
      "geometry_id" : "3987798",
      "favorite" : false,
      "offers_count" : 1,
      "events_count" : 0
    }, {
      "id" : "1159",
      "name" : "Valerie Joseph",
      "description" : "",
      "geometry_id" : "89548",
      "favorite" : false,
      "offers_count" : 1,
      "events_count" : 1
    }, {
      "id" : "6177",
      "name" : "Flag-J",
      "description" : "",
      "geometry_id" : "5836882",
      "favorite" : true,
      "offers_count" : 0,
      "events_count" : 0
    } ],
    "flags" : [ {
      "id" : "1",
      "title" : "Flag name",
      "description" : "Flag desc (Has \"ala\" on it)",
      "type" : "POI",
      "floor" : "1",
      "latitude" : "21.2811480000",
      "longitude" : "-157.8534980000",
      "owner" : "2182"
    } ]
  },
  "meta" : {
    "error" : null,
    "status" : 1
  }
}
	</pre>
</div>