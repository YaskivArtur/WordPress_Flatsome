<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package          Flatsome\Templates
 * @flatsome-version 3.16.0
 */

get_header(); ?>
<?php do_action( 'flatsome_before_404' ); ?>
<?php
if ( get_theme_mod( '404_block' ) ) :
	echo do_shortcode( '[block id="' . get_theme_mod( '404_block' ) . '"]' );
else :
?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main container pt" role="main">
			<section class="error-404 not-found mt mb">
				<div class="row">
					<div class="col medium-3"><span class="header-font" style="font-size: 6em; font-weight: bold; opacity: .3">404</span></div>
					<div class="col medium-9">
						<header class="page-title">
							<h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'flatsome' ); ?></h1>
						</header>
						<div class="page-content">
							<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'flatsome' ); ?></p>
							<?php get_search_form(); ?>
						</div>
					</div>
				</div>
			</section>
		</main>
	</div>
<?php endif; ?>
<?php do_action( 'flatsome_after_404' ); ?>
<?php get_footer(); ?>
