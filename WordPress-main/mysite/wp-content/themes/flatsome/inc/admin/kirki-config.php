<?php
/**
 * Configuration for the Kirki Customizer
 */

if ( ! function_exists( 'flatsome_kirki_update_url' ) ) {
	function flatsome_kirki_update_url( $config ) {
		$config['url_path'] = get_template_directory_uri() . '/inc/admin/kirki/';

		return $config;
	}
}
add_filter( 'kirki_config', 'flatsome_kirki_update_url' );

/**
 * Disable default Kirki modules.
 *
 * @param array $modules List of default modules.
 *
 * @return array Filtered list of modules.
 */
function flatsome_kirki_modules( $modules ) {

	// If Google CDN is enabled we don't load the css module to prevent Kirki generating css and download fonts.
	if ( get_theme_mod( 'google_fonts_cdn' ) ) {
		unset( $modules['css'] );
	}

	unset( $modules['css-vars'] );
	unset( $modules['icons'] );
	unset( $modules['loading'] );
	unset( $modules['selective-refresh'] );
	unset( $modules['gutenberg'] );

	return $modules;
}

add_filter( 'kirki_modules', 'flatsome_kirki_modules' );

/**
 * Custom option sanitize callback.
 */
function flatsome_custom_sanitize( $content ) {
	return $content;
}

/**
 * Quotes font-family value if needed and add font-family fallback.
 *
 * @param string $family The value.
 *
 * @return string
 * @see \Kirki_Output_Property_Font_Family
 */
function flatsome_parse_font_family( $family ) {
	if ( ! is_string( $family ) ) {
		return '';
	}

	// Prep Kirki standard fonts stack value (ex. Georgia,Times,"Times New Roman",serif)
	$family = str_replace( '&quot;', '"', $family );

	// Add double quotes if needed.
	if ( false !== strpos( $family, ' ' ) && false === strpos( $family, '"' ) ) {
		$family = '"' . $family . '"';
	}

	$family_array = explode( ',', $family );
	$last_part = trim( array_pop( $family_array ) );

	// Add font-family fallback.
	if ( $family !== 'initial'
		&& $family !== 'inherit'
		&& $last_part !== 'serif'
		&& $last_part !== 'sans-serif'
		&& $last_part !== 'monospace'
	) {
		$family = $family . ', sans-serif';
	}

	return html_entity_decode( $family, ENT_QUOTES );
}

Flatsome_Option::add_config( 'option', array(
	'option_type'    => 'theme_mod',
	'capability'     => 'edit_theme_options',
	'disable_output' => false,
) );
