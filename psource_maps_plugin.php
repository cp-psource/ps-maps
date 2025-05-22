<?php
/**
 * Plugin Name: Google Maps Pro
 * Plugin URI:  https://cp-psource.github.io/ps-maps/
 * Description: Easily embed, customize, and use Google maps on your WordPress site - in posts, pages or as an easy to use widget, display local images and let your site visitors get directions in seconds.
 * Version:     3.1.2
 * Text Domain: agm_google_maps
 * Domain Path: languages
 * Author:      PSOURCE
 * Author URI:  https://github.com/cp-psource
 *
 * @package  AgmMaps
 */

/*
Copyright 2011-2024 PSOURCE (https://github.com/cp-psource)
Author - DerN3rd (PSOURCE)
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License (Version 2 - GPLv2) as published by
the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

/**
 * @@@@@@@@@@@@@@@@@ PS UPDATER 1.3 @@@@@@@@@@@
 **/

 require 'psource/psource-plugin-update/plugin-update-checker.php';
 use YahnisElsts\PluginUpdateChecker\v5\PucFactory;
 
 $myUpdateChecker = PucFactory::buildUpdateChecker(
	 'https://github.com/cp-psource/ps-maps',
	 __FILE__,
	 'ps-maps'
 );
 
 //Set the branch that contains the stable release.
 $myUpdateChecker->setBranch('master');

/**
 * @@@@@@@@@@@@@@@@@ ENDE PS UPDATER 1.3 @@@@@@@@@@@
 **/

// Define plugin constants.
define( 'AGM_PLUGIN', basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ) );
define( 'AGM_PLUGIN_DIRNAME', basename( dirname( __FILE__ ) ) );
define( 'AGM_BASE_DIR', trailingslashit( dirname( __FILE__ ) ) );
define( 'AGM_INC_DIR', AGM_BASE_DIR . 'inc/' );
define( 'AGM_ADDON_DIR', AGM_BASE_DIR . 'inc/addons/' );
define( 'AGM_VIEWS_DIR', AGM_BASE_DIR . 'views/' );
define( 'AGM_IMG_DIR', AGM_BASE_DIR . 'img/' );
define( 'AGM_PLUGIN_URL', trailingslashit( plugins_url( '', AGM_PLUGIN ) ) );
define( 'AGM_LANG', 'agm_google_maps' );

if ( is_multisite() ) {
	load_muplugin_textdomain( AGM_LANG, false, AGM_PLUGIN_DIRNAME . '/languages/' );
} else {
	load_plugin_textdomain( AGM_LANG, false, AGM_PLUGIN_DIRNAME . '/languages/' );
}

// Include function library.
if ( file_exists( AGM_INC_DIR . 'external/wpmu-lib/core.php' ) ) {
	require_once AGM_INC_DIR . 'external/wpmu-lib/core.php';
}

// Load required classes.
require_once AGM_INC_DIR . 'class-agm-post-indexer.php';
require_once AGM_INC_DIR . 'class-agm-map-model.php';
require_once AGM_INC_DIR . 'class-agm-maps-widget.php';
require_once AGM_INC_DIR . 'class-agm-plugin-installer.php';
require_once AGM_INC_DIR . 'class-agm-addon-base.php';

// Check if DB needs to be updated.
AgmPluginInstaller::check();

add_action(
	'widgets_init',
	'agm_widgets_init'
);

function agm_widgets_init() {
	register_widget( 'AgmMapsWidget' );
}

if ( is_admin() ) {
	require_once AGM_INC_DIR . 'class-agm-admin-help.php';
} else {
	require_once AGM_INC_DIR . 'class-agm-marker-replacer.php';
}
require_once AGM_INC_DIR . 'class-agm-plugins-handler.php';
AgmPluginsHandler::init();

require_once AGM_INC_DIR . 'class-agm-dependencies.php';

if ( is_admin() ) {
	require_once AGM_INC_DIR . 'class-agm-admin-form-renderer.php';
	require_once AGM_INC_DIR . 'class-agm-admin-maps.php';
	AgmAdminMaps::serve();

} else {
	require_once AGM_INC_DIR . 'class-agm-user-maps.php';
	AgmUserMaps::serve();

	if ( class_exists( 'AgmDependencies' ) ) {
		AgmDependencies::serve();
	}
}

add_filter('mce_external_plugins', function($plugins) {
    $plugins['ps_maps_button'] = plugins_url('js/admin/editor.js', __FILE__);
    return $plugins;
});

