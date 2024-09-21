<?php
/**
 * Swatches admin class.
 *
 * @package Flatsome\Extensions
 */

namespace Flatsome\Extensions;

defined( 'ABSPATH' ) || exit;

/**
 * Class Swatches_Admin
 *
 * @package Flatsome\Extensions
 */
class Swatches_Admin {

	/**
	 * The single instance of the class
	 *
	 * @var Swatches_Admin
	 */
	protected static $instance = null;

	/**
	 * Main instance
	 *
	 * @return Swatches_Admin
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Swatches_Admin constructor.
	 */
	private function __construct() {
		add_action( 'admin_init', array( $this, 'includes' ) );
		add_action( 'admin_init', array( $this, 'init_attribute_hooks' ) );
		add_action( 'admin_print_scripts', array( $this, 'enqueue_scripts' ) );

		// Add option fields.
		add_action( 'flatsome_product_attribute_term_fields', array( $this, 'attribute_term_field' ), 10, 3 );
		add_action( 'woocommerce_after_add_attribute_fields', array( $this, 'attribute_fields' ) );
		add_action( 'woocommerce_after_edit_attribute_fields', array( $this, 'attribute_fields' ) );

		// Add attribute option fields.
		add_action( 'woocommerce_attribute_added', array( $this, 'add_attribute_options' ), 10, 2 );
		add_action( 'woocommerce_attribute_updated', array( $this, 'update_attribute_options' ), 10, 3 );
		add_action( 'woocommerce_attribute_updated', array( swatches(), 'cache_clear' ), 10, 3 );
		add_action( 'woocommerce_attribute_deleted', array( $this, 'delete_attribute_option' ), 10 );
	}

	/**
	 * Render attribute fields for add/edit screen.
	 */
	public function attribute_fields() {
		$id               = isset( $_GET['edit'] ) ? absint( $_GET['edit'] ) : false; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$options          = $id ? get_option( "flatsome_product_attribute-{$id}" ) : '';
		$swatch_size      = isset( $options['swatch_size'] ) ? $options['swatch_size'] : '';
		$swatch_shape     = isset( $options['swatch_shape'] ) ? $options['swatch_shape'] : '';
		$variation_images = isset( $options['swatch_variation_images'] ) ? $options['swatch_variation_images'] : '';
		$table            = doing_action( 'woocommerce_after_edit_attribute_fields' ); // Edit screen requires table markup.
		?>

		<?php echo $table ? '<tr class="form-field"><th scope="row" valign="top">' : '<div class="form-field">'; ?>
		<label for="attribute_swatch_size"><?php esc_html_e( 'Swatch size', 'flatsome' ); ?></label>
		<?php if ( $table ) echo '</th><td>'; ?>
		<select name="attribute_swatch_size" id="attribute_swatch_size">
			<option value="x-small" <?php selected( $swatch_size, 'x-small' ); ?>><?php esc_html_e( 'X Small', 'flatsome' ); ?></option>
			<option value="small" <?php selected( $swatch_size, 'small' ); ?>><?php esc_html_e( 'Small', 'flatsome' ); ?></option>
			<option value="" <?php selected( $swatch_size, '' ); ?>><?php esc_html_e( 'Medium', 'flatsome' ); ?></option>
			<option value="large" <?php selected( $swatch_size, 'large' ); ?>><?php esc_html_e( 'Large', 'flatsome' ); ?></option>
			<option value="x-large" <?php selected( $swatch_size, 'x-large' ); ?>><?php esc_html_e( 'X Large', 'flatsome' ); ?></option>
		</select>
		<p class="description"><?php esc_html_e( 'Determines the size of the swatches.', 'flatsome' ); ?></p>
		<?php echo $table ? '</td></tr>' : '</div>'; ?>

		<?php echo $table ? '<tr class="form-field"><th scope="row" valign="top">' : '<div class="form-field">'; ?>
		<label for="attribute_swatch_shape"><?php esc_html_e( 'Swatch shape', 'flatsome' ); ?></label>
		<?php if ( $table ) echo '</th><td>'; ?>
		<select name="attribute_swatch_shape" id="attribute_swatch_shape">
			<option value=""><?php esc_html_e( 'Square', 'flatsome' ); ?></option>
			<option value="rounded" <?php selected( $swatch_shape, 'rounded' ); ?>><?php esc_html_e( 'Rounded', 'flatsome' ); ?></option>
			<option value="circle" <?php selected( $swatch_shape, 'circle' ); ?>><?php esc_html_e( 'Circle', 'flatsome' ); ?></option>
		</select>
		<p class="description"><?php esc_html_e( 'Determines the shape of the swatches.', 'flatsome' ); ?></p>
		<?php echo $table ? '</td></tr>' : '</div>'; ?>

		<?php echo $table ? '<tr class="form-field"><th scope="row" valign="top">' : '<div class="form-field">'; ?>
		<?php echo $table ? '<label for="attribute_swatch_variation_images">' . esc_html__( 'Use variation images?', 'flatsome' ) . '</label></th><td>' : '<label for="attribute_swatch_variation_images">'; ?>
		<input name="attribute_swatch_variation_images" id="attribute_swatch_variation_images" type="checkbox" value="1" <?php checked( $variation_images ); ?>><?php if ( ! $table ) echo esc_html__( 'Use variation images?', 'flatsome' ) . '</label>'; ?>
		<p class="description"><?php esc_html_e( 'Enable this if you want swatches for this attribute to be auto filled with variation images.', 'flatsome' ); ?></p>
		<?php echo $table ? '</td></tr>' : '</div>'; ?>
		<?php
	}

	/**
	 * Include files we need in product admin.
	 */
	public function includes() {
		include_once dirname( __FILE__ ) . '/class-swatches-admin-product.php';
	}

	/**
	 * Add fields to attribute screen.
	 * Add swatch preview column per attribute term.
	 * Save new term meta.
	 */
	public function init_attribute_hooks() {
		$attribute_taxonomies = wc_get_attribute_taxonomies();

		if ( empty( $attribute_taxonomies ) ) {
			return;
		}

		foreach ( $attribute_taxonomies as $tax ) {
			add_action( 'pa_' . $tax->attribute_name . '_add_form_fields', array( $this, 'add_attribute_term_fields' ) );
			add_action( 'pa_' . $tax->attribute_name . '_edit_form_fields', array( $this, 'edit_attribute_term_fields' ), 10, 2 );

			add_filter( 'manage_edit-pa_' . $tax->attribute_name . '_columns', array( $this, 'add_attribute_columns' ) );
			add_filter( 'manage_pa_' . $tax->attribute_name . '_custom_column', array( $this, 'add_attribute_column_content' ), 10, 3 );
		}

		add_action( 'created_term', array( $this, 'save_term_meta' ), 10, 3 );
		add_action( 'edit_term', array( $this, 'save_term_meta' ), 10, 3 );
		add_action( 'edit_term', array( swatches(), 'cache_clear' ), 10, 3 );
	}

	/**
	 * Load scripts in edit product attribute screen.
	 */
	public function enqueue_scripts() {
		$screen = get_current_screen();
		if ( $screen && strpos( $screen->id, 'edit-pa_' ) === false && strpos( $screen->id, 'product' ) === false ) {
			return;
		}

		wp_enqueue_media();

		wp_enqueue_style( 'flatsome-swatches-admin', get_template_directory_uri() . '/assets/css/extensions/flatsome-swatches-admin.css', array( 'wp-color-picker' ), swatches()->version );
		wp_enqueue_script( 'flatsome-swatches-admin', get_template_directory_uri() . '/assets/js/extensions/flatsome-swatches-admin.js', array(
			'jquery',
			'wp-color-picker',
			'wp-util',
		), swatches()->version, true );

		wp_localize_script(
			'flatsome-swatches-admin',
			'flatsome_swatches',
			array(
				'placeholder' => WC()->plugin_url() . '/assets/images/placeholder.png',
			)
		);
	}

	/**
	 * Create hook to add fields to add attribute term screen.
	 *
	 * @param string $taxonomy Taxonomy.
	 */
	public function add_attribute_term_fields( $taxonomy ) {
		$attr = swatches()->get_attribute( $taxonomy );

		do_action( 'flatsome_product_attribute_term_fields', $attr->attribute_type, '', 'add' );
	}

	/**
	 * Create hook to fields to edit attribute term screen.
	 *
	 * @param object $term     Term.
	 * @param string $taxonomy Taxonomy.
	 */
	public function edit_attribute_term_fields( $term, $taxonomy ) {
		$attr  = swatches()->get_attribute( $taxonomy );
		$value = get_term_meta( $term->term_id, $attr->attribute_type, true );

		do_action( 'flatsome_product_attribute_term_fields', $attr->attribute_type, $value, 'edit' );
	}

	/**
	 * Print HTML of custom field on attribute term add/edit screens.
	 *
	 * @param string $type  Attribute type.
	 * @param string $value Field value.
	 * @param string $form  The form kind.
	 */
	public function attribute_term_field( $type, $value, $form ) {
		// Return if this is a default attribute type.
		if ( in_array( $type, array( 'select', 'text' ), true ) ) {
			return;
		}

		$name = $type;

		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		printf( '<%s class="form-field term-%s-wrap">%s<label for="term-%s">%s</label>%s',
			'edit' == $form ? 'tr' : 'div',
			esc_attr( $type ),
			'edit' == $form ? '<th>' : '',
			esc_attr( $type ),
			esc_html( swatches()->get_attribute_types()[ $type ] ),
			'edit' == $form ? '</th><td>' : ''
		);
		// phpcs:enable  WordPress.Security.EscapeOutput.OutputNotEscaped

		switch ( $type ) {
			case 'ux_image':
				$image = $value ? wp_get_attachment_image_src( $value ) : '';
				$image = $image ? $image[0] : WC()->plugin_url() . '/assets/images/placeholder.png';
				?>
				<div class="ux-swatches-term-image-thumbnail" style="margin: 8px 0;">
					<img src="<?php echo esc_url( $image ); ?>" width="60px" height="60px" alt=""/>
				</div>
				<div style="line-height:35px;">
					<input type="hidden" class="ux-swatches-term-image" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>" />
					<button type="button" class="ux-swatches-upload-image-button button"><?php esc_html_e( 'Select image', 'flatsome' ); ?></button>
					<button type="button" class="ux-swatches-remove-image-button button <?php echo $value ? '' : 'hidden'; ?>"><?php esc_html_e( 'Remove', 'flatsome' ); ?></button>
				</div>
				<p>The swatch image.</p>
				<?php
				break;
			case 'ux_color':
				$color   = swatches()->parse_ux_color_term_meta( $value );
				$value   = $color['color'];
				$value_2 = '';
				$name   .= '[]';

				if ( $color['color_2'] ) $value_2 = $color['color_2'];
				?>
				<input type="text" id="term-<?php echo esc_attr( $type ); ?>" class="ux-color-field" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>" />
				<span class="ux-swatches-add-color button" data-content="<?php echo $value_2 ? '+' : '-'; ?>"><?php echo $value_2 ? '-' : '+'; ?></span><br>
				<input type="text" id="term-<?php echo esc_attr( $type ); ?>_2" class="ux-swatches-bicolor-picker ux-color-field" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value_2 ); ?>"/>
				<p>The swatch color.</p>
				<?php
				break;
			case 'ux_label':
				?>
				<input type="text" id="term-<?php echo esc_attr( $type ); ?>" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>" />
				<p>The swatch text.</p>
				<?php
				break;
		}

		echo 'edit' == $form ? '</td></tr>' : '</div>';
	}

	/**
	 * Save term meta.
	 *
	 * @param int    $term_id  Term ID.
	 * @param int    $tt_id    Term taxonomy ID.
	 * @param string $taxonomy Taxonomy slug.
	 */
	public function save_term_meta( $term_id, $tt_id, $taxonomy ) {
		foreach ( swatches()->get_attribute_types() as $type => $label ) {
			if ( isset( $_POST[ $type ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
				$value = wp_unslash( $_POST[ $type ] ); // phpcs:ignore WordPress.Security.NonceVerification, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
				if ( is_array( $value ) ) {
					$array_values = array_filter( $value );
					if ( empty( $array_values ) ) {
						$value = '';
					} else {
						$value = implode( ',', $array_values );
					}
				}

				update_term_meta( $term_id, $type, sanitize_text_field( $value ) );
			}
		}
	}

	/**
	 * Attribute added.
	 *
	 * @param int   $id   Added attribute ID.
	 * @param array $data Attribute data.
	 */
	public function add_attribute_options( $id, $data ) {
		// phpcs:disable WordPress.Security.NonceVerification
		$values = array(
			'swatch_size'             => isset( $_POST['attribute_swatch_size'] ) ? sanitize_text_field( wp_unslash( $_POST['attribute_swatch_size'] ) ) : '',
			'swatch_shape'            => isset( $_POST['attribute_swatch_shape'] ) ? sanitize_text_field( wp_unslash( $_POST['attribute_swatch_shape'] ) ) : '',
			'swatch_variation_images' => isset( $_POST['attribute_swatch_variation_images'] ) ? isset( $_POST['attribute_swatch_variation_images'] ) : '',
		);
		// phpcs:enable WordPress.Security.NonceVerification

		add_option( "flatsome_product_attribute-{$id}", $values );
	}

	/**
	 * Attribute updated.
	 *
	 * @param int    $id       Added attribute ID.
	 * @param array  $data     Attribute data.
	 * @param string $old_slug Attribute old name.
	 */
	public function update_attribute_options( $id, $data, $old_slug ) {
		// phpcs:disable WordPress.Security.NonceVerification
		$values = array(
			'swatch_size'             => isset( $_POST['attribute_swatch_size'] ) ? sanitize_text_field( wp_unslash( $_POST['attribute_swatch_size'] ) ) : '',
			'swatch_shape'            => isset( $_POST['attribute_swatch_shape'] ) ? sanitize_text_field( wp_unslash( $_POST['attribute_swatch_shape'] ) ) : '',
			'swatch_variation_images' => isset( $_POST['attribute_swatch_variation_images'] ) ? isset( $_POST['attribute_swatch_variation_images'] ) : '',
		);
		// phpcs:enable WordPress.Security.NonceVerification

		update_option( "flatsome_product_attribute-{$id}", $values );
	}

	/**
	 * Attribute deleted.
	 *
	 * @param int $id Added attribute ID.
	 */
	public function delete_attribute_option( $id ) {
		delete_option( "flatsome_product_attribute-{$id}" );
	}

	/**
	 * Add thumbnail column to column list
	 *
	 * @param array $columns Columns.
	 *
	 * @return array
	 */
	public function add_attribute_columns( $columns ) {
		if ( empty( $columns ) ) {
			return $columns;
		}

		$new_columns                      = array();
		$new_columns['cb']                = $columns['cb'];
		$new_columns['ux_swatch_preview'] = __( 'Value', 'flatsome' );

		unset( $columns['cb'] );

		return array_merge( $new_columns, $columns );
	}

	/**
	 * Render thumbnail HTML depending on attribute type.
	 *
	 * @param string     $content Current content.
	 * @param string     $column  Column name.
	 * @param string|int $term_id Term ID.
	 *
	 * @return string The column HTML content.
	 */
	public function add_attribute_column_content( $content, $column, $term_id ) {
		if ( 'ux_swatch_preview' === $column ) {
			$classes = array( 'ux-swatch-preview' );
			$attr    = swatches()->get_attribute( $_REQUEST['taxonomy'] ); // phpcs:ignore
			$value   = get_term_meta( $term_id, $attr->attribute_type, true );

			switch ( $attr->attribute_type ) {
				case 'ux_color':
					$color_classes   = array( 'ux-swatch__color' );
					$color           = swatches()->parse_ux_color_term_meta( $value );
					$classes[]       = 'ux-swatch--color';
					$color_classes[] = $color['class'];
					printf( '<div class="%s"><span class="%s" style="%s"></span></div>',
						esc_attr( implode( ' ', $classes ) ),
						esc_attr( implode( ' ', $color_classes ) ),
						$color['style'] // phpcs:ignore WordPress.Security.EscapeOutput
					);
					break;

				case 'ux_image':
					$image     = $value ? wp_get_attachment_image_src( $value ) : '';
					$image     = $image ? $image[0] : WC()->plugin_url() . '/assets/images/placeholder.png';
					$classes[] = 'ux-swatch--image';
					printf( '<div class="%s"><img class="ux-swatch__img" src="%s" width="30px" height="30px" alt=""></div>',
						esc_attr( implode( ' ', $classes ) ),
						esc_url( $image )
					);
					break;

				case 'ux_label':
					$classes[] = 'ux-swatch--label';
					printf( '<div class="%s"><span class="ux-swatch__text">%s</span></div>',
						esc_attr( implode( ' ', $classes ) ),
						esc_html( $value )
					);
					break;
			}
		}

		return $content;
	}
}
