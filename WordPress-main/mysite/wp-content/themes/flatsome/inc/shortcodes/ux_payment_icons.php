<?php

function ux_payment_icons( $atts ) {
	extract( shortcode_atts( array(
		'link'       => '',
		'target'     => '',
		'rel'        => '',
		'icons'      => get_theme_mod( 'payment_icons', array( 'visa', 'paypal', 'stripe', 'mastercard', 'cashondelivery' ) ),
		'custom'     => get_theme_mod( 'payment_icons_custom' ),
		'class'      => '',
		'visibility' => '',
	), $atts ) );

	$classes = array( 'payment-icons', 'inline-block' );

	if ( $class ) $classes[] = $class;

	if ( $visibility ) $classes[] = $visibility;

	$classes = implode( ' ', $classes );

	$link_atts = array(
		'target' => $target,
		'rel'    => array( $rel ),
	);

	$link_start = $link ? '<a href="' . esc_url( $link ) . '"' . flatsome_parse_target_rel( $link_atts ) . '>' : '';
	$link_end   = $link ? '</a>' : '';

	// Get custom icons if set.
	if ( ! empty( $custom ) ) {
		return do_shortcode( '<div class="' . $classes . '">' . $link_start . flatsome_get_image( $custom ) . $link_end . '</div>' );
	} elseif ( empty( $icons ) ) {
		return false;
	}

	if ( ! is_array( $icons ) ) {
		$icons = explode( ',', $icons );
	}

	ob_start();

	echo '<div class="' . esc_attr( $classes ) . '">';
	echo $link_start; // phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped
	foreach ( $icons as $key => $value ) {
		echo '<div class="payment-icon">';
		get_template_part( 'assets/img/payment-icons/icon', $value . '.svg' );
		echo '</div>';
	}
	echo '</div>';
	echo $link_end; // phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped

	$content = ob_get_contents();
	ob_end_clean();

	return $content;
}

add_shortcode( 'ux_payment_icons', 'ux_payment_icons' );
