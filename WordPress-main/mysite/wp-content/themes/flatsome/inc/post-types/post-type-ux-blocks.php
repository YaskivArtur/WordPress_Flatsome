<?php
/* Register post menu */
register_post_type( 'blocks',
	array(
		'labels'              => array(
			'add_new_item'       => __( 'Add block', "blocks" ),
			'name'               => __( 'UX Blocks', "blocks" ),
			'singular_name'      => __( 'Block', "blocks" ),
			'edit_item'          => __( 'Edit Block', "blocks" ),
			'view_item'          => __( 'View Block', "blocks" ),
			'search_items'       => __( 'Search Blocks', "blocks" ),
			'not_found'          => __( 'No Blocks found', "blocks" ),
			'not_found_in_trash' => __( 'No Blocks found in Trash', "blocks" ),
		),
		'public'              => true,
		'has_archive'         => false,
		'show_in_menu'        => true,
		'supports'            => array( 'thumbnail', 'editor', 'title', 'revisions', 'custom-fields' ),
		'show_in_nav_menus'   => false,
		'exclude_from_search' => true,
		'rewrite'             => array( 'slug' => '' ),
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'query_var'           => true,
		'capability_type'     => 'page',
		'hierarchical'        => true,
		'menu_position'       => null,
		'show_in_rest'        => true,
		'rest_base'           => 'ux-blocks',
		'menu_icon'           => 'dashicons-tagcloud',
	)
);

function my_edit_blocks_columns() {
	$columns = array(
		'cb'        => '<input type="checkbox" />',
		'title'     => __( 'Title', 'blocks' ),
		'shortcode' => __( 'Shortcode', 'blocks' ),
		'date'      => __( 'Date', 'blocks' ),
	);

	return $columns;
}

add_filter( 'manage_edit-blocks_columns', 'my_edit_blocks_columns' );

function my_manage_blocks_columns( $column, $post_id ) {
	$post_data = get_post( $post_id, ARRAY_A );
	$slug      = $post_data['post_name'];
	add_thickbox();
	switch ( $column ) {
		case 'shortcode':
			echo '<textarea style="min-width:100%; max-height:30px; background:#eee;">[block id="' . $slug . '"]</textarea>';
			break;
	}
}

add_action( 'manage_blocks_posts_custom_column', 'my_manage_blocks_columns', 10, 2 );


/**
 * Disable gutenberg support for now.
 *
 * @param bool   $use_block_editor Whether the post type can be edited or not. Default true.
 * @param string $post_type        The post type being checked.
 *
 * @return bool
 */
function flatsome_blocks_disable_gutenberg( $use_block_editor, $post_type ) {
	return $post_type === 'blocks' ? false : $use_block_editor;
}

add_filter( 'use_block_editor_for_post_type', 'flatsome_blocks_disable_gutenberg', 10, 2 );
add_filter( 'gutenberg_can_edit_post_type', 'flatsome_blocks_disable_gutenberg', 10, 2 );


/**
 * Update block preview URL
 */
function ux_block_scripts() {
	global $typenow;
	if ( 'blocks' == $typenow && isset( $_GET["post"] ) ) {
		?>
		<script>
          jQuery(document).ready(function ($) {
            var block_id = $('input#post_name').val()
            $('#submitdiv').
              after('<div class="postbox"><h2 class="hndle">Shortcode</h2><div class="inside"><p><textarea style="width:100%; max-height:30px;">[block id="' + block_id +
                '"]</textarea></p></div></div>')
          })
		</script>
		<?php
	}
}

add_action( 'admin_head', 'ux_block_scripts' );

function ux_block_frontend() {
	if ( isset( $_GET["block"] ) ) {
		?>
		<script>
          jQuery(document).ready(function ($) {
            $.scrollTo('#<?php echo esc_attr( $_GET["block"] );?>')
          })
		</script>
		<?php
	}
}

add_action( 'wp_footer', 'ux_block_frontend' );

function block_shortcode( $atts, $content = null ) {
	global $post;

	extract( shortcode_atts( array(
			'id' => '',
		),
			$atts
		)
	);

	// Abort if ID is empty.
	if ( empty ( $id ) ) {
		return '<p><mark>No block ID is set</mark></p>';
	}

	if ( is_woocommerce_activated() && is_shop() ) {
		$post = get_post( wc_get_page_id( 'shop' ) );
	}

	if ( is_home() ) $post = get_post( get_option('page_for_posts') );

	$post_id  = flatsome_get_block_id( $id );
	$the_post = $post_id ? get_post( $post_id, OBJECT, 'display' ) : null;

	if ( $the_post ) {
		$html = $the_post->post_content;

		if ( empty( $html ) ) {
			$html = '<p class="lead shortcode-error">Open this in UX Builder to add and edit content</p>';
		}

		// Add edit link for admins.
		if ( isset( $post ) && current_user_can( 'edit_pages' )
		     && ! is_customize_preview()
		     && function_exists( 'ux_builder_is_active' )
		     && ! ux_builder_is_active()
			 && apply_filters( 'flatsome_show_block_edit_tooltip', true ) ) {
			$edit_link         = ux_builder_edit_url( $post->ID, $post_id );
			$edit_link_backend = admin_url( 'post.php?post=' . $post_id . '&action=edit' );
			$html              = '<div class="block-edit-link" data-title="Edit Block: ' . get_the_title( $post_id ) . '"   data-backend="' . esc_url( $edit_link_backend )
			                     . '" data-link="' . esc_url( $edit_link ) . '"></div>' . $html . '';
		}
	} else {
		$html = '<p class="text-center"><mark>Block <b>"' . esc_html( $id ) . '"</b> not found</mark></p>';
	}

	return do_shortcode( $html );
}

add_shortcode( 'block', 'block_shortcode' );


if ( ! function_exists( 'blocks_categories' ) ) {
	/**
	 * Add block categories support
	 */
	function blocks_categories() {
		$args = array(
			'hierarchical'      => true,
			'public'            => false,
			'show_ui'           => true,
			'show_in_nav_menus' => true,
		);
		register_taxonomy( 'block_categories', array( 'blocks' ), $args );

	}

	// Hook into the 'init' action
	add_action( 'init', 'blocks_categories', 0 );
}
