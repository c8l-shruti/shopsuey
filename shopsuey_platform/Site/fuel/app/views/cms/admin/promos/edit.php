<form id="wizard1" method="POST" enctype="multipart/form-data">
<?=CMS::create_nonce_field('user_add', 'nonce')?>

<div class="fluid">
	<div class="grid8">
		<div class="widget">
			<div class="whead">
				<h6><span class="icon-info-3"></span>Promo Info</h6>
				<div class="clear"></div>
			</div>

			<fieldset class="formpart">
				<div class="formRow">
					<div class="grid3"><label for="name">Name:</label></div>
					<div class="grid9">
						<input type="text" id="name" name="name" placeholder="Special Contest" value="<?=$contest->name?>" />
					</div>
					<div class="clear"></div>
				</div>
                
                                <div class="formRow">
					<div class="grid3"><label for="email">Start Date - Time:</label></div>
					<div class="grid9">
						<span class="floatL mr5"><input type="text" class="datepicker" id="date_start" name="date_start[]" data-max="#date_end" placeholder="date" value="<?=(@$contest->start_date) ? date('m/d/Y', strtotime(@$contest->start_date)) : date('m/d/Y')?>" /></span>
						<span class="floatL mr5 mt4 text-center">&ndash;</span>
						<span class="floatL"><input type="text" class="timepicker" name="date_start[]" placeholder="time" style="width: 70px !important;" value="<?=(@$contest->start_date) ? date('h:iA', strtotime(@$contest->start_date)) : date('h:iA')?>" /></span>
						<div class="clear"></div>
					</div>
					<div class="clear"></div>
				</div>
                            
                                <div class="formRow">
					<div class="grid3"><label for="email">End Date - Time:</label></div>
					<div class="grid9">
						<span class="floatL mr5"><input type="text" class="datepicker" id="date_end" name="date_end[]" data-min="#date_start" placeholder="date" value="<?=(@$contest->end_date) ? date('m/d/Y', strtotime(@$contest->end_date)) : date('m/d/Y', strtotime('+1weeks'))?>" /></span>
						<span class="floatL mr5 mt4 text-center">&ndash;</span>
						<span class="floatL"><input type="text" class="timepicker" name="date_end[]" placeholder="time" style="width: 70px !important;" value="<?=(@$contest->end_date) ? date('h:iA', strtotime(@$contest->end_date)) : date('h:iA')?>" /></span>
						<div class="clear"></div>
					</div>
					<div class="clear"></div>
				</div>
                            
                        </fieldset>
                    
                            <div class="whead">
				<h6><span class="icon-info-3"></span>How will a user enter the contest?</h6>
				<div class="clear"></div>
                            </div>
                    
                        <fieldset class="formpart">
                                
                            <div class="formRow">
                                <div class="grid3">
                                    
                                    <input <?=((!empty($contest->how_favorite_location_id))?"checked=checked":"")?> type="radio" id="favoriteLocation" name="how_enter" value="favorite"/>
                                    
                                    <label for="email">Favorite a location:</label>
                                    
                                </div>
                                
                                    <div class="grid9">
                                        
                                        <span class="floatL">
                                            
                                            <select id="how_favorite_location_id" name="how_favorite_location_id">
                                                
                                                <option value="null">Select a location...</option>
                                                
                                                <?php foreach(CMS::locations() as $location): ?>
                                                    <option value="<?=$location->id?>" <?=(($contest->how_favorite_location_id == $location->id)?"selected=selected":"")?> ><?=$location->name?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            
                                        </span>
                                        
                                        <div class="clear"></div>
                                    </div>
                                    <div class="clear"></div>
                            </div>
                            
                            <div class="formRow">
                                    <div class="grid3">
                                        
                                        <input <?=((!empty($contest->how_checkin_location_id))?"checked=checked":"")?> type="radio" id="checkIn" name="how_enter" value="checkin"/>
                                        
                                        <label for="email">Check-in:</label>
                                    
                                    </div>
                                    
                                    <div class="grid9">
                                            <span class="floatL">

                                                <select id="how_checkin_location_id" name="how_checkin_location_id">
                                                    
                                                    <option value="null">Select a location...</option>
                                                    
                                                    <?php foreach(CMS::locations() as $location): ?>
                                                        <option value="<?=$location->id?>" <?=(($contest->how_checkin_location_id == $location->id)?"selected=selected":"")?> ><?=$location->name?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                
                                            </span>
                                            <div class="clear"></div>
                                    </div>
                                    <div class="clear"></div>
                            </div>

                            <div class="formRow">
                                <div class="grid3">
                                    
                                    <input <?=((!empty($contest->how_signup))?"checked=checked":"")?> type="radio" id="how_signup" name="how_enter" value="signup"/>
                                    
                                    <label for="email">Sign-up (create a new account)</label>
                                    
                                </div>
                                    <div class="grid9">
                                            <span class="floatL">
                                                
                                                
                                            </span>
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

				<div class="formRow">
					<button class="buttonL bGreyish fluid" type="submit">
                        <?=(!$contest->id) ? '<i class="iconb" data-icon="&#xe099;"></i> &nbsp; Add Promo' : '<i class="iconb" data-icon="&#xe097;"></i> &nbsp; Save Promo'?>
                    </button>
				</div>

			</fieldset>
		</div>

	</div>
    
</div>
</form>
