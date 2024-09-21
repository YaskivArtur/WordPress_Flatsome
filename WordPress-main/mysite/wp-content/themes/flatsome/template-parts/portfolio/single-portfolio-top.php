<?php
/**
 * Portfolio single top.
 *
 * @package          Flatsome\Templates
 * @flatsome-version 3.16.0
 */

get_template_part('template-parts/portfolio/portfolio-title', flatsome_option('portfolio_title'));
?>
<div class="portfolio-top">
	<div class="row page-wrapper">

	<div id="portfolio-content" class="large-12 col"  role="main">
		<div class="portfolio-inner pb">
			<?php get_template_part('template-parts/portfolio/portfolio-content'); ?>
		</div>

		<div class="portfolio-summary entry-summary">
			<?php get_template_part('template-parts/portfolio/portfolio-summary','full'); ?>
		</div>
	</div>

	</div>
</div>

<div class="portfolio-bottom">
	<?php get_template_part('template-parts/portfolio/portfolio-next-prev'); ?>
	<?php get_template_part('template-parts/portfolio/portfolio-related'); ?>
</div>
