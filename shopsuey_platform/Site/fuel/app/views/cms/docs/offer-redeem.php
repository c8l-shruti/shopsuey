<div id="doc-content" class="mt20">
	<h1><a id="offer-redeem"></a>Offer - Redeem</h1>

	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
				<td width="100">Endpoints</td>
				<td><?=Config::get('base_url')?>api/offer/:offer_id/redeem/ | <?=Config::get('base_url')?>api/offer/redeem/:redeem_id</td>
			</tr>
			<tr>
				<td>Methods</td>
				<td><a href="#offer-redeem-post">POST</a> | <a href="#offer-redeem-get">GET</a></td>
			</tr>
		</tbody>
	</table>

	<h3><a id="offer-redeem-post"></a>POST</h3>
	<br>
	<h4>Parameters</h4>
	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
			  <td width="100">offer_id</td>
			  <td>The offer to be redeemed</td>
			</tr>
			<tr>
			  <td width="100">format</td>
			  <td>json | xml</td>
			</tr>
		</tbody>
	</table>

	<h4>Example</h4>
	<pre class="code"><?=Config::get('base_url')?>api/offer/1/redeem/</pre>

	<h4>Output</h4>
	<pre class="code">{
  "meta":{
    "error":"",
    "status":1
  },
  "data":{
    "redeem" : {
      "id" : 4,
      "offer_id" : "1",
      "code" : "Unique Code",
      "type" : "code_128",
      "times_used" : 1
    }
  }
}</pre>

	<h3><a id="offer-redeem-get"></a>GET</h3>
	<br>
	<h4>Parameters</h4>
	<table class="grid" cellspacing="2" cellpadding="5" border="1">
		<tbody>
			<tr>
			  <td width="100">redeem_id</td>
			  <td>The id of the redeem</td>
			</tr>
			<tr>
			  <td width="100">format</td>
			  <td>json | xml</td>
			</tr>
		</tbody>
	</table>

	<h4>Example</h4>
	<pre class="code"><?=Config::get('base_url')?>api/offer/redeem/4</pre>

	<h4>Output</h4>
	<pre class="code">{
  "meta":{
    "error":"",
    "status":1
  },
  "data":{
    "redeem" : {
      "id" : 4,
      "offer_id" : "1",
      "code" : "Unique Code",
      "type" : "code_128",
      "times_used" : 1
    }
  }
}</pre>

</div>