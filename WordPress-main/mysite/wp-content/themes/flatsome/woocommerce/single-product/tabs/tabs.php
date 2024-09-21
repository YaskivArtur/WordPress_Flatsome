<?php
/**
 * Single Product tabs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see              https://docs.woocommerce.com/document/template-structure/
 * @package          WooCommerce/Templates
 * @version          3.8.0
 * @flatsome-version 3.16.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$tabs_style = get_theme_mod( 'product_display', 'tabs' );

// Get sections instead of tabs if set.
if ( $tabs_style == 'sections' ) {
	wc_get_template_part( 'single-product/tabs/sections' );

	return;
}

// Get accordion instead of tabs if set.
if ( $tabs_style == 'accordian' || $tabs_style == 'accordian-collapsed' ) {
	wc_get_template_part( 'single-product/tabs/accordian' );

	return;
}

/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 *
 * @see woocommerce_default_product_tabs()
 */
$product_tabs = apply_filters( 'woocommerce_product_tabs', array() );

$tab_count   = 0;
$panel_count = 0;

if ( ! empty( $product_tabs ) ) : ?>

	<div class="woocommerce-tabs wc-tabs-wrapper container tabbed-content">
		<ul class="tabs wc-tabs product-tabs small-nav-collapse <?php flatsome_product_tabs_classes(); ?>" role="tablist">
			<?php foreach ( $product_tabs as $key => $product_tab ) : ?>
				<li class="<?php echo esc_attr( $key ); ?>_tab <?php if ( $tab_count == 0 ) echo 'active'; ?>" id="tab-title-<?php echo esc_attr( $key ); ?>" role="presentation">
					<a href="#tab-<?php echo esc_attr( $key ); ?>" role="tab" aria-selected="<?php echo $tab_count == 0 ? 'true' : 'false'; ?>" aria-controls="tab-<?php echo esc_attr( $key ); ?>"<?php echo $tab_count != 0 ? ' tabindex="-1"' : ''; ?>>
						<?php echo wp_kses_post( apply_filters( 'woocommerce_product_' . $key . '_tab_title', $product_tab['title'], $key ) ); ?>
					</a>
				</li>
				<?php $tab_count++; ?>
			<?php endforeach; ?>
		</ul>
		<div class="tab-panels">
			<?php foreach ( $product_tabs as $key => $product_tab ) : ?>
				<div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--<?php echo esc_attr( $key ); ?> panel entry-content <?php if ( $panel_count == 0 ) echo 'active'; ?>" id="tab-<?php echo esc_attr( $key ); ?>" role="tabpanel" aria-labelledby="tab-title-<?php echo esc_attr( $key ); ?>">
					<?php if ( $key == 'description' && ux_builder_is_active() ) echo flatsome_dummy_text(); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped ?>
					<?php
					if ( isset( $product_tab['callback'] ) ) {
						call_user_func( $product_tab['callback'], $key, $product_tab );
					}
					?>
				</div>
				<?php $panel_count++; ?>
			<?php endforeach; ?>

			<?php do_action( 'woocommerce_product_after_tabs' ); ?>
		</div>
	</div>

<?php endif; ?>
