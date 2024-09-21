<?php

return array(
	'type'    => 'group',
	'heading' => 'Typography',
	'options' => array(
		'font_size'   => array(
			'type'       => 'slider',
			'heading'    => __( 'Font size', 'flatsome' ),
			'responsive' => true,
			'unit'       => 'rem',
			'max'        => 4,
			'min'        => 0.75,
			'step'       => 0.05,
		),
		'line_height' => array(
			'type'       => 'slider',
			'heading'    => __( 'Line height', 'flatsome' ),
			'responsive' => true,
			'max'        => 3,
			'min'        => 0.75,
			'step'       => 0.05,
		),
		'text_align'  => array(
			'type'       => 'radio-buttons',
			'heading'    => __( 'Text align', 'flatsome' ),
			'responsive' => true,
			'default'    => '',
			'options'    => array(
				''   => array(
					'title' => 'None',
					'icon'  => 'dashicons-no-alt',
				),
				'left'    => array(
					'title' => 'Left',
					'icon'  => 'dashicons-editor-alignleft',
				),
				'center'  => array(
					'title' => 'Center',
					'icon'  => 'dashicons-editor-aligncenter',
				),
				'right'   => array(
					'title' => 'Right',
					'icon'  => 'dashicons-editor-alignright',
				),
			),
		),
		'text_color' => array(
			'type'     => 'colorpicker',
			'heading'  => __( 'Text color', 'flatsome' ),
			'format'   => 'rgb',
			'position' => 'bottom right',
			'helpers'  => require( __DIR__ . '/../helpers/colors.php' ),
		),
	),
);
