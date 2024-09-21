<?php
/**
 * Rank Math integration
 *
 * @author      UX Themes
 * @package     Flatsome\Integrations
 * @since       3.12.0
 */

namespace Flatsome\Integrations;

defined( 'ABSPATH' ) || exit;

/**
 * Class Rank_Math
 *
 * @package Flatsome\Integrations
 */
class Rank_Math {

	/**
	 * Static instance
	 *
	 * @var Rank_Math $instance
	 */
	private static $instance = null;

	/**
	 * Rank_Math constructor.
	 */
	private function __construct() {
		add_action( 'wp', [ $this, 'integrate' ] );
	}

	/**
	 * Setting based integration.
	 */
	public function integrate() {
		// Primary term.
		if ( get_theme_mod( 'rank_math_primary_term' ) ) {
			add_filter( 'flatsome_woocommerce_shop_loop_category', [ $this, 'get_primary_term' ], 10, 2 );
			add_filter( 'woocommerce_product_categories_widget_main_term', [ $this, 'make_primary_term_current_category' ] );
		}
		if ( get_theme_mod( 'rank_math_manages_product_layout_priority' ) ) {
			add_filter( 'flatsome_product_block_primary_term_id', [ $this, 'get_primary_term_id' ], 10, 2 );
		}
		// Breadcrumb.
		if ( get_theme_mod( 'rank_math_breadcrumb' ) ) {
			remove_action( 'flatsome_breadcrumb', 'woocommerce_breadcrumb', 20 );
			add_action( 'flatsome_breadcrumb', [ $this, 'rank_math_breadcrumb' ], 20 );
		}
	}

	/**
	 * Retrieve primary product term, set through Rank Math.
	 *
	 * @param string      $term    The original term string.
	 * @param \WC_Product $product Product.
	 *
	 * @return string
	 */
	public function get_primary_term( $term, $product ) {
		$primary_term   = '';
		$primary_cat_id = get_post_meta( $product->get_Id(), 'rank_math_primary_category', true );

		if ( $primary_cat_id ) {
			$product_cat  = get_term( $primary_cat_id, 'product_cat' );
			$primary_term = $product_cat->name;
		}

		if ( ! empty( $primary_term ) ) {
			return $primary_term;
		}

		return $term;
	}

	/**
	 * Make primary term the active term in category widget.

	 * @param  \WP_Term $term WooCommerce main term object.
	 *
	 * @return \WP_Term Term object.
	 */
	public function make_primary_term_current_category( $term ) {
		global $product;

		$primary_term_id = $this->get_primary_term_id( false, $product );

		if ( $primary_term_id ) {
			$_term = get_term_by( 'id', $primary_term_id, 'product_cat' );
			if ( $_term instanceof \WP_Term ) {
				return $_term;
			}
		}

		return $term;
	}

	/**
	 * Retrieve primary product term ID, set through Rank Math.
	 *
	 * @param string      $term    The original term string.
	 * @param \WC_Product $product Product.
	 *
	 * @return int|string
	 */
	public function get_primary_term_id( $term, $product ) {
		$primary_term_id = get_post_meta( $product->get_Id(), 'rank_math_primary_product_cat', true );

		if ( ! empty( $primary_term_id ) ) {
			return $primary_term_id;
		}

		return $term;
	}

	/**
	 * Rank Math breadcrumbs.
	 *
	 * @param string|array $class   One or more classes to add to the class list.
	 * @param bool         $display Whether to display the breadcrumb (true) or return it (false).
	 *
	 * @return string|void The breadcrumbs if $display set to false.
	 */
	public function rank_math_breadcrumb( $class = '', $display = true ) {
		if ( function_exists( 'rank_math_the_breadcrumbs' ) && function_exists( 'rank_math_get_breadcrumbs' ) ) {
			$args      = array();
			$classes   = is_array( $class ) ? $class : array_map( 'trim', explode( ' ', $class ) );
			$classes[] = 'rank-math-breadcrumb';
			$classes[] = 'breadcrumbs';
			$classes[] = get_theme_mod( 'breadcrumb_case', 'uppercase' );
			$classes   = array_unique( array_filter( $classes ) );
			$classes   = implode( ' ', $classes );

			$args['wrap_before'] = '<nav class="' . esc_attr( $classes ) . '"><p>';

			if ( ! $display ) {
				return rank_math_get_breadcrumbs( $args );
			}
			rank_math_the_breadcrumbs( $args );
		}
	}

	/**
	 * Initializes the object and returns its instance.
	 *
	 * @return Rank_Math The object instance
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}

Rank_Math::get_instance();

