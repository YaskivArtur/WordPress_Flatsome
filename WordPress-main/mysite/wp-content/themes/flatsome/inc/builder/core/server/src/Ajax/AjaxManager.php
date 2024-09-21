<?php

namespace UxBuilder\Ajax;

use UxBuilder\Post\PostArray;
use UxBuilder\Elements\ElementOptions;

class AjaxManager {

  protected $data;
  protected $do_shortcode;
  protected $posts;
  protected $post_saver;
  protected $wp_attachment;
  protected $terms;

  public function __construct() {
    $this->data = new Data();
    $this->do_shortcode = new DoShortcode();
    $this->posts = new Posts();
    $this->post_saver = new PostSaver();
    $this->wp_attachment = new WpAttachment();
    $this->terms = new Terms();

    add_action( 'wp_ajax_ux_builder_get_data', array( $this->data, 'get_data' ) );
    add_action( 'wp_ajax_ux_builder_search_posts', array( $this->posts, 'search_posts' ) );
    add_action( 'wp_ajax_ux_builder_get_posts', array( $this->posts, 'get_posts' ) );
    add_action( 'wp_ajax_ux_builder_save', array( $this->post_saver, 'save' ) );
    add_action( 'wp_ajax_ux_builder_get_attachment', array( $this->wp_attachment, 'get_attachment' ) );
    add_action( 'wp_ajax_ux_builder_search_terms', array( $this->terms, 'search_terms' ) );
    add_action( 'wp_ajax_ux_builder_get_terms', array( $this->terms, 'get_terms' ) );
    add_action( 'wp_ajax_ux_builder_save_custom_template', array( $this, 'save_custom_template' ) );
    add_action( 'wp_ajax_ux_builder_delete_custom_template', array( $this, 'delete_custom_template' ) );
    add_action( 'wp_ajax_ux_builder_to_array', array( $this, 'to_array' ) );
    add_action( 'wp_ajax_ux_builder_copy_as_shortcode', array( $this, 'copy_as_shortcode' ) );
    add_action( 'wp_ajax_ux_builder_parse_presets', array( $this, 'parse_presets' ) );
    add_action( 'wp_ajax_ux_builder_import_media', array( $this, 'import_media' ) );

    if ( ! array_key_exists( 'ux_builder_action', $_POST ) ) return;

    add_action( 'template_redirect', array( $this->do_shortcode, 'do_shortcode' ), 0 );
  }

  public function save_custom_template () {
    $data = $_POST['data'];

    // Return an error if nonce is invalid.
    check_ajax_referer( 'ux-builder-' . $data['post_id'], 'security' );

    $transformer = ux_builder( 'to-string' );
    $content     = json_decode( stripslashes( $data['content'] ), true );
    $tag         = wp_unslash( $_POST['data']['tag'] );

    if ( empty( $tag ) ) {
      return wp_send_json_error( array(
        'message' => 'Template tag content cannot be empty.',
      ) );
    }

    if ( $content['tag'] === '_root' ) {
      $content = $content['children'];
    } else {
      $content = array( $content );
    }

    $post_title   = sanitize_text_field( $data['title'] );
    $post_content = $transformer->transform( $content );

    if ( empty( $post_content ) ) {
      return wp_send_json_error(
        array( 'message' => 'Template must have content.' )
      );
    }

    if ( empty( $post_title ) ) {
      return wp_send_json_error(
        array( 'message' => 'Name cannot be empty.' )
      );
    }

    $args = array(
      'post_type'    => 'ux_template',
      'post_title'   => $post_title,
      'post_content' => trim( $post_content ),
      'post_status'  => 'publish',
    );

    if ( array_key_exists( 'id', $data ) ) {
      $args['ID'] = $data['id'];
      $post_id = wp_update_post( $args, true );
    } else {
      $post_id = wp_insert_post( $args, true );
    }

    if ( is_wp_error( $post_id ) ) {
      return wp_send_json_error( array(
        'message' => $post_id->get_error_message(),
      ) );
    }

    update_post_meta( $post_id, '_ux_tag', $tag, true );

    if ( $tag === '_root' ) {
      update_post_meta( $post_id, '_ux_page_template', $data['template'], true );
    }

    $presets = array();

    try {
      $presets = $this->parse_presets_for_tag( $tag );
    } catch ( \Exception $err ) {
      return wp_send_json_error( array(
        'message' => $err->getMessage(),
      ) );
    }

    return wp_send_json_success( compact( 'tag', 'presets' ) );
  }

  public function delete_custom_template () {
    check_ajax_referer( 'ux-builder-' . $_POST['post_id'], 'security' );

    $post_id   = intval( $_POST['id'] );
    $post_type = get_post_type( $post_id );
    $tag       = get_post_meta( $post_id, '_ux_tag', true );

    if ( 'ux_template' !== $post_type ) {
      return wp_send_json_error( array(
        'message' => "Cannot remove post with type {$post_type}.",
      ) );
    }

    if ( wp_delete_post( $post_id, true ) && is_string( $tag ) ) {
      return wp_send_json_success(
        array(
          'tag'     => $tag,
          'presets' => $this->parse_presets_for_tag( $tag ),
        )
      );
    }

    return wp_send_json_error( array(
      'message' => 'Failed to delete template.',
    ) );
  }

  /**
   * Converts content or a template to an array.
   * Used by the import function and template selector.
   */
  public function to_array () {
    $content = '';

    if ( array_key_exists( 'content', $_POST ) ) {
      $content = stripslashes( $_POST['content'] );
    } else if ( array_key_exists( 'id', $_POST ) ) {
      $id = $_POST['id'];
      $template = ux_builder_get_template( $id );
      $content = $template['content'];
    }

    $post_array = new PostArray( (object) array(
      'post_content' => $content
    ) );

    return wp_send_json_success( array(
      'content' => $post_array->get_array()
    ) );
  }

	/**
	 * Converts a shortcode array to raw shortcode content.
	 */
	public function copy_as_shortcode() {
		if ( ! isset( $_POST['post_id'] ) || ! (int) $_POST['post_id'] ) {
			wp_die();
		}

		$post_id   = (int) $_POST['post_id'];
		$shortcode = array();

		check_ajax_referer( 'ux-builder-' . $post_id, 'security' );

		if ( ! empty( $_POST['data'] ) && ! empty( $_POST['data']['shortcode'] ) ) {
			$shortcode = wp_unslash( (array) $_POST['data']['shortcode'] );
		}

		$transformer = ux_builder( 'to-string' );
		$raw         = $transformer->transform( array( $shortcode ) );

		wp_send_json_success( array(
			'content' => $raw,
		) );
	}

  /**
   * Importa external meda files.
   */
  public function import_media () {
    $id  = intval( $_POST['id'] );
    $url = $_POST['url'];

    if ( ! flatsome_envato()->is_registered() ) {
      return wp_send_json_error( array(
        'message' => 'Must register site to import',
      ) );
    }

    if ( ! preg_match( '/^http(s)?:\/\/studio\.uxthemes\.com\//', $url ) ) {
      return wp_send_json_error( array(
        'message' => 'Invalid URL',
      ) );
    }

    // 1. Check if image is already imported by its ID.
    $query = new \WP_Query( array(
      'post_type' => 'attachment',
      'post_status' => 'inherit',
      'meta_query' => array(
        array( 'key' => '_flatsome_studio_id', 'value' => $id, 'compare' => '=' )
      )
    ) );

    if ( $query->have_posts() ) {
      return wp_send_json_success( array(
        'id' => $query->posts[0]->ID,
      ) );
    }

    // 2. Download image from URL.
    $file = array();
    $file['name'] = basename( $url );
    $file['tmp_name'] = download_url( $url );

    if ( is_wp_error( $file['tmp_name'] ) ) {
      @unlink( $file['tmp_name'] );
      return new \WP_Error( 'flatsome', 'Could not download image from Flatsome Studio.' );
    }

    // 3. Add image to media library.
    $attachment_id = media_handle_sideload( $file, 0 );
    $attach_data = wp_generate_attachment_metadata( $attachment_id,  get_attached_file( $attachment_id ) );
    wp_update_attachment_metadata( $attachment_id,  $attach_data );
    update_post_meta( $attachment_id, '_flatsome_studio_id', $id );

    // 4. Return local ID and URL.
    return wp_send_json_success( array(
      'id' => $attachment_id,
    ) );
  }

  /**
   * Parse presets for a shortcode.
   */
  public function parse_presets () {
    $tag     = wp_unslash( $_GET['tag'] );
    $presets = array();

    try {
      $presets = $this->parse_presets_for_tag( $tag );
    } catch ( \Exception $err ) {
      return wp_send_json_error( array(
        'message' => $err->getMessage(),
      ) );
    }

    return wp_send_json_success( compact( 'presets' ) );
  }

  protected function parse_presets_for_tag( $tag ) {
    $shortcode   = ux_builder_shortcodes()->get( $tag );

    if ( ! $shortcode || ! $tag ) {
      return array();
    }

    $transformer = ux_builder( 'to-array' );

    $templates = array_map( function ( $template ) use ( $tag, $transformer ) {
      $array = $transformer->transform( $template['content'] );

      ux_builder_content_array_walk( $array, function ( &$item ) {
        $shortcode       = ux_builder_shortcodes()->get( $item['tag'] );
        $options         = new ElementOptions( $shortcode['options'] );
        $item['options'] = $options->set_values( $item['options'] )->camelcase()->get_values();
      });

      $template['tag']     = $tag;
      $template['raw']     = trim( $template['content'] );
      $template['content'] = array_shift( $array );

      return $template;
    }, $shortcode['presets'] );

    $ux_templates = get_posts(
      array(
        'post_type' => 'ux_template',
        'numberposts' => -1,
        'meta_query' => array(
          array(
            'key'     => '_ux_tag',
            'value'   => $tag,
            'compare' => '=',
          )
        ),
      )
    );

    /**
     * Parse custom templates created by user.
     */
    $custom_templates = array_map( function ( $post ) use ( $tag, $transformer ) {
      $array = $transformer->transform( $post->post_content );

      ux_builder_content_array_walk( $array, function ( &$item ) {
        $shortcode       = ux_builder_shortcodes()->get( $item['tag'] );
        $options         = new ElementOptions( $shortcode['options'] );
        $item['options'] = $options->set_values( $item['options'] )->camelcase()->get_values();
      });

      return array(
        'tag'      => $tag,
        'id'       => $post->ID,
        'name'     => $post->post_title,
        'raw'      => $post->post_content,
        'content'  => array_shift( $array ),
        'template' => get_post_meta( $post->ID, '_ux_page_template', true ),
        'custom'   => true,
      );
    }, $ux_templates );

    return array_merge( $custom_templates, $templates );
  }
}
