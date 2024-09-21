<?php
/**
 * Header product featured.
 *
 * @package          Flatsome/WooCommerce/Templates
 * @flatsome-version 3.16.0
 */

?>
<div class="shop-page-title product-page-title dark  page-title featured-title <?php flatsome_header_title_classes() ?>">

	<div class="page-title-bg fill">
		<div class="title-bg fill bg-fill" data-parallax-fade="true" data-parallax="-2" data-parallax-background data-parallax-container=".page-title"></div>
		<div class="title-overlay fill"></div>
	</div>

	<div class="page-title-inner flex-row  medium-flex-wrap container">
	  <div class="flex-col flex-grow medium-text-center">
	  		<?php do_action('flatsome_product_title') ;?>
	  </div>

	   <div class="flex-col nav-right medium-text-center">
		   	<?php do_action('flatsome_product_title_tools') ;?>
	   </div>
	</div>
</div>
