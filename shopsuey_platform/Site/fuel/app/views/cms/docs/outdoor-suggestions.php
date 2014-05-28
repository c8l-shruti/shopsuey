<div id="doc-content" class="mt20">
	<h1><a id="suggestions"></a>Outdoor Suggestions</h1>
	<p>Return a list of suggested malls, merchants and flags to be shown on a map</p>

	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td width="100">Endpoint</td>
				<td><?=Config::get('base_url')?>api/outdoor/suggestions</td>
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
				<td>latitude (optional*)</td>
				<td>Latitude of the current location (used to sort by distance)</td>
			</tr>
			<tr>
				<td>longitude (optional*)</td>
				<td>Longitude of the current location (used to sort by distance)</td>
			</tr>
			<tr>
				<td>radius (optional)</td>
				<td>Radius of the area around current location to search for (used to order by distance).
				Only applicable when the <strong>latitude</strong> and <strong>longitude</strong> parameters are passed.
				When omitted or set to 0 no distance restriction will take place for searching.
				</td>
			</tr>
			<tr>
				<td>keyword (optional)</td>
				<td>String to search on the suggestions data. Fields are <strong>name</strong>, <strong>description</strong> and <strong>tags</strong> for merchants/malls and <strong>title</strong> and <strong>description</strong> for flags</td>
			</tr>
			<tr>
			<tr>
				<td>order_by (optional)</td>
				<td>Currently only &quot;<em>distance</em>&quot; is supported</td>
			</tr>
            <tr>
				<td>limit (optional)</td>
				<td>Number of items of each type to return, defaults to 25 elements</td>
			</tr>
			<tr>
			  <td width="150">format</td>
			  <td>json | xml</td>
			</tr>
			<tr>
			  <td colspan="2"><strong>* note:</strong> Required when the <strong>order_by</strong> param is set to &quot;<em>distance</em>&quot;</td>
			</tr>
		</tbody>
	</table>

	<h4>Example</h4>
	<pre class="code"><?=Config::get('base_url')?>api/outdoor/suggestions?latitude=21.191148&longitude=-157.743498&radius=20&keyword=ala</pre>

	<h4>Output</h4>
	<pre class="code">
{
  "data" : {
    "merchants" : [ {
      "id" : "911",
      "name" : "Kahala Associates",
      "latitude" : "21.2769280000",
      "longitude" : "-157.7861170000",
      "description" : "",
      "is_customer" : true,
      "mall_id" : "15",
      "mall_name" : "Kahala Mall"
    }, {
      "id" : "1377",
      "name" : "KAHALA OFFICE TOWER",
      "latitude" : "21.2769280000",
      "longitude" : "-157.7861170000",
      "description" : null,
      "is_customer" : true,
      "mall_id" : "15",
      "mall_name" : "Kahala Mall"
    }, {
      "id" : "1379",
      "name" : "KAHALA OFFICE BUILDING",
      "latitude" : "21.2769280000",
      "longitude" : "-157.7861170000",
      "description" : null,
      "is_customer" : true,
      "mall_id" : "15",
      "mall_name" : "Kahala Mall"
    }, {
      "id" : "1399",
      "name" : "Kahala Mall Management",
      "latitude" : "21.2769280000",
      "longitude" : "-157.7861170000",
      "description" : "",
      "is_customer" : true,
      "mall_id" : "15",
      "mall_name" : "Kahala Mall"
    }, {
      "id" : "1403",
      "name" : "Aloha Salads",
      "latitude" : "21.2769280000",
      "longitude" : "-157.7861170000",
      "description" : "",
      "is_customer" : true,
      "mall_id" : "15",
      "mall_name" : "Kahala Mall"
    }, {
      "id" : "1427",
      "name" : "Kahala Barber",
      "latitude" : "21.2769280000",
      "longitude" : "-157.7861170000",
      "description" : "",
      "is_customer" : true,
      "mall_id" : "15",
      "mall_name" : "Kahala Mall"
    }, {
      "id" : "1437",
      "name" : "Kahala Grill",
      "latitude" : "21.2769280000",
      "longitude" : "-157.7861170000",
      "description" : "",
      "is_customer" : true,
      "mall_id" : "15",
      "mall_name" : "Kahala Mall"
    }, {
      "id" : "1439",
      "name" : "Consolidated Kahala  Theaters",
      "latitude" : "21.2769280000",
      "longitude" : "-157.7861170000",
      "description" : "",
      "is_customer" : true,
      "mall_id" : "15",
      "mall_name" : "Kahala Mall"
    }, {
      "id" : "1445",
      "name" : "Riches Kahala",
      "latitude" : "21.2769280000",
      "longitude" : "-157.7861170000",
      "description" : "",
      "is_customer" : true,
      "mall_id" : "15",
      "mall_name" : "Kahala Mall"
    }, {
      "id" : "1474",
      "name" : "Eyewear Kahala",
      "latitude" : "21.2769280000",
      "longitude" : "-157.7861170000",
      "description" : "",
      "is_customer" : true,
      "mall_id" : "15",
      "mall_name" : "Kahala Mall"
    }, {
      "id" : "1475",
      "name" : "Kahala Associates",
      "latitude" : "21.2769280000",
      "longitude" : "-157.7861170000",
      "description" : "",
      "is_customer" : true,
      "mall_id" : "15",
      "mall_name" : "Kahala Mall"
    }, {
      "id" : "1476",
      "name" : "KA HALE I ÔO KAHALA HALAU HULA",
      "latitude" : "21.2769280000",
      "longitude" : "-157.7861170000",
      "description" : "",
      "is_customer" : true,
      "mall_id" : "15",
      "mall_name" : "Kahala Mall"
    }, {
      "id" : "1634",
      "name" : "Il Lupino Trattoria",
      "latitude" : "21.2785180000",
      "longitude" : "-157.8289840000",
      "description" : "",
      "is_customer" : true,
      "mall_id" : "3",
      "mall_name" : "Royal Hawaiian Center"
    }, {
      "id" : "2278",
      "name" : "Cheeseburger Beachwalk",
      "latitude" : "21.2797443000",
      "longitude" : "-157.8306969000",
      "description" : "",
      "is_customer" : true,
      "mall_id" : "2232",
      "mall_name" : "Waikiki Beach Walk"
    }, {
      "id" : "1099",
      "name" : "Ala Moana Poi Bowl",
      "latitude" : "21.2911480000",
      "longitude" : "-157.8434980000",
      "description" : "",
      "is_customer" : true,
      "mall_id" : "5",
      "mall_name" : "Ala Moana Center"
    }, {
      "id" : "1102",
      "name" : "Ala Moana Golf Shop - Coming Soon!! ",
      "latitude" : "21.2911480000",
      "longitude" : "-157.8434980000",
      "description" : "",
      "is_customer" : true,
      "mall_id" : "5",
      "mall_name" : "Ala Moana Center"
    }, {
      "id" : "1116",
      "name" : "Ala Moana Management Office",
      "latitude" : "21.2911480000",
      "longitude" : "-157.8434980000",
      "description" : "",
      "is_customer" : true,
      "mall_id" : "5",
      "mall_name" : "Ala Moana Center"
    }, {
      "id" : "1151",
      "name" : "Dairy Queen / Orange Julius Ala Moana",
      "latitude" : "21.2911480000",
      "longitude" : "-157.8434980000",
      "description" : "",
      "is_customer" : true,
      "mall_id" : "5",
      "mall_name" : "Ala Moana Center"
    }, {
      "id" : "1165",
      "name" : "Ala Moana Golf Shop",
      "latitude" : "21.2911480000",
      "longitude" : "-157.8434980000",
      "description" : "",
      "is_customer" : true,
      "mall_id" : "5",
      "mall_name" : "Ala Moana Center"
    }, {
      "id" : "1251",
      "name" : "Kahala",
      "latitude" : "21.2911480000",
      "longitude" : "-157.8434980000",
      "description" : "",
      "is_customer" : true,
      "mall_id" : "5",
      "mall_name" : "Ala Moana Center"
    }, {
      "id" : "1354",
      "name" : "Hoala Salon & Spa",
      "latitude" : "21.2911480000",
      "longitude" : "-157.8434980000",
      "description" : "",
      "is_customer" : true,
      "mall_id" : "5",
      "mall_name" : "Ala Moana Center"
    }, {
      "id" : "6090",
      "name" : "Kalapaki Girl Dezigns",
      "latitude" : "21.2935300000",
      "longitude" : "-157.8511680000",
      "description" : "",
      "is_customer" : true,
      "mall_id" : "5703",
      "mall_name" : "OUTFIT"
    }, {
      "id" : "4228",
      "name" : "Tango Market",
      "latitude" : "21.2928730000",
      "longitude" : "-157.8527640000",
      "description" : "",
      "is_customer" : true,
      "mall_id" : "3802",
      "mall_name" : "Ward Centers"
    }, {
      "id" : "4249",
      "name" : "Paina Cafe",
      "latitude" : "21.2928730000",
      "longitude" : "-157.8527640000",
      "description" : "",
      "is_customer" : true,
      "mall_id" : "3802",
      "mall_name" : "Ward Centers"
    }, {
      "id" : "5495",
      "name" : "Golden PaLace Seafood",
      "latitude" : "21.3118550000",
      "longitude" : "-157.8636320000",
      "description" : null,
      "is_customer" : true,
      "mall_id" : "5420",
      "mall_name" : "Chinatown"
    } ],
    "malls" : [ {
      "id" : "3801",
      "name" : "Aina Haina Shopping Center",
      "latitude" : "21.2787520000",
      "longitude" : "-157.7540860000",
      "description" : "Conveniently located in the heart of East Oahu's Aina Haina community, at the intersection of Kalanianaole Highway and West Hind Drive, the recently renovated Aina Haina Shopping Center offers a variety of services, shopping and dining experiences. Aina Haina Shopping Center is now the gathering place for customers to come early or stay late to shop or dine in a relaxing and comfortable atmosphere. Anchored by the new Foodland Farms grocery concept, new restaurants and food court along with longstanding favorite tenants, this neighborhood shopping center once again enjoys its well-deserved prominence.",
      "is_customer" : true
    }, {
      "id" : "15",
      "name" : "Kahala Mall",
      "latitude" : "21.2769280000",
      "longitude" : "-157.7861170000",
      "description" : null,
      "is_customer" : true
    }, {
      "id" : "22840",
      "name" : "Ala Moana Center",
      "latitude" : "21.2912881000",
      "longitude" : "-157.8429647000",
      "description" : null,
      "is_customer" : true
    }, {
      "id" : "5",
      "name" : "Ala Moana Center",
      "latitude" : "21.2911480000",
      "longitude" : "-157.8434980000",
      "description" : null,
      "is_customer" : true
    } ],
    "flags" : [ {
      "id" : "1",
      "title" : "Flag name",
      "description" : "Flag desc (Has \"ala\" on it)",
      "type" : "POI",
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