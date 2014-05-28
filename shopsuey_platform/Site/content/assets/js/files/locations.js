$(function() {

	//===== Add class on #content resize. Needed for responsive grid =====//
	$('#content').resize(function () {
	  var width = $(this).width();
		if (width < 769) {
			$(this).addClass('under');
		}
		else {
			$(this).removeClass('under');
		}
	}).resize(); // Run resize on window load

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

});
