/*! Google Maps Pro - v2.9.5
 * https://n3rds.work/piestingtal-source-project/ps-gmaps/
 * Copyright (c) 2018; * Licensed GPLv2+ */
/*global window:false */
/*global document:false */
/*global _agm:false */
/*global navigator:false */

jQuery(document).on("agm_google_maps-user-map_initialized", function (e, map, data) {
	if ( ! data.is_responsive ) {
		return false; // Short out
	}

	var el = jQuery(map.getDiv()),
		container = el.parents(".agm_google_maps"),
		parent = container.parent(),
		center = map.getCenter(),
		total_width = parent.width(),
		map_width = el.width()
	;

	jQuery(window).resize(function () {
		var width = parent.width();

		if ( data.responsive_respect_width ) {
			width = (width / total_width) * map_width;
		}

		container.width(width);
		el.width(width);
		window.google.maps.event.trigger(map, 'resize');
		map.setCenter(center);
	}).trigger('resize');
});