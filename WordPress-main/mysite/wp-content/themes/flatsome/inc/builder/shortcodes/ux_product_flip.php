<?php
/**
 * Registers the Product Flip element in UX Builder.
 *
 * @package flatsome
 */

$repeater_posts     = 'products';    // phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
$repeater_post_type = 'product';     // phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
$repeater_post_cat  = 'product_cat'; // phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable

$options = array(
	'post_options' => require __DIR__ . '/commons/repeater-posts.php',
	'filter_posts' => array(
		'type'       => 'group',
		'heading'    => __( 'Filter Posts' ),
		'conditions' => 'ids == ""',
		'options'    => array(
			'orderby'      => array(
				'type'    => 'select',
				'heading' => __( 'Order By' ),
				'default' => 'normal',
				'options' => array(
					'normal' => 'Normal',
					'sales'  => 'Sales',
					'rand'   => 'Random',
					'date'   => 'Date',
				),
			),
			'order'        => array(
				'type'    => 'select',
				'heading' => __( 'Order' ),
				'default' => 'asc',
				'options' => array(
					'asc'  => 'ASC',
					'desc' => 'DESC',
				),
			),
			'show'         => array(
				'type'    => 'select',
				'heading' => __( 'Order' ),
				'default' => '',
				'options' => array(
					''         => 'All',
					'featured' => 'Featured',
					'onsale'   => 'On Sale',
				),
			),
			'out_of_stock' => array(
				'type'    => 'select',
				'heading' => __( 'Out Of Stock' ),
				'default' => '',
				'options' => array(
					''        => 'Include',
					'exclude' => 'Exclude',
				),
			),
		),
	),
);

$options['post_options']['options']['tags'] = array(
	'type'       => 'select',
	'heading'    => 'Tag',
	'conditions' => 'ids == ""',
	'full_width' => true,
	'default'    => '',
	'config'     => array(
		'multiple'    => true,
		'placeholder' => 'Select...',
		'termSelect'  => array(
			'post_type'  => 'product',
			'taxonomies' => 'product_tag',
		),
	),
);

add_ux_builder_shortcode( 'ux_product_flip', array(
	'name'      => 'Flip Book',
	'category'  => __( 'Shop' ),
	'priority'  => 4,
	'thumbnail' => flatsome_ux_builder_thumbnail( 'product_flipbook' ),
	'wrap'      => false,
	'presets'   => array(
		array(
			'name'    => __( 'Normal' ),
			'content' => '[ux_product_flip]',
		),
	),
	'options'   => $options,
) );
