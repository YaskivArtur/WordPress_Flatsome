<?php
/**
 * Registers the `scroll_to` shortcode.
 *
 * @package flatsome
 */

/**
 * Renders the `scroll_to` shortcode.
 *
 * @param array  $atts    An array of attributes.
 * @param string $content The shortcode content.
 * @param string $tag     The name of the shortcode, provided for context to enable filtering.
 *
 * @return string
 */
function flatsome_scroll_to( $atts, $content = null, $tag = '' ) {
	$atts = shortcode_atts( array(
		'bullet'      => 'true',
		'title'       => 'Change this',
		'link'        => '',
		'offset_type' => '',
		'offset'      => '0',
	), $atts, $tag );

	if ( ! $atts['title'] && ! $atts['link'] ) {
		return false;
	}

	// Convert title to link if link is not set.
	if ( ! $atts['link'] ) {
		$atts['link'] = flatsome_to_dashed( $atts['title'] );
	}

	if ( substr( $atts['link'], 0, 1 ) !== '#' ) {
		$atts['link'] = '#' . $atts['link'];
	}

	$element_atts = array(
		'class'       => 'scroll-to',
		'data-label'  => 'Scroll to: ' . $atts['link'],
		'data-bullet' => $atts['bullet'],
		'data-link'   => $atts['link'],
		'data-title'  => $atts['title'],
	);

	if ( $atts['offset_type'] === 'custom' ) {
		$element_atts['data-offset-type'] = $atts['offset_type'];
		$element_atts['data-offset']      = $atts['offset'];
	}

	return sprintf( '<span %s><a name="%s"></a></span>',
		flatsome_html_atts( $element_atts ),
		str_replace( '#', '', $atts['link'] )
	);
}

add_shortcode( 'scroll_to', 'flatsome_scroll_to' );
