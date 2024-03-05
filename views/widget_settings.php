<p>
	<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Titel:', 'psmaps' ); ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
</p>

<p>
	<label for="<?php echo $this->get_field_id( 'width' ); ?>"><?php _e( 'Kartengröße:', 'psmaps' ); ?></label>
	<input size="3" id="<?php echo $this->get_field_id( 'width' ); ?>" name="<?php echo $this->get_field_name( 'width' ); ?>" type="text" value="<?php echo $width; ?>" />
	&nbsp;x&nbsp;
	<input size="3" id="<?php echo $this->get_field_id( 'height' ); ?>" name="<?php echo $this->get_field_name( 'height' ); ?>" type="text" value="<?php echo $height; ?>" />
</p>

<p class="agm_widget_query_options">
	<label><?php _e( 'Verwende Karten von:', 'psmaps' ); ?></label>
	<br />

	<input id="<?php echo $this->get_field_id( 'query_current' ); ?>" name="<?php echo $this->get_field_name( 'query' ); ?>" type="radio" <?php echo (( 'current' == $query) ? 'checked="checked"' : '' ); ?> value="current" />
		<label for="<?php echo $this->get_field_id( 'query_current' ); ?>"><?php _e( 'Aktuelle Beiträge', 'psmaps' ); ?></label>
	<br />

	<input id="<?php echo $this->get_field_id( 'query_all_posts' ); ?>" name="<?php echo $this->get_field_name( 'query' ); ?>" type="radio" <?php echo (( 'all_posts' == $query) ? 'checked="checked"' : '' ); ?> value="all_posts" />
		<label for="<?php echo $this->get_field_id( 'query_all_posts' ); ?>"><?php _e( 'Alle Beiträge', 'psmaps' ); ?></label>
	<br />

	<input id="<?php echo $this->get_field_id( 'query_all' ); ?>" name="<?php echo $this->get_field_name( 'query' ); ?>" type="radio" <?php echo (( 'all' == $query) ? 'checked="checked"' : '' ); ?> value="all" />
		<label for="<?php echo $this->get_field_id( 'query_all' ); ?>"><?php _e( 'Alle', 'psmaps' ); ?></label>
	<br />

	<input id="<?php echo $this->get_field_id( 'query_random' ); ?>" name="<?php echo $this->get_field_name( 'query' ); ?>" type="radio" <?php echo (( 'random' == $query) ? 'checked="checked"' : '' ); ?> value="random" />
		<label for="<?php echo $this->get_field_id( 'query_random' ); ?>"><?php _e( 'Zufällige Karte', 'psmaps' ); ?></label>
	<br />

	<input class="map_id_switch" id="<?php echo $this->get_field_id( 'query_id' ); ?>" name="<?php echo $this->get_field_name( 'query' ); ?>" type="radio" <?php echo (( 'id' == $query) ? 'checked="checked"' : '' ); ?> value="id" />
		<label for="<?php echo $this->get_field_id( 'query_id' ); ?>"><?php _e( 'Benutze diese Karte:', 'psmaps' ); ?></label>
	<select class="map_id_target" id="<?php echo $this->get_field_id( 'map_id' ); ?>" name="<?php echo $this->get_field_name( 'map_id' ); ?>">
		<option value=""><?php _e( 'Wähle ein', 'psmaps' ); ?></option>
	<?php foreach ($maps as $map) { ?>
		<option value="<?php echo $map['id'];?>" <?php echo (($map_id == $map['id']) ? 'selected="selected"' : '' );?>><?php echo esc_html($map['title']); ?></option>
	<?php } ?>
	</select>
	<br />
	<small><a class="agm_create_new_map" href="#"><?php _e( 'Neue Karte erstellen' );?></a></small>
	<br />

	<input class="custom_switch" id="<?php echo $this->get_field_id( 'query_custom_switch' ); ?>" name="<?php echo $this->get_field_name( 'query' ); ?>" type="radio" <?php echo (( 'custom' == $query) ? 'checked="checked"' : '' ); ?> value="custom" />
		<label for="<?php echo $this->get_field_id( 'query_custom_switch' ); ?>"><?php _e( 'Benutzerdefinierte Abfrage', 'psmaps' ); ?></label>
	<input class="widefat custom_target" id="<?php echo $this->get_field_id( 'query_custom' ); ?>" name="<?php echo $this->get_field_name( 'query_custom' ); ?>" type="text" value="<?php echo $query_custom; ?>" />
	<?php if (AGM_USE_POST_INDEXER) { // Integrate post indexer ?>
		<input class="post_indexer_target" type="checkbox" id="<?php echo $this->get_field_id( 'network' ); ?>" name="<?php echo $this->get_field_name( 'network' ); ?>" <?php echo (( 'network' == $network) ? 'checked="checked"' : '' ); ?> value="network" />
			<label for="<?php echo $this->get_field_id( 'network' ); ?>"><?php _e( 'Dies ist eine Netzwerkabfrage <small>(Benötigt <a href="https://cp-psource.github.io/ps-postindexer/">PS-Multisite Beitragsindex</a> plugin) - nur "tag = ..." wird unterstützt</small>', 'psmaps' ); ?></label>
			<br />
	<?php } ?>
	<small><em><a target="_blank" href="https://cp-psource.github.io/ps-maps/"><?php _e( 'Weitere Informationen zu benutzerdefinierten Abfragen', 'psmaps' ); ?></a></em></small>
</p>

<p>
	<label><?php _e( 'Zeige:', 'psmaps' ); ?></label>
	<br />
	<input class="show_as_one" id="<?php echo $this->get_field_id( 'show_as_one' ); ?>" name="<?php echo $this->get_field_name( 'show_as_one' ); ?>" <?php echo (($show_as_one) ? 'checked="checked"' : '' ); ?> type="checkbox" value="1" />
		<label for="<?php echo $this->get_field_id( 'show_as_one' ); ?>"><?php _e( 'Als eine Karte anzeigen', 'psmaps' ); ?></label>
		<select class="wdg_zoom" name="<?php echo $this->get_field_name( 'zoom' ); ?>">
		<?php foreach ($zoom_items as $zidx => $zlbl) { ?>
			<option value="<?php echo $zidx?>" <?php echo (($zidx == $zoom) ? 'selected="selected"' : '' );?>><?php echo $zlbl;?></option>
		<?php } ?>
		</select>
	<br />
	<input id="<?php echo $this->get_field_id( 'show_map' ); ?>" name="<?php echo $this->get_field_name( 'show_map' ); ?>" <?php echo (($show_map) ? 'checked="checked"' : '' ); ?> type="checkbox" value="1" />
		<label for="<?php echo $this->get_field_id( 'show_map' ); ?>"><?php _e( 'Karte anzeigen', 'psmaps' ); ?></label>
	<br />
	<input id="<?php echo $this->get_field_id( 'show_markers' ); ?>" name="<?php echo $this->get_field_name( 'show_markers' ); ?>" <?php echo (($show_markers) ? 'checked="checked"' : '' ); ?> type="checkbox" value="1" />
		<label for="<?php echo $this->get_field_id( 'show_markers' ); ?>"><?php _e( 'Markierungsliste anzeigen', 'psmaps' ); ?></label>
	<br />
	<input id="<?php echo $this->get_field_id( 'show_images' ); ?>" name="<?php echo $this->get_field_name( 'show_images' ); ?>" <?php echo (($show_images) ? 'checked="checked"' : '' ); ?> type="checkbox" value="1" />
		<label for="<?php echo $this->get_field_id( 'show_images' ); ?>"><?php _e( 'Bilderstreifen anzeigen', 'psmaps' ); ?></label>
	<br />
	<input id="<?php echo $this->get_field_id( 'show_posts' ); ?>" name="<?php echo $this->get_field_name( 'show_posts' ); ?>" <?php echo (($show_posts) ? 'checked="checked"' : '' ); ?> type="checkbox" value="1" />
		<label for="<?php echo $this->get_field_id( 'show_posts' ); ?>"><?php _e( 'Beiträge in Markern anzeigen', 'psmaps' ); ?></label>
</p>

<script type="text/javascript">
var _agmBound = false;

(function($){
$(function() {

function disable ($el) {
	$el.attr( 'disabled', true);
	$el.val( '' );
}

function toggleTargets (e) {
	var $parent = $(this).parent( 'p.agm_widget_query_options' );
	var $idSwitch = $parent.find( 'input.map_id_switch' );
	var $idTarget = $parent.find( 'select.map_id_target' );
	var $customSwitch = $parent.find( 'input.custom_switch' );
	var $customTarget = $parent.find( 'input.custom_target' );
	var $piTarget = $parent.find( 'input.post_indexer_target' );

	if ($idSwitch.is( ':checked' )) $idTarget.attr( 'disabled', false);
	else disable($idTarget);

	if ($customSwitch.is( ':checked' )) {
		$customTarget.attr( 'disabled', false);
		$piTarget.attr( 'disabled', false);
	} else {
		disable($customTarget);
		$piTarget.attr( 'disabled', true);
	}
}

function toggleZoom (e) {
	var $me = $(this);
	var $parent = $me.parent( 'p' );
	if ($me.is(":checked")) {
		$parent.find(".wdg_zoom").show().attr( 'disabled', false);
	} else {
		$parent.find(".wdg_zoom").hide().attr( 'disabled', true).val( '' );
	}
}

function init () {
	$( 'body' ).on( 'click', 'p.agm_widget_query_options input:radio', toggleTargets);
	$( 'p.agm_widget_query_options select.map_id_target' ).each(function() {
		if (!$(this).val()) $(this).attr( 'disabled', true);
	});
	$( 'p.agm_widget_query_options input.custom_target' ).each(function() {
		if (!$(this).val()) $(this).attr( 'disabled', true);
	});
	$( 'body' ).on( 'change', 'input.show_as_one', toggleZoom);
	$( 'input.show_as_one' ).each(function () {
		var $me = $(this);
		if ($me.is(":checked")) $me.parent("p").find(".wdg_zoom").show().attr( 'disabled', false);
		else $me.parent("p").find(".wdg_zoom").hide().attr( 'disabled', true).val( '' );
	});
	_agmBound = true;
}

if (!_agmBound) init();

});
})(jQuery);
</script>