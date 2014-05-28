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


	//===== Form elements styling =====//
	
	$("select, .check, .check :checkbox, input:radio, input:file").uniform();

	//===== Navigation click ======//
	$('#sidebar .mainNav .nav .btn').click(function (evt) {
		evt.preventDefault();
		$('#sidebar .mainNav .nav .btn').removeClass('active');
		$('#sidebar .mainNav .nav li').removeClass('selected');		
		$(this).parent().addClass('selected');
		$(this).addClass('active');
		
		$('.secNav .secWrapper .sub-menu').hide();
		
		var menu = $(this).data('menu');
		if (menu) { $(menu).show(); }
	});
	$('#sidebar .mainNav .nav .btn.active').click();
	
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

	
