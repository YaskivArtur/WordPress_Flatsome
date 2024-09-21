<?php
/**
 * Registers the Menu element in UX Builder.
 *
 * @package flatsome
 */

add_ux_builder_shortcode( 'ux_menu', array(
	'type'      => 'container',
	'name'      => __( 'Menu', 'flatsome' ),
	'category'  => __( 'Content', 'flatsome' ),
	'allow'     => array( 'ux_menu_link', 'ux_menu_title' ),
	'thumbnail' => flatsome_ux_builder_thumbnail( 'ux_menu' ),
	'template'  => flatsome_ux_builder_template( 'ux_menu.html' ),
	'wrap'      => false,
	'nested'    => false,
	'presets'   => array(
		array(
			'name'    => __( 'Default', 'flatsome' ),
			'content' => '
				[ux_menu divider="solid"]
					[ux_menu_link text="Menu link 1"]
					[ux_menu_link text="Menu link 2"]
					[ux_menu_link text="Menu link 3"]
					[ux_menu_link text="Menu link 4"]
				[/ux_menu]
			',
		),
	),
	'options'   => array(
		'divider'          => array(
			'type'       => 'radio-buttons',
			'heading'    => __( 'Divider', 'flatsome' ),
			'responsive' => true,
			'default'    => '',
			'options'    => array(
				''      => array( 'title' => __( 'None', 'flatsome' ) ),
				'solid' => array( 'title' => __( 'Solid', 'flatsome' ) ),
			),
		),
		'advanced_options' => require __DIR__ . '/commons/advanced.php',
	),
) );
