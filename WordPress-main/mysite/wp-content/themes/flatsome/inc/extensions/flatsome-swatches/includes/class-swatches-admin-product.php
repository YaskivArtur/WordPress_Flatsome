<?php
/**
 * Swatches product admin class.
 *
 * @package Flatsome\Extensions
 */

namespace Flatsome\Extensions;

defined( 'ABSPATH' ) || exit;

/**
 * Class Swatches_Admin_Product
 *
 * @package Flatsome\Extensions
 */
class Swatches_Admin_Product {

	/**
	 * Swatches_Admin_Product constructor.
	 */
	public function __construct() {
		add_action( 'woocommerce_product_option_terms', array( $this, 'product_option_terms' ), 10, 3 );
	}

	/**
	 * Add selector for extra attribute types.
	 * html-product-attribute.php template
	 *
	 * @param object     $attribute_taxonomy Taxonomy.
	 * @param string|int $i                  Index.
	 * @param object     $attribute          Attribute.
	 */
	public function product_option_terms( $attribute_taxonomy, $i, $attribute ) {
		if ( ! array_key_exists( $attribute_taxonomy->attribute_type, swatches()->get_attribute_types() ) ) {
			return;
		}

		global $thepostid;

		$taxonomy_name = wc_attribute_taxonomy_name( $attribute_taxonomy->attribute_name );
		$product_id    = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : $thepostid; // phpcs:ignore WordPress.Security.NonceVerification
		?>

		<select multiple="multiple" data-placeholder="<?php esc_attr_e( 'Select terms', 'woocommerce' ); ?>" class="multiselect attribute_values wc-enhanced-select" name="attribute_values[<?php echo esc_attr( $i ); ?>][]">
			<?php
			$args      = array(
				'orderby'    => ! empty( $attribute_taxonomy->attribute_orderby ) ? $attribute_taxonomy->attribute_orderby : 'name',
				'hide_empty' => 0,
			);
			$all_terms = get_terms( $taxonomy_name, apply_filters( 'woocommerce_product_attribute_terms', $args ) );
			if ( $all_terms ) {
				foreach ( $all_terms as $term ) {
					echo '<option value="' . esc_attr( $term->term_id ) . '" ' . selected( has_term( absint( $term->term_id ), $taxonomy_name, $product_id ), true, false ) . '>' . esc_html( apply_filters( 'woocommerce_product_attribute_term_name', $term->name, $term ) ) . '</option>';
				}
			}
			?>
		</select>
		<button class="button plus select_all_attributes"><?php esc_html_e( 'Select all', 'woocommerce' ); ?></button>
		<button class="button minus select_no_attributes"><?php esc_html_e( 'Select none', 'woocommerce' ); ?></button>
		<button class="button fr plus add_new_attribute" data-type="<?php echo esc_attr( $attribute_taxonomy->attribute_type ); ?>"><?php esc_html_e( 'Add new', 'woocommerce' ); ?></button>

		<?php
	}
}

new Swatches_Admin_Product();
