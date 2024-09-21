<?php
/**
 * Portfolio single bottom full.
 *
 * @package          Flatsome\Templates
 * @flatsome-version 3.16.0
 */

get_template_part('template-parts/portfolio/portfolio-title', flatsome_option('portfolio_title'));
?>
<div class="portfolio-top">
	<div class="row page-wrapper">
		<div class="large-12 col mb-0 pb-0">
			<div class="portfolio-summary entry-summary">
				<?php get_template_part('template-parts/portfolio/portfolio-summary','full'); ?>
			</div>
		</div>
	</div>

	<div id="portfolio-content" role="main">
		<div class="portfolio-inner">
			<?php get_template_part('template-parts/portfolio/portfolio-content'); ?>
		</div>
	</div>
</div>

<div class="portfolio-bottom">
	<?php get_template_part('template-parts/portfolio/portfolio-next-prev'); ?>
	<?php get_template_part('template-parts/portfolio/portfolio-related'); ?>
</div>
