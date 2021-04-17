<?php
/*
Plugin Name: Zusätzliche Verhaltensweisen
Description: Zeigt zusätzliches Standardverhalten der Karte als globale Kartenoptionen an:<br>- Klicke auf das Element in der Markierungsliste, um das Detail-Popup zu öffnen<br>- Klicke auf den Wegbeschreibungslink, um zum Wegbeschreibungsformular zu gelangen
Plugin URI:  https://n3rds.work/piestingtal-source-project/ps-gmaps/
Version:     1.0
Author:      DerN3rd (WMS N@W)
*/

class Agm_Mab_AdditionalBehaviors {

	private function __construct() {}

	public static function serve() {
		$me = new Agm_Mab_AdditionalBehaviors();
		$me->_add_hooks();
	}

	private function _add_hooks() {
		add_action(
			'agm-user-scripts',
			array( $this, 'load_scripts' )
		);
		add_filter(
			'agm_google_maps-javascript-data_object-user',
			array( $this, 'add_behaviors_data' )
		);

		add_action(
			'agm_google_maps-options-plugins_options',
			array( $this, 'register_settings' )
		);
	}

	public function load_scripts() {
		lib3()->ui->add( AGM_PLUGIN_URL . 'js/user/additional-behaviors.min.js', 'front' );
	}

	public function add_behaviors_data( $data ) {
		$data['additional_behaviors'] = $this->_get_options();
		return $data;
	}

	public function register_settings() {
		add_settings_section(
			'agm_google_maps_mab',
			__( 'Zusätzliche Verhaltensweisen', AGM_LANG ),
			array( $this, 'create_section' ),
			'agm_google_maps_options_page'
		);
		add_settings_field(
			'agm_google_maps_list_kmls',
			__( 'Verhalten', AGM_LANG ),
			array( $this, 'create_behaviors_box' ),
			'agm_google_maps_options_page',
			'agm_google_maps_mab'
		);
	}

	public function create_section() {
		?>
		<p>
			<em>
			<?php _e( 'Hier kannst Du zusätzliche globale Einstellungen für Karten definieren.', AGM_LANG ); ?>
			</em>
		</p>
		<?php
	}

	public function create_behaviors_box() {
		$opts = $this->_get_options();
		?>
		<label for="agm_google_maps-mab-marker_click_popup">
			<input type="hidden"
				value=""
				name="agm_google_maps[mab][marker_click_popup]" />
			<input type="checkbox"
				value="1"
				id="agm_google_maps-mab-marker_click_popup"
				name="agm_google_maps[mab][marker_click_popup]"
				<?php checked( $opts['marker_click_popup'], true ); ?> />
			&nbsp
			<?php _e( 'Klicke auf das Element in der Markierungsliste, um das Detail-Popup zu öffnen', AGM_LANG ); ?>
		</label>
		<br />

		<label for="agm_google_maps-mab-directions_click_scroll">
			<input type="hidden"
				value=""
				name="agm_google_maps[mab][directions_click_scroll]" />
			<input type="checkbox"
				value="1"
				id="agm_google_maps-mab-directions_click_scroll"
				name="agm_google_maps[mab][directions_click_scroll]"
				<?php checked( $opts['directions_click_scroll'], true ); ?> />
			&nbsp
			<?php _e( 'Klicke auf den Wegbeschreibungslink, um zum Wegbeschreibungsformular zu gelangen', AGM_LANG ); ?>
		</label>
		<br />
		<?php
	}

	private function _get_options() {
		$opts = apply_filters( 'agm_google_maps-options', get_option( 'agm_google_maps' ) );
		$opts = isset( $opts['mab'] ) && $opts['mab'] ? $opts['mab'] : array();
		return wp_parse_args(
			$opts,
			array(
				'directions_click_scroll' => false,
				'marker_click_popup' => false,
			)
		);
	}

};

Agm_Mab_AdditionalBehaviors::serve();