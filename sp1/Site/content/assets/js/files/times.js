
var Times = (function() {
	return {
		"getTimesForSlider": function(startTime, endTime) {
	        if (startTime && endTime) {
	    	    if (startTime == '4:00AM' && endTime == '4:00AM') {
	                // Special case: open 24 hours 
	                startTime = 240;
	                endTime = 1440 + 240;
	            } else {
	                //Oneliners selflessly donated by Federico
	                startTime = (startTime.indexOf('PM') == -1 ? 0 : 720) + (parseInt(startTime.substr(0,2)) % 12) * 60 + parseInt(startTime.substr(3,2));
	                endTime = (endTime.indexOf('PM') == -1 ? 0 : 720) + (parseInt(endTime.substr(0,2)) % 12) * 60 + parseInt(endTime.substr(3,2));
	            }
	            
	            if (endTime < startTime) {
	                endTime += 1440; // special case for late night merchants (perhaps they open at 10:00am and close at 3:00am)
	            }
	
	            return {
	            	"startTime": startTime,
	            	"endTime": endTime
	            };
	        } else {
	        	return null;
	        }
		}
	};
})();
