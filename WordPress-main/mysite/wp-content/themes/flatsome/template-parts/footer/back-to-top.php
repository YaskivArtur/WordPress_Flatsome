<?php
/**
 * Back to top.
 *
 * @package          Flatsome\Templates
 * @flatsome-version 3.16.0
 */

$classes   = array( 'back-to-top', 'button', 'icon', 'invert', 'plain', 'fixed', 'bottom', 'z-1', 'is-outline', 'round' );
$classes[] = get_theme_mod( 'back_to_top_position' ) === 'left' ? 'left' : '';
$classes[] = get_theme_mod( 'back_to_top_mobile' ) ? '' : 'hide-for-medium';
$shape     = get_theme_mod( 'back_to_top_shape', 'circle' );

if ( $shape === 'circle' ) {
	$classes[] = 'circle';
	$classes   = array_diff( $classes, array( 'round' ) );
}

$classes = implode( ' ', array_filter( $classes ) );
?>

<a href="#top" class="<?php echo $classes; ?>" id="top-link" aria-label="<?php esc_attr_e('Go to top', 'flatsome' ); ?>"><?php echo get_flatsome_icon( 'icon-angle-up' ); ?></a>
