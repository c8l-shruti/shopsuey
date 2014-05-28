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
        
		<div class="bottomFree grid12">
            <div class="grid6">
                <a href="<?=Uri::create('dashboard/offer/add')?>" title="Manage offers" class="nobold" rel="tooltipT">
                    <span class="title">
                        <span class="iconb" data-icon="&#xe018;">
                        </span><span>Make an Offer</span>
                    </span>
                    <span class="description">The way to a shoppers heart is to give<br> a deal they can't refuse</span>
                </a>
            </div>
            <div class="grid6">
                <a href="<?=Uri::create('dashboard/event/add')?>" title="Manage events" class="nobold" rel="tooltipT">
                    <span class="title">
                        <span class="iconb" data-icon="&#xe070;">
                        </span><span>Throw an Event</span>
                    </span>
                    <span class="description">If you throw it they will come</span>
                </a>
            </div>
        </div>
	</div>
</div>