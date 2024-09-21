<?php

/**
 * Get category product block.
 *
 * @param WC_Product $product Product.
 *
 * @return int|false Block ID or false if none assigned.
 */
function flatsome_get_cat_product_block( $product ) {

	// Primary based.
	$primary_term_id = apply_filters( 'flatsome_product_block_primary_term_id', false, $product );

	if ( $primary_term_id ) {
		$cat_meta = get_term_meta( $primary_term_id, 'cat_meta' );
		if ( ! empty( $cat_meta ) && ! empty( $cat_meta[0]['cat_product_block'] ) ) {
			return $cat_meta[0]['cat_product_block'];
		}
	}

	// Regular.
	$terms = wc_get_product_terms( $product->get_Id(), 'product_cat', apply_filters(
		'flatsome_product_block_product_terms_args',
		array(
			'orderby' => 'parent',
			'order'   => 'DESC',
		)
	) );

	if ( $terms ) {
		foreach ( $terms as $term ) {
			$cat_meta = get_term_meta( $term->term_id, 'cat_meta' );
			if ( ! empty( $cat_meta ) && ! empty( $cat_meta[0]['cat_product_block'] ) ) {
				return $cat_meta[0]['cat_product_block'];
			}
		}
	}

	return false;
}

/**
 * Get single product block.
 *
 * @param WC_Product $product Product.
 *
 * @return int|false Block ID or false if none assigned.
 */
function flatsome_get_single_product_block( $product ) {
	global $wc_cpdf;

	$block = $wc_cpdf->get_value( $product->get_Id(), '_product_block' );

	return $block ? $block : false;
}

/**
 * Retrieves product block data if one is assigned in any scope per priority.
 *
 * @param string|int $product_id Product ID.
 *
 * @return array|false Block data or false if none assigned.
 */
function flatsome_product_block( $product_id ) {

	static $cache;
	if ( ! is_array( $cache ) ) $cache = array();
	if ( array_key_exists( $product_id, $cache ) ) {
		return $cache[ $product_id ];
	}

	$product = wc_get_product( $product_id );

	if ( ! is_a( $product, 'WC_Product' ) ) {
		$cache[ $product_id ] = false;
		return false;
	}

	// Start collecting.
	$block = false;
	$scope = '';

	if ( 'custom' === get_theme_mod( 'product_layout' ) && get_theme_mod( 'product_custom_layout' ) ) {
		$block = get_theme_mod( 'product_custom_layout' );
		$scope = 'global';
	}

	if ( $cat = flatsome_get_cat_product_block( $product ) ) {
		$block = $cat;
		$scope = 'category';
	}

	if ( $single = flatsome_get_single_product_block( $product ) ) {
		$block = $single;
		$scope = 'single';
	}

	$filtered = apply_filters( 'flatsome_product_block', $block, $product );

	if ( $filtered != $block ) {
		$scope = 'filter';
	}

	$block_data = $filtered
		? array(
			'id'    => flatsome_get_block_id( $filtered ),
			'scope' => $scope,
		)
		: false;

	$cache[ $product_id ] = $block_data;

	return $block_data;
}

/**
 * Retrieve a list of attribute taxonomies.
 *
 * @param array|string $args Optional. Array or string of arguments.
 *
 * @return array|false List of attributes taxonomies matching defaults or `$args`.
 */
function flatsome_get_product_attribute_taxonomies_list_by_id( $args = '' ) {
	$defaults = array(
		'option_none' => '',
	);

	$parsed_args = wp_parse_args( $args, $defaults );

	$attributes = array();

	if ( $parsed_args['option_none'] ) {
		$attributes = array( '' => $parsed_args['option_none'] );
	}

	$taxonomies = wc_get_attribute_taxonomies();

	if ( $taxonomies ) {
		foreach ( wc_get_attribute_taxonomies() as $attribute ) {
			$attributes[ $attribute->attribute_id ] = $attribute->attribute_label;
		}
	}

	return $attributes;
}
