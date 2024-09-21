<?php

if ( ! isset( $content_width ) ) {
	$content_width = 1020; // Pixels.
}

/**
 * Initialized Envato and Flatsome Account implementation.
 */
flatsome_envato();

/**
 * Only load styles for used blocks.
 */
add_filter( 'should_load_separate_core_block_assets', '__return_true' );

/**
 * Setup Flatsome.
 */
function flatsome_setup() {

	/* add woocommerce support */
	add_theme_support( 'woocommerce' );

	if ( get_theme_mod( 'wpseo_breadcrumb' ) ) {
		add_theme_support( 'yoast-seo-breadcrumbs' );
	}

	/* add title tag support */
	add_theme_support( 'title-tag' );

	/* Load child theme languages */
	load_theme_textdomain( 'flatsome', get_stylesheet_directory() . '/languages' );

	/* load theme languages */
	load_theme_textdomain( 'flatsome', get_template_directory() . '/languages' );

	/* Add default posts and comments RSS feed links to head */
	add_theme_support( 'automatic-feed-links' );

	/* Add excerpt to pages */
	add_post_type_support( 'page', 'excerpt' );

	/* Add support for post thumbnails */
	add_theme_support( 'post-thumbnails' );

	/* Add support for Selective Widget refresh */
	add_theme_support( 'customize-selective-refresh-widgets' );

	/** Add sensei support */
	add_theme_support( 'sensei' );

	/* Add support for HTML5 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'widgets',
	) );

	/*  Register menus. */
	register_nav_menus( array(
		'primary'        => __( 'Main Menu', 'flatsome' ),
		'primary_mobile' => __( 'Main Menu - Mobile', 'flatsome' ),
		'secondary'      => __( 'Secondary Menu', 'flatsome' ),
		'footer'         => __( 'Footer Menu', 'flatsome' ),
		'top_bar_nav'    => __( 'Top Bar Menu', 'flatsome' ),
		'my_account'     => __( 'My Account Menu', 'flatsome' ),
		'vertical'       => __( 'Vertical Menu', 'flatsome' ),
	) );

	/*  Enable support for Post Formats */
	add_theme_support( 'post-formats', array( 'video' ) );

	// Disable widgets-block-editor for now.
	remove_theme_support( 'widgets-block-editor' );
}

add_action( 'after_setup_theme', 'flatsome_setup' );


/**
 * Setup Theme Widgets
 */
function flatsome_widgets_init() {

	$title_before = '';
	$title_class  = '';
	$title_after  = '<div class="is-divider small"></div>';

	register_sidebar( array(
		'name'          => __( 'Sidebar', 'flatsome' ),
		'id'            => 'sidebar-main',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => $title_before . '<span class="widget-title ' . $title_class . '"><span>',
		'after_title'   => '</span></span>' . $title_after,
	) );


	register_sidebar( array(
		'name'          => __( 'Footer 1', 'flatsome' ),
		'id'            => 'sidebar-footer-1',
		'before_widget' => '<div id="%1$s" class="col pb-0 widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<span class="widget-title">',
		'after_title'   => '</span><div class="is-divider small"></div>',
	) );


	register_sidebar( array(
		'name'          => __( 'Footer 2', 'flatsome' ),
		'id'            => 'sidebar-footer-2',
		'before_widget' => '<div id="%1$s" class="col pb-0 widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<span class="widget-title">',
		'after_title'   => '</span><div class="is-divider small"></div>',
	) );
}

add_action( 'widgets_init', 'flatsome_widgets_init' );


/**
 * Setup Flatsome Styles and Scripts
 */
function flatsome_scripts() {
	$uri     = get_template_directory_uri();
	$theme   = wp_get_theme( get_template() );
	$version = $theme->get( 'Version' );

	// Styles.
	wp_enqueue_style( 'flatsome-main', $uri . '/assets/css/flatsome.css', array(), $version, 'all' );
	wp_style_add_data( 'flatsome-main', 'rtl', 'replace' );


	if ( is_woocommerce_activated() ) {
		wp_enqueue_style( 'flatsome-shop', $uri . '/assets/css/flatsome-shop.css', array(), $version, 'all' );
		wp_style_add_data( 'flatsome-shop', 'rtl', 'replace' );
	}

	// Load current theme styles.css file.
	if ( ! get_theme_mod( 'flatsome_disable_style_css', 0 ) ) {
		wp_enqueue_style( 'flatsome-style', get_stylesheet_uri(), array(), wp_get_theme()->get( 'Version' ), 'all' );
	}

	// Register styles (Loaded on request).
	wp_register_style( 'flatsome-effects', $uri . '/assets/css/effects.css', array(), $version, 'all' );

	// Register scripts (Loaded on request).
	wp_register_script( 'flatsome-masonry-js', $uri . '/assets/libs/packery.pkgd.min.js', array( 'jquery' ), $version, true );
	wp_register_script( 'flatsome-isotope-js', $uri . '/assets/libs/isotope.pkgd.min.js', array( 'jquery', 'flatsome-js' ), $version, true );

	// Google maps.
	$maps_api = trim( get_theme_mod( 'google_map_api' ) );
	if ( ! empty( $maps_api ) ) {
		wp_register_script( 'flatsome-maps', "//maps.googleapis.com/maps/api/js?key=$maps_api&callback=jQuery.noop", array( 'jquery' ), $version, true );
	}

	// Enqueue theme scripts.
	flatsome_enqueue_asset( 'flatsome-js', 'flatsome', array( 'jquery', 'hoverIntent' ) );

	$sticky_height = get_theme_mod( 'header_height_sticky', 70 );

	if ( is_admin_bar_showing() ) {
		$sticky_height = $sticky_height + 30;
	}

	$lightbox_close_markup = apply_filters('flatsome_lightbox_close_button', '<button title="%title%" type="button" class="mfp-close"><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>');

	$localize_data = array(
		'theme'              => array( 'version' => $version ),
		'ajaxurl'            => admin_url( 'admin-ajax.php' ),
		'rtl'                => is_rtl(),
		'sticky_height'      => $sticky_height, // Deprecated.
		'stickyHeaderHeight' => 0,
		'scrollPaddingTop'   => 0,
		'assets_url'         => $uri . '/assets/',
		'lightbox'           => array(
			'close_markup'     => $lightbox_close_markup,
			'close_btn_inside' => apply_filters( 'flatsome_lightbox_close_btn_inside', false ),
		),
		'user'               => array(
			'can_edit_pages' => current_user_can( 'edit_pages' ),
		),
		'i18n'               => array(
			'mainMenu'     => __( 'Main Menu', 'flatsome' ),
			'toggleButton' => __( 'Toggle', 'flatsome' ),
		),
		'options'            => array(
			'cookie_notice_version'          => get_theme_mod( 'cookie_notice_version', '1' ),
			'swatches_layout'                => get_theme_mod( 'swatches_layout' ),
			'swatches_disable_deselect'      => get_theme_mod( 'swatches_disable_deselect' ),
			'swatches_box_select_event'      => get_theme_mod( 'swatches_box_select_event' ),
			'swatches_box_behavior_selected' => get_theme_mod( 'swatches_box_behavior_selected' ),
			'swatches_box_update_urls'       => get_theme_mod( 'swatches_box_update_urls', '1' ),
			'swatches_box_reset'             => get_theme_mod( 'swatches_box_reset' ),
			'swatches_box_reset_limited'     => get_theme_mod( 'swatches_box_reset_limited' ),
			'swatches_box_reset_extent'      => get_theme_mod( 'swatches_box_reset_extent' ),
			'swatches_box_reset_time'        => get_theme_mod( 'swatches_box_reset_time', 300 ),
			'search_result_latency'          => get_theme_mod( 'search_result_latency', '0' ),
		),
	);

	if ( is_woocommerce_activated() ) {
		$wc_localize_data = array(
			'is_mini_cart_reveal' => flatsome_is_mini_cart_reveal(),
		);

		$localize_data = array_merge( $localize_data, $wc_localize_data );
	}

	// Add variables to scripts.
	wp_localize_script( 'flatsome-js', 'flatsomeVars', $localize_data );

	if ( is_woocommerce_activated() ) {
		flatsome_enqueue_asset( 'flatsome-theme-woocommerce-js', 'woocommerce', array( 'flatsome-js', 'woocommerce' ) );
	}

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	// Custom Properties polyfill for Internet Explorer.
	wp_register_script( 'css-vars-polyfill', 'https://cdn.jsdelivr.net/gh/nuxodin/ie11CustomProperties@4.0.1/ie11CustomProperties.min.js', array(), '4.0.1', true );
	wp_script_add_data( 'css-vars-polyfill', 'conditional', 'IE' );

	// IntersectionObserver polyfill for IE.
	wp_enqueue_script( 'intersection-observer-polyfill', 'https://cdn.jsdelivr.net/npm/intersection-observer-polyfill@0.1.0/dist/IntersectionObserver.js', array(), '0.1.0', true );
	wp_script_add_data( 'intersection-observer-polyfill', 'conditional', 'IE' );
}

add_action( 'wp_enqueue_scripts', 'flatsome_scripts', 100 );

/**
 * Set up UX Builder.
 */
function flatsome_ux_builder_setup() {
	// Add Ux Builder to post types.
	add_ux_builder_post_type( 'blocks' );
	add_ux_builder_post_type( 'product' );
	add_ux_builder_post_type( 'featured_item' );
}

add_action( 'init', 'flatsome_ux_builder_setup', 10 );

/**
 * Enqueue UX Builder scripts.
 *
 * @param  string $context Context is «editor» or content.
 */
function flatsome_ux_builder_scripts( $context ) {
	$uri     = get_template_directory_uri();
	$theme   = wp_get_theme( get_template() );
	$version = $theme->get( 'Version' );

	// Add UxBuilder assets.
	if ( $context == 'editor' ) {
		flatsome_enqueue_asset( 'ux-builder-flatsome', 'builder/custom/editor', array( 'ux-builder-core' ) );
		wp_enqueue_style( 'ux-builder-flatsome', $uri . '/assets/css/builder/custom/builder.css', array( 'ux-builder-core' ), $version );
	}
	if ( $context == 'content' ) {
		wp_enqueue_style( 'ux-builder-flatsome', $uri . '/assets/css/builder/custom/builder.css', null, $version );
		flatsome_enqueue_asset( 'ux-builder-flatsome', 'builder/custom/content', array(
			'flatsome-js',
			'flatsome-masonry-js',
		) );
	}
}

add_action( 'ux_builder_enqueue_scripts', 'flatsome_ux_builder_scripts', 10 );


if ( ! is_admin() && get_theme_mod( 'lazy_load_backgrounds', 1 ) ) {
	/**
	 * Lazy load backgrounds
	 */
	function flatsome_lazy_load_backgrounds_css() {
		echo '<style>.bg{opacity: 0; transition: opacity 1s; -webkit-transition: opacity 1s;} .bg-loaded{opacity: 1;}</style>';
	}

	add_filter( 'wp_head', 'flatsome_lazy_load_backgrounds_css' );
}

/**
 * Remove jQuery migrate.
 *
 * @param WP_Scripts $scripts WP_Scripts object.
 */
function flatsome_remove_jquery_migrate( $scripts ) {
	if ( ! get_theme_mod( 'jquery_migrate' ) ) return;
	if ( ! is_admin() && isset( $scripts->registered['jquery'] ) ) {
		$script = $scripts->registered['jquery'];

		if ( $script->deps ) { // Check whether the script has any dependencies.
			$script->deps = array_diff( $script->deps, array(
				'jquery-migrate',
			) );
		}
	}
}

add_action( 'wp_default_scripts', 'flatsome_remove_jquery_migrate' );

// Disable emoji scripts
if ( ! is_admin() && get_theme_mod( 'disable_emoji', 0 ) ) {
	remove_action('wp_head', 'print_emoji_detection_script', 7);
	remove_action('wp_print_styles', 'print_emoji_styles');
}

function flatsome_deregister_block_styles() {
	if ( ! is_admin() && get_theme_mod( 'disable_blockcss', 0 ) ) {
    wp_dequeue_style( 'wp-block-library' );
  }
}
add_action( 'wp_print_styles', 'flatsome_deregister_block_styles', 100 );

/**
 * Prefetch lazy-loaded chunks.
 */
function flatsome_prefetch_scripts( $urls, $type ) {
	static $manifest;

	$manifest_path = get_template_directory() . '/assets/manifest.json';
	$assets_url    = get_template_directory_uri() . '/assets';
	$theme         = wp_get_theme( get_template() );
	$version       = $theme->get( 'Version' );

	if ( empty( $manifest ) ) {
		if ( ! file_exists( $manifest_path ) ) {
			return $urls;
		}
		$manifest = wp_json_file_decode( $manifest_path, [ 'associative' => true ] );
	}

	$asset_handle_map = array(
		'js/flatsome'    => 'flatsome-js',
		'js/woocommerce' => 'flatsome-theme-woocommerce-js',
	);

	foreach ( $manifest as $key => $asset ) {
		if ( empty( $asset_handle_map[ $key ] ) ) continue;

		$handle = $asset_handle_map[ $key ];

		if ( wp_script_is( $handle, 'enqueued' ) ) {
			if ( $type === 'prefetch' ) {
				$script = wp_scripts()->registered[ $handle ];
				$urls[] = add_query_arg( 'ver', $script->ver, $script->src );
			}
			if ( ! empty( $asset[ $type ]['js'] ) ) {
				foreach ( $asset[ $type ]['js'] as $path ) {
					$urls[] = $assets_url . "/$path?ver=" . $version;
				}
			}
		}
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'flatsome_prefetch_scripts', 10, 2 );

/**
 * Add JSON to allowed file types.
 *
 * @param array $mimes Allowed file types.
 */
function flatsome_upload_mimes( $mimes ) {
	if ( ! isset( $mimes['json'] ) ) {
		$mimes['json'] = 'text/plain';
	}
	return $mimes;
}
add_filter( 'upload_mimes', 'flatsome_upload_mimes' );
