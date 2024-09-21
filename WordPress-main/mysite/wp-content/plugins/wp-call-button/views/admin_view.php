<div class="wpcallbtn-container">
	<div id="wpcallbtn-head">
		<h1><?php echo esc_html( $this->plugin_name ); ?></h1>
	</div>
	<div id="wpcallbtn-menu">
		<a <?php echo ( $this->active_view === '' ) ? ' class="current" ' : ''; ?> href="<?php echo admin_url( 'options-general.php?page=' . $this->plugin_slug ); ?>"><?php esc_html_e( 'Sticky Call Button', 'wp-call-button' ); ?></a>
		<a <?php echo ( $this->active_view === 'static-call-button' ) ? ' class="current" ' : ''; ?> href="<?php echo admin_url( 'options-general.php?page=' . $this->plugin_slug . '&view=static-call-button' ); ?>"><?php esc_html_e( 'Static Call Button', 'wp-call-button' ); ?></a>
		<a <?php echo ( $this->active_view === 'about-us' ) ? ' class="current" ' : ''; ?> href="<?php echo admin_url( 'options-general.php?page=' . $this->plugin_slug . '&view=about-us' ); ?>"><?php esc_html_e( 'About Us', 'wp-call-button' ); ?></a>
	</div>
	<div class="wrap wpcallbtn-wrap">
		<div class="notice notice-success is-dismissible"
		<?php
		if ( $saved_state === 'yes' ) {
			echo ' style="display: block;" ';
		} else {
			echo ' style="display: none;" '; }
		?>
		>
		<p><strong><?php esc_html_e( 'Options saved successfully.', 'wp-call-button' ); ?></strong></p>
		<button type="button" class="notice-dismiss">
			<span class="screen-reader-text"><?php esc_html_e( 'Dismiss this notice.', 'wp-call-button' ); ?></span>
		</button>
	</div>
	<?php if ( empty( $settings['wpcallbtn_phone_num'] ) ) : ?>
		<div class="notice notice-warning is-dismissible" style="display: block;">
			<p><strong><?php esc_html_e( 'Your Call Button won\'t show up until you enter a phone number.', 'wp-call-button' ); ?></strong></p>
			<button type="button" class="notice-dismiss">
				<span class="screen-reader-text"><?php esc_html_e( 'Dismiss this notice.', 'wp-call-button' ); ?></span>
			</button>
		</div>
	<?php endif; ?>
	<?php
	// Load the setting views.
	if ( $this->active_view === '' ) :
		require_once __DIR__ . '/admin_view-sticky.php';
	elseif ( $this->active_view === 'static-call-button' ) :
		require_once __DIR__ . '/admin_view-static.php';
	elseif ( $this->active_view === 'about-us' ) :
		require_once __DIR__ . '/admin_view-about.php';
	endif;
	?>
	</div>
</div>
