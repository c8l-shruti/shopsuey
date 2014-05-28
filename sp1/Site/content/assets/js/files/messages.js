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

	$('.opt_sel').change(function() {
		var pars = $(this).parents().find('input.opt');
		pars.first().attr('checked', true);
		pars.first().change();
		$.uniform.update();
	});

	$('#trigger_toggle').change(function () {
		var rel = $(this).attr('rel');
		if (!rel) { return; }

		var chk = $(this).is(':checked');

		if (chk == true) {
			$('input:radio[name="trigger_type"]').attr('checked', false);
			$.uniform.update('input:radio');
			$(rel).slideUp(150);
		}
		else { $('.slide-toggle-3').show(); $(rel).slideDown(150); }
	});

	$('input:radio[name="trigger_type"]').change(function () {
		if ($(this).is(':checked')) {
			if ($(this).data('show')) {
				$($(this).data('show')).slideDown(150);
			}
			if ($(this).data('hide')) {
				$($(this).data('hide')).slideUp(150);
			}
		}
	});

	$('#repeat_type').change(function () {
		$('.repeat_opt').hide();

		var rel = $(this).find('option:selected').first().attr('rel');
		if (!rel) { return; }

		$(rel).slideDown(150);
	}).change();

	$('.trigger_sel').change(function () {
		var pars = $(this).parents().find('input:radio[name="trigger_type"]');
		pars.first().attr('checked', true);
		pars.first().change();
		$.uniform.update();
	});

	$('.filter_sel').change(function () {

		var pars = $(this).parents().find('input.filter');
		pars.first().attr('checked', true);
		pars.first().change();
		$.uniform.update();
	});

	$('.filter_sub_sel').change(function () {
		var pars = $(this).parents().find('input.filter_sub');
		pars.first().attr('checked', true);
		pars.first().change();
		$.uniform.update();
	});

	$('.sections li a').click(function () {
		$('.sections li').removeClass('activeTab');
		$('.section').hide();
		$($(this).attr('href')).show();
		$(this).parent().addClass('activeTab');
	});


	// Show active tab
	$('.section').hide();

	if (location.hash) {
		$(location.hash).show();
		$('.sections li a').each(function () {
			if ($(this).attr('href') == location.hash) { $(this).parent().addClass('activeTab'); }
		});
		window.setTimeout(function () { location.hash = location.hash; }, 100);
	}
	else {
		$('#message').show();
		$('.sections li').first().addClass('activeTab');
	}
});
