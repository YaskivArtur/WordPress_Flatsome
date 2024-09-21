<?php

add_action( 'wc_cpdf_init', 'wc_custom_product_data_fields', 10, 0 );

if ( ! function_exists( 'wc_custom_product_data_fields' ) ) {
	/**
	 * Custom WooCommerce product fields
	 *
	 * @return array
	 */
	function wc_custom_product_data_fields() {

		$custom_product_data_fields = array();

		$custom_product_data_fields['ux_product_layout_tab'] = array(
			array(
				'tab_name' => __( 'Product layout', 'flatsome' ),
			),
			array(
				'id'          => '_product_block',
				'type'        => 'select',
				'label'       => __( 'Custom product layout', 'flatsome' ),
				'style'       => 'width:100%;height:140px;',
				'description' => __( 'Choose a custom product block layout for this product.', 'flatsome' ),
				'desc_tip'    => true,
				'options'     => flatsome_get_block_list_by_id( array( 'option_none' => '-- None --' ) ),
			),
			array(
				'id'          => '_top_content',
				'type'        => 'textarea',
				'label'       => __( 'Top Content', 'flatsome' ),
				'style'       => 'width:100%;height:140px;',
				'description' => __( 'Enter content that will show after the header and before the product. Shortcodes are allowed', 'flatsome' ),
			),
			array(
				'id'          => '_bottom_content',
				'type'        => 'textarea',
				'label'       => __( 'Bottom Content', 'flatsome' ),
				'style'       => 'width:100%;height:140px;',
				'description' => __( 'Enter content that will show after the product info. Shortcodes are allowed', 'flatsome' ),
			),
		);

		$custom_product_data_fields['ux_extra_tab'] = array(
			array(
				'tab_name' => __( 'Extra', 'flatsome' ),
			),
			array(
				'id'          => '_bubble_new',
				'type'        => 'select',
				'label'       => __( 'Custom Bubble', 'flatsome-admin' ),
				'description' => __( 'Enable a custom bubble on this product.', 'flatsome' ),
				'desc_tip'    => true,
				'options'     => array(
					''      => 'Disabled',
					'"yes"' => 'Enabled',
				),
			),
			array(
				'id'          => '_bubble_text',
				'type'        => 'text',
				'label'       => __( 'Custom Bubble Title', 'flatsome-admin' ),
				'placeholder' => __( 'NEW', 'flatsome-admin' ),
				'class'       => 'large',
				'description' => __( 'Field description.', 'flatsome-admin' ),
				'desc_tip'    => true,
			),
			array(
				'type' => 'divider',
			),
			array(
				'id'          => '_custom_tab_title',
				'type'        => 'text',
				'label'       => __( 'Custom Tab Title', 'flatsome-admin' ),
				'class'       => 'large',
				'description' => __( 'Field description.', 'flatsome-admin' ),
				'desc_tip'    => true,
			),
			array(
				'id'          => '_custom_tab',
				'type'        => 'textarea',
				'label'       => __( 'Custom Tab Content', 'flatsome' ),
				'style'       => 'width:100%;height:140px;',
				'description' => __( 'Enter content for custom product tab here. Shortcodes are allowed', 'flatsome' ),
			),
			array(
				'type' => 'divider',
			),
			array(
				'id'          => '_product_video',
				'type'        => 'text',
				'placeholder' => 'https://www.youtube.com/watch?v=Ra_iiSIn4OI',
				'label'       => __( 'Product Video', 'flatsome' ),
				'style'       => 'width:100%;',
				'description' => __( 'Enter a Youtube or Vimeo Url of the product video here. We recommend uploading your video to Youtube.', 'flatsome' ),
			),
			array(
				'id'          => '_product_video_size',
				'type'        => 'text',
				'label'       => __( 'Product Video Size', 'flatsome-admin' ),
				'placeholder' => __( '900x900', 'flatsome-admin' ),
				'class'       => 'large',
				'description' => __( 'Set Product Video Size.. Default is 900x900. (Width X Height)', 'flatsome-admin' ),
				'desc_tip'    => true,
			),
			array(
				'id'          => '_product_video_placement',
				'type'        => 'select',
				'label'       => __( 'Product Video Placement', 'flatsome-admin' ),
				'description' => __( 'Select where you want to display product video.', 'flatsome' ),
				'desc_tip'    => true,
				'options'     => array(
					''    => 'Lightbox (Default)',
					'tab' => 'New Tab',
				),
			),
		);

		return $custom_product_data_fields;
	}
}
