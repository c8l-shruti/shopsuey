<!-- Event Info -->
<form id="wizard1" method="post" enctype="multipart/form-data">
<?=CMS::create_nonce_field('event_'.$action, 'nonce')?>
<input type="hidden" id="action" name="action" value="<?=$action?>" />
<input type="hidden" id="logo" name="logo" value="<?=@$event->logo?>" />
<input type="hidden" id="landing_screen_img" name="landing_screen_img" value="<?=@$event->landing_screen_img?>" />
<input type="hidden" id="main_location_id" name="main_location_id" value="<?=$event->main_location_id?>" />
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
						<input type="text" id="title" name="title" placeholder="My awesome event" value="<?=@$event->title?>" class="required" />
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
						<span class="floatL mr5"><input type="text" class="datepicker" id="date_start" name="date_start[]" data-max="#date_end" placeholder="date" value="<?=(@$event->date_start) ? date('m/d/Y', strtotime(@$event->date_start)) : ''?>" /></span>
						<span class="floatL mr5 mt4 text-center">&ndash;</span>
						<span class="floatL"><input type="text" class="timepicker" name="date_start[]" placeholder="time" style="width: 70px !important;" value="<?=(@$event->date_start) ? date('h:iA', strtotime(@$event->date_start)) : ''?>" /></span>
						<div class="clear"></div>
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow">
					<div class="grid2"><label for="email">End Date - Time:</label></div>
					<div class="grid10">
						<span class="floatL mr5"><input type="text" class="datepicker" id="date_end" name="date_end[]" data-min="#date_start" placeholder="date" value="<?=(@$event->date_end) ? date('m/d/Y', strtotime(@$event->date_end)) : ''?>" /></span>
						<span class="floatL mr5 mt4 text-center">&ndash;</span>
						<span class="floatL"><input type="text" class="timepicker" name="date_end[]" placeholder="time" style="width: 70px !important;" value="<?=(@$event->date_end) ? date('h:iA', strtotime(@$event->date_end)) : ''?>" /></span>
						<div class="clear"></div>
					</div>
					<div class="clear"></div>
				</div>

                <div class="formRow">
					<div class="grid2"><label for="main_location_search">Main Location:</label></div>
					<div class="grid10">
						<input id="main_location_search" name="main_location_search" class="mainLocationCheck" />
					</div>
					<div class="clear"></div>
				</div>

				<div class="formRow" id="location_selector_wrapper">
					<div class="grid2">Locations:</div>
					<div class="grid10">
					    <input type="text" id="location_search" name="location_search" class="locationsCheck" />
					</div>
					<div class="clear"></div>
				</div>
                
                <div class="formRow">
					<div class="grid2"><label for="name">Coordinator phone number:</label></div>
					<div class="grid10">
						<input type="text" id="coordinator_phone" name="coordinator_phone" value="<?=@$event->coordinator_phone?>" />
					</div>
					<div class="clear"></div>
				</div>
                
                <div class="formRow">
					<div class="grid2"><label for="name">Coordinator email address:</label></div>
					<div class="grid10">
						<input type="text" id="coordinator_email" name="coordinator_email" value="<?=@$event->coordinator_email?>" />
					</div>
					<div class="clear"></div>
				</div>
                
				<div class="formRow">
					<div class="grid2"><label for="name">Event website:</label></div>
					<div class="grid10">
						<input type="text" id="website" name="website" value="<?=@$event->website?>" />
					</div>
					<div class="clear"></div>
				</div>
                
			</fieldset>

		</div>
		<div class="widget"><!-- Description -->
			<div class="whead">
				<h6><span class="icon-document"></span>Description</h6>
				<div class="clear"></div>
			</div>

			<fieldset class="formpart">
				<div class="formRow">
					<textarea name="description" rows="10"><?=strip_tags(@$event->description)?></textarea>
				</div>
			</fieldset>
		</div>

        <div class="widget">
			<div class="whead">
				<h6><span class="icon-share-3"></span>Social Media</h6>
				<div class="clear"></div>
			</div>
			<fieldset class="formpart">
				<div class="formRow">
					<div class="grid4"><label for="social_facebook">Facebook Page URL:</label></div>
					<div class="grid8">
						<input type="url" id="social_facebook" name="social[facebook]" value="<?=@$event->social->facebook?>" />
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow">
					<div class="grid4"><label for="social_foursqare">Foursquare:</label></div>
					<div class="grid8">
						<input type="text" id="social_foursqare" name="social[foursquare]" value="<?=@$event->social->foursquare?>" />
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow">
					<div class="grid4"><label for="social_pintrest">Pinterest:</label></div>
					<div class="grid8">
						<input type="text" id="social_pintrest" name="social[pintrest]" value="<?=@$event->social->pintrest?>" />
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow">
					<div class="grid4"><label for="social_twitter">Twitter:</label></div>
					<div class="grid8">
						<input type="text" id="social_twitter" name="social[twitter]" value="<?=@$event->social->twitter?>" />
					</div>
					<div class="clear"></div>
				</div>

			</fieldset>
		</div>
	</div>
    
    

	<div class="grid3"><!-- Column Right -->
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
		<div class="widget"><!-- Tags -->
			<div class="whead"><h6><span class="icon-tag"></span>Tags</h6><div class="clear"></div></div>
			<fieldset>
				<div class="formRow">
					<input type="text" id="tags" name="tags" class="tags" value="<?=@$event->tags?>" />
				</div>
			</fieldset>
		</div>
        		
        <div class="widget">
			<div class="whead"><h6><span class="icon-picture"></span>Logo</h6><div class="clear"></div></div>
			<fieldset>
				<div class="formRow check">
				    <div id="logo-image" class="grid12">
					<?php if (@$event->logo) : ?>
                        <?= Asset::img(Config::get('cms.event_images_path').DS.'small_'.$event->logo, array('id' => 'logo-img-preview')); ?>
                    <?php else: ?>
                        <img alt="" src="" id="logo-img-preview" />
					<?php endif; ?>
					</div>
					<div class="grid12">
					    <input type="file" name="logo" />
					</div>
					<div class="clear"></div>
                    <input type="hidden" name="x1_logo" id="logo-img-x1" />
                    <input type="hidden" name="y1_logo" id="logo-img-y1" />
                    <input type="hidden" name="x2_logo" id="logo-img-x2" />
                    <input type="hidden" name="y2_logo" id="logo-img-y2" />
                    <input type="hidden" name="preview_width_logo" id="logo-img-preview-width" />
                    <input type="hidden" name="preview_height_logo" id="logo-img-preview-height" />
				</div>
			</fieldset>
			<div id="logo-list">
    			<ul class="image-list">
    			</ul>
			</div>
		</div>
        <div class="widget"><!-- landing screen image -->
			<div class="whead"><h6><span class="icon-picture"></span>Landing Screen Image</h6><div class="clear"></div></div>
			<fieldset>
				<div class="formRow check">
				    <div id="landing-image" class="grid12">
					<?php if (@$event->landing_screen_img) : ?>
                        <?= Asset::img(Config::get('cms.event_images_path').DS.'small_'.$event->landing_screen_img, array('id' => 'landing-img-preview')); ?>
                    <?php else: ?>
                        <img alt="" src="" id="landing-img-preview" />
					<?php endif; ?>
					</div>
					<div class="grid12">
					    <input type="file" name="landing" />
					</div>
					<div class="clear"></div>
                    <input type="hidden" name="x1_landing" id="landing-img-x1" />
                    <input type="hidden" name="y1_landing" id="landing-img-y1" />
                    <input type="hidden" name="x2_landing" id="landing-img-x2" />
                    <input type="hidden" name="y2_landing" id="landing-img-y2" />
                    <input type="hidden" name="preview_width_landing" id="landing-img-preview-width" />
                    <input type="hidden" name="preview_height_landing" id="landing-img-preview-height" />
				</div>
			</fieldset>
			<div id="img-list">
    			<ul class="image-list">
    			</ul>
			</div>
		</div>
        
	</div>
</div>

</form>

<script type="text/javascript">

$("#main_location_search").autoSuggest("<?=Uri::create("api/locations")?>", {
    minChars: 3,
    queryParam: "string",
    extraParams: "&active_only=1&compact=1&order_by=simple_relevance&login_hash=<?=$login_hash?>",
    selectedItemProp: "name",
    selectedValuesProp: "value",
    searchObjProps: "name,address,city,st,zip,email,web,description",
    matchCase: false,
    selectionLimit: 1,
    resultClick: function(data) {
        $("#main_location_id").val(data.attributes.id).change();
    },
    selectionRemoved: function(elem) {
        elem.fadeTo("slow", 0, function() { elem.remove(); });
        $("#main_location_id").val('').change();
    },
    startText: 'Search Location...',
    <?php if ($event->main_location_id): ?>
    preFill: <?=json_encode(CMS::locations_by_id(array($event->main_location_id)));?>,
    <?php endif; ?>
    limitText: 'Only one main location is allowed'
});

$("#location_search").autoSuggest("<?=Uri::create("api/locations")?>", {
	minChars: 3,
	queryParam: "string",
	extraParams: "&active_only=1&compact=1&order_by=simple_relevance&login_hash=<?=$login_hash?>",
	selectedItemProp: "name",
	selectedValuesProp: "value",
	searchObjProps: "name,address,city,st,zip,email,web,description",
	matchCase: false,
	resultClick: function(data) {
		$('<input />', {
			type: 'hidden',
			id: 'location_id_' + data.attributes.id,
			value: data.attributes.id,
			name: 'location_ids[]'
		}).insertAfter('#action');
	},
	selectionRemoved: function(elem, id) {
		elem.fadeTo("slow", 0, function() { elem.remove(); });
		$("#location_id_" + id).remove();
    },
	<?php if (@$event->get_location_ids() && count($event->get_location_ids()) > 0) : ?>
    preFill: <?=json_encode(CMS::locations_by_id($event->get_location_ids()));?>,
    <?php endif; ?>
    startText: 'Search location...'
});

function checkLocations(field, rules, i, options) {
	if ($('input[name="location_ids[]"]').length == 0) {
		return "Please select one or more locations";
	}
}

jQuery.validator.addMethod("mainLocationCheck", function(value, element) {
    return $('input[name="main_location_id"]').val();
}, "Please select a main location");

jQuery.validator.addMethod("locationsCheck", function(value, element) { 
    return $('input[name="location_ids[]"]').length > 0; 
}, "Please select at least one location");
	
$(function() {
    $("#wizard1").validate();
});

var jcrop_api = {
	"logo": null,
	"landing": null
}

$('input[type="file"]').change(function(e) {

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

        reader.onload = function (event) {
		    $('#' + name + '-img-preview').replaceWith($('<img/>', {
			    id: name + "-img-preview",
			    src: event.target.result,
			    width: 200
		    }));

		    $('#' + name + '-img-preview').load(function() {

		        $('#' + name + '-img-preview-height').val($('#' + name + '-img-preview').height());
		        $('#' + name + '-img-preview-width').val($('#' + name + '-img-preview').width());

		        initJCrop($('#' + name + '-img-preview'), name);
		    });
        };

        reader.readAsDataURL(file);
	}
});

function getShowPreviewFunction(name) {
	return function(coords) {
	    if (parseInt(coords.w) > 0)
	    {
	    	$('#' + name + '-img-x1').val(coords.x);
	    	$('#' + name + '-img-y1').val(coords.y);
	    	$('#' + name + '-img-x2').val(coords.x2);
	    	$('#' + name + '-img-y2').val(coords.y2);
	    }
	};
}

function getHidePreviewFunction(name) {
	return function() {
		$('#' + name + '-img-preview').stop().fadeOut('fast');
		$('#' + name + '-img-x1').val(0);
		$('#' + name + '-img-y1').val(0);
		$('#' + name + '-img-x2').val(0);
		$('#' + name + '-img-y2').val(0);
	};
}

function initJCrop(selector, name) {
	var jcrop_options = {
        onChange: getShowPreviewFunction(name),
        onSelect: getShowPreviewFunction(name),
        onRelease: getHidePreviewFunction(name),
        aspectRatio: name == "logo" ? 1 : 320 / 235,
        setSelect: [ 0, 0, 100, 100 ],
    }

	selector.Jcrop(jcrop_options, function() {
	    jcrop_api[name] = this;
	});
}

</script>
