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
	
	//===== Quick Stats - Checkins ======//
	var d1 = [[0, 100], [1, 500], [2, 50], [3, 400], [4, 300], [5, 800], [6, 1000]];
	
	var opts1 = {
		series: {
		   lines: { show: true },
		   points: { show: true },
		   color: '#ee4036'
		},
		grid: { hoverable: true, clickable: true },
		xaxis: {
			min: 0,
			max:  6,
			ticks: [[0, 'Mon'], [1, 'Tue'], [2, 'Wed'], [3, 'Thu'], [4, 'Fri'], [5, 'Sat'], [6, 'Sun']]
		},
		yaxis: {
			min: 0,
			max: 1000,
		},
	};
	
	var qsCheckins = $.plot($("#qstat-checkins"), [d1], opts1);
	
	//===== Dashboard - In App Views ======//
	var d2 = [[1196463600000, 100], [1196550000000, 500], [1196636400000, 0], [1196722800000, 77], [1196809200000, 1630], [1196895600000, 1975], [1196982000000, 1200], [1197068400000, 1000], [1197154800000, 676], [1197241200000, 1205], [1197327600000, 906], [1197414000000, 710], [1197500400000, 639], [1197586800000, 540], [1197673200000, 435], [1197759600000, 301], [1197846000000, 575], [1197932400000, 481], [1198018800000, 591], [1198105200000, 608], [1198191600000, 459], [1198278000000, 234], [1198364400000, 1352], [1198450800000, 686], [1198537200000, 279], [1198623600000, 449], [1198710000000, 468], [1198796400000, 392], [1198882800000, 282], [1198969200000, 208], [1199055600000, 229], [1199142000000, 177], [1199228400000, 374], [1199314800000, 436], [1199401200000, 404], [1199487600000, 253], [1199574000000, 218], [1199660400000, 476], [1199746800000, 462], [1199833200000, 448], [1199919600000, 442], [1200006000000, 403], [1200092400000, 204], [1200178800000, 194], [1200265200000, 327], [1200351600000, 374], [1200438000000, 507], [1200524400000, 546], [1200610800000, 482], [1200697200000, 283], [1200783600000, 221], [1200870000000, 483], [1200956400000, 523], [1201042800000, 500], [1201129200000, 483], [1201215600000, 452], [1201302000000, 270], [1201388400000, 222], [1201474800000, 439], [1201561200000, 559], [1201647600000, 521], [1201734000000, 477], [1201820400000, 442], [1201906800000, 252], [1201993200000, 236], [1202079600000, 525], [1202166000000, 477], [1202252400000, 386], [1202338800000, 409], [1202425200000, 408], [1202511600000, 237], [1202598000000, 193], [1202684400000, 482], [1202770800000, 414], [1202857200000, 393], [1202943600000, 353], [1203030000000, 364], [1203116400000, 215], [1203202800000, 214], [1203289200000, 356], [1203375600000, 399], [1203462000000, 334], [1203548400000, 348], [1203634800000, 243], [1203721200000, 126], [1203807600000, 157], [1203894000000, 288]];

    // first correct the timestamps - they are recorded as the daily
    // midnights in UTC+0100, but Flot always displays dates in UTC
    // so we have to add one hour to hit the midnights in the plot
    for (var i = 0; i < d2.length; ++i) { d2[i][0] += 60 * 60 * 1000; }

    // helper for returning the weekends in a period
    function weekendAreas(axes) {
        var markings = [];
        var d = new Date(axes.xaxis.min);
        // go to the first Saturday
        d.setUTCDate(d.getUTCDate() - ((d.getUTCDay() + 1) % 7))
        d.setUTCSeconds(0);
        d.setUTCMinutes(0);
        d.setUTCHours(0);
        var i = d.getTime();
        do {
            // when we don't set yaxis, the rectangle automatically
            // extends to infinity upwards and downwards
            markings.push({ xaxis: { from: i, to: i + 2 * 24 * 60 * 60 * 1000 } });
            i += 7 * 24 * 60 * 60 * 1000;
        } while (i < axes.xaxis.max);

        return markings;
    }
    
    var opts2 = {
        xaxis: {mode: "time", tickLength: 5},
        selection: {mode: "x"},
        grid: {markings: weekendAreas, hoverable: true},
		series: {
			lines: {show: true},
			points: {show: true},
			color: '#ffd200'
		},
    };
    
	var dbViews = $.plot($("#dashchart-views"), [d2], opts2);
	
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
				title: 'Buy 1 Get 1 50%',
				start: new Date(y, 6, d+10)
			},
			{
				title: 'Ala Moana Grand Opening!',
				start: new Date(y, 6, 20),
				allDay: true	
			},
			{
				title: '10% Tuesdays',
				start: new Date(y, 6, 3),
				allDay: true
			},
			{
				title: '10% Tuesdays',
				start: new Date(y, 6, 10),
				allDay: true
			},
			{
				title: '10% Tuesdays',
				start: new Date(y, 6, 17),
				allDay: true
			},
			{
				title: '10% Tuesdays',
				start: new Date(y, 6, 24),
				allDay: true
			},
			{
				title: '10% Tuesdays',
				start: new Date(y, 6, 31),
				allDay: true
			},
			{
				title: '10% Tuesdays',
				start: new Date(y, 7, 7),
				allDay: true
			},
			
			{
				title: 'Tees - 4 for $25.00',
				start: new Date(y, 6, 28),
				allDay: true	
			},
			
		]
	});
});

	
