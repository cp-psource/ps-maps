/*! PS-Maps - v2.9.5
 * https://n3rds.work/piestingtal-source-project/ps-gmaps/
 * Copyright (c) 2018-2024; * Licensed GPLv2+ */
/*! Google Maps - v2.9.07
 * https://n3rds.work/piestingtal-source-project/ps-gmaps/
 * Copyright (c) 2015-2024; * Licensed GPLv2+ */
/*global window:false */
/*global document:false */
/*global _agm:false */
/*global jQuery:false */

/**
 * Asynchronously load Google Maps API.
 */

/**
 * Global API loaded flag.
 */
window._agmMapIsLoaded = false;

/**
 * Callback - triggers loaded flag setting.
 */
function agmInitialize() {
    window._agmMapIsLoaded = true;
    if (undefined !== window.google.maps.Map.prototype._agm_get_markers) {
        return true;
    }

    window.google.maps.Map.prototype._agm_markers = [];
    window.google.maps.Map.prototype._agm_get_markers = function() { return this._agm_markers; };
    window.google.maps.Map.prototype._agm_clear_markers = function() { this._agm_markers = []; };
    window.google.maps.Map.prototype._agm_add_marker = function(mrk) { this._agm_markers.push(mrk); };
    window.google.maps.Map.prototype._agm_remove_marker = function(idx) { this._agm_markers.splice(idx, 1); };
}

/**
 * Handles the actual loading of Google Maps API.
 */
function loadGoogleMaps() {
    if (typeof window.google === 'object' && typeof window.google.maps === 'object') {
        // We're loaded and ready - albeit from a different source.
        return agmInitialize();
    }

    var protocol = '',
        language = '',
        src = '',
        script = document.createElement("script"),
        libs = _agm.libraries.join(","),
        api_key = ((_agm || {}).maps_api_key) || false;

    try { protocol = document.location.protocol; } catch (ex) { protocol = 'http:'; }

    if (window._agmLanguage !== undefined) {
        language = '&language=' + window._agmLanguage;
    }

    if (api_key) {
        api_key = "&key=" + api_key;
    }

    src = "//maps.google.com/maps/api/js?v=3" + api_key + "&libraries=" + libs +
        "&sensor=false" + language + "&callback=agmInitialize";

    script.type = "text/javascript";
    script.src = protocol + src;

    document.body.appendChild(script);
}

jQuery(document).ready(function($) {
    $(window).on('load', loadGoogleMaps);
});
