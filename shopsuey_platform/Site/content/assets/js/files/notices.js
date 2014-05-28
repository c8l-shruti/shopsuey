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

	//===== WYSIWYG editor =====//
	$("#description").each (function () {
		$(this).cleditor({
			width:"100%",
			height:"250px",
			bodyStyle: "margin: 10px; font: 12px Arial,Verdana; cursor:text",
			useCSS:true});
	});


});
