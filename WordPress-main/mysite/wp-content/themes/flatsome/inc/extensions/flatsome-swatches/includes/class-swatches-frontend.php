<?php
/**
 * Swatches frontend  class.
 *
 * @package Flatsome\Extensions
 */

namespace Flatsome\Extensions;

defined( 'ABSPATH' ) || exit;

/**
 * Class Swatches_Frontend
 *
 * @package Flatsome\Extensions
 */
class Swatches_Frontend {

	/**
	 * The single instance of the class
	 *
	 * @var Swatches_Frontend
	 */
	protected static $instance = null;

	/**
	 * Main instance
	 *
	 * @return Swatches_Frontend
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Swatches_Frontend constructor.
	 */
	private function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		add_action( 'wp_head', array( $this, 'add_css' ), 110 );

		add_filter( 'woocommerce_dropdown_variation_attribute_options_html', array( $this, 'get_swatch_html' ), 100, 2 );
		add_filter( 'flatsome_swatch_html', array( $this, 'swatch_html' ), 5, 4 );

		// Add swatches in loop.
		add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'box_swatch_list' ) );

		// Layered nav.
		add_filter( 'woocommerce_layered_nav_term_html', array( $this, 'layered_nav_term_html' ), 10, 4 );

		// Handle cache.
		add_action( 'save_post', array( $this, 'cache_clear_save_post' ) );
		add_action( 'woocommerce_before_product_object_save', array( $this, 'cache_clear_product_object_save' ) );
		add_filter( 'pre_set_theme_mod_swatches', array( $this, 'cache_clear_all' ), 10, 2 );
		add_filter( 'pre_set_theme_mod_swatches_box_attribute', array( $this, 'cache_clear_all' ), 10, 2 );
		add_filter( 'pre_update_option_woocommerce_thumbnail_image_width', array( $this, 'cache_clear_all' ), 10, 2 );
		add_filter( 'pre_update_option_woocommerce_thumbnail_cropping', array( $this, 'cache_clear_all' ), 10, 2 );

		add_filter( 'woocommerce_variation_is_active', array( $this, 'variation_is_active' ), 10, 2 );
	}

	/**
	 * Enqueue scripts and stylesheets
	 */
	public function enqueue_scripts() {
		wp_enqueue_style( 'flatsome-swatches-frontend', get_template_directory_uri() . '/assets/css/extensions/flatsome-swatches-frontend.css', array(), swatches()->version );
		wp_style_add_data( 'flatsome-swatches-frontend', 'rtl', 'replace' );

		wp_enqueue_script( 'flatsome-swatches-frontend', get_template_directory_uri() . '/assets/js/extensions/flatsome-swatches-frontend.js', array(
			'jquery',
			'flatsome-js',
		), swatches()->version, true );
	}

	/**
	 * Add extension CSS.
	 */
	public function add_css() {
		ob_start();
		?>
		<?php if ( get_theme_mod( 'swatches_layout' ) === 'stacked' ) : ?>
			.variations th,
			.variations td {
			display: block;
			}

			.variations .label {
			display: flex;
			align-items: center;
			}

			.variations .label label {
			margin: .5em 0;
			}

			.ux-swatch-selected-value {
			font-weight: normal;
			font-size: .9em;
			}
		<?php endif; ?>

		<?php if ( get_theme_mod( 'swatches_color_selected', \Flatsome_Default::COLOR_SECONDARY ) !== \Flatsome_Default::COLOR_SECONDARY ) : ?>
			.variations_form .ux-swatch.selected {
			box-shadow: 0 0 0 2px <?php echo get_theme_mod( 'swatches_color_selected' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>;
			}
		<?php endif; ?>

		<?php if ( get_theme_mod( 'swatches_box_color_selected', \Flatsome_Default::COLOR_SECONDARY ) !== \Flatsome_Default::COLOR_SECONDARY ) : ?>
			.ux-swatches-in-loop .ux-swatch.selected {
			box-shadow: 0 0 0 2px <?php echo get_theme_mod( 'swatches_box_color_selected' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>;
			}
		<?php endif; ?>

		<?php
		$output = ob_get_clean();

		if ( ! $output ) {
			return;
		}

		$css  = '<style id="flatsome-swatches-css" type="text/css">';
		$css .= $output;
		$css .= '</style>';

		echo flatsome_minify_css( $css ); // phpcs:ignore WordPress.Security.EscapeOutput
	}

	/**
	 * The swatches HTML for an attribute.
	 *
	 * @param string $html The dropdown variation attribute options html.
	 * @param array  $args Args.
	 *
	 * @return string
	 * @uses swatch_html()
	 */
	public function get_swatch_html( $html, $args ) {
		$swatch_types = swatches()->get_attribute_types();
		$attr         = swatches()->get_attribute( $args['attribute'] );

		// Abort if this is normal attribute.
		if ( empty( $attr ) || ! array_key_exists( $attr->attribute_type, $swatch_types ) ) {
			return $html;
		}

		$swatches             = '';
		$options              = $args['options'];
		$product              = $args['product'];
		$attribute            = $args['attribute'];
		$classes              = array( 'ux-swatches', "ux-swatches-attribute-{$attr->attribute_type}" );
		$selector_classes     = array( 'variation-selector', "variation-select-{$attr->attribute_type}" );
		$args['tooltip']      = get_theme_mod( 'swatches_tooltip', 1 );
		$attr_options         = swatches()->get_attribute_option_by_name( $args['attribute'] );
		$use_variation_images = $this->use_variation_images( $attr_options );
		$variations           = $use_variation_images ? array_map( array( $this, 'get_variation' ), $product->get_children() ) : $product->get_available_variations();

		$args['swatches']                        = $this->get_swatches( $attribute, $options, $variations, $use_variation_images );
		$args['swatches']['use_variation_image'] = $use_variation_images;

		if ( isset( $attr_options['swatch_size'] ) && ! empty( $attr_options['swatch_size'] ) ) {
			$classes[] = 'ux-swatches--' . $attr_options['swatch_size'];
		}

		if ( isset( $attr_options['swatch_shape'] ) && ! empty( $attr_options['swatch_shape'] ) ) {
			$classes[] = 'ux-swatches--' . $attr_options['swatch_shape'];
		}

		if ( empty( $options ) && ! empty( $product ) && ! empty( $attribute ) ) {
			$attributes = $product->get_variation_attributes();
			$options    = $attributes[ $attribute ];
		}

		if ( ! empty( $options ) && taxonomy_exists( $attribute ) ) {
			$terms = wc_get_product_terms( $product->get_id(), $attribute, array( 'fields' => 'all' ) );

			foreach ( $terms as $term ) {
				if ( ! in_array( $term->slug, $options, true ) ) {
					continue;
				}
				$swatches .= apply_filters( 'flatsome_swatch_html', '', $term, $attr->attribute_type, $args );
			}
		}

		if ( ! empty( $swatches ) ) {
			$selector_classes[] = 'hidden';
			$swatches           = '<div class="' . esc_attr( implode( ' ', $classes ) ) . '" data-attribute_name="attribute_' . esc_attr( $attribute ) . '">' . $swatches . '</div>';
			$html               = '<div class="' . esc_attr( implode( ' ', $selector_classes ) ) . '">' . $html . '</div>' . $swatches;
		}

		return $html;
	}

	/**
	 * Single swatch HTML.
	 *
	 * @param string   $html HTML.
	 * @param \WP_Term $term WP Term object.
	 * @param string   $type Attribute type.
	 * @param array    $args Args.
	 *
	 * @return string
	 */
	public function swatch_html( $html, $term, $type, $args ) {
		$selected = sanitize_title( $args['selected'] ) == $term->slug ? 'selected' : '';
		$name     = esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name ) );
		$img_size = apply_filters( 'flatsome_swatch_image_size', 'woocommerce_gallery_thumbnail', $term );
		$classes  = array( 'ux-swatch' );
		$thumb    = '';
		$tooltip  = '';

		if ( ! empty( $args['tooltip'] ) ) {
			$classes[] = 'tooltip';
			$tooltip   = $term->description ? $term->description : $name;
		}

		// Gather variation image if one is available.
		if ( $args['swatches']['use_variation_image'] ) {
			$options_flipped = array_flip( $args['options'] );
			$key             = $options_flipped[ $term->slug ];
			if ( isset( $args['swatches'][ $key ]['variation_id'] ) && $args['swatches'][ $key ]['variation_id'] ) {
				$thumb_id = get_post_thumbnail_id( $args['swatches'][ $key ]['variation_id'] );

				if ( $thumb_id ) {
					$thumb = wp_get_attachment_image( $thumb_id, $img_size, false, array(
						'class' => "ux-swatch__img attachment-$img_size size-$img_size",
						'alt'   => $name,
					) );
					$type  = 'variation-image';
				}
			}
		}

		switch ( $type ) {
			case 'ux_color':
				$color_classes = array( 'ux-swatch__color' );
				$value         = get_term_meta( $term->term_id, 'ux_color', true );
				$color         = swatches()->parse_ux_color_term_meta( $value );

				if ( $color['class'] ) $color_classes[] = $color['class'];

				$html = sprintf( '<div class="%s ux-swatch--color swatch-%s %s" data-value="%s" data-name="%s" title="%s"><span class="%s" style="%s"></span><span class="ux-swatch__text">%s</span></div>',
					implode( ' ', $classes ),
					esc_attr( $term->slug ),
					$selected,
					esc_attr( $term->slug ),
					$name,
					esc_attr( $tooltip ),
					implode( ' ', $color_classes ),
					$color['style'],
					$name
				);
				break;

			case 'ux_image':
				$image_id = get_term_meta( $term->term_id, 'ux_image', true );
				$image    = '';
				if ( $image_id ) {
					$image = wp_get_attachment_image( $image_id, $img_size, false, array(
						'class' => "ux-swatch__img attachment-$img_size size-$img_size",
						'alt'   => $name,
					) );
				}

				$html = sprintf( '<div class="%s ux-swatch--image %s" data-value="%s" data-name="%s" title="%s">%s<span class="ux-swatch__text">%s</span></div>',
					implode( ' ', $classes ),
					$selected,
					esc_attr( $term->slug ),
					$name,
					esc_attr( $tooltip ),
					$image ? $image : wc_placeholder_img( $img_size ),
					$name
				);
				break;

			case 'variation-image':
				$html = sprintf(
					'<div class="%s ux-swatch--image %s" data-value="%s" data-name="%s" title="%s">%s<span class="ux-swatch__text">%s</span></div>',
					implode( ' ', $classes ),
					$selected,
					esc_attr( $term->slug ),
					$name,
					esc_attr( $tooltip ),
					$thumb ? $thumb : wc_placeholder_img( $img_size ),
					$name
				);
				break;

			case 'ux_label':
				$label = get_term_meta( $term->term_id, 'ux_label', true );
				$label = $label ? $label : $name;
				$html  = sprintf(
					'<div class="%s ux-swatch--label %s" data-value="%s" data-name="%s" title="%s"><span class="ux-swatch__text">%s</span></div>',
					implode( ' ', $classes ),
					$selected,
					esc_attr( $term->slug ),
					$name,
					esc_attr( $tooltip ),
					esc_html( $label )
				);
				break;
		}

		return $html;
	}

	/**
	 * Get an array of types and values for each attribute.
	 *
	 * @param array $attributes Attributes.
	 *
	 * @return array
	 */
	public function get_variation_attributes_types( $attributes ) {
		global $wc_product_attributes;
		$types        = array();
		$defined_attr = swatches()->get_attribute_types();

		if ( ! empty( $attributes ) ) {
			foreach ( $attributes as $name => $options ) {
				$current = isset( $wc_product_attributes[ $name ] ) ? $wc_product_attributes[ $name ] : false;

				if ( $current && array_key_exists( $current->attribute_type, $defined_attr ) ) {
					$types[ $name ] = $current->attribute_type;
				}
			}
		}

		return $types;
	}

	/**
	 * Create a list of swatches on product boxes.
	 *
	 * @return string
	 */
	public function box_swatch_list() {
		global $product;

		$attribute = apply_filters( 'flatsome_swatches_box_attribute', $this->get_swatches_box_attribute(), $product );

		if ( ! $attribute ) {
			return;
		}

		$attribute_name = $attribute->slug;

		if ( ! $attribute_name || empty( $attribute_name ) ) {
			return;
		}

		$id = $product->get_id();

		if ( empty( $id ) || ! $product->is_type( 'variable' ) ) {
			return;
		}

		$attr_options  = swatches()->get_attribute_option_by_name( $attribute_name );
		$cache_enabled = apply_filters( 'flatsome_swatches_cache_enabled', true );
		$transient     = 'flatsome_swatches_cache_' . $id;
		$classes       = array( 'ux-swatches', 'ux-swatches-in-loop' );

		if ( $cache_enabled ) {
			$available_variations = get_transient( $transient );
		} else {
			$available_variations = array();
		}

		if ( ! $available_variations ) {
			/** @var $product \WC_Product_Variable */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			$available_variations = $product->get_available_variations();
			if ( $cache_enabled ) {
				set_transient( $transient, $available_variations, apply_filters( 'flatsome_swatches_cache_time', WEEK_IN_SECONDS ) );
			}
		}

		if ( empty( $available_variations ) ) {
			return;
		}

		$swatches_to_show = $this->get_option_variations( $attribute_name, $available_variations );

		if ( empty( $swatches_to_show ) ) {
			return;
		}
		$html = '';

		if ( get_theme_mod( 'swatches_box_shape' ) ) {
			$classes[] = 'ux-swatches--' . get_theme_mod( 'swatches_box_shape' );
		}
		if ( get_theme_mod( 'swatches_box_size' ) ) $classes[] = 'ux-swatches--' . get_theme_mod( 'swatches_box_size' );

		// Start ux-swatches.
		$html .= '<div class="' . implode( ' ', $classes ) . '">';

		// Order.
		$terms                = wc_get_product_terms( $product->get_id(), $attribute_name, array( 'fields' => 'slugs' ) );
		$swatches_to_show_tmp = $swatches_to_show;
		$swatches_to_show     = array();
		foreach ( $terms as $id => $slug ) {
			if ( ! isset( $swatches_to_show_tmp[ $slug ] ) ) {
				continue;
			}
			$swatches_to_show[ $slug ] = $swatches_to_show_tmp[ $slug ];
		}

		$index = 0;

		$swatch_count  = count( $swatches_to_show );
		$swatch_limit  = (int) get_theme_mod( 'swatches_box_limit', 5 );
		$swatch_layout = get_theme_mod( 'swatches_box_layout' );

		foreach ( $swatches_to_show as $key => $swatch ) {
			$swatch_classes    = array( 'ux-swatch' );
			$color_classes     = array( 'ux-swatch__color' );
			$term              = get_term_by( 'slug', $key, $attribute_name );
			$name              = esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name ) );
			$img_size          = apply_filters( 'flatsome_swatch_image_size', 'woocommerce_gallery_thumbnail', $term );
			$data              = array();
			$type_tmp          = $attribute->type;
			$swatch_inner_html = '';

			if ( $this->use_variation_images( $attr_options ) && isset( $swatch['image_src'] ) ) {
				$thumb_id = get_post_thumbnail_id( $swatch['variation_id'] );

				if ( $thumb_id ) {
					$type_tmp          = 'variation-image';
					$swatch_classes[]  = 'ux-swatch--image';
					$swatch_inner_html = wp_get_attachment_image( $thumb_id, $img_size, false, array(
						'class' => "ux-swatch__img attachment-$img_size size-$img_size",
						'alt'   => $name,
					) );
				}
			}

			switch ( $type_tmp ) {
				case 'ux_color':
					$color = swatches()->parse_ux_color_term_meta( isset( $swatch['ux_color'] ) ? $swatch['ux_color'] : '' );

					if ( $color['class'] ) $color_classes[] = $color['class'];

					$swatch_classes[]  = 'ux-swatch--color';
					$swatch_inner_html = '<span class="' . implode( ' ', $color_classes ) . '" style="' . $color['style'] . '"></span>';
					break;
				case 'ux_image':
					$swatch_classes[]  = 'ux-swatch--image';
					$swatch_inner_html = isset( $swatch['ux_image'] ) ? wp_get_attachment_image( $swatch['ux_image'], $img_size, false, array(
						'class' => "ux-swatch__img attachment-$img_size size-$img_size",
						'alt'   => $name,
					) ) : wc_placeholder_img( $img_size );
					break;
				case 'ux_label':
					$swatch_classes[] = 'ux-swatch--label';
					break;
			}

			if ( isset( $swatch['image_src'] ) ) {
				$data['data-image-src']    = $swatch['image_src'];
				$data['data-image-srcset'] = $swatch['image_srcset'];
				$data['data-image-sizes']  = $swatch['image_sizes'];

				if ( ! $swatch['is_in_stock'] ) {
					$swatch_classes[] = 'out-of-stock';
				}
			}

			$data['data-attribute_name'] = 'attribute_' . sanitize_title( $attribute_name );
			$data['data-value']          = $term->slug;

			if ( $swatch_layout === 'limit' && $swatch_count > $swatch_limit ) {
				if ( $index >= $swatch_limit ) {
					$swatch_classes[] = 'ux-swatch--limited hidden';
				}
				if ( $index === $swatch_limit ) {
					$html .= '<span class="ux-swatches__limiter">+' . ( $swatch_count - $swatch_limit ) . '</span>';
				}
			}

			$html .= '<div class="' . esc_attr( implode( ' ', $swatch_classes ) ) . '" ' . flatsome_html_atts( $data ) . '>' . $swatch_inner_html . '<span class="ux-swatch__text">' . $name . '</span></div>';

			$index ++;
		}

		$html .= '</div>';

		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput
	}

	/**
	 * Get the attribute to show in loops by user preference.
	 *
	 * @return \stdClass|null The attribute object.
	 */
	private function get_swatches_box_attribute() {
		if ( ! get_theme_mod( 'swatches_box_attribute' ) ) {
			return null;
		}

		$attr = wc_get_attribute( get_theme_mod( 'swatches_box_attribute' ) );
		if ( $attr ) {
			return $attr;
		}

		return null;
	}

	/**
	 * Returns an array of data for a variation.
	 * (see \WC_Product_Variable->get_available_variation())
	 *
	 * @param \WC_Product $variation Variation product object or ID.
	 *
	 * @return array|bool
	 */
	public function get_variation( $variation ) {
		if ( is_numeric( $variation ) ) {
			$variation = wc_get_product( $variation );
		}
		if ( ! $variation instanceof \WC_Product_Variation ) {
			return false;
		}

		return array(
			'attributes'   => $variation->get_variation_attributes(),
			'image'        => wc_get_product_attachment_props( $variation->get_image_id() ),
			'image_id'     => $variation->get_image_id(),
			'is_in_stock'  => $variation->is_in_stock(),
			'variation_id' => $variation->get_id(),
		);
	}

	/**
	 * Get custom variation option data.
	 *
	 * @param string $attribute_name       Attribute name.
	 * @param array  $available_variations The available variation.
	 * @param mixed  $option               Whether to get only one variation by attribute option value.
	 *
	 * @return array|null
	 */
	private function get_option_variations( $attribute_name, $available_variations, $option = false ) {
		$swatches_to_show = array();

		foreach ( $available_variations as $key => $variation ) { // phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
			$option_variation = array();
			$attr_key         = 'attribute_' . sanitize_title( $attribute_name );
			if ( ! isset( $variation['attributes'][ $attr_key ] ) ) {
				return null;
			}

			$val = $variation['attributes'][ $attr_key ];

			if ( ! empty( $variation['image']['src'] ) ) {
				$option_variation = array(
					'variation_id' => $variation['variation_id'],
					'is_in_stock'  => $variation['is_in_stock'],
					'image_src'    => $variation['image']['thumb_src'],
					'image_srcset' => wp_get_attachment_image_srcset( $variation['image_id'], 'woocommerce_thumbnail' ),
					'image_sizes'  => wp_get_attachment_image_sizes( $variation['image_id'], 'woocommerce_thumbnail' ),
				);
			}

			// Get only one variation by attribute option value.
			if ( $option ) {
				if ( $val != $option ) {
					continue;
				} else {
					return $option_variation;
				}
			} else {
				// Or get all variations with swatches to show by attribute name.
				$swatch = $this->get_swatch( $attribute_name, $val );

				// If key already exist don't replace existing with sequential match.
				if ( ! array_key_exists( $val, $swatches_to_show ) ) {
					$swatches_to_show[ $val ] = array_merge( $swatch, $option_variation );
				}
			}
		}

		return $swatches_to_show;
	}

	/**
	 * Get single swatch values.
	 *
	 * @param string     $attr_name Attribute name.
	 * @param string|int $value     Search for this term value.
	 *
	 * @return array
	 */
	private function get_swatch( $attr_name, $value ) {
		$swatches = array();
		$color    = '';
		$image    = '';
		$label    = '';

		$term = get_term_by( 'slug', $value, $attr_name );
		if ( is_object( $term ) ) {
			$color = get_term_meta( $term->term_id, 'ux_color', true );
			$image = get_term_meta( $term->term_id, 'ux_image', true );
			$label = get_term_meta( $term->term_id, 'ux_label', true );
		}

		if ( $color != '' ) {
			$swatches['ux_color'] = $color;
		}

		if ( $image != '' ) {
			$swatches['ux_image'] = $image;
		}

		if ( $label != '' ) {
			$swatches['ux_label'] = $label;
		}

		return $swatches;
	}

	/**
	 * Get swatches.
	 *
	 * @param string $attr_name            Attribute name.
	 * @param array  $options              Attribute options.
	 * @param array  $available_variations Available variations.
	 * @param bool   $use_variation_images Whether to search and collect the variation image. Default false.
	 *
	 * @return array
	 */
	private function get_swatches( $attr_name, $options, $available_variations, $use_variation_images = false ) {
		$swatches = array();

		foreach ( $options as $key => $value ) {
			$swatch = $this->get_swatch( $attr_name, $value );

			if ( ! empty( $swatch ) ) {
				if ( $available_variations && $use_variation_images ) {
					$variation = $this->get_option_variations( $attr_name, $available_variations, $value );
					if ( $variation ) {
						$swatch = array_merge( $swatch, $variation );
					}
				}
				$swatches[ $key ] = $swatch;
			}

			if ( empty( $swatch ) && $available_variations && $use_variation_images ) {
				$variation = $this->get_option_variations( $attr_name, $available_variations, $value );
				if ( $variation ) {
					$swatch           = array_merge( $swatch, $variation );
					$swatches[ $key ] = $swatch;
				}
			}
		}

		return $swatches;
	}

	/**
	 * Check if given attribute options wants to display variation images.
	 *
	 * @param array $options The attribute options array.
	 *
	 * @return bool Whether to us variation images.
	 */
	private function use_variation_images( $options ) {
		if ( ! $options ) {
			return false;
		}

		if ( isset( $options['swatch_variation_images'] ) && $options['swatch_variation_images'] ) {
			return true;
		}

		return false;
	}

	/**
	 * Render swatch value in layered nav.
	 *
	 * @param string     $term_html Term HTML.
	 * @param object     $term      Term.
	 * @param string     $link      Link.
	 * @param string|int $count     Count.
	 *
	 * @return string
	 */
	public function layered_nav_term_html( $term_html, $term, $link, $count ) {
		$swatch_types = swatches()->get_attribute_types();
		$attr         = swatches()->get_attribute( $term->taxonomy );

		// Abort if this is normal attribute.
		if ( empty( $attr ) || ! array_key_exists( $attr->attribute_type, $swatch_types ) ) {
			return $term_html;
		}

		$classes  = array( 'ux-swatch-widget-layered-nav-list__graphic' );
		$img_size = apply_filters( 'flatsome_swatch_image_size', 'woocommerce_gallery_thumbnail', $term );

		if ( get_theme_mod( 'swatches_box_shape' ) ) {
			$classes[] = 'ux-swatches--' . get_theme_mod( 'swatches_box_shape' );
		}

		switch ( $attr->attribute_type ) {
			case 'ux_image':
				$image_id  = get_term_meta( $term->term_id, 'ux_image', true );
				$image     = '';
				$classes[] = 'ux-swatch--image';

				if ( $image_id ) {
					$image = wp_get_attachment_image( $image_id, $img_size, false, array(
						'class' => "ux-swatch__img attachment-$img_size size-$img_size",
						'alt'   => $term->name,
					) );
				}

				$swatch = sprintf( '<div class="' . implode( ' ', $classes ) . '">%s</div>',
					$image ? $image : wc_placeholder_img( 'woocommerce_gallery_thumbnail' )
				);
				break;
			case 'ux_color':
				$color_classes = array( 'ux-swatch__color' );
				$value         = get_term_meta( $term->term_id, 'ux_color', true );
				$color         = swatches()->parse_ux_color_term_meta( $value );
				$classes[]     = 'ux-swatch--color';

				if ( $color['class'] ) $color_classes[] = $color['class'];

				$swatch = sprintf( '<div class="' . implode( ' ', $classes ) . '"><span class="%s" style="%s"></span></div>',
					implode( ' ', $color_classes ),
					$color['style']
				);
				break;
			default:
				return $term_html;
		}

		return $swatch . $term_html;
	}

	/**
	 * Clear cache on save post by ID.
	 *
	 * @param int $post_id Post ID.
	 */
	public function cache_clear_save_post( $post_id ) {
		if ( ! apply_filters( 'flatsome_swatches_cache_enabled', true ) ) {
			return;
		}

		$transient = 'flatsome_swatches_cache_' . $post_id;

		delete_transient( $transient );
	}

	/**
	 * Clear cache on product object save by ID.
	 *
	 * @param \WC_Product $product Product object.
	 */
	public function cache_clear_product_object_save( $product ) {
		if ( ! apply_filters( 'flatsome_swatches_cache_enabled', true ) ) {
			return;
		}
		$post_id   = $product->get_id();
		$transient = 'flatsome_swatches_cache_' . $post_id;
		delete_transient( $transient );
	}

	/**
	 * Clear all cache.
	 *
	 * @param string $new_value The new value of the theme modification.
	 * @param string $old_value The current value of the theme modification.
	 *
	 * @return mixed
	 */
	public function cache_clear_all( $new_value, $old_value ) {
		if ( $new_value !== $old_value ) {
			swatches()->cache_clear();
		}

		return $new_value;
	}

	/**
	 * Whether this particular variation will appear greyed-out (inactive) or not (active).
	 * Makes a variation inactive if out of stock.
	 *
	 * @param bool                  $active    The state.
	 * @param \WC_Product_variation $variation The variation.
	 *
	 * @return bool
	 */
	public function variation_is_active( $active, $variation ) {
		if ( ! get_theme_mod( 'swatches_out_of_stock_inactive', 0 ) ) return $active;

		if ( ! $variation->is_in_stock() ) {
			return false;
		}

		return $active;
	}
}
