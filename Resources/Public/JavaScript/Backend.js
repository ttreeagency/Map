if (typeof document.addEventListener === 'function') {
	var initializeMaps = function() {
		$(".map-container").each(function () {
			var functionName = "initializeMap" + $(this).data("identifier"),
				fn = window[functionName];

			if (typeof fn === "function") {
				fn();
			}
		});
	};

	document.addEventListener('Neos.PageLoaded', function (e) {
		initializeMaps();
	}, false);
}