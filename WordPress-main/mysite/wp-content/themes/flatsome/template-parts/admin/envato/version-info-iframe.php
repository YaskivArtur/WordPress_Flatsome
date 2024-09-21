<?php
/**
 * Version info.
 *
 * @package          Flatsome\Templates
 * @flatsome-version 3.16.0
 */

iframe_header();
?>
<div id="wpwrap" class="flatsome-panel" style="text-align:center;">
	<a href="https://themeforest.net/item/flatsome-multipurpose-responsive-woocommerce-theme/5484319#item-description__change-log" style="display:inline-block;" target="_blank" rel="noopener noreferrer">
		<div class="wp-badge fl-badge">
			<?php /* translators: 1: Version. */ ?>
			<?php echo sprintf( __( 'Version %s', 'flatsome' ), $version ); ?>
		</div>
		<div style="margin-top:8px;">
			<?php echo __( 'Read change log here', 'flatsome' ); ?>
		</div>
	</a>
</div>

<?php iframe_footer(); ?>
