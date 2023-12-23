<div class="wrap">
	<h2><?php _e( 'Google Maps Plugin Optionen', 'psmaps' ); ?></h2>

	<?php $options = apply_filters( 'agm_google_maps-settings_form_options', '' ); ?>
	<form action="options.php" <?php echo $options; ?> method="post">
		<?php settings_fields( 'agm_google_maps' ); ?>
		<div class="vnav">
		<?php do_settings_sections( 'agm_google_maps_options_page' ); ?>
		</div>
		<p class="submit">
			<button name="Submit" class="button-primary"><?php _e( 'Änderungen speichern', 'psmaps' ); ?></button>
		</p>
	</form>
</div>

<script type="text/javascript">
jQuery(function () {
	// Set up contextual help inline triggers
	jQuery("[data-agm_contextual_trigger]").each(function () {
		var $me = jQuery(this),
			$target = jQuery($me.attr("data-agm_contextual_trigger"))
		;
		if (!$target.length) { return false; }
		$me.on("click", function () {
			jQuery("#contextual-help-link").trigger( "click" );
			$target.find("a").trigger( "click" );
			jQuery(window).scrollTop(0);
			return false;
		});
	});
});
</script>