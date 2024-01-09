<?php
/**
 * Plugin Name: PS Google Maps
 * Plugin URI:  https://n3rds.work/piestingtal_source/ps-google-maps-plugin/
 * Description: Einfaches Einbetten, Anpassen und Verwenden von Google Maps auf Deiner WordPress-Seite - in Posts, Seiten oder als benutzerfreundliches Widget kannst Du lokale Bilder anzeigen und Deinen Seiten-Besuchern in Sekundenschnelle eine Interaktive Googlemap mit einer Unzahl an Optionen und Möglichkeiten geben.
 * Version:     3.1.0
 * Requires at least: 4.6
 * Text Domain: psmaps
 * Author:      WMS N@W
 * Author URI:  https://n3rds.work
 * 
 *
 * @package  AgmMaps
 */

/*
Copyright 2020-2021 WMS N@W (https://n3rds.work)
Author - DerN3rd
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
// Check that the file is not accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Es tut uns leid, aber Du kannst nicht direkt auf diese Datei zugreifen.' );
}

require 'psource/psource-plugin-update/psource-plugin-updater.php';
use Psource\PluginUpdateChecker\v5\PucFactory;
$MyUpdateChecker = PucFactory::buildUpdateChecker(
	'https://n3rds.work//wp-update-server/?action=get_metadata&slug=ps-maps', 
	__FILE__, 
	'ps-maps' 
);

// Define plugin constants.
define( 'AGM_PLUGIN', basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ) );
define( 'AGM_PLUGIN_DIRNAME', basename( dirname( __FILE__ ) ) );
define( 'AGM_BASE_DIR', trailingslashit( dirname( __FILE__ ) ) );
define( 'AGM_INC_DIR', AGM_BASE_DIR . 'inc/' );
define( 'AGM_ADDON_DIR', AGM_BASE_DIR . 'inc/addons/' );
define( 'AGM_VIEWS_DIR', AGM_BASE_DIR . 'views/' );
define( 'AGM_IMG_DIR', AGM_BASE_DIR . 'img/' );
define( 'AGM_PLUGIN_URL', trailingslashit( plugins_url( '', AGM_PLUGIN ) ) );
define( 'AGM_LANG', 'psmaps' );

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

require_once AGM_INC_DIR . 'class-agm-gdpr.php';
AgmGdpr::serve();


