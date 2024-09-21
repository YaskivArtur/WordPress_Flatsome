<?php
/**
 * SEOPress integration
 *
 * @author      UX Themes
 * @package     Flatsome\Integrations
 * @since       3.16.0
 */

namespace Flatsome\Integrations;

defined( 'ABSPATH' ) || exit;

/**
 * Class WP_SEOPress
 *
 * @package Flatsome\Integrations
 */
class WP_SEOPress {

	/**
	 * Static instance
	 *
	 * @var WP_SEOPress $instance
	 */
	private static $instance = null;

	/**
	 * WP_SEOPress constructor.
	 */
	private function __construct() {
		add_action( 'wp', [ $this, 'integrate' ] );
	}

	/**
	 * Setting based integration.
	 */
	public function integrate() {
		// Breadcrumb.
		if ( get_theme_mod( 'wpseopress_breadcrumb' ) ) {
			add_action( 'wp_head', array( $this, 'add_css' ), 110 );
			remove_action( 'flatsome_breadcrumb', 'woocommerce_breadcrumb', 20 );
			add_action( 'flatsome_breadcrumb', [ $this, 'wpseopress_breadcrumb' ], 20 );
		}
	}

	/**
	 * WP_SEOPress breadcrumbs.
	 */
	public function wpseopress_breadcrumb() {
		if ( function_exists( 'seopress_display_breadcrumbs' ) ) {
			$classes = array(
				'seopress-breadcrumb',
				'breadcrumbs',
				get_theme_mod( 'breadcrumb_case', 'uppercase' ),
			);
			$classes = implode( ' ', $classes );

			echo '<span id="breadcrumbs" class="' . esc_attr( $classes ) . '">';
			seopress_display_breadcrumbs( true );
			echo '</span>';
		}
	}

	/**
	 * Add extension CSS.
	 */
	public function add_css() {
		ob_start();
		?>
		.seopress-breadcrumb nav ol.breadcrumb {
		margin: 0 0 0.5em;
		}
		.seopress-breadcrumb .breadcrumb li::after {
		position: relative;
		top: 0;
		opacity: .35;
		margin: 0 .3em;
		font-weight: 300;
		}
		.seopress-breadcrumb .breadcrumb li {
		margin-<?php echo is_rtl() ? 'right' : 'left'; ?>: 0;
		}
		<?php
		$output = ob_get_clean();

		if ( ! $output ) {
			return;
		}

		$css  = '<style id="flatsome-seopress-css">';
		$css .= $output;
		$css .= '</style>';

		echo flatsome_minify_css( $css ); // phpcs:ignore WordPress.Security.EscapeOutput
	}

	/**
	 * Initializes the object and returns its instance.
	 *
	 * @return WP_SEOPress The object instance
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}

WP_SEOPress::get_instance();
