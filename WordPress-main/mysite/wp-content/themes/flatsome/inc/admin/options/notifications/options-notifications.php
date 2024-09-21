<?php

Flatsome_Option::add_section( 'notifications', array(
	'title'    => __( 'Notifications', 'flatsome-admin' ),
	'priority' => 160,
) );

Flatsome_Option::add_field( 'option', array(
	'type'     => 'checkbox',
	'settings' => 'cookie_notice',
	'section'  => 'notifications',
	'label'    => esc_html__( 'Enable cookie notice', 'flatsome-admin' ),
	'default'  => false,
) );

Flatsome_Option::add_field( 'option', array(
	'type'        => 'textarea',
	'settings'    => 'cookie_notice_text',
	'section'     => 'notifications',
	'transport'   => $transport,
	'label'       => esc_html__( 'Custom cookie text', 'flatsome-admin' ),
	'description' => esc_html__( 'Add any HTML or shortcode here...', 'flatsome-admin' ),
	'default'     => '',
) );

Flatsome_Option::add_field( 'option', array(
	'type'        => 'select',
	'settings'    => 'privacy_policy_page',
	'section'     => 'notifications',
	'label'       => esc_html__( 'Privacy policy page', 'flatsome-admin' ),
	'description' => esc_html__( 'Show a button linked to the cookie policy page.', 'flatsome-admin' ),
	'default'     => false,
	'choices'     => $list_pages_by_id,
) );

Flatsome_Option::add_field( 'option', array(
	'type'      => 'select',
	'settings'  => 'cookie_notice_button_style',
	'section'   => 'notifications',
	'transport' => $transport,
	'label'     => esc_html__( 'Button style', 'flatsome-admin' ),
	'choices'   => $button_styles,
) );

Flatsome_Option::add_field( 'option', array(
	'type'      => 'radio-image',
	'settings'  => 'cookie_notice_text_color',
	'section'   => 'notifications',
	'transport' => $transport,
	'label'     => esc_html__( 'Text color', 'flatsome-admin' ),
	'default'   => 'light',
	'choices'   => array(
		'dark'  => $image_url . 'text-light.svg',
		'light' => $image_url . 'text-dark.svg',
	),
) );

Flatsome_Option::add_field( 'option', array(
	'type'      => 'color-alpha',
	'alpha'     => true,
	'settings'  => 'cookie_notice_bg_color',
	'section'   => 'notifications',
	'label'     => esc_html__( 'Background color', 'flatsome-admin' ),
	'default'   => '',
	'transport' => $transport,
) );

Flatsome_Option::add_field( 'option', array(
	'type'        => 'text',
	'settings'    => 'cookie_notice_version',
	'section'     => 'notifications',
	'label'       => esc_html__( 'Version', 'flatsome-admin' ),
	'description' => esc_html__( 'Increase the version to reopen the notice to visitors that have accepted before, after making changes to it.', 'flatsome-admin' ),
	'default'     => '1',
) );

function flatsome_refresh_cookies_partials( WP_Customize_Manager $wp_customize ) {

	// Abort if selective refresh is not available.
	if ( ! isset( $wp_customize->selective_refresh ) ) {
		return;
	}

	$wp_customize->selective_refresh->add_partial( 'refresh_css_cookies', array(
		'selector'        => 'head > style#custom-css',
		'settings'        => array( 'cookie_notice_bg_color' ),
		'render_callback' => function () {
			flatsome_custom_css();
		},
	) );

	$wp_customize->selective_refresh->add_partial( 'cookies-text', array(
		'selector'        => '.flatsome-cookies__text',
		'settings'        => array( 'cookie_notice_text' ),
		'render_callback' => function () {
			return get_theme_mod( 'cookie_notice_text' )
				? do_shortcode( get_theme_mod( 'cookie_notice_text' ) )
				: __( 'This site uses cookies to offer you a better browsing experience. By browsing this website, you agree to our use of cookies.', 'flatsome' );
		},
	) );
}

add_action( 'customize_register', 'flatsome_refresh_cookies_partials' );
