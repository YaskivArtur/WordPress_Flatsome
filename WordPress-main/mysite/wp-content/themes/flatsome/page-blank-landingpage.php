<?php
/**
 * Template name: Page - No Header / No Footer
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

<?php do_action('flatsome_before_page' ); ?>
<?php do_action('flatsome_after_header'); ?>
<div id="wrapper">

	<div id="main" class="<?php flatsome_main_classes();  ?>">

	<?php while ( have_posts() ) : the_post(); ?>
		<?php the_content(); ?>
	<?php endwhile; // end of the loop. ?>

	</div>

</div>
<?php do_action( 'flatsome_after_page' ); ?>

<?php wp_footer(); ?>
</body>
</html>
