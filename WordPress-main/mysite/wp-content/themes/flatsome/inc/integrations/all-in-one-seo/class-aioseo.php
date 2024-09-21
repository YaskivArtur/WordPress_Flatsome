<?php
/**
 * All in one SEO integration
 *
 * @author      UX Themes
 * @package     Flatsome\Integrations
 * @since       3.16.0
 */

namespace Flatsome\Integrations;

defined( 'ABSPATH' ) || exit;

/**
 * Class AIOSEO
 *
 * @package Flatsome\Integrations
 */
class AIOSEO {

	/**
	 * Static instance
	 *
	 * @var AIOSEO $instance
	 */
	private static $instance = null;

	/**
	 * AIOSEO constructor.
	 */
	private function __construct() {
		add_action( 'wp', [ $this, 'integrate' ] );
	}

	/**
	 * Setting based integration.
	 */
	public function integrate() {
		// Breadcrumb.
		if ( get_theme_mod( 'aioseo_breadcrumb' ) ) {
			remove_action( 'flatsome_breadcrumb', 'woocommerce_breadcrumb', 20 );
			add_action( 'flatsome_breadcrumb', [ $this, 'aioseo_breadcrumb' ], 20 );

			add_filter( 'aioseo_breadcrumbs_separator', [ $this, 'wrap_crumb_separator' ] );
		}
	}

	/**
	 * AIOSEO breadcrumbs.
	 */
	public function aioseo_breadcrumb() {
		if ( function_exists( 'aioseo_breadcrumbs' ) ) {
			$classes = array(
				'breadcrumbs',
				get_theme_mod( 'breadcrumb_case', 'uppercase' ),
			);
			$classes = implode( ' ', $classes );

			echo '<nav id="breadcrumbs" class="' . esc_attr( $classes ) . '">';
			aioseo_breadcrumbs();
			echo '</nav>';
		}
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
	 * @return AIOSEO The object instance
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}

AIOSEO::get_instance();
