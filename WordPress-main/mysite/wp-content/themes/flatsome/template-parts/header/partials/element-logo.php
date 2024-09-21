<?php
/**
 * Logo element.
 *
 * @package          Flatsome\Templates
 * @flatsome-version 3.16.0
 */

$site_logo_id        = flatsome_option( 'site_logo' );
$site_logo_sticky_id = flatsome_option( 'site_logo_sticky' );
$site_logo_dark_id   = flatsome_option( 'site_logo_dark' );
$site_logo           = wp_get_attachment_image_src( $site_logo_id, 'large' );
$site_logo_sticky    = wp_get_attachment_image_src( $site_logo_sticky_id, 'large' );
$site_logo_dark      = wp_get_attachment_image_src( $site_logo_dark_id, 'large' );
$logo_link           = get_theme_mod( 'logo_link' );
$logo_link           = $logo_link ? $logo_link : home_url( '/' );
$width               = get_theme_mod( 'logo_width', 200 );
$height              = get_theme_mod( 'header_height', 90 );

if ( ! empty( $site_logo_id ) && ! is_numeric( $site_logo_id ) ) {
	// Fallback to `logo_width` and `header_height` if
	// the logo is a string, ie. it's the default value.
	$site_logo = array( $site_logo_id, $width, $height );
}

if ( ! empty( $site_logo_sticky_id ) && ! is_numeric( $site_logo_sticky_id ) ) {
	$site_logo_sticky = array( $site_logo_sticky_id, $width, $height );
}

if ( ! empty( $site_logo_dark_id ) && ! is_numeric( $site_logo_dark_id ) ) {
	$site_logo_dark = array( $site_logo_dark_id, $width, $height );
}

?>

<!-- Header logo -->
<a href="<?php echo esc_url( $logo_link ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?><?php echo get_bloginfo( 'name' ) && get_bloginfo( 'description' ) ? ' - ' : ''; ?><?php bloginfo( 'description' ); ?>" rel="home">
		<?php
		if ( $site_logo ) {
			$site_title = esc_attr( get_bloginfo( 'name', 'display' ) );
			if ( $site_logo_sticky ) echo '<img width="' . esc_attr( $site_logo_sticky[1] ) . '" height="' . esc_attr( $site_logo_sticky[2] ) . '" src="' . esc_url( $site_logo_sticky[0] ) . '" class="header-logo-sticky" alt="'.$site_title.'"/>';
			echo '<img width="' . esc_attr( $site_logo[1] ) . '" height="' . esc_attr( $site_logo[2] ) . '" src="' . esc_url( $site_logo[0] ) . '" class="header_logo header-logo" alt="'.$site_title.'"/>';
			if ( $site_logo_dark ) echo '<img  width="' . esc_attr( $site_logo_dark[1] ) . '" height="' . esc_attr( $site_logo_dark[2] ) . '" src="' . esc_url( $site_logo_dark[0] ) . '" class="header-logo-dark" alt="'.$site_title.'"/>';
			else echo '<img  width="' . esc_attr( $site_logo[1] ) . '" height="' . esc_attr( $site_logo[2] ) . '" src="' . esc_url( $site_logo[0] ) . '" class="header-logo-dark" alt="'.$site_title.'"/>';
		} else {
			bloginfo( 'name' );
		}
		?>
</a>
<?php
if(get_theme_mod('site_logo_slogan')){
	echo '<p class="logo-tagline">'.get_bloginfo('description').'</p>';
}
?>
