<?php

/**
 * Get the Flatsome Envato instance.
 */
function flatsome_envato() {
	return Flatsome_Envato::get_instance();
}

/**
 * Enqueues a webpack bundle.
 *
 * @param string $handle       Script handle name.
 * @param string $entrypoint   The entrypoint name.
 * @param array  $dependencies Extra dependencies.
 * @return void
 */
function flatsome_enqueue_asset( $handle, $entrypoint, $dependencies = array() ) {
	$filename     = "js/$entrypoint.js";
	$theme        = wp_get_theme( get_template() );
	$version      = $theme->get( 'Version' );
	$template_dir = get_template_directory();
	$template_uri = get_template_directory_uri();
	$assets_path  = "$template_dir/assets/assets.php";
	$script_url   = "$template_uri/assets/$filename";

	$assets = file_exists( $assets_path ) ? require $assets_path : array();

	$script_asset = isset( $assets[ $filename ] )
		? $assets[ $filename ]
		: array( 'dependencies' => array(), 'version' => $version );

	wp_enqueue_script(
		$handle,
		$script_url,
		array_merge( $script_asset['dependencies'], $dependencies ),
		$script_asset['version'],
		true
	);
}

/**
 * Get Flatsome option
 *
 * @deprecated in favor of get_theme_mod()
 *
 * @return string
 */
function flatsome_option($option) {
	// Get options
	return get_theme_mod( $option, flatsome_defaults($option) );
}

if(!function_exists('flatsome_dummy_image')) {
  function flatsome_dummy_image() {
    return get_template_directory_uri().'/assets/img/missing.jpg';
  }
}

/**
 * Checks current WP version against a given version.
 *
 * @param string $version The version to check for.
 *
 * @return bool Returns true if WP version is equal or higher then given version.
 */
function flatsome_wp_version_check( $version = '5.4' ) {
	global $wp_version;
	if ( version_compare( $wp_version, $version, '>=' ) ) {
		return true;
	}

	return false;
}


/* Check WooCommerce Version */
if( ! function_exists('fl_woocommerce_version_check') ){
	function fl_woocommerce_version_check( $version = '2.6' ) {
    if( version_compare( WC()->version, $version, ">=" ) ) {
      return true;
    }
	  return false;
	}
}

/* Get Site URL shortcode */
if( ! function_exists( 'flatsome_site_path' ) ) {
  function flatsome_site_path(){
    return site_url();
  }
}
add_shortcode('site_url', 'flatsome_site_path');
add_shortcode('site_url_secure', 'flatsome_site_path');


/* Get Year */
if( ! function_exists( 'flatsome_show_current_year' ) ) {
  function flatsome_show_current_year(){
      return date('Y');
  }
}
add_shortcode('ux_current_year', 'flatsome_show_current_year');

function flatsome_get_post_type_items($post_type, $args_extended=array()) {
  global $post;
  $old_post = $post;
  $return = false;

  $args = array(
    'post_type'=>$post_type
    , 'post_status'=>'publish'
    , 'showposts'=>-1
    , 'order'=>'ASC'
    , 'orderby'=>'title'
  );

  if ($args && count($args_extended)) {
    $args = array_merge($args, $args_extended);
  }

  query_posts($args);

  if (have_posts()) {
    global $post;
    $return = array();

    while (have_posts()) {
      the_post();
      $return[get_the_ID()] = $post;
    }
  }

  wp_reset_query();
  $post = $old_post;

  return $return;
}

function flatsome_is_request( $type ) {
  switch ( $type ) {
    case 'admin' :
      return is_admin();
    case 'ajax' :
      return defined( 'DOING_AJAX' );
    case 'frontend' :
      return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
  }
}

function flatsome_api_url() {
  $api_url = 'https://flatsome-api.netlify.com';

  if ( defined( 'FLATSOME_API_URL' ) && FLATSOME_API_URL ) {
    $api_url = FLATSOME_API_URL;
  }

  return $api_url;
}

function flatsome_facebook_accounts() {
  $theme_mod = get_theme_mod( 'facebook_accounts', array() );

  return array_filter( $theme_mod, function ( $account ) {
    return ! empty( $account ) && is_array( $account );
  } );
}

/**
 * Returns the current Facebook GraphAPI version beeing used.
 *
 * @since 3.13
 *
 * @return string
 */
function flatsome_facebook_api_version() {
  return 'v14.0';
}

// Get block id by ID or slug.
function flatsome_get_block_id( $post_id ) {
  global $wpdb;

  if ( empty ( $post_id ) ) {
    return null;
  }

  // Get post ID if using post_name as id attribute.
  if ( ! is_numeric( $post_id ) ) {
    $post_id = $wpdb->get_var(
      $wpdb->prepare(
        "SELECT ID FROM $wpdb->posts WHERE post_type = 'blocks' AND post_name = %s",
        $post_id
      )
    );
  }

  // Polylang support.
  if ( function_exists( 'pll_get_post' ) ) {
    if ( $lang_id = pll_get_post( $post_id ) ) {
      $post_id = $lang_id;
    }
  }

  // WPML Support.
  if ( function_exists( 'icl_object_id' ) ) {
    if ( $lang_id = icl_object_id( $post_id, 'blocks', false, ICL_LANGUAGE_CODE ) ) {
      $post_id = $lang_id;
    }
  }

  return $post_id;
}

/**
 * Retrieve a list of blocks.
 *
 * @param array|string $args Optional. Array or string of arguments.
 *
 * @return array|false List of blocks matching defaults or `$args`.
 */
function flatsome_get_block_list_by_id( $args = '' ) {

	$defaults = array(
		'option_none' => '',
	);

	$parsed_args = wp_parse_args( $args, $defaults );

	$blocks = array();

	if ( $parsed_args['option_none'] ) {
		$blocks = array( 0 => $parsed_args['option_none'] );
	}
	$posts = flatsome_get_post_type_items( 'blocks' );
	if ( $posts ) {
		foreach ( $posts as $value ) {
			$blocks[ $value->ID ] = $value->post_title;
		}
	}

	return $blocks;
}

/**
 * Retrieves a page given its title.
 *
 * @param string       $page_title Page title.
 * @param string       $output     Optional. The required return type. One of OBJECT, ARRAY_A, or ARRAY_N, which
 *                                 correspond to a WP_Post object, an associative array, or a numeric array,
 *                                 respectively. Default OBJECT.
 * @param string|array $post_type  Optional. Post type or array of post types. Default 'page'.
 *
 * @return WP_Post|array|null WP_Post (or array) on success, or null on failure.
 */
function flatsome_get_page_by_title( $page_title, $output = OBJECT, $post_type = 'page' ) {
	$args  = array(
		'title'                  => $page_title,
		'post_type'              => $post_type,
		'post_status'            => get_post_stati(),
		'posts_per_page'         => 1,
		'update_post_term_cache' => false,
		'update_post_meta_cache' => false,
		'no_found_rows'          => true,
		'orderby'                => 'post_date ID',
		'order'                  => 'ASC',
	);
	$query = new WP_Query( $args );
	$pages = $query->posts;

	if ( empty( $pages ) ) {
		return null;
	}

	return get_post( $pages[0], $output );
}

/**
 * Calls a shortcode function by its tag name.
 *
 * @param string $tag     The shortcode of the function to be called.
 * @param array  $atts    The attributes to pass to the shortcode function (optional).
 * @param array  $content The content of the shortcode (optional).
 *
 * @return bool|string If a shortcode tag doesn't exist => false, if exists => the result of the shortcode.
 */
function flatsome_apply_shortcode( $tag, $atts = array(), $content = null ) {
	global $shortcode_tags;

	if ( ! isset( $shortcode_tags[ $tag ] ) ) return false;

	return call_user_func( $shortcode_tags[ $tag ], $atts, $content, $tag );
}

/**
 * Hides characters in a string.
 *
 * @param string $string The token.
 * @param int    $visible_chars How many characters to show.
 * @return string
 */
function flatsome_hide_chars( $string, $visible_chars = 4 ) {
	if ( ! is_string( $string ) ) {
		$string = '';
	}
	if ( strlen( $string ) <= $visible_chars ) {
		$visible_chars = strlen( $string ) - 2;
	}

	$chars = str_split( $string );
	$end   = strlen( $string ) - $visible_chars;

	for ( $i = $visible_chars; $i < $end; $i++ ) {
		if ( $chars[ $i ] === '-' ) continue;
		$chars[ $i ] = '*';
	}

	return implode( '', $chars );
}

/**
 * Normalizes the theme directory name.
 *
 * @param string $slug Optional theme slug.
 * @return string
 */
function flatsome_theme_key( $slug = null ) {
	if ( empty( $slug ) ) {
		$slug = basename( get_template_directory() );
	}

	$slug = trim( $slug );
	$slug = preg_replace( '/[,.\s]+/', '-', $slug );
	$slug = strtolower( $slug );

	return $slug;
}

/**
 * Callback to sort on priority.
 *
 * @param int $a First item.
 * @param int $b Second item.
 *
 * @return bool
 */
function flatsome_sort_on_priority( $a, $b ) {
	if ( ! isset( $a['priority'], $b['priority'] ) ) {
		return - 1;
	}

	if ( $a['priority'] === $b['priority'] ) {
		return 0;
	}

	return $a['priority'] < $b['priority'] ? - 1 : 1;
}

/**
 * Check if support is expired.
 *
 * @return bool
 */
function flatsome_is_support_expired() {
	_deprecated_function( __FUNCTION__, '3.14' );
	return true;
}

/**
 * Check if support time is invalid.
 *
 * @param string $support_ends Support end timestamp.
 *
 * @return bool True if invalid false otherwise.
 */
function flatsome_is_invalid_support_time( $support_ends ) {
	_deprecated_function( __FUNCTION__, '3.14' );
	return false;
}

/**
 * Checks whether theme is registered.
 *
 * @return bool
 */
function flatsome_is_theme_enabled() {
	return flatsome_envato()->registration->is_registered();
}
