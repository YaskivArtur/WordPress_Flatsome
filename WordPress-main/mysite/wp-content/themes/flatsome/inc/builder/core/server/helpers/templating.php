<?php

function ux_builder_html_atts( $attributes ) {
  $output = '';

  foreach( $attributes as $key => $value ) {
      $output .= is_numeric( $key ) ? $value . ' ' : $key . '="' . $value . '" ';
  }

  return trim( $output, ' ' );
}

function ux_builder_render( $__template, $__variables = array() ) {
  extract( $__variables );
  unset( $__variables );

  if ( in_array( $__template, array( 'editor', 'iframe-frontend', 'media', 'tinymce' ), true ) ) {
    include ux_builder_path( "/server/templates/{$__template}.php" );
  } else {
    wp_die( sprintf( "No template for <em>%s</em> exist.", esc_html( $__template ) ) );
  }
}
