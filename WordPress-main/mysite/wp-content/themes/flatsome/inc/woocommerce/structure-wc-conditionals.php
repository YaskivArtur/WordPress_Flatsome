<?php

/**
 * Validates whether the WC Cart instance is available in the request.
 *
 * @return bool
 */
function flatsome_is_wc_cart_available() {
	if ( ! function_exists( 'WC' ) ) return false;
	return WC() instanceof \WooCommerce && WC()->cart instanceof \WC_Cart;
}

/**
 * Verifies whether the mini cart can be revealed or not.
 *
 * @return bool
 */
function flatsome_is_mini_cart_reveal() {
	return ( 'yes' !== get_option( 'woocommerce_cart_redirect_after_add' ) && 'link' !== get_theme_mod( 'header_cart_style', 'dropdown' ) && get_theme_mod( 'cart_dropdown_show', 1 ) );
}
