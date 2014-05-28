<div class="sidePad">
    <a class="bGold sideB" href="<?=Uri::create('dashboard/event/add')?>">
        <span class="icon-plus-2"></span>
        <span>New Event</span>
    </a>
    <?php if (Auth::instance('Shopsuey_Session')->get_user_login_object()->user->group == Model_User::GROUP_SUPERADMIN) { ?>
        <a class="bGold sideB mt10" href="<?=Uri::create('dashboard/specialevent/add')?>">
            <span class="icon-plus-2"></span>
            <span>New Special Event</span>
        </a>
    <?php } ?>
    
    <?php if (isset($in_event_list) && $in_event_list): ?>
    <div class="status-filter-container">
        <div class="filter">
            <input type="checkbox" id="include-active" class="css-checkbox event-status-filter" <?php if ($active): ?>checked="checked"<?php endif; ?>/>
            <label for="include-active" class="css-label">See active events</label>
        </div>
        <div class="filter">
            <input type="checkbox" id="include-inactive" class="css-checkbox event-status-filter" <?php if ($inactive): ?>checked="checked"<?php endif; ?>/>
            <label for="include-inactive" class="css-label">See inactive events</label>
        </div>
        <div>
            Filtering events according this timezone: 
            <strong id="current_timezone_name"><?php echo $default_timezone; ?></strong>.
            <a href="#" id="change_current_timezone">Change</a>
            <a href="#" id="cancel_change_current_timezone" style="display: none;">Cancel</a>
        </div>
        <div class="filter timezone-filter" style="display: none;">
            <?php $timezones = Helper_Timezone::get_timezone_list(true); ?>
            <input type="hidden" value="<?php echo $default_timezone; ?>" id="default-timezone"/>
            <select id="timezone" name="timezone" class="fullwidth" data-placeholder="Select your timezone...">
                <?php foreach ($timezones as $timezone): ?>
                <option value="<?= $timezone; ?>" <?php if ($timezone == $default_timezone): ?>selected="selected"<?php endif; ?>><?= $timezone; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    </div>
    <?php endif; ?>
    
    <?php if (isset($event->id) && $event->id && !$event->force_top_message && Auth::instance('Shopsuey_Session')->get_user_login_object()->user->group == Model_User::GROUP_SUPERADMIN) { ?>
        <a class="sideB bGreen mt5" href="<?=$event->special ? Uri::create('dashboard/specialevent/'.$event->id.'/force_top_message') : Uri::create('dashboard/event/'.$event->id.'/force_top_message')?>">
            <span class="icon-email"></span>
            <span>Force top message</span>
        </a>
    <?php } elseif (isset($event->id) && $event->id && Auth::instance('Shopsuey_Session')->get_user_login_object()->user->group == Model_User::GROUP_SUPERADMIN) { ?>
        <a class="sideB bRed mt5" href="<?=$event->special ? Uri::create('dashboard/specialevent/'.$event->id.'/force_top_message?remove=1') : Uri::create('dashboard/event/'.$event->id.'/force_top_message?remove=1')?>">
            <span class="icon-email"></span>
            <span>Undo force top message</span>
        </a>
    <?php } ?>
</div>