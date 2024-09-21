<?php

/*************
 * - Account
 *************/

Flatsome_Option::add_section( 'header_account', array(
	'title' => __( 'Account', 'flatsome-admin' ),
	'panel' => 'header',
) );

Flatsome_Option::add_field( 'option', array(
	'type'        => 'radio-image',
	'settings'     => 'account_icon_style',
	'label'       => __( 'Icon Style', 'flatsome-admin' ),
	'section'     => 'header_account',
	'transport' => $transport,
	'default'     => '',
	'choices'     => array(
		'' => $image_url . 'disabled.svg',
		'image' => $image_url . 'account-icon-image.svg',
		'plain' => $image_url . 'account-icon-plain.svg',
		'fill' => $image_url . 'account-icon-fill.svg',
		'fill-round' => $image_url . 'account-icon-fill-round.svg',
		'outline' => $image_url . 'account-icon-outline.svg',
		'outline-round' => $image_url . 'account-icon-outline-round.svg',
	),
));

Flatsome_Option::add_field( 'option',  array(
	'type'        => 'checkbox',
	'settings'     => 'header_account_title',
	'label'       => __( 'Show label', 'flatsome-admin' ),
	'description' => __( 'Display label for logged in users.', 'flatsome-admin' ),
	'section'     => 'header_account',
	'transport' => $transport,
	'default'     => 1,
));

Flatsome_Option::add_field( 'option', array(
	'type'            => 'checkbox',
	'settings'        => 'header_account_username',
	'label'           => __( 'Use username as label', 'flatsome-admin' ),
	'description'     => __( 'Display username instead of fixed label for logged in users.', 'flatsome-admin' ),
	'section'         => 'header_account',
	'transport'       => $transport,
	'active_callback' => array(
		array(
			'setting'  => 'header_account_title',
			'operator' => '==',
			'value'    => true,
		),
	),
	'default'         => 0,
) );

Flatsome_Option::add_field( '', array(
	'type'     => 'custom',
	'settings' => 'custom_title_account_login',
	'label'    => '',
	'section'  => 'header_account',
	'default'  => '<div class="options-title-divider">Login</div>',
) );

Flatsome_Option::add_field( 'option', array(
	'type'      => 'select',
	'settings'  => 'account_login_style',
	'label'     => __( 'Login Style', 'flatsome-admin' ),
	'section'   => 'header_account',
	'transport' => $transport,
	'default'   => 'lightbox',
	'choices'   => array(
		'link'     => __( 'Link', 'flatsome-admin' ),
		'lightbox' => __( 'Lightbox', 'flatsome-admin' ),
	),
) );

Flatsome_Option::add_field( '', array(
	'type'     => 'custom',
	'settings' => 'custom_title_account_register',
	'label'    => '',
	'section'  => 'header_account',
	'default'  => '<div class="options-title-divider">Register</div>',
) );

Flatsome_Option::add_field( 'option', array(
	'type'        => 'checkbox',
	'settings'    => 'header_account_register',
	'label'       => __( 'Show "Register" label', 'flatsome-admin' ),
	'description' => __( 'Display "Register" for non logged in users.', 'flatsome-admin' ),
	'section'     => 'header_account',
	'transport'   => $transport,
) );

Flatsome_Option::add_field( 'option', array(
	'type'            => 'radio-image',
	'settings'        => 'account_login_lightbox_layout',
	'label'           => __( 'Lightbox layout (register)', 'flatsome-admin' ),
	'description'     => 'Register form/link is only visible for non logged in users and if the WooCommerce setting, "Allow customers to create an account on the "My account" page" is enabled.',
	'section'         => 'header_account',
	'active_callback' => array(
		array(
			'setting'  => 'account_login_style',
			'operator' => '===',
			'value'    => 'lightbox',
		),
	),
	'default'         => '',
	'choices'         => array(
		''                     => flatsome_customizer_images_uri() . '/form-login.svg',
		'lightbox-left-panel'  => flatsome_customizer_images_uri() . '/form-login-lightbox-left-panel.svg',
		'lightbox-right-panel' => flatsome_customizer_images_uri() . '/form-login-lightbox-right-panel.svg',
	),
) );

Flatsome_Option::add_field( 'option',
	array(
		'type'            => 'select',
		'settings'        => 'account_login_lightbox_side_panel_block',
		'label'           => __( 'Side panel block', 'flatsome-admin' ),
		'description'     => __( 'Replace side panel content with a custom Block that can be edited in UX Builder.', 'flatsome-admin' ),
		'section'         => 'header_account',
		'active_callback' => array(
			array(
				'setting'  => 'account_login_style',
				'operator' => '===',
				'value'    => 'lightbox',
			),
			array(
				'setting'  => 'account_login_lightbox_layout',
				'operator' => '!=',
				'value'    => '',
			),
		),
		'default'         => false,
		'choices'         => flatsome_customizer_blocks(),
	)
);

Flatsome_Option::add_field( 'option', array(
	'type'            => 'color',
	'settings'        => 'account_login_lightbox_side_panel_bg_color',
	'label'           => __( 'Side panel background color', 'flatsome-admin' ),
	'section'         => 'header_account',
	'active_callback' => array(
		array(
			'setting'  => 'account_login_style',
			'operator' => '===',
			'value'    => 'lightbox',
		),
		array(
			'setting'  => 'account_login_lightbox_layout',
			'operator' => '!=',
			'value'    => '',
		),
		array(
			'setting'  => 'account_login_lightbox_side_panel_block',
			'operator' => '==',
			'value'    => false,
		),
	),
	'default'         => Flatsome_Default::COLOR_PRIMARY,
) );

Flatsome_Option::add_field( 'option', array(
	'type'            => 'image',
	'settings'        => 'account_login_lightbox_side_panel_bg_image',
	'label'           => __( 'Side panel background image', 'flatsome-admin' ),
	'section'         => 'header_account',
	'active_callback' => array(
		array(
			'setting'  => 'account_login_style',
			'operator' => '===',
			'value'    => 'lightbox',
		),
		array(
			'setting'  => 'account_login_lightbox_layout',
			'operator' => '!=',
			'value'    => '',
		),
		array(
			'setting'  => 'account_login_lightbox_side_panel_block',
			'operator' => '==',
			'value'    => false,
		),
	),
	'default'         => '',
) );

Flatsome_Option::add_field( 'option', array(
	'type'            => 'color-alpha',
	'alpha'           => true,
	'settings'        => 'account_login_lightbox_side_panel_bg_overlay',
	'label'           => __( 'Side panel background overlay', 'flatsome-admin' ),
	'section'         => 'header_account',
	'active_callback' => array(
		array(
			'setting'  => 'account_login_style',
			'operator' => '===',
			'value'    => 'lightbox',
		),
		array(
			'setting'  => 'account_login_lightbox_layout',
			'operator' => '!=',
			'value'    => '',
		),
		array(
			'setting'  => 'account_login_lightbox_side_panel_block',
			'operator' => '==',
			'value'    => false,
		),
	),
	'default'         => '',
) );

Flatsome_Option::add_field( '', array(
	'type'     => 'custom',
	'settings' => 'custom_html_account_shortcut',
	'label'    => __( '', 'flatsome-admin' ),
	'section'  => 'header_account',
	'default'  => '<button style="margin-top:30px; margin-bottom:15px" class="button button-primary" data-to-section="fl-my-account">Account Page Layout →</button>',
) );

function flatsome_refresh_header_account_partials( WP_Customize_Manager $wp_customize ) {

	if ( ! isset( $wp_customize->selective_refresh ) ) {
	      return;
	 }

	// Account
	$wp_customize->selective_refresh->add_partial( 'header-account', array(
	    'selector' => '.header .account-item',
	    'container_inclusive' => true,
	    'settings' => array('header_account_register','account_login_style','account_icon_style','header_account_title','header_account_username'),
	    'render_callback' => function() {
	        get_template_part('template-parts/header/partials/element','account');
	    },
	) );
}
add_action( 'customize_register', 'flatsome_refresh_header_account_partials' );
