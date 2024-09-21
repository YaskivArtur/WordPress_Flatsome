<?php

/*************
 * Header Top
 *************/

Flatsome_Option::add_section( 'top_bar', array(
	'title'       => __( 'Top Bar', 'flatsome-admin' ),
	'panel'       => 'header',
	//'description' => __( 'This is the section description', 'flatsome-admin' ),
) );

Flatsome_Option::add_field( 'option',  array(
	'type'        => 'checkbox',
	'settings'     => 'topbar_show',
	//'transport' => $transport,
	'label'       => __( 'Enable Top Bar', 'flatsome-admin' ),
	'section'     => 'top_bar',
	'default'     => 1,
));

Flatsome_Option::add_field( '', array(
	'type'     => 'custom',
	'settings' => 'custom_title_header_top_layout',
	'section'  => 'top_bar',
	'default'  => '<div class="options-title-divider">Layout</div>',
) );

Flatsome_Option::add_field( 'option',  array(
	'type'        => 'slider',
	'settings'     => 'header_top_height',
	'label'       => __( 'Height', 'flatsome-admin' ),
	'section'     => 'top_bar',
	'default'     => 30,
	'choices'     => array(
		'min'  => 20,
		'max'  => 100,
		'step' => 1
	),
	'transport' => 'postMessage'
));


Flatsome_Option::add_field( 'option',  array(
	'type'        => 'radio-image',
	'settings'     => 'topbar_color',
	'label'       => __( 'Text Color', 'flatsome-admin' ),
	'section'     => 'top_bar',
	'default'     => 'dark',
	'transport' => 'postMessage',
	'choices'     => array(
		'dark' => $image_url . 'text-light.svg',
		'light' => $image_url . 'text-dark.svg'
	),
));


Flatsome_Option::add_field( 'option',  array(
    'type'        => 'color-alpha',
    'settings'     => 'topbar_bg',
    'label'       => __( 'Background Color', 'flatsome-admin' ),
    //'description' => __( 'This is the control description', 'flatsome-admin' ),
    //'help'        => __( 'This is some extra help. You can use this to add some additional instructions for users. The main description should go in the "description" of the field, this is only to be used for help tips.', 'flatsome-admin' ),
    'section'     => 'top_bar',
    'default' => '',
    'transport' => 'postMessage',
	'js_vars'   => array(
		array(
			'element'  => '.header-top',
			'function' => 'css',
			'property' => 'background-color'
		),
	)
));

Flatsome_Option::add_field( '', array(
	'type'     => 'custom',
	'settings' => 'custom_title_header_top_nav',
	'section'  => 'top_bar',
	'default'  => '<div class="options-title-divider">Navigation</div>',
) );

Flatsome_Option::add_field( 'option',  array(
	'type'        => 'radio-image',
	'settings'     => 'nav_style_top',
	'label'       => __( 'Navigation Style', 'flatsome-admin' ),
	'section'     => 'top_bar',
	'default'     => 'divided',
	'transport' => $transport,
	'choices'     => $nav_styles_img
));

Flatsome_Option::add_field( 'option', array(
	'type'      => 'checkbox',
	'settings'  => 'nav_top_uppercase',
	'label'     => __( 'Uppercase', 'flatsome-admin' ),
	'section'   => 'top_bar',
	'transport' => $transport,
	'default'   => 0,
) );

Flatsome_Option::add_field( 'option', array(
	'type'     => 'checkbox',
	'settings' => 'nav_top_body_overlay',
	'label'    => __( 'Add overlay on hover', 'flatsome-admin' ),
	'section'  => 'top_bar',
	'default'  => 0,
) );

Flatsome_Option::add_field( 'option', array(
	'type'      => 'slider',
	'settings'  => 'nav_height_top',
	'label'     => __( 'Nav Height', 'flatsome-admin' ),
	'section'   => 'top_bar',
	'transport' => $transport,
	'default'   => 16,
	'choices'   => array(
		'min'  => 0,
		'max'  => 100,
		'step' => 1,
	),
) );

Flatsome_Option::add_field( 'option', array(
	'type'        => 'color',
	'settings'    => 'type_nav_top_color',
	'label'       => __( 'Nav Color', 'flatsome-admin' ),
	'section'     => 'top_bar',
	'transport'   => $transport,
) );

Flatsome_Option::add_field( 'option', array(
	'type'        => 'color',
	'settings'    => 'type_nav_top_color_hover',
	'label'       => __( 'Nav Color :hover', 'flatsome-admin' ),
	'section'     => 'top_bar',
	'transport'   => $transport,
) );
