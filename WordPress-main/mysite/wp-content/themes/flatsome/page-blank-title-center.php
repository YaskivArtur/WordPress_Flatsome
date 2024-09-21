<?php
/**
 * Template name: Page - Container - Center Title
 *
 * @package          Flatsome\Templates
 * @flatsome-version 3.16.0
 */

get_header();
?>

<?php do_action( 'flatsome_before_page' ); ?>

<div class="row page-wrapper">
<div id="content" class="large-12 col" role="main">

		<?php while ( have_posts() ) : the_post(); ?>
					<header class="entry-header text-center">
						<h1 class="entry-title"><?php the_title(); ?></h1>
						<div class="is-divider medium"></div>
					</header>

					<div class="entry-content">
						<?php the_content(); ?>

						<?php if ( comments_open() || '0' != get_comments_number() ){
							comments_template(); } ?>
					</div>


		<?php endwhile; // end of the loop. ?>


</div>
</div>

<?php do_action( 'flatsome_after_page' ); ?>

<?php get_footer(); ?>
