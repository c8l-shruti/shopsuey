<div id="doc-content" class="mt20">
    <h1>Error Codes</h1>
    <p>When sending requests to the API, under some circumstances errors might occur. There is a special format to identify and handle this conditions for the consumers of the API</p>
    <br>
    <h4>Error Output</h4>
<pre class="code">{
  "data":null,
  "meta":{
    "error_code":"1006",
    "error": "You do not have access to this method",
    "status":0
  }
}</pre>
    <h4>List of codes</h4>
<p>This is the list of possible error codes that the API can return under abnormal conditions, with their meaning:</p><br>
    <ul>
    	<li>Auth and users
    	    <ul>
                <li>1001: Unable to create access_key</li>
                <li>1002: Invalid procedure</li>
                <li>1003: Invalid email or password</li>
                <li>1004: Email and password are required parameters</li>
                <li>1004: Invalid app_id</li>
                <li>1004: Failed autologin</li>
                <li>1006: Access Denied</li>
                <li>1007: The udid parameter is required</li>
                <li>1008: The email parameter is required</li>
                <li>1009: Invalid user_id</li>
                <li>1010: The access_key parameter is required</li>
                <li>1011: Username already in use</li>
                <li>1012: Email address already in use</li>
                <li>1013: Invalid email address</li>
                <li>1014: Password must be at least 8 characters long, contain at least 1 capital letter, and contain at least 1 number. Example: P4ssword</li>
                <li>1015: Unable to save user information</li>
                <li>1016: A required setting parameter is missing</li>
                <li>1017: The fbuid parameter is required</li>
                <li>1018: The group for the user is invalid</li>
                <li>1019: The company is incompatible with the group</li>
                <li>1020: Unable to delete user</li>
                <li>1021: The password reset could not be generated</li>
                <li>1022: The password reset is invalid</li>
                <li>1023: An error occured while uploading the image</li>
                <li>1024: An error occured while deleting the image</li>
                <li>1025: The user does not have an image</li>
                <li>1026: The password could not be changed</li>
                <li>1027: Invalid current password</li>
                <li>1028: Cannot find profiling choices</li>
                <li>1029: Cannot find user profiling</li>
                <li>1030: An error has ocurred updating user profiling</li>
                <li>1031: Invalid login hash</li>
                <li>1032: Access token is required</li>
                <li>1033: Invalid access token</li>
                <li>1034: The Facebook API is unavailable</li>
                <li>1035: SSL connection required</li>
                <li>1036: Invalid APN token</li>
                <li>1037: Error updating APN token</li>
                <li>1038: User attempting to follow does not exist</li>
                <li>1039: Already following specified user</li>
                <li>1040: Not following specified user</li>
                <li>1041: There was an error persisting the user follow</li>
                <li>1042: There was an error persisting the user unfollow</li>                
            </ul>
        </li>
    	<li>Offers
    	    <ul>
    	        <li>2000: Invalid offer_id</li>
                <li>2001: The offer does not have any available codes</li>
                <li>2002: The offer has been already redeemed the allowed number of times</li>
                <li>2003: Could not create a new code for the offer</li>
                <li>2004: Could not create redeem info for the offer</li>
                <li>2005: Invalid redeem_id</li>
                <li>2006: The offer you are trying to redeem has expired, is not active or not redeemable</li>
                <li>2007: Can not determine the default type of offer code to create for the offer</li>
                <li>2008: The user does not have any favorites</li>
                <li>2009: The location is required to search for nearby locations, either as param or stored as a setting</li>
                <li>2010: There are no nearby malls or retailers</li>
                <li>2011: No location selected for offer</li>
                <li>2012: redeemed_only or saved_only should be specified in order to return offers</li>
                <li>2014: Error saving offer</li>
            </ul>
        </li>
        <li>Merchants
    	    <ul>
                <li>3000: Invalid merchant id</li>
                <li>3001: A merchant inside a market place requires the Micello info to be set</li>
                <li>3002: The micello id already exists</li>
                <li>3003: The micello geometry id already exists</li>
            </ul>
        </li>
        <li>Malls
    	    <ul>
                <li>4000: Invalid Mall id</li>
                <li>4001: The micello id already exists</li>
            </ul>
        </li>
        <li>Offer codes
    	    <ul>
                <li>5000: Invalid offer code id</li>
                <li>5001: The offer code is not valid for the selected type</li>
                <li>5002: The default offer code type is required for the auto generation of codes</li>
            </ul>
        </li>
        <li>Locations
    	    <ul>
                <li>6000: Invalid location id</li>
                <li>6001: The time lapse for blocking the location is invalid</li>
                <li>6002: An error occurred while blocking the location for the current user</li>
                <li>6003: The location is not blocked for the current user</li>
                <li>6004: An error occurred while unblocking the location for the current user</li>
                <li>6005: At least one filter must be set for the search to proceed</li>
                <li>6006: The location does not have any Micello's info loaded</li>
                <li>6007: The location has the incorrect Micello's type</li>
                <li>6008: An error occurred while querying info from Micello's API</li>
                <li>6009: An error occurred while saving Micello's info</li>
                <li>6010: Latitude and longitude are required to order by distance</li>
                <li>6011: The latitude and longitude coordinates are required for the location</li>
                <li>6012: Error saving location request</li>
                <li>6013: A location request for the current user already exists</li>
                <li>6014: The user has no favorite locations</li>
                <li>6015: Error updating favorite locations</li>
                <li>6016: The phone format is incorrect</li>
                <li>6017: The web url format is incorrect</li>
                <li>6018: The email address format is incorrect</li>
                <li>6019: Error on request to Foursquare</li>
                <li>6020: Error on request to Yelp</li>
                <li>6021: The communities param format is incorrect</li>
                <li>6022: Invalid Facebook Page URL</li>
                <li>6023: Instagram is not configured for the location</li>
                <li>6024: An error occurred while accessing the instagram feed</li>
                
            </ul>
        </li>
        <li>Events
    	    <ul>
                <li>7000: Invalid event id</li>
                <li>7001: User's location could not be determined</li>
                <li>7002: No nearby event locations could be found</li>
                <li>7003: The user does not have any favorites</li>
                <li>7004: The user hasn't RSPV'd any events</li>
                <li>7005: Error saving event</li>
            </ul>
        </li>
        <li>Proximity messaging
    	    <ul>
                <li>8000: No entity could be found with that id</li>
                <li>8001: Invalid entity type. It should be event or offer.</li>
                <li>8002: Like status should only take the values -1, 0 or 1 (dislike, no opinion and like respectively)</li>
            </ul>
        </li>
        <li>Newsletter subscriptions
    	    <ul>
                <li>9000: Invalid location id</li>
                <li>9001: This user has already subscribed to the newsletter</li>
            </ul>
        </li>
        <li>Request Social Networks
    	    <ul>
                <li>10000: Invalid location id</li>
                <li>10001: Social network already requested for location</li>
            </ul>
        </li>
        <li>Sessions time tracking
    	    <ul>
                <li>10100: Error saving session start time</li>
                <li>10101: Tried to end a not started session</li>
                <li>10102: Tried to end an already ended session</li>
                <li>10103: Error saving session end time</li>
            </ul>
        </li>
        <li>Flags
    	    <ul>
                <li>12000: Invalid title parameter</li>
                <li>12001: Invalid flag type (it must be Aloha, POI, Community or Attraction)</li>
                <li>12002: Invalid flag location</li>
                <li>12003: An error occurred while saving Flag</li>
                <li>12004: Invalid private parameter</li>
                <li>12005: Latitude, Longitude and Radius parameters are required when nearby is true</li>
                <li>12006: Parameter flag_id is required for this service</li>
                <li>12007: Invalid flag id</li>
                <li>12008: The user doesn't have permission to see this flag</li>
                <li>12009: Parameter status is required for this services. It could be 1 or -1</li>
                <li>12010: Parameter must be 1 or -1</li>
                <li>12011: Current user is not the owner of the flag</li>
                <li>12012: A public flag doesn't support invitations</li>
                <li>12013: Unable to delete flag</li>
                <li>12014: Error uploading image</li>
                <li>12015: Specified coordinates are not currently supported by ShopSuey</li>
            </ul>
        </li>
        <li>Promo Codes
    	    <ul>
                <li>13000: Invalid promo code</li>
                <li>13001: Parameter code is required for this service</li>
                <li>13002: Invalid promo code</li>
            </ul>
        </li>
        <li>Social Interactions
    	    <ul>
                <li>14000: Invalid location id</li>
                <li>14001: User had already liked this location</li>
                <li>14002: Error saving social interaction</li>
            </ul>
        </li>
    </ul>
	<br>
</div>