<?php
/**
 * Category title featured centered.
 *
 * @package          Flatsome/WooCommerce/Templates
 * @flatsome-version 3.16.0
 */

?>
<div class="shop-page-title category-page-title dark featured-title page-title <?php flatsome_header_title_classes() ?>">

	<div class="page-title-bg fill">
		<div class="title-bg fill bg-fill" data-parallax-fade="true" data-parallax="-2" data-parallax-background data-parallax-container=".page-title"></div>
		<div class="title-overlay fill"></div>
	</div>

	<div class="page-title-inner flex-row container medium-flex-wrap flex-has-center">
	  <div class="flex-col">
	  	&nbsp;
	  </div>
	  <div class="flex-col flex-center text-center">
	  	  <?php do_action('flatsome_category_title') ;?>
	  </div>
	  <div class="flex-col flex-right text-right medium-text-center form-flat">
	  	  <?php do_action('flatsome_category_title_alt') ;?>
	  </div>
	</div>
</div>
