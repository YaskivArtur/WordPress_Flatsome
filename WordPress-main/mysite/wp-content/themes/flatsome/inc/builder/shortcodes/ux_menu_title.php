<?php
/**
 * Registers the Menu link element in UX Builder.
 *
 * @package flatsome
 */

add_ux_builder_shortcode( 'ux_menu_title', array(
	'name'      => __( 'Menu title', 'flatsome' ),
	'category'  => __( 'Content', 'flatsome' ),
	'require'   => array( 'ux_menu' ),
	'template'  => flatsome_ux_builder_template( 'ux_menu_title.html' ),
	'wrap'      => false,
	'presets'   => array(
		array(
			'name'    => __( 'Default', 'flatsome' ),
			'content' => '[ux_menu_title text="Menu title"]',
		),
	),
	'options'   => array(
		'text'             => array(
			'type'       => 'textfield',
			'heading'    => __( 'Text', 'flatsome' ),
			'default'    => '',
			'auto_focus' => true,
		),
		'advanced_options' => require __DIR__ . '/commons/advanced.php',
	),
) );
