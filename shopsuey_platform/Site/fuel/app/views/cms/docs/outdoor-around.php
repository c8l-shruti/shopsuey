<div id="doc-content" class="mt20">
	<h1><a id="suggestions"></a>Outdoor Around</h1>
	<p>Return a list of malls, merchants and flags around a point within a radius</p>

	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td width="100">Endpoint</td>
				<td><?=Config::get('base_url')?>api/outdoor/around</td>
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
				<td>latitude (required)</td>
				<td>Latitude of the origin point</td>
			</tr>
			<tr>
				<td>longitude (required)</td>
				<td>Longitude of the origin point</td>
			</tr>
			<tr>
				<td>radius (required)</td>
				<td>Radius of the area around origin point to search for</td>
			</tr>
			<tr>
			  <td width="150">format</td>
			  <td>json | xml</td>
			</tr>
		</tbody>
	</table>

	<h4>Example</h4>
	<pre class="code"><?=Config::get('base_url')?>api/outdoor/around?latitude=21.191148&longitude=-157.743498&radius=20</pre>

	<h4>Output</h4>
	<pre class="code">
{
  "data" : {
    "merchants" : [ {
      "id" : "9",
      "name" : "Forever 21",
      "latitude" : "21.2785180000",
      "longitude" : "-157.8289840000",
      "description" : "",
      "is_customer" : true,
      "mall_id" : "3",
      "mall_name" : "Royal Hawaiian Center"
    }, {
      "id" : "909",
      "name" : "fishcake",
      "latitude" : "21.2966650000",
      "longitude" : "-157.8570180000",
      "description" : "",
      "is_customer" : true,
      "mall_id" : null,
      "mall_name" : null
    }, {
      "id" : "911",
      "name" : "Kahala Associates",
      "latitude" : "21.2769280000",
      "longitude" : "-157.7861170000",
      "description" : "",
      "is_customer" : true,
      "mall_id" : "15",
      "mall_name" : "Kahala Mall"
    }, {
      "id" : "1092",
      "name" : "Jeans Warehouse",
      "latitude" : "21.2911480000",
      "longitude" : "-157.8434980000",
      "description" : "",
      "is_customer" : true,
      "mall_id" : "5",
      "mall_name" : "Ala Moana Center"
    }, {
      "id" : "1093",
      "name" : "ABC Stores",
      "latitude" : "21.2911480000",
      "longitude" : "-157.8434980000",
      "description" : "",
      "is_customer" : true,
      "mall_id" : "5",
      "mall_name" : "Ala Moana Center"
    }, {
      "id" : "1095",
      "name" : "Trade Secret",
      "latitude" : "21.2911480000",
      "longitude" : "-157.8434980000",
      "description" : "",
      "is_customer" : true,
      "mall_id" : "5",
      "mall_name" : "Ala Moana Center"
    }, {
      "id" : "1097",
      "name" : "Goma Tei Ramen",
      "latitude" : "21.2911480000",
      "longitude" : "-157.8434980000",
      "description" : "",
      "is_customer" : true,
      "mall_id" : "5",
      "mall_name" : "Ala Moana Center"
    }, {
      "id" : "1098",
      "name" : "Haagen Dazs",
      "latitude" : "21.2911480000",
      "longitude" : "-157.8434980000",
      "description" : "",
      "is_customer" : true,
      "mall_id" : "5",
      "mall_name" : "Ala Moana Center"
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
      "id" : "22858",
      "name" : "Pool Bar",
      "latitude" : "21.2852360000",
      "longitude" : "-157.8389670000",
      "description" : "",
      "is_customer" : true,
      "mall_id" : "6134",
      "mall_name" : "The MODERN Honolulu"
    } ],
    "malls" : [ {
      "id" : "3",
      "name" : "Royal Hawaiian Center",
      "latitude" : "21.2785180000",
      "longitude" : "-157.8289840000",
      "description" : "Royal Hawaiian Center offers 310,000 square feet of delight for Hawaii shoppers. With its more than 110 shops and restaurants, the Center is one of Hawaii’s largest shopping malls. Along the four-tiered three buildings, you can purchase everything from fine designer apparel to fun-in-the-sun apparel, from fine jewelry to costume jewelry and Hawaiian treasures, from fine dining in many restaurants to dining on hot dogs and ice cream. There are boutiques, sporting-good stores, Hawaii’s top surf shops, jewelry stores, craft shops and practically everything else conceivable — all in the very center of Waikiki.All profits from Royal Hawaiian Center go to the education of Hawaiian students. The Center is owned by Kamehameha Schools.",
      "is_customer" : true
    }, {
      "id" : "5",
      "name" : "Ala Moana Center",
      "latitude" : "21.2911480000",
      "longitude" : "-157.8434980000",
      "description" : null,
      "is_customer" : true
    }, {
      "id" : "14",
      "name" : "Hawaii Kai Shopping Center",
      "latitude" : "21.2849810000",
      "longitude" : "-157.7075850000",
      "description" : "",
      "is_customer" : true
    }, {
      "id" : "15",
      "name" : "Kahala Mall",
      "latitude" : "21.2769280000",
      "longitude" : "-157.7861170000",
      "description" : null,
      "is_customer" : true
    }, {
      "id" : "1619",
      "name" : "Manoa Innovation Center",
      "latitude" : "21.3088160000",
      "longitude" : "-157.8080840000",
      "description" : "Located near the main research campus of the University of Hawaii in Manoa Valley, the Manoa Innovation Center (MIC) brings together the best of Hawaii's intellectual and physical resources. MIC's primary role is to serve as an incubator for new and early-stage technology companies. Tenants enjoy advanced 100Mb/s symmetric internet connectivity, state-of-the-art facilities and shared support services. MIC began its 20th year of operations in 2012, accelerating the growth of technology companies by providing business development services, synergistic and strategic partnerships, networking activities and professional marketing opportunities.",
      "is_customer" : true
    }, {
      "id" : "2232",
      "name" : "Waikiki Beach Walk",
      "latitude" : "21.2797443000",
      "longitude" : "-157.8306969000",
      "description" : "Waikiki Beach Walk®, to date the largest development in Waikiki's history, has become the gathering place of modern Waikiki for visitors and residents to shop, dine, play and stay. Spanning nearly eight acres in the heart of Waikiki, this exciting entertainment district includes a vibrant showcase of more than 40 world-class retailers in an open-air, two-level center, an array of delicious restaurants and casual eateries, an outdoor entertainment plaza featuring live performances regularly, plus four accommodation choices. Waikiki Beach Walk is the consummate expression of Hawaii today - it is a place where people come to be enriched by warm hospitality, a colorful mix of cultures, and treasures from the land and sea. For information, visit",
      "is_customer" : true
    }, {
      "id" : "3119",
      "name" : "Manoa Marketplace",
      "latitude" : "21.3085470000",
      "longitude" : "-157.8102400000",
      "description" : "We invite you to come and enjoy a day of shopping and the lovely natural environment of the valley. <br><br>We offer a wide array of retail to serve your daily needs and for special occasions.<br>You will find grocery stores, pharmacies, banks, dry cleaning, work out facilities, and gift shops<br>as well as medical and dental offices and financial services.<br>We also boast a wide array of restaurants and food establishments, from quick service to sit-down.",
      "is_customer" : true
    }, {
      "id" : "3801",
      "name" : "Aina Haina Shopping Center",
      "latitude" : "21.2787520000",
      "longitude" : "-157.7540860000",
      "description" : "Conveniently located in the heart of East Oahu's Aina Haina community, at the intersection of Kalanianaole Highway and West Hind Drive, the recently renovated Aina Haina Shopping Center offers a variety of services, shopping and dining experiences. Aina Haina Shopping Center is now the gathering place for customers to come early or stay late to shop or dine in a relaxing and comfortable atmosphere. Anchored by the new Foodland Farms grocery concept, new restaurants and food court along with longstanding favorite tenants, this neighborhood shopping center once again enjoys its well-deserved prominence.",
      "is_customer" : true
    }, {
      "id" : "3802",
      "name" : "Ward Centers",
      "latitude" : "21.2928730000",
      "longitude" : "-157.8527640000",
      "description" : "Ward Centers offers an exciting collection of charming one-of-a-kind shops and fashion-forward boutiques. For bargain hunters, there’s a treasure trove of national stores for endless shopping. Check out Honolulu’s favorite farmers market for an array of fresh island food and produce.",
      "is_customer" : true
    }, {
      "id" : "4646",
      "name" : "HFM",
      "latitude" : "21.2945730000",
      "longitude" : "-157.8558970000",
      "description" : "",
      "is_customer" : true
    }, {
      "id" : "5420",
      "name" : "Chinatown",
      "latitude" : "21.3118550000",
      "longitude" : "-157.8636320000",
      "description" : "",
      "is_customer" : true
    }, {
      "id" : "5665",
      "name" : "Trump International Hotel",
      "latitude" : "21.2792640000",
      "longitude" : "-157.8321790000",
      "description" : "OWN WAIKIKI. Why save the best for last when you can have it all the time? And why stay anywhere else in Hawaii when you can stay at Trump International Hotel™ Waikiki Beach Walk®? This Hawaii luxury hotel is part of the Waikiki Beach Walk development offering vibrant entertainment, exclusive boutiques and world-class restaurants, making it one of the premier Honolulu hotels. Add the simple fact that Waikiki's famous white sand beaches are just steps away, it becomes the perfect respite for your next Hawaiian getaway.Why save the best for last when you can have it all the time?A two-level, open-air lobby welcomes guests with ocean view and interiors reflecting the island's rich history. Thirty-eight stories high, the spectacular views continue from inside each of the hotel's 462 luxury guest rooms and suites. The emerald waters of the Pacific Ocean stretching out beyond Fort DeRussy Park, Diamond Head, the Honolulu skyline - each landmark dominated by the backdrop of the magnificent Ko`olau Mountains.Trump Waikiki hotel offers all of the exceptional amenities and superior services of the Trump Hotel Collection to help guests live the life Hawaiian style. A 6th-floor infinity pool with an expansive lanai deck, world-class indoor and al fresco dining, The Spa at Trump® and signature services of Trump Attaché - only the best will do at our Honolulu luxury hotel. This is paradise, after all.",
      "is_customer" : true
    }, {
      "id" : "5668",
      "name" : "Bernice Pauahi Bishop Museum",
      "latitude" : "21.3331680000",
      "longitude" : "-157.8693340000",
      "description" : "Bishop Museum is the premier place to experience the history, arts and culture of the Hawaiian people. We are recognized throughout the world for our scientific research, educational programs, and extensive collections which give voice to the stories of Hawai‘i and the broader Pacific. ",
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