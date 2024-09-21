<?php
/**
 * Portfolio content.
 *
 * @package          Flatsome\Templates
 * @flatsome-version 3.16.0
 */

while ( have_posts() ) : the_post(); ?>
		<?php if(get_the_content()) {the_content();} else {
			the_post_thumbnail('original');
		}; ?>
<?php endwhile; wp_reset_query(); // end of the loop. ?>
