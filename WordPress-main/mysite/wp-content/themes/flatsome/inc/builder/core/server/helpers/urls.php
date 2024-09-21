<?php

/**
 * Get url to a file relative to plugin directory.
 *
 * @param  string $path
 * @return string
 */
function ux_builder_url( $path = '' ) {
  return UX_BUILDER_URL . $path;
}

/**
 * Get url to a file relative to the assets directory.
 *
 * @param string $asset [description]
 * @return string
 */
function ux_builder_asset( $path ) {
  return ux_builder_url( "/assets/$path" );
}

/**
 * Renders a url for editing a post with the UX Builder.
 *
 * @param  number $post_id Post to preview.
 * @param  number $edit_post_id Post to edit.
 * @param  string $type
 * @return string
 */
function ux_builder_edit_url( $post_id, $edit_post_id = null, $type = 'editor' ) {
  $query = array(
    'edit_post_id' => $edit_post_id,
    'app' => 'uxbuilder',
    'type' => $type
  );

  $edit_link = get_edit_post_link( $post_id, 'raw' );

  // Polylang support.
  if (
    function_exists( 'pll_get_post_language' ) &&
    function_exists( 'PLL' )
  ) {
    $slug = pll_get_post_language( $post_id );
    $force_lang = PLL()->links_model->options['force_lang'];

    // Rewrite URL if the language has another domain.
    if ( $slug && $force_lang === 3 ) {
      $lang = PLL()->model->get_language( $slug );
      $edit_link = PLL()->links_model->switch_language_in_link( $edit_link, $lang );
    }
  }

  // WPML Support.
  if ( function_exists( 'icl_get_setting' ) ) {
    global $wpml_url_converter;

    $language = apply_filters( 'wpml_post_language_details', null, $post_id );
    $negotiation_type = icl_get_setting( 'language_negotiation_type' );

    // Rewrite URL if the language has another domain.
    // Use looose comparison because it can be a string.
    if ( $negotiation_type == 2 ) {
      $url_strategy = $wpml_url_converter->get_strategy();
      // Replace wp-admin in URL to force convert it...
      $edit_link = str_replace( 'wp-admin', '{{replaced}}', $edit_link  );
      $edit_link = $url_strategy->convert_url_string( $edit_link, $language['language_code'] );
      $edit_link = str_replace( '{{replaced}}', 'wp-admin', $edit_link  );
      $edit_link = str_replace( '/?', '?', $edit_link  );
    }
  }

  $edit_link = add_query_arg( 'app', 'uxbuilder', $edit_link );
  $edit_link = add_query_arg( 'type', $type, $edit_link );
  $edit_link = add_query_arg( 'edit_post_id', $edit_post_id, $edit_link );

  return $edit_link;
}

/**
 * Renders a url for the iframe.
 *
 * @return string
 */
function ux_builder_iframe_url() {
  $post_id = array_key_exists( 'post', $_GET ) ? $_GET['post'] : null;
  $edit_post_id = array_key_exists( 'edit_post_id', $_GET ) ? $_GET['edit_post_id'] : null;
  $permalink = get_permalink( $post_id );

  $permalink = add_query_arg( 'post_id', $post_id, $permalink );
  $permalink = add_query_arg( 'uxb_iframe', true, $permalink );

  if ( $edit_post_id ) {
    $permalink = add_query_arg( 'edit_post_id', $edit_post_id, $permalink );
  }

  // Fix SSL
  if ( is_ssl() ) {
    $permalink = str_replace( 'http:', 'https:', $permalink );
  }

  return $permalink;
}

