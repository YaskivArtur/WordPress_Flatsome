<?php

global $wc;

Flatsome_Option::add_section( 'fl-my-account', array(
	'title'       => __( 'My Account', 'flatsome-admin' ),
	'description' => 'Note: Register form/link is only visible for non logged in users and the WooCommerce setting, "Allow customers to create an account on the "My account" page" is enabled.',
	'panel'       => 'woocommerce',
) );

Flatsome_Option::add_field( 'option', array(
	'type'      => 'color-alpha',
	'alpha'     => true,
	'settings'  => 'my_account_title_bg_color',
	'label'     => __( 'Title Background Color', 'flatsome-admin' ),
	'section'   => 'fl-my-account',
	'default'   => '',
	'transport' => $transport,
));

Flatsome_Option::add_field( 'option',  array(
	'type'        => 'image',
	'settings'     => 'facebook_login_bg',
	'label'       => __( 'Title Background Image', 'flatsome-admin' ),
	'section'     => 'fl-my-account',
	'transport' => $transport,
	'default'     => ''
));

Flatsome_Option::add_field( 'option', array(
	'type'        => 'radio-image',
	'settings'    => 'my_account_title_align',
	'label'       => __( 'Title Align', 'flatsome-admin' ),
	'description' => __( 'For logged in users only.', 'flatsome-admin' ),
	'section'     => 'fl-my-account',
	'default'     => 'left',
	'transport'   => $transport,
	'choices'     => array(
		'left'   => $image_url . 'align-left.svg',
		'center' => $image_url . 'align-center.svg',
		'right'  => $image_url . 'align-right.svg',
	),
));

Flatsome_Option::add_field( 'option', array(
	'type'      => 'radio-image',
	'settings'  => 'my_account_title_text_color',
	'label'     => __( 'Text color', 'flatsome-admin' ),
	'section'   => 'fl-my-account',
	'default'   => 'dark',
	'transport' => $transport,
	'choices'   => array(
		'light' => $image_url . 'text-light.svg',
		'dark'  => $image_url . 'text-dark.svg',
	),
));

Flatsome_Option::add_field( 'option',  array(
	'type'        => 'text',
	'settings'     => 'facebook_login_text',
	'transport' => $transport,
	'label'       => __( 'Login Text', 'flatsome-admin' ),
	'description' => __( '', 'flatsome-admin' ),
	'section'     => 'fl-my-account',
	'sanitize_callback' => 'flatsome_custom_sanitize',
));

Flatsome_Option::add_field( 'option',  array(
  'type'        => 'select',
  'settings'     => 'social_login_pos',
  'label'       => __( 'Social Button', 'flatsome-admin' ),
  'description' => __( 'Change position of Social Login Buttons in lightbox.', 'flatsome-admin' ),
  'section'     => 'fl-my-account',
  'default'     => 'top',
  'choices'     => array(
    'top' => __( 'Top', 'flatsome-admin' ),
    'bottom' => __( 'Bottom', 'flatsome-admin' ),
  ),
));


Flatsome_Option::add_field( 'option',  array(
  'type'        => 'checkbox',
  'settings'     => 'wc_account_links',
  'label'       => __( 'Enable default WooCommerce Account links in Dropdown and Account Sidebar. You can create a custom my account menu instead if you want.', 'flatsome-admin' ),
  'section'     => 'fl-my-account',
  'default'     => 1,
));


Flatsome_Option::add_field( '', array(
  'type'        => 'custom',
  'settings' => 'custom_html_header_account_shortcut',
  'label'       => __( '', 'flatsome-admin' ),
  'section'     => 'fl-my-account',
  'default'     => '<button style="margin-top:30px; margin-bottom:15px" class="button button-primary" data-to-section="header_account">Header Element →</button>',
) );
