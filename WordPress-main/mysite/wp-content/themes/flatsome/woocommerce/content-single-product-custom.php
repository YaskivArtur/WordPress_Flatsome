<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * Override this template by copying it to yourtheme/woocommerce/content-single-product.php
 *
 * @author           WooThemes
 * @package          WooCommerce/Templates
 * @version          3.0.0
 * @flatsome-version 3.16.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

$layout       = flatsome_product_block( get_the_ID() );
$layout_id    = $layout['id'];
$layout_scope = $layout['scope'];

$classes = array(
	'custom-product-page',
	'ux-layout-' . $layout_id,
	'ux-layout-scope-' . $layout_scope,
)


?>
<div class="container">
	<?php
	/**
	 * Hook Woocommerce_before_single_product.
	 *
	 * @hooked wc_print_notices - 10
	 */
	do_action( 'woocommerce_before_single_product' );
	do_action( 'flatsome_before_single_product_custom' );
	if ( post_password_required() ) {
		echo get_the_password_form(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		return;
	}
	?>
</div>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( '', $product ); ?>>

	<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">

		<?php echo flatsome_apply_shortcode( 'block', array( 'id' => $layout_id ) ); ?>
			<div id="product-sidebar" class="mfp-hide">
				<div class="sidebar-inner">
					<?php
					do_action( 'flatsome_before_product_sidebar' );
					/**
					 * The woocommerce_sidebar hook
					 *
					 * @hooked woocommerce_get_sidebar - 10
					 */
					if ( is_active_sidebar( 'product-sidebar' ) ) {
						dynamic_sidebar( 'product-sidebar' );
					} elseif ( is_active_sidebar( 'shop-sidebar' ) ) {
						dynamic_sidebar( 'shop-sidebar' );
					}
					?>
				</div>
			</div>

	</div>

	<?php do_action( 'woocommerce_after_single_product' ); ?>

</div>
