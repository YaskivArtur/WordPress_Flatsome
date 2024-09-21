<?php
/**
 * Server-side rendering of the `flatsome/uxbuilder` block.
 *
 * @package Flatsome
 */

/**
 * Registers the `flatsome/uxbuilder` component and block. This block doesn't
 * need a render function because it only contains raw HTML and shortcodes.
 */
function flatsome_register_uxbuilder_block() {
	$path     = __DIR__ . '/block.json';
	$metadata = json_decode( file_get_contents( $path ), true );

	register_block_type(
		$metadata['name'],
		$metadata
	);
}
add_action( 'init', 'flatsome_register_uxbuilder_block' );
