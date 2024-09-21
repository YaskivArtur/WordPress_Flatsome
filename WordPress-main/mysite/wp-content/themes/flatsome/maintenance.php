<?php
/**
 * Maintenance template.
 *
 * @package          Flatsome\Templates
 * @flatsome-version 3.16.0
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="<?php flatsome_html_classes(); ?>">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php do_action( 'flatsome_after_body_open' ); ?>
<?php wp_body_open(); ?>

<div id="wrapper">
	<main id="main" class="<?php flatsome_main_classes(); ?>">
		<?php
		if ( flatsome_option( 'maintenance_mode_page' ) ) {
			$post = get_post( flatsome_option( 'maintenance_mode_page' ) );
			echo do_shortcode( $post->post_content );
		} else {
			$logo_url = do_shortcode( flatsome_option( 'site_logo' ) );
			echo do_shortcode( '[ux_banner bg_color="#fff" bg_overlay="rgba(255,255,255,.9)" height="100%"] [text_box animate="fadeInUp" text_color="dark"] [ux_image id="' . $logo_url . '" width="70%"] [divider] <p class="lead">' . flatsome_option( 'maintenance_mode_text' ) . '</p> [/text_box] [/ux_banner]' );
		}
		?>
	</main>
</div>
<?php wp_footer(); ?>
</body>
</html>
