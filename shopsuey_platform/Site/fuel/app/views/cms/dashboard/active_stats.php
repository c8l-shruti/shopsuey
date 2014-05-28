<div class="fluid" id="activeShoppers">
    <div class="grid12" id="headerTools">
        <div class="grid5">
            <h1>Active Stats</h1>
        </div>
    </div>
    <div class="grid12">
        <div id="mapElement" class="grid10"></div>
        <div class="grid2" >
            <ul id="stats">
                <li><span id="stats_merchant"></span><br>&nbsp;<span class="stats_label" id="stats_most_active" style="display: none;">Most Active</span></li>
                <li><span id="stats_active_offers"></span><br>Active Offers</li>
                <li><span id="stats_active_events"></span><br>Active Events</li>
                <li><span id="stats_checkins"></span><br>Check-In's</li>
                <li><span id="stats_redemptions"></span><br>Redemptions</li>
                <li><span id="stats_rsvps"></span><br>Event RSPV's</li>
            </ul>
        </div>
	</div>
</div>

<script  type="text/javascript"  src="http://maps.micello.com/webmap/v0/micellomap.js"></script>

<script type="text/javascript">

var micello_stores = <?=json_encode($micello_stores)?>;
var most_active_count = <?=$most_active_count?>;
var mapControl;
var mapDataObject;
var community;

micello.maps.init("<?=$micello_api_key?>", mapInit);
function mapInit() {
    mapControl = new micello.maps.MapControl('mapElement');
    mapDataObject = mapControl.getMapData();

    mapControl.onMapClick = onMapClick;
        
    mapDataObject.loadCommunity(<?=$micello_id?>);
    
    mapDataObject.mapChanged = function(e) {
        if (e.comLoad) {
            community = mapDataObject.getCommunity();
            
            $.each(micello_stores, function(key, value) {
                var inlay = {
                    "id": key,
                    "lr": value.name,
                    "t": value.activity_count == most_active_count ? "Selected" : "Search Result",
                    "anm":"shopsuey_inlays"
                };
                mapDataObject.addInlay(inlay);
        	});
        }
    };
}

function onMapClick (mx, my, clicked) {
    if (clicked) { // check that a clicked object is present
        // Determine which if must be used for matching (geometry_id or group_id)
        var geom_id = clicked.gid ? clicked.gid : clicked.id;
        // Check if the clicked object is on shopsuey's db
        if (micello_stores[geom_id]) {
            updateStats(micello_stores[geom_id]);
        }
        mapControl.defaultSelectAction(clicked);
    }
}

function updateStats(store) {
	var stats = store.metrics;
	$("#stats_merchant").html(store.short_name);
	if (store.activity_count == most_active_count) {
		$("#stats_most_active").show();
	} else {
		$("#stats_most_active").hide();
    }
	$("#stats_active_offers").html(stats.offers_count);
	$("#stats_active_events").html(stats.events_count);
	$("#stats_checkins").html(stats.check_ins_count);
	$("#stats_redemptions").html(stats.redemptions_count);
	$("#stats_rsvps").html(stats.rsvps_count);
}

</script>
