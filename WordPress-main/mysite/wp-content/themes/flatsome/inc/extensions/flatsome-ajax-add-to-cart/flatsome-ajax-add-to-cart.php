<?php
/**
 * Flatsome Ajax add to cart extension.
 *
 * @package    Flatsome/Extensions
 * @since      3.17.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * To be enqueued script.
 */
function flatsome_ajax_add_to_cart_script() {
	wp_enqueue_script(
		'flatsome-ajax-add-to-cart-frontend',
		get_template_directory_uri() . '/assets/js/extensions/flatsome-ajax-add-to-cart-frontend.js',
		array( 'jquery' ),
		wp_get_theme( get_template() )->get( 'Version' ),
		true
	);
}

add_action( 'wp_enqueue_scripts', 'flatsome_ajax_add_to_cart_script' );

/**
 * Single product ajax add to cart.
 */
function flatsome_ajax_add_to_cart() {
	$product_id = absint( isset( $_POST['add-to-cart'] ) ? $_POST['add-to-cart'] : 0 );

	ob_start();
	wc_print_notices();
	$notices = ob_get_clean();

	ob_start();
	woocommerce_mini_cart();
	$mini_cart = ob_get_clean();

	$data = array(
		'product_url' => get_permalink( $product_id ),
		'notices'     => $notices,
		'fragments'   => apply_filters(
			'woocommerce_add_to_cart_fragments',
			array(
				'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>',
			)
		),
		'cart_hash'   => WC()->cart->get_cart_hash(),
	);

	wp_send_json( $data );
}


add_action( 'wp_ajax_flatsome_ajax_add_to_cart', 'flatsome_ajax_add_to_cart' );
add_action( 'wp_ajax_nopriv_flatsome_ajax_add_to_cart', 'flatsome_ajax_add_to_cart' );
