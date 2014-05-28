<!-- Location Info -->
<?=$output?>

<form id="wizard1" method="POST" enctype="multipart/form-data">
<input type="hidden" id="action" name="action" value="<?=$action?>" />

<?=CMS::create_nonce_field('user_'.$action, 'nonce')?>

<div class="fluid">
	<div class="grid8">
		<div class="widget">
			<div class="whead">
				<h6><span class="icon-info-3"></span>Location Info</h6>
				<div class="clear"></div>
			</div>

			<fieldset class="formpart">
				<div class="formRow">
					<div class="grid3"><label for="mname">Location Name:</label></div>
					<div class="grid9">
						<input type="text" id="mname" name="name" placeholder="ABC Properties LLC" value="<?=@$location->name?>" />
					</div>
					<div class="clear"></div>
				</div>

				<div class="formRow">
					<div class="grid3"><label for="contact">Contact Name:</label></div>
					<div class="grid9">
						<input type="text" id="contact" name="contact" placeholder="Juan Julio" value="<?=@$location->contact?>" />
					</div>
					<div class="clear"></div>
				</div>

				<div class="formRow">
					<div class="grid3"><label for="phone">Contact Phone:</label></div>
					<div class="grid9">
						<input type="phone" id="phone" name="phone" placeholder="888-555-1234 ext 567" value="<?=@$location->phone?>" />
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow">
					<div class="grid3"><label for="email">Contact Email:</label></div>
					<div class="grid9">
						<input type="email" id="email" name="email" placeholder="juan@abcproperties.com" value="<?=@$location->email?>" />
					</div>
					<div class="clear"></div>
				</div>

				<div class="formRow">
					<div class="grid3"><label for="web">Website:</label></div>
					<div class="grid9">
						<input type="text" id="web" name="web" placeholder="http://www.abcproperties.com" value="<?=@$location->web?>" />
					</div>
					<div class="clear"></div>
				</div>
			</fieldset>
		</div>
		<div class="widget">
			<div class="whead">
				<h6><span class="icon-document"></span>Description</h6>
				<div class="clear"></div>
			</div>
			<fieldset class="formpart">
				<div class="formRow">
					<textarea name="content" rows="6"><?=@$location->content?></textarea>
				</div>
			</fieldset>
		</div>
		<div class="widget">
			<div class="whead">
				<h6><span class="icon-home"></span>Address Info</h6>
				<div class="clear"></div>
			</div>

			<fieldset class="formpart">
				<div class="formRow">
					<div class="grid3"><label for="mall">Mall:</label></div>
					<div class="grid9">
						<select class="fullwidth" id="mall" name="mall_id" data-placeholder="Select mall">
							<option></option>
							<?php foreach(CMS::malls($location->mall_id) as $mall) : ?>
							<option value="<?=$mall->id?>" <?=@$mall->selected?>><?=$mall->name?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow">
					<div class="grid3"><label for="address">Address:</label></div>
					<div class="grid9">
						<input type="text" id="address" name="address" placeholder="123 Sesame Str." value="<?=@$location->address?>" />
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow">
					<div class="grid3"><label for="city">City:</label></div>
					<div class="grid9">
						<input type="text" id="city" name="city" placeholder="New York City" value="<?=@$location->city?>" />
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow">
					<div class="grid3"><label for="address">ST:</label></div>
					<div class="grid9">
						<input type="text" id="st" name="st" placeholder="NY" size="2" maxlength="2" value="<?=@$location->st?>" />
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow">
					<div class="grid3"><label for="zip">Zip:</label></div>
					<div class="grid9">
						<input type="number" id="zip" name="zip" placeholder="10458" value="<?=@$location->zip?>" />
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow">
					<div class="grid3"><label for="gps_latitude">Latitude:</label></div>
					<div class="grid3">
						<input type="number" id="gps_latitude" name="gps[latitude]" placeholder="37.787125" value="<?=@$location->gps->latitude?>" />
					</div>
					<div class="grid3"><label for="gps_longitude">Longitude:</label></div>
					<div class="grid3">
						<input type="number" id="gps_longitude" name="gps[longitude]" placeholder="-122.425412" value="<?=@$location->gps->longitude?>" />
					</div>
					<div class="clear"></div>
				</div>
			</fieldset>
		</div>

		<div class="widget"><!-- Hours -->
			<div class="whead"><h6><span class="icon-history-2"></span>Hours</h6><div class="clear"></div></div>
			<fieldset>
				<div class="formRow">
					<div class="grid3"><label>Monday:</label></div>
					<div class="grid9">
						<span class="floatL mr5"><input type="text" name="hours[mon][open]" value="<?=@$location->hours->mon->open?>" class="timepicker" /></span>
						<span class="floatL mr5 inline-label">&ndash;</span>
						<span class="floatL"><input type="text" name="hours[mon][close]" value="<?=@$location->hours->mon->close?>" class="timepicker" /></span>
						<div class="clear"></div>
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow">
					<div class="grid3"><label>Tuesday:</label></div>
					<div class="grid9">
						<span class="floatL mr5"><input type="text" name="hours[tue][open]" value="<?=@$location->hours->tue->open?>" class="timepicker" /></span>
						<span class="floatL mr5 inline-label">&ndash;</span>
						<span class="floatL"><input type="text" name="hours[tue][close]" value="<?=@$location->hours->tue->close?>" class="timepicker" /></span>
						<div class="clear"></div>
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow">
					<div class="grid3"><label>Wednesday:</label></div>
					<div class="grid9">
						<span class="floatL mr5"><input type="text" name="hours[wed][open]" value="<?=@$location->hours->wed->open?>" class="timepicker" /></span>
						<span class="floatL mr5 inline-label">&ndash;</span>
						<span class="floatL"><input type="text" name="hours[wed][close]" value="<?=@$location->hours->wed->close?>" class="timepicker" /></span>
						<div class="clear"></div>
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow">
					<div class="grid3"><label>Thursday:</label></div>
					<div class="grid9">
						<span class="floatL mr5"><input type="text" name="hours[thr][open]" value="<?=@$location->hours->thr->open?>" class="timepicker" /></span>
						<span class="floatL mr5 inline-label">&ndash;</span>
						<span class="floatL"><input type="text" name="hours[thr][close]" value="<?=@$location->hours->thr->close?>" class="timepicker" /></span>
						<div class="clear"></div>
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow">
					<div class="grid3"><label>Friday:</label></div>
					<div class="grid9">
						<span class="floatL mr5"><input type="text" name="hours[fri][open]" value="<?=@$location->hours->fri->open?>" class="timepicker" /></span>
						<span class="floatL mr5 inline-label">&ndash;</span>
						<span class="floatL"><input type="text" name="hours[fri][close]" value="<?=@$location->hours->fri->close?>" class="timepicker" /></span>
						<div class="clear"></div>
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow">
					<div class="grid3"><label>Saturday:</label></div>
					<div class="grid9">
						<span class="floatL mr5"><input type="text" name="hours[sat][open]" value="<?=@$location->hours->sat->open?>" class="timepicker" /></span>
						<span class="floatL mr5 inline-label">&ndash;</span>
						<span class="floatL"><input type="text" name="hours[sat][close]" value="<?=@$location->hours->sat->close?>" class="timepicker" /></span>
						<div class="clear"></div>
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow">
					<div class="grid3"><label>Sunday:</label></div>
					<div class="grid9">
						<span class="floatL mr5"><input type="text" name="hours[sun][open]" value="<?=@$location->hours->sun->open?>" class="timepicker" /></span>
						<span class="floatL mr5 inline-label">&ndash;</span>
						<span class="floatL"><input type="text" name="hours[sun][close]" value="<?=@$location->hours->sun->close?>" class="timepicker" /></span>
						<div class="clear"></div>
					</div>
					<div class="clear"></div>
				</div>
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
					<select name="status" class="fullwidth" data-placeholder="Status...">
						<?php foreach(CMS::statuses(@$location->status) as $status) : ?>
						<option value="<?=$status->value?>" <?=@$status->selected?>><?=$status->label?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<?php endif; ?>

				<div class="formRow">
					<button class="buttonL bGreyish fluid" type="submit"><?=($action == 'add') ? '<i class="iconb" data-icon="&#xe099;"></i> &nbsp; Add Location' : '<i class="iconb" data-icon="&#xe097;"></i> &nbsp; Save Location'?></button>
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
						<input type="text" id="social_facebook" name="social[facebook]" value="<?=@$location->social->facebook?>" />
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow">
					<div class="grid4"><label for="social_foursqare">Foursquare:</label></div>
					<div class="grid8">
						<input type="text" id="social_foursqare" name="social[foursquare]" value="<?=@$location->social->foursquare?>" />
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow">
					<div class="grid4"><label for="social_pintrest">Pinterest:</label></div>
					<div class="grid8">
						<input type="text" id="social_pintrest" name="social[pintrest]" value="<?=@$location->social->pintrest?>" />
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow">
					<div class="grid4"><label for="social_twitter">Twitter:</label></div>
					<div class="grid8">
						<input type="text" id="social_twitter" name="social[twitter]" value="<?=@$location->social->twitter?>" />
					</div>
					<div class="clear"></div>
				</div>

			</fieldset>
		</div>
		<div class="widget"><!-- Newsletter -->
			<div class="whead">
				<h6><span class="icon-email"></span>Newsletter</h6>
				<div class="clear"></div>
			</div>
			<fieldset class="formpart">
				<div class="formRow">
					<input type="email" name="newsletter" value="<?=@$location->newsletter?>" placeholder="newsletter@email.com" />
				</div>
			</fieldset>
		</div>

		<div class="widget"><!-- Tags -->
			<div class="whead"><h6><span class="icon-tag"></span>Tags</h6><div class="clear"></div></div>
			<fieldset>
				<div class="formRow">
					<input type="text" id="tags" name="tags" class="tags" value="<?=@$location->tags?>" />
				</div>
			</fieldset>
		</div>

    </div>

	</div>
</div>

</form>