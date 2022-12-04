<?php

/**
 * Handles rendering of form elements for plugin Options page.
 */
class Agm_AdminFormRenderer {

	/**
	 * Helper function that returns a small text input field.
	 *
	 * @access private
	 */
	private function _create_small_text_box( $name, $value ) {
		return "<input type='text' name='agm_google_maps[{$name}]' id='{$name}' size='3' value='{$value}' />";
	}

	/**
	 * Helper function that returns a YES/NO option group (radio buttons).
	 *
	 * @access private
	 */
	private function _create_cfyn_box( $name, $value ) {
		$state_yes = (1 == $value ) ? 'checked="checked"' : '';
		$state_no = (1 != $value ) ? 'checked="checked"' : '';

		return
			'<label for="agm_cfyn_' . $name . '-yes">' .
			'<input type="radio" name="agm_google_maps[custom_fields_options][' . $name . ']" id="agm_cfyn_' . $name . '-yes" value="1" ' . $state_yes . ' /> ' . __( 'Ja', 'psmaps' ) .
			'</label>' .
			'&nbsp;' .
			'<label for="agm_cfyn_' . $name . '-no">' .
			'<input type="radio" name="agm_google_maps[custom_fields_options][' . $name . ']" id="agm_cfyn_' . $name . '-no" value="0" ' . $state_no . ' /> ' . __( 'Nein', 'psmaps' ) .
			'</label>'
		;
	}

	// -----

	public function create_height_box() {
		$opt = apply_filters( 'agm_google_maps-options', get_option( 'agm_google_maps' ) );
		echo '' . $this->_create_small_text_box( 'height', @$opt['height'] ) . 'px';
	}

	public function create_width_box() {
		$opt = apply_filters( 'agm_google_maps-options', get_option( 'agm_google_maps' ) );
		echo '' . $this->_create_small_text_box( 'width', @$opt['width'] ) . 'px';
	}

	public function create_image_limit_box() {
		$opt = apply_filters( 'agm_google_maps-options', get_option( 'agm_google_maps' ) );
		$limit = (isset( $opt['image_limit'] ) ) ? $opt['image_limit'] : 10;
		echo '' . $this->_create_small_text_box( 'image_limit', $limit );
	}

	public function create_map_type_box() {
		$opt = apply_filters( 'agm_google_maps-options', get_option( 'agm_google_maps' ) );
		$items = array(
			'ROADMAP' => __( 'STRAßENKARTE', 'psmaps' ),
			'SATELLITE' => __( 'SATELLIT', 'psmaps' ),
			'HYBRID' => __( 'HYBRID', 'psmaps' ),
			'TERRAIN' => __( 'TERRAIN', 'psmaps' ),
		);
		echo '<select id="map_type" name="agm_google_maps[map_type]">';
		foreach ( $items as $item => $lbl ) {
			$selected = ( @$opt['map_type'] == $item ) ? 'selected="selected"' : '';
			echo '<option value="' . $item . '" ' . $selected . '>' . $lbl . '</option>';
		}
		echo '</select>';
	}

	public function create_map_api_key_box() {
		$opt = apply_filters( 'agm_google_maps-options', get_option( 'agm_google_maps' ) );
		$value = !empty($opt['map_api_key'])
			? $opt['map_api_key']
			: ''
		;

		echo '<input type="text" class="widefat" name="agm_google_maps[map_api_key]" value="' . esc_attr($value) . '"> ';
		printf(
			__('Hole Dir Deinen API-Schlüssel <a href="%s" target="_blank">hier</a>', 'psmaps'),
			esc_url('https://console.developers.google.com/flows/enableapi?apiid=maps_backend,geocoding_backend,directions_backend,distance_matrix_backend,elevation_backend&keyType=CLIENT_SIDE&reusekey=true')
		);
		echo ' ';
		printf(
			__('(Du kannst auch auf den <a href="%s" target="_blank">offiziellen Google-Dokumentationsseiten</a> etwas mehr recherchieren)', 'psmaps'),
			esc_url('https://developers.google.com/maps/documentation/javascript/get-api-key#get-an-api-key')
		);
	}

	public function create_map_zoom_box() {
		$items = array(
			'1' => __( 'Erde', 'psmaps' ),
			'3' => __( 'Kontinent', 'psmaps' ),
			'5' => __( 'Region', 'psmaps' ),
			'7' => __( 'Nahe Städte', 'psmaps' ),
			'12' => __( 'Stadtplan', 'psmaps' ),
			'15' => __( 'Details', 'psmaps' ),
		);
		$opt = apply_filters( 'agm_google_maps-options', get_option( 'agm_google_maps' ) );
		$zoom = ! empty( $opt['zoom'] ) && is_numeric( $opt['zoom'] ) ? (int) $opt['zoom'] : 1;
		$is_advanced = (bool) (empty( $opt['zoom'] ) || ! in_array( $zoom, array_keys( $items ) ) );

		$basic_visibility = $is_advanced ? 'style="display:none"' : '';
		$basic_disabled = $is_advanced ? 'disabled="disabled"' : '';
		$advanced_visibility = $is_advanced ? '' : 'style="display:none"';
		$advanced_disabled = $is_advanced ? '' : 'disabled="disabled"';

		// Basic
		?>
		<div id="agm-zoom-basic-container" <?php echo '' . $basic_visibility; ?>>
			<select id="zoom" name="agm_google_maps[zoom]" <?php echo '' . $basic_disabled; ?>>
			<?php foreach ( $items as $item => $label ) : ?>
				<option value="<?php echo esc_attr( $item ); ?>" <?php selected( $zoom, $item ); ?>>
					<?php echo esc_html( $label ); ?>
				</option>
			<?php endforeach; ?>
			</select>
			&nbsp;<a href="#agm-advanced_zoom" id="agm-advanced_zoom-toggler"><?php _e( 'Erweitert', 'psmaps' ); ?></a>
			<?php _e(
				'<div>Bitte beachte, dass diese Titel nur Näherungswerte sind, '.
				'jedoch im Allgemeinen zur Beschreibung passen.</div>', 'psmaps'
			); ?>
		</div>
		<?php

		// Advanced
		?>
		<div id="agm-zoom-advanced-container" <?php echo '' . $advanced_visibility; ?>>
			<input type="text"
				size="2"
				name="agm_google_maps[zoom]"
				value="<?php echo esc_attr( $zoom ); ?>"
				id="agm-zoom-advanced"
				<?php echo '' . $advanced_disabled; ?> />
			&nbsp;<a href="#agm-advanced_zoom" id="agm-basic_zoom-toggler"><?php _e( 'Basismodus', 'psmaps' ); ?></a>
			<?php _e( '<div>Bitte gib den numerischen Zoomwert ein.</div>', 'psmaps' ); ?>
		</div>
		<?php

		// Toggling JS
		?>
		<script type="text/javascript">
		(function() {
		jQuery("#agm-advanced_zoom-toggler").on("click", function() {
			jQuery("#agm-zoom-basic-container")
				.find("select").attr("disabled", true ).end()
				.hide()
			;
			jQuery("#agm-zoom-advanced-container")
				.find("#agm-zoom-advanced").attr("disabled", false ).end()
				.show()
			;
			return false;
		});
		jQuery("#agm-basic_zoom-toggler").on("click", function() {
			jQuery("#agm-zoom-advanced-container")
				.find("#agm-zoom-advanced").attr("disabled", true ).end()
				.hide()
			;
			jQuery("#agm-zoom-basic-container")
				.find("select").attr("disabled", false ).end()
				.show()
			;
			return false;
		});
		})();
		</script>
		<?php
	}

	public function create_map_units_box() {
		$opt = apply_filters( 'agm_google_maps-options', get_option( 'agm_google_maps' ) );
		$items = array(
			'METRIC' => __( 'Metrisch', 'psmaps' ),
			'IMPERIAL' => __( 'Imperial', 'psmaps' ),
		);
		echo '<select id="zoom" name="agm_google_maps[units]">';
		foreach ( $items as $item => $label ) {
			$selected = ( @$opt['units'] == $item ) ? 'selected="selected"' : '';
			echo '<option value="' . $item .'" ' . $selected . '>' . $label . '</option>';
		}
		echo '</select>';
		_e( '<div>Diese Einheiten werden verwendet, um Entfernungen für Richtungen auszudrücken</div>', 'psmaps' );
	}

	public function create_image_size_box() {
		$opt = apply_filters( 'agm_google_maps-options', get_option( 'agm_google_maps' ) );
		$items = array(
			'small' => __( 'Klein', 'psmaps' ),
			'medium' => __( 'Mittel', 'psmaps' ),
			'thumbnail' => __( 'Thumbnail', 'psmaps' ),
			'square' => __( 'Quadrat', 'psmaps' ),
			'mini_square' => __( 'Mini Quadrat', 'psmaps' ),
		);
		echo '<select id="image_size" name="agm_google_maps[image_size]">';
		foreach ( $items as $item => $lbl ) {
			$selected = ( @$opt['image_size'] == $item ) ? 'selected="selected"' : '';
			echo '<option value="' . $item .'" ' . $selected . '>' . $lbl . '</option>';
		}
		echo '</select>';
	}

	public function create_alignment_box() {
		$opt = apply_filters( 'agm_google_maps-options', get_option( 'agm_google_maps' ) );
		$pos = @$opt['map_alignment'];
		?>
		<input type="radio"
			id="map_alignment_left"
			name="agm_google_maps[map_alignment]"
			value="left"
			<?php checked( 'left' == $pos ); ?> />
		<label for="map_alignment_left">
			<img src="<?php echo esc_attr( AGM_PLUGIN_URL ); ?>img/system/left.png" />
			<?php _e( 'Links', 'psmaps' ); ?>
		</label><br />

		<input type="radio"
			id="map_alignment_center"
			name="agm_google_maps[map_alignment]"
			value="center"
			<?php checked( 'center' == $pos ); ?> />
		<label for="map_alignment_center">
			<img src="<?php echo esc_attr( AGM_PLUGIN_URL ); ?>img/system/center.png" />
			<?php _e( 'Zentriert', 'psmaps' ); ?>
		</label><br />

		<input type="radio"
			id="map_alignment_right"
			name="agm_google_maps[map_alignment]"
			value="right"
			<?php checked( 'right' == $pos ); ?> />
		<label for="map_alignment_right">
			<img src="<?php echo esc_attr( AGM_PLUGIN_URL ); ?>img/system/right.png" />
			<?php _e( 'Rechts', 'psmaps' ); ?>
		</label><br />
		<?php
	}

	public function create_custom_css_box() {
		$opt = apply_filters( 'agm_google_maps-options', get_option( 'agm_google_maps' ) );
		$css = @$opt['additional_css'];
		$css = htmlspecialchars( $css ??'' );  // preserve the original formating
		?>
		<textarea name="agm_google_maps[additional_css]" class="widefat" rows="4" cols="32"><?php
		echo esc_textarea( $css );
		?></textarea>
		<?php
		_e(
			'<p>In diesem Feld kannst Du einige schnelle Stiländerungen hinzufügen,  ' .
			'um das Erscheinungsbild der Karten besser mit Deinem Theme zu kombinieren.</p>', 'psmaps'
		);
		_e(
			'<p>Möglicherweise möchtest Du Stile für einige dieser Selektoren festlegen: ' .
			'<code>.agm_mh_info_title</code>, ' .
			'<code>.agm_mh_info_body</code>, ' .
			'<code>a.agm_mh_marker_item_directions</code>, ' .
			'<code>.agm_mh_marker_list</code>, ' .
			'<code>.agm_mh_marker_item</code>, ' .
			'<code>.agm_mh_marker_item_content</code></p>', 'psmaps'
		);
	}

	public function create_snapping_box() {
		$opt = apply_filters( 'agm_google_maps-options', get_option( 'agm_google_maps' ) );
		$use = isset( $opt['snapping'] ) ? $opt['snapping'] : 1;
		?>
		<label for="agm_snapping-yes">
		<input type="radio"
			name="agm_google_maps[snapping]"
			id="agm_snapping-yes"
			value="1"
			<?php checked( $use ); ?> />
			<?php _e( 'Ja', 'psmaps' ); ?>
		</label>
		&nbsp; | &nbsp;
		<label for="agm_snapping-no">
		<input type="radio"
			name="agm_google_maps[snapping]"
			id="agm_snapping-no"
			value="0"
			<?php checked( ! $use ); ?> />
			<?php _e( 'Nein', 'psmaps' ); ?>
		</label>
		<?php
	}

	public function create_alt_shortcode_box() {
		$use = 'agm_map' == AgmMapModel::get_config( 'shortcode_map' );
		$original = $use ? 'agm_map' : 'map';

		?>
		<label for="shortcode_map-yes">
		<input type="radio"
			name="agm_google_maps[shortcode_map]"
			class="agm_shortcode_map"
			id="shortcode_map-yes"
			value="map"
			<?php checked( ! $use ); ?> />
			<code>[map]</code>
			<?php _e( '(Standard)', 'psmaps' ); ?>
		</label>
		&nbsp; | &nbsp;
		<label for="shortcode_map-no">
		<input type="radio"
			name="agm_google_maps[shortcode_map]"
			class="agm_shortcode_map"
			id="shortcode_map-no"
			value="agm_map"
			<?php checked( $use ); ?> />
			<code>[agm_map]</code>
		</label>
		<p>
			<?php _e(
				'Wenn die Karte nicht auf Deiner Seite angezeigt wird, versuche ' .
				'den alternativen Shortcode <code>[agm_map]</code> zu verwenden.', 'psmaps'
			); ?>
			<div class="alt-hint" style="display:none;color: #900;margin: 10px 0px;">
			<?php _e(
				'<strong>Tipp:</strong> Verwende nach dem Ändern des Shortcodes ' .
				'die Erweiterung "Fixes und Reparaturen" und überprüfe den Status von ' .
				'"Map Shortcode umbenennen". Bei Bedarf aktualisiert dieses Tool ' .
				'automatisch alle Ihre Seiten und Beiträge, um den ' .
				'neuen Shortcode zu verwenden.', 'psmaps'
			); ?>
			</div>
		</p>
		<script>
		jQuery(".agm_shortcode_map").on("click", function() {
			if ( jQuery(this).val() != "<?php echo esc_js( $original ); ?>") {
				jQuery(".alt-hint").show();
			} else {
				jQuery(".alt-hint").hide();
			}
		})
		</script>
		<?php
	}

	public function create_directions_snapping_box() {
		$opt = apply_filters( 'agm_google_maps-options', get_option( 'agm_google_maps' ) );
		$use = isset( $opt['directions_snapping'] ) ? $opt['directions_snapping'] : 1;

		?>
		<label for="agm_directions_snapping-yes">
		<input type="radio"
			name="agm_google_maps[directions_snapping]"
			id="agm_directions_snapping-yes"
			value="1"
			<?php checked( $use ); ?> />
			<?php _e( 'Ja', 'psmaps' ); ?>
		</label>
		&nbsp; | &nbsp;
		<label for="agm_directions_snapping-no">
		<input type="radio"
			name="agm_google_maps[directions_snapping]"
			id="agm_directions_snapping-no"
			value="0"
			<?php checked( ! $use ); ?> />
			<?php _e( 'Nein', 'psmaps' ); ?>
		</label>
		<?php
	}

	public function create_use_custom_fields_box() {
		$opt = apply_filters( 'agm_google_maps-options', get_option( 'agm_google_maps' ) );
		$use = @$opt['use_custom_fields'];

		?>
		<label for="agm_use_custom_fields-yes">
		<input type="radio"
			name="agm_google_maps[use_custom_fields]"
			id="agm_use_custom_fields-yes"
			value="1"
			<?php checked( $use ); ?> />
			<?php _e( 'Ja', 'psmaps' ); ?>
		</label>
		&nbsp; | &nbsp;
		<label for="agm_use_custom_fields-no">
		<input type="radio"
			name="agm_google_maps[use_custom_fields]"
			id="agm_use_custom_fields-no"
			value="0"
			<?php checked( ! $use ); ?> />
			<?php _e( 'Nein', 'psmaps' ); ?>
		</label>
		<?php
	}

	public function create_custom_fields_map_box() {
		$opt = apply_filters( 'agm_google_maps-options', get_option( 'agm_google_maps' ) );
		$lat_field = @$opt['custom_fields_map']['latitude_field'];
		$lon_field = @$opt['custom_fields_map']['longitude_field'];
		$add_field = @$opt['custom_fields_map']['address_field'];

		echo '<div><b>' . __( 'Meine Beiträge haben Breiten-/Längengrade', 'psmaps' ) . '</b></div>';
		_e( 'Breitengrad Feldname:', 'psmaps' );
		echo ' <input type="text" name="agm_google_maps[custom_fields_map][latitude_field]" size="12" value="' . esc_attr( $lat_field ) . '" />';
		echo '<br />';
		_e( 'Längengrad Feldname:', 'psmaps' );
		echo ' <input type="text" name="agm_google_maps[custom_fields_map][longitude_field]" size="12" value="' . esc_attr( $lon_field ) . '" />';

		echo '<div><b>' . __( 'Meine Beiträge haben ein Adressfeld', 'psmaps' ) . '</b></div>';
		_e( 'Adressfeldname(n):', 'psmaps' );
		echo ' <input type="text" name="agm_google_maps[custom_fields_map][address_field]" size="24" value="' . esc_attr( $add_field ) . '" />';

		$discard = @$opt['custom_fields_map']['discard_old'] ? 'checked="checked"' : '';
		echo '<br />';
		echo '<input type="hidden" name="agm_google_maps[custom_fields_map][discard_old]" value="" />';
		echo '<input type="checkbox" id="agm-custom_fields-discard_old" name="agm_google_maps[custom_fields_map][discard_old]" value="1" ' . $discard . ' />';
		echo '&nbsp;';
		echo '<label for="agm-custom_fields-discard_old">' . __( 'Alte Karte verwerfen, wenn sich der Wert meiner benutzerdefinierten Felder ändert', 'psmaps' ) . '</label>';
	}

	public function create_custom_fields_options_box() {
		$opt = apply_filters( 'agm_google_maps-options', get_option( 'agm_google_maps' ) );
		$opt = @$opt['custom_fields_options'];
		echo '<div><small>' . __( '(Eine neue Karte wird automatisch mit den oben angegebenen Standardeinstellungen erstellt )', 'psmaps' ) . '</small></div>';
		_e( 'Ordne die neue Karte dem Beitrag zu:', 'psmaps' );
		echo ' ' . $this->_create_cfyn_box( 'associate_map', @$opt['associate_map'] ) . '<br />';
		_e( 'Karte automatisch anzeigen:', 'psmaps' );
		echo ' ' . $this->_create_cfyn_box( 'autoshow_map', @$opt['autoshow_map'] ) . '<br />';

		$positions = array(
			'top' => 'Oberhalb',
			'bottom' => 'Unterhalb',
		);
		$select = '<select name="agm_google_maps[custom_fields_options][map_position]">';
		foreach ( $positions as $key => $lbl ) {
			$select .= "<option value='{$key}' " . (( $key == @$opt['map_position'] ) ? 'selected="selected"' : '' ) . '>' . __( $lbl, 'psmaps' ) . '</option>';
		}
		$select .= '</select>';

		printf(
			__( 'Wenn die vorherige Option auf "Ja" gesetzt ist, wird die neue Karte %s im Beitrags-Inhalt angezeigt', 'psmaps' ),
			$select
		);
	}

	public function create_overview_box() {
		$shortcode_tag = 'agm_map' == AgmMapModel::get_config( 'shortcode_map' ) ? 'agm_map' : 'map';
		?>
		<p>
			<?php printf(
				__(
					'Das Google Maps Plugin fügt ein &quot;Karte hinzufügen&quot; Symbol ' .
					'zu Deinem visuellen Editor hinzu. Sobald Du Deine neue ' .
					'Karte erstellt hast, wird sie als Shortcode in den Bereich "Beitrag/Seite schreiben" ' .
					'eingefügt. In etwa so: <code>[%s id="1"]</code>.',
					'psmaps'
				),
				$shortcode_tag
			); ?>
		</p>
		<p>
			<?php _e(
				'Außerdem wird ein Widget hinzugefügt, mit dem Du Deiner Seitenleiste ' .
				'Karten hinzufügen kannst (siehe Darstellungs-Widgets).', 'psmaps'
			); ?>
			<iframe style="width: 100%; min-height:600px" src="https://n3rds.work/piestingtal_source/ps-google-maps-plugin/" title="PS-Maps Projektseite"></iframe>
		</p>
		<?php
		// Don't show this link for multisite blogs to keep the plugin white-labeled.
		if ( ! is_multisite() ) {
			?>
			<p>
				<?php printf(
					__(
						'Ausführlichere Anweisungen zur Verwendung ' .
						'findest Du unter <a target="_blank" href="%s">' .
						'Anweisungen zur Installation und Verwendung von PS-Maps</a>.', 'psmaps'
					),
					'https://n3rds.work/docs/ps-google-maps-handbuch/'
				); ?>
			</p>
			<?php
		}
	}

	public function create_plugins_box() {
		$all = AgmPluginsHandler::get_all_plugins();
		$active = AgmPluginsHandler::get_active_plugins();
		$has_buddypress = defined( 'BP_PLUGIN_DIR' );
		$has_kml = ( false !== array_search( 'kml-overlay', $active ) ) ||
			( false !== array_search( 'agm-kml_overlay', $active ) );
		$sections = array( 'thead', 'tfoot' );
		$count_all = count( $all );
		$count_active = count( $active );
		$count_inactive = $count_all - $count_active;

		?>
		<p class="subsubsub">
			<a href="#add-ons" class="agm-addons-all current">
				<?php _e( 'Alle', 'psmaps' ); ?>
				<span class="count">(<?php echo esc_html( $count_all ); ?>)</span>
			</a>
			|
			<a href="#add-ons" class="agm-addons-active">
				<?php _e( 'Aktive', 'psmaps' ); ?>
				<span class="count">(<?php echo esc_html( $count_active ); ?>)</span>
			</a>
			|
			<a href="#add-ons" class="agm-addons-inactive">
				<?php _e( 'Inaktiv', 'psmaps' ); ?>
				<span class="count">(<?php echo esc_html( $count_inactive ); ?>)</span>
			</a>
		</p>

		<?php

		echo '<table class="widefat plugins">';
		foreach ( $sections as $section ) : ?>
			<?php echo '<' . $section . '>'; ?>
			<tr>
			<th width="10" class="check-column">&nbsp;</th>
			<th width="30%"><?php _e( 'Erweiterungsname', 'psmaps' ); ?></th>
			<th width="70%"><?php _e( 'Erweiterung Beschreibung', 'psmaps' ); ?></th>
			</tr>
			<?php echo '</' . $section . '>'; ?>
		<?php endforeach;

		echo '<tbody>';

		// Collect addon-details to generate a sorted list.
		$items = array();
		foreach ( $all as $addon ) {
			$addon_data = AgmPluginsHandler::get_plugin_info( $addon );
			if ( ! @$addon_data['name'] ) {
				// Require the name
				continue;
			}

			$is_active = in_array( $addon, $active );

			$items[ $addon_data['name'] ] = $addon_data;
			$items[ $addon_data['name'] ]['key'] = $addon;
			$items[ $addon_data['name'] ]['active'] = $is_active;
			if ( ! empty( $addon_data['example'] ) ) {
				$items[ $addon_data['name'] ]['example'] = explode( ', ', $addon_data['example'] );
			}
		}
		ksort( $items );

		// Display a sorted list of all add-ons.
		foreach ( $items as $data ) {
			$row_class = $data['active'] ? 'active' : 'inactive';
			$can_use = true;
			if ( ! $has_buddypress && 'BuddyPress' == @$data['requires'] ) { $can_use = false; }
			if ( ! $has_kml && 'KML Overlay' == @$data['requires'] ) { $can_use = false; }

			?>
			<tr class="agm-add-on <?php echo esc_attr( $row_class ); ?>">
			<th width="10" class="check-column">&nbsp;</th>
			<td width="30%" class="plugin-title" width="30%">
			<strong><?php echo esc_html( $data['name'] ); ?></strong>
			<div class="row-actions visible">
			<?php if ( $data['active'] ) : ?>
				<a href="#deactivate"
					class="agm_plugin"
					data-action="agm_deactivate_plugin"
					data-plugin="<?php echo esc_attr( $data['key'] ); ?>" >
				<?php _e( 'Deaktivieren', 'psmaps' ); ?>
				</a>
			<?php else : ?>
				<?php if ( $can_use ) : ?>
					<a href="#activate"
					class="agm_plugin"
					data-action="agm_activate_plugin"
					data-plugin="<?php echo esc_attr( $data['key'] ); ?>" >
					<?php _e( 'Aktivieren', 'psmaps' ); ?>
					</a>
				<?php endif; ?>
			<?php endif; ?>
			<?php if ( ! $can_use && strlen( @$data['requires'] ) ) : ?>
				<div>
					<?php _e( 'Benötigt ', 'psmaps' ); ?>
					<?php echo esc_html( $data['requires'] ); ?>
				</div>
			<?php endif; ?>
			</div>
			</td>
			<td width="70%" class="column-description desc">
				<p>
				<?php echo '' . $data['desc']; ?>
				</p>
				<?php if ( ! empty( $data['example'] ) ) : ?>
					<p class="example">
					<?php _e( 'Beispiel:', 'psmaps' ); ?><br />
					<code><?php echo '' . implode( '</code>, <code>', $data['example'] ); ?></code>
					</p>
				<?php endif; ?>
			</td>
			</tr>
			<?php
		}
		?>
		</tbody>
		</table>
		<script type="text/javascript">
		(function() {
			var list = jQuery( '.plugins' ),
				filters = jQuery( '.subsubsub > a' );

			jQuery( document ).on( 'click', '.agm_plugin', function(ev) {
				var me = jQuery( this ),
					plugin_id = me.attr( 'data-plugin' ),
					action = me.attr( 'data-action' );

				jQuery.post(
					ajaxurl,
					{
						"action": action,
						"plugin": plugin_id
					},
					function( data ) {
						// On success reload the window
						window.location.reload();
					}
				);
				return false;
			});

			jQuery( '.agm-addons-all' ).on("click", function(){
				filters.removeClass( 'current' );
				jQuery( this ).addClass( 'current' );
				list.find( 'tr.agm-add-on' ).show();
			});
			jQuery( '.agm-addons-active' ).on("click", function(){
				filters.removeClass( 'current' );
				jQuery( this ).addClass( 'current' );
				list.find( 'tr.agm-add-on.inactive' ).hide();
				list.find( 'tr.agm-add-on.active' ).show();
			});
			jQuery( '.agm-addons-inactive' ).on("click", function(){
				filters.removeClass( 'current' );
				jQuery( this ).addClass( 'current' );
				list.find( 'tr.agm-add-on.inactive' ).show();
				list.find( 'tr.agm-add-on.active' ).hide();
			});
		})();
		</script>
		<?php
	}
}