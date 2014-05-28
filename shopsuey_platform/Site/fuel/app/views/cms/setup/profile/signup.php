<body>

<div id="user-status-create">

<!-- Create/Update user account -->
<div class="content multiple">
    <div class="login-wrapper">
        <h1 style="margin-bottom: 20px">
        <?php if ($user->is_guest()): ?>
        Create an Account
        <?php else: ?>
        Update your Account
        <?php endif; ?>
        </h1>
        
        <p class="mb25">
            <?php if ($user->is_guest()): ?>
            <a href="<?=Uri::create('login')?>">Already have an account? Sign In</a>
            <?php else: ?>
            <a href="<?=Uri::create('logout')?>">Not you? Logout</a>
            <?php endif; ?>
        </p>

        <div class="step-counter">Step 1 of 3</div>
        
        <?=Form::open(array('id' => 'user_create', 'action' => Uri::create('#'), 'method' => 'post'))?>
            <?=Form::input(array('type' => 'hidden', 'name' => 'nonce', 'value' => $user_nonce, 'id' => 'user_create_nonce'))?>
            <div class="fieldWrapper">
                <?=Form::input(array('type' => 'text', 'name' => 'name_of_business', 'placeholder' => 'Name of Business', 'value' => $user->get_meta_field_value('name_of_business'), 'id' => 'business-name'))?>
                <span class="fieldRequired">*</span>
            </div>
            <div class="fieldWrapper">
                <?=Form::input(array('type' => 'text', 'name' => 'real_name', 'placeholder' => 'Your Name', 'value' => $user->get_meta_field_value('real_name')))?>
                <span class="fieldRequired">*</span>
            </div>
            <div class="fieldWrapper">
                <?=Form::input(array('type' => 'text', 'name' => 'role', 'placeholder' => 'Position w/Company', 'value' => $user->get_meta_field_value('role')))?>
                <span class="fieldRequired">*</span>
            </div>
            <div class="fieldWrapper">
                <?=Form::input(array('type' => 'email', 'name' => 'email', 'placeholder' => 'Email', 'value' => $user->email))?>
                <span class="fieldRequired">*</span>
            </div>
            <?php if ($user->is_guest()): ?>
            <div id="newUserFields">
                <div class="fieldWrapper">
                    <?=Form::input(array('type' => 'password', 'name' => 'password', 'placeholder' => 'Password'))?>
                    <span class="fieldRequired">*</span>
                </div>
                <div class="fieldWrapper">
                    <?=Form::input(array('type' => 'password', 'name' => 'confirmPassword', 'placeholder' => 'Confirm Password'))?>
                    <span class="fieldRequired">*</span>
                </div>
                <div class="terms-checkbox-wrapper">
                    <input type="checkbox" id="terms" name="terms">
                    <label for="terms">I agree to <a href="http://www.thesuey.com/assets/static/tos.html" target="_blank">ShopSuey's Terms of Service</a></label>
                    <span class="fieldRequired">*</span>
                </div>
            </div>
            <?php endif; ?>

            <div class="actionsWrapper">
                <div class="mt25">
                    <input class="big-text" type="submit" value="Next" name="submit">
                </div>
            </div>
    	<?=Form::close()?>
    </div>
</div>

</div>

<script type="text/javascript">

var Create = (function() {
	$('#user_create').submit(function(event) {
	    hide_errors();
	    event.preventDefault();
	    $("#user-status-create").block();
	    $.post("<?=Uri::create('setup/profile/ajax_create')?>", $("#user_create").serialize())
	        .done(function(data) {
	        	data = $.parseJSON(data);
	        	$("#user_create_nonce").val(data.nonce);
	            if (data.error) {
	        		$("#user-status-create").animatescroll();
	        	    show_errors(data.errors);
	            } else {
	                next_step(data.login_hash);
	            }
	        })
	        .fail(function() {
	            show_errors([{"field": null, "message": "Error while performing request. Please try again later"}]);
	        })
	        .always(function() {
	    	    $("#user-status-create").unblock();
            });
	});

	var create_error = function(msg) {
        return $('<div class="fieldError"><p>' + msg + '</p></div>').hide();
	};
	
	var show_errors = function(errors) {
		$.each(errors, function(index, error) {
		    var error_msg = create_error(error.message);
			if (error.field) {
			    var field = $("#user_create").find('input[name="' + error.field + '"]');
			    field.parent().append(error_msg);
			    field.parent().addClass("fieldErrorWrapper");
			} else {
				$("#user_create").prepend(error_msg);
			}
		    error_msg.show("slow");
	    });
	};
	
	var hide_errors = function() {
		$("#user_create").find('div.fieldError').hide("slow", function() {
            $(this).remove();
        });
		$("#user_create").find(".fieldErrorWrapper").removeClass("fieldErrorWrapper");
	};
	
	var next_step = function(new_login_hash) {
		$(".business-name").text($("#business-name").val());
		$("#newUserFields input").prop("disabled", true);
		$("#newUserFields").hide();
		// Update login_hash global var
		login_hash = new_login_hash;
		// Unblock next step
	    $("#user-status-<?=Model_User::STATUS_STEP1?>").unblock();
	    // Animate scroll to next step
		$("#user-status-<?=Model_User::STATUS_STEP1?>").animatescroll();
	};
})();

</script>







<div id="user-status-<?=Model_User::STATUS_STEP1?>">

<!-- Create/Update businesses for user -->
<div class="content multiple">
    <div class="login-wrapper">
        <p>You are logged in: <span class="business-name"><?=$business_name?></span>. <a class="floatR" href="<?= Uri::create('logout') ?>">Not you? Logout</a></p>
        
        <h1 style="margin-bottom:20px">Welcome, <span class="business-name"><?=$business_name?></span></h1>
        
        <?=Form::open(array('id' => 'businesses', 'action' => Uri::create('#'), 'method' => 'POST', 'class' => 'profile'))?>
        
            <p>Let's get you officially setup!</p>
            <div class="step-counter">Step 2 of 3</div>
            
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
                <input type="hidden" id="stores_number" name="stores_number" value="0"/>
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
                <div class="mt25">
                    <input class="big-text" type="button" value="Previous" name="previous">
                    <input class="big-text" type="submit" value="Next" name="submit">
                </div>
            </div>

    	<?=Form::close()?>
    </div>
</div>

</div>

<script type="text/javascript">

var Businesses = (function() {
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

	var filter_repeated_locations = function(locations) {
	    // Filter out already added locations
	    var existing_location_ids = $.map($('#businesses input[name="location_id[]"]'), function(input) {
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

	var reset_form = function() {
	    $('.second_step').hide();
	    $('.third_step').hide();
	    $('.fourth_step').hide();

	    $('#locations div').remove();
	    $('input.social_info').val('');
	}

	var add_location = function(i) {
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
			value: $(".business-name").first().text(),
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
	    				login_hash: login_hash
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
					login_hash: login_hash
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

	$("#businesses").submit(function(event) {
            event.preventDefault();

	    $('#businesses .fieldError').remove();
	    $('#businesses input').removeClass('fieldErrorInput');

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
//	            } else if (business_type === 'merchant' && mall_id === '') {
//				    error_message = 'No Marketplace is selected for the new Merchant at row ' + index;
//	                $('div[data-location-number=' + (index-1) + '] input[name="location[]"]').addClass('fieldErrorInput');
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
	        //console.log(facebook);
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
	    
	    $('#stores_number').val($('.location-row').length);

	    $("#user-status-<?=Model_User::STATUS_STEP1?>").block();
	    $.post("<?=Uri::create('setup/profile/ajax_businesses')?>", $("#businesses").serialize())
	        .done(function(data) {
	        	data = $.parseJSON(data);
	            if (data.error) {
	            	$("#user-status-<?=Model_User::STATUS_STEP1?>").animatescroll();
					$.each(data.errors, function(index, error) {
		            	var error_msg = $('<div class="fieldError"><p>' + error.message + '</p></div>').hide();
						$("#businesses").prepend(error_msg);
						error_msg.show("slow");
				    });
	            } else {
	                Content.set_content(data.content);
	                Content.set_instagram(<?=json_encode($instagram)?>);
	        		// Unblock next step
	        	    $("#user-status-<?=Model_User::STATUS_STEP2?>").unblock();
	        	    // Animate scroll to next step
	        		$("#user-status-<?=Model_User::STATUS_STEP2?>").animatescroll();
	            }
	        })
	        .fail(function() {
	            alert("Error while performing request. Please try again later");
	        })
	        .always(function() {
	    	    $("#user-status-<?=Model_User::STATUS_STEP1?>").unblock();
            });
	});

	var display_error = function(msg, store_number) {
	    //console.log(msg, store_number)
	    var div = $('<div>').addClass('fieldError').html(msg).hide();
	    $('.location-row').each(function() {
	        if ($(this).attr('data-location-number') == store_number) {
	            $(this).append(div);
	            div.show("slow");
	        }
	    })
	}

	var remove_error = function(store_number) {
		$('.location-row input').removeClass('fieldErrorInput');
	    $('.location-row').each(function() {
	        if ($(this).attr('data-location-number') == store_number) {
	            $(this).children('div.fieldError').hide("slow", function() {
	                $(this).remove();
	            });
	        }
	    })
	}

	var display_social_error = function(msg, field) {
	    //console.log(msg, field)
	    var div = $('<div>').addClass('fieldError').html(msg).hide();
	    $('input[name="'+field+'"]').addClass('fieldErrorInput');
	    $('input[name="'+field+'"]').parent().append(div);
	    div.show("slow");
	}

    $('#businesses input[name="previous"]').click(function(event) {
        event.preventDefault();
        $("#user-status-create").animatescroll();
    });
	
	return {
		"init": function() {
			$('input[name="business_type"]').prop('checked', false);
			reset_form();
	    },
	    "add_locations": function(type, locations) {
	    	$("#business_type #" + type).prop("checked", true);
	    	$("#business_type input").change();
	        for(var i = 1; i < locations.length; i++) {
    			add_location(i);
    		}
    		$("#locations .location-row").each(function(index, row) {
        		var location = locations[index];
        		$(row).find('input[name="name[]"]').val(location.name);
        		$(row).find('input[name="zip_or_city[]"]').val(location.zip);
        		$(row).find('input[name="location[]"]').val(location.location);
        		$(row).find('input[name="location_id[]"]').val(location.id);
        		$(row).find('input[name="mall_id[]"]').val(location.mall_id);
        		$(row).find("span.check").show();
    	    });
	    },
	    "set_social_info": function(social) {
			$('input[name="facebook"]').val(social.facebook);
			$('input[name="twitter"]').val(social.twitter);
	    }
	};
})();

</script>








<div id="user-status-<?=Model_User::STATUS_STEP2?>">

<!-- Businesses content info edition -->
<div class="content multiple">
    <div class="login-wrapper">
        <p>You are logged in: <span class="business-name"><?=$business_name?></span>. <a class="floatR" href="<?= Uri::create('logout') ?>">Not you? Logout</a></p>
    
        <form id="businesses-content" action="#" method="POST" enctype="multipart/form-data">

            <div class="step-counter">Step 3 of 3</div>

            <div class="image-selectors">
                <div class="floatL">
                    <strong>Landing Image on File</strong>
                    <p class="mt15 mb15">
                        <input type="checkbox" name="landing_instagram" id="landing-instagram" value="1">
                        &nbsp;<strong>Use my Instagram feed</strong>
                    </p>
                    <div>
                        <img alt="" src="" id="landing-img-preview" width="200" style="display:none;"/>
                        <div class="no-image" id="landing-no-image">No image</div>
                    </div>
                    <div>
                        <div class="img-actions-wrapper">
                            <div style="position: relative; overflow:hidden">
                                <input type="file" name="landing" class="fake-file-input" />
                                <a href="#"> + Choose New File</a>
                            </div>
                            <div style="width:200px;" class="mt10">
                                <input type="checkbox" name="replace_landing_in_all_stores" id="replace_landing_in_all_stores" value="1">
                                <label for="replace_landing_in_all_stores">Replace landing image in all stores with this image</label>
                            </div>
                            <input type="hidden" name="x1_landing" id="landing-img-x1" />
                            <input type="hidden" name="y1_landing" id="landing-img-y1" />
                            <input type="hidden" name="x2_landing" id="landing-img-x2" />
                            <input type="hidden" name="y2_landing" id="landing-img-y2" />
                            <input type="hidden" name="preview_width_landing" id="landing-img-preview-width" />
                            <input type="hidden" name="preview_height_landing" id="landing-img-preview-height" />
                        </div>
                    </div>
                </div>

                <div class="floatL imgRight">
                    <strong>Logo Image on File</strong>
                    <div style="padding-top:51px;">
                        <img alt="" src="" id="logo-img-preview" width="200" style="display:none;" />
                        <div class="no-image" id="logo-no-image">No image</div>
                    </div>
                    <div>
                        <div class="img-actions-wrapper">
                            <div style="position: relative; overflow: hidden">
                                <input type="file" name="logo" class="fake-file-input" />
                                <a href="#"> + Choose New File</a>
                            </div>
                            <div style="display:none; width:200px;" class="mt10">
                                <input type="checkbox" name="replace_logo_in_all_stores" id="replace_logo_in_all_stores" value="1">
                                <label for="replace_logo_in_all_stores">Replace logo in all stores with this image</label>
                            </div>
                            <input type="hidden" name="x1_logo" id="logo-img-x1" />
                            <input type="hidden" name="y1_logo" id="logo-img-y1" />
                            <input type="hidden" name="x2_logo" id="logo-img-x2" />
                            <input type="hidden" name="y2_logo" id="logo-img-y2" />
                            <input type="hidden" name="preview_width_logo" id="logo-img-preview-width" />
                            <input type="hidden" name="preview_height_logo" id="logo-img-preview-height" />
                        </div>
                    </div>
                </div>

                <div class="clear"></div>
            </div>

            <div class="mt30" style="position: relative">
                <?= Asset::img('ios-devices.png', array('width' => 500)) ?>

                <div style="position: absolute; left:331px; top:234px; width: 30px; height:30px; overflow: hidden">
                    <?= Asset::img('logo_default.jpg', array('width' => 29, 'height' => 30, 'id' => 'logo-img-preview-ios')); ?>
                </div>

                <div style="position: absolute; left:17px; top:87px; width: 153px; height:113px; overflow: hidden">
                    <?= Asset::img('logo_big.png', array('width' => 153, 'height'=> 113, 'id' => 'landing-img-preview-ios')); ?>
                </div>

                <?= Asset::img('ios-devices-map.png', array('width' => 42, 'height' => 42, 'style' => "position: absolute; left:426px; top:178px;")) ?>
                <span class="ios-device-title content-name" style="position: absolute; left: 38px; top: 69px;text-align: center;width: 109px;"></span>
                <span class="ios-device-list-title content-name" style="position: absolute; left:364px; top:238px;"></span>
            </div>

            <div class="mt30" style="text-align: center">
                <strong>Select categories that apply to your business</strong>
                <div class="categories-wrapper">
                    <select name="categories[0]">
                        <option disabled selected>Select a category</option>
                        <?php foreach (CMS::categories() as $category) : ?>
                            <option value="<?= $category->id ?>"><?= $category->name ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select name="categories[1]">
                        <option disabled selected>Select a category</option>
                        <?php foreach (CMS::categories() as $category) : ?>
                            <option value="<?= $category->id ?>"><?= $category->name ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select name="categories[2]">
                        <option disabled selected>Select a category</option>
                        <?php foreach (CMS::categories() as $category) : ?>
                            <option value="<?= $category->id ?>"><?= $category->name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="actionsWrapper">
                <div class="mt25">
                    <input class="big-text" type="button" value="Previous" name="previous">
                    <input class="big-text" type="submit" value="Submit" name="submit">
                </div>
            </div>

        </form>
    </div>
</div>

</div>

<div id="setup-instagram-dialog">
    <p>
        Uploaded images will be lost, are you sure you want to proceed?
    </p>
</div>

<script type="text/javascript">

var Content = (function() {
    var jcrop_api = {
        "logo": null,
        "landing": null
    };
    var image_uploaded = false;
    var default_logo = null,
        default_landing = null;
    var instagram_set = false;
    var instagram_url = "";
    var instagram_latest_post = {};
    
    $('#businesses-content input[type="file"]').change(function(e) {
        var name = $(this).attr('name');

        if (jcrop_api[name]) {
            jcrop_api[name].destroy();
        }

        $('#' + name).val('');

        if (window.File && window.FileReader && window.FileList && window.Blob) {
            e.preventDefault();

            var file = $(this)[0].files[0];

            if (!file.type.match('image/.*')) {
                alert("Only image files allowed");
                return;
            }

            var reader = new FileReader();

            reader.onload = function(event) {
                $('#' + name + '-img-preview').replaceWith($('<img/>', {
                    id: name + "-img-preview",
                    src: event.target.result,
                    width: 200
                }));

                $('#' + name + '-no-image').hide();

                $('#replace_' + name + '_in_all_stores').parent().show();

                $('#' + name + '-instagram').prop('checked', false);
                
                $('#' + name + '-img-preview-ios').attr('src', event.target.result)

                $('#' + name + '-img-preview').load(function() {

                    $('#' + name + '-img-preview-height').val($('#' + name + '-img-preview').height());
                    $('#' + name + '-img-preview-width').val($('#' + name + '-img-preview').width());

                    initJCrop($('#' + name + '-img-preview'), name);
                });

                image_uploaded = true;
            };

            reader.readAsDataURL(file);
        }
    });

    var getShowPreviewFunction = function(name) {
        return function(coords) {
            if (parseInt(coords.w) > 0)
            {
                $('#' + name + '-img-x1').val(coords.x);
                $('#' + name + '-img-y1').val(coords.y);
                $('#' + name + '-img-x2').val(coords.x2);
                $('#' + name + '-img-y2').val(coords.y2);


                var rx, ry;
                if (name == 'logo') {
                    rx = 30 / coords.w;
                    ry = 30 / coords.h;
                } else if (name == 'landing') {
                    rx = 153 / coords.w;
                    ry = 113 / coords.h;
                }

                $('#' + name + '-img-preview-ios').css({
                    width: Math.round(rx * $('#' + name + '-img-preview').width()) + 'px',
                    height: Math.round(ry * $('#' + name + '-img-preview').height()) + 'px',
                    marginLeft: '-' + Math.round(rx * coords.x) + 'px',
                    marginTop: '-' + Math.round(ry * coords.y) + 'px'
                });
            }
        };
    };

    var getHidePreviewFunction = function(name) {
        return function() {
            $('#' + name + '-img-preview').stop().fadeOut('fast');
            $('#' + name + '-img-x1').val(0);
            $('#' + name + '-img-y1').val(0);
            $('#' + name + '-img-x2').val(0);
            $('#' + name + '-img-y2').val(0);
        };
    };

    var initJCrop = function(selector, name) {
        var jcrop_options = {
            onChange: getShowPreviewFunction(name),
            onSelect: getShowPreviewFunction(name),
            onRelease: getHidePreviewFunction(name),
            aspectRatio: name == "logo" ? 1 : 320 / 235,
            setSelect: [0, 0, 100, 100],
        };

        selector.Jcrop(jcrop_options, function() {
            jcrop_api[name] = this;
        });
    };

    var set_default_landing = function() {
        if (default_landing) {
            $('#landing-img-preview').attr('src', default_landing);
            $('#landing-img-preview-ios').attr('src', default_landing);
            $('#landing-img-preview').show();
        } else {
            $('#landing-img-preview').attr('src', "");
            $('#landing-img-preview').attr('alt', "");
            $('#landing-img-preview').hide();
            $('#landing-img-preview-ios').attr('src', "<?=Asset::get_file('logo_big.png', 'img')?>");
            $('#landing-no-image').show();
            $('#replace_landing_in_all_stores').parent().hide();
        }
        $('#landing-img-preview-ios').css({
            width: '153px',
            height: '113px',
            marginLeft: '0px',
            marginTop: '0px'
        });
    };

    $('#landing-instagram').change(function() {
        if ($(this).prop('checked') && !instagram_set) {
            if (image_uploaded) {
                $( "#setup-instagram-dialog" ).dialog( "open" );
            } else {
            	window.location.replace(instagram_url);
            }
        } else if($(this).prop('checked') && instagram_set) {
            if (jcrop_api['landing']) {
                jcrop_api['landing'].destroy();
            }
            $('#landing-img-preview').replaceWith($('<img/>', {
                id: "landing-img-preview",
                src: instagram_latest_post.images.standard_resolution.url,
                width: 200
            }));
            $('#landing-no-image').hide();
            $('#replace_landing_in_all_stores').parent().show();
            $('#landing-img-preview-ios').attr('src', instagram_latest_post.images.standard_resolution.url);
            $('#landing-img-preview-ios').css({
                width: '153px',
                height: '113px',
                marginLeft: '0px',
                marginTop: '0px'
            });
        } else {
            set_default_landing();
        }
    });

    $( "#setup-instagram-dialog" ).dialog({
        autoOpen: false,
        width: 500,
        height: 175,
        modal: true,
        buttons: {
            "Ok": function() {
            	window.location.replace(instagram_url);
            },
            "Cancel": function() {
            	$('#landing-instagram').prop('checked', false);
                $( this ).dialog( "close" );
            }
        },
        title: "Instagram Integration",
        resizable: false
    });

    $('#businesses-content input[name="previous"]').click(function(event) {
        event.preventDefault();
        $("#user-status-<?=Model_User::STATUS_STEP1?>").animatescroll();
    });

    $('#businesses-content input[name="skip"]').click(function(event) {
        event.preventDefault();
        goto_next_step();
    });
    
    $("#businesses-content").submit(function(event) {
        event.preventDefault();
        $("#user-status-<?=Model_User::STATUS_STEP2?>").block();
        
        var formData = new FormData($("#businesses-content")[0]);
        
        $.ajax({
            url: "<?=Uri::create('setup/profile/ajax_content')?>", //server script to process data
            type: 'POST',

            // Form data
            data: formData,
            //Options to tell JQuery not to process data or worry about content-type
            cache: false,
            contentType: false,
            processData: false		    
        })
        .done(function(data) {
            data = $.parseJSON(data);
            if (data.error) {
                $("#user-status-<?=Model_User::STATUS_STEP2?>").animatescroll();
                $.each(data.errors, function(index, error) {
                    var error_msg = $('<div class="fieldError"><p>' + error.message + '</p></div>').hide();
                    $("#businesses-content").prepend(error_msg);
                    error_msg.show("slow");
                });
            } else {
                
                // Setup finished, redirect to dashboard
                window.location.replace("<?=Uri::create('welcome/index')?>");
                
            }
        })
        .fail(function() {
                alert("Error while performing request. Please try again later");
        })
        .always(function() {
                $("#user-status-<?=Model_User::STATUS_STEP2?>").unblock();
        });

    });

    var goto_next_step = function() {
		// Unblock next step
	    $("#user-status-<?=Model_User::STATUS_STEP3?>").unblock();
	    // Animate scroll to next step
		$("#user-status-<?=Model_User::STATUS_STEP3?>").animatescroll();
    };
	
    return {
        "set_instagram": function(instagram) {
            instagram_set = instagram.is_set;
            instagram_url = instagram.auth_url;
            instagram_latest_post = instagram.latest_post;

            $("#landing-instagram").prop("checked", instagram_set);
            $("#landing-instagram").change();
        },
        "set_content": function(content) {
            $.each(jcrop_api, function(key, jcrop) {
                if (jcrop) {
                    jcrop.destroy();
                }
            });

            $("span.content-name").text(content.name);
            
        	var logos_base_url = "<?=Config::get('asset.url') . Config::get('asset.folders.img.0') . Config::get('cms.logo_images_path') . DS?>";
        	var logo_url;
        	if (content.logo) {
            	logo_url = logos_base_url + content.logo;
                $('#logo-img-preview-ios').attr('src', logo_url);
                $('#logo-no-image').hide();
        	} else {
            	logo_url = "";
                $('#logo-img-preview').attr('alt', "");
                $('#logo-img-preview-ios').attr('src', "<?=Asset::get_file('logo_default.jpg', 'img')?>");
                $('#logo-no-image').show();
                $('#replace_logo_in_all_stores').parent().hide();
            }
            $('#logo-img-preview').replaceWith($('<img/>', {
                id: "logo-img-preview",
                src: logo_url
            }));
            $('#logo-img-preview').css({
                width: '200px',
                marginLeft: '0px',
                marginTop: '0px'
            });
            if (logo_url === "") {
            	$('#logo-img-preview').hide();
            } else {
            	$('#logo-img-preview').show();
            }
            $('#logo-img-preview-ios').css({
                width: '30px',
                height: '30px',
                marginLeft: '0px',
                marginTop: '0px'
            });

        	var landing_base_url = "<?=Config::get('asset.url') . Config::get('asset.folders.img.0') . Config::get('cms.landing_images_path') . DS?>";
        	var landing_url;
        	if (content.landing_screen_img) {
            	landing_url = landing_base_url + content.landing_screen_img;
                $('#landing-img-preview-ios').attr('src', landing_url);
                $('#landing-no-image').hide();
                default_landing = landing_url;
        	} else {
            	landing_url = "";
                $('#landing-img-preview').attr('alt', "");
                $('#landing-img-preview-ios').attr('src', "<?=Asset::get_file('logo_big.png', 'img')?>");
                $('#landing-no-image').show();
                $('#replace_landing_in_all_stores').parent().hide();
                default_landing = null;
            }
            $('#landing-img-preview').replaceWith($('<img/>', {
                id: "landing-img-preview",
                src: landing_url
            }));
            $('#landing-img-preview').css({
                width: '200px',
                marginLeft: '0px',
                marginTop: '0px'
            });
            if (landing_url === "") {
            	$('#landing-img-preview').hide();
            } else {
            	$('#landing-img-preview').show();
            }
            $('#landing-img-preview-ios').css({
                width: '153px',
                height: '113px',
                marginLeft: '0px',
                marginTop: '0px'
            });
        }
    };
})();

</script>








<!--
<div id="user-status-<?=Model_User::STATUS_STEP3?>">


<div class="content multiple">
    <div class="login-wrapper">
        <p style="text-align: left !important">You are logged in: <span class="business-name"><?=$business_name?></span>. <a class="floatR" href="<?= Uri::create('logout') ?>">Not you? Logout</a></p>
    
        <div class="step-counter">Step 4 of 4</div>
        <?=Form::open(array('id' => 'payment_form', 'action' => Uri::create('#'), 'method' => 'POST', 'class' => 'profile'))?>
        <div id="paymentFormWrapper">
            <p>
                ShopSuey is only $<?=$fees_info['merchant_monthly_fee']?> per month per store or $<?=$fees_info['mall_monthly_fee']?> per month per marketplace.<br>
                <a target="_blank" href="/assets/static/refund-pricing.html">See what's included in your subscription</a> and feel free to change your membership at any time
            </p>
            <p>
                <strong>
                Enjoy a <?=$fees_info['trial_days']?> day free trial by signing up now!
                </strong>
            </p>

            <?=Form::input(array('type' => 'hidden', 'name' => 'nonce', 'value' => $payment_nonce, 'id' => 'payment_nonce'))?>

            <div class="fieldWrapper">
                <?=Form::input(array('type' => 'text', 'data-encrypted-name' => 'number', 'placeholder' => 'Credit Card Number', 'size' => '16', 'autocomplete' => 'off', 'id' => 'number'))?>
                <span class="fieldRequired">*</span>
            </div>
            <div class="fieldWrapper">
                <?=Form::input(array('type' => 'text', 'data-encrypted-name' => 'expiration', 'placeholder' => 'Exp. Date (MM/YY)', 'size' => '5', 'autocomplete' => 'off', 'id' => 'expiration'))?>
                <span class="fieldRequired">*</span>
            </div>
            <div class="fieldWrapper">
                <?=Form::input(array('type' => 'text', 'data-encrypted-name' => 'cvv', 'placeholder' => 'Security Code', 'size' => '4', 'autocomplete' => 'off', 'id' => 'cvv'))?>
                <span class="fieldRequired">*</span>
            </div>

            <div style="text-align: center">
                <strong>Billing Address</strong>
            </div>

            <div class="fieldWrapper">
                <?=Form::input(array('type' => 'text', 'name' => 'address', 'placeholder' => 'Address', 'size' => '30'))?>
                <span class="fieldRequired">*</span>
            </div>
            <div class="fieldWrapper">
                <?=Form::input(array('type' => 'text', 'name' => 'city', 'placeholder' => 'City', 'size' => '30'))?>
                <span class="fieldRequired">*</span>
            </div>
            <div class="fieldWrapper">
                <?=Form::input(array('type' => 'text', 'name' => 'state', 'placeholder' => 'State', 'size' => '30'))?>
                <span class="fieldRequired">*</span>
            </div>
            <div class="fieldWrapper">
                <?=Form::input(array('type' => 'text', 'name' => 'zip', 'placeholder' => 'Zip Code', 'size' => '5'))?>
                <span class="fieldRequired">*</span>
            </div>
        </div>

        <div id="promoWrapper">
            <div style="text-align: center">
                <strong>Promo Code</strong>
            </div>
        
            <div>
                <?=Form::input(array('style' => 'display:inline-block', 'type' => 'text', 'placeholder' => 'ADD A PROMO CODE', 'size' => '20', 'autocomplete' => 'off', 'id' => 'promo', 'name' => 'promo', 'class' => 'not-mandatory'))?>
                <?=Asset::img('elements/loaders/1s.gif', array('style' => 'display:none', 'id' => 'promoLoader'));?>
                <span class="check" style="display:none;margin-left:0" id='promoCheck'>&#x2713;</span>
            </div>
            <div id='promoDiscount' style="display:none"></div>
            <div id='promoFree' style="display:none">This promo code allows you to use ShopSuey for free!</div>
            <div id='promoError' class="fieldError" style="display:none">This doesn't seem to be a valid promo code</div>
            <a id="promoDontHave" style="font-size: 12px;" href="#" onclick="noPromoCode()">I don't have a promo code</a>
        </div>
        
        <div class="terms-checkbox-wrapper mb15">
            <?=Form::input(array('type' => 'checkbox', 'name' => 'privacy', 'id' => 'privacy', 'value' => '1'))?>
            <label for="privacy">I have read and agree to the <a target="_blank" href="http://www.thesuey.com/assets/static/privacy.html">privacy policy</a> and <a target="_blank" href="http://www.thesuey.com/assets/static/refund-pricing.html">refund policy</a></label>
            <span class="fieldRequired">*</span>
        </div>

        <p>
            Questions? <a href="mailto:sales@thesuey.com">sales@thesuey.com</a> or call us at 415.218.3348
        </p>

        <div class="actionsWrapper">
            <div class="mt25">
                <input type="hidden" id="freePromoCode" value="0"/>
                <input class="big-text" type="button" value="Previous" name="previous">
                <input class="big-text" type="submit" value="Submit" name="submit">
            </div>
        </div>
            
    	<?=Form::close()?>
    </div>
</div>

</div>
-->

<script type="text/javascript" src="<?= $client_side_library_url ?>"></script>
<script type="text/javascript">

var Payment = (function() {
    var client_side_encryption_key = "<?= $client_side_encryption_key ?>";

    var display_error = function(msg, fieldId) {
        var div = $('<div>').addClass('fieldError').text(msg).hide();
    	$('#payment_form #' + fieldId).parent().append(div);
        $('#payment_form #' + fieldId).addClass('fieldErrorInput');
        div.show({duration:300, easing: 'easeInOutCubic' });
    };

    var validate_payment_form = function() {
        var is_valid = true;

        if (! $('#privacy').is(':checked')) {
        	display_error('You must agree to the privacy and refund policies', 'privacy');
        	is_valid = false;
        }
        
        if ($('#promo').val() != '' && $('#freePromoCode').val() == '1') {
            return is_valid;
        }
        
        var empty_text_field = false;
        $('#payment_form input[type="text"]:not(.not-mandatory)').each(function() {
        	if ($(this).val() === '') {
                display_error("This field is mandatory", $(this).attr('id'));
                empty_text_field = true;
                return false;
            }
        });
        
        if (empty_text_field) {
            return false;
        }
        
        if (! /^\d{16}$/.test($('#number').val())) {
            display_error('Invalid credit card number', 'number');
            is_valid = false;
        }

        if (! /^\d{2}\/\d{2}$/.test($('#expiration').val())) {
        	display_error('Invalid expiration date', 'expiration');
            is_valid = false;
        }

        if (! /^\d{3,4}$/.test($('#cvv').val())) {
        	display_error('Invalid security code', 'cvv');
            is_valid = false;
        }

        return is_valid;
    };

    var promoCodeTimer = null;
    
    var promoCodeChange = function() {
        clearTimeout(promoCodeTimer);
        $('#promo').removeClass('fieldErrorInput');
        $('#promoError').hide();

        promoCodeTimer = setTimeout(function() {
            $('#promoLoader').show();
            $.get('<?php echo Uri::create('api/promocode/check') ?>', { code : $('#promo').val(), login_hash: login_hash }, function(response) {
                var promoCodeType;
                if (response.promo_code) {
                    promoCodeType = response.promo_code.type_name;
                } else {
                    promoCodeType = 'invalid';
                }

                if (promoCodeType == "discount") {
                    $('#promoCheck').show();
                    $('#promoDontHave').hide();
                    $('#promoError').hide();
                    $('#promo').removeClass('fieldErrorInput');
                    $('#promoFree').hide();
                    $('#promoDiscount').hide();
                    
                    if (response.promo_code.description != '') {
                        $('#promoDiscount').html(response.promo_code.description);
                        $('#promoDiscount').show();
                    }
                    
                    $('#freePromoCode').val(0);
                } else if (promoCodeType == "free") {
                    // to do: redirect to proper screen
                    $('#promoCheck').show();
                    $('#promoDontHave').hide();
                    $('#promoError').hide();
                    $('#promo').removeClass('fieldErrorInput')
                    $('#promoFree').show();
                    $('#promoDiscount').hide();
                    hidePaymentForm();
                    
                    $('#freePromoCode').val(1);
                } else {
                    // probably an invalid promo code
                    $('#promo').addClass('fieldErrorInput');
                    $('#promoError').show();
                    $('#promoCheck').hide();
                    $('#promoDontHave').show();
                    $('#promoFree').hide();
                    $('#promoDiscount').hide();
                    displayPaymentForm();
                    
                    $('#freePromoCode').val(0);
                }
                $('#promoLoader').hide();
            }, 'json');
        }, 500);
    };

    var displayPaymentForm = function() {
        $('#paymentFormWrapper').show({duration:300, easing: 'easeInOutCubic' })
    };

    var hidePaymentForm = function() {
        $('#paymentFormWrapper').hide({duration:300, easing: 'easeInOutCubic' });
    };

    var noPromoCode = function() {
        $('#promoWrapper').hide({duration:300, easing: 'easeInOutCubic' });
    };

    $('#promo').keyup(promoCodeChange);

    
    var payment_submit = function(event) {
        event.preventDefault();
        $('#payment_form .fieldError').remove();
        $('#payment_form input').removeClass('fieldErrorInput');
        var validation_result = validate_payment_form();
        if (! validation_result) {
            return;
        }
        
	    $("#user-status-<?=Model_User::STATUS_STEP3?>").block();
	    $.post("<?=Uri::create('setup/profile/ajax_payment')?>", $("#payment_form").serialize())
	        .done(function(data) {
	        	data = $.parseJSON(data);
	        	$("#payment_nonce").val(data.nonce);
	            if (data.error) {
	            	$("#user-status-<?=Model_User::STATUS_STEP3?>").animatescroll();
					$.each(data.errors, function(index, error) {
		            	var error_msg = $('<div class="fieldError"><p>' + error.message + '</p></div>').hide();
						$("#payment_form").prepend(error_msg);
						error_msg.show("slow");
				    });
	            } else {
		            // Setup finished, redirect to dashboard
		            window.location.replace("<?=Uri::create('welcome/index')?>");
	            }
	        })
	        .fail(function() {
	            alert("Error while performing request. Please try again later");
	        })
	        .always(function() {
	        	$("#user-status-<?=Model_User::STATUS_STEP3?>").unblock();
            });
    };

    $('#payment_form input[name="previous"]').click(function(event) {
        event.preventDefault();
        $("#user-status-<?=Model_User::STATUS_STEP2?>").animatescroll();
    });
    
    $(document).ready(function () {
        var braintree = Braintree.create(client_side_encryption_key);
        braintree.onSubmitEncryptForm('payment_form', payment_submit);
    });
    
})();

</script>

<script type="text/javascript">

    var login_hash = "<?=$login_hash?>";

    $(function() {
        <?php if ($user->is_new_user()): ?>
        // Move into the current step for user
        $("#user-status-<?=$user->status?>").animatescroll();
        <?php endif; ?>

        // Block unavailable steps
        var unavailable_steps = [];
        var unavailable_steps_msgs = {
            "<?=Model_User::STATUS_STEP1?>": "<h1>Complete previous step to enable business setup</h1>",
            "<?=Model_User::STATUS_STEP2?>": "<h1>Complete previous step to enable content setup for business</h1>",
            "<?=Model_User::STATUS_STEP3?>": "<h1>Complete previous step to enable payment</h1>"
        };
        
        switch("<?=$user->status?>") {
            case "":
            case "<?=Model_User::STATUS_ACTIVE?>":
                unavailable_steps.push("<?=Model_User::STATUS_STEP1?>");
            case "<?=Model_User::STATUS_STEP1?>":
                unavailable_steps.push("<?=Model_User::STATUS_STEP2?>");
            case "<?=Model_User::STATUS_STEP2?>":
                unavailable_steps.push("<?=Model_User::STATUS_STEP3?>");
        }

		$.each(unavailable_steps, function(index, step) {
            $("#user-status-" + step).block({ 
                message: unavailable_steps_msgs[step],
                css: { width: '60%', textAlign: 'center', fontSize: "1.5em" }
            });
        });

		Businesses.init();

        <?php if ($user->status >= Model_User::STATUS_STEP2): ?>
            var locations_type = "<?=$user->group == Model_User::GROUP_MERCHANT ? 'merchant' : 'marketplace'?>";
            var locations = [];
        	<?php foreach($businesses as $business): ?>
                <?php if($business->type == Model_Location::TYPE_MALL): ?>
                    <?php $location = $business->city ?>
                <?php else: ?>
                    <?php $mall = Model_Mall::find($business->mall_id) ?>
                    <?php $location = $mall->name ?>
                <?php endif; ?>
                locations.push({
                    "id": "<?=$business->id?>",
                    "name": "<?=$business->name?>",
                    "zip": "<?=$business->zip?>",
                    "mall_id": "<?=$business->mall_id?>",
                    "location": "<?=$location?>"
                });
        	<?php endforeach; ?>
        	Businesses.add_locations(locations_type, locations);
        	Businesses.set_social_info(<?=json_encode($social)?>);
        <?php endif; ?>

        Content.set_content(<?=json_encode($content)?>);
        Content.set_instagram(<?=json_encode($instagram)?>);
    });
</script>

</body>
