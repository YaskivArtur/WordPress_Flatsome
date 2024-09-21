<?php
/**
 * Product small content.
 *
 * @package          Flatsome/WooCommerce/Templates
 * @flatsome-version 3.17.0
 */

global $product;
?>
<li>
	<a href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>" title="<?php echo esc_attr( $product->get_title() ); ?>">
		<?php echo $product->get_image( 'woocommerce_gallery_thumbnail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<span class="product-title"><?php echo $product->get_title(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
	</a>
	<?php if ( ! empty( $show_rating ) ) echo wc_get_rating_html( $product->get_average_rating(), $product->get_rating_count() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<?php echo $product->get_price_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</li>
