<!-- Offer Info -->
<form id="wizard1" method="post" enctype="multipart/form-data">
<?=CMS::create_nonce_field('offer_'.$action, 'nonce')?>
<input type="hidden" id="action" name="action" value="<?=$action?>" />
<?php if (@$offer->location_ids) : ?>
    <?php foreach($offer->location_ids as $location_id): ?>
    <input type="hidden" id="location_id_<?=$location_id?>" name="location_ids[]" value="<?=$location_id?>" />
    <?php endforeach; ?>
<?php endif; ?>

<input type="hidden" name="force_top_message" value="<?=isset($offer->force_top_message)?$offer->force_top_message:0?>" />
<div class="fluid">
	<div class="grid9"><!-- Column Left -->
		<div class="widget"><!-- Offer Info -->
			<div class="whead">
				<h6><span class="icon-info-3"></span>Offer Info</h6>
				<div class="clear"></div>
			</div>

			<fieldset class="formpart">
				<div class="formRow">
					<div class="grid2"><label for="name">Offer Title:</label></div>
					<div class="grid10">
						<input type="text" id="name" name="name" placeholder="What's the offer name?" value="<?=@$offer->name?>" class="required" />
					</div>
					<div class="clear"></div>
				</div>
                <div class="formRow">
					<div class="grid2"><label for="email">Display dates in the app?</label></div>
					<div class="grid10">
						<input type="checkbox" class="filter" id="show_dates" name="show_dates" value="1" <?=(@$offer->show_dates) ? 'checked="checked"' : '' ?> />
						<div class="clear"></div>
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow">
					<div class="grid2"><label for="email">Start Date - Time:</label></div>
					<div class="grid10">
						<span class="floatL mr5"><input type="text" class="datepicker required" id="date_start" name="date_start[]" data-max="#date_end" placeholder="date" value="<?=(@$offer->date_start) ? date('m/d/Y', $offer->date_start) : ''?>" /></span>
						<span class="floatL mr5 mt4 text-center">&ndash;</span>
						<span class="floatL"><input type="text" class="timepicker" name="date_start[]" placeholder="time" style="width: 70px !important;" value="<?=(@$offer->date_start) ? date('h:iA', $offer->date_start) : ''?>" /></span>
						<div class="clear"></div>
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow">
					<div class="grid2"><label for="email">End Date - Time:</label></div>
					<div class="grid10">
						<span class="floatL mr5"><input type="text" class="datepicker required" id="date_end" name="date_end[]" data-min="#date_start" placeholder="date" value="<?=(@$offer->date_end) ? date('m/d/Y', $offer->date_end) : ''?>" /></span>
						<span class="floatL mr5 mt4 text-center">&ndash;</span>
						<span class="floatL"><input type="text" class="timepicker" name="date_end[]" placeholder="time" style="width: 70px !important;" value="<?=(@$offer->date_end) ? date('h:iA', $offer->date_end) : ''?>" /></span>
						<div class="clear"></div>
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow">
					<div class="grid2">Location:</div>
					<div class="grid10 searchDrop" id="searchContainer">
			            <input type="text" id="location_search" name="location_search" class="locationsCheck" />
					</div>
					<div class="clear"></div>
				</div>
				<?php if (!$me->is_admin()): ?>
                <div class="formRow">
					<div class="grid6">
						<label>
							<input type="checkbox" class="filter" id="all_locations" name="all_locations" value="1" <?=(@$offer->all_locations) ? 'checked="checked"' : ''?> />
							Include all locations</label>
					</div>
					<div class="clear"></div>
				</div>
                <?php endif; ?>
                
                <?php if ($me->is_admin()): ?>
                <div class="formRow">
                    <div class="grid2">Reward for campaign:</div>
					<div class="grid10">
                        <select name="reward_for" id="reward_for" class="fullwidth">
                            <option value="0">--- None ---</option>
                            <?php foreach(CMS::contests() as $contest) : ?>
                            <option <?php if (isset($offer->id) && ($reward = Model_Offer::find($offer->id)->reward) && ($reward->contest->id == $contest->id)) echo "selected"?> value="<?=$contest->id?>"><?=$contest->name?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="formRow">
                    <div class="grid2">Is grand prize?</div>
					<div class="grid10">
                        <input type="checkbox" name="grand_prize" value="1" <?php if (isset($offer->id) && ($reward = Model_Offer::find($offer->id)->reward) && ($reward->grand_prize)) echo "checked"?>>
                    </div>
                    <div class="clear"></div>
                </div>
				<?php endif; ?>
				
				<div class="formRow">
					<div class="grid2">The offer is redeemable?</div>
                                        
                                        <div class="grid10">
                                                <input type="checkbox" class="filter" id="redeemable" name="redeemable" value="1" <?=(@$offer->redeemable) ? 'checked="checked"' : ''?> />
					</div>
                                        
					<div class="clear"></div>
				</div>
						<input type="hidden" id="allowed_redeems" name="allowed_redeems" value="<?=@$offer->allowed_redeems?>" />
				<div id="redeemable_info">
    				<div class="formRow">
    					<div class="grid8">
    						<label>
    							<input type="checkbox" class="filter" id="multiple_codes" name="multiple_codes" value="1" <?=(@$offer->multiple_codes) ? 'checked="checked"' : ''?> />
    							A new unique code should be generated for each redeem?</label>
    					</div>
    					<div class="clear"></div>
    				</div>
				    <div id="single_code_info">
        				<div class="formRow">
        					<div class="grid3"><label for="offer_code_type">Unique Code type:</label></div>
        					<div class="grid9 searchDrop">
        						<select name="offer_code_type" id="offer_code_type" class="fullwidth" data-placeholder="Type">
        							<option></option>
        							<?php foreach(CMS::code_types(@$offer_code_type) as $type) : ?>
        							<option value="<?=$type->type?>" <?php if ($type->selected): ?>selected="selected"<?php endif; ?>><?=$type->label?></option>
        							<?php endforeach; ?>
        						</select>
        					</div>
        					<div class="clear"></div>
        				</div>
				        <div class="formRow">
        					<div class="grid2"><label for="offer_code">Unique Code:</label></div>
        					<div class="grid10">
        						<input type="text" id="offer_code" name="offer_code" placeholder="What's the unique code for this offer?" value="<?=@$offer_code?>" />
        					</div>
        					<div class="clear"></div>
        				</div>
    				</div>
    				<div id="multiple_codes_info">
        				<div class="formRow">
        					<div class="grid4"><label for="default_code_type">Auto generated codes type:</label></div>
        					<div class="grid8 searchDrop">
        						<select name="default_code_type" id="default_code_type" class="fullwidth" data-placeholder="Type">
        							<option></option>
        							<?php foreach(CMS::code_types(@$offer->default_code_type) as $type) : ?>
        							<option value="<?=$type->type?>" <?php if ($type->selected): ?>selected="selected"<?php endif; ?>><?=$type->label?></option>
        							<?php endforeach; ?>
        						</select>
        					</div>
        					<div class="clear"></div>
        				</div>
                    </div>				
				</div>		
                                                
                                                <div class="formRow" style="height:262px;">
                    <div class="grid2">Description</div>
                    <div class="grid10">
                        <textarea id="description" name="description" rows="10" class="required">
                            <?=CMS::strip_tags(@$offer->description)?>
                        </textarea>
                    </div>
			
			</fieldset>
                    </div>
		</div>
    	<input id="formChanged" type="hidden" value="0" />
    	<input id="duplicateOffer" name="duplicateOffer" type="hidden" value="0" />


	<div class="grid3"><!-- Column Right -->
		<div class="widget" style="display: none;"><!-- Pricing -->
			<div class="whead"><h6><span class="icon-basket"></span>Pricing</h6><div class="clear"></div></div>
			<fieldset>
				<div class="formRow">
					<div><label for="price_regular">Regular Price</label></div>
					<div><input type="number" class="number" id="price_regular" name="price_regular" value="<?=@$offer->price_regular?>" data-decimal="2" data-step="0.01" /></div>
				</div>
				<div class="formRow">
					<div><label for="savings">Savings</label></div>
					<div><input type="text" id="savings" name="savings" value="<?=@$offer->savings?>" placeholder="amt or %"  /></div>
				</div>
				<div class="formRow">
					<div><label for="price_offer">Offer Price</label></div>
					<div><input type="text" id="price_offer" name="price_offer" value="<?=@$offer->price_offer?>" readonly="readonly" /></div>
				</div>
			</fieldset>
		</div>
		<div class="widget"><!-- Tags -->
			<div class="whead"><h6><span class="icon-tag"></span>Tags</h6><div class="clear"></div></div>
			<fieldset>
				<div class="formRow">
					<input type="text" id="tags" name="tags" class="tags" value="<?=@$offer->tags?>" />
				</div>
			</fieldset>
		</div>
		<div class="widget"><!-- Image -->
			<div class="whead"><h6><span class="icon-picture"></span>Gallery</h6><div class="clear"></div></div>
			<fieldset>
				<div class="formRow check">
					<?php if(!empty($offer->gallery)): ?>
						<div class="gallery-pagination">
							<a href="#" id="gallery-previous">&lt;</a>
							<span id="gallery-nav"></span>
							<a href="#" id="gallery-next">&gt;</a>
						</div>
						<div id="gallery">
						<?php foreach($offer->gallery as $image) : ?>
							<div class="grid12">
                                                            <?php 
                                                            if(filter_var($image, FILTER_VALIDATE_URL)){ 
                                                            ?>
                                                                <?= Asset::img($image, array('id' => 'offer-img-preview')); ?>
                                                            <?php 
                                                            }else{
                                                            ?>
                                                                <?= Asset::img(Config::get('cms.offer_images_path').DS.$image, array('id' => 'offer-img-preview')); ?>
                                                            <?php 
                                                            }
                                                            ?>
								
                                                            <div class="remove-checkbox with-delete-link" >
                                    
                                        <input type="checkbox" name="gallery_remove[]" value="<?=$image?>" /> 
                                        Use Default
                                    
                                    &nbsp;or&nbsp; 
                                    <?php if (isset($offer->id) && !empty($offer->id)): ?>
                                    <a id="delete_photos" href="<?=Uri::create("api/offer/{$offer->id}/delete_photos");?>?login_hash=<?php echo $login_hash; ?>">Delete Image</a>
                                    <?php else: ?>
                                    <a id="delete_photos" href="#">Delete Image</a>
                                    <?php endif; ?>
                                </div>
							</div>
						<?php endforeach; ?>
						</div>
					<?php else: ?>
					    <img src="" id="offer-img-preview"/>
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
					<option></option>
					<?php foreach(CMS::statuses(@$offer->status) as $status) : ?>
					<option value="<?=$status->value?>" <?=@$status->selected?>><?=$status->label?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<?php endif; ?>
			<div class="formRow">
				<button class="buttonL bGreyish fluid" type="submit"><?=($action == 'add') ? '<i class="iconb" data-icon="&#xe099;"></i> &nbsp; Add Offer' : '<i class="iconb" data-icon="&#xe097;"></i> &nbsp; Save Offer'?></button>
			</div>
		</div>
	</div>
	<div class="clear"></div>


</div>

</form>

<script type="text/javascript">

$("#redeemable").change(function() {
    if ($("#redeemable").is(':checked')) {
    	$("#redeemable_info").show();
    } else {
    	$("#redeemable_info").hide();
    }
});

$("#multiple_codes").change(function() {
    if ($("#multiple_codes").is(':checked')) {
    	$("#multiple_codes_info").show();
    	$("#single_code_info").hide();
    	$("#allowed_redeems").val(1);
    } else {
    	$("#multiple_codes_info").hide();
    	$("#single_code_info").show();
    	$("#allowed_redeems").val(0);
    }
});

$("#offer_code_type").change(function() {
	var code = null;
    if ($("#offer_code_type").val() == "<?=Model_Offer_Code::EAN13_TYPE?>") {
        code = "<?=$ean13_code?>";
    } else if ($("#offer_code_type").val() == "<?=Model_Offer_Code::QR_CODE_TYPE?>") {
        code = "<?=$qr_code?>";
    } else {
        code = "<?=$code_128?>";
    }
    $("#offer_code").val(code);
});

function add_hidden_input(location_id) {
	$('<input />', {
		type: 'hidden',
		id: 'location_id_' + location_id,
		value: location_id,
		name: 'location_ids[]'
	}).insertAfter('#action');
}

$(function() {
    $("#redeemable").change();
    $("#multiple_codes").change();

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

	$('div.grid12').css('position', '');

    $("#wizard1").one("change", ":input", function() {
        $('#formChanged').val(1);
    });
});

var posted_info = <?=json_encode($offer)?>;

var autoSuggest = $("#location_search").autoSuggest("<?=Uri::create("api/locations")?>", {
	minChars: 3,
	queryParam: "string",
	extraParams: "&active_only=1&compact=1&order_by=simple_relevance&login_hash=<?=$login_hash?>",
	selectedItemProp: "name",
	selectedValuesProp: "value",
	searchObjProps: "name,address,city,st,zip,email,web",
	matchCase: false,
	resultClick: function(data) {
		add_hidden_input(data.attributes.id);
	},
	selectionAdded: function(elem, data){
		var checkbox;
    	if (data.type == 'Mall') {
        	var field_name = 'include_merchants_' + data.id;
    	    checkbox = $('<input />', {
    			type: 'checkbox',
    			value: '1',
    			name: field_name,
    			title: "Include all merchants within Marketplace",
    			checked: posted_info[field_name] && posted_info[field_name] == "1"
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
	<?php if (@$offer->location_ids && count($offer->location_ids) > 0) : ?>
    preFill: <?=json_encode(CMS::locations_by_id($offer->location_ids));?>,
    <?php endif; ?>
    startText: 'Search location...',
    manualUpdateCallback: function(data) {
        add_hidden_input(data.id);
    }
});

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

$('#delete_photos').live('click', function(e) {
    e.preventDefault();

    if (jcrop_api) {
        jcrop_api.destroy();
    }

    var href = $(this).attr('href');
    if (href != '#') { 
        // Updating offer
        $.post(href, {}, function(r) {
           if (r.data.status) {
               $('#offer-img-preview').css({ height: 'auto', width: '100%'});
               $('#offer-img-preview').addClass('no-crop').attr('src', r.data.default_image);
               $('#deleted_image').val(1);
           }
        }, 'json');
    } else {
        // Adding a new offer
        $('#offer-img-preview').css({ height: 'auto', width: '100%'});
        $('#offer-img-preview').addClass('no-crop').attr('src', '<?= \Fuel\Core\Asset::get_file('default-logo.png', 'img'); ?>');
        $('input[type=file]').val(null);
        $('#deleted_image').val(1);
    }
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
		    $('#offer-img-preview').replaceWith($('<img/>', {
			    id: "offer-img-preview",
			    src: event.target.result,
			    width: 200
		    }));
            
		    $('#offer-img-preview').load(function() {
                $('.remove-checkbox.with-delete-link').show();
                
                if (!$(this).hasClass('no-crop')) {
                    $('#deleted_image').val(0);
                    $('#new-img-preview-height').val($('#offer-img-preview').height());
                    $('#new-img-preview-width').val($('#offer-img-preview').width());

                    initJCrop($('#offer-img-preview'));
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
	$('#offer_img_preview').stop().fadeOut('fast');
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
    };
	
	selector.Jcrop(jcrop_options, function() {
	    jcrop_api = this;
	});
}

</script>
