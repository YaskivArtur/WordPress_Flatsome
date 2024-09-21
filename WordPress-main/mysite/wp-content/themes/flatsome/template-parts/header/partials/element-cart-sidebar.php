<?php
/**
 * Cart sidebar element.
 *
 * @package          Flatsome\Templates
 * @flatsome-version 3.17.5
 */

if ( is_woocommerce_activated() && flatsome_is_wc_cart_available() ) {
	// Get Cart replacement for `catalog_mode`.
	if ( get_theme_mod( 'catalog_mode' ) ) {
		get_template_part( 'template-parts/header/partials/element', 'cart-replace' );
		return;
	}
	$custom_cart_content = get_theme_mod( 'html_cart_header' );
	$icon_style          = get_theme_mod( 'cart_icon_style' );
	$icon                = get_theme_mod( 'cart_icon', 'basket' );
	$cart_title          = get_theme_mod( 'header_cart_title', 1 );
	$cart_total          = get_theme_mod( 'header_cart_total', 1 );

	if ( fl_woocommerce_version_check( '7.8.0' ) && ! wp_script_is( 'wc-cart-fragments' ) ) {
		wp_enqueue_script( 'wc-cart-fragments' );
	}
?>
<li class="menu-item cart-item has-icon has-child">
	<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php _e( 'Cart', 'woocommerce' ); ?>" class="header-cart-link">
		<?php if ( $cart_total || $cart_title ) { ?>
		<span class="header-cart-title">
			<?php if ( $cart_title ) { _e( 'Cart', 'woocommerce' ); } ?>
			<?php /* divider */ if ( $cart_total && $cart_title ) { ?>/<?php } ?>
			<?php if ( $cart_total ) { ?>
				<span class="cart-price"><?php echo WC()->cart->get_cart_subtotal(); ?></span>
			<?php } ?>
		</span>
		<?php } ?>
	</a>

	<ul class="children">
		<li>
			<!-- Cart Sidebar Popup -->
			<div id="cart-popup" class="widget_shopping_cart">
				<div class="cart-popup-inner inner-padding">
						<div class="cart-popup-title text-center">
								<h4 class="uppercase"><?php _e('Cart', 'woocommerce'); ?></h4>
								<div class="is-divider"></div>
						</div>
						<div class="widget_shopping_cart_content">
								<?php woocommerce_mini_cart(); ?>
						</div>
						<?php if($custom_cart_content) {
							echo '<div class="header-cart-content">'.do_shortcode($custom_cart_content).'</div>'; }
						?>
						<?php do_action('flatsome_cart_sidebar'); ?>
				</div>
			</div>
		</li>
	</ul>

</li>
<?php } else {
	fl_header_element_error( 'woocommerce' );
}
?>
