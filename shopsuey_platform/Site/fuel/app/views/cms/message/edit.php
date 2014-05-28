<!-- Message Info -->
<?php
//print_r(json_encode($message));
?>
<form id="wizard1" method="post">

<input type="hidden" id="action" name="action" value="<?=$action?>" />
<input type="hidden" name="company" value="<?=@$me->meta->company?>" />
<?=CMS::create_nonce_field('message_'.$action, 'nonce')?>

<div class="fluid">
    <div class="grid12">
        <div class="widget">
            <ul class="tabs sections">
                <li><a href="#message"><span class="mt9 icon-comments"></span> Message</a></li>
                <li><a href="#triggers"><span class="mt9 icon-fire"></span> Triggers</a></li>
                <li><a href="#filters"><span class="mt9 icon-lab"></span> Filters</a></li>
            </ul>
        </div>
    </div>
    <div class="clear"></div>
</div>

<div class="fluid">
    <div class="grid8"><!-- // left column //-->
        <div class="widget section" id="message"><!-- // section 1 //-->
            <!-- Message -->
            <div class="whead">
                <h6><span class="icon-comments"></span>Content</h6>
                <div class="clear"></div>
            </div>
            <fieldset class="slide-toggle">
                <div class="formRow">
                <textarea name="content" rows="10"><?=strip_tags(str_replace('<br>', "\r\n", @$message->content))?></textarea>
                </div>
            </fieldset>
            <!-- End message -->

            <!-- Sender -->
            <div class="whead">
                <div class="titleOpt"><a class="slide-toggle" rel=".slide-toggle-1"><span class="icos-menu-3 icon-menu-3"></span><span class="clear"></span></a></div>
                <h6><span class="icon-meter-fast"></span>Scope</h6>
                <div class="clear"></div>
            </div>
            <fieldset>
                <div class="slide-toggle-1">
                    <div class="formRow">
                        <div class="grid4"><label><input type="radio" class="opt" name="sender_type" rel="#mall" value="mall" <?=(@$message->sender_type == 'mall') ? 'checked="checked"' : ''?> />Mall</label></div>
                        <div class="grid8">
                            <select name="sender_type_mall_id" class="opt_sel fullwidth sender_meta" id="mall" data-placeholder="Select mall">
                                <option></option>
								<?php foreach(CMS::malls(@$message->sender_meta->mall) as $mall) : ?>
                                <option value="<?=$mall->id?>" <?=@$mall->selected?>><?=$mall->name?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid4"><label><input type="radio" class="opt" name="sender_type" rel="#retailer" value="retailer" <?=(@$message->sender_type == 'retailer') ? 'checked="checked"' : ''?> />Retailer</label></div>
                        <div class="grid8">
                            <div>
                                <select name="sender_type_retailer_id" class="opt_sel fullwidth sender_meta" id="retailer" data-placeholder="Select retailer">
                                    <option></option>
                                    <option value="1" <?=(@$message->sender_meta->retailer == 1 && @$message->sender_type == 'retailer') ? 'selected="selected"' : ''?>>Retailer 1</option>
                                    <option value="2" <?=(@$message->sender_meta->retailer == 2 && @$message->sender_type == 'retailer') ? 'selected="selected"' : ''?>>Retailer 2</option>
                                </select>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid4"><label><input type="radio" class="opt" name="sender_type" rel="#location" value="location" <?=(@$message->sender_type == 'location') ? 'checked="checked"' : ''?> />Location</label></div>
                        <div class="grid8">
                            <div>
                                <select name="sender_type_location_mall_id" class="opt_sel fullwidth sender_meta" id="location_mall" data-placeholder="Select mall">
                                    <option></option>
                                    <option value="1" <?=(@$message->sender_meta->mall == 1 && @$message->sender_type == 'location') ? 'selected="selected"' : ''?>>Mall 1</option>
                                    <option value="2" <?=(@$message->sender_meta->mall == 2 && @$message->sender_type == 'location') ? 'selected="selected"' : ''?>>Mall 2</option>
                                </select>
                            </div>
                            <div class="mt10">
                                <select name="sender_type_location_retailer_id" class="opt_sel fullwidth sender_meta" id="location_retailer" data-placeholder="Select retailer">
                                    <option></option>
                                    <option value="1" <?=(@$message->sender_meta->retailer == 1 && @$message->sender_type == 'location') ? 'selected="selected"' : ''?>>Retailer 1</option>
                                    <option value="2" <?=(@$message->sender_meta->retailer == 2 && @$message->sender_type == 'location') ? 'selected="selected"' : ''?>>Retailer 2</option>
                                </select>
                            </div>
                            <div class="mt10">
                                <select name="sender_type_location_id" class="opt_sel fullwidth sender_meta" id="location" data-placeholder="Select location">
                                    <option></option>
                                    <option value="1" <?=(@$message->sender_meta->location == 1 && @$message->sender_type == 'location') ? 'selected="selected"' : ''?>>Location 1</option>
                                    <option value="2" <?=(@$message->sender_meta->location == 2 && @$message->sender_type == 'location') ? 'selected="selected"' : ''?>>Location 2</option>
                                </select>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </fieldset>
            <!-- End sender -->

            <!-- Action -->
            <div class="whead">
                <div class="titleOpt"><a class="slide-toggle" rel=".slide-toggle-2"><span class="icos-menu-3 icon-menu-3"></span><span class="clear"></span></a></div>
                <h6><span class="icon-accessibility"></span>Action</h6>
                <div class="clear"></div>
            </div>
            <fieldset>
                <div class="slide-toggle-2">
                    <div class="formRow">
                        <div class="grid4"><label><input type="radio" class="opt" name="action_type" rel="#event" value="event" <?=(@$message->action_type == 'event') ? 'checked="checked"' : ''?> />Event</label></div>
                        <div class="grid8">
                            <select name="action_type_event" class="opt_sel fullwidth action_meta" id="event" data-placeholder="Select event">
                                <option></option>
                                <option value="1" <?=(@$message->action_type == 'event' && @$message->action_meta == 1) ? 'selected="selected"' : ''?>>Event 1</option>
                                <option value="2" <?=(@$message->action_type == 'event' && @$message->action_meta == 2) ? 'selected="selected"' : ''?>>Event 2</option>
                            </select>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid4"><label><input type="radio" class="opt" name="action_type" rel="#offer" value="offer" <?=(@$message->action_type == 'offer') ? 'checked="checked"' : ''?> />Offer</label></div>
                        <div class="grid8">
                            <select name="action_type_offer" class="opt_sel fullwidth action_meta" id="offer" data-placeholder="Select offer">
                                <option></option>
                                <option value="1" <?=(@$message->action_type == 'offer' && @$message->action_meta == 1) ? 'selected="selected"' : ''?>>Offer 1</option>
                                <option value="2" <?=(@$message->action_type == 'offer' && @$message->action_meta == 2) ? 'selected="selected"' : ''?>>Offer 2</option>
                            </select>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid4"><label><input type="radio" class="opt" name="action_type" rel="#navto" value="navto" <?=(@$message->action_type == 'navto') ? 'checked="checked"' : ''?> />App Screen</label></div>
                        <div class="grid8">
                            <select name="action_type_navto" class="opt_sel fullwidth action_meta" id="navto" data-placeholder="Select screen">
                                <option></option>
                                <option value="1" <?=(@$message->action_type == 'navto' && @$message->action_meta == 1) ? 'selected="selected"' : ''?>>Events Screen</option>
                                <option value="2" <?=(@$message->action_type == 'navto' && @$message->action_meta == 2) ? 'selected="selected"' : ''?>>Mall Welcome Screen</option>
                                <option value="3" <?=(@$message->action_type == 'navto' && @$message->action_meta == 3) ? 'selected="selected"' : ''?>>More Info Screen</option>
                                <option value="4" <?=(@$message->action_type == 'navto' && @$message->action_meta == 4) ? 'selected="selected"' : ''?>>Offers Screen</option>
                                <option value="5" <?=(@$message->action_type == 'navto' && @$message->action_meta == 5) ? 'selected="selected"' : ''?>>Retailer Welcome Screen</option>
                            </select>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid4"><label><input type="radio" class="opt" name="action_type" rel="#website" value="website" <?=(@$message->action_type == 'website') ? 'checked="checked"' : ''?> />Website</label></div>
                        <div class="grid8">
                            <input type="url" class="opt_sel" name="action_type_website" id="website" placeholder="http://www.website.com" value="<?=(@$message->action_type == 'website') ? @$message->action_meta : ''?>" />
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </fieldset>
            <!-- End action -->

        </div><!-- // end section 1 //-->

        <div class="widget section" id="triggers"><!-- // section 2 //-->
            <!-- Trigger -->
            <div class="whead">
                <div class="titleOpt">
                    <a class="slide-toggle" rel=".slide-toggle-3"><span class="icos-menu-3 icon-menu-3"></span><span class="clear"></span></a>
                </div>
                <span class="right">
                    <div class="on_off">
                        <span class="floatR"><input name="trigger_type" type="checkbox" id="trigger_toggle" rel=".trigger_options" value="manual" <?=(@$message->trigger_type == 'manual') ? 'checked="checked"' : ''?> /></span>
                        <span class="floatR"><label for="trigger_type">manually &nbsp; </label></span>
                        <div class="clear"></div>
                    </div>
                </span>
                <h6><span class="icon-fire"></span>Trigger</h6>
                <div class="clear"></div>
            </div>
            <fieldset>
                <div class="slide-toggle-3">
                    <div class="trigger_options">
                        <div class="formRow">
                            <div class="grid3"><label><input type="radio" class="opt" name="trigger_type" value="datetime" data-hide="#repeat_effective_dates" <?=(@$message->trigger_type == 'datetime') ? 'checked="checked"' : ''?> />Date - Time</label></div>
                            <div class="grid9">
                                <span class="floatL mr5"><input type="text" name="trigger_type_datetime_date" class="datepicker opt_sel" placeholder="<?=date('m/d/Y')?>" value="<?=(@$message->trigger_meta->date && @$message->trigger_type == 'datetime') ? @$message->trigger_meta->date : ''?>" /></span>
                                <span class="floatL mr5 mt4 text-center">&ndash;</span>
                                <span class="floatL"><input type="text" name="trigger_type_datetime_time" class="timepicker opt_sel" placeholder="<?=date('h:iA')?>" value="<?=(@$message->trigger_meta->time && @$message->trigger_type == 'datetime') ? @$message->trigger_meta->time : ''?>" /></span>
                                <div class="clear"></div>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="formRow">
                            <div class="grid3"><label><input type="radio" class="opt" name="trigger_type" value="proximity" data-show="#repeat_effective_dates" <?=(@$message->trigger_type == 'proximity') ? 'checked="checked"' : ''?> />Proximity</label></div>
                            <div class="grid9">
                                <span class="floatL mr10"><input type="number" name="trigger_type_proximity" class="opt_sel number" placeholder="100" data-step="50" data-min="100" data-max="500" style="width: 50px !important;" value="<?=(@$message->trigger_type == 'proximity') ? @$message->trigger_meta->proximity : ''?>" /></span>
                                <span class="floatL inline-label"><label>feet</label></span>
                            </div>
                            <div class="clear"></div>
                        </div>

                        <div class="formRow">
                            <div class="grid3"><label><input type="radio" class="opt" name="trigger_type" value="behavior" data-show="#repeat_effective_dates" <?=(@$message->trigger_type == 'behavior') ? 'checked="checked"' : ''?> />Behavior</label></div>
                            <div class="grid9">
                                <div>
                                    <select name="trigger_type_behavior" class="fullwidth opt_sel" id="trigger_behavior" data-placeholder="Select behavior">
                                        <option></option>
                                        <?php foreach($behaviors as $val => $label) : ?>
                                        <option value="<?=$val?>" <?=(@$message->trigger_meta->behavior == $val && @$message->trigger_type == 'behavior') ? 'selected="selected"' : ''?> ><?=$label?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>

                        <div class="formRow">
                            <div class="grid3"><label><input type="radio" name="trigger_type" value="repeat" data-show="#repeat_effective_dates" <?=(@$message->trigger_type == 'repeat') ? 'checked="checked"' : ''?> />Recurring</label></div>
                            <div class="grid9">
                                <div>
                                    <?php if (isset($message->trigger_meta->repeat)) { $repeat = $message->trigger_meta->repeat; } ?>
                                    <select name="repeat_type" class="fullwidth trigger_sel" id="repeat_type" data-placeholder="Select repeat" >
                                        <option></option>
                                        <option value="daily" rel="#repeat_daily_div" <?=(@$repeat->type == 'daily') ? 'selected="selected"' : ''?> >Daily</option>
                                        <option value="weekly" rel="#repeat_weekly_div" <?=(@$repeat->type == 'weekly') ? 'selected="selected"' : ''?> >Weekly</option>
                                        <option value="monthly" rel="#repeat_monthly_div" <?=(@$repeat->type == 'monthly') ? 'selected="selected"' : ''?> >Monthly</option>
                                    </select>
                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>

                        <div class="repeat_opt <?=(@$repeate->type != 'daily') ? 'hide' : ''?>" id="repeat_daily_div">
                            <div class="formRow">
                                <div class="grid3 text-right">Time:</div>
                                <div class="grid9">
                                    <input type="text" class="timepicker" name="repeat_type_daily_time" placeholder="<?=date('h:iA')?>" value="<?=(@$message->trigger_type == 'repeat' && @$repeat->type == 'daily') ? @$repeat->time : ''?>" />
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>

                        <div class="repeat_opt <?=(@$repeate->type != 'weekly') ? 'hide' : ''?>" id="repeat_weekly_div">
                            <div class="formRow">
                                <div class="grid3 text-right">Days:</div>
                                <div class="grid9 check">
                                    <?php $days = (isset($repeat->days)) ? $days = @$repeat->days : array(); $day_names = array('sun', 'mon', 'tue', 'wed', 'thr', 'fri', 'sat')?>

                                    <?php foreach($day_names as $idx => $day_name): ?>
                                    <span class="floatL"><label><input type="checkbox" name="repeat_type_weekly_days[]" value="<?=$idx?>" <?=(in_array($idx, $days) && @$message->trigger_type == 'repeat' && @$repeat->type == 'weekly') ? 'checked="checked"' : ''?> /> <span class="mr10"><?=ucwords($day_name)?></span></label></span>
                                    <?php endforeach; ?>

                                    <div class="clear"></div>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="formRow">
                                <div class="grid3 text-right">Time:</div>
                                <div class="grid9">
                                    <input type="text" class="timepicker" name="repeat_type_weekly_time" placeholder="<?=date('h:iA')?>" value="<?=(@$message->trigger_type == 'repeat' && @$repeat->type == 'weekly') ? @$repeat->time : ''?>" />
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>

                        <div class="repeat_opt <?=(@$repeate->type != 'monthly') ? 'hide' : ''?>" id="repeat_monthly_div">
                            <div class="formRow">
                                <div class='grid3 text-right'>Day:</div>
                                <div class="grid3">
                                    <span class="floatL"><input type="number" name="repeat_type_monthly_day" class="number" data-min="1" data-max="28" data-width="10" style="width: 50px !important;" value="<?=(@$message->trigger_type == 'repeat' && @$repeat->type == 'monthly') ? @$repeat->day : ''?>" /></span>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="formRow">
                                <div class="grid3 text-right">Time:</div>
                                <div class="grid9">
                                    <input type="text" class="timepicker" name="repeat_type_monthly_time" placeholder="<?=date('h:iA')?>" value="<?=(@$message->trigger_type == 'repeat' && @$repeat->type == 'monthly') ? @$repeat->time : ''?>" />
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>

                        <div class="formRow <?=(@$message->trigger_type == 'datetime') ? 'hide' : ''?>" id="repeat_effective_dates">
                            <div class="grid3"><label>Effective Dates:</label></div>
                            <div class="grid9">
                                <span class="mr5">
                                    <input id="date_start" type="text" class="datepicker" data-max="#date_end" name="repeat_date[]" placeholder="<?=date('m/d/Y')?>" value="<?=(@$message->date_start != '0000-00-00 00:00:00' && @$message->date_start != '1970-01-01 12:00:00' && @$message->trigger_type != 'datetime') ? date('m/d/Y', strtotime(@$message->date_start)) : ''?>" />
                                </span>
                                <span class="mr5 text-center">&ndash;</span>
                                <span>
                                    <input id="date_end" type="text" class="datepicker" data-min="#date_start" name="repeat_date[]" placeholder="<?=date('m/d/Y')?>" value="<?=(@$message->date_end != '0000-00-00 00:00:00' && @$message->date_start != '1970-01-01 12:00:00' && @$message->trigger_type != 'datetime') ? date('m/d/Y', strtotime(@$message->date_end)) : ''?>" />
                                </span>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
            </fieldset>
            <!-- End trigger -->
        </div><!-- end section 2 //-->


        <div class="widget section" id="filters"><!-- // section 3 //-->
            <!-- Demographic filters -->
            <div class="whead">
                <div class="titleOpt"><a class="slide-toggle" rel=".slide-toggle-4"><span class="icos-menu-3 icon-menu-3"></span><span class="clear"></span></a></div>
                <h6><span class="icon-user-2"></span>Demographic Filters</h6>
                <div class="clear"></div>
            </div>
            <fieldset>
                <div class="slide-toggle-4 check">
                    <div class="formRow">
                        <div class="grid4"><label><input type="checkbox" class="filter" <?=(@$message->filter_demographic->age) ? 'checked="checked"' : ''?> />Age</label></div>
                        <div class="grid8">
                            <span class="floatL"><input type="number" class="number filter_sel" name="filter_demographic[age]" data-min="1" style="width: 60px;" value="<?=@$message->filter_demographic->age?>" /></span>
                            <div class="clear"></div>
                        </div>
                        <div class="clear"></div>
                    </div>

                    <div class="formRow">
                        <div class="grid4"><label><input type="checkbox" class="filter" <?=(@$message->filter_demographic->gender) ? 'checked="checked"' : ''?> />Gender</label></div>
                        <div class="grid8">
                            <span class="floatL">
                                <label><input type="checkbox" name="filter_demographic[gender][female]" class="filter_sel" value="1" <?=(@$message->filter_demographic->gender->female) ? 'checked="checked"' : ''?> /> <span class="mr10">Female</span></label>
                            </span>
                            <span class="floatL">
                                <label><input type="checkbox" name="filter_demographic[gender][male]" class="filter_sel" value="1" <?=(@$message->filter_demographic->gender->male) ? 'checked="checked"' : ''?> /> <span class="mr10">Male</span></label>
                            </span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid4"><label><input type="checkbox" class="filter" <?=(@$message->filter_demographic->zip) ? 'checked="checked"' : ''?> />Zip Code</label></div>
                        <div class="grid8">
                            <span class="floatL">
                                <input type="number" class="filter_sel" name="filter_demographic[zip]" style="width: 100px;" value="<?=@$message->filter_demographic->zip?>" />
                            </span>
                            <div class="clear"></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid4"><label><input type="checkbox" class="filter" <?=(@$message->filter_demographic->language) ? 'checked="checked"' : ''?> />Language</label></div>
                        <div class="grid8">
                            <?php
                                $langs_left = array('english', 'japanese', 'chinese', 'korean');
                                $langs_right = array('spanish', 'french', 'italian', 'arabic');
                            ?>
                            <span class="floatL mr20">
                                <?php foreach($langs_left as $idx => $lang) : ?>
                                <div class="mb10"><label><input type="checkbox" name="filter_demographic[language][<?=$lang?>]" class="filter_sel" value="1" <?=(@$message->filter_demographic->language->$lang) ? 'checked="checked"' : ''?> /> <?=ucwords($lang)?></label></div>
                                <?php endforeach ?>
                            </span>
                            <span class="floatL">
                                <?php foreach($langs_right as $idx => $lang) : ?>
                                <div class="mb10"><label><input type="checkbox" name="filter_demographic[language][<?=$lang?>]" class="filter_sel" value="1" <?=(@$message->filter_demographic->language->$lang) ? 'checked="checked"' : ''?> /> <?=ucwords($lang)?></label></div>
                                <?php endforeach; ?>
                            </span>
                            <div class="clear"></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid4"><label><input type="checkbox" class="filter" <?=(@$message->filter_demographic->device) ? 'checked="checked"' : ''?> />Device Type</label></div>
                        <div class="grid8">
                            <div><label><input type="checkbox" name="filter_demographic[device][ios]" class="filter_sel" value="1" <?=(@$message->filter_demographic->device->ios) ? 'checked="checked"' : ''?> /> iOS (Apple)</label></div>
                            <div class="mt10"><label><input type="checkbox" name="filter_demographic[device][android]" class="filter_sel" value="1" <?=(@$message->filter_demographic->device->android) ? 'checked="checked"' : ''?> /> Android</label></div>
                        </div>
                        <div class="clear"></div>
                    </div>

                    <div class="formRow">
                        <div class="grid4"><label><input type="checkbox" class="filter" <?=(@$message->filter_demographic->subscriber) ? 'checked="checked"' : ''?> />Subscriber</label></div>
                        <div class="grid8">
                            <div><label><input type="checkbox" name="filter_demographic[subscriber][favorites]" class="filter_sel" value="1" <?=(@$message->filter_demographic->subscriber->favorites) ? 'checked="checked"' : ''?> /> Favorited</label></div>
                            <div class="mt10"><label><input type="checkbox" name="filter_demographic[subscriber][subscribed]" class="filter_sel" value="1" <?=(@$message->filter_demographic->subscriber->subscribed) ? 'checked="checked"' : ''?> /> Subscribed to e-mail list</label></div>
                            <div class="mt10"><label><input type="checkbox" name="filter_demographic[subscriber][follow]" class="filter_sel" value="1" <?=(@$message->filter_demographic->subscriber->follow) ? 'checked="checked"' : ''?> /> Social media followers</label></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </fieldset>
            <!-- End demographic filter -->

            <!-- Proximity filters -->
            <div class="whead">
                <div class="titleOpt"><a class="slide-toggle" rel=".slide-toggle-5"><span class="icos-menu-3 icon-menu-3"></span><span class="clear"></span></a></div>
                <h6><span class="icon-location"></span>Proximity Filters</h6>
                <div class="clear"></div>
            </div>
            <fieldset>
                <div class="slide-toggle-5 check">
                    <div class="formRow">
                        <div class="grid4"><label><input type="checkbox" class="filter" <?=(@$message->filter_proximity) ? 'checked="checked"' : ''?>/>Range</label></div>
                        <div class="grid8">
                            <span class="floatL mr5 grid3"><input type="number" data-min="100" data-max="1000" data-step="100" class="number" name="filter_proximity[distance]" style="width: 60px;" value="<?=@$message->filter_proximity->distance?>" /></span>
                            <span class="floatL grid3">
                                <select class="fullwidth" name="filter_proximity[multiplier]">
                                    <option value="feet" <?=(@$message->filter_proximity->multiplier == 'feet') ? 'selected="selected"' : ''?>>feet</option>
                                    <option value="miles" <?=(@$message->filter_proximity->multiplier == 'miles') ? 'selected="selected"' : ''?>>miles</option>
                                </select>
                            </span>
                            <div class="clear"></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </fieldset>
            <!-- End proximity filters -->

            <!-- Behavioral filters -->
            <div class="whead">
                <div class="titleOpt"><a class="slide-toggle" rel=".slide-toggle-6"><span class="icos-menu-3 icon-menu-3"></span><span class="clear"></span></a></div>
                <h6><span class="icon-light-bulb"></span>Behavioral Filters</h6>
                <div class="clear"></div>
            </div>
            <fieldset>
                <div class="slide-toggle-6 check">
                    <div class="formRow">
                        <?php
                            $is_offer = 0;
                            $flds = array('viewed', 'redeemed', 'rejected');
                            foreach($flds as $fld) { $is_offer += @array_sum($message->filter_behavior->offer->$fld); }
                        ?>
                        <div class="grid4"><label><input type="checkbox" class="filter" <?=($is_offer > 0) ? 'checked="checked"' : ''?> />Offer Redemption</label></div>
                        <div class="grid8">
                            <div>
                                <span class="floatL mr15 grid4"><label><input type="checkbox" class="filter_sel filter_sub" <?=(@array_sum(@$message->filter_behavior->offer->viewed) > 0) ? 'checked="checked"' : ''?>> Viewed</label></span>
                                <span class="floatL mr5"><input type="number" class="number filter_sel filter_sub_sel" name="filter_behavior[offer][viewed][]" style="width: 60px;" value="<?=@$message->filter_behavior->offer->viewed[0]?>" /></span>
                                <span class="floatL mr5 inline-label"><label>&ndash;</label></span>
                                <span class="floatL"><input type="number" class="number filter_sel filter_sub_sel" name="filter_behavior[offer][viewed][]" style="width: 60px;" value="<?=@$message->filter_behavior->offer->viewed[1]?>" /></span>
                                <div class="clear"></div>
                            </div>
                            <div class="mt10">
                                <span class="floatL mr15 grid4"><label><input type="checkbox" class="filter_sel filter_sub" <?=(@array_sum(@$message->filter_behavior->offer->redeemed) > 0) ? 'checked="checked"' : ''?>> Redeemed</label></span>
                                <span class="floatL mr5"><input type="number" class="number filter_sel filter_sub_sel" name="filter_behavior[offer][redeemed][]" style="width: 60px;" value="<?=@$message->filter_behavior->offer->redeemed[0]?>" /></span>
                                <span class="floatL mr5 inline-label"><label>&ndash;</label></span>
                                <span class="floatL"><input type="number" class="number filter_sel filter_sub_sel" name="filter_behavior[offer][redeemed][]" style="width: 60px;" value="<?=@$message->filter_behavior->offer->redeemed[1]?>" /></span>
                                <div class="clear"></div>
                            </div>
                            <div class="mt10">
                                <span class="floatL mr15 grid4"><label><input type="checkbox" class="filter_sel filter_sub" <?=(@array_sum(@$message->filter_behavior->offer->rejected) > 0) ? 'checked="checked"' : ''?>> Rejected</label></span>
                                <span class="floatL mr5"><input type="number" class="number filter_sel filter_sub_sel" name="filter_behavior[offer][rejected][]" style="width: 60px;" value="<?=@$message->filter_behavior->offer->rejected[0]?>" /></span>
                                <span class="floatL mr5 inline-label"><label>&ndash;</label></span>
                                <span class="floatL"><input type="number" class="number filter_sel filter_sub_sel" name="filter_behavior[offer][rejected][]" style="width: 60px;" value="<?=@$message->filter_behavior->offer->rejected[1]?>" /></span>
                                <div class="clear"></div>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <?php
                            $is_event = 0;
                            $flds = array('viewed', 'rsvp', 'attended', 'rejected');
                            foreach($flds as $fld) { $is_event += @array_sum(@$message->filter_behavior->event->$fld); }
                        ?>
                        <div class="grid4"><label><input type="checkbox" class="filter" <?=($is_event > 0) ? 'checked="checked"' : ''?> />Event Participation</label></div>
                        <div class="grid8">
                            <div>
                                <span class="floatL mr15 grid4"><label><input type="checkbox" class="filter_sel filter_sub" <?=(@array_sum(@$message->filter_behavior->event->viewed) > 0) ? 'checked="checked"' : ''?>> Viewed</label></span>
                                <span class="floatL mr5"><input type="number" class="number filter_sel filter_sub_sel" name="filter_behavior[event][viewed][]" style="width: 60px;" value="<?=@$message->filter_behavior->event->viewed[0]?>" /></span>
                                <span class="floatL mr5 inline-label"><label>&ndash;</label></span>
                                <span class="floatL"><input type="number" class="number filter_sel filter_sub_sel" name="filter_behavior[event][viewed][]" style="width: 60px;" value="<?=@$message->filter_behavior->event->viewed[1]?>" /></span>
                                <div class="clear"></div>
                            </div>
                            <div class="mt10">
                                <span class="floatL mr15 grid4"><label><input type="checkbox" class="filter_sel filter_sub" <?=(@array_sum(@$message->filter_behavior->event->rsvp) > 0) ? 'checked="checked"' : ''?>> RSVP</label></span>
                                <span class="floatL mr5"><input type="number" class="number filter_sel filter_sub_sel" name="filter_behavior[event][rsvp][]" style="width: 60px;" value="<?=@$message->filter_behavior->event->rsvp[0]?>" /></span>
                                <span class="floatL mr5 inline-label"><label>&ndash;</label></span>
                                <span class="floatL"><input type="number" class="number filter_sel filter_sub_sel" name="filter_behavior[event][rsvp][]" style="width: 60px;" value="<?=@$message->filter_behavior->event->rsvp[1]?>" /></span>
                                <div class="clear"></div>
                            </div>
                            <div class="mt10">
                                <span class="floatL mr15 grid4"><label><input type="checkbox" class="filter_sel filter_sub" <?=(@array_sum(@$message->filter_behavior->event->attended) > 0) ? 'checked="checked"' : ''?>> Attended</label></span>
                                <span class="floatL mr5"><input type="number" class="number filter_sel filter_sub_sel" name="filter_behavior[event][attended][]" style="width: 60px;" value="<?=@$message->filter_behavior->event->attended[0]?>" /></span>
                                <span class="floatL mr5 inline-label"><label>&ndash;</label></span>
                                <span class="floatL"><input type="number" class="number filter_sel filter_sub_sel" name="filter_behavior[event][attended][]" style="width: 60px;" value="<?=@$message->filter_behavior->event->attended[1]?>" /></span>
                                <div class="clear"></div>
                            </div>
                            <div class="mt10">
                                <span class="floatL mr15 grid4"><label><input type="checkbox" class="filter_sel filter_sub" <?=(@array_sum(@$message->filter_behavior->event->rejected) > 0) ? 'checked="checked"' : ''?>> Rejected</label></span>
                                <span class="floatL mr5"><input type="number" class="number filter_sel filter_sub_sel" name="filter_behavior[event][rejected][]" style="width: 60px;" value="<?=@$message->filter_behavior->event->rejected[0]?>" /></span>
                                <span class="floatL mr5 inline-label"><label>&ndash;</label></span>
                                <span class="floatL"><input type="number" class="number filter_sel filter_sub_sel" name="filter_behavior[event][rejected][]" style="width: 60px;" value="<?=@$message->filter_behavior->event->rejected[1]?>" /></span>
                                <div class="clear"></div>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid4"><label><input type="checkbox" class="filter" <?=(@$message->filter_behavior->newuser->min > 0 && @$message->filter_behavior->newuser->max > 0) ? 'checked="checked"' : ''?> />New User</label></div>
                        <div class="grid8">
                            <span class="floatL mr5"><input type="number" class="number filter_sel" name="filter_behavior[newuser][min]" style="width: 60px;" <?=(@$message->filter_behavior->newuser->min > 0) ? 'checked="checked"' : ''?> /></span>
                            <span class="floatL inline-label mr5"><label>&ndash;</label></span>
                            <span class="floatL mr5"><input type="number" class="number filter_sel" name="filter_behavior[newuser][max]" style="width: 60px;" <?=(@$message->filter_behavior->newuser->max > 0) ? 'checked="checked"' : ''?> /></span>
                            <span class="floatL mr5 grid3">
                                <select class="fullwidth filter_sel" name="filter_behavior[newuser][multiplier]">
                                    <?php $flds = array('days', 'weeks', 'months', 'years'); ?>
                                    <?php foreach($flds as $fld) : ?>
                                    <option value="<?=$fld?>" <?=(@$message->filter_behavior->newuser->multiplier == $fld) ? 'selected="selected"' : ''?>><?=$fld?></option>
                                    <?php endforeach; ?>
                                </select>
                            </span>
                            <span class="floatL inline-label mr5"><label>ago</label></span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid4"><label><input type="checkbox" class="filter" <?=(@$message->filter_behavior->favorites) ? 'checked="checked"' : ''?> />Favorites</label></div>
                        <div class="grid8 check">
                            <div>
                                <label><input type="radio" name="filter_behavior[favorites]" class="filter_sel" value="1" <?=(@$message->filter_behavior->favorites == 1) ? 'checked="checked"' : ''?> /> User has at least one favorite set</label>
                            </div>
                            <div class="mt10">
                                <label><input type="radio" name="filter_behavior[favorites]" class="filter_sel" value="2" <?=(@$message->filter_behavior->favorites == 2) ? 'checked="checked"' : ''?> /> User has no favorites set</label>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>

                    <div class="formRow">
                        <div class="grid4"><label><input type="checkbox" name="filter_behavior[friends]" value="1" class="filter" <?=(@$message->filter_behavior->friends) ? 'checked="checked"' : ''?> />Friends</label></div>
                        <div class="grid8">1 or more friends are currently at the mall</div>
                        <div class="clear"></div>
                    </div>

                    <div class="formRow">
                        <div class="grid4"><label><input type="checkbox" name="filter_behavior[parking]" value="1" class="filter" <?=(@$message->filter_behavior->parking) ? 'checked="checked"' : ''?> />Parked</label></div>
                        <div class="grid8">User has a parking spot pin set</div>
                        <div class="clear"></div>
                    </div>

                    <div class="formRow">
                        <div class="grid4"><label><input type="checkbox" class="filter" <?=(@array_sum(@$message->filter_behavior->list)) ? 'checked="checked"' : ''?> />Shopping List Items</label></div>
                        <div class="grid8">
                            <span class="floatL mr5"><input type="number" class="number" name="filter_behavior[list][]" style="width: 60px;" value="<?=@$message->filter_behavior->list[0]?>" /></span>
                            <span class="floatL inline-label mr5"><label>&ndash;</label></span>
                            <span class="floatL mr5"><input type="number" class="number" name="filter_behavior[list][]" style="width: 60px;" value="<?=@$message->filter_behavior->list[1]?>" /></span>
                            <div class="clear"></div>
                        </div>
                        <div class="clear"></div>
                    </div>

                    <div class="formRow">
                        <?php
                            $is_tracker = 0;
                            $flds = array('percent', 'amount');
                            foreach($flds as $fld) { $is_tracker += @array_sum(@$message->filter_behavior->tracker->$fld); }
                        ?>
                        <div class="grid4"><label><input type="checkbox" name="filter_behavior[]" value="tracker" class="filter" <?=(@$is_tracker > 0) ? 'checked="checked"' : ''?> />Savings Tracker</label></div>
                        <div class="grid8 check">
                            <div>
                                <span class="floatL mr15 grid4"><label><input type="checkbox" class="filter_sel filter_sub" <?=(@array_sum(@$message->filter_behavior->tracker->percent) > 0) ? 'checked="checked"' : ''?>> Percent</label></span>
                                <span class="floatL mr5"><input type="number" class="number filter_sub_sel" name="filter_behavior[tracker][percent][]" style="width: 60px;" data-max="100" value="<?=@$message->filter_behavior->tracker->percent[0]?>" /></span>
                                <span class="floatL inline-label mr5"><label>&ndash;</label></span>
                                <span class="floatL mr5"><input type="number" class="number filter_sub_sel" name="filter_behavior[tracker][percent][]" style="width: 60px;" data-max="100" value="<?=@$message->filter_behavior->tracker->percent[1]?>" /></span>
                                <div class="clear"></div>
                            </div>
                            <div class="mt10">
                                <span class="floatL mr15 grid4"><label><input type="checkbox" class="filter_sel filter_sub" <?=(@array_sum(@$message->filter_behavior->tracker->amount) > 0) ? 'checked="checked"' : ''?>> Amount</label></span>
                                <span class="floatL mr5"><input type="number" class="number filter_sub_sel" name="filter_behavior[tracker][amount][]" style="width: 60px;" data-min="0" data-decimal="2" data-step="0.25" value="<?=@$message->filter_behavior->tracker->amount[0]?>" /></span>
                                <span class="floatL inline-label mr5"><label>&ndash;</label></span>
                                <span class="floatL mr5"><input type="number" class="number filter_sub_sel" name="filter_behavior[tracker][amount][]" style="width: 60px;" data-min="0" data-decimal="2" data-step="0.25" value="<?=@$message->filter_behavior->tracker->amount[1]?>" /></span>
                                <div class="clear"></div>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>

                </div>
            </fieldset>
            <!-- End behavioral filters -->

            <!-- Frequency filters -->
            <div class="whead">
                <div class="titleOpt"><a class="slide-toggle" rel=".slide-toggle-7"><span class="icos-menu-3 icon-menu-3"></span><span class="clear"></span></a></div>
                <h6><span class="icon-history-2"></span>Visit Frequency</h6>
                <div class="clear"></div>
            </div>
            <fieldset>
                <div class="slide-toggle-7 check">
                    <div class="formRow">
                        <div class="grid4"><label><input type="checkbox"value="range" class="filter" <?=(@$message->filter_frequency->range[0] || @$message->filter_frequency->range[1]) ? 'checked="checked"' : ''?> />Visited Between</label></div>
                        <div class="grid8">
                            <span class="floatL mr5"><input id="filter_frequency_range_min" data-max="#filter_frequency_range_max" type="text" class="datepicker filter_sel" name="filter_frequency[range][]" placeholder="<?=date('m/d/Y')?>" value="<?=@$message->filter_frequency->range[0]?>" /></span>
                            <span class="floatL inline-label mr5"><label>&ndash;</label></span>
                            <span class="floatL mr5"><input id="filter_frequency_range_max" data-min="#filter_frequency_range_min" type="text" class="datepicker" name="filter_frequency[range][]" placeholder="<?=date('m/d/Y')?>" value="<?=@$message->filter_frequency->range[1]?>" /></span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid4"><label><input type="checkbox" class="filter" <?=(@$message->filter_frequency->last->count) ? 'checked="checked"' : ''?> />Last Visit</label></div>
                        <div class="grid8">
                            <span class="floatL mr5"><input type="number" class="number filter_sel filter_sub_sel" name="filter_frequency[last]" style="width: 60px;" value="<?=@$message->filter_frequency->last->count?>" /></span>
                            <span class="floatL mr10">
                                <select class="fullwidth" name="filter_frequency[last][multiplier]">
                                    <option value="days" <?=(@$message->filter_frequency->last->multiplier == 'days') ? 'checked="checked"' : ''?>>days</option>
                                    <option value="weeks" <?=(@$message->filter_frequency->last->multiplier == 'weeks') ? 'checked="checked"' : ''?>>weeks</option>
                                    <option value="months" <?=(@$message->filter_frequency->last->multiplier == 'months') ? 'checked="checked"' : ''?>>months</option>
                                    <option value="years"> <?=(@$message->filter_frequency->last->multiplier == 'years') ? 'checked="checked"' : ''?>years</option>
                                </select>
                            </span>
                            <span class="floatL inline-label"><label>ago</label></span>
                            <div class="clear"></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid4"><label><input type="checkbox" class="filter" <?=(@$message->filter_frequency->visits->count > 0) ? 'checked="checked"' : ''?> />Number of Visits</label></div>
                        <div class="grid8">
                            <span class="floatL mr10"><input type="number" class="number filter_sel filter_sub_sel" name="filter_frequency[visits][count]" style="width: 60px;" /></span>
                            <span class="floatL mr10 inline-label"><label>within</label></span>
                            <span class="floatL mr5"><input type="number" class="number filter_sel filter_sub_sel" name="filter_frequency[visits][interval]" style="width: 60px;" /></span>
                            <span class="floatL mr5 grid3">
                                <select class="fullwidth" name="filter_frequency[visits][multiplier]">
                                    <option value="days" <?=(@$message->filter_frequency->visits->multiplier == 'days') ? 'checked="checked"' : ''?>>days</option>
                                    <option value="weeks" <?=(@$message->filter_frequency->visits->multiplier == 'weeks') ? 'checked="checked"' : ''?>>weeks</option>
                                    <option value="months" <?=(@$message->filter_frequency->visits->multiplier == 'months') ? 'checked="checked"' : ''?>>months</option>
                                    <option value="years"> <?=(@$message->filter_frequency->visits->multiplier == 'years') ? 'checked="checked"' : ''?>years</option>
                                </select>
                            </span>
                            <div class="clear"></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </fieldset>
            <!-- End frequency filters -->

        </div><!-- end section 3 //-->
    </div><!-- // end left column //-->

    <div class="grid4"><!-- // right column //-->
        <div class="widget"><!--// widget 1 //-->
            <div class="whead">
                <h6><span class="icon-cog"></span> Publish</h6>
                <div class="clear"></div>
            </div>
            <fieldset>
                <?php if ($action == 'edit') : ?>
                <div class="formRow">
                    <select name="status" class="fullwidth" data-placeholder="Status...">
                        <option value="1" <?=(@$message->status == 1) ? 'selected="selected"' : ''?>>Active</option>
                        <option value="2" <?=(@$message->status == 2) ? 'selected="selected"' : ''?>>Pending</option>
                        <option value="0" <?=(@$message->status == 0) ? 'selected="selected"' : ''?>>Delete</option>
                    </select>
                </div>
                <?php endif; ?>

                <div class="formRow">
					<button class="buttonL bGreyish fluid" type="submit"><?=($action == 'add') ? '<i class="iconb" data-icon="&#xe099;"></i> &nbsp; Add Message' : '<i class="iconb" data-icon="&#xe097;"></i> &nbsp; Save Message'?></button>
                </div>
            </fieldset>
        </div><!--// end widget 1 //-->
    </div>
    <div class="clear"></div>
</div>

</form>