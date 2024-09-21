<?php
/**
 * Shop category filter button template
 *
 * @package          Flatsome/WooCommerce/Templates
 * @flatsome-version 3.16.0
 */

$layout = get_theme_mod( 'category_sidebar', 'left-sidebar' );
if ( 'none' === $layout || ( get_theme_mod( 'html_shop_page_content' ) && ! is_product_category() && ! is_product_tag() && ! is_search() ) ) {
	return;
}

$after = 'data-visible-after="true"';
$class = 'show-for-medium';
if ( 'off-canvas' === $layout ) {
	$after = '';
	$class = '';
}

$custom_filter_text = get_theme_mod( 'category_filter_text' );
$filter_text        = $custom_filter_text ? $custom_filter_text : __( 'Filter', 'woocommerce' );
?>
<div class="category-filtering category-filter-row <?php echo esc_attr( $class ); ?>">
	<a href="#" data-open="#shop-sidebar" <?php echo wp_kses( $after, array( 'data-visible-after' => array() ) ); ?> data-pos="left" class="filter-button uppercase plain">
		<i class="icon-equalizer"></i>
		<strong><?php echo esc_html( $filter_text ); ?></strong>
	</a>
	<div class="inline-block">
		<?php the_widget( 'WC_Widget_Layered_Nav_Filters', array( 'title' => '' ) ); ?>
	</div>
</div>
