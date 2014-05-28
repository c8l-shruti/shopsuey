<!-- Merchant Info -->
<?php if ($action == 'edit') {
    $url = "admin/merchant/{$merchant->id}/edit";
} else {
    $url = "admin/merchant/add";
}
?>
<form id="wizard1" action="<?=Uri::create($url, array(), Input::get())?>" method="POST" enctype="multipart/form-data">
<input type="hidden" id="action" name="action" value="<?=$action?>" />
<input type="hidden" id="logo" name="logo" value="<?=@$merchant->logo?>" />
<input type="hidden" id="landing_screen_img" name="landing_screen_img" value="<?=@$merchant->landing_screen_img?>" />
<input type="hidden" id="mall_id" name="mall_id" value="<?=$merchant->mall_id?>" />

<?=CMS::create_nonce_field('user_'.$action, 'nonce')?>

<div class="fluid">
	<div class="grid8">
		<div class="widget">
			<div class="whead">
				<h6><span class="icon-user-3"></span>Merchant Info</h6>
				<div class="clear"></div>
			</div>

			<fieldset class="formpart">
				<div class="formRow">
					<div class="grid3"><label for="mname">Merchant Name:</label></div>
					<div class="grid9">
						<input type="text" id="mname" name="name" placeholder="What's the merchant name?" value="<?=@$merchant->name?>" class="required" />
					</div>
					<div class="clear"></div>
				</div>

				<div class="formRow">
					<div class="grid3"><label for="contact">Contact Name:</label></div>
					<div class="grid9">
						<input type="text" id="contact" name="contact" placeholder="Who is the contact here?" value="<?=@$merchant->contact?>" />
					</div>
					<div class="clear"></div>
				</div>

				<div class="formRow">
					<div class="grid3"><label for="phone">Contact Phone:</label></div>
					<div class="grid9">
						<input class="phoneNumber" type="text" id="phone" name="phone" placeholder="What's your contact phone? Ex: 888-555-1234 ext 567" value="<?=@$merchant->phone?>" />
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow">
					<div class="grid3"><label for="email">Contact Email:</label></div>
					<div class="grid9">
						<input type="email" id="email" name="email" placeholder="What's the best email address for contacting this place?" value="<?=@$merchant->email?>" />
					</div>
					<div class="clear"></div>
				</div>

				<div class="formRow">
					<div class="grid3"><label for="web">Website:</label></div>
					<div class="grid9">
						<input type="text" id="web" name="web" placeholder="What's your website url?" value="<?=@$merchant->web?>" />
					</div>
					<div class="clear"></div>
				</div>
				
				<!-- <div class="formRow">
					<div class="grid7"><label for="is_customer">Is ShopSuey Customer:</label></div>
					<div class="grid5">
						<input type="checkbox" id="is_customer" name="is_customer" value="1" <?=(@$merchant->is_customer) ? 'checked="checked"' : ''?> />
					</div>
					<div class="clear"></div>
				</div> -->
			</fieldset>
		</div>
		<!-- div class="widget">
			<div class="whead">
				<h6><span class="icon-document"></span>Description</h6>
				<div class="clear"></div>
			</div>

			<fieldset class="formpart">
				<div class="formRow">
					<textarea name="content" rows="6"><?=@$merchant->content?></textarea>
				</div>
			</fieldset>
		</div -->
		<div class="widget">
			<div class="whead">
				<h6><span class="icon-home"></span>Address Info</h6>
				<div class="clear"></div>
			</div>

			<fieldset class="formpart">
				<div class="formRow">
					<div class="grid3"><label for="mall_search">Mall:</label></div>
					<div class="grid9">
						<input id="mall_search" name="mall_search" />
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow">
					<div class="grid3"><label for="address">Floor:</label></div>
					<div class="grid9">
						<input type="text" id="floor" name="floor" placeholder="In which floor of the mall is the shop located?" value="<?=@$merchant->floor?>" />
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow">
					<div class="grid3"><label for="address">Address:</label></div>
					<div class="grid9">
						<input type="text" id="address" name="address" placeholder="What's the shop address?" value="<?=@$merchant->address?>" />
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow">
					<div class="grid3"><label for="city">City:</label></div>
					<div class="grid9">
						<input type="text" id="city" name="city" placeholder="What's the city of the shop? Ex: New York City" value="<?=@$merchant->city?>" />
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow">
					<div class="grid3"><label for="address">State:</label></div>
					<div class="grid9">
						<input type="text" id="st" name="st" placeholder="What's the state of the shop? Ex: NY" size="2" maxlength="2" value="<?=@$merchant->st?>" />
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow">
					<div class="grid3"><label for="country_id">Country:</label></div>
			        <div class="searchDrop grid9" id="countryWrapper">
    					<select  id="country_id" class="fullwidth countryCheck" data-placeholder="Select your country" name="country_id">
    						<?php foreach(CMS::countries() as $country) : ?>
    						<option value="<?=$country->id?>" <?=$country->id == @$merchant->country_id ? 'selected="selected"' : '' ?>><?=$country->name?> (<?=$country->code?>)</option>
    						<?php endforeach; ?>
    					</select>
				    </div>
					<div class="clear"></div>
			    </div>
				<div class="formRow">
					<div class="grid3"><label for="zip">Zip:</label></div>
					<div class="grid9">
						<input type="text" pattern="\d{5}" maxlength="5" id="zip" name="zip" placeholder="What's the zip code? Ex: 10458" value="<?=@$merchant->zip?>" />
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow">
					<div class="grid2"><label for="latitude">Latitude:</label></div>
					<div class="grid4">
						<input type="number" step="any" id="latitude" name="latitude" placeholder="37.787125" value="<?=@$merchant->latitude?>" class="location-field required"/>
					</div>
					<div class="grid2"><label for="longitude">Longitude:</label></div>
					<div class="grid3">
						<input type="number" step="any" id="longitude" name="longitude" placeholder="-122.425412" value="<?=@$merchant->longitude?>"  class="location-field required"/>
					</div>
					<div class="grid1">
					    <?= Asset::img('elements/loaders/1s.gif', array('class' => 'geo_info_loader', 'style' => 'display: none;')); ?>
					</div>
					<div class="clear"></div>
				</div>
                <div class="formRow">
					<div class="grid3"><label>Timezone:</label></div>
					<div class="grid8">
                        <select id="timezone" name="timezone" class="fullwidth" data-placeholder="Select your timezone...">
                            <option value=""></option>
                            <?php foreach ($timezones as $timezone): ?>
                            <option value="<?= $timezone; ?>" <?php if (@$merchant->timezone && $timezone == $merchant->timezone): ?>selected="selected"<?php endif; ?>><?= $timezone; ?></option>
                            <?php endforeach; ?>
                        </select>
					</div>
					<div class="grid1">
					    <?= Asset::img('elements/loaders/1s.gif', array('class' => 'geo_info_loader', 'style' => 'display: none;')); ?>
					</div>
					<div class="clear"></div>
				</div>
				
			</fieldset>
		</div>
		
		<div id="coords_dialog" title="Update coordinates">
            <p>
                <span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>
                New coordinates has been found. Would you like to update the existing latitude/longitude values?
            </p>
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
                    <div style="float: left; margin-left:20px; width: 400px;">
                        <span class="floatL mr5"><input type="text" name="hours[<?=$i?>][open]" value="<?=$hours[$i]['open']?>" class="timepicker" /></span>
						<span class="floatL mr5 inline-label">&ndash;</span>
						<span class="floatL"><input type="text" name="hours[<?=$i?>][close]" value="<?=$hours[$i]['close']?>" class="timepicker" /></span>
						<div class="clear"></div>
					</div>
					<div class="clear"></div>
				</div>
                <?php } ?>
			</fieldset>
            <input type="hidden" id="inherited_hours" name="inherited_hours" value="<?php echo (isset($merchant->hours_inherited_from_mall)) ? 1 : 0; ?>" />
            <?php if (isset($merchant->hours_inherited_from_mall)): ?>
            <div class="inherited_hours_from_mall">
                The hours of operation were not explicitly set for your store so we inherited the hours from the marketplace for which your store resides. 
                Simply edit your hours if they are not the same as your marketplace.
            </div>
            <?php endif; ?>
		</div>

	</div>
	<div class="grid4">
		<div class="widget">
			<div class="whead">
				<h6><span class="icon-cog"></span>Publish</h6>
				<div class="clear"></div>
			</div>
			<fieldset class="formpart">
				<?php if ($action == 'edit') : ?>
				<div class="formRow">
					<select id="status" name="status" class="fullwidth" data-placeholder="Status...">
						<option value="1" <?=(@$merchant->status == 1) ? 'selected="selected"' : ''?>>Active</option>
						<option value="2" <?=(@$merchant->status == 2) ? 'selected="selected"' : ''?>>Inactive</option>
						<option value="<?=Model_Location::STATUS_SIGNUP?>" <?=(@$merchant->status == Model_Location::STATUS_SIGNUP) ? 'selected="selected"' : ''?>>Pending From Signup</option>
					</select>
				</div>
				<?php endif; ?>

				<div class="formRow">
					<button class="buttonL bGreyish fluid" type="submit"><?=($action == 'add') ? '<i class="iconb" data-icon="&#xe099;"></i> &nbsp; Add Merchant' : '<i class="iconb" data-icon="&#xe097;"></i> &nbsp; Save Merchant'?></button>
				</div>
			</fieldset>
		</div>

		<div class="widget"><!-- Micello's Integration -->
			<div class="whead">
				<h6><span class="icon-share-3"></span>Micello's Integration</h6>
				<div class="clear"></div>
			</div>
			<fieldset class="formpart">
				<div class="formRow">
					<div class="grid4"><label for="micello_search">Search:</label></div>
					<div class="grid7">
						<input type="text" id="micello_search" name="micello_search" value="" />
					</div>
					<div class="grid1">
					    <?= Asset::img('elements/loaders/1s.gif', array('id' => 'micello_search_loader')); ?>
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow">
					<div class="grid4"><label for="micello_id">Micello's Id:</label></div>
					<div class="grid8">
						<input type="text" id="micello_id" name="micello_info[micello_id]" value="<?=@$merchant->micello_info->micello_id?>" />
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow" id="geometry_id_wrapper">
					<div class="grid4"><label for="geom_id">Geometry Id:</label></div>
					<div class="grid8">
						<input type="text" id="geometry_id" name="micello_info[geometry_id]" value="<?=@$merchant->micello_info->geometry_id?>" />
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow">
					<button class="buttonL bBlue fluid" id="micello_populate" type="button">Populate from Micello</button>
				</div>
			</fieldset>
		</div>

		<div id="dialog-confirm" title="Overwrite changes to the Mall?">
            <p>
                <span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>
                There are changes made to the Merchant information that differ from Micello's data.<br>Which fields would you like to populate?
            </p>
        </div>
		
		<!-- div class="widget">
			<div class="whead">
				<h6><span class="icon-cart"></span>Subscription</h6>
				<div class="clear"></div>
			</div>

			<fieldset class="formpart">
				<div class="formRow">
					<div class="grid7"><label for="is_customer">Is ShopSuey Customer:</label></div>
					<div class="grid5">
						<input type="checkbox" id="is_customer" name="is_customer" value="1" <?=(@$merchant->is_customer) ? 'checked="checked"' : ''?> />
					</div>
					<div class="clear"></div>
				</div>
			
				<div class="formRow">
					<div class="grid4"><label for="plan">Plan:</label></div>
					<div class="grid8">
						<select id="plan" name="plan" data-target="#max_users" data-placeholder="Select a plan" rel="#max_users" class="fullwidth">
							<option></option>
							<option value="1" <?=(@$merchant->plan == 1) ? 'selected="selected"' : ''?> data-max="1">Basic</option>
							<option value="2" <?=(@$merchant->plan == 2) ? 'selected="selected"' : ''?> data-max="10">Advanced</option>
							<option value="3" <?=(@$merchant->plan == 3) ? 'selected="selected"' : ''?> data-max="25">Profession</option>
							<option value="4" <?=(@$merchant->plan == 4) ? 'selected="selected"' : ''?> data-max="">Extreme</option>
							<option value="5" <?=(@$merchant->plan == 5) ? 'selected="selected"' : ''?> data-max="">Custom</option>
						</select>
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow">
					<div class="grid4"><label for="max_users">Max Users:</label></div>
					<div class="grid8">
						<input type="number" id="max_users" name="max_users" class="number" placeholder="leave blank if unlimited" value="<?=@$merchant->max_users?>" />
					</div>
					<div class="clear"></div>
				</div>
			</fieldset>
		</div -->
		<div class="widget">
			<div class="whead">
				<h6><span class="icon-share-3"></span>Social Media</h6>
				<div class="clear"></div>
			</div>
				<div class="formRow">
					<div class="grid4"><label for="social_facebook">Facebook Page:</label></div>
					<div class="grid8">
						<input class="facebookUrl" type="text" id="social_facebook" name="social[facebook]" value="<?=@$merchant->social->facebook?>" />
					</div>
					<p class="example">e.g: https://www.facebook.com/GetShopSuey</p>
					<div class="clear"></div>
				</div>
				<div class="formRow">
					<div class="grid4"><label for="social_foursquare">Foursquare:</label></div>
					<div class="grid8">
						<input class="foursquareUrl"  type="text" id="social_foursquare" name="social[foursquare]" value="<?=@$merchant->social->foursquare?>" />
					</div>
					<p class="example">e.g: https://foursquare.com/v/goodwill/4d195c93b15c5bc221</p>
					<div class="clear"></div>
				</div>
				<div class="formRow">
					<div class="grid4"><label for="social_twitter">Twitter:</label></div>
					<div class="grid8">
						<input class="twitterId" type="text" id="social_twitter" name="social[twitter]" value="<?=@$merchant->social->twitter?>" />
					</div>
					<p class="example">e.g: @shopsueyapp</p>
					<div class="clear"></div>
				</div>
                <div class="formRow">
					<div class="grid7"><label for="default_social">Default Social Fields:</label></div>
					<div class="grid5">
						<input type="checkbox" id="default_social" name="default_social" value="1" <?=(@$merchant->default_social) ? 'checked="checked"' : ''?> />
					</div>
					<div class="clear"></div>
				</div>
		</div>
		<div class="widget">
			<div class="whead">
				<h6><span class="icon-email"></span>Newsletter</h6>
				<div class="clear"></div>
			</div>
			<fieldset class="formpart">
				<div class="formRow">
					<input type="email" name="newsletter" value="<?=@$merchant->newsletter?>" placeholder="In which email address do you want to receive our newsletter?" />
				</div>
			</fieldset>
		</div>

		<div class="widget">
			<div class="whead"><h6><span class="icon-picture"></span>Logo</h6><div class="clear"></div></div>
			<fieldset>
				<div class="formRow check">
				    <div id="logo-image" class="grid12">
					<?php if (@$merchant->logo) : ?>
                        <?= Asset::img(Config::get('cms.logo_images_path').DS.'small_'.$merchant->logo, array('id' => 'logo-img-preview')); ?>
                    <?php else: ?>
                        <img alt="" src="" id="logo-img-preview" />
					<?php endif; ?>
					</div>
					<div class="grid12">
					    <input type="file" name="logo" /><br/><br/>
					    <button class="buttonL bBlue" id="logo-list-button" type="button">Select preloaded logo</button>
                        or 
                        <?php if (isset($merchant->id) && $merchant->id): ?>
                        <button class="buttonL bRed" id="delete_logo" href="<?=Uri::create("api/merchant/{$merchant->id}/delete_photo/logo");?>?login_hash=<?php echo $login_hash; ?>" type="button">Delete logo</button>
                        <?php else: ?>
                        <button class="buttonL bRed" id="delete_logo" href="#" type="button">Delete logo</button>
                        <?php endif; ?>
					</div>
					<div class="clear"></div>
                    <input type="hidden" name="x1_logo" id="logo-img-x1" />
                    <input type="hidden" name="y1_logo" id="logo-img-y1" />
                    <input type="hidden" name="x2_logo" id="logo-img-x2" />
                    <input type="hidden" name="y2_logo" id="logo-img-y2" />
                    <input type="hidden" name="preview_width_logo" id="logo-img-preview-width" />
                    <input type="hidden" name="preview_height_logo" id="logo-img-preview-height" />
				</div>
				<div class="formRow">
					<div class="grid7"><label for="default_logo">Default logo:</label></div>
					<div class="grid5">
						<input type="checkbox" id="default_logo" name="default_logo" value="1" <?=(@$merchant->default_logo) ? 'checked="checked"' : ''?> />
					</div>
					<div class="clear"></div>
				</div>
			</fieldset>
			<div id="logo-list">
                <div class="no-preloaded-images">No preloaded images found</div>
    			<ul class="image-list">
    			</ul>
			</div>
		</div>
        <div class="widget"><!-- landing screen image -->
			<div class="whead"><h6><span class="icon-picture"></span>Landing Screen Image</h6><div class="clear"></div></div>
			<fieldset>
				<div class="formRow check">
				    <div id="landing-image" class="grid12">
					<?php if (@$merchant->landing_screen_img) : ?>
                        <?= Asset::img(Config::get('cms.landing_images_path').DS.'small_'.@$merchant->landing_screen_img, array('id' => 'landing-img-preview')); ?>
                    <?php else: ?>
                        <img alt="" src="" id="landing-img-preview" />
					<?php endif; ?>
					</div>
					<div class="grid12">
					    <input type="file" name="landing" /><br/><br/>
					    <button class="buttonL bBlue" id="img-list-button" type="button">Select preloaded image</button>
                        or 
                        <?php if (isset($merchant->id) && $merchant->id): ?>
                        <button class="buttonL bRed" id="delete_landing_screen" href="<?=Uri::create("api/merchant/{$merchant->id}/delete_photo/landing");?>?login_hash=<?php echo $login_hash; ?>" type="button">Delete Landing Screen</button>
                        <?php else: ?>
                        <button class="buttonL bRed" id="delete_landing_screen" href="#" type="button">Delete Landing Screen</button>
                        <?php endif; ?>
					</div>
					<div class="clear"></div>
                    <input type="hidden" name="x1_landing" id="landing-img-x1" />
                    <input type="hidden" name="y1_landing" id="landing-img-y1" />
                    <input type="hidden" name="x2_landing" id="landing-img-x2" />
                    <input type="hidden" name="y2_landing" id="landing-img-y2" />
                    <input type="hidden" name="preview_width_landing" id="landing-img-preview-width" />
                    <input type="hidden" name="preview_height_landing" id="landing-img-preview-height" />
				</div>
				<div class="formRow">
					<div class="grid7"><label for="default_landing_screen_img">Default landing image:</label></div>
					<div class="grid5">
						<input type="checkbox" id="default_landing_screen_img" name="default_landing_screen_img" value="1" <?=(@$merchant->default_landing_screen_img) ? 'checked="checked"' : ''?> />
					</div>
					<div class="clear"></div>
				</div>
			</fieldset>
			<div id="img-list">
                <div class="no-preloaded-images" style="display:none;">No preloaded images found</div>
    			<ul class="image-list">
    			</ul>
			</div>
		</div>
        
		<div class="widget">
			<div class="whead"><h6><span class="icon-picture"></span>Instagram Integration</h6><div class="clear"></div></div>
			<fieldset class="formpart">
				<div class="formRow">
					<div class="grid9"><label for="use_instagram">Use Instagram feed:</label></div>
					<div class="grid3">
						<input type="checkbox" id="use_instagram" name="use_instagram" value="1" <?=(@$merchant->use_instagram) ? 'checked="checked"' : ''?> <?=(!$instagram_set) ? 'disabled="disabled"' : ''?>/>
					</div>
			        <?php if ($instagram_set) : ?>
					<div class="grid12">
                        <?=\Html::img($instagram_latest_post->images->standard_resolution->url, array('width' => 200))?>
                    </div>
				    <?php endif; ?>
                    <div class="clear"></div>
				</div>
				<div class="formRow">
					<button class="buttonL bGreyish fluid" id="setup-instagram" type="button">Setup Instagram</button>
				</div>
			</fieldset>
		</div>

		<div id="setup-instagram-dialog" title="Setup Instagram">
            <p>
                <span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>
                This is only for merchants/marketplace users who know the instagram credentials. If you know the credentials, create an account and adjust accordingly - else stay away.
            </p>
        </div>
		
		<div class="widget">
			<div class="whead"><h6><span class="icon-tag"></span>Tags</h6><div class="clear"></div></div>
			<fieldset>
				<div class="formRow">
					<input type="text" id="tags" name="tags" class="tags" value="<?=@$merchant->tags?>" />
				</div>
			</fieldset>
		</div>
		
		<div class="widget">
			<div class="whead"><h6><span class="icon-tag"></span>Category (Pick up to three)</h6><div class="clear"></div></div>
			<fieldset>
				<div class="formRow">
			        <div class="searchDrop">
    					<select class="fullwidth" data-placeholder="Select..." name="category_ids[]" multiple="multiple">
    						<option></option>
    						<?php $merchantCategories = isset($merchant->category_ids) ? @$merchant->category_ids : array() ?>
    						<?php foreach(CMS::categories() as $category) : ?>
    						<option value="<?=$category->id?>" <?=(@in_array($category->id, $merchantCategories)) ? 'selected="selected"' : '' ?>><?=$category->name?></option>
    						<?php endforeach; ?>
    					</select>
				    </div>
				</div>
			</fieldset>
		</div>
		
                <div class="widget">
			<div class="whead"><h6><span class="icon-tag"></span>Associated brands</h6><div class="clear"></div></div>
			<fieldset>
                            <div class="formRow">
                            <div class="searchDrop">
                                    <select class="fullwidth" data-placeholder="Select..." name="profilings[]" multiple="multiple">
                                            <option></option>
                                            <?php $profilings = isset($merchant->profilings) ? @$merchant->profilings : array() ?>
                                            <?php 
                                            
                                            $profiling_ids = array();
                                            
                                            foreach($profilings as $profiling){
                                                $profiling_ids[] = (int)$profiling->id;
                                            }
                                            
                                            ?>
                                            
                                            <?php foreach(Model_Profilingchoice::query()->order_by('order', 'ASC')->get() as $profiling) : ?>
                                                <option value="<?=$profiling->id?>" <?=(@in_array($profiling->id, $profiling_ids)) ? 'selected="selected"' : '' ?>><?=$profiling->name?></option>
                                            <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
			</fieldset>
		</div>
                
		<div class="widget">
			<div class="whead">
				<h6><span class="icon-cog"></span>Publish</h6>
				<div class="clear"></div>
			</div>
			<fieldset class="formpart">
				<div class="formRow">
					<button class="buttonL bGreyish fluid" type="submit"><?=($action == 'add') ? '<i class="iconb" data-icon="&#xe099;"></i> &nbsp; Add Merchant' : '<i class="iconb" data-icon="&#xe097;"></i> &nbsp; Save Merchant'?></button>
				</div>
			</fieldset>
		</div>
		
	</div>
</div>

<div class="fluid">
	<div class="widget">
    <?php if ($action == 'edit') : $notes = CMS::comments($merchant->id, 'merchant'); ?>
        <?php if (count($notes) > 0) : ?>
        <div class="whead" data-target="#notes-div">
            <h6><span class="icon-comments-4"></span>Notes</h6>
            <div class="titleOpt">
                <a class="slide-toggle" data-target="#notes-div"><span class="icos-menu-3 icon-menu-3"></span><span class="clear"></span></a>
            </div>
            <div class="clear"></div>
        </div>
        <fieldset>
            <div id="notes-div" class="formRow" style="padding: 0">
                <ul class="messagesOne">
					<?php
						$prev_user = null;
						foreach($notes as $idx=>$note) :
						$note = (object) $note;
						$author = CMS::user($note->user_id, 'object');
					?>
                    <?php if ($idx > 0 && $prev_user != $note->user_id) : $prev_user = $note->user_id; ?>
                    <li class="divider"><span></span></li>
                    <?php endif; ?>
                    <li class="<?=(CMS::is_me($note->user_id)) ? 'by_me' : 'by_user' ?>">
                        <a href="#" title=""><span class="icona avatar" data-icon="&#xe03d;"></span></a>
                        <div class="messageArea">
                            <span class="aro"></span>
                            <div class="infoRow">
                                <span class="name"><strong><?=$author->meta->fullname?></strong> says:</span>
                                <span class="time"><?=date('m/d/Y h:i a', strtotime($note->timestamp))?></span>
                                <div class="clear"></div>
                            </div>
                            <?=$note->comment?>
                        </div>
                        <div class="clear"></div>
                    </li>
                <?php endforeach; ?>
                </ul>
            </div>
        </fieldset>
        <?php endif; ?>
    <?php endif; ?>
    <div class="whead"><h6><span class="icon-comments-3"></span><?=($action == 'edit') ? 'Add ' : ''?>Notes</h6><div class="clear"></div></div>
    <fieldset class="formpart">
		<textarea id="description" name="description" rows="" cols="16"><?=CMS::strip_tags(@$merchant->description)?></textarea>
    </fieldset>
	</div>
</div>

<div class="fluid">

</div>
</form>

<style type="text/css">
#countryWrapper .error {
    padding-bottom: 10px;
}
</style>

<script src="https://maps.googleapis.com/maps/api/js?libraries=places&v=3.exp&sensor=false"></script>

<script type="text/javascript">
    
function all_fields_empty(data) {
	if ($("#mall_id").val() == '' || $("#mall_id").val() == '0') {
    	return !($("#address").val() ||
            $("#city").val() ||
        	$("#st").val() ||
        	$("#zip").val() ||
        	$("#latitude").val() ||
        	$("#longitude").val() ||
        	$("#mname").val());
	} else {
		return !($("#floor").val() ||
	        $("#mname").val());
    }
};

function data_changed(data) {
	if ($("#mall_id").val() == '' || $("#mall_id").val() == '0') {
	    return $("#address").val() != data.street1 ||
            $("#city").val() != data.city ||
        	$("#st").val() != data.state ||
        	$("#zip").val() != data.zipcode ||
        	$("#latitude").val() != data.lat ||
        	$("#longitude").val() != data.lon ||
        	$("#mname").val() != data.name;
	} else {
		return $("#floor").val() != data.lnm ||
	        $("#mname").val() != data.nm;
    }
};

function update_data(data, empty_only) {
	if ($("#mall_id").val() == '' || $("#mall_id").val() == '0') {
	    update_mall(data, empty_only);
	} else {
		if (! $("#floor").val() || ! empty_only) {
			$("#floor").val(data.lnm);
	    }
		if (! $("#mname").val() || ! empty_only) {
			$("#mname").val(data.nm);
	    }
    }
}

function update_mall(data, empty_only) {
	if ((! $("#mname").val() || ! empty_only) && data.name) {
		$("#mname").val(data.name);
    }
	if (! $("#st").val() || ! empty_only) {
		if ($("#st").val() == data.state) {
			$("#st").change();
		}
		$("#st").val(data.state);
    }
    if (! $("#address").val() || ! empty_only) {
		if ($("#address").val() == data.street1) {
			$("#address").change();
		}
	    $("#address").val(data.street1);
    }
	if (! $("#city").val() || ! empty_only) {
		if ($("#city").val() == data.city) {
			$("#city").change();
		}
	    $("#city").val(data.city);
    }
	if (! $("#zip").val() || ! empty_only) {
		$("#zip").val(data.zipcode);
    }
	if (! $("#latitude").val() || ! empty_only) {
		$("#latitude").val(data.lat);
    }
	if (! $("#longitude").val() || ! empty_only) {
		$("#longitude").val(data.lon);
    }

	if (data.hours != undefined && !$('input[name="hours[0][open]"]').val()) {
        for (i = 0; i < 3; i++) {
            $('input[name="hours['+i+'][sun]"]').prop('checked', data.hours[i].days.indexOf('sun') != -1 ? true : false);
            $('input[name="hours['+i+'][mon]"]').prop('checked', data.hours[i].days.indexOf('mon') != -1 ? true : false);
            $('input[name="hours['+i+'][tue]"]').prop('checked', data.hours[i].days.indexOf('tue') != -1 ? true : false);
            $('input[name="hours['+i+'][wed]"]').prop('checked', data.hours[i].days.indexOf('wed') != -1 ? true : false);
            $('input[name="hours['+i+'][thr]"]').prop('checked', data.hours[i].days.indexOf('thr') != -1 ? true : false);
            $('input[name="hours['+i+'][fri]"]').prop('checked', data.hours[i].days.indexOf('fri') != -1 ? true : false);
            $('input[name="hours['+i+'][sat]"]').prop('checked', data.hours[i].days.indexOf('sat') != -1 ? true : false);
    
            $('input[name="hours['+i+'][open]"]').val(data.hours[i].open);
            $('input[name="hours['+i+'][close]"]').val(data.hours[i].close);
        }
	}
}

var micello_info = null;

var micello_select_callback = function(event, ui) {
	micello_info = ui.item.data;
	// Enable populate button
	$("#micello_populate").prop("disabled", false);
	$("#micello_populate").removeClass('bBlack');
	$("#micello_populate").addClass('bBlue');

	if ($("#mall_id").val() == '' || $("#mall_id").val() == '0') {
		$("#geometry_id").val('');
		$("#micello_id").val(micello_info.id);
    } else {
		$("#geometry_id").val(micello_info.gid);
		$("#micello_id").val(micello_info.eid);
    }
}

$("#micello_search").customautocomplete({
	delay: 500,
	minLength: 3,
	source: "<?=Config::get('base_url')?>api/location/micello_community/?login_hash=<?=$login_hash?>&type=Retail",
	select: micello_select_callback,
	search: function(event, ui) {
		$("#micello_search_loader").show();
	}
});

$("#micello_id").customautocomplete({
	delay: 500,
	minLength: 1,
	source: "<?=Config::get('base_url')?>api/location/micello_community/?login_hash=<?=$login_hash?>&type=Retail&search_by=id",
	select: function (event, ui) {
		micello_select_callback(event, ui);
		$("#micello_search").val(ui.item.data.name);
		event.preventDefault();
	},
	search: function(event, ui) {
		$("#micello_search_loader").show();
	}
});

$("#micello_search, #micello_id").bind("autocompletesearchcomplete", function(event, contents) {
	$("#micello_search_loader").hide();
});

$("#micello_search, #micello_id").keypress(function() {
    if (event.which != 13) {
    	$("#micello_populate").addClass('bBlack');
    	$("#micello_populate").removeClass('bBlue');
    	$("#micello_populate").prop("disabled", true);
    }
});

$("#mall_id").change(function() {
	$("#micello_search").val('');
	$("#micello_search_loader").show();
	// Disable populate button
	$("#micello_populate").prop("disabled", true);
	$("#micello_populate").addClass('bBlack');
	$("#micello_populate").removeClass('bBlue');
	
	if ($("#mall_id").val() == '' || $("#mall_id").val() == '0') {
		source = "<?=Config::get('base_url')?>api/location/micello_community/?login_hash=<?=$login_hash?>&type=Retail";
		$("#micello_search").autocomplete("option", "source", source);
		//$("#micello_search").autocomplete("option", "select", micello_select_callback);
		$("#micello_search").on("autocompleteselect", micello_select_callback);
		$("#micello_search").autocomplete("option", "delay", 500);
		$("#micello_search").autocomplete("option", "minLength", 3);
		$("#geometry_id_wrapper").hide();
		$("#micello_search_loader").hide();
		$("#micello_id").autocomplete("enable");
    } else {
		url = "<?=Config::get('base_url')?>api/location/micello_entity/?login_hash=<?=$login_hash?>&location_id=" + $("#mall_id").val();
		$.getJSON(url, { login_hash: "<?=$login_hash?>", location_id: $("#mall_id").val() }, function(data) {
		    $("#micello_search").autocomplete("option", "source", data);
			//$("#micello_search").autocomplete("option", "select", micello_select_callback);
			$("#micello_search").on("autocompleteselect", micello_select_callback);
            $("#micello_search").autocomplete("option", "delay", 0);
			$("#micello_search").autocomplete("option", "minLength", 0);
			$("#geometry_id_wrapper").show();
			$("#micello_search_loader").hide();
			$("#micello_id").autocomplete("disable");
		});
    }

	var mallId = $('#mall_id').val();
	if (mallId != '' && mallId != '0') {
        var url = "<?=Config::get('base_url')?>api/mall/"+mallId+"?login_hash=<?=$login_hash?>&ajax=1";
    
    	$.get(url, function(data) {
        	var mall = data.data.mall;
        	var data = {
        	    street1: mall.address,
        	    city: mall.city,
        	    state: mall.st,
        	    zipcode: mall.zip,
        	    lat: mall.latitude,
        	    lon: mall.longitude,
        	    hours: mall.hours
            };
    	    update_mall(data, true);
        });
	} else {
	    update_mall({}, true);
	}
});

$("#micello_search_loader").hide();

$("#micello_populate").click(function() {
	if (!all_fields_empty() && data_changed(micello_info)) {
		$( "#dialog-confirm" ).dialog("open");
	} else {
		update_data(micello_info, false);
	}
    return false;	
});

var logo_merchant_name = '';
var img_merchant_name = '';

$(function() {
	$( "#dialog-confirm" ).dialog({
		autoOpen: false,
    	resizable: false,
    	height:175,
    	width:500,
    	modal: true,
    	buttons: {
        	"Empty Fields": function() {
    			update_data(micello_info, true);
    		    $( this ).dialog( "close" );
        	},
            "All Fields": function() {
    			update_data(micello_info, false);
    		    $( this ).dialog( "close" );
        	},
        	Cancel: function() {
        	    $( this ).dialog( "close" );
        	}
    	}
	});

	$("#mall_id").change();

	$("#mall_search").autoSuggest("<?=Uri::create("api/malls")?>", {
		minChars: 3,
		queryParam: "string",
		extraParams: "&pagination=0&compact=1&order_by=simple_relevance&login_hash=<?=$login_hash?>",
		selectedItemProp: "name",
		selectedValuesProp: "value",
		searchObjProps: "name,address,city,st,zip,email,web,description",
		matchCase: false,
		asHtmlID: "mall_ids",
		selectionLimit: 1,
		resultClick: function(data) {
			$("#mall_id").val(data.attributes.id).change();
		},
		selectionRemoved: function(elem) {
			elem.fadeTo("slow", 0, function() { elem.remove(); });
			$("#mall_id").val('').change();
	    },
	    startText: 'Search Market Place...',
		<?php if ($merchant->mall_id): ?>
	    preFill: <?=json_encode(CMS::malls_by_id(array($merchant->mall_id)));?>,
        <?php endif; ?>
	    limitText: 'Only one Market Place is allowed'
	});

	$("#logo-list").dialog({
	    autoOpen: false,
    	resizable: false,
    	height: 'auto',
    	width: 500,
    	modal: true,
    	title: "Pick a logo"
	});

	$("#logo-list-button").click(function() {
		var merchant_name = $('#mname').val();
        var url = "<?=Config::get('base_url')?>api/merchant/logos?login_hash=<?=$login_hash?>&merchant_name="+merchant_name;
        var showDialog = true;

        if (logo_merchant_name != merchant_name) {
            $("#logo-list ul").html('');
            
            if (merchant_name.length >= 3) {        
            	$.get(url, {name: name}, function(data) {
                    if (data == null) {
                        $('#logo-list .no-preloaded-images').show();
                        $('#logo-list ul').hide();
                    } else {
                        $('#logo-list .no-preloaded-images').hide();
                        $('#logo-list ul').show();
                        
                        for (var key in data) {
                            $("#logo-list ul").append('<li class="image-item logo-item" logo-name="'+data[key].logoName+'"></li>');
                            $("#logo-list ul li:last").append('<img src="'+data[key].logoUrl+'" />');
                        } 
                    }
                }, 'json');
            } else {
                alert('Merchant name should have 3 or more characters to find preloaded logos.');
                showDialog = false;
            }
        }

        if (showDialog) {
            $("#logo-list").dialog("open");
        }

        logo_merchant_name = merchant_name;
	});

	$(".logo-item").live('click', function() {
	    var logoName = $(this).attr('logo-name');
	    var logoUrl = $(this).find('img').attr('src');
	    $('#logo').val(logoName);
	    // Destroy the jcrop object if present
	    if (jcrop_api.logo) {
	        jcrop_api.logo.destroy();
	    }
	    $('#logo-img-preview').replaceWith($('<img/>', {
		    id: "logo-img-preview",
		    src: logoUrl
	    }));
	    $("#logo-list").dialog("close");
	    // Empty the file input to prevent upload
	    var file_control = $('input[name="logo"]');
	    file_control.replaceWith( file_control = file_control.clone( true ) );
	});
    
    
    var delete_image = function(e, name, img_id) {
        e.preventDefault();

        if (jcrop_api[name]) {
            jcrop_api[name].destroy();
        }

        var img_name = (name == 'logo') ? name : 'landing_screen_img';

        var href = $(e.target).attr('href');
        if (href != '#') { 
            // Updating mall
            $.post(href, {}, function(r) {
               if (r.data.status) {
                   $('#' + img_id).css({ height: '200px', width: '200px'});
                   $('#' + img_id).addClass('no-crop').attr('src', r.data.default_image);
                   $('#deleted_image_' + name).val(1);
                   $('#' + img_name).val(r.data.default_image_name);
                   $('input[type=file][name=' + name + ']').val(null);
               }
            }, 'json');
        } else {
            // Adding a new mall
            $('#' + img_id).css({ height: '200px', width: '200px'});
            $('#' + img_id).addClass('no-crop').attr('src', '<?= \Fuel\Core\Asset::get_file('default-logo.png', 'img'); ?>');
            $('input[type=file][name=' + name + ']').val(null);
            $('#' + img_name).val(null);
            $('#deleted_image_' + name).val(1);
        }
    }
    
    $('#delete_logo').live('click', function(e) { delete_image(e, 'logo', 'logo-img-preview'); });
    $('#delete_landing_screen').live('click',  function(e) { delete_image(e, 'landing', 'landing-img-preview'); });



	$("#img-list").dialog({
	    autoOpen: false,
    	resizable: false,
    	height: 'auto',
    	width: 500,
    	modal: true,
    	title: "Pick an image"
	});

	$("#img-list-button").click(function() {
		var merchant_name = $('#mname').val();
        var url = "<?=Config::get('base_url')?>api/merchant/images?login_hash=<?=$login_hash?>&merchant_name="+merchant_name;
        var showDialog = true;

        if (img_merchant_name != merchant_name) {
            $("#img-list ul").html('');
    
            if (merchant_name.length >= 3) {        
            	$.get(url, {name: name}, function(data) {
            	    if (data == null) {
                        $('#img-list .no-preloaded-images').show();
                        $('#img-list ul').hide();
                    } else {
                        $('#img-list .no-preloaded-images').hide();
                        $('#img-list ul').show();
                    
                        for (var key in data) {
                            $("#img-list ul").append('<li class="image-item img-item" image-name="'+data[key].imgName+'"></li>');
                            $("#img-list ul li:last").append('<img src="'+data[key].imgUrl+'" />');
                        } 
                    }
                }, 'json');
            } else {
                alert('Merchant name should have 3 or more characters to find preloaded landing images.');
                showDialog = false;
            }
        }

        if (showDialog) {
            $("#img-list").dialog("open");
        }

        img_merchant_name = merchant_name;
	});

	$(".img-item").live('click', function() {
	    var imgName = $(this).attr('image-name');
	    var imgUrl = $(this).find('img').attr('src');
	    $('#landing_screen_img').val(imgName);
	    // Destroy the jcrop object if present
        if (jcrop_api.landing) {
            jcrop_api.landing.destroy();
        }
	    $('#landing-img-preview').replaceWith($('<img/>', {
		    id: "landing-img-preview",
		    src: imgUrl
	    }));
	    $("#img-list").dialog("close");
	    // Empty the file input to prevent upload
	    var file_control = $('input[name="landing"]');
	    file_control.replaceWith( file_control = file_control.clone( true ) );
	    
	});

    $( "#setup-instagram-dialog" ).dialog({
        autoOpen: false,
        width:500,
        modal: true,
        buttons: {
            "Ok": function() {
                $( this ).dialog( "close" );
            }
        }
    });

    jQuery.validator.addMethod("countryCheck", function(value, element) { 
        return $('#country_id').val(); 
    }, "Please select a country");

    jQuery.validator.addMethod("facebookUrl", function(value, element) {
    	var regex = /^(http(s)?:\/\/)?(www.)?facebook.com\/(pages\/)?\w[\w\.\-]*(\/\w[\w\.]*)?$/;
        return $('#social_facebook').val() == "" || $('#social_facebook').val().match(regex); 
    }, "Please enter a valid FB url");

    jQuery.validator.addMethod("foursquareUrl", function(value, element) {
    	var regex = /^(http(s)?:\/\/)?(www.)?foursquare.com\/v\/\w+?\/\w+?$/;
        return $('#social_foursquare').val() == "" || $('#social_foursquare').val().match(regex); 
    }, "Please enter a valid Foursquare url");

    jQuery.validator.addMethod("twitterId", function(value, element) {
    	var regex = /^@\w+$/;
        return $('#social_twitter').val() == "" || $('#social_twitter').val().match(regex); 
    }, "Please enter a valid Twitter ID");

    jQuery.validator.addMethod("phoneNumber", function(value, element) {
    	var regex = /^([\+][0-9]{1,3}[ \.\-])?([\(]{1}[0-9]{2,6}[\)])?([0-9 \.\-\/]{3,20})((x|ext|extension)[ ]?[0-9]{1,4})?$/;
        return $('#phone').val() == "" || $('#phone').val().match(regex); 
    }, "Please enter a valid phone number");
    
    var validator = $("#wizard1").validate({
        focusInvalid: false,
        invalidHandler: function(form, validator) {
            if (!validator.numberOfInvalids())
                return;
            $('html, body').animate({
                scrollTop: $(validator.errorList[0].element).offset().top
            }, 1500);
        }
    });

    $("#wizard1").on("submit", function(e) {
        console.log("here");
        if (! validator.numberOfInvalids() && !$('#country_id').valid()) {
            $('html, body').animate({
                scrollTop: $('#countryWrapper').offset().top
            }, 1500);
            e.preventDefault();
        }
    });

	$( "#coords_dialog" ).dialog({
		autoOpen: false,
    	resizable: false,
    	height:175,
    	width:500,
    	modal: true,
    	buttons: {
        	"Accept": function() {
                $('#latitude').val($(this).data("latitude"));
                $('#longitude').val($(this).data("longitude"));
    		    $( this ).dialog( "close" );
        	},
        	"Cancel": function() {
        	    $( this ).dialog( "close" );
        	}
    	}
	});
});


$('.day_checkbox').live('change', function(e) {
    markHoursAsModified();
})

function markHoursAsModified() {
    $('#inherited_hours').val('0');
}

function getTime(hours, minutes) {
    var time = null;
    minutes = minutes + "";
    if (hours < 12) {
        time = "AM";
    }
    else {
        time = "PM";
    }
    if (hours == 0) {
        hours = 12;
    }
    if (hours > 12) {
        hours = hours - 12;
    }
    if (minutes.length == 1) {
        minutes = "0" + minutes;
    }

    return hours + ":" + minutes + time;
}

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
                $('.remove-checkbox.with-delete-link').show();
                
                if (!$(this).hasClass('no-crop')) {
                    $('#deleted_image_' + name).val(0);
                    $('#' + name + '-img-preview-height').val($('#' + name + '-img-preview').height());
                    $('#' + name + '-img-preview-width').val($('#' + name + '-img-preview').width());

                    initJCrop($('#' + name + '-img-preview'), name);
                }
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
        allowSelect: false
    };
	
	selector.Jcrop(jcrop_options, function() {
	    jcrop_api[name] = this;
	});
}

$("#setup-instagram").click(function() {
	 $( "#setup-instagram-dialog" ).dialog("open");
});

$('#st').live('change', function(e){
    var state = $(this).val();

    if (tz_by_state[state.toUpperCase()]) {
        if ($('#timezone option[value="' + tz_by_state[state.toUpperCase()] + '"]').length > 0) {
            $('#timezone option[value="' + tz_by_state[state.toUpperCase()] + '"]').attr('selected', 'selected');
            $('#timezone').trigger("liszt:updated");
        }
    }
});

$('#address').change(update_address_fields);
$('#country_id').change(function() {
    update_address_fields();
    
    // Google Api returns different timezone names in some countries (ie argentina).   
    if ($(this).val() == '24') {
        $('#timezone option').removeAttr('selected');
        $('#timezone option[value="America/Argentina/Buenos_Aires"]').attr('selected', 'selected');
        $('#timezone').trigger("liszt:updated");
    }
});
$('#city').change(update_address_fields);

<?php if ($action == 'add' && !is_null($merchant->mall_id)): ?>  
    $("#micello_search").autocomplete( "option", "autoFocus", true );
    $('#mname').live('keyup', function(e) {
        $('#micello_search').customautocomplete('search', $(this).val());
    });
    $('#mname').live('blur', function(e) {
        if ($('.ui-autocomplete li').length == 1 && $('.ui-autocomplete .ui-state-hover').length) {
            $('.ui-autocomplete .ui-state-hover').click();
        } else {
            $("#micello_search").autocomplete("close");
        }
    });
<?php endif; ?>

</script>
