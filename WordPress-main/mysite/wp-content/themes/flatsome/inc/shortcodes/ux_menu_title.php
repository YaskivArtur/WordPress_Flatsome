<?php
/**
 * Registers the `ux_menu_title` shortcode.
 *
 * @package flatsome
 */

/**
 * Renders the `ux_menu_title` shortcode.
 *
 * @param array  $atts    An array of attributes.
 * @param string $content The shortcode content.
 * @param string $tag     The name of the shortcode, provided for context to enable filtering.
 *
 * @return string
 */
function flatsome_render_ux_menu_title_shortcode( $atts, $content, $tag ) {
	$atts = shortcode_atts(
		array(
			'visibility' => '',
			'class'      => '',
			'text'       => '',
		),
		$atts,
		$tag
	);

	$classes = array( 'ux-menu-title', 'flex' );

	if ( ! empty( $atts['class'] ) )      $classes[] = $atts['class'];
	if ( ! empty( $atts['visibility'] ) ) $classes[] = $atts['visibility'];

	ob_start();

	?>
	<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
		<?php echo esc_html( $atts['text'] ); ?>
	</div>
	<?php

	return ob_get_clean();
}
add_shortcode( 'ux_menu_title', 'flatsome_render_ux_menu_title_shortcode' );
