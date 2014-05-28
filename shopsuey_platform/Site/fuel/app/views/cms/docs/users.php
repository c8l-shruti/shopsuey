    <div id="doc-content" class="mt20">
        <h1><a id="users"></a>Users</h1>
		<p>Outputs a list of users <small>(20 per request)</small></p>
        <table class="grid" cellspacing="2" cellpadding="5" border="1">
            <tbody>
                <tr>
                    <td width="100">Endpoint</td>
                    <td><?=Config::get('base_url')?>api/users/</td>
                </tr>
                <tr>
                    <td>Methods</td>
                    <td><a href="#users-get">GET</a></td>
                </tr>
            </tbody>
        </table>

        <h3><a id="users-get"></a>GET</h3>
        <br>
        <h4>Parameters</h4>
        <table class="grid" cellspacing="2" cellpadding="5" border="1">
            <tbody>
                <tr>
                    <td>email</td>
                    <td>Email address to search for</td>
                </tr>
                <tr>
                    <td>page</td>
                    <td>INTEGER value referencing results page to display</td>
                </tr>
                <tr>
                  <td>format</td>
                  <td>json | xml</td>
                </tr>
            </tbody>
        </table>

        <h4>Example</h4>
        <pre class="code"><?=Config::get('base_url')?>api/users/?page=1&access_key=5007d09b45929</pre>

        <h4>Output</h4>
        <pre class="code">{
   "data":{
      "users":[
         {
            "created_at":"1346227067",
            "email":"adam@collins.com",
            "first_name":"Adam",
            "group":{
               "name":"Guest",
               "roles":[

               ],
               "level":"0"
            },
            "id":"85",
            "last_name":"Collins",
            "meta":{
               "company":"1",
               "real_name":"Adam Collins",
               "remember":null
            },
            "username":"adam@collins.com"
         },
         {
            "created_at":"1346135401",
            "email":"adam@shoppingmademobile.com",
            "first_name":"Adam",
            "group":{
               "name":"Super Admin",
               "roles":[
                  "user",
                  "manager",
                  "retailer",
                  "admin",
                  "super"
               ],
               "level":"1000"
            },
            "id":"38",
            "last_name":"Schuster",
            "meta":{
               "company":"2",
               "real_name":"Adam Schuster",
               "remember":"1"
            },
            "username":"adam@shoppingmademobile.com"
         },
         {
            "created_at":"1346224759",
            "email":"allie@blahblah.com",
            "first_name":"allie",
            "group":{
               "name":"Retailer",
               "roles":[
                  "user",
                  "manager",
                  "retailer"
               ],
               "level":"50"
            },
            "id":"55",
            "last_name":"tullos",
            "meta":{
               "company":"1",
               "real_name":"allie tullos",
               "remember":null
            },
            "username":"allie@blahblah.com"
         },
         {
            "created_at":"1346227585",
            "email":"amy@jonesdrew.com",
            "first_name":"Amy",
            "group":{
               "name":"Retailer",
               "roles":[
                  "user",
                  "manager",
                  "retailer"
               ],
               "level":"50"
            },
            "id":"98",
            "last_name":"Jones",
            "meta":{
               "company":"1",
               "real_name":"Amy Jones",
               "remember":null
            },
            "username":"amy@jonesdrew.com"
         },
         {
            "created_at":"1346226519",
            "email":"amy@willias.com",
            "first_name":"Amy",
            "group":{
               "name":"Retailer",
               "roles":[
                  "user",
                  "manager",
                  "retailer"
               ],
               "level":"50"
            },
            "id":"68",
            "last_name":"Williams",
            "meta":{
               "company":"1",
               "real_name":"Amy Williams",
               "remember":null
            },
            "username":"amy@willias.com"
         }
      ]
   },
   "meta":{
      "error":null,
      "pagination":{
         "limit":5,
         "offset":{
            "current":0,
            "next":5,
            "prev":null
         },
         "page":{
            "count":14,
            "current":1,
            "next":2,
            "prev":null
         },
         "records":69
      },
      "status":1
   }
}
</pre>
	</div>