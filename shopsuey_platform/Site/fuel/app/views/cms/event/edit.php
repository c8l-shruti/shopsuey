<!-- Event Info -->
<form id="wizard1" method="post" enctype="multipart/form-data">
<?=CMS::create_nonce_field('event_'.$action, 'nonce')?>
<input type="hidden" id="action" name="action" value="<?=$action?>" />
<?php if (@$event->get_location_ids()) : ?>
    <?php foreach($event->get_location_ids() as $location_id): ?>
    <input type="hidden" id="location_id_<?=$location_id?>" name="location_ids[]" value="<?=$location_id?>" />
    <?php endforeach; ?>
<?php endif; ?>

<input type="hidden" name="force_top_message" value="<?=!is_null($event->force_top_message)?$event->force_top_message:0?>" />
<div class="fluid">
	<div class="grid9"><!-- Column Left -->
		<div class="widget"><!-- Event Info -->
			<div class="whead">
				<h6><span class="icon-info-3"></span>Event Info</h6>
				<div class="clear"></div>
			</div>

			<fieldset class="formpart">
				<div class="formRow">
					<div class="grid2"><label for="title">Event Title:</label></div>
					<div class="grid10">
						<input type="text" id="title" name="title" placeholder="What's the event name?" value="<?=@$event->title?>" class="required" />
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow">
					<div class="grid2"><label for="email">Display dates in the app?</label></div>
					<div class="grid10">
						<input type="checkbox" class="filter" id="show_dates" name="show_dates" value="1" <?=(@$event->show_dates) ? 'checked="checked"' : '' ?> />
						<div class="clear"></div>
					</div>
					<div class="clear"></div>
				</div>
                <div class="formRow">
					<div class="grid2"><label for="email">Start Date - Time:</label></div>
					<div class="grid10">
						<span class="floatL mr5"><input type="text" class="datepicker required" id="date_start" name="date_start[]" data-max="#date_end" placeholder="date" value="<?=(@$event->date_start) ? date('m/d/Y', strtotime(@$event->date_start)) : ''?>" /></span>
						<span class="floatL mr5 mt4 text-center">&ndash;</span>
						<span class="floatL"><input type="text" class="timepicker" name="date_start[]" placeholder="time" style="width: 70px !important;" value="<?=(@$event->date_start) ? date('h:iA', strtotime(@$event->date_start)) : ''?>" /></span>
						<div class="clear"></div>
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow">
					<div class="grid2"><label for="email">End Date - Time:</label></div>
					<div class="grid10">
						<span class="floatL mr5"><input type="text" class="datepicker required" id="date_end" name="date_end[]" data-min="#date_start" placeholder="date" value="<?=(@$event->date_end) ? date('m/d/Y', strtotime(@$event->date_end)) : ''?>" /></span>
						<span class="floatL mr5 mt4 text-center">&ndash;</span>
						<span class="floatL"><input type="text" class="timepicker" name="date_end[]" placeholder="time" style="width: 70px !important;" value="<?=(@$event->date_end) ? date('h:iA', strtotime(@$event->date_end)) : ''?>" /></span>
						<div class="clear"></div>
					</div>
					<div class="clear"></div>
				</div>

				<div class="formRow" id="location_selector_wrapper">
					<div class="grid2">Location:</div>
					<div class="grid10" id="searchContainer">
					    <input type="text" id="location_search" name="location_search" class="locationsCheck" />
					</div>
					<div class="clear"></div>
				</div>

				<?php if (!$me->is_admin()): ?>
                <div class="formRow">
					<div class="grid6">
						<label>
							<input type="checkbox" class="filter" id="all_locations" name="all_locations" value="1" <?=(isset($all_locations) && $all_locations) ? 'checked="checked"' : ''?>/>
							Include all locations</label>
					</div>
					<div class="clear"></div>
				</div>
                <?php endif; ?>
				
                <div class="formRow" style="display: none">
					<div class="grid2"><label for="name">Promo Code:</label></div>
					<div class="grid10">
						<input type="text" id="code" name="code" value="<?=@$event->code?>" />
					</div>
					<div class="clear"></div>
				</div>
                
                <div class="formRow" style="display: none">
					<div class="grid2"><label for="name">Coordinator phone number:</label></div>
					<div class="grid10">
						<input type="text" id="coordinator_phone" name="coordinator_phone" value="<?=@$event->coordinator_phone?>" />
					</div>
					<div class="clear"></div>
				</div>
                
                <div class="formRow" style="display: none">
					<div class="grid2"><label for="name">Coordinator email address:</label></div>
					<div class="grid10">
						<input type="text" id="coordinator_email" name="coordinator_email" value="<?=@$event->coordinator_email?>" />
					</div>
					<div class="clear"></div>
				</div>
                
				<div class="formRow">
					<div class="grid2"><label for="name">Event website:</label></div>
					<div class="grid10">
                        <input type="text" id="website" name="website" value="<?=@$event->website?>" placeholder="What's the event website url?" />
					</div>
					<div class="clear"></div>
				</div>
                
                <div class="formRow" style="display: none">
					<div class="grid2"><label for="name">Facebook Event ID:</label></div>
					<div class="grid10">
						<input type="text" id="fb_event_id" name="fb_event_id" value="<?=@$event->fb_event_id?>" />
					</div>
					<div class="clear"></div>
				</div>
                
                <div class="formRow" style="display: none">
					<div class="grid2"><label for="name">Foursquare Event ID:</label></div>
					<div class="grid10">
						<input type="text" id="foursquare_event_id" name="foursquare_event_id" value="<?=@$event->foursquare_event_id?>" />
					</div>
					<div class="clear"></div>
				</div>
                
                <div class="formRow" style="display: none">
                    <div class="grid2"><label for="name">Foursquare Venue ID:</label></div>
                    <div class="grid10">
                            <input type="text" id="foursquare_venue_id" name="foursquare_venue_id" value="<?=@$event->foursquare_venue_id?>" />
                    </div>
                    <div class="clear"></div>
                </div>
                            
                
                <div class="formRow">
                    
                    <div class="grid2"><label for="name">Description</label></div>
                    
                    <div class="grid10">
                        <fieldset class="formpart">
                            <textarea id="description" name="description" rows="10" class="required">
                                    <?=CMS::strip_tags(@$event->description)?>
                            </textarea>
                        </fieldset>
                    </div>
                    <div class="clear"></div>
                    
                </div>
                            
                </fieldset>

		</div>
            
            
	

            
            
	</div>
    
    

	<div class="grid3"><!-- Column Right -->
		<div class="widget"><!-- Tags -->
			<div class="whead"><h6><span class="icon-tag"></span>Tags</h6><div class="clear"></div></div>
			<fieldset>
				<div class="formRow">
					<input type="text" id="tags" name="tags" class="tags" value="<?=@$event->tags?>"/>
				</div>
			</fieldset>
		</div>
        		
        <div class="widget"><!-- Image -->
			<div class="whead"><h6><span class="icon-picture"></span>Gallery</h6><div class="clear"></div></div>
			<fieldset>
				<div class="formRow check">
					<?php if(!empty($event->gallery)): ?>
						<div class="gallery-pagination">
							<a href="#" id="gallery-previous">&lt;</a>
							<span id="gallery-nav"></span>
							<a href="#" id="gallery-next">&gt;</a>
							</div>
						<div id="gallery">
						<?php foreach($event->gallery as $image) : ?>
							<div class="grid12">
								<?= Asset::img(Config::get('cms.event_images_path').DS.$image, array('id' => 'event-img-preview')); ?>
								<div class="remove-checkbox with-delete-link">
                                    
                                        <input type="checkbox" name="gallery_remove[]" value="<?=$image?>" /> 
                                        Use Default
                                    
                                    &nbsp;or&nbsp; 
                                    <?php if ($event->id): ?>
                                    <a id="delete_photos" href="<?=Uri::create("api/event/{$event->id}/delete_photos");?>?login_hash=<?php echo $login_hash; ?>">Delete Image</a>
                                    <?php else: ?>
                                    <a id="delete_photos" href="#">Delete Image</a>
                                    <?php endif; ?>
                                </div>
							</div>
						<?php endforeach; ?>
						</div>
                    <?php else: ?>
					    <img src="" id="event-img-preview"/>
                        <div class="remove-checkbox with-delete-link" style="display: none;" >
                            
                                <input type="checkbox" name="gallery_remove[]" value="" /> 
                                Use Default
                            
                            &nbsp;or&nbsp; 
                            <a id="delete_photos" href="#">Delete Image</a>
                        </div>
					<?php endif; ?>
					
                    <input type="hidden" name="deleted_image" id="deleted_image" value="0"/>
                    
                    <input type="hidden" name="x1_0" id="new-img-x1" />
                    <input type="hidden" name="y1_0" id="new-img-y1" />
                    <input type="hidden" name="x2_0" id="new-img-x2" />
                    <input type="hidden" name="y2_0" id="new-img-y2" />
                    <input type="hidden" name="preview_width_0" id="new-img-preview-width" />
                    <input type="hidden" name="preview_height_0" id="new-img-preview-height" />
					
					<div>
						<input type="file" name="gallery_add[]" />
					</div>
					<div class="clear"></div>
				</div>
			</fieldset>
		</div>
        
        <div class="widget"><!-- Publish -->
			<div class="whead">
				<h6><span class="icon-cog"></span>Publish</h6>
				<div class="clear"></div>
			</div>
			<?php if ($action == 'edit') : ?>
			<div class="formRow">
				<select name="status" class="fullwidth" data-placeholder="Status">
					<?php foreach(CMS::statuses(@$event->status) as $status) : ?>
					<option value="<?=$status->value?>" <?=@$status->selected?>><?=$status->label?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<?php endif; ?>
			<div class="formRow formpart">
				<button class="buttonL bGreyish fluid" type="submit"><?=($action == 'add') ? '<i class="iconb" data-icon="&#xe099;"></i> &nbsp; Add Event' : '<i class="iconb" data-icon="&#xe097;"></i> &nbsp; Save Event'?></button>
			</div>
	</div>
            
	</div>
	<div class="clear"></div>
</div>

</form>

<script type="text/javascript">

function add_hidden_input(location_id) {
	$('<input />', {
		type: 'hidden',
		id: 'location_id_' + location_id,
		value: location_id,
		name: 'location_ids[]'
	}).insertAfter('#action');
}

var autoSuggest = $("#location_search").autoSuggest("<?=Uri::create("api/locations")?>", {
	minChars: 3,
	queryParam: "string",
	extraParams: "&active_only=1&compact=1&order_by=simple_relevance&login_hash=<?=$login_hash?>",
	selectedItemProp: "name",
	selectedValuesProp: "value",
	searchObjProps: "name,address,city,st,zip,email,web,description",
	matchCase: false,
	resultClick: function(data) {
		add_hidden_input(data.attributes.id);
	},
	selectionAdded: function(elem, data){
		var checkbox;
    	if (data.type == 'Mall') {
    	    checkbox = $('<input />', {
    			type: 'checkbox',
    			value: '1',
    			name: 'include_merchants_' + data.id,
    			title: "Include all merchants within Marketplace"
		    });
    	} else if (data.type == 'Merchant') {
    	    checkbox = $('<input />', {
    			type: 'checkbox',
    			value: data.id,
    			"class": 'add_similar_merchants',
    			name: 'add_similar_merchants_' + data.id,
    			title: "Add all merchants with the same name and country"
    		});
    	}
    	if (checkbox) {
        	checkbox.appendTo(elem);
        	checkbox.tipsy();
    	}
	},
	selectionRemoved: function(elem, id) {
		elem.fadeTo("slow", 0, function() { elem.remove(); });
		$("#location_id_" + id).remove();
    },
	<?php if (@$event->get_location_ids() && count($event->get_location_ids()) > 0) : ?>
    preFill: <?=json_encode(CMS::locations_by_id($event->get_location_ids()));?>,
    <?php endif; ?>
    startText: 'Search location...',
    manualUpdateCallback: function(data) {
        add_hidden_input(data.id);
    }
});

$('#delete_photos').live('click', function(e) {
    e.preventDefault();

    if (jcrop_api) {
        jcrop_api.destroy();
    }

    var href = $(this).attr('href');
    if (href != '#') { 
        // Updating event
        $.post(href, {}, function(r) {
           if (r.data.status) {
               $('#event-img-preview').css({ height: 'auto', width: '100%'});
               $('#event-img-preview').addClass('no-crop').attr('src', r.data.default_image);
               $('#deleted_image').val(1);
           }
        }, 'json');
    } else {
        // Adding a new event
        $('#event-img-preview').css({ height: 'auto', width: '100%'});
        $('#event-img-preview').addClass('no-crop').attr('src', '<?= \Fuel\Core\Asset::get_file('default-logo.png', 'img'); ?>');
        $('input[type=file]').val(null);
        $('#deleted_image').val(1);
    }
});

function checkLocations(field, rules, i, options) {
	if ($('input[name="location_ids[]"]').length == 0) {
		return "Please select one or more locations";
	}
}

jQuery.validator.addMethod("locationsCheck", function(value, element) { 
    return $('#all_locations').is(':checked') || $('input[name="location_ids[]"]').length > 0; 
}, "Please select at least one location");
	
$(function() {
    $("#wizard1").validate({
        focusInvalid: false,
        invalidHandler: function(form, validator) {
            if (!validator.numberOfInvalids())
                return;
            $('html, body').animate({
                scrollTop: $(validator.errorList[0].element).offset().top
            }, 2000);
        }
    });

    $( "#searchContainer" ).on( "click", "input.add_similar_merchants", function() {
        $(this).prop("checked", false);

        var url = "<?=Uri::create("api/locations")?>/";
        var params = {
            login_hash: "<?=$login_hash?>",
            similar_to: $(this).val(),
            active_only: "1",
            compact: "1",
            pagination: "0"
        };
        // Make ajax call to fetch merchants with the same name
        var jqxhr = $.getJSON(url, params, function() {
        })
            .done(function(data) {
                autoSuggest.trigger("manualUpdate", {entries: data});
            })
            .fail(function() {
                alert("Error while fetching similar merchants");
            })
            .always(function() {
            });
	});

        tinyMCE.init({
            mode : "textareas",
            menubar:false,
            statusbar: false,
            theme : "modern",
            plugins : "searchreplace,paste,directionality,noneditable,visualchars,nonbreaking",

            theme_advanced_buttons1 : "bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
            theme_advanced_buttons2 : "cut,copy,paste,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink",
            theme_advanced_toolbar_location : "top",
            theme_advanced_toolbar_align : "left",
            theme_advanced_statusbar_location : "bottom",
            theme_advanced_resizing : true,

            //content_css : "css/example.css",
        });

});

var jcrop_api;

$('input[type="file"]').change(function(e) {
    $('#offer-img-preview').removeClass('no-crop');
    
    if (jcrop_api) {
        jcrop_api.destroy();
    }

	if (window.File && window.FileReader && window.FileList && window.Blob) {
	    e.preventDefault();
               
	    var file = $(this)[0].files[0];

        if (!file.type.match('image/.*')) {
            alert("Only image files allowed");
            return;
        }

        var reader = new FileReader();
    
        reader.onload = function (event) {
		    $('#event-img-preview').replaceWith($('<img/>', {
			    id: "event-img-preview",
			    src: event.target.result,
			    width: 200
		    }));
            
		    $('#event-img-preview').load(function() {
                $('.remove-checkbox.with-delete-link').show();
                
                if (!$(this).hasClass('no-crop')) {
                    $('#new-img-preview-height').val($('#event-img-preview').height());
                    $('#new-img-preview-width').val($('#event-img-preview').width());

                    initJCrop($('#event-img-preview'));
                }
		    });
        };

        reader.readAsDataURL(file);
	}
});

function showPreview(coords)
{
    if (parseInt(coords.w) > 0)
    {
    	$('#new-img-x1').val(coords.x);
    	$('#new-img-y1').val(coords.y);
    	$('#new-img-x2').val(coords.x2);
    	$('#new-img-y2').val(coords.y2);
    }
}

function hidePreview()
{
	$('#event_img_preview').stop().fadeOut('fast');
	$('#new-img-x1').val(0);
	$('#new-img-y1').val(0);
	$('#new-img-x2').val(0);
	$('#new-img-y2').val(0);
}

function initJCrop(selector) {
	var jcrop_options = {
        onChange: showPreview,
        onSelect: showPreview,
        onRelease: hidePreview,
        aspectRatio: 1,
        setSelect: [ 0, 0, 100, 100 ],
        allowSelect: false
    }
	
	selector.Jcrop(jcrop_options, function() {
	    jcrop_api = this;
	});
}

</script>
