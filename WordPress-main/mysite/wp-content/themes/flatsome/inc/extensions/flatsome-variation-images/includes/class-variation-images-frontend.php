<?php
/**
 * Additional variation images frontend class.
 *
 * @package Flatsome\Extensions
 */

namespace Flatsome\Extensions;

defined( 'ABSPATH' ) || exit;

/**
 * Class Variation_Images_Frontend
 *
 * @package Flatsome\Extensions
 */
class Variation_Images_Frontend {

	/**
	 * The single instance of the class
	 *
	 * @var Variation_Images_Frontend
	 */
	protected static $instance = null;

	/**
	 * Main instance
	 *
	 * @return Variation_Images_Frontend
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Variation_Images_Frontend constructor.
	 */
	private function __construct() {
		add_action( 'wp_head', array( $this, 'add_css' ), 110 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		add_filter( 'flatsome_single_product_thumbnails_render_without_attachments', array( $this, 'thumbnails_render_without_attachments' ), 10, 3 );

		add_action( 'wp_ajax_flatsome_additional_variation_images_load_images_ajax_frontend', array( $this, 'load_images_ajax' ) );
		add_action( 'wp_ajax_nopriv_flatsome_additional_variation_images_load_images_ajax_frontend', array( $this, 'load_images_ajax' ) );
	}

	/**
	 * Enqueue scripts and stylesheets
	 */
	public function enqueue_scripts() {
		wp_enqueue_script(
			'flatsome-variation-images-frontend',
			get_template_directory_uri() . '/assets/js/extensions/flatsome-variation-images-frontend.js',
			array( 'jquery' ),
			variation_images()->version,
			true
		);
	}

	/**
	 * Add CSS.
	 */
	public function add_css() {
		ob_start();
		?>
		.ux-additional-variation-images-thumbs-placeholder {
			max-height: 0;
			opacity: 0;
			visibility: hidden;
			transition: visibility .1s, opacity .1s, max-height .2s ease-out;
		}

		.ux-additional-variation-images-thumbs-placeholder--visible {
			max-height: 1000px;
			opacity: 1;
			visibility: visible;
			transition: visibility .2s, opacity .2s, max-height .1s ease-in;
		}
		<?php
		$output = ob_get_clean();

		$css  = '<style id="flatsome-variation-images-css">';
		$css .= $output;
		$css .= '</style>';

		echo flatsome_minify_css( $css ); // phpcs:ignore WordPress.Security.EscapeOutput
	}

	/**
	 * Makes the thumbnail slider render hidden as placeholder if only the post thumbnail is present.
	 *
	 * @param bool        $render_without_attachments Whether to render without attachments.
	 * @param \WC_product $product                    Product object.
	 * @param array       $args                       Arguments.
	 *
	 * @return bool|mixed
	 */
	public function thumbnails_render_without_attachments( $render_without_attachments, $product, $args ) {
		if ( isset( $args['thumb_count'] ) && $args['thumb_count'] == 1 ) {
			if ( $this->has_additional_variation_images( $product ) ) {
				add_filter( 'flatsome_single_product_thumbnails_classes', function ( $classes ) {
					$classes[] = 'ux-additional-variation-images-thumbs-placeholder';

					return $classes;
				} );

				return true;
			}
		}

		return $render_without_attachments;
	}

	/**
	 * Load images frontend ajax.
	 */
	public function load_images_ajax() {
		$variation_id = isset( $_POST['variation_id'] ) ? absint( $_POST['variation_id'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification
		$variation    = $variation_id ? wc_get_product( $variation_id ) : false;

		if ( ! $variation || ! $variation->is_visible() ) {
			wp_send_json_error();
		}

		$attachment_ids       = array_filter( explode( ',', variation_images()->get_image_ids( $variation_id ) ) );
		$variation_main_image = $variation->get_image_id();

		if ( ! empty( $variation_main_image ) ) {
			array_unshift( $attachment_ids, $variation_main_image );
		}

		if ( empty( $attachment_ids ) ) {
			wp_send_json_error();
		}

		// Collect main images.
		$images = array();
		foreach ( $attachment_ids as $attachment_id ) {
			$images[] = apply_filters( 'woocommerce_single_product_image_thumbnail_html', flatsome_wc_get_gallery_image_html( $attachment_id, false ), $attachment_id );
		}

		// Collect thumb images (see product-gallery-thumbnails.php).
		$thumbs     = array();
		$image_size = 'thumbnail';
		// Check if custom gallery thumbnail size is set and use that.
		$image_check = wc_get_image_size( 'gallery_thumbnail' );
		if ( $image_check['width'] !== 100 ) {
			$image_size = 'gallery_thumbnail';
		}

		$gallery_thumbnail = wc_get_image_size( apply_filters( 'woocommerce_gallery_thumbnail_size', 'woocommerce_' . $image_size ) );

		foreach ( $attachment_ids as $attachment_id ) {
			$classes     = array();
			$image_class = esc_attr( implode( ' ', $classes ) );
			$image       = wp_get_attachment_image_src( $attachment_id, apply_filters( 'woocommerce_gallery_thumbnail_size', 'woocommerce_' . $image_size ) );

			if ( empty( $image ) ) {
				continue;
			}

			$image_alt = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
			$image     = '<img src="' . $image[0] . '" alt="' . $image_alt . '" width="' . $gallery_thumbnail['width'] . '" height="' . $gallery_thumbnail['height'] . '"  class="attachment-woocommerce_thumbnail" />';

			$thumbs[] = apply_filters( 'woocommerce_single_product_image_thumbnail_html', sprintf( '<div class="col"><a>%s</a></div>', $image ), $attachment_id, 0, $image_class );
		}

		wp_send_json( array(
			'data' => array(
				'uniqueMainImage' => (bool) $variation->get_image_id( 'edit ' ),
				'images'          => $images,
				'thumbs'          => $thumbs,
			),
		) );
	}

	/**
	 * Does a particular product have additional variation image(s) assigned?
	 *
	 * @param \WC_Product $product Product object.
	 *
	 * @return bool
	 */
	private function has_additional_variation_images( $product ) {
		if ( $product->is_type( 'variable' ) ) {
			if ( ! empty( variation_images()->get_image_ids( $product->get_id() ) ) ) {
				return true;
			}
		}

		$variation_ids = $product->get_children();
		if ( count( $variation_ids ) > 0 ) {
			foreach ( $variation_ids as $variation_id ) {
				if ( ! empty( variation_images()->get_image_ids( $variation_id ) ) ) {
					return true;
				}
			}
		}

		return false;
	}
}
