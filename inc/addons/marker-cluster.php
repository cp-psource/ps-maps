<?php
/*
Plugin Name: Marker-Cluster
Description: Bereinigt Karten, indem Markierungen in der Nähe in Clustern gruppiert werden. Dies wirkt sich bei Aktivierung automatisch auf alle Karten aus.
Plugin URI:  https://n3rds.work/piestingtal-source-project/ps-gmaps/
Version:     1.0
Author:      DerN3rd (WMS N@W)
*/

class Agm_Mc_UserPages {

	private function __construct() {}

	public static function serve() {
		$me = new Agm_Mc_UserPages();
		$me->_add_hooks();
	}

	private function _add_hooks() {
		add_action(
			'agm-user-scripts',
			array( $this, 'load_scripts' )
		);
	}

	public function load_scripts() {
		lib3()->ui->add( AGM_PLUGIN_URL . 'js/external/markerclusterer_packed.min.js', 'front' );
		lib3()->ui->add( AGM_PLUGIN_URL . 'js/user/marker-cluster.min.js', 'front' );
	}
};

if ( ! is_admin() ) {
	Agm_Mc_UserPages::serve();
}