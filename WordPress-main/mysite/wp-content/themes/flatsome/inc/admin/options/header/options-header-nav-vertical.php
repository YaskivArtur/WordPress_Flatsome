<?php
/**
 * Options for nav vertical element.
 *
 * @package flatsome
 */

Flatsome_Option::add_section( 'header_nav_vertical', array(
	'title' => __( 'Vertical Menu', 'flatsome' ),
	'panel' => 'header',
) );

/**
 * Add options.
 */
function flatsome_customizer_header_nav_vertical_options() {
	Flatsome_Option::add_field( '', array(
		'type'     => 'custom',
		'settings' => 'custom_title_header_nav_vertical_layout',
		'label'    => '',
		'section'  => 'header_nav_vertical',
		'default'  => '<div class="options-title-divider">Opener</div>',
	) );

	Flatsome_Option::add_field( 'option', array(
		'type'      => 'radio-image',
		'settings'  => 'header_nav_vertical_icon_style',
		'label'     => __( 'Icon', 'flatsome' ),
		'section'   => 'header_nav_vertical',
		'default'   => 'plain',
		'transport' => flatsome_customizer_transport(),
		'choices'   => array(
			''      => flatsome_customizer_images_uri() . '/disabled.svg',
			'plain' => flatsome_customizer_images_uri() . '/nav-icon-plain.svg',
		),
	) );

	Flatsome_Option::add_field( 'option', array(
		'type'      => 'slider',
		'settings'  => 'header_nav_vertical_height',
		'label'     => __( 'Height', 'flatsome' ),
		'section'   => 'header_nav_vertical',
		'transport' => flatsome_customizer_transport(),
		'default'   => '50',
		'choices'   => array(
			'min'  => '10',
			'max'  => '500',
			'step' => '1',
		),
	) );

	Flatsome_Option::add_field( 'option', array(
		'type'      => 'slider',
		'settings'  => 'header_nav_vertical_width',
		'label'     => __( 'Width', 'flatsome' ),
		'section'   => 'header_nav_vertical',
		'transport' => flatsome_customizer_transport(),
		'default'   => '250',
		'choices'   => array(
			'min'  => '10',
			'max'  => '500',
			'step' => '1',
		),
	) );

	Flatsome_Option::add_field( 'option', array(
		'type'      => 'text',
		'settings'  => 'header_nav_vertical_tagline',
		'label'     => __( 'Tag line', 'flatsome' ),
		'section'   => 'header_nav_vertical',
		'transport' => flatsome_customizer_transport(),
		'default'   => '',
	) );

	Flatsome_Option::add_field( 'option', array(
		'type'      => 'text',
		'settings'  => 'header_nav_vertical_text',
		'label'     => __( 'Text', 'flatsome' ),
		'section'   => 'header_nav_vertical',
		'transport' => flatsome_customizer_transport(),
		'default'   => '',
	) );

	Flatsome_Option::add_field( 'option', array(
		'type'      => 'radio-image',
		'settings'  => 'header_nav_vertical_text_color',
		'label'     => __( 'Text base color', 'flatsome' ),
		'section'   => 'header_nav_vertical',
		'default'   => 'dark',
		'transport' => flatsome_customizer_transport(),
		'choices'   => array(
			'dark'  => flatsome_customizer_images_uri() . '/text-light.svg',
			'light' => flatsome_customizer_images_uri() . '/text-dark.svg',
		),
	) );

	Flatsome_Option::add_field( 'option', array(
		'type'      => 'color-alpha',
		'alpha'     => true,
		'settings'  => 'header_nav_vertical_color',
		'label'     => __( 'Color', 'flatsome' ),
		'section'   => 'header_nav_vertical',
		'transport' => flatsome_customizer_transport(),
		'default'   => '',
	) );

	Flatsome_Option::add_field( 'option', array(
		'type'      => 'color-alpha',
		'alpha'     => true,
		'settings'  => 'header_nav_vertical_bg_color',
		'label'     => __( 'Background color', 'flatsome' ),
		'section'   => 'header_nav_vertical',
		'transport' => flatsome_customizer_transport(),
		'default'   => '',
	) );

	Flatsome_Option::add_field( '', array(
		'type'     => 'custom',
		'settings' => 'custom_title_header_nav_vertical_fly_out',
		'label'    => '',
		'section'  => 'header_nav_vertical',
		'default'  => '<div class="options-title-divider">Fly out</div>',
	) );

	Flatsome_Option::add_field( 'option', array(
		'type'      => 'checkbox',
		'settings'  => 'header_nav_vertical_fly_out_frontpage',
		'label'     => __( 'Keep open on front page', 'flatsome' ),
		'section'   => 'header_nav_vertical',
		'transport' => flatsome_customizer_transport(),
		'default'   => 1,
	) );

	Flatsome_Option::add_field( 'option', array(
		'type'      => 'checkbox',
		'settings'  => 'header_nav_vertical_fly_out_shadow',
		'label'     => __( 'Add shadow', 'flatsome' ),
		'section'   => 'header_nav_vertical',
		'transport' => flatsome_customizer_transport(),
		'default'   => 1,
	) );

	Flatsome_Option::add_field( 'option', array(
		'type'      => 'slider',
		'settings'  => 'header_nav_vertical_fly_out_width',
		'label'     => __( 'Width', 'flatsome' ),
		'section'   => 'header_nav_vertical',
		'transport' => flatsome_customizer_transport(),
		'default'   => '250',
		'choices'   => array(
			'min'  => '10',
			'max'  => '500',
			'step' => '1',
		),
	) );

	Flatsome_Option::add_field( 'option', array(
		'type'      => 'color-alpha',
		'alpha'     => true,
		'settings'  => 'header_nav_vertical_fly_out_bg_color',
		'label'     => __( 'Background color', 'flatsome' ),
		'section'   => 'header_nav_vertical',
		'transport' => flatsome_customizer_transport(),
		'default'   => '',
	) );

	Flatsome_Option::add_field( '', array(
		'type'     => 'custom',
		'settings' => 'custom_title_header_nav_vertical_fly_out_navigation',
		'label'    => '',
		'section'  => 'header_nav_vertical',
		'default'  => '<div class="options-title-divider">Fly out navigation</div>',
	) );

	Flatsome_Option::add_field( 'option', array(
		'type'      => 'checkbox',
		'settings'  => 'header_nav_vertical_fly_out_nav_divider',
		'label'     => __( 'Divider', 'flatsome' ),
		'section'   => 'header_nav_vertical',
		'transport' => flatsome_customizer_transport(),
		'default'   => 1,
	) );

	Flatsome_Option::add_field( 'option', array(
		'type'      => 'slider',
		'settings'  => 'header_nav_vertical_fly_out_nav_height',
		'label'     => __( 'Nav height', 'flatsome' ),
		'section'   => 'header_nav_vertical',
		'transport' => flatsome_customizer_transport(),
		'default'   => 0,
		'choices'   => array(
			'min'  => 0,
			'max'  => 200,
			'step' => 1,
		),
	) );

	Flatsome_Option::add_field( 'option', array(
		'type'      => 'radio-image',
		'settings'  => 'header_nav_vertical_fly_out_text_color',
		'label'     => __( 'Text base color', 'flatsome' ),
		'section'   => 'header_nav_vertical',
		'default'   => 'light',
		'transport' => flatsome_customizer_transport(),
		'choices'   => array(
			'dark'  => flatsome_customizer_images_uri() . '/text-light.svg',
			'light' => flatsome_customizer_images_uri() . '/text-dark.svg',
		),
	) );

	Flatsome_Option::add_field( 'option', array(
		'type'      => 'color-alpha',
		'alpha'     => true,
		'settings'  => 'header_nav_vertical_fly_out_nav_color',
		'label'     => __( 'Nav color', 'flatsome' ),
		'section'   => 'header_nav_vertical',
		'transport' => flatsome_customizer_transport(),
		'default'   => '',
	) );

	Flatsome_Option::add_field( 'option', array(
		'type'      => 'color-alpha',
		'alpha'     => true,
		'settings'  => 'header_nav_vertical_fly_out_nav_color_hover',
		'label'     => __( 'Nav color :hover', 'flatsome' ),
		'section'   => 'header_nav_vertical',
		'transport' => flatsome_customizer_transport(),
		'default'   => '',
	) );

	Flatsome_Option::add_field( 'option', array(
		'type'      => 'color-alpha',
		'alpha'     => true,
		'settings'  => 'header_nav_vertical_fly_out_nav_bg_color_hover',
		'label'     => __( 'Nav background color :hover', 'flatsome' ),
		'section'   => 'header_nav_vertical',
		'transport' => flatsome_customizer_transport(),
		'default'   => '',
	) );
}

add_action( 'init', 'flatsome_customizer_header_nav_vertical_options' );

/**
 * Refresh partials.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager.
 */
function flatsome_refresh_header_nav_vertical_partials( WP_Customize_Manager $wp_customize ) {
	if ( ! isset( $wp_customize->selective_refresh ) ) {
		return;
	}

	$wp_customize->selective_refresh->add_partial( 'header-vertical-menu', array(
		'selector'            => '.header-vertical-menu',
		'container_inclusive' => true,
		'settings'            => array(
			'header_nav_vertical_icon_style',
			'header_nav_vertical_tagline',
			'header_nav_vertical_text',
		),
		'render_callback'     => function () {
			get_template_part( 'template-parts/header/partials/element', 'nav-vertical' );
		},
	) );

	$wp_customize->selective_refresh->add_partial( 'header-vertical-menu-refresh-css', array(
		'selector'        => 'head > style#custom-css',
		'settings'        => array(
			'header_nav_vertical_color',
			'header_nav_vertical_bg_color',
			'header_nav_vertical_fly_out_bg_color',
			'header_nav_vertical_fly_out_nav_divider',
			'header_nav_vertical_fly_out_nav_height',
			'header_nav_vertical_fly_out_nav_color',
			'header_nav_vertical_fly_out_nav_color_hover',
			'header_nav_vertical_fly_out_nav_bg_color_hover',
		),
		'render_callback' => function () {
			flatsome_custom_css();
		},
	) );
}

add_action( 'customize_register', 'flatsome_refresh_header_nav_vertical_partials' );
