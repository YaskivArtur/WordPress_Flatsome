<?php
/**
 * Post-entry header bottom.
 *
 * @package          Flatsome\Templates
 * @flatsome-version 3.16.0
 */

?>
<header class="entry-header">
	<?php if ( has_post_thumbnail() ) : ?>
		<?php if ( ! is_single() || ( is_single() && get_theme_mod( 'blog_single_featured_image', 1 ) ) ) : ?>
			<div class="entry-image relative">
				<?php get_template_part( 'template-parts/posts/partials/entry-image', 'default' ); ?>
				<?php if ( get_theme_mod( 'blog_badge', 1 ) ) get_template_part( 'template-parts/posts/partials/entry', 'post-date' ); ?>
			</div>
		<?php endif; ?>
	<?php endif; ?>

	<div class="entry-header-text entry-header-text-bottom text-<?php echo get_theme_mod( 'blog_posts_title_align', 'center' ); ?>">
		<?php get_template_part( 'template-parts/posts/partials/entry', 'title' ); ?>
	</div>
</header>
