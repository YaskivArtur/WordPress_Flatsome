<?php

function flatsome_woocommerce_setup() {
	// Theme support for default WC gallery.
	if ( get_theme_mod( 'product_gallery_woocommerce') ) {
		add_theme_support( 'wc-product-gallery-slider' );
		// Force default lightbox when default gallery is chosen.
		if ( get_theme_mod( 'product_lightbox','default' ) !== 'default' ) {
			set_theme_mod( 'product_lightbox', 'default' );
		}
	}
	// Theme support for default WC gallery lightbox.
	if ( get_theme_mod( 'product_lightbox','default' ) === 'default' ) {
		add_theme_support( 'wc-product-gallery-lightbox' );
	}
	// Remove default row and column options.
	remove_theme_support( 'product_grid' );
}
add_action( 'after_setup_theme', 'flatsome_woocommerce_setup', 90 );


if ( ! function_exists( 'flatsome_woocommerce_add_notice' ) ) {
	/**
	 * Add wc notices except for the cart page
	 */
	function flatsome_woocommerce_add_notice() {
		if ( is_woocommerce_activated() && ! is_cart() ) {
			if ( function_exists( 'wc_print_notices' ) ) wc_print_notices();
		}
	}
}
add_action( 'flatsome_after_header', 'flatsome_woocommerce_add_notice', 100 );

function flatsome_my_account_menu_classes($classes){

    // Add Active Class
    if(in_array('is-active', $classes)){
      array_push($classes, 'active');
    }

    return $classes;
}
add_filter('woocommerce_account_menu_item_classes', 'flatsome_my_account_menu_classes');

/* My Account Dashboard overview */
function flatsome_my_account_dashboard(){
  wc_get_template( 'myaccount/dashboard-links.php' );
}
add_action('woocommerce_account_dashboard','flatsome_my_account_dashboard');


// Remove logout from my account menu
function flatsome_remove_logout_account_item( $items ) {
  unset( $items['customer-logout'] );
  return $items;
}
add_filter( 'woocommerce_account_menu_items', 'flatsome_remove_logout_account_item' );


/**
 * Conditionally remove WooCommerce styles and/or scripts.
 */
function flatsome_woocommerce_scripts_styles() {
	// Remove default WooCommerce Lightbox.
	if ( get_theme_mod( 'product_lightbox', 'default' ) !== 'woocommerce' || ! is_product() ) {
		wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
		wp_deregister_style( 'woocommerce_prettyPhoto_css' );

		wp_dequeue_script( 'prettyPhoto' );
		wp_dequeue_script( 'prettyPhoto-init' );
	}

	if ( ! is_admin() ) {
		wp_dequeue_style( 'woocommerce-layout' );
		wp_deregister_style( 'woocommerce-layout' );
		wp_dequeue_style( 'woocommerce-smallscreen' );
		wp_deregister_style( 'woocommerce-smallscreen' );
		wp_dequeue_style( 'woocommerce-general' );
		wp_deregister_style( 'woocommerce-general' );
	}
}

add_action( 'wp_enqueue_scripts', 'flatsome_woocommerce_scripts_styles', 98 );


// Add Shop  Widgets
function flatsome_shop_widgets_init() {

  register_sidebar( array(
    'name'          => __( 'Shop Sidebar', 'flatsome' ),
    'id'            => 'shop-sidebar',
    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    'after_widget'  => '</aside>',
    'before_title'  => '<span class="widget-title shop-sidebar">',
    'after_title'   => '</span><div class="is-divider small"></div>',
  ) );

  register_sidebar( array(
    'name'          => __( 'Product Sidebar', 'flatsome' ),
    'id'            => 'product-sidebar',
    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    'after_widget'  => '</aside>',
    'before_title'  => '<span class="widget-title shop-sidebar">',
    'after_title'   => '</span><div class="is-divider small"></div>',
  ) );


}
add_action( 'widgets_init', 'flatsome_shop_widgets_init' );



/* Modify define(name, value)ault Shop Breadcrumbs */
function flatsome_woocommerce_breadcrumbs() {

    $home = (get_theme_mod('breadcrumb_home',1)) ? _x( 'Home', 'breadcrumb', 'woocommerce' ) : false;

    return array(
        'delimiter'   => '&#47;',
        'wrap_before' => '<nav class="woocommerce-breadcrumb breadcrumbs '.get_theme_mod('breadcrumb_case', 'uppercase').'">',
        'wrap_after'  => '</nav>',
        'before'      => '',
        'after'       => '',
        'home'        => $home
    );
}

add_filter( 'woocommerce_breadcrumb_defaults', 'flatsome_woocommerce_breadcrumbs' );

/**
 * Add default breadcrumbs.
 *
 * @see woocommerce_breadcrumb()
 */
add_action( 'flatsome_breadcrumb' , 'woocommerce_breadcrumb', 20 );

/* Update cart price */
function flatsome_header_add_to_cart_fragment( $fragments ) {
  ob_start();
  ?> <span class="cart-price"><?php echo WC()->cart->get_cart_subtotal(); ?></span><?php
  $fragments['.cart-price'] = ob_get_clean();

  return $fragments;

}
add_filter('woocommerce_add_to_cart_fragments', 'flatsome_header_add_to_cart_fragment');

if ( ! function_exists( 'flatsome_header_add_to_cart_fragment_count' ) ) {
	/**
	 * Update cart number when default cart icon is selected
	 *
	 * @param $fragments
	 *
	 * @return mixed
	 */
	function flatsome_header_add_to_cart_fragment_count( $fragments ) {
		ob_start();
		?>
		<span class="cart-icon image-icon">
			<strong><?php echo WC()->cart->cart_contents_count; ?></strong>
		</span>
		<?php
		$fragments['.header .cart-icon'] = ob_get_clean();

		return $fragments;
	}
}
add_filter( 'woocommerce_add_to_cart_fragments', 'flatsome_header_add_to_cart_fragment_count' );


if ( ! function_exists( 'flatsome_header_add_to_cart_fragment_count_label' ) ) {
	/**
	 * Update cart label when a build-in cart icon is selected
	 *
	 * @param $fragments
	 *
	 * @return mixed
	 */
	function flatsome_header_add_to_cart_fragment_count_label( $fragments ) {
		if ( ! get_theme_mod( 'cart_icon_style' ) ) {
			return $fragments;
		}

		$icon = get_theme_mod( 'cart_icon', 'basket' );
		ob_start();
		?>
		<i class="icon-shopping-<?php echo $icon; ?>" data-icon-label="<?php echo WC()->cart->cart_contents_count; ?>">
		<?php
		$fragments[ '.cart-item i.icon-shopping-' . $icon ] = ob_get_clean();

		return $fragments;
	}
}
add_filter( 'woocommerce_add_to_cart_fragments', 'flatsome_header_add_to_cart_fragment_count_label' );

if ( ! function_exists( 'flatsome_header_add_to_cart_custom_icon_fragment_count_label' ) ) {
	/**
	 * Update cart label when custom cart icon is selected
	 *
	 * @param $fragments
	 *
	 * @return mixed
	 */
	function flatsome_header_add_to_cart_custom_icon_fragment_count_label( $fragments ) {
		$custom_cart_icon = get_theme_mod( 'custom_cart_icon' );
		if ( ! $custom_cart_icon ) {
			return $fragments;
		}

		ob_start();
		?>
		<span class="image-icon header-cart-icon" data-icon-label="<?php echo WC()->cart->cart_contents_count; ?>">
			<img class="cart-img-icon" alt="<?php _e( 'Cart', 'woocommerce' ); ?>" src="<?php echo do_shortcode( $custom_cart_icon ); ?>"/>
		</span>
		<?php
		$fragments['.image-icon.header-cart-icon'] = ob_get_clean();

		return $fragments;
	}
}
add_filter( 'woocommerce_add_to_cart_fragments', 'flatsome_header_add_to_cart_custom_icon_fragment_count_label' );

// Add Pages and blog posts to top of search results if set.
function flatsome_pages_in_search_results(){
    if(!is_search() || !get_theme_mod('search_result', 1)) return;
    global $post;
    ?>
    <?php if( get_search_query() ) : ?>
    <?php
      /**
       * Include pages and posts in search
       */
      query_posts( array( 'post_type' => array( 'post'), 's' => get_search_query() ) );

      $posts = array();
      while ( have_posts() ) : the_post();
        array_push($posts, $post->ID);
      endwhile;

      wp_reset_query();

      // Get pages
      query_posts( array( 'post_type' => array( 'page'), 's' => get_search_query() ) );
      $pages = array();
      while ( have_posts() ) : the_post();
        $wc_page = false;
        if($post->post_type == 'page'){
          foreach (array('shop', 'cart', 'checkout', 'view_order', 'terms') as $wc_page_type) {
            if( $post->ID == wc_get_page_id($wc_page_type) ) $wc_page = true;
          }
        }
        if( !$wc_page ) array_push($pages, $post->ID);
      endwhile;

      wp_reset_query();

		if ( ! empty( $posts ) ) {
			echo '<hr/><h4 class="uppercase">' . esc_html__( 'Posts found', 'flatsome' ) . '</h4>';
			echo flatsome_apply_shortcode( 'blog_posts', array(
				'columns'      => '3',
				'columns__md'  => '3',
				'columns__sm'  => '2',
				'type'         => get_theme_mod( 'search_result_style', 'slider' ),
				'image_height' => '56.25%',
				'show_date'    => get_theme_mod( 'blog_badge', 1 ) ? 'true' : 'false',
				'ids'          => implode( ',', $posts ),
			) );
		}

		if ( ! empty( $pages ) ) {
			echo '<hr/><h4 class="uppercase">' . esc_html__( 'Pages found', 'flatsome' ) . '</h4>';
			echo flatsome_apply_shortcode( 'ux_pages', array(
				'columns'      => '3',
				'columns__md'  => '3',
				'columns__sm'  => '2',
				'type'         => get_theme_mod( 'search_result_style', 'slider' ),
				'image_height' => '56.25%',
				'ids'          => implode( ',', $pages ),
			) );
		}
		?>
    <?php endif; ?>

    <?php
}
add_action('woocommerce_after_main_content','flatsome_pages_in_search_results', 10);

/**
 * Filters the WooCommerce sale flash.
 *
 * @param string     $html    The HTML string.
 * @param WP_Post    $post    Post object.
 * @param WC_Product $product Product object.
 *
 * @return false|mixed
 */
function flatsome_woocommerce_sale_flash( $html, $post, $product ) {
	if ( ! get_theme_mod( 'sale_bubble', 1 ) ) {
		return '';
	}

	return $html;
}

add_filter( 'woocommerce_sale_flash', 'flatsome_woocommerce_sale_flash', 10, 3 );

function flatsome_new_flash( $html, $post, $product, $badge_style ) {
	if ( ! get_theme_mod( 'new_bubble_auto' ) ) {
		return $html;
	}

	$datetime_created = $product->get_date_created();

	if ( ! $datetime_created ) {
		return $html;
	}

	$timestamp_created = $datetime_created->getTimestamp();
	$datetime_now      = new WC_DateTime();
	$timestamp_now     = $datetime_now->getTimestamp();
	$time_delta        = $timestamp_now - $timestamp_created;
	$days              = (int) get_theme_mod( 'new_bubble_auto' );
	$days_in_seconds   = 60 * 24 * 60 * $days;

	if ( $time_delta < $days_in_seconds ) {
		$html .= apply_filters( 'flatsome_new_flash_html', '<div class="badge callout badge-' . $badge_style . '"><div class="badge-inner is-small new-bubble-auto">' . esc_html__( 'New', 'flatsome' ) . '</div></div>', $post, $product, $badge_style );
	}

	return $html;
}

add_filter( 'flatsome_product_labels', 'flatsome_new_flash', 20, 4 );

/**
 * Calculates discount percentage for the product sale bubble for
 * simple, variable or external product types. Returns base bubble text
 * with or without formatting otherwise.
 *
 * @param WC_Product $product Product object.
 * @param string     $text    Default text.
 *
 * @return string
 */
function flatsome_presentage_bubble( $product, $text ) {
	$post_id = $product->get_id();

	if ( $product->is_type( 'simple' ) || $product->is_type( 'external' ) || $product->is_type( 'variation' ) ) {
		$regular_price  = $product->get_regular_price();
		$sale_price     = $product->get_sale_price();
		$bubble_content = round( ( ( floatval( $regular_price ) - floatval( $sale_price ) ) / floatval( $regular_price ) ) * 100 );
	} elseif ( $product->is_type( 'variable' ) ) {
		$bubble_content = flatsome_percentage_get_cache( $post_id );
		if ( $bubble_content && apply_filters( 'flatsome_sale_bubble_percentage_cache_enabled', true ) ) {
			return flatsome_percentage_format( $bubble_content );
		}

		$available_variations = $product->get_available_variations();
		$maximumper           = 0;

		for ( $i = 0; $i < count( $available_variations ); ++ $i ) {
			$variation_id     = $available_variations[ $i ]['variation_id'];
			$variable_product = new WC_Product_Variation( $variation_id );
			if ( ! $variable_product->is_on_sale() ) {
				continue;
			}
			$regular_price = $variable_product->get_regular_price();
			$sale_price    = $variable_product->get_sale_price();
			$percentage    = round( ( ( floatval( $regular_price ) - floatval( $sale_price ) ) / floatval( $regular_price ) ) * 100 );
			if ( $percentage > $maximumper ) {
				$maximumper = $percentage;
			}
		}

		$bubble_content = sprintf( __( '%s', 'woocommerce' ), $maximumper );

		// Cache percentage for variable products to reduce database queries.
		if ( apply_filters( 'flatsome_sale_bubble_percentage_cache_enabled', true ) ) {
			flatsome_percentage_set_cache( $post_id, $bubble_content );
		}
	} else {
		// Return default if the product type doesn't meet specification.
		return $text;
	}

	return flatsome_percentage_format( $bubble_content );
}

function flatsome_percentage_get_cache( $post_id ) {
	return get_post_meta( $post_id, '_flatsome_product_percentage', true );
}

function flatsome_percentage_set_cache( $post_id, $bubble_content ) {
	update_post_meta( $post_id, '_flatsome_product_percentage', $bubble_content );
}

// Process custom formatting. Keep mod value double check
// to process % for default parameter (See sprintf()).
function flatsome_percentage_format( $value ) {
	$formatting = get_theme_mod( 'sale_bubble_percentage_formatting' );
	$formatting = $formatting ? $formatting : '-{value}%';

	return str_replace( '{value}', $value, $formatting );
}

// Clear cached percentage whenever a product or variation is saved.
function flatsome_percentage_clear( $object ) {
	if ( ! get_theme_mod( 'sale_bubble_percentage' ) ) return;

	$post_id = 'variation' === $object->get_type()
		? $object->get_parent_id()
		: $object->get_id();

	delete_post_meta( $post_id, '_flatsome_product_percentage' );
}
add_action( 'woocommerce_before_product_object_save', 'flatsome_percentage_clear' );

// Clear all cached percentages when disabling bubble percentage.
function flatsome_percentage_clear_all( $value, $old_value ) {
	if ( ! $value && $old_value ) {
		delete_metadata( 'post', null, '_flatsome_product_percentage', '', true );
	}

	return $value;
}
add_filter( 'pre_set_theme_mod_sale_bubble_percentage', 'flatsome_percentage_clear_all', 10, 2 );

// Account login style
function flatsome_account_login_lightbox(){
  // Show Login Lightbox if selected
  if ( !is_user_logged_in() && get_theme_mod('account_login_style','lightbox') == 'lightbox' && !is_checkout() && !is_account_page() ) {
    $is_facebook_login = is_nextend_facebook_login();
    $is_google_login = is_nextend_google_login();
    $layout = get_theme_mod( 'account_login_lightbox_layout' );

	if ( empty( $layout ) && 'no' === get_option( 'woocommerce_registration_generate_password' ) ) {
		wp_enqueue_script( 'wc-password-strength-meter' );
	}

    ?>
    <div id="login-form-popup" class="lightbox-content mfp-hide">
      <?php if(get_theme_mod('social_login_pos','top') == 'top' && ($is_facebook_login || $is_google_login)) wc_get_template('myaccount/header.php'); ?>
      	<div class="woocommerce">
      		<?php wc_get_template_part('myaccount/form-login', $layout ); ?>
		</div>
      	<?php if(get_theme_mod('social_login_pos','top') == 'bottom' && ($is_facebook_login || $is_google_login)) wc_get_template('myaccount/header.php'); ?>
    </div>
  <?php }
}
add_action('wp_footer', 'flatsome_account_login_lightbox', 10);

// Payment icons to footer
function flatsome_footer_payment_icons(){
  $icons = get_theme_mod('payment_icons_placement');
  if(is_array($icons) && !in_array('footer', $icons)) return;
  echo do_shortcode('[ux_payment_icons]');
}
add_action('flatsome_absolute_footer_secondary','flatsome_footer_payment_icons', 10);


/* Disable reviews globally */
if(get_theme_mod('disable_reviews')){
    remove_filter( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
    remove_filter( 'woocommerce_single_product_summary','woocommerce_template_single_rating', 10);
    add_filter( 'woocommerce_product_tabs', 'wcs_woo_remove_reviews_tab', 98 );
    function wcs_woo_remove_reviews_tab($tabs) {
     unset($tabs['reviews']);
     return $tabs;
    }
}

if ( ! function_exists( 'flatsome_wc_get_gallery_image_html' ) ) {
	/**
	 * Get HTML for a gallery image. Copied and modified from woocommerce plugin and wc_get_gallery_image_html helper function.
	 *
	 * @param int    $attachment_id Attachment ID.
	 * @param bool   $main_image    Is this the main image or a thumbnail?.
	 * @param string $size          Image size.
	 *
	 * @return string
	 */
	function flatsome_wc_get_gallery_image_html( $attachment_id, $main_image = false, $size = 'woocommerce_single' ) {
		$gallery_thumbnail = wc_get_image_size( 'gallery_thumbnail' );
		$thumbnail_size    = apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );
		$image_size        = apply_filters( 'woocommerce_gallery_image_size', $size );
		$full_size         = apply_filters( 'woocommerce_gallery_full_size', apply_filters( 'woocommerce_product_thumbnails_large_size', 'full' ) );
		$thumbnail_src     = wp_get_attachment_image_src( $attachment_id, $thumbnail_size );
		$full_src          = wp_get_attachment_image_src( $attachment_id, $full_size );
		$alt_text          = trim( wp_strip_all_tags( get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) ) );

		if ( empty( $full_src ) ) {
			return '';
		}

		$image = wp_get_attachment_image(
			$attachment_id,
			$image_size,
			false,
			apply_filters(
				'woocommerce_gallery_image_html_attachment_image_params',
				array(
					'title'                   => _wp_specialchars( get_post_field( 'post_title', $attachment_id ), ENT_QUOTES, 'UTF-8', true ),
					'data-caption'            => _wp_specialchars( get_post_field( 'post_excerpt', $attachment_id ), ENT_QUOTES, 'UTF-8', true ),
					'data-src'                => esc_url( $full_src[0] ),
					'data-large_image'        => esc_url( $full_src[0] ),
					'data-large_image_width'  => esc_attr( $full_src[1] ),
					'data-large_image_height' => esc_attr( $full_src[2] ),
					'class'                   => esc_attr( $main_image ? 'wp-post-image skip-lazy' : 'skip-lazy' ), // skip-lazy, blacklist for Jetpack's lazy load.
				),
				$attachment_id,
				$image_size,
				$main_image
			)
		);

		$image_wrapper_class = $main_image ? 'slide first' : 'slide';

		return '<div data-thumb="' . esc_url( $thumbnail_src[0] ) . '" data-thumb-alt="' . esc_attr( $alt_text ) . '" class="woocommerce-product-gallery__image ' . $image_wrapper_class . '"><a href="' . esc_url( $full_src[0] ) . '">' . $image . '</a></div>';
	}
}

/* Move demo store notice to top. */
function flatsome_move_store_notice() {
    if ( get_theme_mod( 'woocommerce_store_notice_top' ) ) {
        remove_action( 'wp_footer', 'woocommerce_demo_store' );
        add_action ( 'flatsome_after_body_open', 'woocommerce_demo_store', 0 );
    }
}
add_action( 'wp_loaded', 'flatsome_move_store_notice' );

/**
 * Filter WC Product shortcode attributes,
 *
 * @param array $attrs Attributes.
 *
 * @return array Attributes.
 */
function flatsome_filter_shortcode_atts_products( $attrs ) {
	if ( $attrs['limit'] == '-1' ) {
		$attrs['limit'] = '12';
	}

	if ( $attrs['columns'] == '' ) {
		$attrs['columns'] = '4';
	}

	return $attrs;
}

add_filter( 'shortcode_atts_products', 'flatsome_filter_shortcode_atts_products' );

/**
 * Flatsome Payment Icons List.
 *
 * Returns a list of Flatsome Payment Icons.
 *
 * @return array Payment Icons list.
 */
function flatsome_get_payment_icons_list() {
	return apply_filters( 'flatsome_payment_icons', array(
		'amazon'          => __( 'Amazon', 'flatsome-admin' ),
		'americanexpress' => __( 'American Express', 'flatsome-admin' ),
		'applepay'        => __( 'Apple Pay', 'flatsome-admin' ),
		'afterpay'        => __( 'AfterPay', 'flatsome-admin' ),
		'afterpay-2'      => __( 'AfterPay 2', 'flatsome-admin' ),
		'alipay'          => __( 'Alipay', 'flatsome-admin' ),
		'atm'             => __( 'Atm', 'flatsome-admin' ),
		'bancontact'      => __( 'Bancontact', 'flatsome-admin' ),
		'bankomat'        => __( 'Bankomat', 'flatsome-admin' ),
		'banktransfer'    => __( 'Bank Transfer', 'flatsome-admin' ),
		'belfius'         => __( 'Belfius', 'flatsome-admin' ),
		'bitcoin'         => __( 'BitCoin', 'flatsome-admin' ),
		'braintree'       => __( 'Braintree', 'flatsome-admin' ),
		'cartasi'         => __( 'CartaSi', 'flatsome-admin' ),
		'cashcloud'       => __( 'CashCloud', 'flatsome-admin' ),
		'cashondelivery'  => __( 'Cash On Delivery', 'flatsome-admin' ),
		'cashonpickup'    => __( 'Cash on Pickup', 'flatsome-admin' ),
		'cbc'             => __( 'CBC', 'flatsome-admin' ),
		'cirrus'          => __( 'Cirrus', 'flatsome-admin' ),
		'clickandbuy'     => __( 'Click and Buy', 'flatsome-admin' ),
		'creditcard'      => __( 'Credit Card', 'flatsome-admin' ),
		'creditcard2'     => __( 'Credit Card 2', 'flatsome-admin' ),
		'dancard'         => __( 'DanKort', 'flatsome-admin' ),
		'dinnersclub'     => __( 'Dinners Club', 'flatsome-admin' ),
		'discover'        => __( 'Discover', 'flatsome-admin' ),
		'elo'             => __( 'Elo', 'flatsome-admin' ),
		'eps'             => __( 'Eps', 'flatsome-admin' ),
		'facture'         => __( 'Facture', 'flatsome-admin' ),
		'fattura'         => __( 'Fattura', 'flatsome-admin' ),
		'flattr'          => __( 'Flattr', 'flatsome-admin' ),
		'giropay'         => __( 'GiroPay', 'flatsome-admin' ),
		'googlepay'       => __( 'Google Pay', 'flatsome-admin' ),
		'googlewallet'    => __( 'Google Wallet', 'flatsome-admin' ), // Deprecated, changed to Google Pay.
		'hiper'           => __( 'Hiper', 'flatsome-admin' ),
		'ideal'           => __( 'IDeal', 'flatsome-admin' ),
		'interac'         => __( 'Interac', 'flatsome-admin' ),
		'invoice'         => __( 'Invoice', 'flatsome-admin' ),
		'jcb'             => __( 'JCB', 'flatsome-admin' ),
		'kbc'             => __( 'KBC', 'flatsome-admin' ),
		'klarna'          => __( 'Klarna', 'flatsome-admin' ),
		'maestro'         => __( 'Maestro', 'flatsome-admin' ),
		'mastercard'      => __( 'MasterCard', 'flatsome-admin' ),
		'mastercard-2'    => __( 'MasterCard 2', 'flatsome-admin' ),
		'mir'             => __( 'Mir', 'flatsome-admin' ),
		'moip'            => __( 'Moip', 'flatsome-admin' ),
		'mollie'          => __( 'Mollie', 'flatsome-admin' ),
		'ogone'           => __( 'Ogone', 'flatsome-admin' ),
		'paybox'          => __( 'Paybox', 'flatsome-admin' ),
		'paylife'         => __( 'Paylife', 'flatsome-admin' ),
		'paymill'         => __( 'PayMill', 'flatsome-admin' ),
		'paypal'          => __( 'PayPal', 'flatsome-admin' ),
		'paypal-2'        => __( 'PayPal 2', 'flatsome-admin' ),
		'paysafe'         => __( 'PaySafe', 'flatsome-admin' ),
		'paysera'         => __( 'Paysera', 'flatsome-admin' ),
		'payshop'         => __( 'PayShop', 'flatsome-admin' ),
		'paytm'           => __( 'Paytm', 'flatsome-admin' ),
		'payu'            => __( 'PayU', 'flatsome-admin' ),
		'postepay'        => __( 'Postepay', 'flatsome-admin' ),
		'quick'           => __( 'Quick', 'flatsome-admin' ),
		'rechung'         => __( 'Rechung', 'flatsome-admin' ),
		'revolut'         => __( 'Revolut', 'flatsome-admin' ),
		'ripple'          => __( 'Ripple', 'flatsome-admin' ),
		'rupay'           => __( 'RuPay', 'flatsome-admin' ),
		'sage'            => __( 'Sage', 'flatsome-admin' ),
		'sepa'            => __( 'Sepa', 'flatsome-admin' ),
		'six'             => __( 'Six', 'flatsome-admin' ),
		'skrill'          => __( 'Skrill', 'flatsome-admin' ),
		'sofort'          => __( 'Sofort', 'flatsome-admin' ),
		'square'          => __( 'Square', 'flatsome-admin' ),
		'stripe'          => __( 'Stripe', 'flatsome-admin' ),
		'swish'           => __( 'Swish (SE)', 'flatsome-admin' ),
		'truste'          => __( 'Truste', 'flatsome-admin' ),
		'twint'           => __( 'Twint', 'flatsome-admin' ),
		'unionpay'        => __( 'UnionPay', 'flatsome-admin' ),
		'venmo'           => __( 'Venmo', 'flatsome-admin' ),
		'verisign'        => __( 'VeriSign', 'flatsome-admin' ),
		'vipps'           => __( 'Vipps', 'flatsome-admin' ),
		'visa'            => __( 'Visa', 'flatsome-admin' ),
		'visa1'           => __( 'Visa 2', 'flatsome-admin' ),
		'visaelectron'    => __( 'Visa Electron', 'flatsome-admin' ),
		'westernunion'    => __( 'Western Union', 'flatsome-admin' ),
		'wirecard'        => __( 'Wirecard', 'flatsome-admin' ),
	) );
}

if ( flatsome_is_mini_cart_reveal() ) {
	/**
	* Adds a span tag with the "added-to-cart" class to Add to Cart notice to trigger auto reveal mini cart.
	*
	* @param  string $message    Default WooCommerce added to cart notice.
	* @param  int    $product_id Product id.
	* @return string             The modified message.
	*/
	add_filter( 'wc_add_to_cart_message_html', function ( $message ) {
		$message .= '<span class="added-to-cart" data-timer=""></span>';

		return $message;
	});
}

/**
 * Get HTML for ratings.
 *
 * @see wc_get_rating_html()
 */
function flatsome_get_rating_html( $rating, $count = 0 ) {
	global $product;
	$review_count = $product->get_review_count();
	$label        = sprintf( __( 'Rated %s out of 5', 'woocommerce' ), $rating ); // phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment
	$html         = '';

	if ( $rating > 0 ) {
		if ( is_single() ) {
			$style = get_theme_mod( 'product_info_review_count_style', 'inline' );
			// Default to 'simple' when review count visibility is disabled.
			$style = get_theme_mod( 'product_info_review_count' ) ? $style : 'simple';

			switch ( $style ) {
				case 'tooltip':
					$title = sprintf( _n( '%s customer review', '%s customer reviews', $review_count, 'woocommerce' ), $review_count ); // phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment
					$html  = '<a href="#reviews" class="woocommerce-review-link" rel="nofollow">';
					$html .= '<div class="star-rating tooltip" role="img" aria-label="' . esc_attr( $label ) . '" title="' . esc_attr( $title ) . '">';
					$html .= wc_get_star_rating_html( $rating, $count );
					$html .= '</div>';
					$html .= '</a>';
					break;
				case 'inline':
					$html  = '<div class="star-rating star-rating--inline" role="img" aria-label="' . esc_attr( $label ) . '">';
					$html .= wc_get_star_rating_html( $rating, $count );
					$html .= '</div>';
					break;
				case 'stacked':
					$html  = '<div class="star-rating" role="img" aria-label="' . esc_attr( $label ) . '">';
					$html .= wc_get_star_rating_html( $rating, $count );
					$html .= '</div>';
					break;
				case 'simple':
					$html  = '<a href="#reviews" class="woocommerce-review-link" rel="nofollow">';
					$html .= '<div class="star-rating" role="img" aria-label="' . esc_attr( $label ) . '">';
					$html .= wc_get_star_rating_html( $rating, $count );
					$html .= '</div>';
					$html .= '</a>';
					break;
			}
		} else {
			$html  = '<div class="star-rating star-rating--inline" role="img" aria-label="' . esc_attr( $label ) . '">';
			$html .= wc_get_star_rating_html( $rating, $count );
			$html .= '</div>';
		}
	}

	return apply_filters( 'woocommerce_product_get_rating_html', $html, $rating, $count );
}

