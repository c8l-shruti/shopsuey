// JavaScript Document
$.extend({
  password: function (length, special) {
    var iteration = 0;
    var password = "";
    var randomNumber;
    if(special == undefined){
        var special = false;
    }
    while(iteration < length){
        randomNumber = (Math.floor((Math.random() * 100)) % 94) + 33;
        if(!special){
            if ((randomNumber >=33) && (randomNumber <=47)) { continue; }
            if ((randomNumber >=58) && (randomNumber <=64)) { continue; }
            if ((randomNumber >=91) && (randomNumber <=96)) { continue; }
            if ((randomNumber >=123) && (randomNumber <=126)) { continue; }
        }
        iteration++;
        password += String.fromCharCode(randomNumber);
    }
	randomNumber = Math.floor((0 + (Math.random()*(9-0)))*10);
	password += randomNumber;
    return password;
  }
});

jQuery.validator.addMethod("locationsCheck", function(value, element) {
	console.log($("#group").val());
	if ($("#group").val() == "25" || $("#group").val() == "50") {
		return $('input[name="location_ids[]"]').length > 0;
	}
	return true;
}, "The selected role requires at least one location");

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

	$('#autogen').change(function (evt) {
		var chk = $(this).is(':checked');

		if (chk == true) {
			$('#emailpass').attr('checked', true).change();
			$('#confirm').parent().slideUp(150);
			$('#password').val($.password(8));
			$('#confirm').delay(150).val($('#password').val());
		}
		else {
			$('#confirm').parent().slideDown(150);
			$('#password, #confirm').val('');
		}
	}).change();

	$('#group').change(function () {
		var level = Number($('#group :selected').val());
		var roles = $('#group :selected').data('roles');
		var ulevel = Number($('#ulevel').val());
		var uperms = $('#uperms').val();
		if (uperms) { uperms = uperms.split(','); }

		$(".perm :checkbox").attr('checked', false);
		$(".perm").hide();

		if (roles) {
			roles = roles.split(',');

			$.each(roles, function (index, value) {
				var cls = "."+value;
				if (level != ulevel) { $(cls + " :checkbox").attr("checked", true); }
				else {
					if (uperms) {
						$('.'+value).each(function () {
							var chkbox = $(this).find(':checkbox');
							var pname = chkbox.attr('name');
							if (uperms.indexOf(pname) > -1) { chkbox.attr('checked', true); }
						});
					}
				}
				$(cls).show();
			});
		}

		if (!level) { level = -2; }

		if (level == 25) { $("#locations").slideDown(150); }
		else { $("#locations").slideUp(150); }

		if (level >= 25) { $("#permissions").slideDown(150); }
		else { $("#permissions").slideUp(150); }

		$.uniform.update();

	}).change();


	if ($('#action').val()) {
		var wizvalidate = {
			rules: {
				"meta[real_name]": {required: true, minlength: 1},
				email: {required: true, email: true},
				password: {required: true, minlength: 8},
				confirm: {
					required: true,
					minlength: 8,
					equalTo: "#password"
				},
				company: 'required',
				location_search: 'locationsCheck'
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
	}


})