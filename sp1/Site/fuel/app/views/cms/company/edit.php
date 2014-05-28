<form id="company" method="POST">

<div class="fluid">
	<div class="grid7">
		<div class="widget"><!-- Mall Info -->
			<div class="whead">
				<h6><span class="icon-info-3"></span>Company Info</h6>
				<div class="clear"></div>
			</div>

			<fieldset class="formpart">
				<div class="formRow">
					<div class="grid3"><label for="mname">Name:</label></div>
					<div class="grid9">
						<input type="text" id="mname" name="name" placeholder="ABC Properties LLC" value="<?=@$location->name?>" class="validate[required]" />
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow">
					<div class="grid3"><label for="web">Website:</label></div>
					<div class="grid9">
						<input type="text" id="web" name="web" placeholder="http://www.abcproperties.com" value="<?=@$location->web?>" class="validate[required]" />
					</div>
					<div class="clear"></div>
				</div>
			</fieldset>
		</div>

		<div class="widget"><!-- Description -->
			<div class="whead">
				<h6><span class="icon-document"></span>Manager Info</h6>
				<div class="clear"></div>
			</div>
		
				<div class="formRow">
					<div class="grid3"><label for="contact">Name:</label></div>
					<div class="grid9">
						<input type="text" id="contact" name="contact" placeholder="Juan Julio" value="<?=@$location->contact?>" />
					</div>
					<div class="clear"></div>
				</div>

				<div class="formRow">
					<div class="grid3"><label for="phone">Phone:</label></div>
					<div class="grid9">
						<input type="phone" id="phone" name="phone" placeholder="888-555-1234 ext 567" value="<?=@$location->phone?>" class="validate[required,custom[phone]]" />
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow">
					<div class="grid3"><label for="email">Email:</label></div>
					<div class="grid9">
						<input type="email" id="email" name="email" placeholder="juan@abcproperties.com" value="<?=@$location->email?>" class="validate[custom[email]]" />
					</div>
					<div class="clear"></div>
				</div>

			</fieldset>
		</div>

		<div class="widget"><!-- Hours -->
			<div class="whead"><h6><span class="icon-history-2"></span>Hours</h6><div class="clear"></div></div>
			<fieldset>
                <?php for ($i = 0; $i < 3; $i++) { ?>
				<div class="formRow">
                    <div style="float: left">
                        <input class="day_checkbox" type="checkbox" name="hours[<?=$i?>][sun]" <?= in_array('sun', $hours[$i]['days']) ? 'checked' : '' ?>/> S&nbsp;&nbsp;
                        <input class="day_checkbox" type="checkbox" name="hours[<?=$i?>][mon]" <?= in_array('mon', $hours[$i]['days']) ? 'checked' : '' ?>/> M&nbsp;&nbsp;
                        <input class="day_checkbox" type="checkbox" name="hours[<?=$i?>][tue]" <?= in_array('tue', $hours[$i]['days']) ? 'checked' : '' ?>/> T&nbsp;&nbsp;
                        <input class="day_checkbox" type="checkbox" name="hours[<?=$i?>][wed]" <?= in_array('wed', $hours[$i]['days']) ? 'checked' : '' ?>/> W&nbsp;&nbsp;
                        <input class="day_checkbox" type="checkbox" name="hours[<?=$i?>][thr]" <?= in_array('thr', $hours[$i]['days']) ? 'checked' : '' ?>/> T&nbsp;&nbsp;
                        <input class="day_checkbox" type="checkbox" name="hours[<?=$i?>][fri]" <?= in_array('fri', $hours[$i]['days']) ? 'checked' : '' ?>/> F&nbsp;&nbsp;
                        <input class="day_checkbox" type="checkbox" name="hours[<?=$i?>][sat]" <?= in_array('sat', $hours[$i]['days']) ? 'checked' : '' ?>/> S&nbsp;&nbsp;
                    </div>
                    <div style="float: left; margin-left:20px">
						<span class="floatL mr5"><input type="text" name="hours[<?=$i?>][open]" value="<?=$hours[$i]['open']?>" class="timepicker" /></span>
						<span class="floatL mr5 inline-label">&ndash;</span>
						<span class="floatL"><input type="text" name="hours[<?=$i?>][close]" value="<?=$hours[$i]['close']?>" class="timepicker" /></span>
						<div class="clear"></div>
					</div>
					<div class="clear"></div>
				</div>
                <?php } ?>
			</fieldset>
            <input type="hidden" id="inherited_hours" name="inherited_hours" value="<?php echo (isset($location->hours_inherited_from_mall)) ? 1 : 0; ?>" />
            <?php if (isset($location->hours_inherited_from_mall)): ?>
            <div class="inherited_hours_from_mall">
                The hours of operation were not explicitly set for your store so we inherited the hours from the marketplace for which your store resides. 
                Simply edit your hours if they are not the same as your marketplace.
            </div>
            <?php endif; ?>
		</div>

	</div>
	
	<div class="grid5">
			<div class="widget"><!-- Address Info -->
			<div class="whead">
				<h6><span class="icon-home"></span>Address Info</h6>
				<div class="clear"></div>
			</div>

			<fieldset class="formpart">
				<div class="formRow">
					<div class="grid3"><label for="address">Address:</label></div>
					<div class="grid9">
						<input type="text" id="address" name="address" placeholder="123 Sesame Str." value="<?=@$location->address?>" class="validate[required]" />
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow">
					<div class="grid3"><label for="city">City:</label></div>
					<div class="grid9">
						<input type="text" id="city" name="city" placeholder="New York City" value="<?=@$location->city?>" class="validate[required]" />
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow">
					<div class="grid3"><label for="address">ST:</label></div>
					<div class="grid9">
						<input type="text" id="st" name="st" placeholder="NY" size="2" maxlength="2" value="<?=@$location->st?>" class="validate[required]" />
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow">
					<div class="grid3"><label for="country_id">Country:</label></div>
			        <div class="searchDrop grid9">
    					<select id="country" class="fullwidth" data-placeholder="Select your country" name="country_id[]" class="validate[required]">
                            <option></option>
    						<?php foreach(CMS::countries() as $country) : ?>
    						<option value="<?=$country->id?>" <?=$country->id == @$location->country_id ? 'selected="selected"' : '' ?>><?=$country->name?> (<?=$country->code?>)</option>
    						<?php endforeach; ?>
    					</select>
				    </div>
					<div class="clear"></div>
			    </div>
				<div class="formRow">
					<div class="grid3"><label for="zip">Zip:</label></div>
					<div class="grid9">
						<input type="number" id="zip" name="zip" placeholder="10458" value="<?=@$location->zip?>" class="validate[required]" />
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow">
					<div class="grid3"><label>Timezone:</label></div>
					<div class="grid9">
                        <select id="timezone" name="timezone" class="fullwidth" data-placeholder="Select your timezone...">
                            <option value=""></option>
                            <?php foreach ($timezones as $timezone): ?>
                            <option value="<?= $timezone; ?>" <?php if (@$location->timezone && $timezone == @$location->timezone): ?>selected="selected"<?php endif; ?>><?= $timezone; ?></option>
                            <?php endforeach; ?>
                        </select>
					</div>
					<div class="clear"></div>
				</div>

                <?php if ($location->type == Model_Location::TYPE_MERCHANT && is_null($location->mall_id)): ?>
                <input type="hidden" id="stand_alone_merchant"/>
                <div class="formRow">
					<div class="grid3"><label for="address">Latitude:</label></div>
					<div class="grid9">
						<input readonly="readonly" type="text" id="latitude" name="latitude" placeholder="" value="<?=@$location->latitude?>" />
					</div>
					<div class="clear"></div>
				</div>
                <div class="formRow">
					<div class="grid3"><label for="address">Longitude:</label></div>
					<div class="grid9">
						<input readonly="readonly" type="text" id="longitude" name="longitude" placeholder="" value="<?=@$location->longitude?>" />
					</div>
					<div class="clear"></div>
				</div>
                <?php endif; ?>
			</fieldset>
		</div>
	
		<div class="widget"><!-- Tags -->
			<div class="whead"><h6><span class="icon-tag"></span>Category (Pick up to three)</h6><div class="clear"></div></div>
			<fieldset>
				<div class="formRow">
			        <div class="searchDrop">
    					<select class="fullwidth" data-placeholder="Select..." name="category_ids[]" multiple="multiple">
    						<option></option>
    						<?php foreach(CMS::categories() as $category) : ?>
    						<option value="<?=$category->id?>" <?=(@in_array($category->id, @$location->category_ids)) ? 'selected="selected"' : '' ?>><?=$category->name?></option>
    						<?php endforeach; ?>
    					</select>
				    </div>
				</div>
			</fieldset>
		</div>
		
				<div class="widget"><!-- Social Media -->
			<div class="whead">
				<h6><span class="icon-share-3"></span>Social Media</h6>
				<div class="clear"></div>
			</div>
			<fieldset class="formpart">
				<div class="formRow">
					<div class="grid4"><label for="social_facebook">Facebook:</label></div>
					<div class="grid8">
						<input type="text" id="social_facebook" name="social[facebook]" value="<?=@$location->social->facebook?>" class="validate[custom[facebookUrl]]" placeholder="https://www.facebook.com/GetShopSuey" />
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow">
					<div class="grid4"><label for="social_foursquare">Foursquare:</label></div>
					<div class="grid8">
						<input type="text" id="social_foursquare" name="social[foursquare]" value="<?=@$location->social->foursquare?>" class="validate[custom[foursquareUrl]]" placeholder="https://foursquare.com/v/goodwill/4d195c93b15c5bc221" />
					</div>
					<div class="clear"></div>
				</div>
				<!-- div class="formRow">
					<div class="grid4"><label for="social_pintrest">Pinterest:</label></div>
					<div class="grid8">
						<input type="text" id="social_pintrest" name="social[pintrest]" value="<?=@$location->social->pintrest?>" />
					</div>
					<div class="clear"></div>
				</div-->
				<div class="formRow">
					<div class="grid4"><label for="social_twitter">Twitter:</label></div>
					<div class="grid8">
						<input type="text" id="social_twitter" name="social[twitter]" value="<?=@$location->social->twitter?>" class="validate[custom[twitterId]]" placeholder="@shopsueyapp" />
					</div>
					<div class="clear"></div>
				</div>
                <div class="formRow">
					<div class="grid7"><label for="default_social">Default Social Fields:</label></div>
					<div class="grid5">
						<input type="checkbox" id="default_social" name="default_social" value="1" <?=(@$location->default_social) ? 'checked="checked"' : ''?> />
					</div>
					<div class="clear"></div>
				</div>
			</fieldset>
		</div>
		
		<div class="widget"><!-- Publish -->
			<fieldset class="formpart">
				<div class="formRow">
					<button class="buttonL bGreyish fluid" type="submit"><i class="iconb" data-icon="&#xe097;"></i> &nbsp; Next</button>
				</div>
			</fieldset>
		</div>
		
	</div>
</div>
</form>

<div style="display: hidden; padding:20px" id="dialog" title="Welcome!">
    <h3>Welcome to the ShopSuey family!<br> Thank you for signing up!</h3>
</div>

<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>

<script type="text/javascript">

$(function() {
    $("#company").validationEngine();
    
    $('#st').live('change', function(e){
        var state = $(this).val();
        
        if (tz_by_state[state.toUpperCase()]) {
            if ($('#timezone option[value="' + tz_by_state[state.toUpperCase()] + '"]').length > 0) {
                $('#timezone option[value="' + tz_by_state[state.toUpperCase()] + '"]').attr('selected', 'selected');
                $('#timezone').trigger("liszt:updated");
            }
        }
    });
    
    $('#address').live('blur', update_address_fields);
    $('#country').live('change', function() {
        update_address_fields();

        // Google Api returns different timezone names in some countries (ie argentina).   
        if ($(this).val() == '24') {
            $('#timezone option').removeAttr('selected');
            $('#timezone option[value="America/Argentina/Buenos_Aires"]').attr('selected', 'selected');
            $('#timezone').trigger("liszt:updated");
        }
    });
    $('#city').live('blur', update_address_fields);
    
    $('.day_checkbox').live('change', function(e) {
        markHoursAsModified();
    });
    
    $('.timepicker').live('change', function(e) {
        markHoursAsModified();
    });

    function markHoursAsModified() {
        $('#inherited_hours').val('0');
    }
});

<?php if ($first_time) { ?>
$(function() {
    $( "#dialog" ).dialog({
        height: 200,
        width: 400,
        modal: true,
        buttons: {
            Ok: function() {
              $( this ).dialog( "close" );
            }
          }
    });
});
<?php } else { ?>
    $(function() {
        $( "#dialog" ).hide()
    });
<?php } ?>


</script>
