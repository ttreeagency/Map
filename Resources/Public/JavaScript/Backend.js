if (typeof document.addEventListener === 'function') {
	document.addEventListener('Neos.PageLoaded', function (e) {
		TtreeMap.initialize();
	}, false);
	document.addEventListener('Neos.NodeCreated', function (e) {
		TtreeMap.initialize();
	}, false);
}