/*! PS-Maps - v2.9.5
 * https://n3rds.work/piestingtal-source-project/ps-gmaps/
 * Copyright (c) 2018-2022; * Licensed GPLv2+ */
/*global window:false */
/*global document:false */
/*global _agm:false */
/*global navigator:false */

jQuery(function () {

jQuery(document).on("agm_google_maps-user-map_initialized", function (e, map, data, markers) {
	if ( ! markers || ! markers.length ) { return; }
	var markerCluster = new window.MarkerClusterer(
		map,
		markers, {
			"zoomOnClick": false, "gridSize": 30,
            imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'
		}
	);

	window.google.maps.event.addListener(markerCluster, "click", function (c) {
		var clustered = c.getMarkers();
		var contents = '';

		jQuery.each(clustered, function () {
			if ( '_agmInfo' in this ) {
				contents += this._agmInfo.getContent();
				contents += "<hr style='clear:both' />";
			}
		});

		var info = new window.google.maps.InfoWindow({
			content: contents
		});

		info.open(map, clustered[0]);
	});
});

});
