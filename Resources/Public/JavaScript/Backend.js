if (typeof document.addEventListener === 'function') {
	document.addEventListener('Neos.PageLoaded', function (event) {
		$(".map-container").each(function () {
			var functionName = "initializeMap" + $(this).data("identifier"),
				fn = window[functionName];

			if (typeof fn === "function") {
				fn();
			}
		});
	}, false);
}