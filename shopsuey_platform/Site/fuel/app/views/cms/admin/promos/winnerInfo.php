<?php

//$lname = $user->get_meta_field_value('lname');
//$fname = $user->get_meta_field_value('fname');
$realName = $winnerUser->get_meta_field_value('real_name');

?>

<div id="winnerFoundArea" style="width: 600px; height: 285px;">
    
    <div class="winnerRealName">
        <?=$realName?>
    </div>
    <div class="winnerEmail">
        <?=$winnerUser->email?>
    </div>
    <div class="winnerPicture">
        <?php
        $profPic = $winnerUser->getProfilePicUrl();
        
        if ($profPic != ""){
        ?>
        <img height="320" width="235" class='winnerProfPic' src='<?=$profPic?>' />
        <?php
        }else{
        ?>
        <div class='winnerNoPic'>No picture available</div>
        <?php
        }
        ?>
    </div>
    
    <div class="winnerAccountType"></div>
     
    <div class="winnerTitleSince">
        <div class="winnerUserSinceTitle">
            ShopSuey user since
        </div>

        <div class="winnerUserSince">
            <?php echo date("n-j-Y", $winnerUser->created_at); ?>
        </div>
    </div>
    
    <?php if ($isNew){ ?>
        <div id="findAnotherWinnerButton" class="winnerFindAnother" onclick="Promo.doFindWinner('<?=$promoId?>','<?=$offerId?>')">Find another winner</div>
        <div id="deliverButton" class="deliverButton" onclick="Promo.deliver('<?=$promoId?>','<?=$offerId?>','<?=$winnerUser->id?>')">Deliver</div>
        <div id="winnerNotificationMsg"></div>
    <?php } ?>
    
    <div id="winnerProfileButton" class="winnerProfileButton" onclick="location.href=base_url+'dashboard/user/<?=$winnerUser->id?>/edit'">User profile</div>
    
</div>