$(function() {
	$('#calendar').fullCalendar({
		header: {
			left: 'prev,next',
			center: 'title',
			right: 'month,agendaWeek,agendaDay'
		},
		editable: false,
		events: EVENTS,
		eventClick: function(event) {
			if (event.url) {
				window.location.href = event.url;
				return false;
			}
		}
	});
});
