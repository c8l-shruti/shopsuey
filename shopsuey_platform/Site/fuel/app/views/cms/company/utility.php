<div class="fluid">
	<div class="grid12">
		<div class="formRow">
			<div class="grid12"><label for="foursquare_search">Search with Foursquare:</label></div>
			<div class="clear"></div>
			<div class="grid10">
				<input type="text" id="foursquare_search" name="foursquare_search" value="" />
			</div>
			<div class="grid1">
			    <?= Asset::img('elements/loaders/1s.gif', array('id' => 'foursquare_search_loader')); ?>
			</div>
		</div>
		<div class="formRow">
			<div class="grid12"><label for="foursquare_location">Location:</label></div>
			<div class="clear"></div>
			<div class="grid11">
				<input type="text" id="foursquare_location" name="foursquare_location" value="" placeholder="San Francisco, CA" />
			</div>
		</div>
	    <?= Asset::img('elements/services/foursquare.png', array('id' => 'foursquarePowered')); ?>
	</div>
</div>

<div class="fluid">
	<div class="grid12">
		<div class="formRow">
			<div class="grid12"><label for="yelp_search">Search with Yelp:</label></div>
			<div class="clear"></div>
			<div class="grid10">
				<input type="text" id="yelp_search" name="yelp_search" value="" />
			</div>
			<div class="grid1">
			    <?= Asset::img('elements/loaders/1s.gif', array('id' => 'yelp_search_loader')); ?>
			</div>
		</div>
		<div class="formRow">
			<div class="grid12"><label for="yelp_location">Location:</label></div>
			<div class="clear"></div>
			<div class="grid10">
				<input type="text" id="yelp_location" name="yelp_location" value="" placeholder="San Francisco, CA" />
			</div>
			<div class="grid1">
			    <?= Asset::img('elements/loaders/1s.gif', array('id' => 'yelp_location_loader')); ?>
			</div>
		</div>
        <?= Asset::img('elements/services/yelp.png', array('id' => 'yelpPowered')); ?>
    </div>
</div>

<div id="foursquare-dialog-confirm" title="Overwrite changes to the Location?">
    <p>
        <span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>
        The information from Fousquare will overwrite the current location's data.<br>Which fields would you like to populate?
    </p>
</div>

<div id="yelp-dialog-confirm" title="Overwrite changes to the Location?">
    <p>
        <span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>
        The information from Yelp will overwrite the current location's data.<br>Which fields would you like to populate?
    </p>
</div>
    
<script type="text/javascript">

var foursquare_data = null;
var yelp_data = null;

var days = ['sun', 'mon', 'tue', 'wed', 'thr', 'fri', 'sat'];

function foursquare_populate(data, empty_only) {
	if (! $("#mname").val() || ! empty_only) {
	    $("#mname").val(data.name);
	}
	if (! $("#phone").val() || ! empty_only) {
		$("#phone").val(data.contact.formattedPhone);
	}
	if (! $("#st").val() || ! empty_only) {
		$("#st").val(data.location.state);
		$("#st").change();
    }
	if (! $("#address").val() || ! empty_only) {
		$("#address").val(data.location.address);
		$("#address").change();
	}
	if (! $("#city").val() || ! empty_only) {
		$("#city").val(data.location.city);
		$("#city").change();
    }
	if (! $("#zip").val() || ! empty_only) {
		$("#zip").val(data.location.postalCode);
	}
	if (! $("#web").val() || ! empty_only) {
	    $("#web").val(data.url);
	}
	if (empty_hours() || ! empty_only) {
		// Get hours info
		$.getJSON(
			"<?=Config::get('base_url')?>api/location/foursquare_hours/",
			{ login_hash: "<?=$login_hash?>", venue_id: data.id },
			function(data) {
				var sliders = $('.slider-range');
			    for (var i = 0; i < 3; i++) {
					for (var j = 0; j < days.length; j++) {
						$('input[name="hours['+i+']['+days[j]+']"]').prop('checked', data[i].days.indexOf(days[j]) != -1);
					}
					$('input[name="hours['+i+'][open]"]').val(data[i].open);
					$('input[name="hours['+i+'][close]"]').val(data[i].close);
					var sliderValues = Times.getTimesForSlider(data[i].open, data[i].close);
					if (sliderValues) {
						$(sliders[i]).slider({values: [sliderValues.startTime, sliderValues.endTime]});
			    	    $('#time'+i).text(data[i].open + ' - ' + data[i].close);
				    }
			    }
		    }
		);
	}
}

function empty_hours() {
	for (var i = 0; i < 3; i++) {
		for (var j = 0; j < days.length; j++) {
			if ($('input[name="hours['+i+']['+days[j]+']"]').prop('checked')) {
				return false;
			}
		}
	}
	return true;
}

function yelp_populate(data, empty_only) {
    if (! $("#mname").val() || ! empty_only) {
        $("#mname").val(data.name);
    }
    if (! $("#phone").val() || ! empty_only) {
        $("#phone").val(data.display_phone);
    }
    if (! $("#st").val() || ! empty_only) {
        $("#st").val(data.location.state_code);
		$("#st").change();
    }
    if (! $("#address").val() || ! empty_only) {
        $("#address").val(data.location.address[0]);
		$("#address").change();
    }
    if (! $("#city").val() || ! empty_only) {
        $("#city").val(data.location.city);
		$("#city").change();
    }
    if (! $("#zip").val() || ! empty_only) {
        $("#zip").val(data.location.postal_code);
    }
}

$("#foursquare_search_loader").hide();
$("#yelp_search_loader").hide();
$("#yelp_location_loader").hide();

$("#foursquare_search").customautocomplete({
	delay: 500,
	minLength: 5,
	source: function(request, response) {
		$.getJSON(
			"<?=Config::get('base_url')?>api/location/foursquare_venues/",
			{ login_hash: "<?=$login_hash?>", term: $("#foursquare_search").val(), location: $("#foursquare_location").val() },
			response
		);
	},
	select: function(event, ui) {
		if (ui.item.data) {
			foursquare_data = ui.item.data;
			$("#foursquare-dialog-confirm").dialog("open");
		}
    },
	search: function(event, ui) {
		$("#foursquare_search_loader").show();
	}
});

$("#yelp_search").customautocomplete({
	delay: 500,
	minLength: 5,
	source: function(request, response) {
		$.getJSON(
			"<?=Config::get('base_url')?>api/location/yelp_businesses/",
			{ login_hash: "<?=$login_hash?>", term: $("#yelp_search").val(), location: $("#yelp_location").val() },
			response
		);
	},
	select: function(event, ui) {
	    if (ui.item.data) {
			yelp_data = ui.item.data;
			$("#yelp-dialog-confirm").dialog("open");
		}
    },
	search: function(event, ui) {
		$("#yelp_search_loader").show();
	}
});

$("#yelp_location").keyup(function() {
	if ($("#yelp_location").val() == '') {
		$("#yelp_search").val('Enter a location to proceed');
		$("#yelp_search").prop('disabled', true);
	} else {
		$("#yelp_search").val('');
	    $("#yelp_search").prop('disabled', false);
    }
});

$("#foursquare_search").bind("autocompletesearchcomplete", function(event, contents) {
	$("#foursquare_search_loader").hide();
});

$("#yelp_search").bind("autocompletesearchcomplete", function(event, contents) {
	$("#yelp_search_loader").hide();
});

$("#yelp_location").customautocomplete({
	delay: 500,
	minLength: 3,
	source: function(request, response) {
		Places.getPlaces(request.term, function(predictions, status) {
			var options;
		    if (status == "OK") {
				options = $.map(predictions, function(elem, i) {
					return elem.description;
				});
			} else {
				options = [];
			}
			return response(options);
	    });
	},
	search: function(event, ui) {
		$("#yelp_location_loader").show();
	}
});

$("#yelp_location").bind("autocompletesearchcomplete", function(event, contents) {
	$("#yelp_location_loader").hide();
});

$(function() {
	$( "#foursquare-dialog-confirm" ).dialog({
		autoOpen: false,
    	resizable: false,
    	height:175,
    	width:500,
    	modal: true,
    	buttons: {
        	"Empty Fields": function() {
    		    foursquare_populate(foursquare_data, true);
    		    $( this ).dialog( "close" );
        	},
            "All Fields": function() {
    		    foursquare_populate(foursquare_data, false);
    		    $( this ).dialog( "close" );
        	},
        	Cancel: function() {
        	    $( this ).dialog( "close" );
        	}
    	}
	});

	$( "#yelp-dialog-confirm" ).dialog({
		autoOpen: false,
    	resizable: false,
    	height:175,
    	width:500,
    	modal: true,
    	buttons: {
        	"Empty Fields": function() {
    		    yelp_populate(yelp_data, true);
    		    $( this ).dialog( "close" );
        	},
            "All Fields": function() {
    		    yelp_populate(yelp_data, false);
    		    $( this ).dialog( "close" );
        	},
        	Cancel: function() {
        	    $( this ).dialog( "close" );
        	}
    	}
	});

	$("#yelp_location").keyup();
});

</script>
