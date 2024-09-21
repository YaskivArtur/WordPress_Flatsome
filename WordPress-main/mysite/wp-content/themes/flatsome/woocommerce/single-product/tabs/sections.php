<?php
/**
 * Single Product tabs
 *
 * @author           WooThemes
 * @package          WooCommerce/Templates
 * @version          2.0.0
 * @flatsome-version 3.16.0
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

if ( ! empty( $product_tabs ) ) : ?>
<div class="product-page-sections">
	<?php foreach ( $product_tabs as $key => $product_tab ) : ?>
	<div class="product-section">
	<div class="row">
		<div class="large-2 col pb-0 mb-0">
			 <h5 class="uppercase mt"><?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', $product_tab['title'], $key ); ?></h5>
		</div>

		<div class="large-10 col pb-0 mb-0">
			<div class="panel entry-content">
				<?php
				if ( isset( $product_tab['callback'] ) ) {
					call_user_func( $product_tab['callback'], $key, $product_tab );
				}
				?>
			</div>
		</div>
	</div>
	</div>
	<?php endforeach; ?>
</div>
<?php endif; ?>
