TtreeMap = {
	initializeSingleMap: function(map, configuration) {
		var mapConfiguration = configuration.map,
			mapOptions = mapConfiguration.options,
			myLatlng = new google.maps.LatLng(mapConfiguration.longitude, mapConfiguration.latitude);

		mapOptions.center = myLatlng;
		mapOptions.mapTypeControlOptions = {
			mapTypeIds: [google.maps.MapTypeId.ROADMAP, "styles"]
		};

		var map = new google.maps.Map(map, mapOptions),
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
			var el = $(this),
				configuration = JSON.parse(decodeURIComponent(el.data('ttreeMapConfiguration')));

			TtreeMap.initializeSingleMap(el[0], configuration);
		});

	}
};

$(document).ready(function ($) {
	google.maps.event.addDomListener(window, 'load', TtreeMap.initialize);
});