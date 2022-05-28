/*! PS-Maps - v2.9.5
 * https://n3rds.work/piestingtal-source-project/ps-gmaps/
 * Copyright (c) 2018-2022; * Licensed GPLv2+ */
/*global window:false */
/*global document:false */
/*global _agm:false */
/*global navigator:false */

jQuery(function () {

	jQuery( document ).on(
		"agm_google_maps-user-map_initialized",
		function(e, map, data) {
			var has_scroll = false;

			try {
				has_scroll = data.disable_scroll ? data.disable_scroll : false;
			} catch ( ex ) {
				has_scroll = false;
			}
			has_scroll = ! has_scroll;

			map.setOptions({
				scrollwheel: has_scroll
			});
		}
	);

});
