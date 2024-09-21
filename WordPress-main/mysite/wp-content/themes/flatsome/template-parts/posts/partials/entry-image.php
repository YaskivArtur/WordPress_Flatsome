<?php
/**
 * Post-entry image.
 *
 * @package          Flatsome\Templates
 * @flatsome-version 3.16.0
 */

?>
<a href="<?php the_permalink();?>">
    <?php the_post_thumbnail('large'); ?>
</a>
