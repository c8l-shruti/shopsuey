<?php $billing_address = !is_null($credit_card) && $credit_card->billingAddress ? $credit_card->billingAddress : NULL; ?>

<form id="updatecc_form" method="POST">
    <?=CMS::create_nonce_field('updatecc', 'nonce')?>
    <div class="fluid">
        <div class="grid9">
            <div class="widget">
                <div class="whead"><h6><span class="icon-card"></span>Card Information</h6><div class="clear"></div></div>
                <fieldset>
                    <div class="formRow">
                        <div class="grid2"><label for="number">Card Number:</label></div>
                        <div class="grid10">
                            <input type="text" id="number" name="number" data-encrypted-name="number" placeholder="Card Number (no spaces or dashes)" maxlength="16" class="creditcard" autocomplete="off" required />
                        </div>
                        <div class="clear"></div>
                    </div>

                    <div class="formRow">
                        <div class="grid2"><label for="expiration">Exp. Date:</label></div>
                        <div class="grid10">
                            <input style="width: 150px" type="text" id="expiration" name="expiration" data-encrypted-name="expiration" placeholder="Exp. Date (MM/YY)" maxlength="5" autocomplete="off" required />
                        </div>
                        <div class="clear"></div>
                    </div>

                    <div class="formRow">
                        <div class="grid2"><label for="cvv">Security Code:</label></div>
                        <div class="grid10">
                            <input type="text" style="width: 150px" id="cvv" name="cvv" data-encrypted-name="cvv" placeholder="Security Code" minlength="3" maxlength="4" required autocomplete="off" class="digits" />
                        </div>
                        <div class="clear"></div>
                    </div>
                </fieldset>
            </div>
            <div class="widget">
                <div class="whead"><h6><span class="icon-home"></span>Billing Address</h6><div class="clear"></div></div>
                <fieldset>
                    <div class="formRow">
                        <div class="grid2"><label for="address">Address:</label></div>
                        <div class="grid10">
                            <input type="text" id="address" name="address" placeholder="Address" value="<?= $billing_address ? $billing_address->streetAddress : ''; ?>" required />
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid2"><label for="city">City:</label></div>
                        <div class="grid10">
                            <input type="text" id="city" name="city" placeholder="City" value="<?= $billing_address ? $billing_address->locality : ''; ?>" required />
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid2"><label for="state">State:</label></div>
                        <div class="grid10">
                            <input type="text" id="state" name="state" placeholder="State" value="<?= $billing_address ? $billing_address->region : ''; ?>" required />
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid2"><label for="zip">Zip Code:</label></div>
                        <div class="grid10">
                            <input type="text" id="zip" name="zip" placeholder="Zip Code" value="<?= $billing_address ? $billing_address->postalCode : ''; ?>" required />
                        </div>
                        <div class="clear"></div>
                    </div>
                </fieldset>
            </div>
        </div>

        <div class="grid3">
            <div class="widget">
                <div class="whead">
                    <h6><span class="icon-cog"></span>Confirm</h6>
                    <div class="clear"></div>
                </div>
                <fieldset class="formpart">
                    <div class="formRow">
                        <button class="buttonL bGreyish fluid" type="submit">
                            <i class="iconb" data-icon="&#xe097;"></i> &nbsp; Confirm Changes
                        </button>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript" src="<?= $client_side_library_url ?>"></script>
<script type="text/javascript">

$(function() {
    $("#updatecc_form").validate();

    var client_side_encryption_key = "<?= $client_side_encryption_key ?>";
    var braintree = Braintree.create(client_side_encryption_key);

    braintree.onSubmitEncryptForm('updatecc_form');
});

</script>