<?php
/**
 * Registers the Stack element in UX Builder.
 *
 * @package flatsome
 */

add_ux_builder_shortcode( 'ux_stack', array(
	'type'      => 'container',
	'name'      => __( 'Stack', 'flatsome' ),
	'category'  => __( 'Layout', 'flatsome' ),
	'thumbnail' => flatsome_ux_builder_thumbnail( 'ux_stack' ),
	'template'  => flatsome_ux_builder_template( 'ux_stack.html' ),
	'wrap'      => false,
	'nested'    => true,
	'options'   => array(
		'direction'        => array(
			'type'       => 'select',
			'heading'    => __( 'Direction', 'flatsome' ),
			'responsive' => true,
			'default'    => 'row',
			'options'    => array(
				'row' => __( 'Horizontal', 'flatsome' ),
				'col' => __( 'Vertical', 'flatsome' ),
			),
		),
		'distribute'       => array(
			'type'       => 'select',
			'heading'    => __( 'Distribute', 'flatsome' ),
			'responsive' => true,
			'default'    => 'start',
			'options'    => array(
				'start'   => __( 'Start', 'flatsome' ),
				'center'  => __( 'Center', 'flatsome' ),
				'end'     => __( 'End', 'flatsome' ),
				'between' => __( 'Space between', 'flatsome' ),
				'around'  => __( 'Space around', 'flatsome' ),
			),
		),
		'align'            => array(
			'type'       => 'select',
			'heading'    => __( 'Align', 'flatsome' ),
			'responsive' => true,
			'default'    => 'stretch',
			'options'    => array(
				'stretch'  => __( 'Stretch', 'flatsome' ),
				'start'    => __( 'Start', 'flatsome' ),
				'center'   => __( 'Center', 'flatsome' ),
				'end'      => __( 'End', 'flatsome' ),
				'baseline' => __( 'Baseline', 'flatsome' ),
			),
		),
		'gap'              => array(
			'type'       => 'slider',
			'heading'    => __( 'Gap', 'flatsome' ),
			'responsive' => true,
			'default'    => '0',
			'unit'       => 'rem',
			'max'        => '16',
			'min'        => '0',
			'step'       => '0.25',
		),
		'advanced_options' => require( __DIR__ . '/commons/advanced.php'),
	),
) );
