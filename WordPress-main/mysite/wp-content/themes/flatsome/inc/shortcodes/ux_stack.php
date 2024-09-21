<?php
/**
 * Registers the `ux_stack` shortcode.
 *
 * @package flatsome
 */

/**
 * Renders the `ux_stack` shortcode.
 *
 * @param array  $atts    An array of attributes.
 * @param string $content The shortcode content.
 * @param string $tag     The name of the shortcode, provided for context to enable filtering.
 *
 * @return string
 */
function flatsome_render_ux_stack_shortcode( $atts, $content, $tag ) {
	$atts = shortcode_atts(
		array(
			'visibility'     => '',
			'class'          => '',
			'direction'      => 'row',
			'direction__sm'  => null,
			'direction__md'  => null,
			'distribute'     => 'start',
			'distribute__sm' => null,
			'distribute__md' => null,
			'align'          => 'stretch',
			'align__sm'      => null,
			'align__md'      => null,
			'gap'            => '0',
			'gap__sm'        => null,
			'gap__md'        => null,
		),
		$atts,
		$tag
	);

	$id      = 'stack-' . wp_rand();
	$classes = array( 'stack' );

	if ( ! empty( $atts['class'] ) )      $classes[] = $atts['class'];
	if ( ! empty( $atts['visibility'] ) ) $classes[] = $atts['visibility'];

	foreach ( array( '', 'sm', 'md' ) as $value ) {
		$class_prefix = $value ? "$value:" : '';
		$attr_suffix  = $value ? "__$value" : '';

		if ( $atts[ "direction{$attr_suffix}" ] ) {
			$classes[] = "{$class_prefix}stack-{$atts["direction{$attr_suffix}"]}";
		}

		if ( $atts[ "distribute{$attr_suffix}" ] ) {
			$classes[] = "{$class_prefix}justify-{$atts["distribute{$attr_suffix}"]}";
		}

		if ( $atts[ "align{$attr_suffix}" ] ) {
			$classes[] = "{$class_prefix}items-{$atts["align{$attr_suffix}"]}";
		}
	}

	wp_enqueue_script( 'css-vars-polyfill' );

	ob_start();

	?>
	<div id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
		<?php echo do_shortcode( $content ); ?>
		<?php
			echo ux_builder_element_style_tag(
				$id,
				array(
					'gap' => array(
						'selector' => '> *',
						'property' => '--stack-gap',
						'unit'     => 'rem',
					),
				),
				$atts
			);
		?>
	</div>
	<?php

	return ob_get_clean();
}
add_shortcode( 'ux_stack', 'flatsome_render_ux_stack_shortcode' );
add_shortcode( 'ux_stack_inner', 'flatsome_render_ux_stack_shortcode' );
add_shortcode( 'ux_stack_inner_1', 'flatsome_render_ux_stack_shortcode' );
add_shortcode( 'ux_stack_inner_2', 'flatsome_render_ux_stack_shortcode' );
add_shortcode( 'ux_stack_inner_4', 'flatsome_render_ux_stack_shortcode' );
add_shortcode( 'ux_stack_inner_5', 'flatsome_render_ux_stack_shortcode' );
add_shortcode( 'ux_stack_inner_6', 'flatsome_render_ux_stack_shortcode' );
add_shortcode( 'ux_stack_inner_7', 'flatsome_render_ux_stack_shortcode' );
add_shortcode( 'ux_stack_inner_8', 'flatsome_render_ux_stack_shortcode' );
add_shortcode( 'ux_stack_inner_9', 'flatsome_render_ux_stack_shortcode' );
