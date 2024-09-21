<?php
/**
 * Register form.
 *
 * @package          Flatsome\Templates
 * @flatsome-version 3.16.0
 */

if ( $args['form'] ) : ?>
<form action="<?php echo admin_url( 'admin-post.php' ); ?>" method="POST" autocomplete="off" onsubmit="return onFlatsomeRegisterForm(this);">
<?php endif; ?>

	<?php if ( $error ) : ?>
	<div class="notice notice-<?php echo is_string( $error->get_error_code() ) ? 'warning' : 'error'; ?> notice-alt inline" style="display:block!important">
		<?php echo wpautop( $error->get_error_message() ); ?>
	</div>
	<?php elseif ( ! empty( $issues ) ) : ?>
	<div class="notice notice-warning notice-alt inline" style="display:block!important">
		<?php foreach ( $issues as $issue ) : ?>
			<?php echo wpautop( $issue ); ?>
		<?php endforeach; ?>
	</div>
	<?php elseif ( $registration->is_registered() && $registration->is_verified() && $code ) : ?>
	<div class="notice notice-success notice-alt inline" style="display:block!important;margin-bottom:15px!important">
		<p><?php _e( 'Your site is <strong>registered</strong>. Thank you! Enjoy Flatsome and one-click updates.', 'flatsome' ); ?></p>
	</div>
	<?php endif; ?>

	<?php wp_nonce_field( 'flatsome_envato_register', 'flatsome_envato_register_nonce' ); ?>
	<input type="hidden" name="action" value="flatsome_envato_register" />

	<div class="flatsome-registration-form">

		<?php if ( is_a( $registration, 'Flatsome_Envato_Registration' ) ) : ?>
			<div class="wp-clearfix">
				<div class="flatsome-token-migrator__errors"></div>
				<div class="notice notice-info notice-alt inline" style="display:block!important;margin-bottom:15px!important">
					<p><?php _e( 'Your copy of Flatsome is registered with a personal Envato token. Please register Flatsome with a purchase code instead to ensure your site receives updates in the future.', 'flatsome' ); ?></p>
					<p><?php _e( 'The selector below will show available purchase codes you can register with.', 'flatsome' ); ?></p>
					<div class="flatsome-token-migrator__selector" style="display:flex;max-width:500px;margin-bottom:16px;">
						<select name="flatsome_purchase_id" class="flatsome-token-migrator__select" style="width:100%;max-width:none;padding:10px 16px;font-size:16px;">
							<option value="" disabled selected><?php esc_html_e( 'Select a purchase code:', 'flatsome' ); ?></option>
						</select>
						<span class="spinner"></span>
					</div>
				</div>
			</div>
		<?php endif; ?>

		<p class="flatsome-registration-form__code">
			<?php if ( $registration->get_code() ) : ?>
				<input type="text" value="<?php echo esc_attr( $code ); ?>" class="code" style="width:100%;padding:10px 16px;" readonly>
			<?php else : ?>
				<input type="text" id="flatsome_purchase_code" name="flatsome_purchase_code" value="<?php echo esc_attr( $code ); ?>" class="code" placeholder="Purchase code (e.g. 123e4567-e89b-12d3-a456-426614174000)" style="width:100%;padding:10px 16px;">
			<?php endif; ?>
		</p>

		<?php if ( empty( $issues ) && $registration->get_option( 'domain' ) ) : ?>
		<p>
			<?php esc_html_e( 'Registered domain:', 'flatsome' ); ?>
			<b><?php echo esc_html( $registration->get_option( 'domain' ) ); ?></b>
			<?php if ( ! $registration->is_public() ) : ?>
				<code><?php echo esc_html( strtolower( $registration->get_option( 'type' ) ) ); ?></code>
			<?php endif; ?>
		</p>
		<?php endif; ?>

		<?php if ( $args['show_terms'] ) : ?>
			<p>
				<?php if ( $registration->get_code() ) : ?>
					<input type="hidden" name="flatsome_envato_terms" value="1" />
					<input type="checkbox" checked readonly onclick="return false;">
				<?php else : ?>
					<input type="checkbox" <?php checked( $confirmed ); ?> id="flatsome_envato_terms" name="flatsome_envato_terms">
				<?php endif; ?>
				<label for="flatsome_envato_terms" style="display: inline-block;vertical-align: top;width: 90%;margin-top: 2px;font-size: 14px">
					Confirm that, according to the Envato License Terms, each license entitles one person for a single project.
					Creating multiple unregistered installations is a copyright violation.
					<a href="https://themeforest.net/licenses/standard" target="_blank" rel="noopener noreferrer">More info</a>.
				</label>
			</p>
		<?php endif; ?>

		<?php if ( $args['form'] && $args['show_submit'] ) : ?>
		<p>
			<?php if ( $registration->get_code() ) : ?>
				<?php if ( ! empty( $issues ) ) : ?>
					<input name="flatsome_verify" type="submit" class="button button-large button-primary" value="Re-register"/>
					<input name="flatsome_unregister" onclick="return onFlatsomeUnregister()" type="submit" class="button button-large button-secondary" value="Unregister"/>
				<?php elseif ( ! $registration->is_verified() ) : ?>
					<input name="flatsome_verify" type="submit" class="button button-large button-primary" value="Verify purchase code"/>
					<input name="flatsome_unregister" onclick="return onFlatsomeUnregister()" type="submit" class="button button-large button-secondary" value="Unregister"/>
				<?php elseif ( $code ) : ?>
					<input name="flatsome_unregister" onclick="return onFlatsomeUnregister()" type="submit" class="button button-large button-primary" value="Unregister"/>
				<?php else : ?>
					<input name="flatsome_register" type="submit" class="button button-large button-primary" value="Register"/>
				<?php endif; ?>
			<?php else : ?>
				<input name="flatsome_register" type="submit" class="button button-large button-primary" value="Register"/>
			<?php endif; ?>
			<?php if ( is_a( $registration, 'Flatsome_Envato_Registration' ) ) : ?>
				<input name="flatsome_unregister" onclick="return onFlatsomeUnregister()" type="submit" class="button button-large button-secondary" value="Unregister token"/>
			<?php endif; ?>
			<a class="button button-large" href="<?php echo esc_url_raw( UXTHEMES_ACCOUNT_URL ); ?>" target="_blank" rel="noopener noreferrer">
				<?php esc_html_e( 'Manage your licenses', 'flatsome' ); ?>
				<span style="font-size:16px;width:auto;height:auto;vertical-align:middle;" class="dashicons dashicons-external"></span>
			</a>
		</p>
		<?php endif; ?>

	</div>

<?php if ( $args['form'] ) :  ?>
</form>
<?php endif; ?>

<small style="padding-top: 10px; margin-top: 15px; opacity: .8; display: block; border-top: 1px solid #eee;">A purchase code (license) is only valid for <strong>One Domain</strong>. Are you using this theme on a new domain? Purchase a <a href="//bit.ly/buy-flatsome" target="_blank">new license here</a> to get a new purchase code.</small>

<script type="text/javascript">
function onFlatsomeUnregister() {
	if (!confirm("<?php echo wp_slash( __( 'Are you sure you want to unregister Flatsome?', 'flatsome' ) ) ?>")) {
		return false;
	}
}
function onFlatsomeRegisterForm(form){
	<?php if ( ! $registration->is_registered() ) : ?>
	if (!form.flatsome_envato_terms.checked) {
		form.flatsome_envato_terms.parentNode.style.color = "#dc3232";
		return false;
	}
	<?php endif; ?>
	return true;
}
</script>
