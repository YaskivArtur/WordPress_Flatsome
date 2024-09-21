<?php
/**
 * Checkout focused layout.
 *
 * @package          Flatsome/WooCommerce/Templates
 * @flatsome-version 3.16.0
 */

do_action( 'get_header', null, array() );
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="<?php flatsome_html_classes(); ?>">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>"/>
	<link rel="profile" href="http://gmpg.org/xfn/11"/>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>"/>

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php do_action( 'flatsome_after_body_open' ); ?>
<?php wp_body_open(); ?>

<div id="main-content" class="site-main" style="max-width:1000px; margin:60px auto 60px auto;">

	<div id="main" class="page-wrapper box-shadow page-checkout <?php flatsome_main_classes(); ?>" style="padding:15px 30px 15px;">

		<div class="focused-checkout-logo text-center" style="padding-top: 30px; padding-bottom: 30px;">
			<div id="logo" class="logo"><?php get_template_part( 'template-parts/header/partials/element', 'logo' ); ?></div>
		</div>

		<div class="container">
			<div class="top-divider full-width"></div>
		</div>

		<div class="focused-checkout-header pb">
			<?php wc_get_template( 'checkout/header.php' ); ?>
		</div>

		<div class="row">
			<div id="content" class="large-12 col" role="main">

				<?php while ( have_posts() ) : the_post(); ?>

					<?php wc_print_notices(); ?>
					<?php the_content(); ?>

				<?php endwhile; // end of the loop. ?>

			</div>
		</div>

	</div>

	<div class="focused-checkout-footer">
		<?php get_template_part( 'template-parts/footer/footer', 'absolute' ); ?>
	</div>

</div>

</div>

<?php wp_footer(); ?>

</body>
</html>
