<?php

Flatsome_Option::add_section( 'woocommerce_product_catalog', array(
  'title' => __( 'Product Catalog', 'woocommerce' ),
  'panel' => 'woocommerce',
) );

Flatsome_Option::add_field( '', array(
	'type'     => 'custom',
	'settings' => 'custom_title_category_homepage',
	'label'    => __( '', 'flatsome-admin' ),
	'section'  => 'woocommerce_product_catalog',
	'default'  => '<div class="options-title-divider">Shop Page</div>',
) );

Flatsome_Option::add_field( 'option', array(
	'type'        => 'textarea',
	'settings'    => 'html_shop_page',
	'label'       => __( 'Shop Page Header', 'flatsome-admin' ),
	'description' => __( 'Enter HTML that should be placed on top of main shop page. Shortcodes are allowed. This will replace Shop Homepage Header', 'flatsome-admin' ),
	'section'     => 'woocommerce_product_catalog',
	'default'     => '',
) );

Flatsome_Option::add_field( 'option', array(
	'type'        => 'textarea',
	'settings'    => 'html_shop_page_content',
	'label'       => __( 'Shop Page Content', 'flatsome-admin' ),
	'description' => __( 'Enter HTML/Shortcodes that should replace Shop Homepage content.', 'flatsome-admin' ),
	'section'     => 'woocommerce_product_catalog',
	'default'     => '',
) );

Flatsome_Option::add_field( '', array(
	'type'     => 'custom',
	'settings' => 'custom_title_category_layout',
	'label'    => __( '', 'flatsome-admin' ),
	'section'  => 'woocommerce_product_catalog',
	'default'  => '<div class="options-title-divider">Catalog Layout</div>',
) );

Flatsome_Option::add_field( 'option', array(
	'type'     => 'radio-image',
	'settings' => 'category_sidebar',
	'label'    => __( 'Layout', 'flatsome-admin' ),
	'section'  => 'woocommerce_product_catalog',
	'default'  => 'left-sidebar',
	'choices'  => array(
		'none'          => $image_url . 'category-no-sidebar.svg',
		'left-sidebar'  => $image_url . 'category-left-sidebar.svg',
		'right-sidebar' => $image_url . 'category-right-sidebar.svg',
		'off-canvas'    => $image_url . 'category-off-canvas.svg',
	),
) );

Flatsome_Option::add_field( 'option', array(
	'type'            => 'checkbox',
	'settings'        => 'category_sticky_sidebar',
	'label'           => __( 'Sticky sidebar', 'flatsome-admin' ),
	'section'         => 'woocommerce_product_catalog',
	'active_callback' => array(
		array(
			'setting'  => 'category_sidebar',
			'operator' => '!==',
			'value'    => 'none',
		),
		array(
			'setting'  => 'category_sidebar',
			'operator' => '!==',
			'value'    => 'off-canvas',
		),
	),
	'default'         => 0,
) );

Flatsome_Option::add_field( 'option', array(
	'type'            => 'select',
	'settings'        => 'category_sticky_sidebar_mode',
	'label'           => __( 'Sticky mode', 'flatsome-admin' ),
	'section'         => 'woocommerce_product_catalog',
	'default'         => '',
	'choices'         => array(
		''           => __( 'CSS (native)', 'flatsome-admin' ),
		'javascript' => __( 'JavaScript (enhanced)', 'flatsome-admin' ),
	),
	'active_callback' => array(
		array(
			'setting'  => 'category_sidebar',
			'operator' => '!==',
			'value'    => 'none',
		),
		array(
			'setting'  => 'category_sidebar',
			'operator' => '!==',
			'value'    => 'off-canvas',
		),
		array(
			'setting'  => 'category_sticky_sidebar',
			'operator' => '==',
			'value'    => true,
		),
	),
) );

Flatsome_Option::add_field( 'option', array(
	'type'      => 'radio-image',
	'settings'  => 'category_grid_style',
	'label'     => __( 'List Style', 'flatsome-admin' ),
	'section'   => 'woocommerce_product_catalog',
	'transport' => $transport,
	'default'   => 'grid',
	'choices'   => array(
		'grid'    => $image_url . 'category-style-grid.svg',
		'list'    => $image_url . 'category-style-list.svg',
		'masonry' => $image_url . 'category-style-masonry.svg',
	),
) );

Flatsome_Option::add_field( 'option', array(
	'type'        => 'checkbox',
	'settings'    => 'category_force_image_height',
	// 'transport' => $transport,
	'label'       => __( 'EQUAL IMAGE HEIGHTS', 'flatsome-admin' ),
	'description' => 'Force all images to have the same height',
	'section'     => 'woocommerce_product_catalog',
	'default'     => false,
) );

Flatsome_Option::add_field( 'option', array(
	'type'            => 'slider',
	'settings'        => 'category_image_height',
	'label'           => __( 'Equal Image height', 'flatsome-admin' ),
	'description'     => 'Change the image height in %. (100% = 1:1)',
	'section'         => 'woocommerce_product_catalog',
	'active_callback' => array(
		array(
			'setting'  => 'category_force_image_height',
			'operator' => '==',
			'value'    => true,
		),
	),
	'transport'       => $transport,
	'default'         => 100,
	'choices'         => array(
		'min'  => 50,
		'max'  => 200,
		'step' => 1,
	),
) );


Flatsome_Option::add_field( '', array(
    'type'        => 'custom',
    'settings' => 'custom_html_woocommerce_image_shortcut_category',
    'label'       => __( '', 'flatsome-admin' ),
    'section'         => 'woocommerce_product_catalog',
    'default'     => '<button style="margin-top: 15px; margin-bottom:15px" class="button button-primary" data-to-section="woocommerce_product_images">Thumbnail Image Settings →</button>',
) );

Flatsome_Option::add_field( 'option', array(
	'type'      => 'text',
	'settings'  => 'products_pr_page',
	'transport' => $transport,
	'label'     => __( 'Products per Page', 'flatsome-admin' ),
	'section'   => 'woocommerce_product_catalog',
	'default'   => 12,
) );

Flatsome_Option::add_field( 'option', array(
	'type'      => 'slider',
	'settings'  => 'category_row_count',
	'transport' => $transport,
	'label'     => __( 'Products per row - Desktop', 'flatsome-admin' ),
	'section'   => 'woocommerce_product_catalog',
	'default'   => 3,
	'choices'   => array(
		'min'  => 1,
		'max'  => 6,
		'step' => 1,
	),
) );

Flatsome_Option::add_field( 'option', array(
	'type'      => 'slider',
	'settings'  => 'category_row_count_tablet',
	'label'     => __( 'Products per row - Tablet', 'flatsome-admin' ),
	'section'   => 'woocommerce_product_catalog',
	'transport' => $transport,
	'default'   => 3,
	'choices'   => array(
		'min'  => 1,
		'max'  => 4,
		'step' => 1,
	),
) );

Flatsome_Option::add_field( 'option', array(
	'type'      => 'slider',
	'settings'  => 'category_row_count_mobile',
	'label'     => __( 'Products per row - Mobile', 'flatsome-admin' ),
	'section'   => 'woocommerce_product_catalog',
	'transport' => $transport,
	'default'   => 2,
	'choices'   => array(
		'min'  => 1,
		'max'  => 3,
		'step' => 1,
	),
) );

Flatsome_Option::add_field( '', array(
	'type'     => 'custom',
	'settings' => 'custom_title_category_header',
	'label'    => __( '', 'flatsome-admin' ),
	'section'  => 'woocommerce_product_catalog',
	'default'  => '<div class="options-title-divider">Header</div>',
) );

Flatsome_Option::add_field( 'option', array(
	'type'      => 'radio-image',
	'settings'  => 'category_title_style',
	'label'     => __( 'Title Style', 'flatsome-admin' ),
	'section'   => 'woocommerce_product_catalog',
	'transport' => $transport,
	'default'   => '',
	'choices'   => array(
		''                => $image_url . 'category-title.svg',
		'featured'        => $image_url . 'category-title-featured.svg',
		'featured-center' => $image_url . 'category-title-featured-center.svg',
	),
) );

Flatsome_Option::add_field( 'option', array(
	'type'      => 'checkbox',
	'settings'  => 'category_show_title',
	'transport' => $transport,
	'label'     => __( 'Show title', 'flatsome-admin' ),
	'section'   => 'woocommerce_product_catalog',
	'default'   => '0',
) );

Flatsome_Option::add_field( 'option', array(
	'type'            => 'checkbox',
	'settings'        => 'breadcrumb_home',
	'transport'       => $transport,
	'label'           => __( 'Show home link in breadcrumb', 'flatsome-admin' ),
	'section'         => 'woocommerce_product_catalog',
	'active_callback' => function () {
		$wpseo     = class_exists( 'WPSEO_Options' ) && get_theme_mod( 'wpseo_breadcrumb' ) ? true : false;
		$rank_math = class_exists( 'RankMath' ) && get_theme_mod( 'rank_math_breadcrumb' ) ? true : false;

		return ! $wpseo && ! $rank_math;
	},
	'default'         => 1,
) );

Flatsome_Option::add_field( 'option', array(
	'type'     => 'checkbox',
	'settings' => 'category_header_transparent',
	'label'    => __( 'Transparent Header', 'flatsome-admin' ),
	'section'  => 'woocommerce_product_catalog',
	'default'  => '0',
) );

Flatsome_Option::add_field( 'option', array(
	'type'      => 'checkbox',
	'settings'  => 'header_shop_bg_featured',
	'transport' => $transport,
	'help'      => __( 'Use Featured Images from categories and products as background. Will fallback to default Shop Title background if nothing is set.', 'flatsome-admin' ),
	'label'     => __( 'Featured Image as Background', 'flatsome-admin' ),
	'section'   => 'woocommerce_product_catalog',
	'default'   => 1,
) );

Flatsome_Option::add_field( 'option', array(
	'type'      => 'image',
	'settings'  => 'header_shop_bg_image',
	'transport' => $transport,
	'label'     => __( 'Shop Title Background', 'flatsome-admin' ),
	'section'   => 'woocommerce_product_catalog',
	'default'   => '',
) );

Flatsome_Option::add_field( 'option', array(
	'type'      => 'color-alpha',
	'alpha'     => true,
	'settings'  => 'header_shop_bg_color',
	'transport' => $transport,
	'label'     => __( 'Title Background Color', 'flatsome-admin' ),
	'section'   => 'woocommerce_product_catalog',
	'default'   => 'rgba(0,0,0,.3)',
) );

Flatsome_Option::add_field( 'option', array(
	'type'      => 'text',
	'settings'  => 'category_filter_text',
	'transport' => $transport,
	'label'     => __( 'Custom Filter Text', 'flatsome-admin' ),
	'section'   => 'woocommerce_product_catalog',
	'default'   => '',
) );

Flatsome_Option::add_field( '', array(
	'type'     => 'custom',
	'settings' => 'custom_title_category_breadcrumbs',
	'label'    => __( '', 'flatsome-admin' ),
	'section'  => 'woocommerce_product_catalog',
	'default'  => '<div class="options-title-divider">Breadcrumbs</div>',
) );

Flatsome_Option::add_field( 'option', array(
	'type'     => 'select',
	'settings' => 'breadcrumb_size',
	'label'    => __( 'Breadcrumb Size', 'flatsome-admin' ),
	'help'     => __( 'Change size of breadcrumb on product categories. Useful if you have long breadcrumbs.', 'flatsome-admin' ),
	'section'  => 'woocommerce_product_catalog',
	'default'  => 'large',
	'choices'  => $sizes,
) );


Flatsome_Option::add_field( 'option', array(
	'type'     => 'radio-buttonset',
	'settings' => 'breadcrumb_case',
	'label'    => esc_attr__( 'Breadcrumbs Case', 'flatsome-admin' ),
	'section'  => 'woocommerce_product_catalog',
	'default'  => 'uppercase',
	'choices'  => array(
		'uppercase' => 'UPPERCASE',
		''          => 'Normal',
	),
) );

Flatsome_Option::add_field( '', array(
	'type'     => 'custom',
	'settings' => 'custom_title_category_category_box',
	'label'    => __( '', 'flatsome-admin' ),
	'section'  => 'woocommerce_product_catalog',
	'default'  => '<div class="options-title-divider">Category Box</div>',
) );

Flatsome_Option::add_field( 'option', array(
	'type'      => 'radio-image',
	'settings'  => 'cat_style',
	'label'     => __( 'Category Box Style', 'flatsome-admin' ),
	'section'   => 'woocommerce_product_catalog',
	'transport' => $transport,
	'default'   => 'badge',
	'choices'   => array(
		'normal'  => $image_url . 'category-box.svg',
		'badge'   => $image_url . 'category-box-badge.svg',
		'overlay' => $image_url . 'category-box-overlay.svg',
		'label'   => $image_url . 'category-box-label.svg',
		'shade'   => $image_url . 'category-box-shade.svg',
		'bounce'  => $image_url . 'category-box-bounce.svg',
		'push'    => $image_url . 'category-box-push.svg',
	),
) );

Flatsome_Option::add_field( 'option', array(
	'type'      => 'checkbox',
	'settings'  => 'category_show_count',
	'transport' => $transport,
	'label'     => __( 'Show product count', 'flatsome-admin' ),
	'section'   => 'woocommerce_product_catalog',
	'default'   => 1,
) );

Flatsome_Option::add_field( '', array(
	'type'     => 'custom',
	'settings' => 'custom_title_category_product_box',
	'label'    => __( '', 'flatsome-admin' ),
	'section'  => 'woocommerce_product_catalog',
	'default'  => '<div class="options-title-divider">Product Box</div>',
) );

Flatsome_Option::add_field( 'option', array(
	'type'      => 'radio-image',
	'settings'  => 'grid_style',
	'label'     => __( 'Grid Style', 'flatsome-admin' ),
	'section'   => 'woocommerce_product_catalog',
	'transport' => $transport,
	'default'   => 'grid1',
	'choices'   => array(
		'grid1' => $image_url . 'product-box.svg',
		'grid2' => $image_url . 'product-box-center.svg',
		'grid3' => $image_url . 'product-box-wide.svg',
	),
) );

Flatsome_Option::add_field( 'option', array(
	'type'      => 'select',
	'settings'  => 'product_hover',
	'transport' => $transport,
	'label'     => __( 'Product Image Hover style', 'flatsome-admin' ),
	'section'   => 'woocommerce_product_catalog',
	'default'   => 'fade_in_back',
	'choices'   => array(
		'none'         => __( 'None', 'flatsome-admin' ),
		'fade_in_back' => __( 'Back Image - Fade In', 'flatsome-admin' ),
		'zoom_in'      => __( 'Back Image - Zoom In', 'flatsome-admin' ),
		'zoom'         => 'Zoom',
		'zoom-fade'    => 'Zoom Fade',
		'blur'         => 'Blur',
		'fade-in'      => 'Fade In',
		'fade-out'     => 'Fade Out',
		'glow'         => 'Glow',
		'color'        => 'Add Color',
		'grayscale'    => 'Grayscale',
	),
) );

Flatsome_Option::add_field( 'option', array(
	'type'      => 'slider',
	'settings'  => 'category_shadow',
	'label'     => __( 'Drop Shadow', 'flatsome-admin' ),
	'section'   => 'woocommerce_product_catalog',
	'transport' => $transport,
	'default'   => 0,
	'choices'   => array(
		'min'  => 0,
		'max'  => 5,
		'step' => 1,
	),
) );

Flatsome_Option::add_field( 'option', array(
	'type'      => 'slider',
	'settings'  => 'category_shadow_hover',
	'label'     => __( 'Drop Shadow :hover', 'flatsome-admin' ),
	'section'   => 'woocommerce_product_catalog',
	'transport' => $transport,
	'default'   => 0,
	'choices'   => array(
		'min'  => 0,
		'max'  => 5,
		'step' => 1,
	),
) );

Flatsome_Option::add_field( 'option', array(
	'type'      => 'radio-image',
	'settings'  => 'add_to_cart_icon',
	'label'     => __( 'Add To Cart Button', 'flatsome-admin' ),
	'section'   => 'woocommerce_product_catalog',
	'transport' => $transport,
	'default'   => 'disable',
	'choices'   => array(
		'disable' => $image_url . 'product-box.svg',
		'show'    => $image_url . 'product-box-add-to-cart-icon.svg',
		'button'  => $image_url . 'product-box-add-to-cart-button.svg',
	),
) );

Flatsome_Option::add_field( 'option', array(
	'type'      => 'select',
	'settings'  => 'add_to_cart_style',
	'label'     => __( 'Button Style', 'flatsome-admin' ),
	'section'   => 'woocommerce_product_catalog',
	'transport' => $transport,
	'default'   => 'outline',
	'choices'   => array(
		'flat'      => __( 'Plain', 'flatsome-admin' ),
		'outline'   => __( 'Outline', 'flatsome-admin' ),
		'underline' => __( 'Underline', 'flatsome-admin' ),
		'shade'     => __( 'Shade', 'flatsome-admin' ),
		'bevel'     => __( 'Bevel', 'flatsome-admin' ),
		'gloss'     => __( 'Gloss', 'flatsome-admin' ),
	),
) );

Flatsome_Option::add_field( 'option', array(
	'type'      => 'checkbox',
	'settings'  => 'product_box_category',
	'transport' => $transport,
	'label'     => __( 'Show Category', 'flatsome-admin' ),
	'section'   => 'woocommerce_product_catalog',
	'default'   => 1,
) );

Flatsome_Option::add_field( 'option', array(
	'type'      => 'checkbox',
	'settings'  => 'product_box_rating',
	'transport' => $transport,
	'label'     => __( 'Show Ratings', 'flatsome-admin' ),
	'section'   => 'woocommerce_product_catalog',
	'default'   => 1,
) );

Flatsome_Option::add_field( 'option', array(
	'type'      => 'checkbox',
	'settings'  => 'product_box_review_count',
	'transport' => $transport,
	'label'     => __( 'Show review count', 'flatsome-admin' ),
	'section'   => 'woocommerce_product_catalog',
	'default'   => 0,
) );

Flatsome_Option::add_field( 'option', array(
	'type'      => 'checkbox',
	'settings'  => 'short_description_in_grid',
	'transport' => $transport,
	'label'     => __( 'Show Short Description', 'flatsome-admin' ),
	'section'   => 'woocommerce_product_catalog',
	'default'   => '0',
) );

Flatsome_Option::add_field( 'option', array(
	'type'      => 'checkbox',
	'settings'  => 'disable_quick_view',
	'transport' => $transport,
	'label'     => __( 'Disable Quick View', 'flatsome-admin' ),
	'section'   => 'woocommerce_product_catalog',
	'default'   => 0,
) );

Flatsome_Option::add_field( 'option', array(
	'type'        => 'checkbox',
	'settings'    => 'equalize_product_box',
	'transport'   => $transport,
	'label'       => esc_attr__( 'Equalize Items', 'flatsome-admin' ),
	'section'     => 'woocommerce_product_catalog',
	'default'     => '0',
) );

Flatsome_Option::add_field( 'option', array(
	'type'      => 'radio-image',
	'settings'  => 'bubble_style',
	'label'     => __( 'Bubble Style', 'flatsome-admin' ),
	'section'   => 'woocommerce_product_catalog',
	'transport' => $transport,
	'default'   => 'style1',
	'choices'   => array(
		'style1' => $image_url . 'badge-circle.svg',
		'style2' => $image_url . 'badge-square.svg',
		'style3' => $image_url . 'badge-border.svg',
	),
) );

Flatsome_Option::add_field( 'option', array(
	'type'        => 'checkbox',
	'settings'    => 'sale_bubble',
	'transport'   => $transport,
	'label'       => __( 'Show sale bubble', 'flatsome-admin' ),
	'section'     => 'woocommerce_product_catalog',
	'default'     => 1,
) );

Flatsome_Option::add_field( 'option', array(
	'type'              => 'text',
	'settings'          => 'sale_bubble_text',
	'transport'         => $transport,
	'label'             => __( 'Custom sale bubble text', 'flatsome-admin' ),
	'section'           => 'woocommerce_product_catalog',
	'sanitize_callback' => 'wp_kses_post',
	'active_callback' => array(
		array(
			'setting'  => 'sale_bubble',
			'operator' => '!=',
			'value'    => false,
		),
	),
	'default'           => '',
) );

Flatsome_Option::add_field( 'option', array(
	'type'     => 'checkbox',
	'settings' => 'sale_bubble_percentage',
	'label'    => __( 'Enable % instead of "Sale!"', 'flatsome-admin' ),
	'section'  => 'woocommerce_product_catalog',
	'active_callback' => array(
		array(
			'setting'  => 'sale_bubble',
			'operator' => '!=',
			'value'    => false,
		),
	),
	'default'  => '0',
) );

Flatsome_Option::add_field( 'option', array(
	'type'              => 'text',
	'settings'          => 'sale_bubble_percentage_formatting',
	'transport'         => $transport,
	'label'             => __( 'Sale bubble % formatting', 'flatsome-admin' ),
	'description'       => __( 'How the discount should be displayed. e.g. -{value}%', 'flatsome-admin' ),
	'section'           => 'woocommerce_product_catalog',
	'sanitize_callback' => 'wp_kses_post',
	'active_callback'   => array(
		array(
			'setting'  => 'sale_bubble_percentage',
			'operator' => '==',
			'value'    => true,
		),
		array(
			'setting'  => 'sale_bubble',
			'operator' => '!=',
			'value'    => false,
		),
	),
	'default'           => '-{value}%',
) );

Flatsome_Option::add_field( 'option', array(
	'type'        => 'number',
	'settings'    => 'new_bubble_auto',
	'label'       => __( 'Auto "New" bubble', 'flatsome-admin' ),
	'description' => __( 'Shown on products witch age is x and lower in days.', 'flatsome-admin' ),
	'section'     => 'woocommerce_product_catalog',
	'transport'   => $transport,
	'default'     => 0,
	'choices'     => array(
		'min'  => 0,
		'step' => 1,
	),
) );

if ( get_theme_mod( 'swatches' ) ) :
	Flatsome_Option::add_field( '', array(
		'type'     => 'custom',
		'settings' => 'custom_title_category_swatches',
		'label'    => '',
		'section'  => 'woocommerce_product_catalog',
		'default'  => '<div class="options-title-divider">Swatches</div>',
	) );

	Flatsome_Option::add_field( 'option', array(
		'type'     => 'select',
		'settings' => 'swatches_box_attribute',
		'label'    => __( 'Product attribute', 'flatsome-admin' ),
		'tooltip'  => __( 'The product (variation) atrribute that swatches will be populated for (usually an attribute that determines colors).', 'flatsome-admin' ),
		'section'  => 'woocommerce_product_catalog',
		'default'  => '',
		'choices'  => flatsome_get_product_attribute_taxonomies_list_by_id( array( 'option_none' => '-- None --' ) ),
	) );

	Flatsome_Option::add_field( 'option', array(
		'type'            => 'radio-buttonset',
		'settings'        => 'swatches_box_layout',
		'label'           => __( 'Layout', 'flatsome-admin' ),
		'section'         => 'woocommerce_product_catalog',
		'active_callback' => array(
			array(
				'setting'  => 'swatches_box_attribute',
				'operator' => '!=',
				'value'    => '',
			),
		),
		'default'         => '',
		'choices'         => array(
			''      => 'Normal',
			'limit' => 'Limited',
		),
	) );

	Flatsome_Option::add_field( 'option', array(
		'type'            => 'slider',
		'settings'        => 'swatches_box_limit',
		'label'           => __( 'Limit', 'flatsome-admin' ),
		'section'         => 'woocommerce_product_catalog',
		'active_callback' => array(
			array(
				'setting'  => 'swatches_box_attribute',
				'operator' => '!=',
				'value'    => '',
			),
			array(
				'setting'  => 'swatches_box_layout',
				'operator' => '===',
				'value'    => 'limit',
			),
		),
		'default'         => 5,
		'choices'         => array(
			'min'  => 1,
			'max'  => 30,
			'step' => 1,
		),
	) );

	Flatsome_Option::add_field( 'option', array(
		'type'            => 'radio-buttonset',
		'settings'        => 'swatches_box_size',
		'label'           => __( 'Size', 'flatsome-admin' ),
		'section'         => 'woocommerce_product_catalog',
		'transport'       => $transport,
		'active_callback' => array(
			array(
				'setting'  => 'swatches_box_attribute',
				'operator' => '!=',
				'value'    => '',
			),
		),
		'default'         => '',
		'choices'         => array(
			'x-small' => 'X Small',
			'small'   => 'Small',
			''        => 'Medium',
			'large'   => 'Large',
			'x-large' => 'X Large',
		),
	) );

	Flatsome_Option::add_field( 'option', array(
		'type'            => 'radio-buttonset',
		'settings'        => 'swatches_box_shape',
		'label'           => __( 'Shape', 'flatsome-admin' ),
		'section'         => 'woocommerce_product_catalog',
		'transport'       => $transport,
		'active_callback' => array(
			array(
				'setting'  => 'swatches_box_attribute',
				'operator' => '!=',
				'value'    => '',
			),
		),
		'default'         => '',
		'choices'         => array(
			''        => 'Square',
			'rounded' => 'Rounded',
			'circle'  => 'Circle',
		),
	) );

	Flatsome_Option::add_field( 'option', array(
		'type'            => 'radio-buttonset',
		'settings'        => 'swatches_box_select_event',
		'label'           => __( 'Select event - Desktop', 'flatsome-admin' ),
		'section'         => 'woocommerce_product_catalog',
		'active_callback' => array(
			array(
				'setting'  => 'swatches_box_attribute',
				'operator' => '!=',
				'value'    => '',
			),
		),
		'default'         => '',
		'choices'         => array(
			''      => 'On hover',
			'click' => 'On click',
		),
	) );

	Flatsome_Option::add_field( 'option', array(
		'type'            => 'color',
		'settings'        => 'swatches_box_color_selected',
		'transport'       => 'postMessage',
		'label'           => __( 'Color :selected', 'flatsome' ),
		'section'         => 'woocommerce_product_catalog',
		'active_callback' => array(
			array(
				'setting'  => 'swatches_box_attribute',
				'operator' => '!=',
				'value'    => '',
			),
		),
		'default'         => Flatsome_Default::COLOR_SECONDARY,
	) );

	Flatsome_Option::add_field( 'option', array(
		'type'            => 'select',
		'settings'        => 'swatches_box_behavior_selected',
		'label'           => __( 'Behavior :selected', 'flatsome-admin' ),
		'description'     => __( 'When clicking an already selected swatch.', 'flatsome-admin' ),
		'section'         => 'woocommerce_product_catalog',
		'active_callback' => array(
			array(
				'setting'  => 'swatches_box_attribute',
				'operator' => '!=',
				'value'    => '',
			),
		),
		'default'         => '',
		'choices'         => array(
			''         => 'Go to product page',
			'unselect' => 'Unselect swatch',
		),
	) );

	Flatsome_Option::add_field( 'option', array(
		'type'            => 'checkbox',
		'settings'        => 'swatches_box_update_urls',
		'label'           => __( 'Update links to selected variation', 'flatsome-admin' ),
		'section'         => 'woocommerce_product_catalog',
		'active_callback' => array(
			array(
				'setting'  => 'swatches_box_attribute',
				'operator' => '!=',
				'value'    => '',
			),
		),
		'default'         => 1,
	) );

	Flatsome_Option::add_field( 'option', array(
		'type'            => 'checkbox',
		'settings'        => 'swatches_box_reset',
		'label'           => __( 'Auto reset', 'flatsome-admin' ),
		'section'         => 'woocommerce_product_catalog',
		'active_callback' => array(
			array(
				'setting'  => 'swatches_box_attribute',
				'operator' => '!=',
				'value'    => '',
			),
		),
		'default'         => 0,
	) );

	Flatsome_Option::add_field( 'option', array(
		'type'            => 'checkbox',
		'settings'        => 'swatches_box_reset_limited',
		'label'           => __( 'Reset limited', 'flatsome-admin' ),
		'section'         => 'woocommerce_product_catalog',
		'active_callback' => array(
			array(
				'setting'  => 'swatches_box_attribute',
				'operator' => '!=',
				'value'    => '',
			),
			array(
				'setting'  => 'swatches_box_layout',
				'operator' => '==',
				'value'    => 'limit',
			),
			array(
				'setting'  => 'swatches_box_reset',
				'operator' => '!=',
				'value'    => 0,
			),
		),
		'default'         => 0,
	) );

	Flatsome_Option::add_field( 'option', array(
		'type'            => 'radio-buttonset',
		'settings'        => 'swatches_box_reset_extent',
		'label'           => __( 'Reset extent', 'flatsome-admin' ),
		'description'     => __( 'The area the pointer should leave for a reset.', 'flatsome-admin' ),
		'section'         => 'woocommerce_product_catalog',
		'active_callback' => array(
			array(
				'setting'  => 'swatches_box_attribute',
				'operator' => '!=',
				'value'    => '',
			),
			array(
				'setting'  => 'swatches_box_reset',
				'operator' => '!=',
				'value'    => 0,
			),
		),
		'default'         => '',
		'choices'         => array(
			''            => __( 'Swatch', 'flatsome-admin' ),
			'product-box' => __( 'Product box', 'flatsome-admin' ),
		),
	) );

	Flatsome_Option::add_field( 'option', array(
		'type'            => 'slider',
		'settings'        => 'swatches_box_reset_time',
		'label'           => __( 'Reset time', 'flatsome-admin' ),
		'description'     => __( 'In milliseconds (1000ms = 1sec).', 'flatsome-admin' ),
		'section'         => 'woocommerce_product_catalog',
		'active_callback' => array(
			array(
				'setting'  => 'swatches_box_attribute',
				'operator' => '!=',
				'value'    => '',
			),
			array(
				'setting'  => 'swatches_box_reset',
				'operator' => '!=',
				'value'    => 0,
			),
		),
		'default'         => 300,
		'choices'         => array(
			'min'  => 100,
			'max'  => 10000,
			'step' => 100,
		),
	) );
endif;
