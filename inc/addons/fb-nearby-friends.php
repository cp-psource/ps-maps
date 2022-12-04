<?php
/*
Plugin Name: Facebook Freunde in der Nähe 
Description: Zeigt eine Liste der Facebook-Freunde in der Nähe an.
Plugin URI:  https://n3rds.work/piestingtal-source-project/ps-gmaps/
Version:     1.0
Author:      DerN3rd (WMS N@W)
*/

class Agm_Fbnf_AdminPages {

	private $_help;

	private function __construct() {
		$this->_help = Agm_AdminHelp::instance();
	}

	public static function serve() {
		$me = new Agm_Fbnf_AdminPages();
		$me->_add_hooks();
	}

	private function _add_hooks() {
		add_action(
			'agm_google_maps-options-plugins_options',
			array( $this, 'register_settings' )
		);
		/*
		add_action(
			'admin_notices',
			array($this, 'admin_notice')
		);
		 */
	}

	public function register_settings() {
		add_settings_section(
			'agm_google_maps_facebook',
			__( 'Facebook Freunde in der Nähe', 'psmaps' ),
			'__return_false',
			'agm_google_maps_options_page'
		);
		add_settings_field(
			'agm_google_maps_fbnf_fb',
			__( 'Facebook App ID', 'psmaps' ),
			array( $this, 'create_fb_app_box' ),
			'agm_google_maps_options_page',
			'agm_google_maps_facebook'
		);
		add_settings_field(
			'agm_google_maps_fbnf_scope',
			__( 'Reichweite', 'psmaps' ),
			array( $this, 'create_scope_box' ),
			'agm_google_maps_options_page',
			'agm_google_maps_facebook'
		);
		add_settings_field(
			'agm_google_maps_fbnf_help',
			__( 'Richte die App ein', 'psmaps' ),
			array( $this, 'create_help_box' ),
			'agm_google_maps_options_page',
			'agm_google_maps_facebook'
		);
	}

	/**
	 * Notify the admin user about the limitations
	 */
	public function admin_notice () {
		if (!current_user_can('manage_options')) return false;
		?>
		<div class="notice notice-warning">
			<p><?php
				echo '<b>' . esc_html(__('Hinweis:', 'psmaps')) . '</b> ';
				echo sprintf(esc_html(
					__('Aufgrund der Ablehnung der erforderlichen Funktionen und Berechtigungen in der Facebook-API funktioniert die Erweiterung %s nicht für neu erstellte Anwendungen.', 'psmaps')),
					'&quot;Facebook Freunde in der Nähe&quot;'
				);
			?></p>
		</div>
		<?php
	}

	public function create_fb_app_box() {
		$fb_app_id = $this->_get_options( 'fb_app_id' );
		$this->admin_notice();
		?>
		<input type="text"
			name="agm_google_maps[fbnf-fb_app_id]"
			placeholder="<?php _e( 'Facebook App ID', 'psmaps' ); ?>"
			value="<?php echo esc_attr( $fb_app_id ); ?>" />
		<?php
	}

	public function create_scope_box() {
		$radius = $this->_get_options( 'radius' );
		$radius = (int) $radius ? (int) $radius : 1000;
		$months = $this->_get_options( 'months' );
		$months = (int) $months ? (int) $months : 4;
		?>
		<label for="agm-fbnf-radius">
			<?php _e( 'Suche nach Freunden innerhalb ', 'psmaps' ); ?>
		</label>
		<input type="number"
			id="agm-fbnf-radius"
			size="6"
			min="0"
			max="999999"
			name="agm_google_maps[fbnf-radius]"
			value="<?php echo esc_attr( $radius ); ?>" />
		<?php _e( 'Metern', 'psmaps' ); ?>

		<br />

		<label for="agm-fbnf-months">
			<?php _e( 'Suche nach Freunden in Updates und Fotos für Freunde in den letzten ', 'psmaps' ); ?>
		</label>
		<input type="text"
			id="agm-fbnf-months"
			size="3"
			min="0"
			max="9999"
			name="agm_google_maps[fbnf-months]"
			value="<?php echo esc_attr( $months ); ?>">
		<?php _e( 'Monaten', 'psmaps' ); ?>
		<?php
	}

	public function create_help_box() {
		?>
		<p>
			<?php _e( 'Befolge diese Schritte, um das Feld <em>App ID</em> einzurichten', 'psmaps' ); ?>
		</p>
		<ol>
			<li>
				<?php _e(
					'<a target="_blank" href="https://developers.facebook.com/apps">' .
					'Erstelle eine neue Facebook-App</a>', 'psmaps'
				); ?>
			</li>
			<li>
				<?php printf(
					__(
						'Deine Facebook-App sollte ähnlich aussehen. Die ID wird oben angezeigt:' .
						'<br /><img src="%s" width="590" />', 'psmaps'
					),
					AGM_PLUGIN_URL . 'img/system/fb-setup.png'
				); ?>
			</li>
		</ol>
		<?php
	}

	private function _get_options( $key ) {
		static $Opts = null;
		if ( null === $Opts ) {
			$Opts = get_option( 'agm_google_maps' );
			$Opts = apply_filters( 'agm_google_maps-options-fbnf', $Opts );
		}
		return @$Opts['fbnf-' . $key];
	}
}


class Agm_Fbnf_PublicPages {

	private function __construct() {}

	public static function serve() {
		$me = new Agm_Fbnf_PublicPages();
		$me->_add_hooks();
	}

	private function _add_hooks() {
		add_action(
			'agm-user-scripts',
			array( $this, 'load_scripts' )
		);
		add_action(
			'agm_google_maps-add_javascript_data',
			array( $this, 'add_javascript_data' )
		);
	}

	public function add_javascript_data() {
		$fb_app_id = $this->_get_options( 'fb_app_id' );
		$radius = $this->_get_options( 'radius' );
		$radius = (int) $radius ? (int) $radius : 1000;
		$months = $this->_get_options( 'months' );
		$months = (int) $months ? (int) $months : 4;

		printf(
			'<script type="text/javascript">if (typeof(_agmFbnf) == "undefined") _agmFbnf={
				"fb_app_id": "%s",
				"radius": %d,
				"months": %d
			};</script>',
			$fb_app_id,
			$radius,
			$months
		);
	}

	public function load_scripts() {
		lib3()->ui->add( AGM_PLUGIN_URL . 'js/user/fb-nearby-friends.min.js', 'front' );
	}

	private function _get_options( $key ) {
		static $Opts = null;
		if ( null === $Opts ) {
			$Opts = get_option( 'agm_google_maps' );
			$Opts = apply_filters( 'agm_google_maps-options-fbnf', $Opts );
		}
		return @$Opts['fbnf-' . $key];
	}
}

if ( is_admin() ) {
	Agm_Fbnf_AdminPages::serve();
} else {
	Agm_Fbnf_PublicPages::serve();
}