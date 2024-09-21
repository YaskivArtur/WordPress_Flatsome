<?php
/**
 * Category title.
 *
 * @package          Flatsome/WooCommerce/Templates
 * @flatsome-version 3.16.0
 */

?>
<div class="shop-page-title category-page-title page-title <?php flatsome_header_title_classes() ?>">
	<div class="page-title-inner flex-row  medium-flex-wrap container">
	  <div class="flex-col flex-grow medium-text-center">
	  	<?php do_action('flatsome_category_title') ;?>
	  </div>
	  <div class="flex-col medium-text-center">
	  	<?php do_action('flatsome_category_title_alt') ;?>
	  </div>
	</div>
</div>
