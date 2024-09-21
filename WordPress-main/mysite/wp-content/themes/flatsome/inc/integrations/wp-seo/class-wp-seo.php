<?php
/**
 * WP SEO integration
 *
 * @author      UX Themes
 * @package     Flatsome\Integrations
 * @since       3.7.0
 */

namespace Flatsome\Integrations;

defined( 'ABSPATH' ) || exit;

/**
 * Class WP_Seo
 *
 * @package Flatsome\Integrations
 */
class WP_Seo {

	/**
	 * Static instance
	 *
	 * @var WP_Seo $instance
	 */
	private static $instance = null;

	/**
	 * WP_Seo constructor.
	 */
	private function __construct() {
		add_action( 'wp', [ $this, 'integrate' ] );
	}

	/**
	 * Setting based integration.
	 */
	public function integrate() {
		// Primary term.
		if ( get_theme_mod( 'wpseo_primary_term' ) ) {
			add_filter( 'flatsome_woocommerce_shop_loop_category', [ $this, 'get_primary_term' ], 10, 2 );
			add_filter( 'woocommerce_product_categories_widget_main_term', [ $this, 'make_primary_term_current_category' ] );
		}
		if ( get_theme_mod( 'wpseo_manages_product_layout_priority' ) ) {
			add_filter( 'flatsome_product_block_primary_term_id', [ $this, 'get_primary_term_id' ], 10, 2 );
		}
		// Breadcrumb.
		if ( get_theme_mod( 'wpseo_breadcrumb' ) ) {
			remove_action( 'flatsome_breadcrumb', 'woocommerce_breadcrumb', 20 );
			add_action( 'flatsome_breadcrumb', [ $this, 'yoast_breadcrumb' ], 20, 2 );

			// Manipulate last crumb.
			if ( is_woocommerce_activated() && get_theme_mod( 'wpseo_breadcrumb_remove_last', 1 ) && apply_filters( 'flatsome_wpseo_breadcrumb_remove_last', is_product() ) ) {
				add_filter( 'wpseo_breadcrumb_links', [ $this, 'remove_last_crumb' ] );
				add_filter( 'wpseo_breadcrumb_single_link', [ $this, 'add_link_to_last_crumb' ], 10, 2 );
			}

			add_filter( 'wpseo_breadcrumb_separator', [ $this, 'wrap_crumb_separator' ] );
		}
	}

	/**
	 * Retrieve primary product term, set through YOAST.
	 *
	 * @param string      $term    The original term string.
	 * @param \WC_Product $product Product.
	 *
	 * @return string
	 */
	public function get_primary_term( $term, $product ) {
		if ( function_exists( 'yoast_get_primary_term' ) ) {
			$primary_term = yoast_get_primary_term( 'product_cat', $product->get_Id() );
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
	 * Retrieve primary product term ID, set through YOAST.
	 *
	 * @param string      $term    The original term string.
	 * @param \WC_Product $product Product.
	 *
	 * @return int|string
	 */
	public function get_primary_term_id( $term, $product ) {
		if ( function_exists( 'yoast_get_primary_term_id' ) ) {
			$primary_term_id = yoast_get_primary_term_id( 'product_cat', $product->get_Id() );
		}

		if ( ! empty( $primary_term_id ) ) {
			return $primary_term_id;
		}

		return $term;
	}

	/**
	 * Yoast breadcrumbs.
	 *
	 * @param string|array $class   One or more classes to add to the class list.
	 * @param bool         $display Whether to display the breadcrumb (true) or return it (false).
	 */
	public function yoast_breadcrumb( $class = '', $display = true ) {
		if ( function_exists( 'yoast_breadcrumb' ) ) {
			$classes   = is_array( $class ) ? $class : array_map( 'trim', explode( ' ', $class ) );
			$classes[] = 'yoast-breadcrumb';
			$classes[] = 'breadcrumbs';
			$classes[] = get_theme_mod( 'breadcrumb_case', 'uppercase' );
			$classes   = array_unique( array_filter( $classes ) );
			$classes   = implode( ' ', $classes );

			yoast_breadcrumb( '<nav id="breadcrumbs" class="' . esc_attr( $classes ) . '">', '</nav>', $display );
		}
	}

	/**
	 * Removes last crumb in the crumbs array.
	 *
	 * @param array $crumbs The crumbs array.
	 *
	 * @return mixed
	 */
	public function remove_last_crumb( $crumbs ) {
		if ( count( $crumbs ) > 1 ) {
			array_pop( $crumbs );
		}

		return $crumbs;
	}

	/**
	 * Adds a link to last crumb, use in conjunction with remove_last_crumb()
	 *
	 * @param string $output The output string.
	 * @param array  $crumb  The link array.
	 *
	 * @return string
	 */
	public function add_link_to_last_crumb( $output, $crumb ) {
		$output  = '<a property="v:title" rel="v:url" href="' . $crumb['url'] . '" >';
		$output .= $crumb['text'];
		$output .= '</a>';

		return $output;
	}

	/**
	 * Wrap breadcrumb separator.
	 *
	 * @param string $separator Breadcrumbs separator.
	 *
	 * @return string
	 */
	public function wrap_crumb_separator( $separator ) {
		return '<span class="divider">' . $separator . '</span>';
	}

	/**
	 * Initializes the object and returns its instance.
	 *
	 * @return WP_Seo The object instance
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}

WP_Seo::get_instance();
