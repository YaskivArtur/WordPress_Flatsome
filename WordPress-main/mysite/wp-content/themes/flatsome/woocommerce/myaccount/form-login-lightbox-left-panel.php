<?php
/**
 * Login Form - Lightbox Right Pane
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login-lightbox-left-panel.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see              https://docs.woocommerce.com/document/template-structure/
 * @package          Flatsome\Templates
 * @version          7.0.1
 * @flatsome-version 3.16.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$registration              = 'yes' === get_option( 'woocommerce_enable_myaccount_registration' );
$panel_bg_color            = get_theme_mod( 'account_login_lightbox_side_panel_bg_color', Flatsome_Default::COLOR_PRIMARY );
$panel_bg_image            = get_theme_mod( 'account_login_lightbox_side_panel_bg_image' );
$panel_bg_overlay          = get_theme_mod( 'account_login_lightbox_side_panel_bg_overlay' );
$css_panel_bg_args         = array();
$css_panel_bg_overlay_args = array();

if ( $panel_bg_image ) {
	$css_panel_bg_args[] = array(
		'attribute' => 'background-image',
		'value'     => 'url(' . do_shortcode( $panel_bg_image ) . ')',
	);
}
if ( $panel_bg_color ) {
	$css_panel_bg_args[] = array(
		'attribute' => 'background-color',
		'value'     => $panel_bg_color,
	);
}

if ( $panel_bg_overlay ) {
	$css_panel_bg_overlay_args[] = array(
		'attribute' => 'background-color',
		'value'     => $panel_bg_overlay,
	);
}

do_action( 'woocommerce_before_customer_login_form' ); ?>

<div class="account-container">

	<?php if ( $registration ) : ?>

	<div class="col2-set row row-collapse row-large" id="customer_login">

			<?php if ( $block = get_theme_mod( 'account_login_lightbox_side_panel_block' ) ) : ?>
				<div class="col-1 large-6 col">

					<div class="account-register-inner">
						<?php echo flatsome_apply_shortcode( 'block', array( 'id' => $block ) ); ?>
					</div>

				</div>
			<?php else : // Default. ?>
				<div class="col-1 large-6 col flex-row">

					<?php if ( $panel_bg_image || $panel_bg_color ) : ?>
						<div class="account-register-bg fill bg-fill" <?php echo get_shortcode_inline_css( $css_panel_bg_args ); ?>>
							<?php if ( $panel_bg_overlay ) echo '<div class="account-register-bg-overlay fill"' . get_shortcode_inline_css( $css_panel_bg_overlay_args ) . '></div>'; ?>
						</div>
					<?php endif; ?>

					<div class="account-register-inner relative flex-col flex-grow dark text-center">

						<h3 class="uppercase"><?php esc_html_e( 'Register', 'woocommerce' ); ?></h3>
						<p><?php esc_html_e( "Don't have an account? Register one!", 'flatsome' ); ?></p>
						<a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>" class="button white is-outline"><?php esc_html_e( 'Register an Account', 'flatsome' ); ?></a>

					</div>

				</div>
			<?php endif; ?>
	<?php endif; ?>

	<?php if ( $registration ) : ?>

		<div class="col-2 large-6 col">

			<?php endif; ?>

			<div class="account-login-inner inner-padding">

				<h3 class="uppercase"><?php esc_html_e( 'Login', 'woocommerce' ); ?></h3>

				<form class="woocommerce-form woocommerce-form-login login" method="post">

					<?php do_action( 'woocommerce_login_form_start' ); ?>

					<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
						<label for="username"><?php esc_html_e( 'Username or email address', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
						<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
					</p>
					<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
						<label for="password"><?php esc_html_e( 'Password', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
						<input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password" />
					</p>

					<?php do_action( 'woocommerce_login_form' ); ?>

					<p class="form-row">
						<label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
							<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" />
							<span><?php esc_html_e( 'Remember me', 'woocommerce' ); ?></span>
						</label>
						<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
						<button type="submit" class="woocommerce-button button woocommerce-form-login__submit<?php if ( fl_woocommerce_version_check( '7.0.1' ) ) { echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); } ?>" name="login" value="<?php esc_attr_e( 'Log in', 'woocommerce' ); ?>"><?php esc_html_e( 'Log in', 'woocommerce' ); ?></button>
					</p>
					<p class="woocommerce-LostPassword lost_password">
						<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'woocommerce' ); ?></a>
					</p>

					<?php do_action( 'woocommerce_login_form_end' ); ?>

				</form>
			</div>

			<?php if ( $registration ) : ?>
		</div>
	</div>
<?php endif; ?>

</div>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
