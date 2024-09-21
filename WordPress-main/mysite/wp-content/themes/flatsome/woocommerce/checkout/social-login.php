<?php
/**
 * Social login.
 *
 * @package          Flatsome/WooCommerce/Templates
 * @flatsome-version 3.16.0
 */

?>
<div class="text-left social-login pb-half pt-half">
	<?php if ( is_nextend_facebook_login() && get_option( 'woocommerce_enable_myaccount_registration' ) == 'yes' && ! is_user_logged_in() ) :
		$facebook_url = add_query_arg( array( 'loginSocial' => 'facebook' ), wp_login_url() );
		?>

		<a href="<?php echo esc_url( $facebook_url ); ?>" class="button social-button large facebook circle" data-plugin="nsl" data-action="connect" data-redirect="current" data-provider="facebook" data-popupwidth="475" data-popupheight="175">
			<i class="icon-facebook"></i>
			<span><?php _e( 'Login with <strong>Facebook</strong>', 'flatsome' ); ?></span>
		</a>

	<?php endif; ?>

	<?php if ( is_nextend_google_login() && get_option( 'woocommerce_enable_myaccount_registration' ) == 'yes' && ! is_user_logged_in() ) :
		$google_url = add_query_arg( array( 'loginSocial' => 'google' ), wp_login_url() );
		?>

		<a href="<?php echo esc_url( $google_url ); ?>" class="button social-button large google-plus circle" data-plugin="nsl" data-action="connect" data-redirect="current" data-provider="google" data-popupwidth="600" data-popupheight="600">
			<i class="icon-google-plus"></i>
			<span><?php _e( 'Login with <strong>Google</strong>', 'flatsome' ); ?></span>
		</a>

	<?php endif; ?>
</div>
