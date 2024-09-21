<?php
/**
 * Page title centered.
 *
 * @package          Flatsome\Templates
 * @flatsome-version 3.16.0
 */

?>
<div class="page-title <?php flatsome_header_title_classes() ?>">

	<div class="page-title-bg fill"><div class="page-title-bg-overlay"></div></div>

	<div class="page-title-inner container flex-row">
	 	<div class="flex-col flex-grow text-center">
	 		<h1 class="page-title uppercase"><?php echo get_the_title() ?></h1>
	 		<div class="is-divider"></div>
	 	</div>
	</div>
</div>
