<?php
/**
 * Page title with breadcrumbs.
 *
 * @package          Flatsome\Templates
 * @flatsome-version 3.16.0
 */

?>
<div class="page-title <?php flatsome_header_title_classes() ?>">

	<div class="page-title-bg fill"><div class="page-title-bg-overlay"></div></div>

	<div class="page-title-inner container flex-row medium-flex-wrap medium-text-center">
	 	<div class="flex-col flex-grow">
	 		<h1 class="page-title uppercase mb-0"><?php echo get_the_title() ?></h1>
	 	</div>

	 	<div class="flex-col">
	 		<?php flatsome_breadcrumb( 'page-breadcrumbs' ); ?>
	 	</div>

	</div>
</div>
