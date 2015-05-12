if (typeof document.addEventListener === 'function') {
	document.addEventListener('Neos.PageLoaded', function (e) {
		TtreeMap.initialize();
	}, false);
}