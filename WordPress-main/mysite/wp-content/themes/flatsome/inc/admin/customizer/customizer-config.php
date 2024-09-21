<?php
/**
 * Add Custom CSS to Customizer
 */

function flatsome_enqueue_customizer_stylesheet() {
    $theme = wp_get_theme( get_template() );
    $version = $theme['Version'];

    flatsome_enqueue_asset( 'flatsome-customizer-admin-js', 'admin/customizer-admin' );
    wp_enqueue_style( 'flatsome-header-builder-css', get_template_directory_uri() . '/assets/css/admin/admin-header-builder.css', NULL, $version, 'all' );
    wp_enqueue_style( 'flatsome-customizer-admin', get_template_directory_uri() . '/assets/css/admin/admin-customizer.css', NULL, $version, 'all' );
}
add_action( 'customize_controls_print_styles', 'flatsome_enqueue_customizer_stylesheet' );

function flatsome_customizer_live_preview() {
    $theme = wp_get_theme( 'flatsome' );
    $version = $theme['Version'];

    flatsome_enqueue_asset( 'flatsome-customizer-frontend-js', 'admin/customizer-frontend' );
    wp_enqueue_style( 'flatsome-customizer-preview', get_template_directory_uri() . '/assets/css/admin/admin-frontend.css', NULL, $version, 'all' );
}
add_action( 'customize_preview_init', 'flatsome_customizer_live_preview' );
