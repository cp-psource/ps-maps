<?php

class Agm_AdminHelp {

	const SETTINGS_SCREEN_ID = 'settings_page_agm_google_maps';

	private $_help;

	static public function instance() {
		static $Instance = null;

		if ( ! $Instance instanceof Agm_AdminHelp ) {
			$Instance = new Agm_AdminHelp();
			$Instance->_add_hooks();
		}
		return $Instance;
	}

	private function __construct() {
		if ( ! class_exists( 'PSOURCE_ContextualHelp' ) ) {
			require_once AGM_INC_DIR . 'external/class_wd_contextual_help.php';
		}
		$this->_help = new PSOURCE_ContextualHelp();
	}

	private function _get_default_tabs() {
		return array(
			array(
				'id'      => 'agm_google_maps-options',
				'title'   => __( 'Optionen', AGM_LANG ),
				'content' => '' .
					'<p>' . __(
						'Hier kannst Du PS-Maps einrichten ' .
						'Standardeinstellungen.', AGM_LANG
					) . '</p>'
			),
			array(
				'id'      => 'agm_google_maps-custom_fields',
				'title'   => __( 'Benutzerdefinierte Felder', AGM_LANG ),
				'content' => '' .
					'<p>' . __(
						'Hier kannst Du die automatische Erstellung von neuen Google Maps  ' .
						'einrichten, die durch Deine vorhandenen ' .
						' benutzerdefinierten Standortfelder ausgelöst wird.', AGM_LANG
					) . '</p>'
			),
			array(
				'id'      => 'agm_google_maps-addons',
				'title'   => __( 'Erweiterungen', AGM_LANG ),
				'content' => '' .
					'<p>' . __(
						'Dies sind die optionalen Ergänzungen für Google Maps.' .
						'Maps. Aktiviere oder deaktiviere sie nach Bedarf.', AGM_LANG
					) . '</p>'
			),

		);
	}

	private function _get_default_sidebar() {
		return '' .
			'<h4>' . __( 'PS-Maps', AGM_LANG ) . '</h4>' .
			'<ul>' .
				'<li>' .
					'<a href="https://n3rds.work/piestingtal-source-project/ps-gmaps/" target="_blank">' .
						__( 'Projektseite', AGM_LANG ) .
					'</a>' .
				'</li>' .
				'<li>' .
					'<a href="https://n3rds.work/wiki/piestingtal-source-wiki/ps-google-maps/" target="_blank">' .
						__( 'Installations- und Anleitungswiki', AGM_LANG ) .
					'</a>' .
				'</li>' .
				'<li>' .
					'<a href="https://n3rds.work/forenindex/nw-forum/" target="_blank">' .
						__( 'Support Forum', AGM_LANG ) .
					'</a>' .
				'</li>' .
			'</ul>' .
		'';
	}

	private function _add_hooks() {
		add_action(
			'admin_init',
			array( $this, 'initialize' )
		);

		add_filter(
			'plugin_action_links_' . AGM_PLUGIN,
			array( $this, 'add_settings_link' )
		);
	}

	public function initialize() {
		$this->_help->add_page(
			self::SETTINGS_SCREEN_ID,
			$this->_get_default_tabs(),
			$this->_get_default_sidebar()
		);
		$this->_help->initialize();
	}

	public function add_tab( $id, $title, $content ) {
		$this->_help->add_tab(
			self::SETTINGS_SCREEN_ID,
			array(
				'id'      => $id,
				'title'   => $title,
				'content' => $content,
			)
		);
	}

	/**
	 * Add quick link to plugin settings page.
	 *
	 * @param $links Links array.
	 *
	 * @return array
	 */
	public function add_settings_link( $links ) {

		$plugin_link = array(
			'<a href="' . admin_url( 'options-general.php?page=agm_google_maps' ) . '">' . __( "Einstellungen", AGM_LANG ) . '</a>',
		);

		return array_merge( $plugin_link, $links );
	}
};

Agm_AdminHelp::instance();