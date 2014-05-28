<div class="fluid">
    <div class="grid12">
    <?php if (count($subscribers) > 0) : ?>
        <div class="widget" >
            <div class="whead"><h6><i class="icon-checkmark"></i>Newsletter Subscriptions</h6><div class="clear"></div></div>
            <div style="padding:8px"><h6>
                <?= count($subscribers) ?> people have signed up for the newsletter.
                <a target="_blank" href="<?=Uri::create('dashboard/subscribers?csv=1&company_id=' . $company->id)?>">Click here to download the email addresses list.</a>
            </h6></div>
        </div>
    <?php endif; ?>

        <div class="widget">
            <div class="whead">
                <h6><span class="icon-email"></span>Subscribers</h6>
                <div class="clear"></div>
            </div>
        <div class="clear"></div>
        <table cellpadding="0" cellspacing="0" width="100%" class="tDefault" >
            <thead>
                <tr>
                    <td class="header"><div class="text-left">Email Address</div></td>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($subscribers as $email) { ?>
                <tr>
                    <td><?= $email ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
	</div>
</div>