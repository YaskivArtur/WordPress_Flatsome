<?php
/**
 * Registers the `ux_html` shortcode.
 *
 * @package flatsome
 */

/**
 * Renders the `ux_html` shortcode.
 *
 * @param array  $atts    An array of attributes.
 * @param string $content The shortcode content.
 * @param string $tag     The name of the shortcode, provided for context to enable filtering.
 *
 * @return string
 */
function flatsome_render_ux_html_shortcode( $atts, $content, $tag ) {
	$atts = shortcode_atts(
		array(
			'visibility' => '',
			'class'      => '',
			'label'      => '',
		),
		$atts,
		$tag
	);

	$classes = array();

	if ( ! empty( $atts['class'] ) )      $classes[] = $atts['class'];
	if ( ! empty( $atts['visibility'] ) ) $classes[] = $atts['visibility'];

	if ( empty( $classes ) ) {
		return do_shortcode( $content );
	}

	ob_start(); ?>
		<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
				<?php echo do_shortcode( $content ); ?>
		</div>
	<?php

	return ob_get_clean();
}
add_shortcode( 'ux_html', 'flatsome_render_ux_html_shortcode' );
