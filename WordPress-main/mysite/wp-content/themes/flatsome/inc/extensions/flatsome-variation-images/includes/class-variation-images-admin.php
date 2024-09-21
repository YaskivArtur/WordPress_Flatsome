<?php
/**
 * Additional variation images admin class.
 *
 * @package Flatsome\Extensions
 */

namespace Flatsome\Extensions;

defined( 'ABSPATH' ) || exit;

/**
 * Class Variation_Images_Admin
 *
 * @package Flatsome\Extensions
 */
class Variation_Images_Admin {

	/**
	 * The single instance of the class
	 *
	 * @var Variation_Images_Admin
	 */
	protected static $instance = null;

	/**
	 * Main instance
	 *
	 * @return Variation_Images_Admin
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Variation_Images_Admin constructor.
	 */
	private function __construct() {
		add_action( 'admin_print_scripts', array( $this, 'enqueue_scripts' ) );

		add_action( 'wp_ajax_flatsome_additional_variation_images_load_images_ajax', array( $this, 'load_images_ajax' ) );
		add_action( 'save_post', array( $this, 'save_post_meta' ), 1, 2 );
		add_action( 'woocommerce_save_product_variation', array( $this, 'save_product_variation' ), 10, 2 );
	}

	/**
	 * Load admin scripts.
	 */
	public function enqueue_scripts() {
		if ( 'product' === get_post_type() ) {
			wp_enqueue_style(
				'flatsome-variation-images-admin',
				get_template_directory_uri() . '/assets/css/extensions/flatsome-variation-images-admin.css',
				array(),
				variation_images()->version
			);
			wp_enqueue_script(
				'flatsome-variation-images-admin',
				get_template_directory_uri() . '/assets/js/extensions/flatsome-variation-images-admin.js',
				array( 'jquery' ),
				variation_images()->version,
				true
			);

			wp_localize_script(
				'flatsome-variation-images-admin',
				'flatsome_variation_images_admin',
				array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'nonce'   => array(
						'load_images' => wp_create_nonce( 'flatsome-variation-images-load-images' ),
					),
				)
			);
		}
	}

	/**
	 * Load images admin ajax..
	 */
	public function load_images_ajax() {
		if ( ! isset( $_POST['variation_ids'] ) ) {
			echo 'No variation IDs supplied.';
			exit;
		}

		if ( ! check_ajax_referer( 'flatsome-variation-images-load-images', 'nonce', false ) ) {
			wp_send_json_error( array(
				'message' => 'Invalid nonce',
			) );
		}

		$variation_ids = array_map( 'absint', $_POST['variation_ids'] );

		$variation_images = array();

		if ( count( $variation_ids ) > 0 ) {
			foreach ( $variation_ids as $id ) {
				$ids = variation_images()->get_image_ids( $id );

				$html  = '';
				$html .= '<input type="hidden" class="ux-additional-variation-images-save" name="ux_additional_variation_images_thumbs[' . esc_attr( $id ) . ']" value="' . esc_attr( $ids ) . '">';
				$html .= '<ul class="ux-additional-variation-images__list">';

				foreach ( explode( ',', $ids ) as $attach_id ) {
					$attachment = wp_get_attachment_image_src( $attach_id, array( 64, 64 ) );

					if ( $attachment ) {
						$html .= '<li class="ux-additional-variation-images__thumbnail" data-attachment-id="' . esc_attr( $attach_id ) . '">';
						$html .= '<img class="ux-additional-variation-images__thumbnail-img" src="' . esc_attr( $attachment[0] ) . '" alt="" width="64" height="64"/>';
						$html .= '<span class="actions"><a href="#" class="ux-additional-variation-images__delete"></a></span>';
						$html .= '</li>';
					}
				}

				$html .= '</ul>';

				$variation_images[ $id ] = $html;
			}
		}

		wp_send_json( array(
			'images' => $variation_images,
		) );
	}

	/**
	 * Save product meta.
	 *
	 * (see WC_Admin_Meta_Boxes save_meta_boxes())
	 *
	 * @param int      $post_id Post ID.
	 * @param \WP_Post $post    Post object.
	 */
	public function save_post_meta( $post_id, $post ) {
		$post_id = absint( $post_id );

		// $post_id and $post are required.
		if ( empty( $post_id ) || empty( $post ) || ! isset( $_POST['ux_additional_variation_images_thumbs'] ) ) {
			return;
		}

		// Don't save meta boxes for revisions or auto-saves.
		if ( defined( 'DOING_AUTOSAVE' ) || is_int( wp_is_post_revision( $post ) ) || is_int( wp_is_post_autosave( $post ) ) ) {
			return;
		}

		// Check the nonce.
		if ( empty( $_POST['woocommerce_meta_nonce'] ) || ! wp_verify_nonce( $_POST['woocommerce_meta_nonce'], 'woocommerce_save_data' ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
			return;
		}

		// Check the post being saved == the $post_id to prevent triggering this call for other save_post events.
		if ( empty( $_POST['post_ID'] ) || $_POST['post_ID'] != $post_id ) {
			return;
		}

		// Check user has permission to edit.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Check the post type.
		if ( ! in_array( $post->post_type, array( 'product' ), true ) ) {
			return;
		}

		$ids = $_POST['ux_additional_variation_images_thumbs']; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
		$ids = array_map( 'sanitize_text_field', $ids );

		if ( count( $ids ) > 0 ) {
			foreach ( $ids as $variation_id => $attachment_ids ) {
				if ( ! empty( $attachment_ids ) ) {
					update_post_meta( $variation_id, '_ux_additional_variation_images', $attachment_ids );
				} else {
					delete_post_meta( $variation_id, '_ux_additional_variation_images' );
				}
			}
		}
	}

	/**
	 * Saves additional variation images on ajax save.
	 *
	 * @param int $variation_id variation ID.
	 * @param int $i Loop count.
	 */
	public function save_product_variation( $variation_id, $i ) {
		// phpcs:disable WordPress.Security.NonceVerification
		if ( ! isset( $_POST['ux_additional_variation_images_thumbs'] ) ) {
			return;
		}

		$ids = sanitize_text_field( $_POST['ux_additional_variation_images_thumbs'][ $variation_id ] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
		// phpcs:enable WordPress.Security.NonceVerification

		if ( ! empty( $ids ) ) {
			update_post_meta( $variation_id, '_ux_additional_variation_images', $ids );
		} else {
			delete_post_meta( $variation_id, '_ux_additional_variation_images' );
		}
	}
}
