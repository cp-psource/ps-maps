<?php
/*
Plugin Name: Wo bin ich?
Description: Fügt der Karte in unterstützenden Browsern automatisch oder über das Shortcode-Attribut die Standortmarkierung des Besuchers hinzu.
Plugin URI:  https://n3rds.work/piestingtal-source-project/ps-gmaps/
Version:     1.1
Author:      DerN3rd (WMS N@W)
*/

class Agm_Wmi_AdminPages {

	private function __construct() {
		$this->_add_hooks();
	}

	public static function serve() {
		$me = new Agm_Wmi_AdminPages();
	}

	private function _add_hooks() {
		add_action(
			'agm_google_maps-options-plugins_options',
			array( $this, 'register_settings' )
		);
	}

	public function register_settings() {
		add_settings_section(
			'agm_google_wmi_fields',
			__( 'Wo bin ich?', 'psmaps' ),
			'__return_false',
			'agm_google_maps_options_page'
		);
		add_settings_field(
			'agm_google_maps_wmi_auto',
			__( 'Verhalten', 'psmaps' ),
			array( $this, 'create_auto_add_box' ),
			'agm_google_maps_options_page',
			'agm_google_wmi_fields'
		);
		add_settings_field(
			'agm_google_maps_wmi_marker',
			__( 'Aussehen', 'psmaps' ),
			array( $this, 'create_marker_options_box' ),
			'agm_google_maps_options_page',
			'agm_google_wmi_fields'
		);
	}

	public function create_auto_add_box() {
		$shortcode_only = $this->_get_options( 'shortcode_only' );
		$no = $shortcode_only ? 'checked="checked"' : false;
		$yes = $shortcode_only ? false : 'checked="checked"';

		echo '<input type="radio" id="agm-wmi-auto-yes" name="agm_google_maps[wmi-shortcode_only]" value="" ' . $yes . ' />' .
			'&nbsp' .
			'<label for="agm-wmi-auto-yes">' . __( 'Ich möchte Besucherstandorte automatisch auf allen meinen Karten anzeigen', 'psmaps' ) . '</label>' .
			'<div><small>' . __( 'Der Standort des Besuchers wird automatisch allen Karten hinzugefügt', 'psmaps' ) . '</small></div>' .
		'<br />';
		echo '<input type="radio" id="agm-wmi-auto-no" name="agm_google_maps[wmi-shortcode_only]" value="1" ' . $no . ' />' .
			'&nbsp' .
			'<label for="agm-wmi-auto-no">' . __( 'Ich möchte mithilfe eines Shortcode-Attributs angeben, auf welchen Karten der Besucherstandort angezeigt wird', 'psmaps' ) . '</label>' .
			'<div><small>' . __( 'Du kannst den Besucherstandort auf Deinen Karten anzeigen, indem Du Deinen Shortcodes <code>visitor_location=&quot;yes&quot;</code> hinzufügst', 'psmaps' ) . '</small></div>' .
		'<br />';

		$center = $this->_get_options( 'auto_center' );
		$center = $center ? 'checked="checked"' : '';
		echo '<br />';
		echo '<input type="hidden" name="agm_google_maps[wmi-auto_center]" value="" />' .
			'<input type="checkbox" id="agm-wmi-auto_center" name="agm_google_maps[wmi-auto_center]" value="1" ' . $center . ' />' .
			'&nbsp' .
			'<label for="agm-wmi-auto_center">' . __( 'Karte automatisch auf Besucherort zentrieren', 'psmaps' ) . '</label>' .
		'<br />';

		$marker = $this->_get_options( 'marker' );
		$marker = $marker ? 'checked="checked"' : '';
		echo '<input type="hidden" name="agm_google_maps[wmi-marker]" value="" />' .
			'<input type="checkbox" id="agm-wmi-marker" name="agm_google_maps[wmi-marker]" value="1" ' . $marker . ' />' .
			'&nbsp' .
			'<label for="agm-wmi-marker">' . __( 'Füge der Markierungsliste für meine Karte automatisch den Besucherort hinzu', 'psmaps' ) . '</label>' .
			'<div><small>' . __( 'Standardmäßig wird nur der Besucherstandort zur Karte hinzugefügt. Aktiviere diese Option, wenn Du sie auch in die Markierungsliste aufnehmen möchtest.', 'psmaps' ) . '</small></div>' .
		'<br />';
	}

	public function create_marker_options_box() {
		$label = $this->_get_options( 'label' );
		$label = $label ? $label : __( 'Das bist du', 'psmaps' );
		echo '<label for="agm-wmi-label">' . __( 'Besuchermarkierungsetikett', 'psmaps' ) . '</label>' .
			'&nbsp;' .
			'<input type="text" class="widefat" id="agm-wmi-label" name="agm_google_maps[wmi-label]" value="' . esc_attr( $label ) . '" />' .
		'<br />';

		$icon = $this->_get_options( 'icon' );
		echo '<label for="agm-wmi-icon">' . __( 'Besuchermarkierungssymbol', 'psmaps' ) . '</label>' .
			$this->_create_icons_box() .
			'<input type="text" class="widefat" id="agm-wmi-icon" name="agm_google_maps[wmi-icon]" value="' . esc_attr( $icon ) . '" />' .
			'<div><small>' . __( 'Lasse das Feld leer, um das Standardsymbol zu verwenden', 'psmaps' ) . '</small></div>' .
		'<br />';
	}

	private function _create_icons_box() {
		$out = '';
		$icons = AgmAdminMaps::list_icons();
		foreach ( $icons as $icon ) {
			$out .= "<a href='#select'><img src='{$icon}' class='marker-icon-32' /></a> ";
		}
		$out = '<div id="agm_google_maps-wmi-preset_icons">' . $out . '</div>';
		ob_start();
		?>
		<script type="text/javascript">
		jQuery(function () {
			jQuery( '#agm_google_maps-wmi-preset_icons a' ).on("click", function () {
				jQuery( '#agm-wmi-icon' ).val( jQuery(this).find('img').attr('src') );
				return false;
			});
		});
		</script>
		<?php
		$out .= ob_get_clean();
		return $out;
	}

	private function _get_options( $key = 'shortcode_only' ) {
		$opts = apply_filters(
			'agm_google_maps-options-wmi',
			get_option( 'agm_google_maps' )
		);
		return @$opts['wmi-' . $key];
	}
}

class Agm_Wmi_UserPages {

	private function __construct() {
		$this->_add_hooks();
	}

	public static function serve() {
		$me = new Agm_Wmi_UserPages();
	}

	private function _add_hooks() {
		add_action(
			'agm-user-scripts',
			array( $this, 'load_scripts' )
		);
		add_action(
			'agm_google_maps-add_javascript_data',
			array( $this, 'add_wmi_data' )
		);

		// Shortcode attribute
		add_filter(
			'agm-shortcode-defaults',
			array( $this, 'attributes_defaults' )
		);
		add_filter(
			'agm-shortcode-overrides',
			array( $this, 'overrides_process' ),
			10, 2
		);
	}

	public function attributes_defaults( $defaults ) {
		$defaults['visitor_location'] = false;
		return $defaults;
	}

	public function overrides_process( $overrides, $atts ) {
		if ( @$atts['visitor_location'] ) {
			$overrides['visitor_location'] = $atts['visitor_location'];
		}
		return $overrides;
	}

	public function load_scripts() {
		lib3()->ui->add( AGM_PLUGIN_URL . 'js/user/where-am-i.min.js', 'front' );
	}

	public function add_wmi_data() {
		$label = $this->_get_options( 'label' );
		$label = $label ? $label : __( 'Das bist du', 'psmaps' );
		printf(
			'<script type="text/javascript">if ( window._agmWmi === undefined) _agmWmi = {
				"add_marker": %d,
				"shortcode_only": %d,
				"auto_center": %d,
				"marker_label": "%s",
				"icon": "%s"
			};</script>',
			(int)$this->_get_options( 'marker' ),
			(int)$this->_get_options( 'shortcode_only' ),
			(int)$this->_get_options( 'auto_center' ),
			esc_js( $label ),
			esc_js( $this->_get_options( 'icon' ) )
		);
	}

	private function _get_options( $key = 'shortcode_only' ) {
		$opts = apply_filters( 'agm_google_maps-options-wmi', get_option( 'agm_google_maps' ) );
		return @$opts['wmi-' . $key];
	}
}

if ( is_admin() ) {
	Agm_Wmi_AdminPages::serve();
} else {
	Agm_Wmi_UserPages::serve();
}