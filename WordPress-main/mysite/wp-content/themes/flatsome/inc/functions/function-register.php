<?php
/**
 * Register functions.
 *
 * @package flatsome
 */

/**
 * Register a custom follow link.
 *
 * @param string $key       The key.
 * @param string $label     The label.
 * @param array  $link_args The link args.
 * @param bool   $option    Register option.
 *
 * @return void
 */
function flatsome_register_follow_link( $key, $label, $link_args = array(), $option = true ) {
	if ( $option ) {
		Flatsome_Option::add_field( 'option', array(
			'type'     => 'text',
			'settings' => 'follow_' . $key,
			'label'    => $label,
			'section'  => 'follow',
			'default'  => '',
		) );
	}

	add_filter( 'shortcode_atts_follow', function ( $out, $pairs, $atts, $shortcode ) use ( $key ) {
		$out[ $key ] = ! empty( $atts[ $key ] ) ? $atts[ $key ] : '';

		return $out;
	}, 10, 4 );

	add_filter( 'shortcode_atts_team_member', function ( $out, $pairs, $atts, $shortcode ) use ( $key ) {
		$out[ $key ] = ! empty( $atts[ $key ] ) ? $atts[ $key ] : '';

		return $out;
	}, 10, 4 );

	add_filter( 'flatsome_shortcode_team_member_social_links', function ( $out, $atts ) use ( $key ) {
		$out[ $key ] = ! empty( $atts[ $key ] ) ? $atts[ $key ] : '';

		return $out;
	}, 10, 2 );

	add_filter( 'flatsome_shortcode_follow_social_links', function ( $out, $atts ) use ( $key ) {
		$out[ $key ] = ! empty( $atts[ $key ] ) ? $atts[ $key ] : '';

		return $out;
	}, 10, 2 );

	add_filter( 'flatsome_follow_links', function ( $links, $args ) use ( $key, $label, $link_args ) {
		/* translators: %s: The label */
		$follow_text = sprintf( esc_attr__( 'Follow on %s', 'flatsome' ), $label );
		$global_link = get_theme_mod( 'follow_' . $key, '' );
		$link        = $args['use_global_link'] ? $global_link : $args['atts'][ $key ];

		$defaults = array(
			'enabled'  => ! empty( $link ),
			'atts'     => array(
				'href'       => $link,
				'data-label' => $label,
				'target'     => '_blank',
				'rel'        => 'noopener noreferrer nofollow',
				'class'      => $args['style'] . " ${key} tooltip",
				'title'      => $follow_text,
				'aria-label' => $follow_text,
			),
			'icon'     => '<i class="icon-' . $key . '"></i>',
			'priority' => 9999,
		);

		// Add new follow link.
		$links[ $key ] = wp_parse_args( $link_args, $defaults );

		return $links;
	}, 10, 2 );

	add_filter( 'ux_builder_shortcode_data', function ( $data ) use ( $key, $label ) {
		if ( $data['tag'] === 'follow' ) {
			$data['options']['social_icons']['options'][ $key ] = array(
				'type'    => 'textfield',
				'heading' => $label,
				'default' => '',
			);
		}

		if ( $data['tag'] === 'team_member' ) {
			$data['options']['social_icons']['options'][ $key ] = array(
				'type'    => 'textfield',
				'heading' => $label,
				'default' => '',
			);
		}

		return $data;
	} );
}


