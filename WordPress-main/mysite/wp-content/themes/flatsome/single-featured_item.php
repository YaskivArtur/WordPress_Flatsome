<?php
/**
 * The template for a single featured item.
 *
 * @package          Flatsome\Templates
 * @flatsome-version 3.16.0
 */

get_header(); ?>

<div class="portfolio-page-wrapper portfolio-single-page">
	<?php get_template_part('template-parts/portfolio/single-portfolio', flatsome_option('portfolio_layout')); ?>
</div>

<?php get_footer(); ?>
