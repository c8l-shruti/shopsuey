<div class="fluid" id="activeShoppers">
    <div class="grid12" id="headerTools">
        <div class="grid4">
            <h1>Active Shoppers</h1>
        </div>
        <div class="grid3" id="genderWrapper">
            <div class="grid12">
                <div class="grid6">
                    <input type="checkbox" name="gender_male" value="male" checked="checked" />
                    <?= Asset::img('maps/blue_dot_select.png'); ?>
                </div>
                <div class="grid6">
                    <input type="checkbox" name="gender_female" value="female" checked="checked" />
                    <?= Asset::img('maps/pink_dot_select.png'); ?>
                </div>
            <!--
            <input type="checkbox" name="gender_other" value="other" checked="checked" />
            <?= Asset::img('maps/green_dot.png'); ?>
            -->
            </div>
        </div>
        <div class="grid4" id="datesRange">
		    <form method="POST">
		        <div class="formRow">
		            <label>Start Date</label>
        			<span class="floatL mr5"><input type="text" class="datepicker" name="start_date[]" data-max="#date_end" placeholder="date" value="<?=date('m/d/Y', $start_time)?>" /></span>
        			<span class="floatL mr5"><input type="text" class="timepicker" name="start_date[]" placeholder="time" style="width: 70px !important;" value="<?=date('h:iA', $start_time)?>" /></span>
        			<input class="buttonS bGreen" type="submit" value="Refresh">
			    </div>
			    <div class="clear"></div>
		        <div class="formRow">
		            <label>End Date</label>
        			<span class="floatL mr5"><input type="text" class="datepicker" name="end_date[]" data-max="#date_end" placeholder="date" value="<?=date('m/d/Y', $end_time)?>" /></span>
        			<span class="floatL mr5"><input type="text" class="timepicker" name="end_date[]" placeholder="time" style="width: 70px !important;" value="<?=date('h:iA', $end_time)?>" /></span>
			    </div>
		    </form>
        </div>
    </div>
    <div class="grid12">
        <div id="mapElement" class="grid10"></div>
        <div class="grid2" >
            <ul id="stats">
                <li><span id="stats_total"></span><br>Total</li>
                <li><span id="stats_female"></span><br>Female</li>
                <li><span id="stats_male"></span><br>Male</li>
                <li><span id="stats_other"></span><br>Unknow</li>
                <li><span id="stats_locals"></span><br>Locals</li>
                <li><span id="stats_visitors"></span><br>Visitors</li>
            </ul>
        </div>
	</div>
</div>

<script  type="text/javascript"  src="http://maps.micello.com/webmap/v0/micellomap.js"></script>

<script type="text/javascript">

var active_shoppers = <?=json_encode($active_shoppers)?>;
var micello_stores = <?=json_encode($micello_stores)?>;
var genders = {
	"male":   { img: "/assets/images/maps/blue_dot.png",  display: true },
	"female": { img: "/assets/images/maps/pink_dot.png",  display: true },
	"other":  { img: "/assets/images/maps/green_dot.png", display: true }
}
var mapControl;
var mapDataObject;
var community;

micello.maps.init("<?=$micello_api_key?>", mapInit);
function mapInit() {
    mapControl = new micello.maps.MapControl('mapElement');
    mapDataObject = mapControl.getMapData();

    mapDataObject.loadCommunity(<?=$micello_id?>);
    
    mapDataObject.mapChanged = function(e) {
        if (e.comLoad) {
            community = mapDataObject.getCommunity();

            $.each(micello_stores, function(key, value) {
                var inlay = {
                    "id": key,
                    "lr": value.name,
                    "t": "Search Result",
                    "anm": "shopsuey_inlays"
                };
                mapDataObject.addInlay(inlay);
        	});
        }
        // Set the active shoppers markers
        setGpsMarkers();
    };
}

function setGpsMarkers() {
    mapDataObject.removeMarkerOverlay(null, 'active_shoppers');

    var stats = {
	    "total":    0,
		"male":     0,
		"female":   0,
		"other":    0,
		"locals":   0,
		"visitors": 0
	};
    var thisLevel = mapDataObject.getCurrentLevel();
    var drawing = mapDataObject.getCurrentDrawing();

    for(var i = 0; i < active_shoppers.length; i++) {
        var active_shopper = active_shoppers[i];
        var map_coords = mapDataObject.latLonToMxy(active_shopper.latitude, active_shopper.longitude);
        if  (map_coords[0] > 0 && map_coords[0] < drawing.w && map_coords[1] > 0 && map_coords[1] < drawing.h && genders[active_shopper.gender].display)  {
            var gpsMarker = {
                "mx": map_coords[0],
                "my": map_coords[1],
                "lid": thisLevel.id,
                "mt": micello.maps.markertype.IMAGE,
                "mr": {"src": genders[active_shopper.gender].img},
                "aid": "active_shoppers",
                "idat": "<div>"+active_shopper.latitude+", "+active_shopper.longitude+"</div>"
            }
            mapDataObject.addMarkerOverlay(gpsMarker);
            stats.total++;
            stats[active_shopper.gender]++;
            if (active_shopper.zipcode == "<?=$company_zipcode?>") {
            	stats.locals++;
            } else {
            	stats.visitors++;
            }
        } 
    }
    updateStats(stats);
}

function updateStats(stats) {
	$("#stats_total").html(stats.total);
	$("#stats_female").html(stats.female);
	$("#stats_male").html(stats.male);
	$("#stats_other").html(stats.other);
	$("#stats_locals").html(stats.locals);
	$("#stats_visitors").html(stats.visitors);
}

$(function() {
	$('#genderWrapper input[type="checkbox"]').change(function(e) {
		genders[$(this).val()].display = $(this).is(":checked");
        setGpsMarkers();
	});
});

</script>
