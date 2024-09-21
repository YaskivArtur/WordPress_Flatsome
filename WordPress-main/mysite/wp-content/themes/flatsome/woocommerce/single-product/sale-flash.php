<?php
/**
 * Product loop sale flash
 *
 * @author           WooThemes
 * @package          WooCommerce/Templates
 * @version          1.6.4
 * @flatsome-version 3.16.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $product;

$badge_style = get_theme_mod( 'bubble_style', 'style1' );

// Fix deprecated.
if($badge_style == 'style1') $badge_style = 'circle';
if($badge_style == 'style2') $badge_style = 'square';
if($badge_style == 'style3') $badge_style = 'frame';

?>
<div class="badge-container is-larger absolute left top z-1">
<?php if ( get_theme_mod( 'sale_bubble', 1 ) && $product->is_on_sale() ) :
	$custom_text = get_theme_mod( 'sale_bubble_text' );
	$text        = $custom_text ? $custom_text : __( 'Sale!', 'woocommerce' );

	if ( get_theme_mod( 'sale_bubble_percentage' ) ) {
		$text = flatsome_presentage_bubble( $product, $text );
	}
	echo apply_filters( 'woocommerce_sale_flash', '<div class="callout badge badge-'.$badge_style.'"><div class="badge-inner secondary on-sale"><span class="onsale">' .  $text . '</span></div></div>', $post, $product ); ?>
<?php endif; ?>

<?php echo apply_filters( 'flatsome_product_labels', '', $post, $product, $badge_style); ?>
</div>
