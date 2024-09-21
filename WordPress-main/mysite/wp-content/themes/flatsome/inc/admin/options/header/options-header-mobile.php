<?php

/*************
 * Header Mobile
 *************/

Flatsome_Option::add_section( 'header_mobile', array(
	'title'       => __( 'Header Mobile Menu / Overlay', 'flatsome-admin' ),
	'panel'       => 'header',
	//'description' => __( 'This is the section description', 'flatsome-admin' ),
) );


Flatsome_Option::add_field( 'option',  array(
	'type'        => 'slider',
	'settings'     => 'header_height_mobile',
	'label'       => __( 'Mobile Header Height', 'flatsome-admin' ),
	//'description' => __( 'This is the control description', 'flatsome-admin' ),
	//'help'        => __( 'This is some extra help. You can use this to add some additional instructions for users. The main description should go in the "description" of the field, this is only to be used for help tips.', 'flatsome-admin' ),
	'section'     => 'header_mobile',
	'default'     => '70',
	'choices'     => array(
		'min'  => 30,
		'max'  => 500,
		'step' => 1
	),
	'transport' => 'postMessage'
));

Flatsome_Option::add_field( 'option', array(
	'type'        => 'radio-image',
	'settings'     => 'logo_position_mobile',
	'label'       => __( 'Logo position', 'flatsome-admin' ),
	'section'     => 'header_mobile',
	'transport' => $transport,
	'default'     => 'center',
	'choices'     => array(
		'left' => $image_url . 'logo-left.svg',
		'center' => $image_url . 'logo-right.svg',
	),
));

Flatsome_Option::add_field( 'option',  array(
	'type'        => 'radio-image',
	'settings'     => 'menu_icon_style',
	'label'       => __( 'Menu Icon Style', 'flatsome-admin' ),
	'section'     => 'header_mobile',
	'default'     => '',
	'transport' => $transport,
	'choices'     => array(
		'' => $image_url . 'nav-icon-plain.svg',
		'outline' => $image_url . 'nav-icon-outline.svg',
		'fill' => $image_url . 'nav-icon-fill.svg',
		'fill-round' => $image_url . 'nav-icon-fill-round.svg',
		'outline-round' => $image_url . 'nav-icon-outline-round.svg',
	),
));

Flatsome_Option::add_field( 'option',  array(
	'type'        => 'checkbox',
	'settings'     => 'menu_icon_title',
	'label'       => __( 'Show Menu title', 'flatsome-admin' ),
	'section'     => 'header_mobile',
	'transport' => $transport,
	'default'     => 0,
));

Flatsome_Option::add_field( 'option', array(
	'type'        => 'radio-image',
	'settings'     => 'mobile_overlay',
	'label'       => __( 'Menu Overlay', 'flatsome-admin' ),
	'section'     => 'header_mobile',
	'transport'	  => $transport,
	'default'     => 'left',
	'choices'     => array(
		'left' => $image_url . 'overlay-left.svg',
		'right' => $image_url . 'overlay-right.svg',
		'center' => $image_url . 'overlay-center.svg'
	),
));

Flatsome_Option::add_field( 'option', array(
	'type'        => 'radio',
	'settings'    => 'mobile_submenu_parent_behavior',
	'label'       => __( 'Menu item behavior', 'flatsome' ),
	'description' => __( 'Click behavior for menu items with a submenu', 'flatsome' ),
	'section'     => 'header_mobile',
	'transport'   => 'refresh',
	'default'     => '',
	'choices'     => array(
		''       => __( 'Open link', 'flatsome' ),
		'toggle' => __( 'Toggle submenu', 'flatsome' ),
	),
) );

Flatsome_Option::add_field( 'option', array(
	'type'            => 'radio',
	'settings'        => 'mobile_submenu_effect',
	'label'           => __( 'Submenu effect', 'flatsome' ),
	'section'         => 'header_mobile',
	'transport'       => 'refresh',
	'default'         => 'accordion',
	'choices'         => array(
		'accordion' => __( 'Accordion', 'flatsome' ),
		'slide'     => __( 'Slide', 'flatsome' ),
	),
	'active_callback' => array(
		array(
			'setting'  => 'mobile_overlay',
			'operator' => '!=',
			'value'    => 'center',
		),
	),
) );

Flatsome_Option::add_field( 'option', array(
	'type'            => 'select',
	'settings'        => 'mobile_submenu_levels',
	'label'           => __( 'Submenu levels', 'flatsome' ),
	'section'         => 'header_mobile',
	'transport'       => 'refresh',
	'default'         => '1',
	'choices'         => array(
		'1' => __( '1 level', 'flatsome' ),
		'2' => __( '2 levels', 'flatsome' ),
	),
	'active_callback' => array(
		array(
			'setting'  => 'mobile_overlay',
			'operator' => '!=',
			'value'    => 'center',
		),
		array(
			'setting'  => 'mobile_submenu_effect',
			'operator' => '===',
			'value'    => 'slide',
		),
	),
) );

Flatsome_Option::add_field( 'option', array(
	'type'              => 'textarea',
	'settings'          => 'mobile_sidebar_top_content',
	'label'             => __( 'Top content', 'flatsome' ),
	'section'           => 'header_mobile',
	'sanitize_callback' => 'flatsome_custom_sanitize',
	'default'           => '',
) );

Flatsome_Option::add_field( 'option', array(
	'type'     => 'radio-buttonset',
	'settings' => 'mobile_sidebar_tabs',
	'label'    => __( 'Tabs', 'flatsome' ),
	'section'  => 'header_mobile',
	'default'  => '0',
	'choices'  => array(
		'0' => __( 'None', 'flatsome' ),
		'2' => __( '2 Tabs', 'flatsome' ),
	),
) );

Flatsome_Option::add_field( 'option', array(
	'type'            => 'text',
	'settings'        => 'mobile_sidebar_tab_text',
	'label'           => __( 'Tab 1 text', 'flatsome' ),
	'section'         => 'header_mobile',
	'default'         => '',
	'active_callback' => array(
		array(
			'setting'  => 'mobile_sidebar_tabs',
			'operator' => '!=',
			'value'    => false,
		),
	),
) );

Flatsome_Option::add_field( 'option',  array(
  'type'        => 'sortable',
  'settings'     => 'mobile_sidebar',
  'label'       => __( 'Menu elements', 'flatsome' ),
  'section'     => 'header_mobile',
  'transport'   => $transport,
  'multiple' => 10,
  'default'     => flatsome_header_mobile_sidebar(),
  'choices'     => $nav_elements
));

Flatsome_Option::add_field( 'option', array(
	'type'            => 'text',
	'settings'        => 'mobile_sidebar_tab_2_text',
	'label'           => __( 'Tab 2 text', 'flatsome' ),
	'section'         => 'header_mobile',
	'default'         => '',
	'active_callback' => array(
		array(
			'setting'  => 'mobile_sidebar_tabs',
			'operator' => '!=',
			'value'    => false,
		),
	),
) );

Flatsome_Option::add_field( 'option', array(
	'type'            => 'sortable',
	'settings'        => 'mobile_sidebar_tab_2',
	'label'           => __( 'Menu elements tab 2', 'flatsome' ),
	'section'         => 'header_mobile',
	'transport'       => flatsome_customizer_transport(),
	'multiple'        => 10,
	'default'         => '',
	'choices'         => $nav_elements,
	'active_callback' => array(
		array(
			'setting'  => 'mobile_sidebar_tabs',
			'operator' => '!=',
			'value'    => false,
		),
	),
) );

Flatsome_Option::add_field( 'option', array(
	'type'        => 'radio-image',
	'settings'     => 'mobile_overlay_color',
	'label'       => __( 'Overlay Color', 'flatsome-admin' ),
	'section'     => 'header_mobile',
	'transport'	  => $transport,
	'default'     => '',
	'choices'     => array(
		'' => $image_url . 'text-dark.svg',
		'dark' => $image_url . 'text-light.svg',
	),
));


Flatsome_Option::add_field( 'option',  array(
    'type'        => 'color-alpha',
    'settings'     => 'mobile_overlay_bg',
    'label'       => __( 'Background Color', 'flatsome-admin' ),
	'section'     => 'header_mobile',
	'default'     => '',
	'transport' => 'postMessage'
));
