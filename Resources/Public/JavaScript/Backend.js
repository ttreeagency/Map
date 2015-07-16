if (typeof document.addEventListener === 'function') {
	document.addEventListener('Neos.PageLoaded', function (event) {
		TtreeMap.initialize();
	}, false);
	document.addEventListener('Neos.NodeCreated', function (event) {
		TtreeMap.initialize();
	}, false);
}