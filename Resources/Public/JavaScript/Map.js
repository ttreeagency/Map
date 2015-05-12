TtreeMap = {
	initializeSingleMap: function(target, configuration) {
		var mapConfiguration = configuration.map,
			mapOptions = mapConfiguration.options,
			myLatlng = new google.maps.LatLng(mapConfiguration.longitude, mapConfiguration.latitude);

		mapOptions.center = myLatlng;
		mapOptions.mapTypeControlOptions = {
			mapTypeIds: [google.maps.MapTypeId.ROADMAP, "styles"]
		};

		var map = new google.maps.Map(document.getElementById(target), mapOptions),
			mapType = new google.maps.StyledMapType(configuration.styles.default, {});

		map.mapTypes.set("styles", mapType);
		map.setMapTypeId("styles");

		new google.maps.Marker({
			position: myLatlng,
			map: map
		});
	},
	initialize: function() {
		$(".ttree-map-configuration-js").each(function() {
			var element = $(this),
				configuration = JSON.parse(element.html()),
				target = element.data('map-identifier');

			console.log(configuration, target);
			TtreeMap.initializeSingleMap(target, configuration);
		});

	}
};

$(document).ready(function ($) {
	google.maps.event.addDomListener(window, 'load', TtreeMap.initialize);
});