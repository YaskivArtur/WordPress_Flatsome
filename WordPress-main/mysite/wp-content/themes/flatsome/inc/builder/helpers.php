<?php

function flatsome_ux_builder_template( $path ) {
  ob_start();
  include get_template_directory() . '/inc/builder/shortcodes/templates/' . $path;
  return ob_get_clean();
}

function flatsome_ux_builder_thumbnail( $name ) {
  return get_template_directory_uri() . '/inc/builder/shortcodes/thumbnails/' . $name . '.svg';
}

function flatsome_ux_builder_template_thumb( $name ) {
  return get_template_directory_uri() . '/inc/builder/templates/thumbs/' . $name . '.jpg';
}

function flatsome_ux_builder_image_sizes( $sizes = array() ) {
  $image_sizes      = get_intermediate_image_sizes();
  $additional_sizes = wp_get_additional_image_sizes();

  $sizes['original'] = __( 'Original', 'flatsome' );

  foreach ( $image_sizes as $key ) {
    if ( isset( $additional_sizes[ $key ] ) ) {
      $width  = $additional_sizes[ $key ]['width'];
      $height = $additional_sizes[ $key ]['height'];
    } else {
      $width  = get_option( $key . '_size_w' );
      $height = get_option( $key . '_size_h' );
    }

    $name = ucfirst( str_replace( '_', ' ', $key ) );
    $size = join( 'x', array_filter( array( $width, $height ) ) );

    if ( $size != $key ) {
      $name .= " ($size)";
    }

    $sizes[ $key ] = $name;
  }

  if ( is_woocommerce_activated() ) {
    foreach ( array( 'shop_catalog', 'shop_single', 'shop_thumbnail' ) as $key ) {
      if ( array_key_exists( $key, $sizes ) ) {
        unset( $sizes[ $key ] );
      }
    }
  }

  return $sizes;
}
