$(function() {

	//===== iButtons =====//
	$('.on_off :checkbox, .on_off :radio').iButton({
		labelOn: "",
		labelOff: "",
		enableDrag: false
	});

	$('.yes_no :checkbox, .yes_no :radio').iButton({
		labelOn: "On",
		labelOff: "Off",
		enableDrag: false
	});

	$('.enabled_disabled :checkbox, .enabled_disabled :radio').iButton({
		labelOn: "Enabled",
		labelOff: "Disabled",
		enableDrag: false
	});

	$('#price_regular').change(function () {
		calc_price();
	}).change();

	$('#savings').change(function () {
		calc_price();
	}).change();


	function calc_price() {
		var price = $('#price_regular').val();
		var discount = $('#savings').val();

		if (!price || !discount) { return; }

		price = Number(price);

		if (isNaN(discount)) {
			var arr = discount.split('%');
			discount = Number(arr[0]);
			discount = discount * 0.01;
			discount = price * discount;
		}
		var amt = price - discount;

		$('#price_offer').val(amt.toFixed(2));
	}

	// Images gallery
	$('#gallery').cycle({
		fit:    1,
		fx:     'fade',
	    speed:  'normal',
	    timeout: 0,
	    pager:  '#gallery-nav',
	    next:	'#gallery-next',
	    prev: 	'#gallery-previous'
	});

});
