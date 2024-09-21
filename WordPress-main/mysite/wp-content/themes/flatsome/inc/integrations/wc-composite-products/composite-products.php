<?php
/**
 * Composite Products integration
 *
 * @author      UX Themes
 * @package     Flatsome/Integrations
 * @see         https://woocommerce.com/products/composite-products/
 */

/**
 *  Composite products integration script.
 */
function flatsome_wc_composite_products_integration() {
	global $integrations_uri;
	wp_enqueue_script( 'flatsome-composite-products', $integrations_uri . '/wc-composite-products/composite-products.js', array( 'jquery', 'flatsome-js' ), 1.2, true );
}

add_action( 'wp_enqueue_scripts', 'flatsome_wc_composite_products_integration' );

/**
 * Disabled sticky add to cart on composite products type.
 *
 * @param bool       $enabled Default enabled.
 * @param WC_Product $product The product object.
 *
 * @return bool
 */
function flatsome_wc_composite_products_disable_sticky_add_to_cart( $enabled, $product ) {
	if ( $product->get_type() == 'composite' ) {
		return false;
	}

	return $enabled;
}

add_filter( 'flatsome_sticky_add_to_cart_enabled', 'flatsome_wc_composite_products_disable_sticky_add_to_cart', 10, 2 );
