TtreeMap = {
    initializeSingleMap: function(map, configuration) {
        var mapConfiguration = configuration.map,
            mapOptions = mapConfiguration.options,
            myLatlng = new google.maps.LatLng(mapConfiguration.latitude, mapConfiguration.longitude);

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
        var entries = document.getElementsByClassName("ttree-map-configuration-js");
        for(var index = 0; index < entries.length; index++) {
            var entry = entries[index];
            var configuration = JSON.parse(decodeURIComponent(entry.getAttribute('data-ttree-map-configuration')));
            TtreeMap.initializeSingleMap(entry, configuration);
        }
    }
};
