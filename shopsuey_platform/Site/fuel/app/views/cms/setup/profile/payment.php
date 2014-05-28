<body class="background payment with-logo">
    <div class="content">
        <p style="text-align: left !important">You are logged in: <?=$business_name?>. <a class="floatR" href="<?= Uri::create('logout') ?>">Not you? Logout</a></p>
        <div class="login-wrapper">
            <div class="step-counter">Step 3 of 3</div>
            <?=Form::open(array('id' => 'payment_form', 'action' => Uri::create('setup/profile/payment'), 'method' => 'POST', 'class' => 'profile'))?>
            <div id="paymentFormWrapper">
                <p>
                    ShopSuey is only $<?=$fees_info['merchant_monthly_fee']?> per month per store or $<?=$fees_info['mall_monthly_fee']?> per month per marketplace.<br>
                    <a target="_blank" href="/assets/static/refund-pricing.html">See what's included in your subscription</a> and feel free to change your membership at any time
                </p>
                <p>
                    <strong>
                    Enjoy a <?=$fees_info['trial_days']?> day free trial by signing up now!
                    </strong>
                </p>

                <?=CMS::field_error(@$notice, null)?>

                <?=CMS::create_nonce_field('payment', 'nonce', 'payment_nonce')?>
                <div <?=CMS::field_error_wrapper_class(@$notice, 'number')?>>
                    <?=Form::input(array('type' => 'text', 'data-encrypted-name' => 'number', 'placeholder' => 'Credit Card Number', 'size' => '16', 'autocomplete' => 'off', 'id' => 'number'))?>
                    <?=CMS::field_error(@$notice, 'number')?>
                </div>
                <div <?=CMS::field_error_wrapper_class(@$notice, 'expiration')?>>
                    <?=Form::input(array('type' => 'text', 'data-encrypted-name' => 'expiration', 'placeholder' => 'Exp. Date (MM/YY)', 'size' => '5', 'autocomplete' => 'off', 'id' => 'expiration'))?>
                    <?=CMS::field_error(@$notice, 'expiration')?>
                </div>
                <div <?=CMS::field_error_wrapper_class(@$notice, 'cvv')?>>
                    <?=Form::input(array('type' => 'text', 'data-encrypted-name' => 'cvv', 'placeholder' => 'Security Code', 'size' => '4', 'autocomplete' => 'off', 'id' => 'cvv'))?>
                    <?=CMS::field_error(@$notice, 'cvv')?>
                </div>

                <div style="text-align: center">
                    <strong>Billing Address</strong>
                </div>

                <div <?=CMS::field_error_wrapper_class(@$notice, 'address')?>>
                    <?=Form::input(array('type' => 'text', 'name' => 'address', 'placeholder' => 'Address', 'size' => '30'))?>
                    <?=CMS::field_error(@$notice, 'address')?>
                </div>
                <div <?=CMS::field_error_wrapper_class(@$notice, 'city')?>>
                    <?=Form::input(array('type' => 'text', 'name' => 'city', 'placeholder' => 'City', 'size' => '30'))?>
                    <?=CMS::field_error(@$notice, 'city')?>
                </div>
                <div <?=CMS::field_error_wrapper_class(@$notice, 'state')?>>
                    <?=Form::input(array('type' => 'text', 'name' => 'state', 'placeholder' => 'State', 'size' => '30'))?>
                    <?=CMS::field_error(@$notice, 'state')?>
                </div>
                <div <?=CMS::field_error_wrapper_class(@$notice, 'zip')?>>
                    <?=Form::input(array('type' => 'text', 'name' => 'zip', 'placeholder' => 'Zip Code', 'size' => '5'))?>
                    <?=CMS::field_error(@$notice, 'zip')?>
                </div>
            </div>

            <div id="promoWrapper">
                <div style="text-align: center">
                    <strong>Promo Code</strong>
                </div>
            
                <div>
                    <?=Form::input(array('style' => 'display:inline-block', 'type' => 'text', 'placeholder' => 'ADD A PROMO CODE', 'size' => '20', 'autocomplete' => 'off', 'id' => 'promo', 'name' => 'promo', 'class' => 'not-mandatory'))?>
                    <?=Asset::img('elements/loaders/1s.gif', array('style' => 'display:none', 'id' => 'promoLoader'));?>
                    <span class="check" style="display:none;margin-left:0" id='promoCheck'>&#x2713;</span>
                </div>
                <div id='promoDiscount' style="display:none"></div>
                <div id='promoFree' style="display:none">This promo code allows you to use ShopSuey for free!</div>
                <div id='promoError' class="fieldError" style="display:none">This doesn't seem to be a valid promo code</div>
                <a id="promoDontHave" style="font-size: 12px;" href="#" onclick="noPromoCode()">I don't have a promo code</a>
            </div>
            
            <div class="terms-checkbox-wrapper mb15">
                <?=Form::input(array('type' => 'checkbox', 'name' => 'privacy', 'id' => 'privacy', 'value' => '1'))?>
                <label for="privacy">I have read and agree to the <a target="_blank" href="http://www.thesuey.com/assets/static/privacy.html">privacy policy</a> and <a target="_blank" href="http://www.thesuey.com/assets/static/refund-pricing.html">refund policy</a></label>
                <?=CMS::field_error(@$notice, 'privacy')?>
            </div>

            <p>
                Questions? <a href="mailto:sales@thesuey.com">sales@thesuey.com</a> or call us at 415.218.3348
            </p>

            <div class="actionsWrapper">
                <div class="mt25 mb25">
                    <input type="hidden" id="freePromoCode" value="0"/>
                    <input class="big-text" type="submit" value="Submit" name="submit">
                </div>
            </div>
                
        	<?=Form::close()?>
        </div>
    </div>

<script type="text/javascript" src="<?= $client_side_library_url ?>"></script>
<script type="text/javascript">
$(document).ready(function () {
    var client_side_encryption_key = "<?= $client_side_encryption_key ?>";
    var user_login_hash = "<?= $login_hash; ?>";
    var braintree = Braintree.create(client_side_encryption_key);

    braintree.onSubmitEncryptForm('payment_form');

    function display_error(msg, fieldId) {
        var div = $('<div>').addClass('fieldError').text(msg);
    	$('#' + fieldId).parent().append(div);
        $('#' + fieldId).addClass('fieldErrorInput');
    }

    function validate_payment_form() {
        var is_valid = true;

        if (! $('#privacy').is(':checked')) {
        	display_error('You must agree to the privacy and refund policies', 'privacy');
        	is_valid = false;
        }
        
        if ($('#promo').val() != '' && $('#freePromoCode').val() == '1') {
            return is_valid;
        }
        
        var empty_text_field = false;
        $('#payment_form input[type="text"]:not(.not-mandatory)').each(function() {
        	if ($(this).val() === '') {
                display_error("This field is mandatory", $(this).attr('id'));
                empty_text_field = true;
                return false;
            }
        });
        
        if (empty_text_field) {
            return false;
        }
        
        if (! /^\d{16}$/.test($('#number').val())) {
            display_error('Invalid credit card number', 'number');
            is_valid = false;
        }

        if (! /^\d{2}\/\d{2}$/.test($('#expiration').val())) {
        	display_error('Invalid expiration date', 'expiration');
            is_valid = false;
        }

        if (! /^\d{3,4}$/.test($('#cvv').val())) {
        	display_error('Invalid security code', 'cvv');
            is_valid = false;
        }

        return is_valid;
    }
    
    $('#payment_form').submit(function(event) {
        $('.fieldError').remove();
        $('input').removeClass('fieldErrorInput');
        var validation_result = validate_payment_form();
        if (! validation_result) {
            event.preventDefault();
        }
    });

    var promoCodeTimer = null;
    function promoCodeChange() {
        clearTimeout(promoCodeTimer);
        $('#promo').removeClass('fieldErrorInput');
        $('#promoError').hide();

        promoCodeTimer = setTimeout(function() {
            $('#promoLoader').show();
            $.get('<?php echo Uri::create('api/promocode/check') ?>', { code : $('#promo').val(), login_hash: user_login_hash }, function(response) {
                var promoCodeType;
                if (response.promo_code) {
                    promoCodeType = response.promo_code.type_name;
                } else {
                    promoCodeType = 'invalid';
                }

                if (promoCodeType == "discount") {
                    $('#promoCheck').show();
                    $('#promoDontHave').hide();
                    $('#promoError').hide();
                    $('#promo').removeClass('fieldErrorInput');
                    $('#promoFree').hide();
                    $('#promoDiscount').hide();
                    
                    if (response.promo_code.description != '') {
                        $('#promoDiscount').html(response.promo_code.description);
                        $('#promoDiscount').show();
                    }
                    
                    $('#freePromoCode').val(0);
                } else if (promoCodeType == "free") {
                    // to do: redirect to proper screen
                    $('#promoCheck').show();
                    $('#promoDontHave').hide();
                    $('#promoError').hide();
                    $('#promo').removeClass('fieldErrorInput')
                    $('#promoFree').show();
                    $('#promoDiscount').hide();
                    hidePaymentForm();
                    
                    $('#freePromoCode').val(1);
                } else {
                    // probably an invalid promo code
                    $('#promo').addClass('fieldErrorInput');
                    $('#promoError').show();
                    $('#promoCheck').hide();
                    $('#promoDontHave').show();
                    $('#promoFree').hide();
                    $('#promoDiscount').hide();
                    displayPaymentForm();
                    
                    $('#freePromoCode').val(0);
                }
                $('#promoLoader').hide();
            }, 'json');
        }, 500);
    }

    window.displayPaymentForm = function() {
        $('#paymentFormWrapper').show({duration:300, easing: 'easeInOutCubic' })
    };

    window.hidePaymentForm = function() {
        $('#paymentFormWrapper').hide({duration:300, easing: 'easeInOutCubic' });
    };

    window.noPromoCode = function() {
        $('#promoWrapper').hide({duration:300, easing: 'easeInOutCubic' });
    };

    $('#promo').keyup(promoCodeChange)
});


</script>

</body>
