<?php
/* CUSTOM CSS */
function flatsome_custom_css() {
ob_start();
?>
<style id="custom-css" type="text/css">
:root {
	--primary-color: <?php echo get_theme_mod('color_primary', Flatsome_Default::COLOR_PRIMARY ); ?>;
}
<?php
// Screen sizes
$small_screen = '550px';
$small_screen_max = '549px';
$medium_screen = '850px';
$medium_screen_max = '849px';
$admin_bar = 0;
if(is_admin_bar_showing()){
  $admin_bar = 32;
}

// Layout backgrounds
if ( get_theme_mod( 'body_bg_image' ) ) echo 'html{background-image: url(\'' . get_theme_mod( 'body_bg_image' ) . '\');}';
if ( get_theme_mod( 'body_bg' ) ) echo 'html{background-color:' . get_theme_mod( 'body_bg' ) . '!important;}';

/* Site Width */

if(get_theme_mod('site_width')) {
$site_width = intval(get_theme_mod('site_width')); ?>
.container-width, .full-width .ubermenu-nav, .container, .row{max-width: <?php echo $site_width - 30; ?>px}
.row.row-collapse{max-width: <?php echo $site_width - 60; ?>px}
.row.row-small{max-width: <?php echo $site_width - 37.5; ?>px}
.row.row-large{max-width: <?php echo $site_width; ?>px}
<?php } ?>

<?php if(get_theme_mod('body_layout') !== 'full-width' && get_theme_mod('site_width_boxed')){ ?>
body.framed, body.framed header, body.framed .header-wrapper, body.boxed, body.boxed header, body.boxed .header-wrapper, body.boxed .is-sticky-section{ max-width: <?php echo get_theme_mod('site_width_boxed'); ?>px
}
<?php } ?>


<?php
$content_bg = get_theme_mod('content_bg');
if($content_bg){ ?>
	.sticky-add-to-cart--active, #wrapper,#main,#main.dark{background-color: <?php echo $content_bg; ?>}
<?php } ?>

<?php
$cookie_notice_bg_color = get_theme_mod( 'cookie_notice_bg_color' );
if ( $cookie_notice_bg_color ) { ?>
	.flatsome-cookies {background-color: <?php echo $cookie_notice_bg_color; ?>}
<?php } ?>

<?php
$header_height = get_theme_mod('header_height', 90); ?>
.header-main{height: <?php echo $header_height; ?>px}
#logo img{max-height: <?php echo $header_height; ?>px}

#logo{width:<?php echo get_theme_mod('logo_width', 200); ?>px;}

<?php if(get_theme_mod('logo_padding')) echo '#logo img{padding:'.get_theme_mod('logo_padding').'px 0;}'; ?>
<?php if(get_theme_mod('logo_max_width')) echo '#logo a{max-width:'.get_theme_mod('logo_max_width').'px;}'; ?>
<?php if(get_theme_mod('sticky_logo_padding')) echo '.stuck #logo img{padding:'.get_theme_mod('sticky_logo_padding').'px 0;}'; ?>

<?php if(get_theme_mod('header_bottom_height')){ ?>
.header-bottom{min-height: <?php echo get_theme_mod('header_bottom_height'); ?>px}
<?php } ?>

.header-top{min-height: <?php echo get_theme_mod('header_top_height', 30); ?>px}

<?php $header_height_transparent = get_theme_mod( 'header_height_transparent', 90 ); ?>
.transparent .header-main{height: <?php echo $header_height_transparent; ?>px}
.transparent #logo img{max-height: <?php echo $header_height_transparent; ?>px}

<?php

$height = 0;
$height = $height + $header_height_transparent;
if(flatsome_has_top_bar()['large_or_mobile']) $height = $height + '30';
if(flatsome_has_bottom_bar()['large_or_mobile']) $height = $height + '50';

$mob_height = 0;
?>
.has-transparent + .page-title:first-of-type,
.has-transparent + #main > .page-title,
.has-transparent + #main > div > .page-title,
.has-transparent + #main .page-header-wrapper:first-of-type .page-title{
padding-top: <?php echo $height; ?>px;
}

<?php if(get_theme_mod('header_bg_transparent')){ ?>
.transparent .header-wrapper{background-color: <?php echo get_theme_mod('header_bg_transparent'); ?>!important;}
.transparent .top-divider{display: none;}
<?php } ?>

<?php
$header_height_sticky = get_theme_mod('header_height_sticky', 70); ?>
.header.show-on-scroll,
.stuck .header-main{height:<?php echo $header_height_sticky; ?>px!important}
.stuck #logo img{max-height: <?php echo $header_height_sticky; ?>px!important}

<?php if(get_theme_mod('header_search_width')){ ?>
.search-form{ width: <?php echo get_theme_mod('header_search_width')?>%;}
<?php } ?>

<?php if(get_theme_mod('header_bg')){ ?>
.header-bg-color {background-color: <?php echo get_theme_mod('header_bg', 'rgba(255,255,255,0.9)'); ?>}
<?php } ?>

<?php if(get_theme_mod('header_bg_img')){ ?>
.header-bg-image {background-image: url('<?php echo get_theme_mod('header_bg_img'); ?>');}
.header-bg-image {background-repeat: <?php echo get_theme_mod('header_bg_img_repeat','repeat') ?>;}
<?php } ?>

.header-bottom {background-color: <?php echo get_theme_mod('nav_position_bg','#f1f1f1'); ?>}

<?php if(get_theme_mod('nav_height_top')){ ?>
.top-bar-nav > li > a{line-height: <?php echo get_theme_mod('nav_height_top').'px';?> }
<?php } ?>

<?php if(get_theme_mod('nav_height')){ ?>
.header-main .nav > li > a{line-height: <?php echo get_theme_mod('nav_height').'px';?> }
<?php } ?>

<?php if(get_theme_mod('nav_push')){ ?>
.header-wrapper:not(.stuck) .header-main .header-nav{margin-top: <?php echo get_theme_mod('nav_push').'px';?> }
<?php } ?>

<?php if(get_theme_mod('nav_height_sticky')){ ?>
.stuck .header-main .nav > li > a{line-height: <?php echo get_theme_mod('nav_height_sticky').'px';?> }
<?php } ?>
<?php if(get_theme_mod('nav_height_bottom')){ ?>
.header-bottom-nav > li > a{line-height: <?php echo get_theme_mod('nav_height_bottom').'px';?> }
<?php } ?>

<?php
$header_height_mobile = get_theme_mod('header_height_mobile', 70);
if($header_height_mobile){ ?>
@media (max-width: <?php echo $small_screen_max ?>) {
	.header-main{height: <?php echo $header_height_mobile;?>px}
	#logo img{max-height: <?php echo $header_height_mobile; ?>px}
}
<?php } ?>

<?php if(get_theme_mod('mobile_overlay_bg')){ ?>
	.main-menu-overlay{
	  background-color: <?php echo get_theme_mod('mobile_overlay_bg'); ?>
	}
<?php } ?>

<?php if ( get_theme_mod( 'dropdown_border_enabled', 1 ) && get_theme_mod( 'dropdown_border' ) ) { ?>
.nav-dropdown-has-arrow.nav-dropdown-has-border li.has-dropdown:before{border-bottom-color: <?php echo get_theme_mod('dropdown_border'); ?>;}
.nav .nav-dropdown{  border-color: <?php echo get_theme_mod('dropdown_border'); ?> }
<?php } ?>

<?php if(get_theme_mod('dropdown_radius')){ ?>
.nav-dropdown{border-radius:<?php echo get_theme_mod('dropdown_radius'); ?>}
<?php } ?>

<?php if(get_theme_mod('dropdown_nav_size', 100) !== 100){ ?>
	.nav-dropdown{font-size:<?php echo get_theme_mod('dropdown_nav_size'); ?>%}
<?php } ?>

<?php if(get_theme_mod('dropdown_bg')){ ?>
  .nav-dropdown-has-arrow li.has-dropdown:after{border-bottom-color: <?php echo get_theme_mod('dropdown_bg'); ?>;}
  .nav .nav-dropdown{background-color: <?php echo get_theme_mod('dropdown_bg'); ?>}
<?php } ?>

<?php if(get_theme_mod('topbar_bg')){ ?>
.header-top{background-color:  <?php echo get_theme_mod('topbar_bg'); ?>!important;}
<?php } ?>

<?php if(get_theme_mod('blog_bg_color')){ ?>
.blog-wrapper{background-color: <?php echo get_theme_mod('blog_bg_color'); ?>;}
<?php } ?>

<?php

$color_primary = get_theme_mod('color_primary', Flatsome_Default::COLOR_PRIMARY );
if($color_primary && $color_primary !== Flatsome_Default::COLOR_PRIMARY){ ?>

/* Color */
.accordion-title.active, .has-icon-bg .icon .icon-inner,.logo a, .primary.is-underline, .primary.is-link, .badge-outline .badge-inner, .nav-outline > li.active> a,.nav-outline >li.active > a, .cart-icon strong,[data-color='primary'], .is-outline.primary{color: <?php echo $color_primary; ?>;}

/* Color !important */
[data-text-color="primary"]{color: <?php echo $color_primary; ?>!important;}

/* Background Color */
[data-text-bg="primary"]{background-color: <?php echo $color_primary; ?>;}

/* Background */
.scroll-to-bullets a,.featured-title, .label-new.menu-item > a:after, .nav-pagination > li > .current,.nav-pagination > li > span:hover,.nav-pagination > li > a:hover,.has-hover:hover .badge-outline .badge-inner,button[type="submit"], .button.wc-forward:not(.checkout):not(.checkout-button), .button.submit-button, .button.primary:not(.is-outline),.featured-table .title,.is-outline:hover, .has-icon:hover .icon-label,.nav-dropdown-bold .nav-column li > a:hover, .nav-dropdown.nav-dropdown-bold > li > a:hover, .nav-dropdown-bold.dark .nav-column li > a:hover, .nav-dropdown.nav-dropdown-bold.dark > li > a:hover, .header-vertical-menu__opener ,.is-outline:hover, .tagcloud a:hover,.grid-tools a, input[type='submit']:not(.is-form), .box-badge:hover .box-text, input.button.alt,.nav-box > li > a:hover,.nav-box > li.active > a,.nav-pills > li.active > a ,.current-dropdown .cart-icon strong, .cart-icon:hover strong, .nav-line-bottom > li > a:before, .nav-line-grow > li > a:before, .nav-line > li > a:before,.banner, .header-top, .slider-nav-circle .flickity-prev-next-button:hover svg, .slider-nav-circle .flickity-prev-next-button:hover .arrow, .primary.is-outline:hover, .button.primary:not(.is-outline), input[type='submit'].primary, input[type='submit'].primary, input[type='reset'].button, input[type='button'].primary, .badge-inner{background-color: <?php echo $color_primary; ?>;}
/* Border */
.nav-vertical.nav-tabs > li.active > a,.scroll-to-bullets a.active,.nav-pagination > li > .current,.nav-pagination > li > span:hover,.nav-pagination > li > a:hover,.has-hover:hover .badge-outline .badge-inner,.accordion-title.active,.featured-table,.is-outline:hover, .tagcloud a:hover,blockquote, .has-border, .cart-icon strong:after,.cart-icon strong,.blockUI:before, .processing:before,.loading-spin, .slider-nav-circle .flickity-prev-next-button:hover svg, .slider-nav-circle .flickity-prev-next-button:hover .arrow, .primary.is-outline:hover{border-color: <?php echo get_theme_mod('color_primary', Flatsome_Default::COLOR_PRIMARY ); ?>}
.nav-tabs > li.active > a{border-top-color: <?php echo $color_primary; ?>}
.widget_shopping_cart_content .blockUI.blockOverlay:before { border-left-color: <?php echo $color_primary; ?> }
.woocommerce-checkout-review-order .blockUI.blockOverlay:before { border-left-color: <?php echo $color_primary; ?> }
/* Fill */
.slider .flickity-prev-next-button:hover svg,
.slider .flickity-prev-next-button:hover .arrow{fill: <?php echo $color_primary; ?>;}

/* Focus */
.primary:focus-visible, .submit-button:focus-visible, button[type="submit"]:focus-visible { outline-color: <?php echo $color_primary; ?>!important; }
<?php } ?>

<?php
$color_secondary = get_theme_mod('color_secondary', Flatsome_Default::COLOR_SECONDARY);
if( $color_secondary && $color_secondary !== Flatsome_Default::COLOR_SECONDARY ){ ?>
	/* Background Color */
	[data-icon-label]:after, .secondary.is-underline:hover,.secondary.is-outline:hover,.icon-label,.button.secondary:not(.is-outline),.button.alt:not(.is-outline), .badge-inner.on-sale, .button.checkout, .single_add_to_cart_button, .current .breadcrumb-step{ background-color:  <?php echo $color_secondary; ?>; }
	[data-text-bg="secondary"]{background-color: <?php echo $color_secondary; ?>;}
	/* Color */
	.secondary.is-underline,.secondary.is-link, .secondary.is-outline,.stars a.active, .star-rating:before, .woocommerce-page .star-rating:before,.star-rating span:before, .color-secondary{color: <?php echo $color_secondary ;?>}
	/* Color !important */
	[data-text-color="secondary"]{color: <?php echo $color_secondary; ?>!important;}

	/* Border */
	.secondary.is-outline:hover{
	border-color:  <?php echo $color_secondary; ?>
	}

	/* Focus */
	.secondary:focus-visible, .alt:focus-visible { outline-color: <?php echo $color_secondary; ?>!important; }
<?php } ?>

<?php
$color_success = get_theme_mod( 'color_success' , Flatsome_Default::COLOR_SUCCESS );
if( $color_success && $color_success !== Flatsome_Default::COLOR_SUCCESS ){ ?>
	.success.is-underline:hover,.success.is-outline:hover,
	.success{background-color: <?php echo $color_success;?>}
	.success-color, .success.is-link, .success.is-outline{
		color: <?php echo $color_success;?>;
	}
	.success-border{
		border-color: <?php echo $color_success;?>!important;
	}
	/* Color !important */
	[data-text-color="success"]{color: <?php echo $color_success; ?>!important;}
	/* Background Color */
	[data-text-bg="success"]{background-color: <?php echo $color_success; ?>;}
<?php } ?>

<?php
$alert_color = get_theme_mod('color_alert', Flatsome_Default::COLOR_ALERT);
if($alert_color && $alert_color !== Flatsome_Default::COLOR_ALERT){ ?>
	.alert.is-underline:hover,.alert.is-outline:hover,
	.alert{background-color: <?php echo $alert_color;?>}
	.alert.is-link, .alert.is-outline, .color-alert{
		color: <?php echo $alert_color; ?>;
	}
	/* Color !important */
	[data-text-color="alert"]{color: <?php echo $alert_color; ?>!important;}
	/* Background Color */
	[data-text-bg="alert"]{background-color: <?php echo $alert_color; ?>;}
<?php } ?>

<?php
	if(get_theme_mod('color_texts')){
		echo 'body{color: '.get_theme_mod('color_texts').'}';
	}

	if(get_theme_mod('type_headings_color')){
	  echo 'h1,h2,h3,h4,h5,h6,.heading-font{color: '.get_theme_mod('type_headings_color').';}';
	}
?>

<?php
// Get Type options.
if ( ! get_theme_mod( 'disable_fonts', 0 ) ) :
	$type_nav      = \Kirki_Field_Typography::sanitize( get_theme_mod( 'type_nav', array( 'font-family' => 'Lato', 'variant' => '700' ) ) );
	$type_texts    = \Kirki_Field_Typography::sanitize( get_theme_mod( 'type_texts', array( 'font-family' => 'Lato', 'variant' => 'regular' ) ) );
	$type_headings = \Kirki_Field_Typography::sanitize( get_theme_mod( 'type_headings', array( 'font-family' => 'Lato', 'variant' => '700' ) ) );
	$type_alt      = \Kirki_Field_Typography::sanitize( get_theme_mod( 'type_alt', array( 'font-family' => 'Dancing Script', 'variant' => 'regular' ) ) );

	// Type sizes
	if(get_theme_mod('type_size', 100) !== 100){
	   echo 'body{font-size: '.get_theme_mod('type_size').'%;}';
	}
	if(get_theme_mod('type_size_mobile', 100) !== 100){
	   echo '@media screen and (max-width: ' . $small_screen_max . '){body{font-size: '.get_theme_mod('type_size_mobile').'%;}}';
	}

	// Fix old
	if(!is_array($type_nav)) {
	  $type_nav = array('font-family' => $type_nav, 'variant' => '700');
	}
	if(!is_array($type_texts)) {
	  $type_texts = array('font-family' => $type_texts, 'variant' => 'regular');
	}
	if(!is_array($type_alt)) {
	  $type_alt = array('font-family' => $type_alt, 'variant' => 'regular');
	}
	if(!is_array($type_headings)) {
	  $type_headings = array('font-family' => $type_headings, 'variant' => '700');
	}

	// Type Base
	if(!empty($type_texts['font-family'])) {
		echo 'body{font-family: '. flatsome_parse_font_family( $type_texts['font-family'] ).';}';
	}

	if ( ! empty( $type_texts['font-weight'] ) ) { ?>
	body {
		font-weight: <?php echo intval( $type_texts['font-weight'] ); ?>;
		font-style: <?php echo $type_texts['font-style']; ?>;
	}
	<?php }

	// Type Navigations
	if(!empty($type_nav['font-family'])) {
		echo '.nav > li > a {font-family: '. flatsome_parse_font_family( $type_nav['font-family'] ).';}';
		echo '.mobile-sidebar-levels-2 .nav > li > ul > li > a {font-family: '. flatsome_parse_font_family( $type_nav['font-family'] ).';}';
	}

	if ( ! empty( $type_nav['font-weight'] ) ) { ?>
	.nav > li > a,
	.mobile-sidebar-levels-2 .nav > li > ul > li > a {
		font-weight: <?php echo intval( $type_nav['font-weight'] ); ?>;
		font-style: <?php echo $type_nav['font-style']; ?>;
	}
	<?php }

	// Type Headings
	if(!empty($type_headings['font-family'])) {
	echo 'h1,h2,h3,h4,h5,h6,.heading-font, .off-canvas-center .nav-sidebar.nav-vertical > li > a{font-family: '. flatsome_parse_font_family( $type_headings['font-family'] ).';}';
	}

	if ( ! empty( $type_headings['font-weight'] ) ) { ?>
	h1,h2,h3,h4,h5,h6,.heading-font,.banner h1,.banner h2 {
		font-weight: <?php echo intval( $type_headings['font-weight'] ); ?>;
		font-style: <?php echo $type_headings['font-style']; ?>;
	}
	<?php }

	// Alt Type
	if(!empty($type_alt ['font-family'])) {
	echo '.alt-font{font-family: '. flatsome_parse_font_family( $type_alt['font-family'] ).';}';
	}

	if ( ! empty( $type_alt['font-weight'] ) ) { ?>
	.alt-font {
		font-weight: <?php echo intval( $type_alt['font-weight'] ); ?>!important;
		font-style: <?php echo $type_alt['font-style']; ?>!important;
	}
	<?php } ?>
<?php endif;

// Text Transforms
if(get_theme_mod('text_transform_breadcrumbs')){
	echo '.breadcrumbs{text-transform: '.get_theme_mod('text_transform_breadcrumbs').';}';
}
if(get_theme_mod('text_transform_buttons')){
	echo 'button,.button{text-transform: '.get_theme_mod('text_transform_buttons').';}';
}
if(get_theme_mod('text_transform_navigation')){
	echo '.nav > li > a, .links > li > a{text-transform: '.get_theme_mod('text_transform_navigation').';}';
}
if(get_theme_mod('text_transform_section_titles')){
	echo '.section-title span{text-transform: '.get_theme_mod('text_transform_section_titles').';}';
}
if(get_theme_mod('text_transform_widget_titles')){
	echo 'h3.widget-title,span.widget-title{text-transform: '.get_theme_mod('text_transform_widget_titles').';}';
}

if(get_theme_mod('type_nav_top_color')){ ?>
.header:not(.transparent) .top-bar-nav > li > a {
color: <?php echo get_theme_mod('type_nav_top_color'); ?>;
}
<?php } ?>
<?php if(get_theme_mod('type_nav_top_color_hover')) { ?>
.header:not(.transparent) .top-bar-nav.nav > li > a:hover,
.header:not(.transparent) .top-bar-nav.nav > li.active > a,
.header:not(.transparent) .top-bar-nav.nav > li.current > a,
.header:not(.transparent) .top-bar-nav.nav > li > a.active,
.header:not(.transparent) .top-bar-nav.nav > li > a.current{
	color: <?php echo get_theme_mod('type_nav_top_color_hover'); ?>;
}
.top-bar-nav.nav-line-bottom > li > a:before,
.top-bar-nav.nav-line-grow > li > a:before,
.top-bar-nav.nav-line > li > a:before,
.top-bar-nav.nav-box > li > a:hover,
.top-bar-nav.nav-box > li.active > a,
.top-bar-nav.nav-pills > li > a:hover,
.top-bar-nav.nav-pills > li.active > a{
color:#FFF!important;
background-color: <?php echo get_theme_mod('type_nav_top_color_hover'); ?>;
}
<?php } ?>

<?php if(get_theme_mod('type_nav_color')){ ?>
.header:not(.transparent) .header-nav-main.nav > li > a {
	color: <?php echo get_theme_mod('type_nav_color'); ?>;
}
<?php } ?>
<?php if(get_theme_mod('type_nav_color_hover')) { ?>
.header:not(.transparent) .header-nav-main.nav > li > a:hover,
.header:not(.transparent) .header-nav-main.nav > li.active > a,
.header:not(.transparent) .header-nav-main.nav > li.current > a,
.header:not(.transparent) .header-nav-main.nav > li > a.active,
.header:not(.transparent) .header-nav-main.nav > li > a.current{
	color: <?php echo get_theme_mod('type_nav_color_hover'); ?>;
}
.header-nav-main.nav-line-bottom > li > a:before,
.header-nav-main.nav-line-grow > li > a:before,
.header-nav-main.nav-line > li > a:before,
.header-nav-main.nav-box > li > a:hover,
.header-nav-main.nav-box > li.active > a,
.header-nav-main.nav-pills > li > a:hover,
.header-nav-main.nav-pills > li.active > a{
color:#FFF!important;
background-color: <?php echo get_theme_mod('type_nav_color_hover'); ?>;
}
<?php } ?>

<?php if(get_theme_mod('type_nav_bottom_color')){ ?>
.header:not(.transparent) .header-bottom-nav.nav > li > a{
color: <?php echo get_theme_mod('type_nav_bottom_color'); ?>;
}
<?php } ?>

<?php if(get_theme_mod('type_nav_bottom_color_hover')){ ?>
.header:not(.transparent) .header-bottom-nav.nav > li > a:hover,
.header:not(.transparent) .header-bottom-nav.nav > li.active > a,
.header:not(.transparent) .header-bottom-nav.nav > li.current > a,
.header:not(.transparent) .header-bottom-nav.nav > li > a.active,
.header:not(.transparent) .header-bottom-nav.nav > li > a.current{
color: <?php echo get_theme_mod('type_nav_bottom_color_hover'); ?>;
}
.header-bottom-nav.nav-line-bottom > li > a:before,
.header-bottom-nav.nav-line-grow > li > a:before,
.header-bottom-nav.nav-line > li > a:before,
.header-bottom-nav.nav-box > li > a:hover,
.header-bottom-nav.nav-box > li.active > a,
.header-bottom-nav.nav-pills > li > a:hover,
.header-bottom-nav.nav-pills > li.active > a{
color:#FFF!important;
background-color: <?php echo get_theme_mod('type_nav_bottom_color_hover'); ?>;
}
<?php } ?>

<?php
$color_links = get_theme_mod( 'color_links' );
$color_links_hover = get_theme_mod( 'color_links_hover' );

if( $color_links ){ ?>
a{color: <?php echo $color_links; ?>;}
<?php } ?>

<?php if ( $color_links_hover ){ ?>
a:hover{color: <?php echo $color_links_hover; ?>;}
.tagcloud a:hover{border-color: <?php echo $color_links_hover; ?>;
background-color: <?php echo $color_links_hover; ?>;}
<?php } ?>


<?php if(get_theme_mod('color_widget_links')){ ?>
.widget a{color: <?php echo get_theme_mod('color_widget_links'); ?>;}
.widget a:hover{color: <?php echo get_theme_mod('color_widget_links_hover'); ?>;}
.widget .tagcloud a:hover{border-color: <?php echo get_theme_mod('color_widget_links_hover'); ?>; background-color: <?php echo get_theme_mod('color_widget_links_hover'); ?>;}
<?php } ?>

<?php if(get_theme_mod('color_divider')){ ?>
.is-divider{background-color: <?php echo get_theme_mod('color_divider'); ?>;}
<?php } ?>

<?php if(is_woocommerce_activated() && get_theme_mod('header_shop_bg_color')){ ?>
.shop-page-title.featured-title .title-overlay{
background-color: <?php echo get_theme_mod('header_shop_bg_color') ?>;}
<?php } ?>

<?php if(get_theme_mod('color_checkout')) { ?>
  	.current .breadcrumb-step, [data-icon-label]:after, .button#place_order,.button.checkout,.checkout-button,.single_add_to_cart_button.button{background-color: <?php echo get_theme_mod('color_checkout'); ?>!important }
<?php } ?>

<?php if(get_theme_mod('category_force_image_height')) { ?>
  .has-equal-box-heights .box-image {
    padding-top: <?php echo get_theme_mod('category_image_height', 100) ;?>%;
  }
<?php } ?>

<?php if(get_theme_mod('color_sale')) { ?>
  .badge-inner.on-sale{background-color: <?php echo get_theme_mod('color_sale'); ?>}
<?php } ?>

<?php if ( get_theme_mod( 'color_new_bubble_auto' ) ) { ?>
  .badge-inner.new-bubble-auto{background-color: <?php echo get_theme_mod( 'color_new_bubble_auto' ); ?>}
<?php } ?>

<?php if(get_theme_mod('color_new_bubble')) { ?>
  .badge-inner.new-bubble{background-color: <?php echo get_theme_mod('color_new_bubble'); ?>}
<?php } ?>

<?php if(get_theme_mod('color_review')) { ?>
	.star-rating span:before,.star-rating:before, .woocommerce-page .star-rating:before, .stars a:hover:after, .stars a.active:after{color: <?php echo get_theme_mod('color_review'); ?>}
<?php } ?>

<?php if ( is_woocommerce_activated() && get_theme_mod( 'color_regular_price' ) ) { ?>
.price del, .product_list_widget del, del .woocommerce-Price-amount { color: <?php echo get_theme_mod( 'color_regular_price' ); ?>; }
<?php } ?>

<?php if ( is_woocommerce_activated() && get_theme_mod( 'color_sale_price' ) ) { ?>
ins .woocommerce-Price-amount { color: <?php echo get_theme_mod( 'color_sale_price' ); ?>; }
<?php } ?>

<?php if(is_woocommerce_activated() && get_theme_mod('header_shop_bg_image')){ ?>
.shop-page-title.featured-title .title-bg{background-image: url(<?php echo get_theme_mod('header_shop_bg_image'); ?>);}
<?php } ?>

<?php if(get_theme_mod('button_radius') && get_theme_mod('button_radius')  !== '0px') { ?>
input[type='submit'], input[type="button"], button:not(.icon), .button:not(.icon){border-radius: <?php echo get_theme_mod('button_radius');?>!important}
<?php } ?>

<?php if(get_theme_mod('flatsome_lightbox_bg')) { ?>
  .pswp__bg,.mfp-bg.mfp-ready{background-color: <?php echo get_theme_mod('flatsome_lightbox_bg'); ?>}
<?php } ?>

<?php if(is_woocommerce_activated() && get_theme_mod('header_shop_bg_featured', 1)) {  ?>
<?php if(is_product_category() || is_product_tag()) { ?>
<?php
global $wp_query;
$cat = $wp_query->get_queried_object();
$thumbnail_id = get_term_meta( $cat->term_id, 'thumbnail_id', true );
$image = wp_get_attachment_url( $thumbnail_id );
if($image) echo '.shop-page-title.featured-title .title-bg{background-image: url('.$image.')!important;}';
?>
<?php } ?>
<?php if(is_product()) {
// On product pages
global $post;
$feat_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
echo '.shop-page-title.featured-title .title-bg{ background-image: url('.$feat_image.')!important;}';
} ?>
<?php } ?>

<?php if(is_woocommerce_activated()){
	$image_sizes = wc_get_image_size('thumbnail');
	$image_width = $image_sizes['width'];
?>
@media screen and (min-width: <?php echo $small_screen; ?>){
.products .box-vertical .box-image{
  min-width: <?php echo $image_width;?>px!important;
  width: <?php echo $image_width;?>px!important;
}
}
<?php } ?>


<?php
$header_icons_color = get_theme_mod('header_icons_color');
$header_icons_color_hover = get_theme_mod('header_icons_color_hover');
if(!$header_icons_color_hover) $header_icons_color_hover = $header_icons_color;
if($header_icons_color){ ?>
.header-main .social-icons,
.header-main .cart-icon strong,
.header-main .menu-title,
.header-main .header-button > .button.is-outline,
.header-main .nav > li > a > i:not(.icon-angle-down){
	color: <?php echo $header_icons_color; ?>!important;
}
.header-main .header-button > .button.is-outline,
.header-main .cart-icon strong:after,
.header-main .cart-icon strong{
	border-color: <?php echo $header_icons_color; ?>!important;
}
.header-main .header-button > .button:not(.is-outline){
	background-color: <?php echo $header_icons_color; ?>!important;
}

.header-main .current-dropdown .cart-icon strong,
.header-main .header-button > .button:hover,
.header-main .header-button > .button:hover i,
.header-main .header-button > .button:hover span{
	color:#FFF!important;
}
<?php if($header_icons_color_hover){ ?>
.header-main .menu-title:hover,
.header-main .social-icons a:hover,
.header-main .header-button > .button.is-outline:hover,
.header-main .nav > li > a:hover > i:not(.icon-angle-down){
	color: <?php echo $header_icons_color_hover; ?>!important;
}

.header-main .current-dropdown .cart-icon strong,
.header-main .header-button > .button:hover{
	background-color: <?php echo $header_icons_color_hover; ?>!important;
}
.header-main .current-dropdown .cart-icon strong:after,
.header-main .current-dropdown .cart-icon strong,
.header-main .header-button > .button:hover{
	border-color: <?php echo $header_icons_color_hover; ?>!important;
}
<?php } ?>
<?php } ?>

<?php if(get_theme_mod('footer_1_bg_image')){ ?>
.footer-1{background-image: url('<?php echo get_theme_mod('footer_1_bg_image'); ?>');}
<?php } ?>
<?php if(get_theme_mod('footer_2_bg_image')){ ?>
.footer-2{background-image: url('<?php echo get_theme_mod('footer_2_bg_image'); ?>');}
<?php } ?>
<?php if(get_theme_mod('footer_1_bg_color')){ ?>
.footer-1{background-color: <?php echo get_theme_mod('footer_1_bg_color') ;?>}
<?php } ?>
<?php if(get_theme_mod('footer_2_bg_color')){ ?>
.footer-2{background-color: <?php echo get_theme_mod('footer_2_bg_color') ;?>}
<?php } ?>

<?php if(get_theme_mod('footer_bottom_color')){ ?>
.absolute-footer, html{background-color: <?php echo get_theme_mod('footer_bottom_color') ;?>}
<?php } ?>

<?php if(get_theme_mod('product_header') == 'top') {
echo '.page-title-small + main .product-container > .row{padding-top:0;}';
} ?>

<?php if ( get_theme_mod( 'cart_auto_refresh' ) ) {
	echo 'button[name=\'update_cart\'] { display: none; }';
} ?>

<?php if ( get_theme_mod( 'header_nav_vertical_height', '50' ) != 50 ) { ?>
.header-vertical-menu__opener{height: <?php echo get_theme_mod('header_nav_vertical_height');?>px}
<?php } ?>

<?php if ( get_theme_mod( 'header_nav_vertical_width', '250' ) != 250 ) { ?>
.header-vertical-menu__opener {width: <?php echo get_theme_mod('header_nav_vertical_width');?>px}
<?php } ?>

<?php if ( get_theme_mod( 'header_nav_vertical_fly_out_width', '250' ) != 250 ) { ?>
.header-vertical-menu__fly-out {width: <?php echo get_theme_mod('header_nav_vertical_fly_out_width');?>px}
<?php } ?>

<?php if ( get_theme_mod( 'header_nav_vertical_color' ) ) { ?>
	.header-vertical-menu__opener{color: <?php echo get_theme_mod('header_nav_vertical_color');?>}
<?php } ?>

<?php if ( get_theme_mod( 'header_nav_vertical_bg_color' ) ) { ?>
	.header-vertical-menu__opener{background-color: <?php echo get_theme_mod('header_nav_vertical_bg_color');?>}
<?php } ?>

<?php if ( get_theme_mod( 'header_nav_vertical_fly_out_bg_color' ) ) { ?>
.header-vertical-menu__fly-out{background-color: <?php echo get_theme_mod('header_nav_vertical_fly_out_bg_color');?>}
<?php } ?>

<?php if ( get_theme_mod( 'header_nav_vertical_fly_out_nav_divider', 1 ) ) { ?>
.nav-vertical-fly-out > li + li {border-top-width: 1px; border-top-style: solid;}
<?php } ?>

<?php if ( get_theme_mod( 'header_nav_vertical_fly_out_nav_color' ) ) { ?>
.header-vertical-menu__fly-out .nav-vertical-fly-out > li.menu-item > a {
	color: <?php echo get_theme_mod( 'header_nav_vertical_fly_out_nav_color' ); ?>;
}
<?php } ?>

<?php if ( get_theme_mod( 'header_nav_vertical_fly_out_nav_color_hover' ) ) { ?>
.header-vertical-menu__fly-out .nav-vertical-fly-out > li.menu-item > a:hover,
.header-vertical-menu__fly-out .nav-vertical-fly-out > li.menu-item.current-dropdown > a {
	color: <?php echo get_theme_mod( 'header_nav_vertical_fly_out_nav_color_hover' ); ?>;
}
<?php } ?>

<?php if ( get_theme_mod( 'header_nav_vertical_fly_out_nav_bg_color_hover' ) ) { ?>
.header-vertical-menu__fly-out .nav-vertical-fly-out > li.menu-item > a:hover,
.header-vertical-menu__fly-out .nav-vertical-fly-out > li.menu-item.current-dropdown > a {
	background-color: <?php echo get_theme_mod( 'header_nav_vertical_fly_out_nav_bg_color_hover' ); ?>;
}
<?php } ?>

<?php if ( get_theme_mod( 'header_nav_vertical_fly_out_nav_height' ) ) { ?>
.header-vertical-menu__fly-out .nav-vertical-fly-out > li.menu-item > a {
	height: <?php echo intval( get_theme_mod( 'header_nav_vertical_fly_out_nav_height' ) ); ?>px;
}
<?php } ?>

<?php if(get_theme_mod('html_custom_css')){
echo '/* Custom CSS */';
echo get_theme_mod('html_custom_css');
} ?>

<?php if(get_theme_mod('html_custom_css_tablet')){
echo '/* Custom CSS Tablet */';
echo '@media (max-width: ' . $medium_screen_max . '){';
echo get_theme_mod('html_custom_css_tablet');
echo '}';
} ?>

<?php if(get_theme_mod('html_custom_css_mobile')){
echo '/* Custom CSS Mobile */';
echo '@media (max-width: ' . $small_screen_max . '){';
echo get_theme_mod('html_custom_css_mobile');
echo '}';
} ?>

<?php if(is_admin_bar_showing()){ ?>
@media (max-width: <?php echo $medium_screen_max; ?>){
	#wpadminbar{display: none!important;}
	html{margin-top: 0!important}
}
@media (min-width: <?php echo $medium_screen; ?>){
	.mfp-content,
	.stuck,
	button.mfp-close{
	top: 32px!important;
	}
	.is-full-height{height: calc(100vh - 32px)!important;}
}
<?php } ?>

<?php if(is_admin_bar_showing() || is_customize_preview()) { ?>
.xdebug-var-dump{
	z-index: 999999;
}
.shortcode-error{
border: 2px dashed #000;
padding: 20px;
color:#fff;
font-size:16px;
background-color: #71cedf;
}
.custom-product-page .shortcode-error {
	padding: 15% 10%;
	text-align: center;
}
<?php } ?>

<?php  if ( current_user_can( 'edit_pages' ) && is_admin_bar_showing() ) { ?>
	.edit-block-wrapper{
		position: relative;
	}
	.edit-block-button{
		font-size: 12px!important;
		background-color: #555!important;
		margin: 6px 2px 3px 0px!important;
		border-radius: 4px!important;
	}
	.edit-block-button-builder{
		background-color: #00a0d2!important;
	}
<?php } ?>

.label-new.menu-item > a:after{content:"<?php _e('New','flatsome'); ?>";}
.label-hot.menu-item > a:after{content:"<?php _e('Hot','flatsome'); ?>";}
.label-sale.menu-item > a:after{content:"<?php _e('Sale','flatsome'); ?>";}
.label-popular.menu-item > a:after{content:"<?php _e('Popular','flatsome'); ?>";}

</style>

<?php
$buffer = ob_get_clean();
echo flatsome_minify_css($buffer);
}
add_action( 'wp_head', 'flatsome_custom_css', 100 );
