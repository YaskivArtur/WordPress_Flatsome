<?php
/**
 * The overlay menu.
 *
 * @package          Flatsome\Templates
 * @flatsome-version 3.16.0
 */

$flatsome_mobile_overlay         = get_theme_mod( 'mobile_overlay' );
$flatsome_mobile_sidebar_classes = array( 'mobile-sidebar', 'no-scrollbar', 'mfp-hide' );
$flatsome_nav_classes            = array( 'nav', 'nav-sidebar', 'nav-vertical', 'nav-uppercase' );
$flatsome_levels                 = 0;

if ( 'center' == $flatsome_mobile_overlay ) {
	$flatsome_nav_classes[] = 'nav-anim';
}

if (
	'center' != $flatsome_mobile_overlay &&
	'slide' == get_theme_mod( 'mobile_submenu_effect' )
) {
	$flatsome_levels = (int) get_theme_mod( 'mobile_submenu_levels', '1' );

	$flatsome_mobile_sidebar_classes[] = 'mobile-sidebar-slide';
	$flatsome_nav_classes[]            = 'nav-slide';

	for ( $level = 1; $level <= $flatsome_levels; $level++ ) {
		$flatsome_mobile_sidebar_classes[] = "mobile-sidebar-levels-{$level}";
	}
}
?>
<div id="main-menu" class="<?php echo esc_attr( implode( ' ', $flatsome_mobile_sidebar_classes ) ); ?>"<?php echo $flatsome_levels ? ' data-levels="' . esc_attr( $flatsome_levels ) . '"' : ''; ?>>

	<?php do_action( 'flatsome_before_sidebar_menu' ); ?>

	<div class="sidebar-menu no-scrollbar <?php if ( $flatsome_mobile_overlay == 'center') echo 'text-center'; ?>">

		<?php do_action( 'flatsome_before_sidebar_menu_elements' ); ?>

		<?php if ( get_theme_mod( 'mobile_sidebar_tabs' ) ) : ?>

			<ul class="sidebar-menu-tabs flex nav nav-line-bottom nav-uppercase">
				<li class="sidebar-menu-tabs__tab active">
					<a class="sidebar-menu-tabs__tab-link" href="#">
						<span class="sidebar-menu-tabs__tab-text"><?php echo get_theme_mod( 'mobile_sidebar_tab_text' ) ? esc_html( get_theme_mod( 'mobile_sidebar_tab_text' ) ) : esc_html__( 'Menu', 'flatsome' ); ?></span>
					</a>
				</li>
				<li class="sidebar-menu-tabs__tab">
					<a class="sidebar-menu-tabs__tab-link" href="#">
						<span class="sidebar-menu-tabs__tab-text"><?php echo get_theme_mod( 'mobile_sidebar_tab_2_text' ) ? esc_html( get_theme_mod( 'mobile_sidebar_tab_2_text' ) ) : esc_html__( 'Categories', 'flatsome' ); ?></span>
					</a>
				</li>
			</ul>

			<ul class="<?php echo esc_attr( implode( ' ', $flatsome_nav_classes ) ); ?> hidden" data-tab="2">
				<?php flatsome_header_elements( 'mobile_sidebar_tab_2', 'sidebar' ); ?>
			</ul>
			<ul class="<?php echo esc_attr( implode( ' ', $flatsome_nav_classes ) ); ?>" data-tab="1">
				<?php flatsome_header_elements( 'mobile_sidebar', 'sidebar' ); ?>
			</ul>
		<?php else : ?>
			<ul class="<?php echo esc_attr( implode( ' ', $flatsome_nav_classes ) ); ?>" data-tab="1">
				<?php flatsome_header_elements( 'mobile_sidebar', 'sidebar' ); ?>
			</ul>
		<?php endif; ?>

		<?php do_action( 'flatsome_after_sidebar_menu_elements' ); ?>

	</div>

	<?php do_action( 'flatsome_after_sidebar_menu' ); ?>

</div>
