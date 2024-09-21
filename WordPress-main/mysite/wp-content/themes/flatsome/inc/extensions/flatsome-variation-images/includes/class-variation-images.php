<?php
/**
 * Additional variation images main class.
 *
 * @package Flatsome\Extensions
 */

namespace Flatsome\Extensions;

defined( 'ABSPATH' ) || exit;

/**
 * Class Variation_Images
 *
 * @package Flatsome\Extensions
 */
final class Variation_Images {
	/**
	 * The single instance of the class.
	 *
	 * @var Variation_Images
	 */
	protected static $instance = null;

	/**
	 * Holds extension version.
	 *
	 * @var string
	 */
	public $version;

	/**
	 * Variation_Images constructor.
	 */
	private function __construct() {
		$theme         = wp_get_theme( get_template() );
		$this->version = $theme->get( 'Version' );

		$this->includes();

		add_action( 'init', array( $this, 'init' ) );
	}

	/**
	 * Include core files.
	 */
	public function includes() {
		if ( is_admin() ) {
			require_once dirname( __FILE__ ) . '/class-variation-images-admin.php';
		}

		require_once dirname( __FILE__ ) . '/class-variation-images-frontend.php';
	}

	/**
	 * Initialize.
	 */
	public function init() {
		if ( is_admin() ) {
			$this->admin();
		}

		if ( ! is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
			$this->frontend();
		}
	}

	/**
	 * Main instance.
	 *
	 * @return Variation_Images
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Get additional images IDs by variation ID.
	 *
	 * @param int $variation_id variation ID.
	 *
	 * @return mixed The value of the meta field, string of ID's.
	 *               False for an invalid `$post_id` (non-numeric, zero, or negative value).
	 *               An empty string if a valid but non-existing post ID is passed.
	 */
	public function get_image_ids( $variation_id ) {
		return get_post_meta( $variation_id, '_ux_additional_variation_images', true );
	}

	/**
	 * Instance of admin.
	 *
	 * @return Variation_Images_Admin
	 */
	public function admin() {
		return Variation_Images_Admin::instance();
	}

	/**
	 * Instance of frontend.
	 *
	 * @return Variation_Images_Frontend
	 */
	public function frontend() {
		return Variation_Images_Frontend::instance();
	}
}

/**
 * Main instance.
 *
 * @return Variation_Images
 */
function variation_images() {
	return Variation_Images::instance();
}

