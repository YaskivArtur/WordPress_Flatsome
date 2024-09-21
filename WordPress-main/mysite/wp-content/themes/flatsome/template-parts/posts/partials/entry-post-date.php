<?php
/**
 * Post-entry post date.
 *
 * @package          Flatsome\Templates
 * @flatsome-version 3.16.0
 */

?>
<div class="badge absolute top post-date badge-<?php echo flatsome_option('blog_badge_style'); ?>">
	<div class="badge-inner">
		<span class="post-date-day"><?php echo get_the_time('d', get_the_ID()); ?></span><br>
		<span class="post-date-month is-small"><?php echo get_the_time('M', get_the_ID()); ?></span>
	</div>
</div>
