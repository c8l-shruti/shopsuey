<!-- mall Info -->
<?php if ($action == 'edit') {
    $url = "admin/mall/{$mall->id}/edit";
} else {
    $url = "admin/mall/add";
}
?>
<form id="wizard1" action="<?=Uri::create($url, array(), Input::get())?>" method="POST" enctype="multipart/form-data">
<input type="hidden" id="action" name="action" value="<?=$action?>" />
<input type="hidden" id="content" name="content" value="<?=@$mall->content?>" />
<input type="hidden" id="market_place_type" name="market_place_type" value="<?=@$mall->market_place_type?>" />
<input type="hidden" id="logo" name="logo" value="<?=@$mall->logo?>" />
<input type="hidden" id="landing_screen_img" name="landing_screen_img" value="<?=@$mall->landing_screen_img?>" />
<input type="hidden" id="plan" name="plan" value="<?=@$mall->plan?>" />
<input type="hidden" id="max_users" name="max_users" value="<?=@$mall->max_users?>" />
<input type="hidden" id="newsletter" name="newsletter" value="" />

<?=CMS::create_nonce_field('user_'.$action, 'nonce')?>

<div class="fluid">
	<div class="grid8">
		<div class="widget"><!-- Mall Info -->
			<div class="whead">
				<h6><span class="icon-info-3"></span>Marketplace Info</h6>
				<div class="clear"></div>
			</div>

			<fieldset class="formpart">
				<div class="formRow">
					<div class="grid3"><label for="mname">Marketplace Name:</label></div>
					<div class="grid9">
						<input type="text" id="mname" name="name" placeholder="ABC Properties LLC" value="<?=@$mall->name?>" class="required" />
					</div>
					<div class="clear"></div>
				</div>

				<div class="formRow">
					<div class="grid3"><label for="contact">Contact Name:</label></div>
					<div class="grid9">
						<input type="text" id="contact" name="contact" placeholder="Juan Julio" value="<?=@$mall->contact?>" />
					</div>
					<div class="clear"></div>
				</div>

				<div class="formRow">
					<div class="grid3"><label for="phone">Contact Phone:</label></div>
					<div class="grid9">
						<input class="phoneNumber" type="text" id="phone" name="phone" placeholder="888-555-1234 ext 567" value="<?=@$mall->phone?>" />
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow">
					<div class="grid3"><label for="email">Contact Email:</label></div>
					<div class="grid9">
						<input type="email" id="email" name="email" placeholder="juan@abcproperties.com" value="<?=@$mall->email?>" />
					</div>
					<div class="clear"></div>
				</div>

				<div class="formRow">
					<div class="grid3"><label for="web">Website:</label></div>
					<div class="grid9">
						<input type="text" id="web" name="web" placeholder="http://www.abcproperties.com" value="<?=@$mall->web?>" />
					</div>
					<div class="clear"></div>
				</div>
                
                <div class="formRow">
					<div class="grid3"><label for="web">No. of merchants:</label></div>
					<div class="grid9">
						<?php echo (isset($mall->merchant_count) ? $mall->merchant_count : 0 ) ?>
					</div>
					<div class="clear"></div>
				</div>
				
				<!-- <div class="formRow">
					<div class="grid7"><label for="is_customer">Is ShopSuey Customer:</label></div>
					<div class="grid5">
						<input type="checkbox" id="is_customer" name="is_customer" value="1" <?=(@$mall->is_customer) ? 'checked="checked"' : ''?> />
					</div>
					<div class="clear"></div>
				</div> -->
			</fieldset>
		</div>
		<!--div class="widget">
			<div class="whead">
				<h6><span class="icon-document"></span>Description</h6>
				<div class="clear"></div>
			</div>

			<fieldset class="formpart">
				<div class="formRow">
					<textarea name="description" rows="6"><?=@$mall->description?></textarea>
				</div>
			</fieldset>
		</div -->
		<div class="widget"><!-- Address Info -->
			<div class="whead">
				<h6><span class="icon-home"></span>Address Info</h6>
				<div class="clear"></div>
			</div>

			<fieldset class="formpart">
				<div class="formRow">
					<div class="grid3"><label for="address">Address:</label></div>
					<div class="grid9">
						<input type="text" id="address" name="address" placeholder="123 Sesame Str." value="<?=@$mall->address?>" />
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow">
					<div class="grid3"><label for="city">City:</label></div>
					<div class="grid9">
						<input type="text" id="city" name="city" placeholder="New York City" value="<?=@$mall->city?>" />
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow">
					<div class="grid3"><label for="st">State:</label></div>
					<div class="grid9">
						<input type="text" id="st" name="st" placeholder="NY" size="2" maxlength="2" value="<?=@$mall->st?>" />
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow">
					<div class="grid3"><label for="country_id">Country:</label></div>
			        <div class="searchDrop grid9" id="countryWrapper">
    					<select  id="country_id" class="fullwidth countryCheck" data-placeholder="Select your country" name="country_id">
    						<?php foreach(CMS::countries() as $country) : ?>
    						<option value="<?=$country->id?>" <?=$country->id == @$mall->country_id ? 'selected="selected"' : '' ?>><?=$country->name?> (<?=$country->code?>)</option>
    						<?php endforeach; ?>
    					</select>
					</div>
					<div class="clear"></div>
			    </div>
				<div class="formRow">
					<div class="grid3"><label for="zip">Zip:</label></div>
					<div class="grid9">
						<input type="text" pattern="\d{5}" maxlength="5" id="zip" name="zip" placeholder="10458" value="<?=@$mall->zip?>" />
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow">
					<div class="grid2"><label for="latitude">Latitude:</label></div>
					<div class="grid4">
                        <input type="number" step="any" id="latitude" name="latitude" placeholder="37.787125" value="<?=@$mall->latitude?>" class="location-field required" />
					</div>
					<div class="grid2"><label for="longitude">Longitude:</label></div>
					<div class="grid3">
                        <input type="number" step="any" id="longitude" name="longitude" placeholder="-122.425412" value="<?=@$mall->longitude?>"  class="location-field required" />
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
                            <option value="<?= $timezone; ?>" <?php if (@$mall->timezone && $timezone == $mall->timezone): ?>selected="selected"<?php endif; ?>><?= $timezone; ?></option>
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
                        <input type="checkbox" name="hours[<?=$i?>][sun]" <?= in_array('sun', $hours[$i]['days']) ? 'checked' : '' ?>/> S&nbsp;&nbsp;
                        <input type="checkbox" name="hours[<?=$i?>][mon]" <?= in_array('mon', $hours[$i]['days']) ? 'checked' : '' ?>/> M&nbsp;&nbsp;
                        <input type="checkbox" name="hours[<?=$i?>][tue]" <?= in_array('tue', $hours[$i]['days']) ? 'checked' : '' ?>/> T&nbsp;&nbsp;
                        <input type="checkbox" name="hours[<?=$i?>][wed]" <?= in_array('wed', $hours[$i]['days']) ? 'checked' : '' ?>/> W&nbsp;&nbsp;
                        <input type="checkbox" name="hours[<?=$i?>][thr]" <?= in_array('thr', $hours[$i]['days']) ? 'checked' : '' ?>/> T&nbsp;&nbsp;
                        <input type="checkbox" name="hours[<?=$i?>][fri]" <?= in_array('fri', $hours[$i]['days']) ? 'checked' : '' ?>/> F&nbsp;&nbsp;
                        <input type="checkbox" name="hours[<?=$i?>][sat]" <?= in_array('sat', $hours[$i]['days']) ? 'checked' : '' ?>/> S&nbsp;&nbsp;
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
		</div>

	</div>
	<div class="grid4">
		<div class="widget"><!-- Publish -->
			<div class="whead">
				<h6><span class="icon-cog"></span>Publish</h6>
				<div class="clear"></div>
			</div>
			<fieldset class="formpart">
				<?php if ($action == 'edit') : ?>
				<div class="formRow">
					<select id="status" name="status" class="fullwidth" data-placeholder="Status...">
						<option value="1" <?=(@$mall->status == 1) ? 'selected="selected"' : ''?>>Active</option>
						<option value="2" <?=(@$mall->status == 2) ? 'selected="selected"' : ''?>>Inactive</option>
						<option value="<?=Model_Location::STATUS_SIGNUP?>" <?=(@$merchant->status == Model_Location::STATUS_SIGNUP) ? 'selected="selected"' : ''?>>Pending From Signup</option>
						<option value="0" <?=(@$mall->status == 0) ? 'selected="selected"' : ''?>>Deleted</option>
					</select>
				</div>
				<?php endif; ?>

				<div class="formRow">
					<button class="buttonL bGreyish fluid" type="submit"><?=($action == 'add') ? '<i class="iconb" data-icon="&#xe099;"></i> &nbsp; Add marketplace' : '<i class="iconb" data-icon="&#xe097;"></i> &nbsp; Save marketplace'?></button>
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
						<input type="text" id="micello_id" name="micello_info[micello_id]" value="<?=@$mall->micello_info->micello_id?>" />
					</div>
					<div class="clear"></div>
				</div>
				<?php if ($action == 'add') : ?>
				<div class="formRow">
					<div class="grid9"><label for="sync_merchants">Auto Create Merchants:</label></div>
					<div class="grid3">
						<input type="checkbox" id="sync_merchants" name="sync_merchants" value="1" />
					</div>
					<div class="clear"></div>
				</div>
				<?php endif; ?>
				<div class="formRow">
					<div class="grid9"><label for="set_merchants_coords">Update Merchants coordinates:</label></div>
					<div class="grid3">
						<input type="checkbox" id="set_merchants_coords" name="set_merchants_coords" value="1" />
					</div>
					<div class="clear"></div>
				</div>
				<!-- div class="formRow">
					<div class="grid9"><label for="clear_map_cache">Clear cached map info:</label></div>
					<div class="grid3">
						<input type="checkbox" id="clear_map_cache" name="clear_map_cache" value="1" />
					</div>
					<div class="clear"></div>
				</div -->
				<div class="formRow">
					<button class="buttonL bBlack fluid" id="micello_populate" type="button">Populate from Micello</button>
				</div>
				<?php if ($action == 'edit') : ?>
				<div class="formRow">
					<button class="buttonL bBlue fluid" id="micello-import" type="button">Update Micello Map</button>
				</div>
				<?php endif; ?>
			</fieldset>
		</div>
		
		<div id="dialog-confirm" title="Overwrite changes to the marketplace?">
            <p>
                <span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>
                There are local changes to the marketplace information that differ from Micello's data.<br>Which fields would you like to populate?
            </p>
        </div>

		<div id="no-micello-info" title="No Micello info">
            <p>
                <span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>
                No info was fetched from Micello. Please perform a search to use this feature
            </p>
        </div>

		<!-- class="widget">
			<div class="whead">
				<h6><span class="icon-cart"></span>Subscription</h6>
				<div class="clear"></div>
			</div>

			<fieldset class="formpart">
				<div class="formRow">
					<div class="grid7"><label for="is_customer">Is ShopSuey Customer:</label></div>
					<div class="grid5">
						<input type="checkbox" id="is_customer" name="is_customer" value="1" <?=(@$mall->is_customer) ? 'checked="checked"' : ''?> />
					</div>
					<div class="clear"></div>
				</div>
			</fieldset>
		</div -->
		
		<div class="widget"><!-- Social Media -->
			<div class="whead">
				<h6><span class="icon-share-3"></span>Social Media</h6>
				<div class="clear"></div>
			</div>
				<div class="formRow">
					<div class="grid4"><label for="social_facebook">Facebook Page:</label></div>
					<div class="grid8">
                        <input class="facebookUrl" type="text" id="social_facebook" name="social[facebook]" value="<?=@$mall->social->facebook?>" />
					</div>
					<p class="example">e.g: https://www.facebook.com/GetShopSuey</p>
					<div class="clear"></div>
				</div>
				<div class="formRow">
					<div class="grid4"><label for="social_foursquare">Foursquare:</label></div>
					<div class="grid8">
						<input class="foursquareUrl" type="text" id="social_foursquare" name="social[foursquare]" value="<?=@$mall->social->foursquare?>" />
					</div>
					<p class="example">e.g: https://foursquare.com/v/goodwill/4d195c93b15c5bc221</p>
					<div class="clear"></div>
				</div>
				<div class="formRow">
					<div class="grid4"><label for="social_twitter">Twitter:</label></div>
					<div class="grid8">
						<input class="twitterId" type="text" id="social_twitter" name="social[twitter]" value="<?=@$mall->social->twitter?>" />
					</div>
					<p class="example">e.g: @shopsueyapp</p>
					<div class="clear"></div>
				</div>
                <div class="formRow">
					<div class="grid7"><label for="default_social">Default Social Fields:</label></div>
					<div class="grid5">
						<input type="checkbox" id="default_social" name="default_social" value="1" <?=(@$mall->default_social) ? 'checked="checked"' : ''?> />
					</div>
					<div class="clear"></div>
				</div>
		</div>

		<div class="widget"><!-- Logo -->
			<div class="whead"><h6><span class="icon-picture"></span>Logo</h6><div class="clear"></div></div>
			<fieldset>
				<div class="formRow check">
					<div class="grid12">
				    <?php if (@$mall->logo) : ?>
                        <?= Asset::img(Config::get('cms.logo_images_path').DS.'small_'.$mall->logo, array('id' => 'logo-img-preview')); ?>
                    <?php else: ?>
                        <img src="" id="logo-img-preview">
					<?php endif; ?>
                    </div>
					<div class="grid12">
                        <input type="file" name="logo" />
                        <?php if (isset($mall->id) && $mall->id): ?>
                        <button class="buttonL bRed" id="delete_logo" href="<?=Uri::create("api/mall/{$mall->id}/delete_photo/logo");?>?login_hash=<?php echo $login_hash; ?>" type="button">Delete logo</button>
                        <?php else: ?>
                        <button class="buttonL bRed" id="delete_logo" href="#" type="button">Delete logo</button>
                        <?php endif; ?>
                    </div>
					<div class="clear"></div>
                    
                    <input type="hidden" name="deleted_image_logo" id="deleted_image_logo" value="0"/>
                    
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
						<input type="checkbox" id="default_logo" name="default_logo" value="1" <?=(@$mall->default_logo) ? 'checked="checked"' : ''?> />
					</div>
					<div class="clear"></div>
				</div>
			</fieldset>
		</div>
        <div class="widget"><!-- landing screen image -->
			<div class="whead"><h6><span class="icon-picture"></span>Landing Screen Image</h6><div class="clear"></div></div>
			<fieldset>
				<div class="formRow check">
					<div class="grid12">
				    <?php if (@$mall->landing_screen_img) : ?>
                        <?= Asset::img(Config::get('cms.landing_images_path').DS.'small_'.$mall->landing_screen_img, array('id' => 'landing-img-preview')); ?>
                    <?php else: ?>
                        <img src="" id="landing-img-preview">
                    <?php endif; ?>
                    </div>
					<div class="grid12">
                        <input type="file" name="landing" />
                        <?php if (isset($mall->id) && $mall->id): ?>
                        <button class="buttonL bBlue" id="delete_landing_screen" href="<?=Uri::create("api/mall/{$mall->id}/delete_photo/landing");?>?login_hash=<?php echo $login_hash; ?>" type="button">Delete landing screen</button>
                        <?php else: ?>
                        <button class="buttonL bBlue" id="delete_landing_screen" href="#" type="button">Delete landing screen</button>
                        <?php endif; ?>
                    </div>
					<div class="clear"></div>
                    
                    <input type="hidden" name="deleted_image_landing" id="deleted_image_landing" value="0"/>
                    
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
						<input type="checkbox" id="default_landing_screen_img" name="default_landing_screen_img" value="1" <?=(@$mall->default_landing_screen_img) ? 'checked="checked"' : ''?> />
					</div>
					<div class="clear"></div>
				</div>
			</fieldset>
		</div>
        
		<div class="widget">
			<div class="whead"><h6><span class="icon-picture"></span>Instagram Integration</h6><div class="clear"></div></div>
			<fieldset class="formpart">
				<div class="formRow">
					<div class="grid9"><label for="use_instagram">Use Instagram feed:</label></div>
					<div class="grid3">
						<input type="checkbox" id="use_instagram" name="use_instagram" value="1" <?=(@$mall->use_instagram) ? 'checked="checked"' : ''?> <?=(!$instagram_set) ? 'disabled="disabled"' : ''?>/>
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
        
		<div class="widget"><!-- Tags -->
			<div class="whead"><h6><span class="icon-tag"></span>Tags</h6><div class="clear"></div></div>
			<fieldset>
				<div class="formRow">
					<input type="text" id="tags" name="tags" class="tags" value="<?=@$mall->tags?>" />
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
    						<?php $mallCategories = isset($mall->category_ids) ? @$mall->category_ids : array() ?>
    						<?php foreach(CMS::categories() as $category) : ?>
    						<option value="<?=$category->id?>" <?=(@in_array($category->id, $mallCategories)) ? 'selected="selected"' : '' ?>><?=$category->name?></option>
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
                                            <?php $profilings = isset($mall->profilings) ? $mall->profilings : array() ?>
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
                
		<div class="widget"><!-- Publish -->
			<div class="whead">
				<h6><span class="icon-cog"></span>Publish</h6>
				<div class="clear"></div>
			</div>
			<fieldset class="formpart">
				<div class="formRow">
					<button class="buttonL bGreyish fluid" type="submit"><?=($action == 'add') ? '<i class="iconb" data-icon="&#xe099;"></i> &nbsp; Add marketplace' : '<i class="iconb" data-icon="&#xe097;"></i> &nbsp; Save marketplace'?></button>
				</div>

			</fieldset>
		</div>

	</div>
</div>
</form>

<div id="micello-import-dialog" title="Micello Import">
    <div class="wrapper">
        <p id="micello-import-loading">
            Please wait while the info from Micello is loaded&nbsp;
            <span><?= Asset::img('elements/loaders/1s.gif'); ?></span>
        </p>
        <div class="widget" id="micello-import-data-table">
            <div class="whead">
                <h6>Import info</h6>
                <div class="clear"></div>
            </div>
            <form action="<?=$update_merchants_url?>" method="POST" id="update-merchants">
                <table class="tDefault" width="100%" cellspacing="0" cellpadding="0">
                    <thead>
                        <tr>
                            <td><input type="checkbox" id="select-all"/></td>
                            <td width="25%">Type</td>
                            <td>Name</td>
                            <td>Diffs</td>
                            <td>Action</td>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</div>

<style type="text/css">
#countryWrapper .error {
    padding-bottom: 10px;
}
</style>

<script src="https://maps.googleapis.com/maps/api/js?libraries=places&v=3.exp&sensor=false"></script>

<script type="text/javascript">

function all_fields_empty(data) {
	return !($("#address").val() ||
        $("#city").val() ||
    	$("#st").val() ||
    	$("#zip").val() ||
    	$("#latitude").val() ||
    	$("#longitude").val() ||
    	$("#mname").val());
};

function data_changed(data) {
	return $("#address").val() != data.street1 ||
        $("#city").val() != data.city ||
    	$("#st").val() != data.state ||
    	$("#zip").val() != data.zipcode ||
    	$("#latitude").val() != data.lat ||
    	$("#longitude").val() != data.lon ||
    	$("#mname").val() != data.name;
};

function update_data(data, empty_only) {
	if (! $("#st").val() || ! empty_only) {
		$("#st").val(data.state);
		$("#st").change();
    }
    if (! $("#address").val() || ! empty_only) {
		$("#address").val(data.street1);
		$("#address").change();
    }
	if (! $("#city").val() || ! empty_only) {
		$("#city").val(data.city);
		$("#city").change();
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
	if (! $("#mname").val() || ! empty_only) {
		$("#mname").val(data.name);
    }
}

var micello_data = null;

$("#micello_search_loader").hide();

$( "#micello_search" ).customautocomplete({
	delay: 500,
	minLength: 3,
	source: "<?=Config::get('base_url')?>api/location/micello_community/?login_hash=<?=$login_hash?>&type=<?=urlencode("Shopping Mall|Convention Center")?>",
	select: function(event, ui) {
		micello_data = ui.item.data;
		$("#micello_populate").removeClass('bBlack');
		$("#micello_populate").addClass('bBlue');
		$("#micello_populate").prop("disabled", false);
		$("#micello_id").val(micello_data.id);
    },
	search: function(event, ui) {
		$("#micello_search_loader").show();
	}
});

$( "#micello_id" ).customautocomplete({
	delay: 500,
	minLength: 1,
	source: "<?=Config::get('base_url')?>api/location/micello_community/?login_hash=<?=$login_hash?>&type=<?=urlencode("Shopping Mall|Convention Center")?>&search_by=id",
	select: function(event, ui) {
		micello_data = ui.item.data;
		$("#micello_populate").removeClass('bBlack');
		$("#micello_populate").addClass('bBlue');
		$("#micello_populate").prop("disabled", false);
		$("#micello_search").val(micello_data.name);
		$("#micello_id").val(micello_data.id);
		event.preventDefault();
    },
	search: function(event, ui) {
		$("#micello_search_loader").show();
	}	
});

$("#micello_search, #micello_id").bind("autocompletesearchcomplete", function(event, contents) {
	$("#micello_search_loader").hide();
});

$("#micello_search, #micello_id").keypress(function(event) {
	// Disable populate button except when selecting an entry
    if (event.which != 13) {
    	$("#micello_populate").addClass('bBlack');
    	$("#micello_populate").removeClass('bBlue');
    	$("#micello_populate").prop("disabled", true);
    }
});

$("#micello_populate").click(function() {
	if (! micello_data) {
		 $( "#no-micello-info" ).dialog("open");
	} else {
		if (!all_fields_empty() && data_changed(micello_data)) {
			$( "#dialog-confirm" ).dialog("open");
		} else {
			update_data(micello_data, false);
		}
	}
    return false;	
});

$("#micello_populate").prop("disabled", true);
		
$(function() {
	$( "#dialog-confirm" ).dialog({
		autoOpen: false,
    	resizable: false,
    	height:175,
    	width:500,
    	modal: true,
    	buttons: {
        	"Empty Fields": function() {
    			update_data(micello_data, true);
    		    $( this ).dialog( "close" );
        	},
            "All Fields": function() {
    			update_data(micello_data, false);
    		    $( this ).dialog( "close" );
        	},
        	Cancel: function() {
        	    $( this ).dialog( "close" );
        	}
    	}
	});

    $( "#no-micello-info" ).dialog({
        autoOpen: false,
        width:500,
        modal: true,
        buttons: {
            "Ok": function() {
                $( this ).dialog( "close" );
            }
        }
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

    micelloImport.init();

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

var jcrop_api = {
	"logo": null,
	"landing": null
}

$('input[type="file"]').change(function(e) {

	var name = $(this).attr('name');
	
    if (jcrop_api[name]) {
        jcrop_api[name].destroy();
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
    }
	
	selector.Jcrop(jcrop_options, function() {
	    jcrop_api[name] = this;
	});
}


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


$("#setup-instagram").click(function() {
	 $( "#setup-instagram-dialog" ).dialog("open");
});

var micelloImport = (function() {
	var dialog,
        merchants,
        entities,
        diffs,
        diff_fields = ['name',
                       'entity_id',
                       'geometry_id'/*,
                       'floor',
                       'phone',
                       'email',
                       'url',
                       'description'*/];

    var loadInfo = function() {
	    $.ajax({
	    	  dataType: "json",
	    	  url: "<?=$micello_import_url?>",
	    	  timeout: 100000
	    })
        .done(function( response ) {
            updateDialog(response.data);
        })
        .fail(function( xhr, status ) {
            alert("Failed to fetch info from micello");
        })
        .always(function() {
    	    $( "#micello-import-loading" ).hide();
	    });
    };

	var updateDialog = function(data) {
        diffs = data.import_info;
        merchants = data.merchants;
        entities = data.entities;
        for (var key in diffs) {
            $( "#micello-import-data-table tbody" ).append(getRow(diffs[key]));
        }
        refreshSelects();
	    $( "#micello-import-data-table" ).show();
    }

	var getTypeName = function(status) {
		if (status == 'match') {
			return 'Match found';
		} else if (status == 'conflict') {
			return 'Conflict Found';
		} else if (status == 'additional_location') {
			return 'Only on ShopSuey';
	    } else if (status == 'new_entity') {
			return 'Only on Micello';
	    }
    }

	var getRowStatus = function(type, diffs) {
		if (type == 'match') {
			return diffs.length == 0 ? 'match' : 'conflict';
		} else {
			return type;
		}
    };
	
	var getRow = function(rowData) {
        var row = $('<tr>', {
            "id": rowData.info.id
        });

        var rowStatus = getRowStatus(rowData.status, rowData.diffs);

		var row_checkbox = $('<input>', {
			"type": "checkbox",
			"name": "process_" + rowData.info.id,
			"checked": rowStatus == 'conflict',
			"class": "row-selection"
		});
		
        row.append($('<td>').append(row_checkbox));
		
        // Type
        row.append($('<td>', {
			"text": getTypeName(rowStatus),
			"class": rowStatus
	    }));

	    // Name
		row.append($('<td>', {
			"text": rowData.info.name + ' (' + rowData.info.id.split('_').pop() + ')',
			"class": rowStatus + ' name'
	    }));

	    // Diffs
		var diffs_td = $('<td>');

		var diffs = $('<div>', {
			"title": "Differences",
			"html": getDiffsHtml(rowData, diffs_td)
		});

		diffs_td.append(diffs);

		diffs.dialog({
			autoOpen: false,
	    	resizable: false,
	    	height:500,
	    	width:800,
	    	modal: true,
	    	buttons: {
	            "Dismiss": function() {
	        	    $( this ).dialog( "close" );
	        	}
	    	}
	    });

		var button_color = rowData.diffs.length == 0 ? 'bBlack' : 'bBlue';
		var diff_button = $('<button>', {
			"class": "buttonL fluid " + button_color,
			"type": "button",
			"text": "View",
			"disabled": rowData.diffs.length == 0
		}).click(function() {
			diffs.dialog( "open" );
		});

		diffs_td.append(diff_button).appendTo(row);

		// Actions
		var actions_td = $('<td>').html(getRowActions(rowData));

		row.append(actions_td);
		
	    return row;
    }

    var getRowActions = function(rowData) {
        var rowId = rowData.info.id;
        var status = rowData.status;

        var container = $('<div>');
        container.append($('<input type="hidden" name="imports[]" value="' + rowId + '">'));
        container.append($('<input type="hidden" name="type_' + rowId + '" value="' + status + '">'));

		if (status == 'match') {
			var button = $('<button>', {
				"class": "buttonL bBlack fluid",
				"type": "button",
				"text": "Remove Match"
			}).click(function() {
				removeMatch(rowId);
			});
			container.append(button);
		} else if (rowData.status == 'additional_location') {
			var select = $('<select>', {
				"class": "entity-select fullwidth select",
				"id": "select_" + rowId
			});
		    var button = $('<button>', {
				"class": "buttonL bBlack fluid",
				"type": "button",
				"text": "Match with Entity"
			}).click(function() {
				addMatchWithEntity(rowId);
			});
			container.append(select).append(button);
		} else if (rowData.status == 'new_entity') {
			var select = $('<select>', {
				"class": "merchant-select fullwidth select",
				"id": "select_" + rowId
			});
		    var button = $('<button>', {
				"class": "buttonL bBlack fluid",
				"type": "button",
				"text": "Match with Merchant"
			}).click(function() {
				addMatchWithLocation(rowId);
			});
			container.append(select).append(button);
	    }

	    return container;
    }

    var getDiffsHtml = function(rowData, diffs_td) {
        var rowId = rowData.info.id;

        var table = $('<table>', {
        	"class": "tDefault",
        	"width": "100%",
        	"cellspacing": "0",
        	"cellpadding": "0"
        }).append('<thead><tr><td>Type</td><td>ShopSuey</td><td>Micello</td><td>Action</td></tr></thead>');

        var tbody = $('<tbody>');
        
        for(var key in rowData.diffs) {
            var diffs = rowData.diffs[key];
            var tr = $('<tr>');
            $('<td>', { 'text': key }).appendTo(tr);
            $('<td>', { 'text': diffs.location }).appendTo(tr);
            $('<td>', { 'text': diffs.micello }).appendTo(tr);
            var value = $('<input>', {
                'type': 'hidden',
                'name': key + '_' + rowId,
                'value': diffs.micello
            });
            var overwrite = $('<input>', {
                'type': 'hidden',
                'name': 'update_' + key + '_' + rowId,
                'id': 'update_' + key + '_' + rowId,
                'value': diffs.micello != '' ? '1' : '0'
            });
            var action;
            if (rowData.status == 'match') {
                action = $('<input>', {
                    'type': 'checkbox',
                    'data-update': 'update_' + key + '_' + rowId,
                    'value': '1',
                    'checked': diffs.micello != '',
                    'class': 'update-checkbox'
                }).after($('<label class="mr20">Update from Micello</label>'));
            } else if (rowData.status == 'additional_location') {
                action = $('<span>No match from Micello</span>');
            } else if (rowData.status == 'new_entity') {
                action = $('<span>No match from ShopSuey</span>');
            }
            diffs_td.append(value).append(overwrite);
            $('<td>', {
                'width': '25%'
            }).append(action).appendTo(tr);
            tr.appendTo(tbody);
        }

        tbody.appendTo(table);
        return table;
    }
	
    var saveMerchants = function() {
        $('#update-merchants').submit();
        dialog.dialog( "close" );
    }

	var removeMatch = function(rowId) {
		// Fetch diff from collection and remove it
		var diff = diffs[rowId];
	    delete(diffs[rowId]);

		// Fetch merchant and entity from the corresponding collections
		var merchant = merchants[diff.info.id];
		var entity = entities[diff.info.match_id];
	    merchant.match_found = false;
	    entity.match_found = false;

	    // Build a fake diff for the entity and add it to the collection
	    var entity_diff = buildDiff(null, entity);
	    diffs[entity_diff.info.id] = entity_diff;

	    // Build a fake diff for the merchant and add it to the collection
	    var merchant_diff = buildDiff(merchant, null);
	    diffs[merchant_diff.info.id] = merchant_diff;

		// Add two new rows for the now unmatched location and entity
		$('#' + rowId).after(getRow(entity_diff));
		$('#' + rowId).after(getRow(merchant_diff));

		// Remove the match from the table
		$('#' + rowId).remove();

		// Update selects for unmatched entities and locations
		refreshSelects();
	}

	var addMatchWithEntity = function(rowId) {
		// Fetch fake diff from collection and remove it
	    var diff = diffs[rowId];
	    delete(diffs[rowId]);

		// Fetch merchant from the corresponding collection
	    var merchant = merchants[diff.info.id];
	    merchant.match_found = true;

	    // Get the entity to match from the select
	    var entity_id = $("#select_" + rowId).val();
	    var entity = entities[entity_id];
	    entity.match_found = true;

	    // Delete the fake diff from the collection
	    delete(diffs[entity_id]);

	    // Build a diff for the merchant and entity and add it to the collection
	    var match_diff = buildDiff(merchant, entity);
	    diffs[match_diff.info.id] = match_diff;

	    var rowElem = $('#' + rowId);
	    var rowEntity = $('#' + entity_id);

		// Add a new row for the manual match
		rowElem.after(getRow(match_diff));

		// Remove the unmatched rows
		rowElem.remove();
		rowEntity.remove();
	    
		// Update selects for unmatched entities and locations
		refreshSelects();
	}

	var addMatchWithLocation = function(rowId) {
		// Fetch fake diff from collection and remove it
	    var diff = diffs[rowId];
	    delete(diffs[rowId]);

		// Fetch entity from the corresponding collection
	    var entity = entities[diff.info.id];
	    entity.match_found = true;
	    
	    // Get the merchant to match from the select
	    var merchant_id = $("#select_" + rowId).val();
	    var merchant = merchants[merchant_id];
	    merchant.match_found = true;
	    
	    // Delete the fake diff from the collection
	    delete(diffs[merchant_id]);
	    
	    // Build a diff for the merchant and entity and add it to the collection
	    var match_diff = buildDiff(merchant, entity);
	    diffs[match_diff.info.id] = match_diff;

	    var rowElem = $('#' + rowId);
	    var rowMerchant = $('#' + merchant_id);

		// Add a new row for the manual match
		rowElem.after(getRow(match_diff));

		// Remove the unmatched rows
		rowElem.remove();
		rowMerchant.remove();
		
		// Update selects for unmatched entities and locations
		refreshSelects();
	}

    var getDiff = function(id) {
		return diffs[id];
    }
	
    var buildDiff = function(location, entity) {
        var diff = {
            'diffs': {}
        };
        var info_source, match;

        if (! location) {
            diff.status = 'new_entity';
            location = buildFakeInfo();
            info_source = entity;
        } else if (! entity) {
            diff.status = 'additional_location';
            entity = buildFakeInfo();
            info_source = location;
        } else {
            diff.status = 'match';
            info_source = location;
            match = entity;
        }

        diff.info = {
    		'id': info_source.id,
    		'name': info_source.name,
        };

        if (match) {
            diff.info.match_id = match.id;
        }
        
        for (var i= 0; i < diff_fields.length; i++) {
            var field = diff_fields[i];
            if (location[field] != entity[field]) {
                diff.diffs[field] = {
                    'location': location[field],
                    'micello': entity[field]
                };
            }
        }
   
        return diff;
    }

	var buildFakeInfo = function() {
		return {
            'name': '',
            'entity_id': '',
            'geometry_id': '',
            'floor': '',
            'phone': '',
            'email': '',
            'url': '',
            'description': ''
		}
	}

	var refreshSelects = function() {
		$("select.merchant-select option").remove();
        for (var key in merchants) {
            if (merchants[key].match_found) {
                continue;
            }
            var option = $('<option>', {
                'value': merchants[key].id,
                'text': merchants[key].name
            }).appendTo($("select.merchant-select"));
        }

		$("select.entity-select option").remove();
        for (var key in entities) {
            if (entities[key].match_found) {
                continue;
            }
            var option = $('<option>', {
                'value': entities[key].id,
                'text': entities[key].name
            }).appendTo($("select.entity-select"));
        }
	}
	
	return {
		init: function() {
			var self = this;

			dialog = $( "#micello-import-dialog" ).dialog({
    			autoOpen: false,
    	    	resizable: false,
    	    	height:600,
    	    	width:1000,
    	    	modal: true,
    	    	buttons: {
    	            "Update": function() {
    	    			saveMerchants();
    	        	},
    	        	"Cancel": function() {
    	        	    $( this ).dialog( "close" );
    	        	}
    	    	}
		    });

		    $( "#micello-import" ).click(function() {
			    $( "#micello-import-data-table" ).hide();
			    $( "#micello-import-data-table tbody" ).html("");
			    $( "#micello-import-loading" ).show();
        	    $( "#micello-import-dialog" ).dialog("open");
         	    loadInfo();
    	    });

		    $( "body" ).on( "click", 'input[type="checkbox"].update-checkbox', function() {
			    var input_to_update = $(this).attr("data-update");
			    $('#' + input_to_update).val($(this).prop("checked") ? '1' : '0');
	    	});

		    $( "#select-all" ).click(function() {
			    $("input.row-selection").prop("checked", $(this).prop("checked"));
		    });
		}
	};
}());

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


</script>
