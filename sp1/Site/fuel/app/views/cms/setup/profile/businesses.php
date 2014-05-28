<body class="background with-logo">
    <div class="content">
        <p>You are logged in: <?=$business_name?>. <a class="floatR" href="<?= Uri::create('logout') ?>">Not you? Logout</a></p>
        <div class="login-wrapper">
            
            <h1 style="margin-bottom:20px">Welcome, <?=$business_name?></h1>
            
            <?=Form::open(array('id' => 'businesses', 'action' => Uri::create('setup/profile/businesses'), 'method' => 'POST', 'class' => 'profile'))?>
            
                <p>Let's get you officially setup!</p>
                <div class="step-counter">Step 1 of 3</div>
                
                <?=CMS::field_error(@$notice, null)?>

                <div id="business_type">
                    <strong>Select Business Type</strong>
                    <div class="business-type-selector">
                        <div class="floatR">
                            <label for="marketplace" class="floatL">Marketplace</label>
                            <div>
                                <?= Asset::img('business-type-marketplace.png', array('alt' => 'Marketplace')) ?>
                            </div>
                            <div class="business-type-radio-wrapper">
                                <input type="checkbox" name="business_type" value="marketplace" id="marketplace">
                            </div>
                        </div>
                        <div class="floatR">
                            <label for="merchant" class="floatL">Merchant</label>
                            <div>
                                <?= Asset::img('business-type-merchant.png', array('alt' => 'Merchant')) ?>
                            </div>
                            <div class="business-type-radio-wrapper">
                                <input type="checkbox" name="business_type" value="merchant" id="merchant">
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
                
                <div class="second_step">
                    <input type="text" id="stores_number" name="stores_number" value="0"/>
                    <?=CMS::field_error(@$notice, 'stores_number')?>
                </div>
                
                <div class="mt20 mb5 third_step"><strong>Let's find your locations!</strong></div>
                
                <div class="third_step" id="locations">
                </div>

                <div class="mt15 mb5 third_step"><strong>Social media links</strong></div>
                <div class="third_step">
                    <?=Form::input(array('type' => 'text', 'name' => 'facebook', 'placeholder' => 'Your Facebook Page', 'class' => 'social_info'))?>
                    <p class="example">Example: https://www.facebook.com/GetShopSuey</p>
                </div>
                <div class="third_step">
                    <?=Form::input(array('type' => 'text', 'name' => 'twitter', 'placeholder' => 'Your Twitter User', 'class' => 'social_info'))?>
                    <p class="example">Example: @shopsueyapp</p>
                </div>
                <div class="third_step">
                    <?=Form::input(array('type' => 'text', 'name' => 'instagram', 'placeholder' => 'Your Instagram URL', 'class' => 'social_info'))?>
                </div>

                <div class="actionsWrapper third_step">
                    <div class="mt25 mb25">
                        <input class="big-text" type="submit" value="Next" name="submit">
                    </div>
                </div>

        	<?=Form::close()?>
        </div>
    </div>
</body>

<script type="text/javascript">

/**
 * Hack to fix broken "response" event for the autocomplete plugin
 */
$.widget("ui.customautocomplete", $.extend({}, $.ui.autocomplete.prototype, {
    __response: function(contents) {
        var filtered_contents = filter_repeated_locations(contents);
        $(this.element).trigger("autocompletesearchcomplete", [filtered_contents]);
        $.ui.autocomplete.prototype.__response.apply(this, [filtered_contents]);
    }
}));

function filter_repeated_locations(locations) {
    // Filter out already added locations
    var existing_location_ids = $.map($('input[name="location_id[]"]'), function(input) {
        return $(input).val();
    });
    var filtered_locations = [];
    for (var i = 0; i < locations.length; i++) {
        if ($.inArray(locations[i].id, existing_location_ids) === -1) {
            filtered_locations.push(locations[i]);
        }
    }
    return filtered_locations;
}

function reset_form() {
    $('.second_step').hide();
    $('.third_step').hide();
    $('.fourth_step').hide();

    $('#locations div').remove();
    $('input.social_info').val('');
}

function add_location(i) {
	var location_div = $('<div>', {
		"class": "location-row",
        "data-location-number": i
	});
    
    // Remove the "add button" in the other rows
    $('.add-location-button').each(function(idx, element) {
        $(element).remove()
    });
    
    // Add the "add button" in the last (first in the list) added row.
    var add_button = $('<a/>', { 
        'class' : 'add-location-button',
        'html'  : '+',
        'href'  : '#'
    });
    location_div.prepend($('<div>').addClass('add-location-button-holder').append(add_button));

	var create_location_div = $('<div>', {
		"class": "location-create"
	}).prependTo(location_div).hide();

    var create_location_checkbox = $('<input>', {
		type: 'checkbox',
		name: 'create_location[]',
		value: '1'
	});

	var create_location_p = $('<p>', {
		text: "Looks like we can't find this location, lets create it"
	}).append(create_location_checkbox).appendTo(create_location_div);

	var name_text_field = $('<input>', {
		type: 'text',
		name: 'name[]',
		placeholder: 'Name',
		value: <?=json_encode($business_name)?>,
		"class": "location-field name"
	}).appendTo(location_div);

	var zip_or_city_text_field = $('<input>', {
		type: 'text',
		name: 'zip_or_city[]',
		placeholder: 'ZIP or City',
		"class": "location-field name"
	}).appendTo(location_div);

	var location_text_field = $('<input>', {
		type: 'text',
		name: 'location[]',
		placeholder: '',
		"class": "location-field location"
	}).appendTo(location_div);

    var location_status_div = $('<div>', {
		"class": "location-status"
	}).appendTo(location_div);
	
	var location_found_check = $('<span>', {
		html: '&#x2713;',
		"class": "check"
	}).appendTo(location_status_div).hide();

	var loading_indicator = $('<?= Asset::img('elements/loaders/1s.gif'); ?>').appendTo(location_status_div).hide();
	
	var location_id_hidden_field = $('<input>', {
		type: 'hidden',
		name: 'location_id[]'
	}).appendTo(location_div);

	var mall_id_hidden_field = $('<input>', {
		type: 'hidden',
		name: 'mall_id[]'
	}).appendTo(location_div);


    var remove_button = $('<a/>', { 
        'class' : 'remove-location-button',
        'html'  : '-',
        'href'  : '#'
    }).appendTo(location_div);

	var reset_existing_location = function() {
		location_id_hidden_field.val('');
		mall_id_hidden_field.val('');
		location_found_check.hide();
    };
	
	var start_search_callback = function() {
		reset_existing_location();
		loading_indicator.show();
    };

    var error_found = false;
    
    var display_error_message = function(msg, node) {
        var i = $(node).attr('data-location-number')
        error_found = true;
	    remove_error(i);
		display_error(msg, i);
    };

    var remove_error_message = function(node) {
        var i = $(node).attr('data-location-number')
        error_found = false;
        remove_error(i);
    };

    var show_create_location = function() {
    	create_location_div.show("slow");
    };

    var hide_create_location = function() {
    	create_location_div.hide("slow");
    };
    
	if ($('input[name="business_type"]:checked').val() === 'merchant') {
		location_text_field.attr('placeholder', 'Marketplace');
    	location_text_field.customautocomplete({
    		delay: 500,
    		minLength: 3,
    		source: function(request, response) {
    			var search_url = '<?=$businesses_search_url?>';

    			$.getJSON(search_url, {
    				name: request.term,
    				type: 'marketplace',
    				login_hash: '<?=$login_hash?>'
    			})
    			.done(function(data) { response(data.locations); })
    			.fail(function() { console.log( "Request failed!!" ); response([]); });
    		},
    		select: function(event, ui) {
    			var location_data = ui.item;
    			mall_id_hidden_field.val(location_data.id);
    			zip_or_city_text_field.val(location_data.zip);

    		    remove_error_message(location_div);
    			show_create_location();
    	    },
    		search: function(event, ui) {
    			start_search_callback();
    		    hide_create_location();
    		},
    		close: function(event, ui) {
    	        if (mall_id_hidden_field.val() === '') {
    		        display_error_message('No marketplace was selected from search results', location_div);
    	        }
    		},
    		change: function(event, ui) {
    			if (ui.item === null && ! error_found) {
    				reset_existing_location();
    				display_error_message('The Marketplace for the Merchant has changed and the results were reset', location_div);
    				hide_create_location();
    			}
    	    }
    	});
	} else {
		location_text_field.attr('placeholder', 'City');
		location_text_field.keyup(function(event) {
			if (location_id_hidden_field.val() !== '') {
				reset_existing_location();
				display_error_message('The city for the Marketplace has changed and the results were reset', location_div);
				show_create_location();
		    } else if(location_text_field.val() !== '') {
		    	remove_error_message(location_div);
		    }
		});
    }

    var autocomplete_params = {
		delay: 500,
		minLength: 3,
		source: function(request, response) {
			var search_url = '<?=$businesses_search_url?>';

			$.getJSON(search_url, {
				name: name_text_field.val(),
			    zip_or_city: zip_or_city_text_field.val(),
				type: $('input[name="business_type"]:checked').val(),
				login_hash: '<?=$login_hash?>'
			})
			.done(function(data) { response(data.locations); })
			.fail(function() { console.log( "Request failed!!" ); response([]); });
		},
		select: function(event, ui) {
			var location_data = ui.item;

			name_text_field.val(location_data.name);
			zip_or_city_text_field.val(location_data.zip);

			location_id_hidden_field.val(location_data.id);
			mall_id_hidden_field.val(location_data.mall_id);
			location_found_check.show();

			if ($('input[name="business_type"]:checked').val() === 'merchant') {
				if (location_data.mall_id) {
				    location_text_field.val(location_data.location);
				} else {
				    location_text_field.val('');
			    }
			} else {
			    location_text_field.val(location_data.city);
		    }
			
			hide_create_location();

			// Override the default behaviour
			event.preventDefault();
	    },
		search: function(event, ui) {
			start_search_callback();
		    remove_error_message(location_div);
		    hide_create_location();
		},
		close: function(event, ui) {
	        if (location_id_hidden_field.val() === '') {
		        display_error_message('No business was selected from search results', location_div);
		        show_create_location();
	        }
		},
		change: function(event, ui) {
			if (ui.item === null && ! error_found) {
				reset_existing_location();
		        display_error_message('Some of the fields for the business have changed and the results were reset', location_div);
				show_create_location();
			}
	    }
	};
	
	name_text_field.customautocomplete(autocomplete_params);
	zip_or_city_text_field.customautocomplete(autocomplete_params);

    var response_callback = function(event, contents) {
    	loading_indicator.hide();
        if (!contents || contents.length === 0) {
	        display_error_message('No business was found', location_div);
	        show_create_location();
        }
    };
	
	name_text_field.bind("autocompletesearchcomplete", response_callback);
	zip_or_city_text_field.bind("autocompletesearchcomplete", response_callback);

	location_text_field.bind("autocompletesearchcomplete", function(event, contents) {
    	loading_indicator.hide();
        if (!contents || contents.length === 0) {
	        display_error_message('No marketplace was found', location_div);
	        show_create_location();
        }
    });
	
	$('#locations').prepend(location_div);
    
    if ($('.location-row').length > 1) {
        $('.remove-location-button').show();
    } else {
        $('.remove-location-button').hide();
    }
}

$('.remove-location-button').live('click', function(e) {
    e.preventDefault();
    
    if ($('.location-row').length > 1) {
        var container = $(this).parents('.location-row');
        
        if (container.attr('data-location-number') == 0) {
            var add_button = $('<a/>', { 
                'class' : 'add-location-button',
                'html'  : '+',
                'href'  : '#'
            });
            container.next('.location-row').find('.add-location-button-holder').append(add_button);
        }
        
        container.remove();
    }
    
    if ($('.location-row').length == 1) {
        $('.remove-location-button').hide();
    }
    
    index_location();
});

$('.add-location-button').live('click', function(e) {
    e.preventDefault();
    
    $('.remove-location-button').show();
    var totalRows = $('.location-row').length;
    add_location(totalRows);
    index_location();
});

var index_location = function() {
    $('.location-row').each(function(idx, row) {
        $(row).attr('data-location-number', idx);
    });
}

$("#business_type input").change(function() {
	if ($(this).prop("checked") === true) {
    	$("#business_type input").prop("checked", false);
        $(this).prop("checked", true);
        reset_form();
        
        add_location(0);
        $('.third_step').show();
	}
	$(this).prop("checked", true);
});

$('#locations').on("click", 'input[name="existing_location[]"]', function(event) {
	var that = this;
	if ($(this).prop("checked") === true) {
		$(this).parent().parent().find('input[name="existing_location[]"]').each(function(index) {
			if (this !== that) {
				$(this).prop("checked", false);
			}
		});
	}
	$(this).prop("checked", true);
});

$("#businesses").submit(function() {
    $('.fieldError').remove();
    $('input').removeClass('fieldErrorInput');

	var business_type = $('input[name="business_type"]:checked').val();
	var errors = false;

    var location_rows = $('.location-row');
    location_rows.each(function(index) {
        index++;
        var error_message = '';
		var name = $(this).find('input[name="name[]"]').val();
		var zip = $(this).find('input[name="zip_or_city[]"]').val();
		var location = $(this).find('input[name="location[]"]').val();
		var create_location = $(this).find('input[name="create_location[]"]:checked').val();

	    var location_id = $(this).find('input[name="location_id[]"]').val();
	    var mall_id = $(this).find('input[name="mall_id[]"]').val();

	    if (location_id === '') {
		    if (name === '') {
			    error_message = 'Please enter the business name';
                $('div[data-location-number=' + (index-1) + '] input[name="name[]"]').addClass('fieldErrorInput');
		    } else if (zip === '') { 
                error_message = 'Please enter the zip code';
                $('div[data-location-number=' + (index-1) + '] input[name="zip_or_city[]"]').addClass('fieldErrorInput');
//            } else if (business_type === 'merchant' && mall_id === '') {
//			    error_message = 'No Marketplace is selected for the new Merchant at row ' + index;
//                $('div[data-location-number=' + (index-1) + '] input[name="location[]"]').addClass('fieldErrorInput');
		    } else if (business_type === 'marketplace' && location === '') {
			    error_message = 'Please enter the name of the city';
                $('div[data-location-number=' + (index-1) + '] input[name="location[]"]').addClass('fieldErrorInput');
		    } else if (business_type === 'merchant' && location !== '' && mall_id === '') {
			    error_message = 'Please select a valid marketplace or leave empty for a standalone merchant';
                $('div[data-location-number=' + (index-1) + '] input[name="location[]"]').addClass('fieldErrorInput');
            } else if (create_location !== '1') {
			    error_message = 'Please confirm you want to create a new business';
                $('div[data-location-number=' + (index-1) + '] input[name="create_location[]"]').addClass('fieldErrorInput');
            }
	    }
	    
	    if (error_message !== '') {
        	display_error(error_message, index - 1);
            errors = true;
		    return false;
	    }
    });
    
	var facebook = $('input[name="facebook"]').val();
	var twitter = $('input[name="twitter"]').val();

    if ((facebook !== '') && !facebook.match(/^http(s)?:\/\/(www.)?facebook.com\/(page\/)?\w[\w\.]*(\/\w[\w\.]*)?$/)) {
        console.log(facebook);
        display_social_error('Incorrect facebook page format.', 'facebook');
        errors = true;
	}
	
    if ((twitter !== '') && !twitter.match(/^@\w+$/)) {
        display_social_error('Incorrect twitter user format.', 'twitter');
        errors = true;
	}

    if (errors) {
    	return false;
    }
    
    $('#stores_number').val($('.location-row').length)
});

function display_error(msg, store_number) {
    console.log(msg, store_number)
    var div = $('<div>').addClass('fieldError').html(msg).hide();
    $('.location-row').each(function() {
        if ($(this).attr('data-location-number') == store_number) {
            $(this).append(div);
            div.show("slow");
        }
    })
}

function remove_error(store_number) {
	$('.location-row input').removeClass('fieldErrorInput');
    $('.location-row').each(function() {
        if ($(this).attr('data-location-number') == store_number) {
            $(this).children('div.fieldError').hide("slow", function() {
                $(this).remove();
            });
        }
    })
}

function display_social_error(msg, field) {
    console.log(msg, field)
    var div = $('<div>').addClass('fieldError').html(msg).hide();
    $('input[name="'+field+'"]').addClass('fieldErrorInput');
    $('input[name="'+field+'"]').parent().append(div);
    div.show("slow");
}

$(function() {
	$('input[name="business_type"]').prop('checked', false);
	reset_form();
});

</script>
