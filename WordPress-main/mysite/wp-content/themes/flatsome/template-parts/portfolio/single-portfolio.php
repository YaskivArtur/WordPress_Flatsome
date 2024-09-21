<?php
/**
 * Portfolio single.
 *
 * @package          Flatsome\Templates
 * @flatsome-version 3.16.0
 */

get_template_part('template-parts/portfolio/portfolio-title', flatsome_option('portfolio_title'));
?>
<div class="portfolio-top">
	<div class="page-wrapper row">
  	<div class="large-3 col col-divided">
  		<div class="portfolio-summary entry-summary sticky-sidebar">
  				<?php get_template_part('template-parts/portfolio/portfolio-summary'); ?>
  		</div>
  	</div>

  	<div id="portfolio-content" class="large-9 col"  role="main">
  		<div class="portfolio-inner">
  			<?php get_template_part('template-parts/portfolio/portfolio-content'); ?>
  		</div>
  	</div>
	</div>
</div>

<div class="portfolio-bottom">
	<?php get_template_part('template-parts/portfolio/portfolio-next-prev'); ?>
	<?php get_template_part('template-parts/portfolio/portfolio-related'); ?>
</div>
