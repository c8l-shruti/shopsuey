<div class="widget fluid">
    <div class="whead">
        <h6><span class="icon-clipboard"></span> Billing Information</h6>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>

    <div style="padding:16px">
        <div class="floatL infoBox">
            <div>Name on card</div>
            <div><strong><?= $user->get_friendly_name() ?></strong></div>
        </div>
        <div class="floatL infoBox">
            <div>Last 4 digits</div>
            <div><strong><?= is_null($credit_card) ? "N/A" : $credit_card->last4 ?></strong></div>
        </div>
        <div class="floatL infoBox">
            <div>Next Payment On</div>
            <div><strong><?= is_null($subscription) ? "N/A" : $subscription->nextBillingDate->format("F d, Y") ?></strong></div>
        </div>
        <div class="floatL infoBox">
            <div>Next Payment Amount</div>
            <div><strong><?= is_null($subscription) ? "N/A" : "$" . $subscription->nextBillAmount ?></strong></div>
        </div>
        <div class="floatL infoBox">
            <div>Balance</div>
            <div><strong><?= is_null($subscription) ? "N/A" : "$" . $subscription->balance ?></strong></div>
        </div>
        
        <?php if (! is_null($credit_card)): ?>
        <div class="floatR">
            <a class="sideB bGold" href="<?=Uri::create('dashboard/profile/updatecc')?>" style="padding:7px 20px">
                <span class="icon-card"></span>
                <span>Update Credit Card</span>
            </a>
        </div>
        <?php endif; ?>

        <div class="clear"></div>
    </div>
</div>

<div class="widget fluid">
    <div class="whead">
        <h6><span class="icon-clock"></span> Billing History</h6>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
    <?php if (!is_null($subscription) && count($subscription->transactions) > 0): ?>
        <table cellpadding="0" cellspacing="0" width="100%" class="tDefault" >
            <thead>
                <tr>
                    <td class="header"><div class="text-left">Type</div></td>
                    <td class="header"><div class="text-left">Date</div></td>
                    <td class="header"><div class="text-left">Pay Method</div></td>
                    <td class="header"><div class="text-left">Amount</div></td>
                    <td class="header"><div class="text-left">Status</div></td>
<!--                     <td class="header"><div class="text-left">Invoice</div></td> -->
<!--                     <td class="header"><div class="text-left">Receipt</div></td> -->
                </tr>
            </thead>
            <tbody>
                <?php foreach($subscription->transactions as $transaction): ?>
                    <tr>
                        <td><?= $transaction_types[$transaction->type] ?></td>
                        <td><?= $transaction->createdAt->format("F d, Y") ?></td>
                        <td>Credit Card</td>
                        <td>$<?= $transaction->amount ?></td>
                        <td><?= $transaction_statuses[$transaction->status] ?></td>
<!--                         <td>No. 3489</td> -->
<!--                         <td>view receipt</td> -->
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="floatL infoBox" style="padding:16px">
            <div><strong>You've been charged $0</strong></div>
        </div>
    <?php endif; ?>
</div>