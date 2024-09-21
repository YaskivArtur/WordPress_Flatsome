<?php

add_action( 'wp_ajax_flatsome_block_title', function () {
	global $wpdb;

	$block_id = isset( $_GET['block_id'] )
		? intval( $_GET['block_id'] )
		: 0;

	if ( empty( $block_id ) ) {
		return wp_send_json_success( array( 'block_title' => '' ) );
	}

	$query = $wpdb->prepare(
		"SELECT post_title FROM $wpdb->posts WHERE post_type = 'blocks' AND id = %d",
		$block_id
	);

	wp_send_json_success( array(
		'block_title' => $wpdb->get_var( $query )
	) );
} );
