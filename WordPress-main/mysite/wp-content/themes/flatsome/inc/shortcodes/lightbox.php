<?php

/**
 * [lightbox]
 */
function ux_lightbox( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'id'         => 'enter-id-here',
		'width'      => '650px',
		'padding'    => '20px',
		'class'      => '',
		'auto_open'  => false,
		'auto_timer' => '2500',
		'auto_show'  => '',
		'version'    => '1',
	), $atts ) );

	ob_start();
	?>
	<div id="<?php echo $id; ?>"
	     class="lightbox-by-id lightbox-content mfp-hide lightbox-white <?php echo $class; ?>"
	     style="max-width:<?php echo $width ?> ;padding:<?php echo $padding; ?>">
		<?php echo do_shortcode( $content ); ?>
	</div>
	<?php if ( $auto_open ) : ?>
		<script>
			// Auto open lightboxes
			jQuery(document).ready(function ($) {
				/* global flatsomeVars */
				'use strict'
				var cookieId = '<?php echo "lightbox_{$id}" ?>'
				var cookieValue = '<?php echo "opened_{$version}"; ?>'
				var timer = parseInt('<?php echo $auto_timer; ?>')

				// Auto open lightbox
				<?php if ( $auto_show == 'always' ) : ?>
				cookie(cookieId, false)
				<?php endif; ?>

				// Run lightbox if no cookie is set
				if (cookie(cookieId) !== cookieValue) {

					// Ensure closing off canvas
					setTimeout(function () {
						if (jQuery.fn.magnificPopup) jQuery.magnificPopup.close()
					}, timer - 350)

					// Open lightbox
					setTimeout(function () {
						$.loadMagnificPopup().then(function() {
							$.magnificPopup.open({
								midClick: true,
								removalDelay: 300,
								// closeBtnInside: flatsomeVars.lightbox.close_btn_inside,
								// closeMarkup: flatsomeVars.lightbox.close_markup,
								items: {
									src: '#<?php echo $id; ?>',
									type: 'inline'
								}
							})
						})
					}, timer)

					// Set cookie
					cookie(cookieId, cookieValue, 365)
				}
			})
		</script>
	<?php endif; ?>

	<?php
	$content = ob_get_contents();
	ob_end_clean();

	return $content;
}

add_shortcode( 'lightbox', 'ux_lightbox' );
