// JavaScript Document
$(function() {
	$("table").each(function () {
		if (!$(this).hasClass('dTable')) {
			$(this).tablesorter();
		}
	});

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

	//===== Dynamic data table =====//
	$('.dTable').each(function() {
		$(this).dataTable({
		"bJQueryUI": false,
		"bAutoWidth": false,
		"sPaginationType": "full_numbers",
		"sDom": '<"H"fl>t<"F"ip>'
		});
		$('select').chosen({allow_single_deselect: true, disable_search_threshold: 20});
		$('.chzn-container').css('width', '90px');
	});

	$('.tags').each(function () {
		var placeholder = ($(this).attr('placeholder')) ? $(this).attr('placeholder') : 'add a tag';
		$(this).tagsInput({width: '100%', defaultText: placeholder, placeholderColor: '#9F9F9F'});
	});

	$('a').click(function (e) {
		if ($(this).attr('href') == '#') { e.preventDefault(); }
	});

	$('.save-button').click(function () {
		var fnd = false;
		$(this).parents().each(function () {
			if ($(this).is('form') && fnd != true) { fnd == true; $(this).submit(); }
		});
	});

	$('.has a').click(function (evt) {
		$(this).parent().find('ul').slideToggle(100);
	});

	$(document).bind('click', function (evt) {
		var $clicked = $(evt.target);
		if (!$clicked.parents().hasClass('has')) {
			$('.has a').parent().find('ul').slideUp(100);
		}
	});

	$('.checker').live('click', function (evt) {
		var checked = $(this).find('span').eq(0).hasClass('checked');
		$(this).find('input').eq(0).attr('checked', checked);
		$.uniform.update();
	});

	$('.slide-toggle').live('click', function (evt) {
		var target = $(this).data('target');
		if (!target) { target = $(this).attr('rel'); }
		if (!target) { return; }
		$(target).slideToggle(150);
	});

	$('input.number').each(function () {
		var dec = $(this).data('decimial');
		var max = $(this).data('max');
		var min = $(this).data('min');
		var step = $(this).data('step');
		var width = $(this).data('width');

		var opt = {
			decimals: (dec) ? dec : 0,
			stepping: (step) ? step : 1
		}

		if (max) { opt['max'] = max; }

		if (min) { opt['min'] = min; }
		else { opt['min'] = 0; }

		if (width) { opt['width'] = width; }
		else { opt['width'] = 50; }

		$(this).spinner(opt).bind('spinchange', function () { $(this).change(); });
	});

	//===== Form elements styling =====//
	$('select').chosen({allow_single_deselect: true, disable_search_threshold: 20});

	$(".check, .check :checkbox, input:radio, input:file").uniform();

	// ===== timepicker =====//

	$('.timepicker').timeEntry({
		showSeconds: false,
		spinnerImage: timespinner,
		spinnerSize: [19, 26, 0],
		spinnerIncDecOnly: true
	});

	// ===== datepicker =====//
	$('.datepicker').each(function (){
		var opt = {
			showOtherMonths: true,
			selectOtherMonths: true,
			autoSize: true,
			dateFormat: 'mm/dd/yy'
		}

		// ranged date
		var max = $(this).data('max');
		var min = $(this).data('min');

		if (max) {
			opt['onSelect'] = function (sel) { $(max).datepicker('option', 'minDate', sel); }
		}

		if (min) {
			opt['onSelect'] = function (sel) { $(min).datepicker('option', 'maxDate', sel); }
		}

		$(this).datepicker(opt);
	});

	//===== Timezone =====//
	if (redir) {
		var tzd = new Date();
		var timezone = -tzd.getTimezoneOffset();
		var data = {zone: timezone};
		$.get(redir, data, function (output) { });
	}

	//===== Tooltips =====//
	$('*').each(function () {
		if ($(this).prop('title') && !$(this).prop('rel')) {
			$(this).prop('rel', 'tooltip');
		}
	});

	$('*[rel=tooltip]').tipsy({gravity: 'e', fade: true, html:true});
	$('*[rel=tooltipB]').tipsy({gravity: 'n', fade: true, html:true});
	$('*[rel=tooltipL]').tipsy({gravity: 'e', fade: true, html:true});
	$('*[rel=tooltipR]').tipsy({gravity: 'w', fade: true, html:true});
	$('*[rel=tooltipT]').tipsy({gravity: 's', fade: true, html:true});

	//===== Notifications =====//
	$('.shutter, .slideDown').delay(250).slideDown('fast');
	$('.autohide').delay(5250).slideUp('fast');

});

/**
 * Hack to fix broken "response" event for the autocomplete plugin
 */
$.widget("ui.customautocomplete", $.extend({}, $.ui.autocomplete.prototype, {
    __response: function(contents) {
        $.ui.autocomplete.prototype.__response.apply(this, arguments);
        $(this.element).trigger("autocompletesearchcomplete", [contents]);
    }
}));
