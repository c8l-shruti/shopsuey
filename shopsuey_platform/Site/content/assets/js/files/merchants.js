$(function() {
	$('.on_off :checkbox, .on_off :radio').iButton({
		labelOn: "",
		labelOff: "",
		enableDrag: false
	});

	$('#delete').change(function (evt) {
		var chk = $(this).is(':checked');

		if (chk == true) {
			$('.formpart').hide();
			$('#delete-form').delay(150).slideDown();
		}
		else {
			$('#delete-form').slideUp(150);
			window.setTimeout(function () { $('.formpart').slideDown(150); }, 200);
		}
	}).change();

	$('#plan').change(function (evt) {
		var target = $(this).data('target');
		var maxusr = $(this).find('option:selected').data('max');
		$(target).val(maxusr);
		if (maxusr) { $(target).attr('readonly', true); }
		else { $(target).attr('readonly', false); }
	});

	//===== WYSIWYG editor =====//

	$("#description").each (function () {
		$(this).cleditor({
			width:"100%",
			height:"250px",
			bodyStyle: "margin: 10px; font: 12px Arial,Verdana; cursor:text",
			useCSS:true});
	});



	if ($('#action').val()) {
		/*
                var wizvalidate = {
			rules: {
				fname: {required: true, minlength: 1},
				email: {required: true, email: true},
				password: {required: true, minlength: 8},
				confirm: {
					required: true,
					minlength: 5,
					equalTo: "#password"
				},
				company: 'required',
			},
			messages: {
				confirm: {
					equalTo: "Passwords do not match",
				}
			}
		};

		if ($('#action').val() == 'edit') {
			delete(wizvalidate.rules.password);
			delete(wizvalidate.rules.confirm);
		}

		$("#wizard1").validate(wizvalidate);
		*/
	}


})