var tz_by_state = {
    "AL" : "America/Chicago",
    "AK" : "America/Anchorage",
    "AZ" : "America/Phoenix",
    "AR" : "America/Chicago",
    "CA" : "America/Los_Angeles",
    "CO" : "America/Denver",
    "CT" : "America/New_York",
    "DE" : "America/New_York",
    "FL" : "America/New_York",
    "GA" : "America/New_York",
    "HI" : "Pacific/Honolulu",
    "ID" : "America/Denver",
    "IL" : "America/Chicago",
    "IN" : "America/New_York",
    "IA" : "America/Chicago",
    "KS" : "America/Chicago",
    "KY" : "America/New_York",
    "LA" : "America/Chicago",
    "ME" : "America/New_York",
    "MD" : "America/New_York",
    "MA" : "America/New_York",
    "MI" : "America/New_York",
    "MN" : "America/Chicago",
    "MS" : "America/Chicago",
    "MO" : "America/Chicago",
    "MT" : "America/Denver",
    "NE" : "America/Chicago",
    "NV" : "America/Los_Angeles",
    "NH" : "America/New_York",
    "NJ" : "America/New_York",
    "NM" : "America/Denver",
    "NY" : "America/New_York",
    "NC" : "America/New_York",
    "ND" : "America/Chicago",
    "OH" : "America/New_York",
    "OK" : "America/Chicago",
    "OR" : "America/Los_Angeles",
    "PA" : "America/New_York",
    "RI" : "America/New_York",
    "SC" : "America/New_York",
    "SD" : "America/Chicago",
    "TN" : "America/Chicago",
    "TX" : "America/Chicago",
    "UT" : "America/Denver",
    "VT" : "America/New_York",
    "VA" : "America/New_York",
    "WA" : "America/Los_Angeles",
    "WV" : "America/New_York",
    "WI" : "America/Chicago",
    "WY" : "America/Denver"
};

var last_google_string   = '';
var update_address_fields = function() {   
    var geocoder = new google.maps.Geocoder();

    var city    = $('#city').val();
    var address = $('#address').val();
    var country = $('#country_id option:selected').html();

    if (city == '' && address == '' && country == '') {
        return;
    }
    
    var address_parts = [];

    if (address) {
    	address_parts.push(address);
    }
    if (city) {
    	address_parts.push(city);
    }
    if (country) {
        /* Removes the country code from the end of the string */
        country = country.substring(0, country.length - 5);
    	address_parts.push(country);
    }
    
    var google_string = address_parts.join(", ");
    if (google_string == last_google_string) {
        return;
    }
    last_google_string = google_string;

    $(".geo_info_loader").show();

    geocoder.geocode({'address': google_string}, function(results, status) {
        if (status == 'OK' && results.length > 0) {
            var result      = results[0];
            var coordinates = result.geometry.location;

            $(result.address_components).each(function(idx, element) {
                if (element.types[0] == 'postal_code') {
                    if ($('#zip').val() == '') {
                        $('#zip').val(element.long_name);
                    }
                } else if(element.types[0] == 'administrative_area_level_1') {
                    if ($('#st').val() == '') {
                        $('#st').val(element.short_name);
                    }
                } else if(element.types[0] == 'locality') {
                    if ($('#city').val() == '') {
                        $('#city').val(element.long_name);
                    }
                }
            });
            
            var longitude = coordinates.lng();
            var latitude  = coordinates.lat();

            // Check if the lat/long fields exists
            if ($('#latitude').length && $('#longitude').length) {
            	if (($('#latitude').val() == '' && $('#longitude').val() == '') || $('#stand_alone_merchant').length) {
                    // Only I'll auto update the values of latitude/longitude if the fields are empty or the location is a stand alone merchant
                    $('#latitude').val(latitude);
                    $('#longitude').val(longitude);
            	} else if ($('#coords_dialog').length) {
            		// Open the confirm dialog
            		$('#coords_dialog').data("latitude", latitude);
            		$('#coords_dialog').data("longitude", longitude);
            		$('#coords_dialog').dialog("open");
            	}
            }

            var external_service_uri = window.location.protocol + '//' + window.location.hostname + '/externalservices/address_information';
            $.get(external_service_uri, { latitude : latitude, longitude : longitude }, function(response) {
                if (response.status == 'OK') {
                    var timezone_id = response.timeZoneId;

                    if ($('#timezone option[value="' + timezone_id + '"]').length > 0) {
                        $('#timezone option[value="' + timezone_id + '"]').attr('selected', 'selected');
                        $('#timezone').trigger("liszt:updated");
                    }
                }
                $(".geo_info_loader").hide();
            }, 'json');
        } else {
            $(".geo_info_loader").hide();
        }
    });
};
