<!-- User Info -->

<form id="wizard1" method="post" autocomplete="off">
<input type="hidden" id="action" name="action" value="<?=$action?>" />
<?php if (@$user->location_ids) : ?>
    <?php foreach($user->location_ids as $location_id): ?>
    <input type="hidden" id="location_id_<?=$location_id?>" name="location_ids[]" value="<?=$location_id?>" />
    <?php endforeach; ?>
<?php endif; ?>
<input type="hidden" id="nonce" name="nonce" value="<?=CMS::create_nonce("user_$action"); ?>" />

<div class="widget fluid">

    <div class="whead">
        <h6><span class="icon-user-2"></span>User Info</h6>
            <div class="titleOpt">
            	<?php if ($action == 'edit' && $me->id != $user->id) : ?>
                <div class="on_off">
                    <span class="floatR"><input type="checkbox" id="delete" name="delete" value="1" <?=(Input::get('delete')) ? 'checked="checked"' : ''?> /></span>
                    <span class="floatR"><label for="delete">remove user: &nbsp; </label></span>
                    <div class="clear"></div>
                </div>
                <?php endif; ?>
            </div>
        <div class="clear"></div>
    </div>

    <fieldset class="formpart">
        <div class="formRow">
        	<div class="grid2"><label for="real_name">Name:</label></div>
            <div class="grid10">
            	<div class="grid6">
                	<input type="text" id="real_name" name="meta[real_name]" placeholder="Real Name" value="<?=@$user->meta->real_name?>" />
                </div>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
        </div>
        <div class="formRow">
        	<div class="grid2"><label for="email">Email Address:</label></div>
            <div class="grid10">
            	<input type="text" id="email" name="email" value="<?=@$user->email?>" autocomplete="off" />
            </div>
            <div class="clear"></div>
        </div>
        <div class="formRow">
        	<div class="grid2"><label for="email">Phone:</label></div>
            <div class="grid10">
            	<input type="text" id="phone" name="meta[phone]" value="<?=@$user->meta->phone?>" autocomplete="off" />
            </div>
            <div class="clear"></div>
        </div>
        <?php if (!isset($profile_mode)) : ?>
            <div class="formRow">
                <div class="grid2"><label for="email">Zip Code:</label></div>
                <div class="grid10">
                    <input type="text" id="zipcode" name="meta[zipcode]" value="<?=@$user->meta->zipcode?>" autocomplete="off" />
                </div>
                <div class="clear"></div>
            </div>

            <?php if ($action == 'edit') { ?>
                <div class="formRow">
                    <div class="grid2"><label for="email">Gender:</label></div>
                    <div class="grid10">
                        <span class="floatL mr20"><label><input type="radio" name="meta[gender]" value="female" <?=(@$user->meta->gender == 'female') ? 'checked="checked"' : ''?> />Female</label></span>
                        <span class="floatL"><label><input type="radio" name="meta[gender]" value="male" <?=(@$user->meta->gender == 'male') ? 'checked="checked"' : ''?> />Male</label></span>
                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                </div>

                <div class="formRow">
                    <div class="grid2"><label>Birthday</label></div>
                    <div class="grid2">
                        <select name="meta[dob][month]" id="month" data-placeholder="month" class="fullwidth">
                            <option value=""></option>
                            <?php for($m = 1; $m <= 12; $m++) : ?>
                            <?php
                                $str = $m . '/01/2000';
                                $mon = date('F', strtotime($str));
                            ?>
                            <option value="<?=$m?>" <?=(@$user->meta->dob->month == $m) ? 'selected="selected"' : ''?>><?=$mon?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="grid1">
                        <input type="text" name="meta[dob][day]" id="day" placeholder="day" value="<?=@$user->meta->dob->day?>" autocomplete="off" />
                    </div>
                    <div class="grid1">
                        <input type="text" name="meta[dob][year]" id="year" placeholder="year" value="<?=@$user->meta->dob->year?>" autocomplete="off" />
                    </div>
                    <div class="clear"></div>
                </div>
            <?php } ?>
        <?php endif ?>
        <?php if ($me->is_admin()) : ?>
        <div class="formRow">
        	<div class="grid2">Company:</div>
            <div class="grid10 searchDrop">
            	<input type="text" id="location_search" name="location_search" />
            </div>
            <div class="clear"></div>
        </div>
        <?php endif; ?>
    </fieldset>

    <div class="whead formpart">
        <h6><span class="icon-cog"></span>User Settings</h6>
        <div class="titleOpt">

        </div>
        <div class="clear"></div>
    </div>
    <fieldset class="formpart">
        <div class="formRow">
        	<div class="grid2"><label for="password">Password:</label></div>
            <div class="grid10">
                <div><input type="password" id="password" name="password" placeholder="password" /></div>
                <div class="mt10"><input type="password" id="confirm" name="confirm" placeholder="confirm" /></div>
            </div>
            <div class="clear"></div>
            <?php if (!isset($profile_mode)) : ?>
            <div class="mt10">
                <div class="grid2"><label for="autogen">Auto Generate:</label></div>
                <div class="grid10 on_off"><input type="checkbox" id="autogen" /></div>
            </div>
            <?php endif; ?>
            <div class="clear"></div>
        </div>
        <?php if (!isset($profile_mode)) : ?>
            <div class="formRow">
                <?php if ($action == 'edit') : ?>
                <div class="grid2">Email Password?:</div>
                <?php else : ?>
                <div class="grid2">Send Reg. Email?:</div>
                <?php endif; ?>
                <div class="grid10 on_off">
                    <input type="checkbox" id="emailpass" name="emailpass" checked="checked" />
                </div>
                <div class="clear"></div>
            </div>
        <?php  endif; ?>
    </fieldset>
   	<?php if ($me->is_admin() && !isset($profile_mode)) : ?>
        <div class="whead formpart">
        <h6><span class="icon-key"></span>User Access</h6>
        <div class="titleOpt">

        </div>
        <div class="clear"></div>
    </div>
	<fieldset class="formpart">
        <div class="formRow">
        	<div class="grid2">Group:</div>
            <div class="grid10 searchDrop">
            	<select class="fullwidth select" data-placeholder="Select..." name="group" id="group">
                    <?php foreach($groups as $key => $name) : ?>
                    <option value="<?=$key?>" <?=(isset($user->group) && $user->group == $key) ? 'selected="selected"' : '' ?>><?=$name?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="clear"></div>
        </div>
    </fieldset>
    <?php endif; ?>

    <?php if ($action == 'edit' && $me->id != $user->id) : // Remove button ?>
    <div class="formRow hide" id="delete-form">
        <div class="formSubmit">
        	Removing a user cannot be undone
            <button class="buttonM bRed ml10" type="submit" id="delete-submit"><span class="icon-x"></span> &nbsp; Confirm Delete</button>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
    </div>
    <?php endif; ?>

    <div class="formRow formpart">
        <button class="buttonM bRed m110" type="button" onClick="history.go(-1);return false;"><span class="icon-undo"></span> &nbsp; Cancel</button>
        <div class="formSubmit">
            <button class="buttonM bGreyish ml10" type="submit"><?=($action == 'add') ? '<span class="icon-plus-2"></span> &nbsp; Add User' : '<span class="icon-checkmark"></span> &nbsp; Update User'?></button>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
    </div>

</div>
</form>

<script type="text/javascript">

    var include_merchants_location_ids = <?= isset($user->include_merchants_location_ids) ? json_encode($user->include_merchants_location_ids) : '[]'; ?>;

    $("#location_search").autoSuggest("<?=Uri::create("api/locations")?>", {
    	minChars: 3,
    	queryParam: "string",
    	extraParams: "&compact=1&order_by=simple_relevance&login_hash=<?=$login_hash?>",
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
    	selectionAdded: function(elem, data){
        	if (data.type == 'Mall') {
        		$('<input />', {
        			type: 'checkbox',
        			value: '1',
        			name: 'include_merchants_' + data.id,
        			checked: include_merchants_location_ids.indexOf(data.id) != -1
        		}).appendTo(elem);
        	}
    	},
    	selectionRemoved: function(elem, id) {
    		elem.fadeTo("slow", 0, function() { elem.remove(); });
    		$("#location_id_" + id).remove();
        },
    	<?php if (@$user->location_ids && count($user->location_ids) > 0) : ?>
        preFill: <?=json_encode(CMS::locations_by_id($user->location_ids));?>,
        <?php endif; ?>
        startText: 'Search location...'
    });
</script>
