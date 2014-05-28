<script type="text/javascript">
<?php if (count($dates) > 0) : // Calendar data ?>
	var EVENTS = <?=stripslashes(json_encode($dates))?>;
<?php else : ?>
	var EVENTS = [];
<?php endif; ?>

<?php if (count($checkins) > 0) : // Checkin data ?>
	var CHECKINS = <?=stripslashes(json_encode($checkins))?>;
<?php else : ?>
	var CHECKINS = [];
<?php endif; ?>
</script>

<div class="fluid"><!-- Top Row -->
	<div class="grid12">
        <?php if ($subscribers > 0) : ?>
		<div class="widget">
			<div class="whead"><h6><i class="icon-checkmark"></i>Newsletter Subscriptions</h6><div class="clear"></div></div>
            <div style="padding:8px"><h6>
                <?= $subscribers ?> people have signed up for the newsletter.
                <a target="_blank" href="<?=Uri::create('dashboard/subscribers')?>">Click here to download the email addresses list.</a>
            </h6></div>
		</div>
		<?php endif; ?>
        
        <?php if ($twitterrequests > 0 && (!isset($company->social->twitter) || empty($company->social->twitter))) : ?>
		<div class="widget"">
			<div class="whead"><h6><i class="icon-checkmark"></i>Twitter account requests</h6><div class="clear"></div></div>
            <div style="padding:8px"><h6>
                <?= $twitterrequests ?> people think you should get a Twitter account to share updates.
            </h6></div>
		</div>
		<?php endif; ?>
        
		<?php if (count($checkins) > 0) : ?>
		<div class="widget"><!-- Check Ins -->
			<div class="whead"><h6><i class="icon-checkmark"></i>Check Ins</h6><div class="clear"></div></div>
			<div id="checkins"></div>
		</div>
		<?php endif; ?>

		<?php if (count($dates) > 0) : ?>
		<div class="widget"><!-- Calendar -->
			<div class="whead"><h6><i class="icon-calender"></i>Calendar</h6><div class="clear"></div></div>
			<div id="calendar"></div>
		</div>
		<?php endif; ?>
	</div>
</div>