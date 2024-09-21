<?php

namespace UxBuilder\Ajax;

class DoShortcode {

  public function do_shortcode() {
    $post_id = isset( $_POST['post_id'] ) ? (int) $_POST['post_id'] : 0;
    $nonce   = isset( $_POST['security'] ) ? $_POST['security'] : '';

	define( 'UX_BUILDER_DOING_AJAX', true );

    if ( ! wp_verify_nonce( $nonce, 'ux-builder-' . $post_id ) ) {
      echo '<div class="uxb-error">';
      echo __( 'Sorry, an error occurred while rendering this element.', 'flatsome' );
      echo '</div>';
      die;
    }

	define( 'UX_BUILDER_AJAX_REQUEST', true );

    $shortcode = wp_parse_args( $_POST['ux_builder_shortcode'], array(
      'tag' => '', '$id' => '', 'options' => array()
    ) );

    setup_postdata( get_the_ID() );

    // Get current shortcode data.
    $current_shortcode = ux_builder_shortcodes()->get( $shortcode['tag'] );

    // Set <content/> as shortcode content to allow nested
    // shortcodes if current shortcode is a container.
    if( $current_shortcode['type'] == 'container' ) {
        $shortcode['children'] = array( array(
            'tag' => 'text',
            'options' => array(),
            'content' => '<content></content>',
        ) );
    }

    // Render and return the shortcode markup.
    echo do_shortcode( ux_builder( 'to-string' )->transform( array( $shortcode ) ) );

    die;
  }
}
