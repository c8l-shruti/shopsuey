
$(function() {
	Places = (function() {
		var service = new google.maps.places.AutocompleteService();
	
		return {
			"getPlaces": function(text, callback) {
		        if (text) {
		        	service.getPlacePredictions({ input: text, types: ["(cities)"] }, callback);
		        } else {
		        	return callback([], 'OK');
		        }
			}
		};
	})();
});
