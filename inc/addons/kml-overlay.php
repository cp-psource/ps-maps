<?php
/*
Plugin Name: KML Overlay
Description: Ermöglicht das Überlagern einer KML-URL mit Deiner Karte (z.B. von Flickr: <code>http://api.flickr.com/services/feeds/geo/Zrenjanin?tags=sunflower&amp;lang=en-us&amp;format=kml_nl</code>). Fügt Shortcodes außerdem Unterstützung für das neue Attribut <code>kml_url</code> hinzu. Du kannst das neue Attribut entweder eigenständig oder in Kombination mit vorhandenen Karten verwenden.
Example:     [map id="1" kml_url="http://api.flickr.com/services/feeds/geo/Australia"]
Plugin URI:  https://n3rds.work/piestingtal-source-project/ps-gmaps/
Version:     1.0
Author:      DerN3rd (PSOURCE)
*/

class Agm_Kml_AdminPages {

	private function __construct() {}

	public static function serve() {
		$me = new Agm_Kml_AdminPages();
		$me->_add_hooks();
	}

	private function _add_hooks() {
		// Basic KML overlay funcitonality
		add_action(
			'agm-admin-scripts',
			array( $this, 'load_scripts' )
		);
		add_filter(
			'agm-save-options',
			array( $this, 'prepare_for_save' ),
			10, 2
		);
		add_filter(
			'agm-load-options',
			array( $this, 'prepare_for_load' ),
			10, 2
		);
	}

	public function load_scripts() {
		lib3()->ui->add( AGM_PLUGIN_URL . 'js/admin/kml-overlay.min.js' );
	}

	public function prepare_for_save( $options, $raw ) {
		if ( isset( $raw['kml_url'] ) ) {
			$options['kml_url'] = $raw['kml_url'];
		}
		return $options;
	}

	public function prepare_for_load( $options, $raw ) {
		if ( isset( $raw['kml_url'] ) ) {
			$options['kml_url'] = $raw['kml_url'];
		}
		return $options;
	}
}

class Agm_Kml_UserPages {

	private function __construct() {
	}

	public static function serve() {
		$me = new Agm_Kml_UserPages();
		$me->_add_hooks();
	}

	private function _add_hooks() {
		// Basic KML overlay funcitonality
		add_action(
			'agm-user-scripts',
			array( $this, 'load_scripts' )
		);
		add_filter(
			'agm-load-options',
			array( $this, 'prepare_for_load' ),
			10, 2
		);

		// KML in shortcode attribute
		add_filter(
			'agm-shortcode-defaults',
			array( $this, 'attributes_defaults' )
		);
		add_filter(
			'agm-shortcode-process',
			array( $this, 'attributes_process' )
		);
		add_filter(
			'agm-shortcode-overrides',
			array( $this, 'overrides_process' ),
			10, 2
		);
		add_filter(
			'agm-create-tag',
			array( $this, 'create_map_tag_from_shortcode' ),
			10, 2
		);
	}

	public function load_scripts() {
		lib3()->ui->add( AGM_PLUGIN_URL . 'js/user/kml-overlay.min.js', 'front' );
	}

	public function prepare_for_load( $options, $raw ) {
		if ( isset( $raw['kml_url'] ) ) {
			$options['kml_url'] = $raw['kml_url'];
		}
		return $options;
	}

	public function attributes_defaults( $defaults ) {
		$defaults['kml_url'] = false;
		return $defaults;
	}

	public function overrides_process( $overrides, $atts ) {
		if ( @$atts['kml_url'] ) {
			$overrides['kml_url'] = $atts['kml_url'];
		}
		return $overrides;
	}

	public function attributes_process( $atts ) {
		if ( ! $atts['id'] && ! $atts['query'] && $atts['kml_url'] ) {
			$atts['id'] = md5( $atts['kml_url'] );
		}
		return $atts;
	}

	public function create_map_tag_from_shortcode( $map, $overrides ) {
		if ( $map ) {
			return $map;
		}
		if ( ! @$overrides['kml_url'] ) {
			return $map;
		}

		$model = new AgmMapModel();
		$defaults = $model->get_map_defaults();
		$map = array(
			'id' => md5( $overrides['kml_url'] ),
			'defaults' => $defaults,
			'markers' => array(),
			'show_map' => 1,
			'show_markers' => 0,
			'show_images' => 0,
			'zoom' => $defaults['zoom'],
		);
		return $map;
	}
}


if ( is_admin() ) {
	Agm_Kml_AdminPages::serve();
} else {
	Agm_Kml_UserPages::serve();
}