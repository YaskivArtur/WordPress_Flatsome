<?php
/**
 * Posts featured.
 *
 * @package          Flatsome\Templates
 * @flatsome-version 3.16.0
 */

$args = array(
	'posts_per_page' => 5,
	'post__in'  => get_option('sticky_posts'),
	'ignore_sticky_posts' => 0
);

$the_query = new WP_Query( $args );

if ( $the_query->have_posts() ) : ?>

<?php
	// Create IDS
	$ids = array();
	while ( $the_query->have_posts() ) : $the_query->the_post();
		array_push($ids, get_the_ID());
	endwhile; // end of the loop.

	// Set ids
	$ids = implode(',', $ids);

	$readmore = __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'flatsome' );
?>
	<?php
	echo flatsome_apply_shortcode( 'blog_posts', array(
		'class'            => 'featured-posts mb',
		'slider_nav_style' => 'circle',
		'style'            => 'shade',
		'show_category'    => 'text',
		'text_align'       => 'center',
		'text_padding'     => '5% 15% 5% 15%',
		'title_size'       => 'xlarge',
		'readmore'         => $readmore,
		'image_height'     => intval( get_theme_mod( 'blog_featured_height', 500 ) ) . 'px',
		'type'             => 'slider-full',
		'depth'            => get_theme_mod( 'blog_posts_depth', 0 ),
		'depth_hover'      => get_theme_mod( 'blog_posts_depth_hover', 0 ),
		'columns'          => '2',
		'image_size'       => get_theme_mod( 'blog_featured_image_size', 'medium' ),
		'show_date'        => get_theme_mod( 'blog_badge', 1 ) ? 'true' : 'false',
		'ids'              => $ids,
	) );
	?>

<?php endif; ?>
