<?php

$facebook_accounts = flatsome_facebook_accounts();
$default_account   = ! empty( $facebook_accounts )
	? array_keys( $facebook_accounts )[0]
	: '';

$options = array(
'pages_options' => array(
    'type' => 'group',
    'heading' => __( 'Options' ),
    'options' => array(

    'username' => array(
      'type' => 'select',
      'heading' => 'Account',
      'default' => '',
      'options' => array_merge(
        array( '' => '-' ),
        wp_list_pluck( $facebook_accounts, 'username', 'username' )
      ),
    ),

	'loading' => array(
		'type' => 'select',
		'heading' => __( 'Loading' ),
		'default' => '',
		'options' => array(
		  ''     => __( 'Default' ),
		  'lazy' => __( 'Lazy' ),
		),
	),

    'photos' => array(
        'type' => 'slider',
        'heading' => 'Images',
        'default' => 10,
        'max' => 12,
        'min' => 3,
    ),

    'caption' => array(
          'type' => 'radio-buttons',
          'heading' => __('Captions'),
          'default' => 'true',
          'options' => array(
              0  => array( 'title' => 'Off'),
              'true'  => array( 'title' => 'On'),
          ),
    ),

    'image_overlay' => array(
        'type'      => 'colorpicker',
        'heading'   => __( 'Image Overlay' ),
        'default'   => '',
        'alpha'     => true,
        'format'    => 'rgb',
        'position'  => 'bottom right',
        'on_change' => array(
          'selector' => '.overlay',
          'style'    => 'background-color: {{ value }}',
        ),
    ),

    'image_hover' => array(
        'type' => 'select',
        'heading' => __( 'Hover' ),
        'default' => '',
        'options' => require( __DIR__ . '/values/image-hover.php' ),
        'on_change' => array(
            'selector' => '.instagram-image-container',
            'class' => 'image-{{ value }}'
        )
    ),
  ),
),
'layout_options' => require( __DIR__ . '/commons/repeater-options.php' ),
'layout_options_slider' => require( __DIR__ . '/commons/repeater-slider.php' ),
'advanced_options' => require( __DIR__ . '/commons/advanced.php'),
'hashtag_options' => array(
    'type' => 'group',
    'heading' => __( 'Hashtag' ),
    'description' => 'These options are deprecated and will be removed in a future update.',
    'options' => array(
      'hashtag' => array(
        'type'    => 'textfield',
        'heading' => 'Hashtag',
        'default' => ''
      ),
      'hashtag_media' => array(
        'type'    => 'select',
        'heading' => __( 'Hashtag media type' ),
        'default' => 'top',
        'conditions' => 'hashtag !== ""',
        'options' => array(
          'top'    => 'Top media',
          'recent' => 'Recent media (within 24 hours)'
        ),
      ),
    ),
),
);

// Set defaults.
$options['layout_options']['options']['columns']['default']     = '5';
$options['layout_options']['options']['col_spacing']['default'] = 'collapse';
$options['layout_options']['options']['type']['default']        = 'row';
$options['layout_options']['options']['type']['options']        = array(
  'slider' => 'Slider',
  'row'    => 'Row',
);

add_ux_builder_shortcode( 'ux_instagram_feed', array(
  'name'      => __( 'Instagram Feed' ),
  'category'  => __( 'Content' ),
  'thumbnail' => flatsome_ux_builder_thumbnail( 'instagram_feed' ),
  'presets'   => array(
    array(
      'name'    => __( 'Default' ),
      'content' => '[ux_instagram_feed username="' . $default_account . '"]',
    ),
    array(
      'name'    => __( 'Simple Grid' ),
      'content' => '[ux_instagram_feed username="' . $default_account . '" col_spacing="small"]',
    ),
    array(
      'name'    => __( 'Full Width Slider' ),
      'content' => '[ux_instagram_feed username="' . $default_account . '" type="slider" slider_nav_color="light" width="full-width" columns="6"]',
    ),
    array(
      'name'    => __( 'Simple Slider' ),
      'content' => '[ux_instagram_feed username="' . $default_account . '" type="slider" slider_nav_position="outside" col_spacing="small"]',
    ),
    array(
      'name'    => __( 'Full Width B&W' ),
      'content' => '[ux_instagram_feed username="' . $default_account . '" image_hover="color" type="slider" slider_nav_color="light" width="full-width" columns="6"]',
    ),
  ),
  'options'   => $options,
) );
