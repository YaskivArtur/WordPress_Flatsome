<?php
/**
 * Flatsome Engine Room.
 * This is where all Theme Functions runs.
 *
 * @package flatsome
 */

if ( ! defined( 'UXTHEMES_API_URL' ) ) {
  define( 'UXTHEMES_API_URL', 'https://api.uxthemes.com' );
}

if ( ! defined( 'UXTHEMES_ACCOUNT_URL' ) ) {
  define( 'UXTHEMES_ACCOUNT_URL', 'https://account.uxthemes.com' );
}

/**
 * Require Classes
 */
require get_template_directory() . '/inc/classes/class-flatsome-default.php';
require get_template_directory() . '/inc/classes/class-flatsome-options.php';
require get_template_directory() . '/inc/classes/class-flatsome-upgrade.php';
require get_template_directory() . '/inc/classes/class-flatsome-base-registration.php';
require get_template_directory() . '/inc/classes/class-flatsome-wupdates-registration.php';
require get_template_directory() . '/inc/classes/class-flatsome-registration.php';
require get_template_directory() . '/inc/classes/class-flatsome-envato.php';
require get_template_directory() . '/inc/classes/class-flatsome-envato-admin.php';
require get_template_directory() . '/inc/classes/class-flatsome-envato-registration.php';
require get_template_directory() . '/inc/classes/class-flatsome-instagram.php';
require get_template_directory() . '/inc/classes/class-uxthemes-api.php';

/**
 * Setup.
 * Enqueue styles, register widget regions, etc.
 */
require get_template_directory() . '/inc/functions/function-conditionals.php';
require get_template_directory() . '/inc/functions/function-global.php';
require get_template_directory() . '/inc/functions/function-register.php';
require get_template_directory() . '/inc/functions/function-upgrade.php';
require get_template_directory() . '/inc/functions/function-update.php';
require get_template_directory() . '/inc/functions/function-defaults.php';
require get_template_directory() . '/inc/functions/function-setup.php';
require get_template_directory() . '/inc/functions/function-theme-mods.php';
require get_template_directory() . '/inc/functions/function-plugins.php';
require get_template_directory() . '/inc/functions/function-custom-css.php';
require get_template_directory() . '/inc/functions/function-maintenance.php';
require get_template_directory() . '/inc/functions/function-fallbacks.php';
require get_template_directory() . '/inc/functions/function-site-health.php';
require get_template_directory() . '/inc/functions/fl-template-functions.php';
require get_template_directory() . '/inc/functions/function-fonts.php';

// Get Presets for Theme Options and Demos
require get_template_directory() . '/inc/functions/function-presets.php';

/**
 * Helper functions
 */
require get_template_directory() . '/inc/helpers/helpers-frontend.php';
require get_template_directory() . '/inc/helpers/helpers-shortcode.php';
require get_template_directory() . '/inc/helpers/helpers-grid.php';
require get_template_directory() . '/inc/helpers/helpers-icons.php';
if ( is_woocommerce_activated() ) { require get_template_directory() . '/inc/helpers/helpers-woocommerce.php'; }

/**
 * Structure.
 * Template functions used throughout the theme.
 */
//if(!is_admin()){
  require get_template_directory() . '/inc/structure/structure-footer.php';
  require get_template_directory() . '/inc/structure/structure-header.php';
  require get_template_directory() . '/inc/structure/structure-pages.php';
  require get_template_directory() . '/inc/structure/structure-posts.php';
  require get_template_directory() . '/inc/structure/structure-sidebars.php';

  if(is_portfolio_activated()){
      require get_template_directory() . '/inc/structure/structure-portfolio.php';
  }
//}

if(is_admin()){
  require get_template_directory() . '/inc/structure/structure-admin.php';
  require get_template_directory() . '/inc/admin/gutenberg/class-gutenberg.php';
}

/**
 * Flatsome Shortcodes.
 */

require get_template_directory() . '/inc/shortcodes/row.php';
require get_template_directory() . '/inc/shortcodes/text_box.php';
require get_template_directory() . '/inc/shortcodes/sections.php';
require get_template_directory() . '/inc/shortcodes/ux_slider.php';
require get_template_directory() . '/inc/shortcodes/ux_banner.php';
require get_template_directory() . '/inc/shortcodes/ux_banner_grid.php';
require get_template_directory() . '/inc/shortcodes/accordion.php';
require get_template_directory() . '/inc/shortcodes/tabs.php';
require get_template_directory() . '/inc/shortcodes/gap.php';
require get_template_directory() . '/inc/shortcodes/featured_box.php';
require get_template_directory() . '/inc/shortcodes/ux_sidebar.php';
require get_template_directory() . '/inc/shortcodes/buttons.php';
require get_template_directory() . '/inc/shortcodes/share.php';
require get_template_directory() . '/inc/shortcodes/follow.php';
require get_template_directory() . '/inc/shortcodes/elements.php';
require get_template_directory() . '/inc/shortcodes/titles_dividers.php';
require get_template_directory() . '/inc/shortcodes/lightbox.php';
require get_template_directory() . '/inc/shortcodes/blog_posts.php';
require get_template_directory() . '/inc/shortcodes/google_maps.php';
require get_template_directory() . '/inc/shortcodes/testimonials.php';
require get_template_directory() . '/inc/shortcodes/team_members.php';
require get_template_directory() . '/inc/shortcodes/messages.php';
require get_template_directory() . '/inc/shortcodes/search.php';
require get_template_directory() . '/inc/shortcodes/ux_html.php';
require get_template_directory() . '/inc/shortcodes/ux_logo.php';
require get_template_directory() . '/inc/shortcodes/ux_image.php';
require get_template_directory() . '/inc/shortcodes/ux_image_box.php';
require get_template_directory() . '/inc/shortcodes/ux_menu_link.php';
require get_template_directory() . '/inc/shortcodes/ux_menu_title.php';
require get_template_directory() . '/inc/shortcodes/ux_menu.php';
require get_template_directory() . '/inc/shortcodes/price_table.php';
require get_template_directory() . '/inc/shortcodes/scroll_to.php';
require get_template_directory() . '/inc/shortcodes/ux_pages.php';
require get_template_directory() . '/inc/shortcodes/ux_gallery.php';
require get_template_directory() . '/inc/shortcodes/ux_hotspot.php';
require get_template_directory() . '/inc/shortcodes/page_header.php';
require get_template_directory() . '/inc/shortcodes/ux_instagram_feed.php';
require get_template_directory() . '/inc/shortcodes/ux_countdown/ux-countdown.php';
require get_template_directory() . '/inc/shortcodes/ux_video.php';
require get_template_directory() . '/inc/shortcodes/ux_nav.php';
require get_template_directory() . '/inc/shortcodes/ux_payment_icons.php';
require get_template_directory() . '/inc/shortcodes/ux_stack.php';
require get_template_directory() . '/inc/shortcodes/ux_text.php';
require get_template_directory() . '/inc/shortcodes/ux_lottie.php';

if(is_portfolio_activated()){
  require get_template_directory() . '/inc/shortcodes/portfolio.php';
}

if (is_woocommerce_activated()) {
  require get_template_directory() . '/inc/shortcodes/ux_products.php';
  require get_template_directory() . '/inc/shortcodes/ux_products_list.php';
  require get_template_directory() . '/inc/shortcodes/product_flip.php';
  require get_template_directory() . '/inc/shortcodes/product_categories.php';
  require get_template_directory() . '/inc/shortcodes/custom-product.php';
}

/**
 * Flatsome Blocks
 */
if ( function_exists( 'register_block_type' ) ) {
  require get_template_directory() . '/inc/blocks/uxbuilder/index.php';
}

/**
 * Load WooCommerce Custom Fields
 */
if (is_woocommerce_activated()) {
  require get_template_directory() . '/inc/classes/class-wc-product-data-fields.php';
  require get_template_directory() . '/inc/woocommerce/structure-wc-product-page-fields.php';
}

/**
 * Load WooCommerce functions
 */
if ( is_woocommerce_activated() ) {
  require get_template_directory() . '/inc/woocommerce/structure-wc-conditionals.php';
  require get_template_directory() . '/inc/woocommerce/structure-wc-global.php';
  require get_template_directory() . '/inc/woocommerce/structure-wc-category-page.php';
  require get_template_directory() . '/inc/woocommerce/structure-wc-category-page-header.php';
  require get_template_directory() . '/inc/woocommerce/structure-wc-product-box.php';
  require get_template_directory() . '/inc/woocommerce/structure-wc-helpers.php';
  require get_template_directory() . '/inc/woocommerce/structure-wc-checkout.php';
  require get_template_directory() . '/inc/woocommerce/structure-wc-cart.php';
  require get_template_directory() . '/inc/woocommerce/structure-wc-product-page.php';
  require get_template_directory() . '/inc/woocommerce/structure-wc-product-page-header.php';
  require get_template_directory() . '/inc/woocommerce/structure-wc-single-product-custom.php';
  if ( get_theme_mod( 'catalog_mode' ) ) require get_template_directory() . '/inc/woocommerce/structure-wc-catalog-mode.php';
}


/**
 * Flatsome Theme Widgets
 */
require get_template_directory() . '/inc/widgets/widget-recent-posts.php';
require get_template_directory() . '/inc/widgets/widget-blocks.php';
if (is_woocommerce_activated() ) { require get_template_directory() . '/inc/widgets/widget-upsell.php'; }


/**
 * Custom Theme Post Types
 */
require get_template_directory() . '/inc/post-types/post-type-ux-blocks.php';

if(is_portfolio_activated()){
  require get_template_directory() . '/inc/post-types/post-type-ux-portfolio.php';
}


/**
 * Theme Integrations
 */

require get_template_directory() . '/inc/integrations/integrations.php';

/**
 * Theme Extenstions
 */
require get_template_directory() . '/inc/extensions/extensions.php';

/**
 * Include Kirki.
 *
 * options-type.php - Needs to be reachable on the frontend to generate local Font CSS
 * on the kirki-inline-styles <style> element.
 */
require get_template_directory() . '/inc/admin/kirki/kirki.php';
require get_template_directory() . '/inc/admin/kirki-config.php';
require get_template_directory() . '/inc/admin/options/styles/options-type.php';

/**
 * Theme Admin
 */
if(current_user_can( 'manage_options')){
  require get_template_directory() . '/inc/admin/admin-init.php';
}

/**
 * UX Builder
 */
require get_template_directory() . '/inc/builder/builder.php';
