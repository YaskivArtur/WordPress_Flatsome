<?php
/**
 * Registers the `ux_menu` shortcode.
 *
 * @package flatsome
 */

/**
 * Renders the `ux_menu` shortcode.
 *
 * @param array  $atts    An array of attributes.
 * @param string $content The shortcode content.
 * @param string $tag     The name of the shortcode, provided for context to enable filtering.
 *
 * @return string
 */
function flatsome_render_ux_menu_shortcode( $atts, $content, $tag ) {
	$atts = shortcode_atts(
		array(
			'visibility' => '',
			'class'      => '',
			'divider'    => '',
		),
		$atts,
		$tag
	);

	$classes = array( 'ux-menu', 'stack', 'stack-col', 'justify-start' );

	if ( ! empty( $atts['class'] ) )      $classes[] = $atts['class'];
	if ( ! empty( $atts['divider'] ) )    $classes[] = 'ux-menu--divider-' . $atts['divider'];
	if ( ! empty( $atts['visibility'] ) ) $classes[] = $atts['visibility'];

	wp_enqueue_script( 'css-vars-polyfill' );

	ob_start();

	?>
	<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
		<?php echo do_shortcode( $content ); ?>
	</div>
	<?php

	return ob_get_clean();
}
add_shortcode( 'ux_menu', 'flatsome_render_ux_menu_shortcode' );
