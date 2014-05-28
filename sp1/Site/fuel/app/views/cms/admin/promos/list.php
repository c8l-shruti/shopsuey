<script>
    var base_url = '<?=Config::get('base_url')?>';
    var login_hash = '<?=$login_hash?>';

    $(function() {
        $(".fancybox").fancybox({
            
        });
    });
</script>

<div id="offersDataTableWrapper" class="widget fluid">
    <div class="whead"><h6><span class="icon-cart"></span><?=$title?></h6><div class="clear"></div></div>
    <?php if ($promo) { ?>
        <div class="whead"><h6><span class="icon-calendar-2"></span>Active from <?=date('m/d/Y h:iA', strtotime($promo->start_date))?> to <?=date('m/d/Y h:iA', strtotime($promo->end_date))?></h6><div class="clear"></div></div>
    <?php } ?>
    
    <?php if (!is_null($rewards)) : ?>
    <table cellpadding="0" cellspacing="0" width="100%" class="tDefault">
        <thead>
            <tr>
                <td width="60" class=""></td>                
                <td class="sortCol"><div class="text-left">Offer Name<span></span></div></td>
                <td class="sortCol"><div class="text-left">Locations<span></span></div></td>
                <td width="80" class="sortCol"><div class="text-left">Created<span></span></div></td>
                <td width="60" class=""></td>
            </tr>
        </thead>
        
        <tbody id="dataTableBody">
            <?php foreach($rewards as $reward) : $offer = $reward->offer // Offer list ?>
                <?php $status = CMS::status($offer->status); ?>
            
                <?php 
                
                $contestantObject = Model_Contestant::find($reward->contestant_id);
                
                if ($contestantObject){
                    $user_id = $contestantObject->user_id;
                }
                
                if ($contestantObject && $reward->redeemed) { 

                    $button_label = "Redeemed";
                    $button_class = "bttn_redeemed";
                    $button_action = "Promo.showWinner('{$promo->id}','{$offer->id}')";

                }else if ($contestantObject) {

                    $button_label = "Delivered";
                    $button_class = "bttn_delivered";
                    $button_action = Config::get('base_url')."admin/promos/getWinner/{$promo->id}/{$offer->id}?login_hash={$login_hash}&ajax=1";
                                        
                }else{

                    $button_label = "Find a winner";
                    $button_class = "bttn_winner";
                    $button_action = Config::get('base_url')."admin/promos/doFindWinner/{$promo->id}/{$offer->id}?login_hash={$login_hash}&ajax=1";

                }
                
                ?>

                <tr class="dataTableDataRow">
                    
                    <td>
                        
                        <a class="fancybox msg_bttn <?=$button_class?>" href="<?=$button_action?>">
                            <?=$button_label?>
                        </div>
                        
                    </td>
                    
                    <td>
                        <?php if ($offer->force_top_message) echo "<strong>" ?>
                        <a href="<?=Uri::create("dashboard/offer/{$offer->id}/edit/")?>"><?=$offer->name?></a>
                        <?php if ($offer->force_top_message) echo "</strong>" ?>
                    </td>
                    <td>
                        <?php
                        $locations = $offer->locations;
                        $more_locations = '';
                        if (($locations_count = count($locations)) > 10) {
                            $locations = array_slice($locations, 0, 10);
                            $more_locations = $locations_count - 10;
                        }
                        echo implode(' / ', array_map(function($loc) { return $loc->name; }, $locations));
                        if ($more_locations) {
                            echo " (and $more_locations additional locations)";
                        }
                        ?>
                    </td>
                    <td><?=date('m/d/Y', $offer->created_at)?></td>
                    <td>
                        <a href="<?=Uri::create("dashboard/offer/{$offer->id}/edit/")?>" class="tablectrl_small bGold tipS" title="Edit Message"><span class="iconb" data-icon="&#xe04d;"></span></a>
                    </td>
                </tr>
                <?php endforeach; ?>
        </tbody>
    </table>

    <?php else: ?>
    <div style="padding:15px">
        <h1>Please select a promo in the left navigation bar</h1>
    </div>
    <?php endif; ?>
</div>
