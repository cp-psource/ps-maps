<?php
/*
Plugin Name: Erzwungene Lokalisierung von Karten
Description: Standardmäßig werden Karten entsprechend dem bevorzugten Browser-Gebietsschema für Deine Besucher angezeigt. Wenn Du diese Erweiterung aktivierst, werden Karten in der Sprache angezeigt, die Du in den Plugin-Einstellungen ausgewählt hast.
Plugin URI:  https://n3rds.work/piestingtal-source-project/ps-gmaps/
Version:     1.0
Author:      DerN3rd (WMS N@W)
*/

class Agm_Locale_AdminPages {

	private function __construct() {}

	public static function serve() {
		$me = new Agm_Locale_AdminPages();
		$me->_add_hooks();
	}

	private function _add_hooks() {
		add_action(
			'agm_google_maps-options-plugins_options',
			array( $this, 'register_settings' )
		);
	}

	public function register_settings() {
		add_settings_section(
			'agm_google_maps_forced_l10n',
			__( 'Lokalisierung', 'psmaps' ),
			'__return_false',
			'agm_google_maps_options_page'
		);
		add_settings_field(
			'agm_google_maps_l10n_languages',
			__( 'Sprachen', 'psmaps' ),
			array( $this, 'create_languages_box' ),
			'agm_google_maps_options_page',
			'agm_google_maps_forced_l10n'
		);
	}

	public function create_languages_box() {
		$language = $this->_get_options( 'language' );
		?>
		<label for="agm-locale-select_language">
			<?php _e( 'Wähle deine Sprache', 'psmaps' ); ?>:
		</label>
		<select id="agm-locale-select_language" name="agm_google_maps[locale-language]">
			<option value=""><?php _e( 'Browsererkennung (Standard)', 'psmaps' ); ?></option>
			<?php foreach ( Agm_Locale_PublicPages::get_supported_languages() as $key => $lang ) : ?>
			<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $language ); ?>>
				<?php echo esc_html( $lang ); ?>
			</option>
			<?php endforeach; ?>
		</select>
		<?php
	}

	private function _get_options( $key = 'language' ) {
		$opts = apply_filters(
			'agm_google_maps-options-locale',
			get_option( 'agm_google_maps' )
		);
		return @$opts['locale-' . $key];
	}
}

class Agm_Locale_PublicPages {

	private function __construct() {}

	public static function serve() {
		$me = new Agm_Locale_PublicPages();
		$me->_add_hooks();
	}

	public static function get_supported_languages() {
		return array(
			'ar' => __( 'Arabic', 'psmaps' ),
			'eu' => __( 'Basque', 'psmaps' ),
			'bg' => __( 'Bulgarian', 'psmaps' ),
			'bn' => __( 'Bengali', 'psmaps' ),
			'ca' => __( 'Catalan', 'psmaps' ),
			'cs' => __( 'Czech', 'psmaps' ),
			'da' => __( 'Danish', 'psmaps' ),
			'de' => __( 'Deutsch', 'psmaps' ),
			'el' => __( 'Greek', 'psmaps' ),
			'en' => __( 'English', 'psmaps' ),
			'en-AU' => __( 'English (Australian)', 'psmaps' ),
			'en-GB' => __( 'English (Great Britain)', 'psmaps' ),
			'es' => __( 'Spanish', 'psmaps' ),
			'eu' => __( 'Basque', 'psmaps' ),
			'fa' => __( 'Farsi', 'psmaps' ),
			'fi' => __( 'Finnish', 'psmaps' ),
			'fil' => __( 'Filipino', 'psmaps' ),
			'fr' => __( 'French', 'psmaps' ),
			'gl' => __( 'Galician', 'psmaps' ),
			'gu' => __( 'Gujarati', 'psmaps' ),
			'hi' => __( 'Hindi', 'psmaps' ),
			'hr' => __( 'Croatian', 'psmaps' ),
			'hu' => __( 'Hungarian', 'psmaps' ),
			'id' => __( 'Indonesian', 'psmaps' ),
			'it' => __( 'Italian', 'psmaps' ),
			'iw' => __( 'Hebrew', 'psmaps' ),
			'ja' => __( 'Japanese', 'psmaps' ),
			'kn' => __( 'Kannada', 'psmaps' ),
			'ko' => __( 'Korean', 'psmaps' ),
			'lt' => __( 'Lithuanian', 'psmaps' ),
			'lv' => __( 'Latvian', 'psmaps' ),
			'ml' => __( 'Malayalam', 'psmaps' ),
			'mr' => __( 'Marathi', 'psmaps' ),
			'nl' => __( 'Dutch', 'psmaps' ),
			'no' => __( 'Norwegian', 'psmaps' ),
			'pl' => __( 'Polish', 'psmaps' ),
			'pt' => __( 'Portuguese', 'psmaps' ),
			'pt-BR' => __( 'Portuguese (Brazil)', 'psmaps' ),
			'pt-PT' => __( 'Portuguese (Portugal)', 'psmaps' ),
			'ro' => __( 'Romanian', 'psmaps' ),
			'ru' => __( 'Russian', 'psmaps' ),
			'sk' => __( 'Slovak', 'psmaps' ),
			'sl' => __( 'Slovenian', 'psmaps' ),
			'sr' => __( 'Serbian', 'psmaps' ),
			'sv' => __( 'Swedish', 'psmaps' ),
			'tl' => __( 'Tagalog', 'psmaps' ),
			'ta' => __( 'Tamil', 'psmaps' ),
			'te' => __( 'Telugu', 'psmaps' ),
			'th' => __( 'Thai', 'psmaps' ),
			'tr' => __( 'Turkish', 'psmaps' ),
			'uk' => __( 'Ukrainian', 'psmaps' ),
			'vi' => __( 'Vietnamese', 'psmaps' ),
			'zh-CN' => __( 'Chinese (simplified)', 'psmaps' ),
			'zh-TW' => __( 'Chinese (traditional)', 'psmaps' ),
		);
	}

	private function _get_options( $key = 'language' ) {
		$opts = apply_filters(
			'agm_google_maps-options-locale',
			get_option( 'agm_google_maps' )
		);
		return @$opts['locale-' . $key];
	}

	private function _add_hooks() {
		add_action( 'agm_google_maps-add_javascript_data', array($this, 'add_language_data' ) );
	}

	public function add_language_data() {
		$language = $this->_get_options( 'language' );
		$all_languages = array_keys( self::get_supported_languages() );
		if ( ! in_array( $language, $all_languages ) ) { return false; }
		printf(
			'<script type="text/javascript">if (typeof(_agmLanguage) == "undefined") _agmLanguage="%s";</script>',
			$language
		);
	}
}

if ( is_admin() ) {
	Agm_Locale_AdminPages::serve();
} else {
	Agm_Locale_PublicPages::serve();
}