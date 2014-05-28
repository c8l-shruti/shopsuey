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

	//===== Check all checbboxes =====//
	$(".titleIcon input:checkbox").click(function() {
		var checkedStatus = this.checked;
		var table = $(this).data('table');

		if (!table) { return; }
		$(table + " tbody tr td:first-child input:checkbox").each(function() {
			this.checked = checkedStatus;
				if (checkedStatus == this.checked) {
					$(this).closest('.checker > span').removeClass('checked');
					$(this).closest('table tbody tr').removeClass('thisRow');
				}
				if (this.checked) {
					$(this).closest('.checker > span').addClass('checked');
					$(this).closest('table tbody tr').addClass('thisRow');
				}
		});
	});	
	
	$(function() {
    $('#checkAll tbody tr td:first-child input[type=checkbox]').change(function() {
        $(this).closest('tr').toggleClass("thisRow", this.checked);
		});
	});
	
	//===== Dynamic data table =====//
	var oTable = $('.dTable').dataTable({
		"bJQueryUI": false,
		"bAutoWidth": false,
		"sPaginationType": "full_numbers",
		"sDom": '<"H"fl>t<"F"ip>'
	});	
	
	//===== WYSIWYG editor =====//
	$("#editor").cleditor({
		width:"100%", 
		height:"250px",
		bodyStyle: "margin: 10px; font: 12px Arial,Verdana; cursor:text",
		useCSS:true
	});	
	
	//===== Tags =====//	
	$('.tags').tagsInput({width:'100%'});
	
	//===== Datepicker =====//	
	$( ".datepicker" ).datepicker({ 
		defaultDate: +7,
		showOtherMonths:true,
		autoSize: true,
		dateFormat: 'mm-dd-yy'
	});
	
	//===== Help icons =====//
	$('.info-help').click(function (e) {
		e.preventDefault();
		
		var topic = $(this).data('help');
		
		if (topic) {
			var target = $(this).data('target');
			var info = help[topic];
			if (target && info) { 
				$(target).html(help[topic]);
				$(target).slideToggle('fast'); 
			}
		}
	});
	
	//===== jQuery UI dialog =====//
    $('#dialog-modal').dialog({
        autoOpen: false,
        width: 400,
		modal: true,
        buttons: {
            "Delete": function () {
                $(this).dialog("close");
            },
            "Cancel": function () {
                $(this).dialog("close");
            }
        },
		create: function () {
			$(this).closest(".ui-dialog").find("button:first").addClass("confirm");
		}
    });
	
	$('#dialog-calendar').dialog({
		autoOpen: false,
		width: '75%',
		height: 655,
		modal: true,
		buttons: {
			"Close": function () { $(this).dialog('close'); }	
		},
		create: function () {
			$(".ui-dialog-titlebar").hide();
		},
		open: function () {
			$('#calendar').delay(100).fullCalendar('render');
		}
	});

    $('#dialog-upload').dialog({
       autoOpen: false,
        width: '75%',
        height: 355,
        modal: true,
        buttons: {
            "Done": function () { $(this).dialog('close'); }
        }
    });
	
    // Dialog Link
    $('.remove').click(function (e) {
		e.preventDefault();
        $('#dialog-modal').dialog('open');
        return false;
    });
	
	$('#calendar-open').click(function (e) {
		e.preventDefault();
		$('#dialog-calendar').dialog('open');
		return false;
	});

    $('#upload-open').click(function (e) {
        e.preventDefault();
        $('#dialog-upload').dialog('open');
    })


    //===== Thumbs =====//
	$('#content').resize(function () {
		var cnt = Math.floor($('.thumbs').parent().width() / 90);
		var w = (cnt * 90) - 10;
		
		var x = ($('.thumbs').parent().width() - w) / 2;
		
		$('.thumbs').css('margin-left', x + 'px');
	}).resize();

    //===== Sortable lists =====//
    $(".sortable").sortable({disabled: true, cursor: 'move'});
    $(".sortable").disableSelection();
	/*
		TEST INSTANCES !!!!!
	*/
	$('.save-button').click(function (e) {
		e.preventDefault();
		$('html, body').animate({ scrollTop: 0 }, 0);
		$('.nSuccess').delay(250).slideToggle('fast').delay(5000).slideToggle('fast');
	});

	$('#check19').change(function () {
		var chkd = $(this).attr('checked');
		
		if (chkd) { $('.timesel').slideUp('fast'); }
		else { $('.timesel').slideDown('fast'); }
	});
	
	$('.time-spinner').spinner(); 
	
	$('.remove-toggle').click(function (e) {
		e.preventDefault();
        var cansort = $('.remove').is(":visible");
        $('.sortable').sortable('option', 'disabled', cansort);
        $('.remove').toggle();
	});

    //===== File uploader =====//

    $("#uploader").pluploadQueue({
        runtimes : 'html5,html4',
        url : 'php/upload.php',
        max_file_size : '100kb',
        unique_names : true,
        filters : [
            {title : "Image files", extensions : "jpg,gif,png"}
        ]
    });

    //===== Calendar =====//
	var date = new Date();
	var d = date.getDate();
	var m = date.getMonth();
	var y = date.getFullYear();
	
	$('#calendar').fullCalendar({
		header: {
			left: 'prev,next',
			center: 'title',
			right: 'month,agendaWeek,agendaDay'
		},
		editable: true,
		height: 525,
		events: [
			{
				title: '2 Day Sale',
				start: new Date(y, 6, 21),
				end: new Date(y, 6, 22)
			},
			{
				title: 'Summer Clearance Sale',
				start: new Date(y, 6, d-5),
				end: new Date(y, 6, d+4)
			},
			{
				title: 'Ala Moana Grand Opening!',
				start: new Date(y, 6, 20),
				allDay: true	
			}
		]
	});
	
	$('.search-toggle').click(function (e) {
		e.preventDefault();
		$('.tablePars').slideToggle('fast');
	});
	
	//===== Form elements styling =====//
	$("select, .check, .check :checkbox, input:radio, input:file").uniform();

});

	
