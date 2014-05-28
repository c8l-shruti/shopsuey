<div class="sidePad">
    
    <a class="bGold sideB" href="<?=Uri::create('dashboard/offer/add')?>">
        <span class="icon-plus-2"></span>
        <span>
            New Offer
        </span>
    </a>
    
    <div style="display: block; height: 20px;"></div>
    
    <a class="bGold sideB" href="<?=Uri::create('dashboard/offer/import')?>">
        <span class="icon-plus-2"></span>
        <span>
            Import Offers
        </span>
    </a>
    
    <?php if (isset($offer->id)) { ?>
        <a id="duplicate" class="sideB bGold mt5" href="#">
            <span class="icon-plus-2"></span>
            <span>Duplicate offer</span>
        </a>
    <?php } ?>
    
    <?php if (isset($in_offer_list) && $in_offer_list): ?>
    <div class="status-filter-container">
        <div class="filter">
            <input type="checkbox" id="include-active" class="css-checkbox offer-status-filter" <?php if ($active): ?>checked="checked"<?php endif; ?>/>
            <label for="include-active" class="css-label">See active offers</label>
        </div>
        <div class="filter">
            <input type="checkbox" id="include-inactive" class="css-checkbox offer-status-filter" <?php if ($inactive): ?>checked="checked"<?php endif; ?>/>
            <label for="include-inactive" class="css-label">See inactive offers</label>
        </div>
        <div>
            Filtering offers according this timezone: 
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
    <?php endif; ?>
    
    <?php if (isset($offer->id) && !$offer->force_top_message && Auth::instance('Shopsuey_Session')->get_user_login_object()->user->group == Model_User::GROUP_SUPERADMIN) { ?>
        <a class="sideB bGreen mt5" href="<?=Uri::create('dashboard/offer/'.$offer->id.'/force_top_message')?>">
            <span class="icon-email"></span>
            <span>Force top message</span>
        </a>
    <?php } elseif (isset($offer->id) && Auth::instance('Shopsuey_Session')->get_user_login_object()->user->group == Model_User::GROUP_SUPERADMIN) { ?>
        <a class="sideB bRed mt5" href="<?=Uri::create('dashboard/offer/'.$offer->id.'/force_top_message?remove=1')?>">
            <span class="icon-email"></span>
            <span>Undo force top message</span>
        </a>
    <?php } ?>
</div>

<script type="text/javascript">
<?php if (isset($offer->id)): ?>
$(function () {
    $('#duplicate').click(function () {
        if ($('#formChanged').val() == 1) {
            $('<div></div>').appendTo('body')
                .html('<div>Do you want to save the current changes?</div>')
                .dialog({
                    modal: true,
                    title: 'Duplicate Offer',
                    zIndex: 10000,
                    autoOpen: true,
                    width: 300,
                    resizable: false,
                    buttons: {
                        Yes: function () {
                            $('#duplicateOffer').val(1);
                            $('#wizard1').submit();
                        },
                        No: function () {
                            window.location.href = '<?=Uri::create('dashboard/offer/'.$offer->id.'/add')?>';
                        }
                    },
                    close: function (event, ui) {
                        $(this).remove();
                    }
                });
        } else {
            window.location.href = '<?=Uri::create('dashboard/offer/'.$offer->id.'/add')?>';
        }
    });
});
<?php endif; ?>
</script>
