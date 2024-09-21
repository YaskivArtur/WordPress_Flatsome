<?php

// [gap]
function flatsome_gap_shortcode( $atts, $content = null ) {
	extract( $atts = shortcode_atts( array(
		'_id'        => 'gap-' . rand(),
		'height'     => '30px',
		'height__sm' => '',
		'height__md' => '',
		'class'      => '',
		'visibility' => '',
	), $atts ) );

	$classes = array( 'gap-element', 'clearfix' );

	if ( $class ) {
		$classes[] = $class;
	}
	if ( $visibility ) {
		$classes[] = $visibility;
	}

	$classes = implode( ' ', $classes );

	$args = array(
		'height' => array(
			'selector' => '',
			'property' => 'padding-top',
		),
	);

	ob_start();
	?>
	<div id="<?php echo esc_attr( $_id ); ?>" class="<?php echo esc_attr( $classes ); ?>" style="display:block; height:auto;">
		<?php echo ux_builder_element_style_tag( $_id, $args, $atts ); // phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped ?>
	</div>
	<?php

	return ob_get_clean();
}

add_shortcode( 'gap', 'flatsome_gap_shortcode' );
