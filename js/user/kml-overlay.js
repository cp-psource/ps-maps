/*! PS-Maps - v2.9.5
 * https://n3rds.work/piestingtal-source-project/ps-gmaps/
 * Copyright (c) 2018-2022; * Licensed GPLv2+ */
/*global window:false */
/*global document:false */
/*global _agm:false */
/*global navigator:false */

jQuery(function () {

// Load KML overlay
jQuery(document).on("agm_google_maps-user-map_initialized", function (e, map, data) {
	var url = '';

	try { url = data.kml_url ? data.kml_url : ''; }
	catch (ex) { url = ''; }

	if ( ! url ) { return false; }

	var kml = new window.google.maps.KmlLayer(url);
	jQuery(document).data("kml_overlay", kml);
	kml.setMap(map);
});

});
