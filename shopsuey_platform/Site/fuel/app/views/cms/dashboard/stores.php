<?php
function friendly_number($number) {
    if ($number > 1000000) {
        return round($number / 1000000, 0) . "m";
    } elseif ($number > 1000) {
        return round($number / 1000, 0) . "k";
    }
    return $number;
}
?>

<div class="fluid" id="healthMetrics">
    <div class="m15">
        <div class="healthWidget pink">
            <div class="healthWidgetNumber"><?=friendly_number($current->favorites_count)?></div>
            <div class="healthWidgetDescription">Favorites</div>
        </div>
        
        <div class="healthWidget salmon">
            <div class="healthWidgetNumber"><?=friendly_number($current->offers_count)?></div>
            <div class="healthWidgetDescription">Offers</div>
        </div>
        
        <div class="healthWidget orange">
            <div class="healthWidgetNumber"><?=friendly_number($current->events_count)?></div>
            <div class="healthWidgetDescription">Events</div>
        </div>
        
        <div class="healthWidget yellow">
            <div class="healthWidgetNumber"><?=friendly_number($current->sign_ups_count)?></div>
            <div class="healthWidgetDescription">Sign-Ups</div>
        </div>
        
        <div class="healthWidget green">
            <div class="healthWidgetNumber"><?=friendly_number($current->check_ins_count)?></div>
            <div class="healthWidgetDescription">Check-Ins</div>
        </div>
        
        <div class="healthWidget blue">
            <div class="healthWidgetNumber"><?=friendly_number($current->likes_count)?></div>
            <div class="healthWidgetDescription">Likes</div>
        </div>
        
        <div class="healthWidget purple">
            <div class="healthWidgetNumber"><?=friendly_number($current->follows_count)?></div>
            <div class="healthWidgetDescription">Followers</div>
        </div>
        <div class="clear"></div>
    </div>
    
    <div id="storeMetrics">
        <div>
            <div class="storeMetricsRowHeader" style="height: 80px">
                <input id="searchBox" type="text" placeholder="Search...">
            </div>
            <div>
                <a href="<?=Uri::create('dashboard/stores/')?>?sortby=favorites_count<?php if ($sortby == 'favorites_count' && $sort == 'desc') echo "&sort=asc"; ?>">
                    <?=Asset::img('dashboard/stores-header-1.png')?>
                </a>
                <a href="<?=Uri::create('dashboard/stores/')?>?sortby=offers_count<?php if ($sortby == 'offers_count' && $sort == 'desc') echo "&sort=asc"; ?>">
                    <?=Asset::img('dashboard/stores-header-2.png')?>
                </a>
                <a href="<?=Uri::create('dashboard/stores/')?>?sortby=events_count<?php if ($sortby == 'events_count' && $sort == 'desc') echo "&sort=asc"; ?>">
                    <?=Asset::img('dashboard/stores-header-3.png')?>
                </a>
                <a href="<?=Uri::create('dashboard/stores/')?>?sortby=sign_ups_count<?php if ($sortby == 'sign_ups_count' && $sort == 'desc') echo "&sort=asc"; ?>">
                    <?=Asset::img('dashboard/stores-header-4.png')?>
                </a>
                <a href="<?=Uri::create('dashboard/stores/')?>?sortby=check_ins_count<?php if ($sortby == 'check_ins_count' && $sort == 'desc') echo "&sort=asc"; ?>">
                    <?=Asset::img('dashboard/stores-header-5.png')?>
                </a>
                <a href="<?=Uri::create('dashboard/stores/')?>?sortby=likes_count<?php if ($sortby == 'likes_count' && $sort == 'desc') echo "&sort=asc"; ?>">
                    <?=Asset::img('dashboard/stores-header-6.png')?>
                </a>
                <a href="<?=Uri::create('dashboard/stores/')?>?sortby=follows_count<?php if ($sortby == 'follows_count' && $sort == 'desc') echo "&sort=asc"; ?>">
                    <?=Asset::img('dashboard/stores-header-7.png')?>
                </a>
            </div>
            <div class="clear"></div>
        </div>
        <?php foreach ($stores as $store) { ?>
            <div class="storeMetricsCompleteRow">
                <div class="storeMetricsRowHeader" data-store-name="<?= $store->name ?>">
                        <?php if ($store->logo) { ?>
                            <?php
                            try {
                                echo Asset::img(Config::get('cms.logo_images_path').DS.'small_'.$store->logo); 
                            } catch (Exception $e) {
                                echo "<h3>$store->name</h3>";
                            }
                            ?>
                        <?php } else { ?>
                            <h3><?= $store->name ?></h3>
                        <?php } ?>
                </div>
                <div class="storeMetricsRow">
                    <?php 
                        $current = $stores_metrics[$store->id];
                    ?>
                    <div class="m15">
                        <div class="healthWidget pink">
                            <div class="healthWidgetNumber"><?=friendly_number($current->favorites_count)?></div>
                            <div class="healthWidgetDescription">Favorites</div>
                        </div>

                        <div class="healthWidget salmon">
                            <div class="healthWidgetNumber"><?=friendly_number($current->offers_count)?></div>
                            <div class="healthWidgetDescription">Offers</div>
                        </div>

                        <div class="healthWidget orange">
                            <div class="healthWidgetNumber"><?=friendly_number($current->events_count)?></div>
                            <div class="healthWidgetDescription">Events</div>
                        </div>

                        <div class="healthWidget yellow">
                            <div class="healthWidgetNumber"><?=friendly_number($current->sign_ups_count)?></div>
                            <div class="healthWidgetDescription">Sign-Ups</div>
                        </div>

                        <div class="healthWidget green">
                            <div class="healthWidgetNumber"><?=friendly_number($current->check_ins_count)?></div>
                            <div class="healthWidgetDescription">Check-Ins</div>
                        </div>

                        <div class="healthWidget blue">
                            <div class="healthWidgetNumber"><?=friendly_number($current->likes_count)?></div>
                            <div class="healthWidgetDescription">Likes</div>
                        </div>

                        <div class="healthWidget purple">
                            <div class="healthWidgetNumber"><?=friendly_number($current->follows_count)?></div>
                            <div class="healthWidgetDescription">Followers</div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
        <?php } ?>
    </div>
    
</div>

<script type="text/javascript">
$(window).load(function() {
    $('#searchBox').keyup(function() {
        var search = $('#searchBox').val();
        $('.storeMetricsCompleteRow').each(function() {
            var row = $(this)
            var storeName = row.find('.storeMetricsRowHeader').attr('data-store-name')
            if (storeName.toLowerCase().indexOf(search.toLowerCase()) === -1) {
                row.hide()
            } else {
                row.show()
            }
        })
    })
})
</script>