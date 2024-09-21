<?php
/**
 * Loop Rating
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/rating.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see              https://docs.woocommerce.com/document/template-structure/
 * @package          WooCommerce\Templates
 * @version          3.6.0
 * @flatsome-version 3.16.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;

if ( ! wc_review_ratings_enabled() ) {
	return;
}

$rating_count = $product->get_rating_count();
$review_count = $product->get_review_count();

echo flatsome_get_rating_html( $product->get_average_rating() ); // phpcs:ignore WordPress.Security.EscapeOutput

if ( $rating_count > 0 ) :
	if ( get_theme_mod( 'product_box_review_count' ) ) :
		if ( comments_open() ) :
			echo apply_filters( 'flatsome_loop_review_count_html', '<span class="review-count is-small op-7">(' . esc_html( $review_count ) . ')</span>', $product ); // phpcs:ignore WordPress.Security.EscapeOutput
		endif;
	endif;
endif;
