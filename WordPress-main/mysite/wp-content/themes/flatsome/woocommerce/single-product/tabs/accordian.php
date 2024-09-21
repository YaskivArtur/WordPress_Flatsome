<?php
/**
 * Single Product tabs
 *
 * @author           WooThemes
 * @package          WooCommerce/Templates
 * @version          2.0.0
 * @flatsome-version 3.17.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * Filter tabs and allow third parties to add their own
 *
 * Each tab is an array containing title, callback and priority.
 * @see woocommerce_default_product_tabs()
 */
$product_tabs = apply_filters( 'woocommerce_product_tabs', array() );

$product_display = get_theme_mod( 'product_display', 'tabs' );

if ( ! empty( $product_tabs ) ) : ?>
<div class="product-page-accordian">
	<div class="accordion">
		<?php foreach ( $product_tabs as $key => $product_tab ) : ?>
		<?php $is_open = $key === array_key_first( $product_tabs ) && $product_display !== 'accordian-collapsed'; ?>
		<div id="accordion-<?php echo esc_attr( $key ) ?>" class="accordion-item">
			<a id="accordion-<?php echo esc_attr( $key ) ?>-label" class="accordion-title plain<?php echo $is_open ? ' active' : ''; ?>" aria-expanded="<?php echo $is_open ? 'true' : 'false'; ?>" aria-controls="accordion-<?php echo esc_attr( $key ) ?>-content" href="<?php echo esc_url( '#accordion-item-' . $key ); ?>">
				<button class="toggle" aria-label="<?php echo esc_attr__( 'Toggle', 'flatsome' ); ?>"><i class="icon-angle-down"></i></button>
				<?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', $product_tab['title'], $key ); ?>
			</a>
			<div id="accordion-<?php echo esc_attr( $key ) ?>-content" class="accordion-inner"<?php echo $is_open ? ' style="display: block;"' : ''; ?> aria-labelledby="accordion-<?php echo esc_attr( $key ) ?>-label">
				<?php
				if ( isset( $product_tab['callback'] ) ) {
					call_user_func( $product_tab['callback'], $key, $product_tab );
				}
				?>
			</div>
		</div>
		<?php endforeach; ?>
	</div>
</div>
<?php endif; ?>
