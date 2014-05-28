    <div align="center" class="sidePad mt20">
		<a class="sideB bRed" href="<?=Uri::create('developer')?>">
			<span class="icon-cog"></span>
			<span>Manage Apps</span>
		</a>
	</div>
	<div class="divider"><span></span></div>
	<div align="center" class="sidePad">

		<a class="sideB bGold" href="<?=Uri::create('developer/app')?>">
			<span class="icon-plus-2"></span>
			<span>Create an app</span>
		</a>
    </div>
    <div class="hdr noBorderT mt20">
        <span class="iconb" data-icon="&#xe015;"></span>
        <span class="text">API Documentation</span>
    </div>
	<div>
		<ul class="list">
			<li><a href="<?=Uri::create('developer/docs')?>">Introduction</a>
			<li><a href="<?=Uri::create('developer/docs/auth')?>">Auth</a></li>
			<li><a href="<?=Uri::create('developer/docs/auth-force_upgrade')?>">Force Upgrade Check</a></li>
			<li><a href="<?=Uri::create('developer/docs/error-codes')?>">Error Codes</a></li>
		</ul>
	</div>

    <div class="hdr noBorderT mt20">
        <span class="iconb" data-icon="&#xe13c;"></span>
        <span class="text">Tools</span>
    </div>
	<div>
		<ul class="list">
			<li><a href="<?=Uri::create('developer/test')?>">Web Interface</a></li>
		</ul>
	</div>
    <div class="hdr noBorderT mt20">
        <span class="iconb" data-icon="&#xe05b;"></span>
        <span class="text">Objects</span>
    </div>

	<div>
		<ul class="list">
			<li><a href="<?=Uri::create('developer/docs/users')?>">Users</a></li>
			<li><a href="<?=Uri::create('developer/docs/users-list')?>">Users List (People module)</a></li>
			<li><a href="<?=Uri::create('developer/docs/user')?>">User</a></li>
			<li><a href="<?=Uri::create('developer/docs/user-fblogin')?>">User Facebook Login</a></li>
			<li><a href="<?=Uri::create('developer/docs/user-anonymous')?>">User Anonymous Login</a></li>
			<li><a href="<?=Uri::create('developer/docs/preferences')?>">User Preferences</a></li>
			<li><a href="<?=Uri::create('developer/docs/user-reset')?>">User - Reset</a></li>
			<li><a href="<?=Uri::create('developer/docs/user-image_upload')?>">User - Image Upload</a></li>
			<li><a href="<?=Uri::create('developer/docs/user-image_delete')?>">User - Image Delete</a></li>
			<li><a href="<?=Uri::create('developer/docs/user-get_profiling_choices')?>">User - Get Profiling Choices</a></li>
			<li><a href="<?=Uri::create('developer/docs/user-get_profiling')?>">User - Get User Profiling</a></li>
			<li><a href="<?=Uri::create('developer/docs/user-update_profiling')?>">User - Update User Profiling</a></li>
			<li><a href="<?=Uri::create('developer/docs/user-set_apn_token')?>">User - Set APN Token</a></li>
			<li><a href="<?=Uri::create('developer/docs/user-follow')?>">User - Follow</a></li>
			<li><a href="<?=Uri::create('developer/docs/user-unfollow')?>">User - Unfollow</a></li>
			<li><a href="<?=Uri::create('developer/docs/user-following')?>">User - Following</a></li>
			<li><a href="<?=Uri::create('developer/docs/user-followers')?>">User - Followers</a></li>
			<li><a href="<?=Uri::create('developer/docs/user-get_votes')?>">User - Get Votes</a></li>
			<li><a href="<?=Uri::create('developer/docs/user-get_favorites')?>">User - Get Favorites</a></li>
			<li><a href="<?=Uri::create('developer/docs/user-get_details')?>">User - Get Details</a></li>
			<li><a href="<?=Uri::create('developer/docs/user-get_offers')?>">User - Get Offers</a></li>
			<li><a href="<?=Uri::create('developer/docs/user-get_events')?>">User - Get Events</a></li>
		</ul>
	</div>
	<div>
		<ul class="list" style="margin-top: -10px;">
<!--  <li><a href="<?=Uri::create('developer/docs/offers')?>">Offers</a></li>  -->
			<li><a href="<?=Uri::create('developer/docs/offer-search')?>">Offers</a></li>
			<li><a href="<?=Uri::create('developer/docs/offer-count')?>">Offers - Count</a></li>
			<li><a href="<?=Uri::create('developer/docs/offer')?>">Offer</a></li>
			<li><a href="<?=Uri::create('developer/docs/offer-bookmarks')?>">Offer - Bookmarks</a></li>
			<li><a href="<?=Uri::create('developer/docs/offer-bookmark')?>">Offer - Bookmark</a></li>
			<li><a href="<?=Uri::create('developer/docs/offer-redeem')?>">Offer - Redeem</a></li>
		</ul>
	</div>
	<div>
		<ul class="list" style="margin-top: -10px;">
			<li><a href="<?=Uri::create('developer/docs/events')?>">Events List</a></li>
			<li><a href="<?=Uri::create('developer/docs/event')?>">Event Details</a></li>
			<li><a href="<?=Uri::create('developer/docs/event-rsvp')?>">Event RSVP</a></li>
			<li><a href="<?=Uri::create('developer/docs/event-count')?>">Event Count</a></li>
			<li><a href="<?=Uri::create('developer/docs/specialevent-rsvp')?>">Special Event RSVP</a></li>
			<li><a href="<?=Uri::create('developer/docs/specialevent-locations')?>">Special Event Locations</a></li>
			<li><a href="<?=Uri::create('developer/docs/specialevent-offers')?>">Special Event Offers</a></li>
			<li><a href="<?=Uri::create('developer/docs/specialevent-events')?>">Special Event Events</a></li>
		</ul>
	</div>
	<div>
		<ul class="list" style="margin-top: -10px;">
			<li><a href="<?=Uri::create('developer/docs/malls')?>">Malls</a></li>
			<li><a href="<?=Uri::create('developer/docs/mall')?>">Mall</a></li>
			<li><a href="<?=Uri::create('developer/docs/mall-merchants_count')?>">Mall - Merchants count</a></li>
		</ul>
	</div>
	<div>
		<ul class="list" style="margin-top: -10px;">
			<li><a href="<?=Uri::create('developer/docs/merchants')?>">Merchants</a></li>
			<li><a href="<?=Uri::create('developer/docs/merchant')?>">Merchant</a></li>
			</ul>
	</div>
    <div>
		<ul class="list" style="margin-top: -10px;">
			<li><a href="<?=Uri::create('developer/docs/subscription')?>">Newsletter Subscription</a></li>
			</ul>
	</div>
	<div>
		<ul class="list" style="margin-top: -10px;">
			<li><a href="<?=Uri::create('developer/docs/proximity')?>">Proximity Messages</a></li>
			<li><a href="<?=Uri::create('developer/docs/proximity-like')?>">Messages - Like/Dislike</a></li>
			<li><a href="<?=Uri::create('developer/docs/proximity-get_likes')?>">Messages - Get likes</a></li>
		</ul>
	</div>
	<div>
		<ul class="list" style="margin-top: -10px;">
			<li><a href="<?=Uri::create('developer/docs/retailers')?>">Retailers</a></li>
			<li><a href="<?=Uri::create('developer/docs/retailer')?>">Retailer</a></li>
			</ul>
	</div>
    <div>
		<ul class="list" style="margin-top: -10px;">
			<li><a href="<?=Uri::create('developer/docs/socialnetworkrequest-twitter')?>">Request Twitter</a></li>
        </ul>
	</div>
	<div>
		<ul class="list" style="margin-top: -10px;">
			<li><a href="<?=Uri::create('developer/docs/locations')?>">Locations</a></li>
			<li><a href="<?=Uri::create('developer/docs/location')?>">Location</a></li>
			<li><a href="<?=Uri::create('developer/docs/location-block')?>">Location Block/Unblock</a></li>
			<li><a href="<?=Uri::create('developer/docs/location-search')?>">Location Search</a></li>
			<li><a href="<?=Uri::create('developer/docs/location-map')?>">Location Map</a></li>
			<li><a href="<?=Uri::create('developer/docs/location-maps-validity')?>">Location Maps Validity</a></li>
			<li><a href="<?=Uri::create('developer/docs/location-points')?>">Location Points</a></li>
			<li><a href="<?=Uri::create('developer/docs/location-favorite')?>">Location Favorite</a></li>
			<li><a href="<?=Uri::create('developer/docs/location-merchants_at_mall')?>">[DEPRECATED] Location Merchants at Mall</a></li>
			<li><a href="<?=Uri::create('developer/docs/location-merchants')?>">Location Merchants</a></li>
			<li><a href="<?=Uri::create('developer/docs/location-request')?>">Location Request</a></li>
			<li><a href="<?=Uri::create('developer/docs/location-requested')?>">Location Requested</a></li>
			<li><a href="<?=Uri::create('developer/docs/location-position-supported')?>">Supported user position</a></li>
			<li><a href="<?=Uri::create('developer/docs/location-instagram-feed')?>">Location's Instagram feed</a></li>
		</ul>
	</div>
	<div>
		<ul class="list" style="margin-top: -10px;">
			<li><a href="<?=Uri::create('developer/docs/position')?>">Position</a></li>
		</ul>
	</div>
	<div>
		<ul class="list" style="margin-top: -10px;">
			<li><a href="<?=Uri::create('developer/docs/flag')?>">Flag</a></li>
			<li><a href="<?=Uri::create('developer/docs/flag-flags')?>">Flags</a></li>
			<li><a href="<?=Uri::create('developer/docs/flag-flag_vote')?>">Flag Vote</a></li>
			<li><a href="<?=Uri::create('developer/docs/flag-flag_details')?>">Flag Details</a></li>
			<li><a href="<?=Uri::create('developer/docs/flag-flag_private')?>">Flag Private</a></li>
			<li><a href="<?=Uri::create('developer/docs/flag-flag_invite')?>">Flag Invite</a></li>
			<li><a href="<?=Uri::create('developer/docs/flag-flag_uninvite')?>">Flag Uninvite</a></li>
			<li><a href="<?=Uri::create('developer/docs/flag-flag_delete')?>">Flag Delete</a></li>
		</ul>
	</div>
	<div>
		<ul class="list" style="margin-top: -10px;">
			<li><a href="<?=Uri::create('developer/docs/socialinteraction_location-like')?>">Social - Location Like</a></li>
			<li><a href="<?=Uri::create('developer/docs/socialinteraction_location-dislike')?>">Social - Location Dislike</a></li>
			<li><a href="<?=Uri::create('developer/docs/socialinteraction_location-checkin')?>">Social - Location Checkin</a></li>
		</ul>
	</div>
	<div>
		<ul class="list" style="margin-top: -10px;">
			<li><a href="<?=Uri::create('developer/docs/outdoor-suggestions')?>">Outdoor suggestions</a></li>
			<li><a href="<?=Uri::create('developer/docs/outdoor-around')?>">Outdoor locations/flags around a point</a></li>
		</ul>
	</div>
	<div>
		<ul class="list" style="margin-top: -10px;">
			<li><a href="<?=Uri::create('developer/docs/indoor-points')?>">Indoor points</a></li>
		</ul>
	</div>
	