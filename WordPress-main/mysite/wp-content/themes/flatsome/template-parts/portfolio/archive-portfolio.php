<?php
/**
 * Portfolio archive.
 *
 * @package          Flatsome\Templates
 * @flatsome-version 3.16.0
 */

get_template_part( 'template-parts/portfolio/archive-portfolio-title', get_theme_mod( 'portfolio_archive_title', '' ) );
?>

<div id="content" role="main" class="page-wrapper">
	<?php
	$cat        = false;
	$filter     = get_theme_mod( 'portfolio_archive_filter', 'left' );
	$filter_nav = get_theme_mod( 'portfolio_archive_filter_style', 'line-grow' );

	if ( $filter == 'disabled' || is_tax() ) $filter = 'disabled';

	// Check if category.
	if ( is_tax() ) $cat = get_queried_object()->term_id;

	// Height.
	$height = get_theme_mod( 'portfolio_height', 0 ) ? get_theme_mod( 'portfolio_height', 0 ) : '';

	echo flatsome_apply_shortcode( 'ux_portfolio', array(
		'image_height' => $height,
		'filter'       => $filter,
		'filter_nav'   => $filter_nav,
		'type'         => 'row',
		'cat'          => $cat,
		'orderby'      => get_theme_mod( 'portfolio_archive_orderby', 'menu_order' ),
		'order'        => get_theme_mod( 'portfolio_archive_order', 'desc' ),
		'col_spacing'  => get_theme_mod( 'portfolio_archive_spacing', 'small' ),
		'columns'      => get_theme_mod( 'portfolio_archive_columns', 4 ),
		'columns__md'  => get_theme_mod( 'portfolio_archive_columns_tablet', 3 ),
		'columns__sm'  => get_theme_mod( 'portfolio_archive_columns_mobile', 2 ),
		'depth'        => get_theme_mod( 'portfolio_archive_depth', 0 ),
		'depth_hover'  => get_theme_mod( 'portfolio_archive_depth_hover', 0 ),
		'image_radius' => get_theme_mod( 'portfolio_archive_image_radius', 0 ),
		'image_size'   => get_theme_mod( 'portfolio_archive_image_size', 'medium' ),
	) );
	?>

<?php wp_reset_query(); ?>

</div>
