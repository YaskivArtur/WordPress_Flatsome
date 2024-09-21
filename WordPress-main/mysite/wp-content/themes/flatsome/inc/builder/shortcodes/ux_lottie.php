<?php
/**
 * Registers the Lottie animation element in UX Builder.
 *
 * @package Flatsome
 */

add_ux_builder_shortcode( 'ux_lottie', array(
	'name'      => __( 'Lottie', 'flatsome' ),
	'category'  => __( 'Content', 'flatsome' ),
	'thumbnail' => flatsome_ux_builder_thumbnail( 'ux_lottie' ),
	'template'  => flatsome_ux_builder_template( 'ux_lottie.html' ),
	'wrap'      => false,
	'options'   => array(
		'path'             => array(
			'type'        => 'file',
			'heading'     => __( 'Lottie animation URL', 'flatsome' ),
			'placeholder' => __( 'Paste animation URL or select file', 'flatsome' ),
			'full_width'  => true,
			'mime_types'  => array( 'application/json', 'text/plain' ),
			'default'     => '',
		),
		'settings_options' => array(
			'type'    => 'group',
			'heading' => __( 'Settings', 'flatsome' ),
			'options' => array(
				'loop'             => array(
					'type'    => 'checkbox',
					'heading' => __( 'Loop', 'flatsome' ),
					'default' => 'true',
				),
				'autoplay'         => array(
					'type'    => 'checkbox',
					'heading' => __( 'Autoplay', 'flatsome' ),
					'default' => 'true',
				),
				'trigger'          => array(
					'type'       => 'select',
					'heading'    => __( 'Trigger', 'flatsome' ),
					'conditions' => 'autoplay != "true"',
					'default'    => '',
					'options'    => array(
						''       => 'None (static)',
						'hover'  => 'On hover',
						'click'  => 'On click',
						'scroll' => 'On scroll',
					),
				),
				'mouseout'         => array(
					'type'       => 'select',
					'heading'    => __( 'Mouse out', 'flatsome' ),
					'conditions' => 'autoplay != "true" && trigger == "hover"',
					'default'    => '',
					'options'    => array(
						''        => 'Pause',
						'reverse' => 'Reverse',
					),
				),
				'speed'            => array(
					'type'       => 'slider',
					'heading'    => __( 'Speed', 'flatsome' ),
					'conditions' => 'autoplay == "true" || trigger == "hover" || trigger == "click"',
					'default'    => '1',
					'min'        => '0',
					'max'        => '5',
					'step'       => '0.1',
				),
				'reverse'          => array(
					'type'       => 'checkbox',
					'heading'    => __( 'Reverse', 'flatsome' ),
					'conditions' => 'autoplay == "true" || trigger == "hover" || trigger == "click"',
					'default'    => 'false',
				),
				'visibility_start' => array(
					'type'        => 'slider',
					'heading'     => __( 'Visibility start', 'flatsome' ),
					'full_width'  => true,
					'description' => 'Start in relation to the viewport',
					'conditions'  => 'autoplay != "true" && trigger == "scroll"',
					'default'     => '0',
					'min'         => '0',
					'max'         => '100',
					'step'        => '1',
					'unit'        => '%',
				),
				'visibility_end'   => array(
					'type'        => 'slider',
					'heading'     => __( 'Visibility end', 'flatsome' ),
					'full_width'  => true,
					'description' => 'Finish in relation to the viewport',
					'conditions'  => 'autoplay != "true" && trigger == "scroll"',
					'default'     => '100',
					'min'         => '0',
					'max'         => '100',
					'step'        => '1',
					'unit'        => '%',
				),
				'start'            => array(
					'type'       => 'slider',
					'heading'    => __( 'Frame start', 'flatsome' ),
					'full_width' => true,
					'default'    => '0',
					'min'        => '0',
					'max'        => '100',
					'step'       => '1',
					'unit'       => '%',
				),
				'end'              => array(
					'type'       => 'slider',
					'heading'    => __( 'Frame end', 'flatsome' ),
					'full_width' => true,
					'default'    => '100',
					'min'        => '0',
					'max'        => '100',
					'step'       => '1',
					'unit'       => '%',
				),
				'controls'         => array(
					'type'    => 'checkbox',
					'heading' => __( 'Controls', 'flatsome' ),
					'default' => 'false',
				),
			),
		),
		'layout_options'   => array(
			'type'    => 'group',
			'heading' => __( 'Layout', 'flatsome' ),
			'options' => array(
				'width'   => array(
					'type'       => 'scrubfield',
					'heading'    => __( 'Width', 'flatsome' ),
					'responsive' => true,
					'default'    => '100%',
					'min'        => '0',
				),
				'height'  => array(
					'type'       => 'scrubfield',
					'heading'    => __( 'Height', 'flatsome' ),
					'responsive' => true,
					'default'    => '300px',
					'min'        => '0',
				),
				'padding' => array(
					'type'       => 'margins',
					'heading'    => __( 'Padding', 'flatsome' ),
					'full_width' => true,
					'responsive' => true,
					'min'        => 0,
					'step'       => 1,
				),
				'margin'  => array(
					'type'       => 'margins',
					'heading'    => __( 'Margin', 'flatsome' ),
					'full_width' => true,
					'responsive' => true,
					'step'       => 1,
				),
			),
		),
		'link_options'     => require __DIR__ . '/commons/links.php',
		'advanced_options' => require __DIR__ . '/commons/advanced.php',
	),
) );
