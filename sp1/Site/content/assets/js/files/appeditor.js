// JavaScript Document
$(function() {
	//===== jQuery UI dialog =====//
    $('#dialog-delete').dialog({
        autoOpen: false,
        width: 400,
		modal: true,
        buttons: {
            "Delete": function () {
                $('#delete').submit();
            },
            "Cancel": function () {
                $(this).dialog("close");
            }
        },
		create: function () {
			$(this).closest(".ui-dialog").find("button:first").addClass("confirm");
		}
    });
	
	$('#dialog-refresh').dialog({
        autoOpen: false,
        width: 400,
		modal: true,
        buttons: {
            "Refresh": function () {
                $('#refresh').submit();
            },
            "Cancel": function () {
                $(this).dialog("close");
            }
        },
		create: function () {
			$(this).closest(".ui-dialog").find("button:first").addClass("confirm");
		}		
	});
	
	$('#form').submit(function (e) {
		if ($('#app-delete').is(':checked')) { 
			e.preventDefault();
			$('#dialog-delete').dialog('open');
			return;
		}
	});
	
	$('#app-delete').change(function () {
		if ($(this).is(':checked')) {
			$('#form .save-button span.text').text('Delete Application');
			$('#form .fields').slideUp('fast'); 
		}
		else { 
			$('#form .save-button span.text').text('Save Application');
			$('#form .fields').slideDown('fast'); 
		}
	}).change();
	
	$('.secret-refresh').click(function (e) {
		$('#dialog-refresh').dialog('open');
	});
	
	//===== iButton =====//
	$('.yes_no :checkbox, .yes_no :radio').iButton({labelOn: 'Yes', labelOff: 'No', enableDrag: true});
	$('.on_off :checkbox, .on_off :radio').iButton({labelOn: '', labelOff: '', enableDrag: true});
	
	$('#form').validationEngine();
});