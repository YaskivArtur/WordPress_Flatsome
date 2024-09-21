<?php

/**
 * Get theme icon by classname.
 *
 * @param string $name The icon name.
 * @param string $size Optional size corresponding to font size.
 * @param array  $atts Optional element attributes.
 *
 * @return string Icon markup.
 */
function get_flatsome_icon( $name, $size = null, $atts = null ) {
	$default_atts = array(
		'class' => $name,
	);

	if ( $size ) {
		$default_atts['style'] = 'font-size:' . $size . ';';
	}

	$atts      = wp_parse_args( $atts, $default_atts );
	$icon_html = '<i ' . flatsome_html_atts( $atts ) . '></i>';

	return apply_filters( 'flatsome_icon', $icon_html, $name, $size, $atts );
}

function flatsome_add_icons_css() {
	$theme     = wp_get_theme( get_template() );
	$version   = $theme->get( 'Version' );
	$fonts_url = get_template_directory_uri() . '/assets/css/icons';

	wp_add_inline_style(
		'flatsome-main',
		'@font-face {
				font-family: "fl-icons";
				font-display: block;
				src: url(' . $fonts_url . '/fl-icons.eot?v=' . $version . ');
				src:
					url(' . $fonts_url . '/fl-icons.eot#iefix?v=' . $version . ') format("embedded-opentype"),
					url(' . $fonts_url . '/fl-icons.woff2?v=' . $version . ') format("woff2"),
					url(' . $fonts_url . '/fl-icons.ttf?v=' . $version . ') format("truetype"),
					url(' . $fonts_url . '/fl-icons.woff?v=' . $version . ') format("woff"),
					url(' . $fonts_url . '/fl-icons.svg?v=' . $version . '#fl-icons) format("svg");
			}'
	);
}

add_action( 'wp_enqueue_scripts', 'flatsome_add_icons_css', 150 );

