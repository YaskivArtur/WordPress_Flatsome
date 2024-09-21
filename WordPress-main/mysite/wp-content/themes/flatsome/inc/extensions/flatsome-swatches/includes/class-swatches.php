<?php
/**
 * Swatches main class.
 *
 * @package Flatsome\Extensions
 */

namespace Flatsome\Extensions;

defined( 'ABSPATH' ) || exit;

/**
 * Class Swatches
 *
 * @package Flatsome\Extensions
 */
final class Swatches {
	/**
	 * The single instance of the class.
	 *
	 * @var Swatches
	 */
	protected static $instance = null;

	/**
	 * Custom attribute types.
	 *
	 * @var array
	 */
	private $types;

	/**
	 * Holds extension version.
	 *
	 * @var string
	 */
	public $version;

	/**
	 * Swatches constructor.
	 */
	private function __construct() {
		$this->types = array(
			'ux_color' => esc_html__( 'UX Color', 'flatsome' ),
			'ux_image' => esc_html__( 'UX Image', 'flatsome' ),
			'ux_label' => esc_html__( 'UX Label', 'flatsome' ),
		);

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
			require_once dirname( __FILE__ ) . '/class-swatches-admin.php';
		}

		require_once dirname( __FILE__ ) . '/class-swatches-frontend.php';
	}

	/**
	 * Initialize.
	 */
	public function init() {
		add_filter( 'product_attributes_type_selector', array( $this, 'add_attribute_types' ) );

		if ( is_admin() ) {
			$this->admin();
		}

		if ( ! is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
			$this->frontend();
		}
	}

	/**
	 * Add extra custom attribute types.
	 *
	 * @param array $types The default types.
	 *
	 * @return array
	 */
	public function add_attribute_types( $types ) {
		$types = array_merge( $types, $this->types );

		return $types;
	}

	/**
	 * Get custom attribute types.
	 *
	 * @return array
	 */
	public function get_attribute_types() {
		return $this->types;
	}

	/**
	 * Get product attribute option data by ID.
	 *
	 * @param string|int $id The ID.
	 *
	 * @return false|mixed
	 */
	public function get_attribute_option( $id ) {
		return get_option( "flatsome_product_attribute-{$id}" );
	}

	/**
	 * Get product attribute option data by name.
	 *
	 * @param string $attribute The attribute name.
	 *
	 * @return false|mixed
	 */
	public function get_attribute_option_by_name( $attribute ) {
		$id = wc_attribute_taxonomy_id_by_name( $attribute );

		return get_option( "flatsome_product_attribute-{$id}" );
	}

	/**
	 * Get attribute's properties.
	 *
	 * @param string $taxonomy Taxonomy.
	 *
	 * @return object
	 */
	public function get_attribute( $taxonomy ) {
		global $wpdb;

		$attr = substr( $taxonomy, 3 );
		$attr = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}woocommerce_attribute_taxonomies WHERE attribute_name = %s;", $attr ) );

		return $attr;
	}

	/**
	 * Parses the term value specifically for ux_color. Checks and
	 * returns parsed data for single and dual color value(s).
	 *
	 * @param string $value The term meta value.
	 *
	 * @return string[]
	 */
	public function parse_ux_color_term_meta( $value ) {
		$data = array(
			'color'   => '',
			'color_2' => '',
			'class'   => '',
			'style'   => '',
		);

		$colors = explode( ',', $value );

		$data['color'] = $colors[0];

		if ( count( $colors ) > 1 ) {
			$data['color_2'] = $colors[1];
			$data['style']   = "--swatch-color: $colors[0]; --swatch-color-secondary: $colors[1];";
			$data['class']   = 'ux-swatch__color--dual-color';
		} else {
			$data['style'] = "--swatch-color: $colors[0]";
			$data['class'] = 'ux-swatch__color--single-color';
		}

		return $data;
	}

	/**
	 * Clears all cache.
	 */
	public function cache_clear() {
		global $wpdb;

		$wpdb->query( "DELETE FROM {$wpdb->options} WHERE `option_name` LIKE ('%\_transient\_flatsome\_swatches%');" );
		$wpdb->query( "DELETE FROM {$wpdb->options} WHERE `option_name` LIKE ('%\_transient\_timeout\_flatsome\_swatches%');" );
	}

	/**
	 * Main instance.
	 *
	 * @return Swatches
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Instance of admin.
	 *
	 * @return Swatches_Admin
	 */
	public function admin() {
		return Swatches_Admin::instance();
	}

	/**
	 * Instance of frontend.
	 *
	 * @return Swatches_Frontend
	 */
	public function frontend() {
		return Swatches_Frontend::instance();
	}
}

/**
 * Main instance.
 *
 * @deprecated 3.17 Use swatches()
 *
 * @return Swatches
 */
function flatsome_swatches() {
	_deprecated_function( __FUNCTION__, '3.17', 'swatches' );

	return swatches();
}

/**
 * Main instance.
 *
 * @return Swatches
 */
function swatches() {
	return Swatches::instance();
}

