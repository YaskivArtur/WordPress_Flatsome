<?php

namespace UxBuilder\Post;

use UxBuilder\Transformers\StringToArray;
use UxBuilder\Elements\ElementOptions;

class PostArray {

  protected $post;
  protected $post_array;
  protected $used_ids;
  protected $preserved_ids;

  public function __construct( $post ) {
    $this->post = $post;
    $this->post_array = $this->create_array();
  }

  public function create_array() {
    $self         = $this;
    $post_content = $this->post->post_content;
    $transformer  = ux_builder( 'to-array' );

    if ( has_blocks( $post_content ) ) {
      $blocks   = parse_blocks( $post_content );
      $elements = array();

      $content_block = has_block( 'flatsome/uxbuilder', $post_content )
        ? 'flatsome/uxbuilder'
        : 'core/html';

      foreach ( $blocks as $block ) {
        if ( empty( $block['blockName'] ) ) {
          continue;
        }

        if ( $block['blockName'] === $content_block ) {
          if ( ! empty( $block['innerHTML'] ) ) {
            $elements = array_merge(
              $elements,
              $transformer->transform( $block['innerHTML'] )
            );
          }
        } else {
          array_push( $elements, array(
            'tag'     => 'ux_gutenberg',
            'options' => array(),
            'content' => serialize_block( $block ),
          ) );
        }
      }

      // Merge adjacent `ux_gutenberg` elements.
      for ( $i = count( $elements ) - 1; $i >= 0; $i-- ) {
        if ( ! empty( $elements[ $i + 1 ] ) ) {
          $block = &$elements[ $i ];
          $next_block = $elements[ $i + 1 ];

          if (
            $block['tag'] === 'ux_gutenberg' &&
            $next_block['tag'] === 'ux_gutenberg'
          ) {
            $block['content'] .= "\n\n" . $next_block['content'];
            array_splice( $elements, $i + 1, 1 );
          }
        }
      }

      $this->post_array = array(
        array(
          'tag'      => '_root',
          'options'  => array(),
          'children' => $elements,
        ),
      );
    } else {
      $this->post_array = $transformer->transform( "[_root]{$post_content}[/_root]" );
    }

    ux_builder_content_array_walk( $this->post_array, function ( &$item ) use ( $self ) {
      $item['options'] = $self->get_options( $item['tag'], $item['options'] );
    });

    return array_shift( $this->post_array );
  }

  /**
   * Gets the generated post array.
   *
   * @return array
   */
  public function get_array() {
    return $this->post_array;
  }

  /**
   * Get options for an element.
   *
   * @param  string $tag
   * @param  array  $values
   * @return array
   */
  public function get_options( $tag, $values ) {
    $shortcode = ux_builder_shortcodes()->get( $tag );
    $options = new ElementOptions( $shortcode['options'] );
    return $options->set_values( $values )->camelcase()->get_values();
  }
}
