/*! PS-Maps - v2.9.5
 * https://n3rds.work/piestingtal-source-project/ps-gmaps/
 * Copyright (c) 2018-2022; * Licensed GPLv2+ */
/*global window:false */
/*global document:false */
/*global _agm:false */
/*global navigator:false */

jQuery(document).on("agm_google_maps-user-map_initialized", function (e, map, data, markers) {
	if ( ! data.hide_map_markers ) { return false; }

	jQuery.each(markers, function () {
		this.setVisible(false);
	});
});

