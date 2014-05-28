<div class="sidePad">
    <?php if (Auth::instance('Shopsuey_Session')->get_user_login_object()->user->group == Model_User::GROUP_MANAGER) { ?>
    
        <a class="bigSidebarButton" href="<?=Uri::create('dashboard/active_stats/')?>">
            <?=Asset::img('dashboard/active.png')?>
            <h2><?= $active_percentage ?>% active</h2>
        </a>
    
        <?php if ($number_of_stores) { ?>
        <a class="bigSidebarButton" href="<?=Uri::create('dashboard/stores/')?>">
            <?=Asset::img('dashboard/stores.png')?>
            <h2><?= $number_of_stores ?> stores</h2>
        </a>
        <?php } ?>
    
        <a class="bigSidebarButton" href="<?=Uri::create('dashboard/active_shoppers/')?>">
            <?=Asset::img('dashboard/shoppers.png')?>
            <h2 class="shoppers"><?= number_format($shoppers, 0, "", ","); ?> shoppers</h2>
            <h6>(<?=date('m/d/Y', $active_shoppers_dates['start_time'])?> - <?=date('m/d/Y', $active_shoppers_dates['end_time'])?>)</h6>
        </a>
    
    <?php } ?>
</div>