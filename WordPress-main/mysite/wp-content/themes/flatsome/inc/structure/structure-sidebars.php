<?php

function flatsome_sidebar_classes(){

   echo implode(' ',  apply_filters( 'flatsome_sidebar_class', array() ) );
}


function flatsome_add_sidebar_class($classes){
	//$classes[] = 'col-divided';
	//$classes[] = 'widgets-boxed';

	return $classes;
}

add_filter('flatsome_sidebar_class','flatsome_add_sidebar_class', 10);

/**
 * Renders the sidebar menu header content.
 */
function flatsome_mobile_sidebar_top_content() {
	if ( $top_content = get_theme_mod( 'mobile_sidebar_top_content' ) ) {
		echo '<div class="sidebar-menu-top-content">';
		echo do_shortcode( $top_content );
		echo '</div>';
	}
}

add_action( 'flatsome_before_sidebar_menu_elements', 'flatsome_mobile_sidebar_top_content' );
